<?php

namespace API\Event;

class GiveXP extends \API\API
{
  protected function requirePermissions()
  {
    return array(\Model\Entity\Ref\Permission::TRAINER);
  }

  protected function execute(&$response)
  {
    $playerId = $this->getPayloadParameter('playerId');
    $amount = $this->getPayloadParameter('amount');
    $description = $this->getPayloadParameter('description');

    $accrual = new \Model\Entity\Event\XPAccrual();
    $accrual->player_id = $playerId;
    $accrual->amount = $amount;
    $accrual->description = $description;
    $accrual->save();
  }
}