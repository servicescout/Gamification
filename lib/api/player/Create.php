<?php

namespace API\Player;

class Create extends \API\API
{
  private $passwordHasher;

  public function __construct($auth, \Phpass\Hash $passwordHasher = null)
  {
    parent::__construct($auth);

    $this->passwordHasher = $passwordHasher ?: new \Phpass\Hash();
  }

  public function execute(&$response)
  {
    $accountData = $this->getPayloadParameter('account');

    $account = new \Model\Account();
    $account->email = $accountData['email'];
    $account->password_hash = $this->passwordHasher->hashPassword(
      $this->getPayloadParameter('password'));
    $account->save();

    $player = new \Model\Player();
    $player->name = $this->getPayloadParameter('name');
    $player->guild_id = $this->getPayloadParameter('guild_id');
    $player->character_class = $this->getPayloadParameter('character_class');

    $player->account()->associate($account);
    $player->save();

    $response->addParam('player', $player->toArray());
  }
}