<?php

namespace API\Slack;

class Game extends Slack
{
  protected function execute(&$response)
  {
    $args = $this->getArgs(true);
    $command = reset($args);

    switch ($command)
    {
      case 'xp':
        $api = new XP($this->getAuth(), $this->requestData->all());
        $api->process($response);

        return;
      case 'gold':
        $api = new Gold($this->getAuth(), $this->requestData->all());
        $api->process($response);

        return;
      case 'bank':
        $api = new Bank($this->getAuth(), $this->requestData->all());
        $api->process($response);

        return;
      case 'stats':
        $api = new Stats($this->getAuth(), $this->requestData->all());
        $api->process($response);

        return;
      case 'help':
        $api = new Help($this->getAuth(), $this->requestData->all());
        $api->process($response);

        return;
    }

    $response->setData('Unrecognized command: ' . $command);
  }
}