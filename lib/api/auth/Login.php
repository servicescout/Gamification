<?php

namespace API\Auth;

class Login extends \API\API
{
  private $hasher;
  private $retriever;

  public function __construct(\Auth\Auth $auth, \Phpass\Hash $hasher = null,
   \Model\Retriever $retriever = null, $payload = null)
  {
    parent::__construct($auth, $payload);

    $this->hasher = $hasher ?: new \Phpass\Hash();
    $this->retriever = $retriever ?: new \Model\Retriever();
  }

  protected function execute(&$response)
  {
    $email = $this->getPayloadParameter('email');
    $password = $this->getPayloadParameter('password');

    $account = ($email)
      ? $this->retriever->get('Model\Entity\Account')->where('email', '=', $email)->first()
      : null;

    if (is_null($account) || !$this->hasher->checkPassword($password, $account->password_hash))
    {
      $response->addError('Invalid email or password');
      throw new \Exception\Validation();
    }

    $this->getAuth()->getSession()->setValue('auth', array(
      'accountId' => $account->id,
    ));

    $response->addParam('account', $account->toArray());
  }
}