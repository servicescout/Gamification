<?php

namespace API\Slack;

class XP extends \API\API
{
  private $requestData;
  private $apiFactory;
  private $retriever;

  public function __construct(\Auth\Auth $auth, $requestArray, \API\Factory $apiFactory = null,
   \Model\Retriever $retreiver = null)
  {
    parent::__construct($auth);

    $requestData = new \Util\Config();

    foreach ($requestArray as $name => $value)
    {
      $requestData->setValue($name, $value);
    }

    $this->requestData = $requestData;
    $this->apiFactory = $apiFactory ?: new \API\Factory();
    $this->retriever = $retreiver ?: new \Model\Retriever();
  }

  /**
   * 
   * @param \API\Response $response
   */
  protected function execute(&$response)
  {
    $text = $this->requestData->getValue('text');

    list($recipient, $amount, $description) = str_getcsv($text, ' ', '\'');

    $playerIds = $this->parseUsername($recipient, $response);

    $responses = array();

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
      if ($item->getStatus() !== 200)
      {
        $response->setStatus($item->getStatus());
        $response->setData('An error occurred');

        return;
      }
    }

    $response->setData('XP Awarded Successfully');
  }

  private function parseUsername($recipient, &$response)
  {
    // simple username entry
    $account = $this->retriever->get('Model\Entity\Account')
      ->where('username', '=', $recipient)->first();

    if ($account instanceof \Model\Entity\Account)
    {
      $player = $account->player;

      if (!($player instanceof \Model\Entity\Player))
      {
        $response->setData('Could not find players for:' . $recipient);
        throw new \Exception\Validation();
      }

      return array(
        $player->id,
      );
    }

    $response->setData('Could not find players for:' . $recipient);
    throw new \Exception\Validation();
  }
}