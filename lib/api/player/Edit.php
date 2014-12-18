<?php

namespace API\Player;

class Edit extends \API\API
{
  private $playerId;

  public function __construct($auth, $playerId)
  {
    parent::__construct($auth);

    $this->playerId = $playerId;
  }

  public function execute(&$response)
  {
    $player = \Model\Entity\Player::find($this->playerId);
    $account = \Model\Entity\Account::find($player->account_id);

    $account->email = $this->getPayloadParameter('email');

    $player->name = $this->getPayloadParameter('name');
    $player->guild_id = $this->getPayloadParameter('guild_id');
    $player->character_class = $this->getPayloadParameter('character_class');
    $player->push();

    $response->addParam('player', $player->toArray());
  }
}
