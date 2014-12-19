<?php

namespace API\Slack;

class XP extends Slack
{
  private $config;

  public function __construct(\Auth\Auth $auth, $requestArray, \API\Factory $apiFactory = null, \Model\Retriever $retreiver = null, \Util\Config $config = null)
  {
    parent::__construct($auth, $requestArray, $apiFactory, $retreiver);

    $this->config = $config ?: \Util\Config::get();
  }

  /**
   * 
   * @param \API\Response $response
   */
  protected function execute(&$response)
  {
    list($recipient, $amount, $description) = $this->getArgs();

    $playerIds = $this->parseUsername($recipient, $response);

    $responses = array();
    $messages = array();

    foreach ($playerIds as $playerId)
    {
      $payload = json_encode(array(
        'playerId' => $playerId,
        'amount' => $amount,
        'description' => $description,
      ));

      $api = $this->apiFactory->eventGiveXP($this->getAuth(), $payload);
      $responses[] = $api->process();
    }

    foreach ($responses as $item)
    {
      $data = $item->getData();

      if (isset($data['params']) && isset($data['params']['message']))
      {
        $messages[] = $data['params']['message'];
      }

      if ($item->getStatus() !== 200)
      {
        $response->setStatus($item->getStatus());
        $response->setData((isset($data['errors']))
          ? implode("\n", $data['errors'])
          : 'An error occurred');

        return;
      }
    }

    $slackUrl = $this->config->getValue('slack.hookUrl');

    // send the messages so far back into Slack
    if (count($messages) && $slackUrl)
    {
      $session = curl_init();

      $json = json_encode(array(
        'text' => implode("\n", $messages),
      ));

      curl_setopt($session, CURLOPT_URL, $slackUrl);
      curl_setopt($session, CURLOPT_POST, true);
      curl_setopt($session, CURLOPT_POSTFIELDS, $json);
      curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($session, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json),
      ));

      curl_exec($session);
    }

    $messages[] = 'XP awarded successfully!';

    $response->setData(implode("\n", $messages));
  }
}