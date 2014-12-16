<?php

namespace View;

class API extends \Slim\View
{
  public function render($response)
  {
    return json_encode($response->getData());
  }
}