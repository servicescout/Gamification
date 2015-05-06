<?php

namespace API\Event;

class TransferGold extends \API\API
{
  private $retriever;

  protected function validate()
  {
    $errors = array();
    $amount = $this->getPayloadParameter('amount');

    if ((!is_int($amount) && !ctype_digit($amount)) || $amount <= 0)
    {
      $errors[] = 'Amount is invalid';
    }

    $allowBanker = (is_null($this->getPayloadParameter('toPlayerId'))
      || is_null($this->getPayloadParameter('fromPlayerId')));

    $account = $this->getAuth()->getAccount();

    // banker can transfer gold as he/she pleases
    if (!$allowBanker || !$account->hasPermission(\Model\Entity\Ref\Permission::BANKER))
    {
      $player = $account->player;

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

    $this->retriever = new \Model\Retriever();
    $toPlayer = ($toPlayerId) ? $this->retriever->get('Model\Entity\Player')
      ->find($toPlayerId) : null;
    $fromPlayer = ($fromPlayerId) ? $this->retriever->get('Model\Entity\Player')
      ->find($fromPlayerId) : null;

    $transfer = new \Model\Entity\Event\GoldTransfer();
    $transfer->to_player_id = $toPlayerId;
    $transfer->from_player_id = $fromPlayerId;
    $transfer->amount = $amount;
    $transfer->description = $description;
    $transfer->save();

    $message = null;

    if ($toPlayer && $fromPlayer)
    {
      $message = "{$fromPlayer->name} gave {$toPlayer->name} {$amount} gold";
    }
    elseif ($toPlayer)
    {
      $message = "{$toPlayer->name} received {$amount} gold";
    }

    if ($description)
    {
      $message .= ": '{$description}'";
    }

    if ($message)
    {
      $response->addParam('message', $message);
    }
  }
}