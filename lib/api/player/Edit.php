<?php

namespace API\Player;

class Edit extends \API\Post
{
  private $playerId;

  public function __construct($playerId)
  {
    parent::__construct();

    $this->playerId = $playerId;
  }

  public function execute()
  {
    $player = \Model\Player::find($this->playerId);
    $account = \Model\Account::find($player->account_id);

    $account->email = $this->getPayloadParameter('email');

    $player->name = $this->getPayloadParameter('name');
    $player->guild_id = $this->getPayloadParameter('guild_id');
    $player->character_class = $this->getPayloadParameter('character_class');
    $player->push();

    $this->addParam('player', $player->toArray());
  }
}
