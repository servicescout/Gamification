<?php

namespace API\Event;

class TransferGold extends \API\API
{
  public function execute(&$response)
  {
    $toPlayerId = $this->getPayloadParameter('toPlayerId');
    $fromPlayerId = $this->getPayloadParameter('fromPlayerId');
    $amount = $this->getPayloadParameter('amount');
    $description = $this->getPayloadParameter('description');

    $transfer = new \Model\Event\GoldTransfer();
    $transfer->to_player_id = $toPlayerId;
    $transfer->from_player_id = $fromPlayerId;
    $transfer->amount = $amount;
    $transfer->description = $description;
    $transfer->save();
  }
}