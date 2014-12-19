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

    if ($this->countArgs() === 0)
    {
      $response->setData('You have ' . $fromPlayer->getGold() . ' gold!');
      return;
    }

    list($recipient, $amount, $description) = $this->getArgs();

    $playerIds = $this->parseUsername($recipient, $response);

    $responses = array();
    $messages = array();

    foreach ($playerIds as $playerId)
    {
      if ($playerId == $fromPlayer->id)
      {
        continue;
      }

      $payload = json_encode(array(
        'toPlayerId' => $playerId,
        'fromPlayerId' => $fromPlayer->id,
        'amount' => $amount,
        'description' => $description,
      ));

      $api = $this->apiFactory->eventTransferGold($this->getAuth(), $payload);
      $responses[] = $api->process();
    }

    if (count($responses) === 0)
    {
      $response->setData('No action taken');
      return;
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

    $messages[] = 'Gold transferred successfully';

    $response->setData(implode("\n", $messages));
  }
}