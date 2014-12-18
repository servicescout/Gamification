<?php

namespace API\Auth;

class Logout extends \API\API
{
  protected function execute(&$response)
  {
    $this->getAuth()->getSession()->destroy();
  }
}