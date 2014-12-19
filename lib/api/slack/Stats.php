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

    $response->setData(json_encode($fromPlayer->getStatsArray()));
    return;
  }
}