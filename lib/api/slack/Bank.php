<?php

namespace API\Slack;

class Bank extends Slack
{
  protected function requirePermissions()
  {
    return array(\Model\Entity\Ref\Permission::BANKER);
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
    $messages = array();

    $toTheBank = ($amount < 0);

    foreach ($playerIds as $playerId)
    {
      $payload = json_encode(array(
        'toPlayerId' => (!$toTheBank) ? $playerId : null,
        'fromPlayerId' => ($toTheBank) ? $playerId : null,
        'amount' => abs($amount),
        'description' => $description,
      ));

      $api = $this->apiFactory->eventTransferGold($this->getAuth(), $payload);
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
        $response->setData('An error occurred');

        return;
      }
    }

    $messages[] = 'Gold transferred Successfully';

    $response->setData(implode(', ', $messages));
  }
}