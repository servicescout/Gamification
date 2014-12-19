<?php

namespace API\Slack;

class Gold extends Slack
{
  /**
   * 
   * @param \API\Response $response
   */
  protected function execute(&$response)
  {
    $fromPlayer = $this->getAuth()->getAccount()->player;

    if (!$fromPlayer instanceof \Model\Entity\Player)
    {
      $response->setStatus(400);
      $response->setData('Account is not associated with a player');

      return;
    }

    $text = $this->requestData->getValue('text');

    if (trim($text) === 'gold')
    {
      $response->setData($fromPlayer->getGold());
      return;
    }

    list($command, $recipient, $amount, $description) = str_getcsv($text, ' ', '\'');

    $playerIds = $this->parseUsername($recipient, $response);

    $responses = array();
    $messages = array();


    foreach ($playerIds as $playerId)
    {
      $payload = json_encode(array(
        'toPlayerId' => $playerId,
        'fromPlayerId' => $fromPlayer->id,
        'amount' => $amount,
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
        $response->setData((isset($data['errors']))
          ? implode(', ', $data['errors'])
          : 'An error occurred');

        return;
      }
    }

    $messages[] = 'Gold transferred Successfully';

    $response->setData(implode(', ', $messages));
  }
}