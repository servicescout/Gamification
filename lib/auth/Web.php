<?php

namespace Auth;

class Web implements Auth
{
  private $session;
  private $retriever;

  /**
   * 
   * @param \Auth\Session $session
   * @param \Model\Retriever $retriever
   */
  public function __construct(Session $session = null,
    \Model\Retriever $retriever = null)
  {
    $this->session = $session ?: Session::get();
    $this->retriever = $retriever ?: new \Model\Retriever();
  }

  public function verify()
  {
    return !is_null($this->getAccount());
  }

  public function getAccount()
  {
    // use the web session only
    $accountId = $this->session->getValue('auth.accountId');

    return ($accountId)
      ? $this->retriever->get('\Model\Entity\Account')->find($accountId)
      : null;
  }

  public function getSession()
  {
    return $this->session;
  }
}