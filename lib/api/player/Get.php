<?php

namespace API\Player;

class Get extends \API\API
{
  private $playerId;

  public function __construct($auth, $playerId)
  {
    parent::__construct($auth);

    $this->playerId = $playerId;
  }

  public function execute(&$response)
  {
    $player = \Model\Entity\Player::find($this->playerId)->toArray();

    $response->addParam('player', $player);
  }
}