<?php

namespace API\Slack;

class Game extends Slack
{
  protected function execute(&$response)
  {
    $text = $this->requestData->getValue('text');
    $parts = str_getcsv($text, ' ', '\'');
    $command = reset($parts);

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
    }

    $response->addError('Unrecognized command');
    throw new \Exception\Validation();
  }
}