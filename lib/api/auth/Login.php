<?php

namespace API\Auth;

class Login extends \API\API
{
  private $hasher;

  public function __construct(\Auth\Auth $auth, Phpass\Hash $hasher = null)
  {
    parent::__construct($auth);

    $this->hasher = $hasher ?: new \Phpass\Hash();
  }

  protected function execute(&$response)
  {
    $email = $this->getPayloadParameter('email');
    $password = $this->getPayloadParameter('password');

    $account = ($email)
      ? \Model\Account::where('email', '=', $email)->first()
      : null;

    if (is_null($account) || !$this->hasher->checkPassword($password, $account->password_hash))
    {
      $response->addError('Invalid email or password');
      throw new \Exception\Validation();
    }

    \Auth\Session::get()->setValue('auth', array(
      'accountId' => $account->id,
    ));

    $response->addParam('account', $account->toArray());
  }
}