<?php

namespace API\Slack;

abstract class Slack extends \API\API
{
  protected $requestData;
  protected $apiFactory;
  protected $retriever;

  public function __construct(\Auth\Auth $auth, $requestArray, \API\Factory $apiFactory = null,
   \Model\Retriever $retreiver = null)
  {
    parent::__construct($auth);

    $requestData = new \Util\Config();

    foreach ($requestArray as $name => $value)
    {
      $requestData->setValue($name, $value);
    }

    $this->requestData = $requestData;
    $this->apiFactory = $apiFactory ?: new \API\Factory();
    $this->retriever = $retreiver ?: new \Model\Retriever();
  }

  public function parseUsername($recipient, &$response)
  {
    // XP for everyone!
    if ($recipient === '*')
    {
      return array_map(function($p)
      {
        return $p->id;
      }, $this->retriever->get('Model\Entity\Player')->all());
    }

    if (preg_match('/^guild:/', $recipient))
    {
      $recipient = preg_replace('/^guild:/', '', $recipient);

      $players = $this->retriever->get('Model\Entity\Player')
        ->join('guild', 'player.guild_id', 'guild.id')
        ->where('guild.username', '=', $recipient)
        ->all();

      if (count($players))
      {
        return array_map(function($p)
        {
          return $p->id;
        }, $players); 
      }

      $response->setData('Could not find guild:' . $recipient);
      throw new \Exception\Validation();
    }

    // simple username entry
    $account = $this->retriever->get('Model\Entity\Account')
      ->where('username', '=', $recipient)->first();

    if ($account instanceof \Model\Entity\Account)
    {
      $player = $account->player;

      if ($player instanceof \Model\Entity\Player)
      {
        return array(
          $player->id,
        );
      }
    }

    $response->setData('Could not find players for:' . $recipient);
    throw new \Exception\Validation();
  }
}