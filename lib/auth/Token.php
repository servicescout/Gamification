<?php

namespace Auth;

class Token implements Auth
{
  private $token;
  private $retriever;

  /**
   *
   * @param string $token
   * @param \Auth\Session $session
   * @param \Model\Retriever $retriever
   */
  public function __construct($token, Session $session = null,
    \Model\Retriever $retriever = null)
  {
    $this->token = $token;
    $this->session = $session ?: Session::get();
    $this->retriever = $retriever ?: new \Model\Retriever();
  }

  public function verify()
  {
    return !is_null($this->getAccount());
  }

  public function getAccount()
  {
    return $this->retriever
      ->get('\Model\Entity\Account')
      ->where('api_token', '=', $this->token)
      ->first();
  }

  public function getSession()
  {
    return $this->session;
  }
}