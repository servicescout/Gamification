<?php

namespace API\Event;

class GiveXP extends \API\Post
{
  public function execute()
  {
    $playerId = $this->getPayloadParameter('playerId');
    $amount = $this->getPayloadParameter('amount');
    $description = $this->getPayloadParameter('description');

    $accrual = new \Model\Event\XPAccrual();
    $accrual->player_id = $playerId;
    $accrual->amount = $amount;
    $accrual->description = $description;
    $accrual->save();
  }
}