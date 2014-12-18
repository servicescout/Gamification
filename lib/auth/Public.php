<?php

namespace Auth;

class PublicAccess implements Auth
{
  private $session;

  /**
   * 
   * @param \Auth\Session $session
   */
  public function __construct(Session $session = null)
  {
    $this->session = $session ?: Session::get();
  }

  public function verify()
  {
    return true;
  }

  public function getAccount()
  {
    return null;
  }

  public function getSession()
  {
    return $this->session;
  }
}