<?php

namespace Auth;

class Token implements Auth
{
  private $token;

  public function __construct($token)
  {
    $this->token = $token;
  }

  public function verify()
  {
    return true;
  }

  public function getAccount()
  {
    
  }

  public function getSession()
  {
    return Session::get();
  }
}