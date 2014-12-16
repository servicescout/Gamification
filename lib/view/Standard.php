<?php

namespace View;

class Standard extends \Slim\View
{
  public function render($template)
  {
    ob_start();
    require($template);

    $content = ob_get_contents();
    ob_end_clean();

    return $content;
  }
}