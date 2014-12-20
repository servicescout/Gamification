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
```
Usage: /game COMMAND [ARGS]

The most commonly used game commands are:
      gold      Transfer gold to another player or view your own gold total
      stats     View stats for your player
      xp        Award xp to another player
      bank      Transfer money (or take money away) from a player to the bank

Tip: You can use "guild:guildname" or "*" to indicate multiple recipients

Command usage:
      gold (transfer)   /game gold [recipient] [amount] [description]
      gold (view)       /game gold
      stats             /game stats
      xp                /game xp [recipient] [amount] [description]
      bank              /game bank [recipient] [amount] [description]

Command permissions:
      gold              None
      stats             None
      xp                Trainer
      bank              Banker

Examples:
      /game gold tstella 10 'Thanks for fixing my machine!'
      /game stats
      /game xp bklima 200 'Fantastic demo!'
      /game bank pdetagyos -20 'Service fees'
```
STR
      );
  }
}