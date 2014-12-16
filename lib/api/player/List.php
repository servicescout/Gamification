<?php

namespace API\Player;

class ListAPI extends \API\API
{
  public function execute()
  {
    $players = \Model\Player::all()->toArray();

    usort($players, function($a, $b)
    {
      if ($a['xp'] === $b['xp'])
      {
        return 0;
      }

      return ($a['xp'] < $b['xp']) ? 1 : - 1;
    });

    $this->addParam('players', $players);
  }
}