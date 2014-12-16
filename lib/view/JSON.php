<?php

namespace View;

class JSON extends \Slim\View
{
  public function render($void)
  {
    return json_encode(array(
      'content' => $this->get('content'),
      'params' => $this->get('params') ?: array(),
      'messages' => $this->get('messages') ?: array(),
    ));
  }
}