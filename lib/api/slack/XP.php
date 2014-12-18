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
}