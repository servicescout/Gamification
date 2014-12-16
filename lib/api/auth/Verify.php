<?php

namespace API\Auth;

class Verify extends \API\API
{
  protected function execute(&$response)
  {
    $response->addParam('account', $this->getAuth()->getAccount()->toArray());
  }
}