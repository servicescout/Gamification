<?php

namespace Auth;

class PublicAccess implements Auth
{
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
    return Session::get();
  }
}