<?php

namespace API\Slack;

class XP extends Slack
{
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
          ? implode(', ', $data['errors'])
          : 'An error occurred');

        return;
      }
    }

    $messages[] = 'XP Awarded Successfully';

    $response->setData(implode(', ', $messages));
  }
}