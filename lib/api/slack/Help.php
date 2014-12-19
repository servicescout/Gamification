<?php

namespace API\Slack;

class Help extends Slack
{
  /**
   * 
   * @param \API\Response $response
   */
  protected function execute(&$response)
  {
    $response->setData(<<<STR

Commands
--------

XP - Award xp to another player
Permissions required: Trainer

Usage:
/game xp recipient amount description

Example:
/game xp bklima 200 'Fantastic demo!'

GOLD - Transfer gold to another player or view your own gold total
Permissions required: None

Usage:
/game gold
/game gold recipient amount description

Example:
/game gold tstella 10 'Thanks for fixing my machine!'

STATS - View stats for your player
Permissions required: None

Usage:
/game stats

BANK - Transfer money (or take money away) from a player to the bank
Permissions required: Banker

Usage:
/game bank recipient amount description

Example:
/game bank pdetagyos -20 'Service fees'

STR
      );
  }
}