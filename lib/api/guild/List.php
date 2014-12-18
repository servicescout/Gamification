<?php

namespace API\Guild;

class ListAPI extends \API\API
{
  public function execute(&$response)
  {
    $guilds = \Model\Entity\Guild::all()->toArray();
    $players = \Model\Entity\Player::all()->toArray();

    usort($players, function($a, $b)
    {
      if ($a['xp'] === $b['xp'])
      {
        return 0;
      }

      return ($a['xp'] < $b['xp']) ? 1 : - 1;
    });

    foreach ($guilds as $idx => $guild)
    {
      $guilds[$idx]['players'] = array();

      foreach ($players as $player)
      {
        if ($player['guild_id'] === $guild['id'])
        {
          $guilds[$idx]['players'][] = $player;
        }
      }
    }

    $response->addParam('guilds', $guilds);
  }
}