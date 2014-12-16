<?php

namespace API\Player;

class Get extends \API\API
{
  private $playerId;

  public function __construct($playerId)
  {
    $this->playerId = $playerId;
  }

  public function execute()
  {
    $player = \Model\Player::find($this->playerId)->toArray();

    $this->addParam('player', $player);
  }
}