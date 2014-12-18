<?php

namespace API\Event;

class TransferGold extends \API\API
{
  protected function validate()
  {
    $errors = array();
    $amount = $this->getPayloadParameter('amount');

    if ((!is_int($amount) && !ctype_digit($amount)) || $amount <= 0)
    {
      $errors[] = 'Amount is invalid';
    }

    $account = $this->getAuth()->getAccount();

    // banker can transfer gold as he/she pleases
    if (!$account->hasPermission(\Model\Entity\Ref\Permission::BANKER))
    {
      $player = $account->getPlayer();

      if (is_null($player) || ($player->id != $this->getPayloadParameter('fromPlayerId')))
      {
        $errors[] = 'Player can only transfer gold from his/her own account';
      }

      if (!is_null($player) && $amount > $player->getGold())
      {
        $errors[] = 'Player does not have enough gold to transfer';
      }
    }

    return $errors;
  }

  protected function execute(&$response)
  {
    $toPlayerId = $this->getPayloadParameter('toPlayerId');
    $fromPlayerId = $this->getPayloadParameter('fromPlayerId');
    $amount = $this->getPayloadParameter('amount');
    $description = $this->getPayloadParameter('description');

    $transfer = new \Model\Entity\Event\GoldTransfer();
    $transfer->to_player_id = $toPlayerId;
    $transfer->from_player_id = $fromPlayerId;
    $transfer->amount = $amount;
    $transfer->description = $description;
    $transfer->save();
  }
}