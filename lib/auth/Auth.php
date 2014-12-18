<?php

namespace Auth;

interface Auth
{
  public function verify();

  /**
   * \Model\Entity\Account
   */
  public function getAccount();

  /**
   * \Auth\Session
   */
  public function getSession();
}