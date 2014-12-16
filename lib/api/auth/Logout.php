<?php

namespace API\Auth;

class Logout extends \API\API
{
  protected function execute(&$response)
  {
    \Auth\Session::get()->setValue('auth', null);
  }
}