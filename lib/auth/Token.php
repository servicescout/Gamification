<?php

namespace Auth;

class Token implements Auth
{
  private $token;

  public function __construct($token, Session $session = null)
  {
    $this->token = $token;
    $this->session = $session ?: Session::get();
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
    return $this->session;
  }
}