<?php

namespace Game;

class Slim extends \Slim\Slim
{
  public function processAPI(\API\API $api)
  {
    $response = $api->process();

    $this->view('View\API');
    $this->status($response->getStatus());
    $this->render($response);
  }
}