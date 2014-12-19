<?php

namespace API\Slack;

class Stats extends Slack
{
  /**
   * 
   * @param \API\Response $response
   */
  protected function execute(&$response)
  {
    $fromPlayer = $this->getAuth()->getAccount()->player;

    if (!$fromPlayer instanceof \Model\Entity\Player)
    {
      $response->setStatus(400);
      $response->setData('Account is not associated with a player');

      return;
    }

    $stats = $fromPlayer->getStatsArray();

    $response->setData(<<<STR
Name: {$fromPlayer->name}
XP: {$stats['xp']}
Level: {$stats['level']}
Gold: {$stats['gold']}
Percent to Next Level: {$stats['nextLevel']['percent']}%
XP Needed for Next Level: {$stats['nextLevel']['xp']}
STR
      );
  }
}