<?php

namespace Auth;

class Web implements Auth
{
  public function verify()
  {
    return !is_null($this->getAccount());
  }

  public function getAccount()
  {
    // use the web session only
    $accountId = $this->getSession()->getValue('auth.accountId');

    return ($accountId)
      ? \Model\Entity\Account::find($accountId)
      : null;
  }

  public function getSession()
  {
    return Session::get();
  }
}