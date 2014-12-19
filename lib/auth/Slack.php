<?php

namespace Auth;

class Slack implements Auth
{
  private $requestData;
  private $config;
  private $retriever;

  public function __construct(array $requestArray, \Util\Config $config,
   \Model\Retriever $retriever = null)
  {
    $requestData = new \Util\Config();

    foreach ($requestArray as $name => $value)
    {
      $requestData->setValue($name, $value);
    }

    $this->requestData = $requestData;
    $this->config = $config;
    $this->retriever = $retriever ?: new \Model\Retriever();
  }

  public function verify()
  {
    return !is_null($this->getAccount());
  }

  public function getAccount()
  {
    $slackConfig = $this->config->getValue('slack');

    if ($slackConfig['teamId'] != $this->requestData->getValue('team_id'))
    {
      return null;
    }

    // find an account for the username
    return $this->retriever->get('Model\Entity\Account')
      ->where('username', '=', $this->requestData->getValue('user_name'))->first();
  }

  /**
   * Slack authentication does not carry a session
   * 
   * @return null
   */
  public function getSession()
  {
    return null;
  }
}