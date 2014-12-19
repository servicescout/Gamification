<?php

namespace API\Event;

class GiveXP extends \API\API
{
  private $retriever;

  public function __construct(\Auth\Auth $auth, $payload = null, $retriever = null)
  {
    parent::__construct($auth, $payload);

    $this->retriever = $retriever ?: new \Model\Retriever();
  }

  protected function requirePermissions()
  {
    return array(\Model\Entity\Ref\Permission::TRAINER);
  }

  protected function execute(&$response)
  {
    $playerId = $this->getPayloadParameter('playerId');

    $player = $this->retriever->get('Model\Entity\Player')
      ->find($playerId);

    // starting level
    $start = $player->getCurrentLevel();

    $amount = $this->getPayloadParameter('amount');
    $description = $this->getPayloadParameter('description');

    $accrual = new \Model\Entity\Event\XPAccrual();
    $accrual->player_id = $playerId;
    $accrual->amount = $amount;
    $accrual->description = $description;
    $accrual->save();

    $end = $player->getCurrentLevel();

    if ($start != $end)
    {
      $response->addParam('message', "{$player->name} advanced to level {$end}!");
    }
  }
}