<?php

namespace API;

abstract class API
{
  private $auth;
  private $payloadData;

  public function __construct(\Auth\Auth $auth, $payload = null)
  {
    $this->auth = $auth;
    $this->payloadData = json_decode($payload ?: file_get_contents('php://input'), true);
  }

  /**
   * @param Response
   */
  abstract protected function execute(&$response);

  protected function validate()
  {
    return array();
  }

  protected function requirePermissions()
  {
    return array();
  }

  public function process($response = null)
  {
    $response = $response ?: new Response();

    try
    {
      if (!$this->auth->verify())
      {
        $response->addError('Invalid credentials');
        throw new \Exception\Auth();
      }

      if (count($this->requirePermissions()))
      {
        foreach ($this->requirePermissions() as $permission)
        {
          $account = $this->auth->getAccount();

          if (is_null($account) || !$account->hasPermission($permission))
          {
            $response->addError('Lacking permissions');
            throw new \Exception\Validation();
          }
        }
      }

      $errors = $this->validate();

      if (count($errors))
      {
        foreach ($errors as $error)
        {
          $response->addError($error);
        }

        throw new \Exception\Validation();
      }

      $this->execute($response);
    }
    catch (\Exception\Auth $ex)
    {
      $response->setStatus(401);
    }
    catch (\Exception\Validation $ex)
    {
      $response->setStatus(400);
    }
    catch (\Exception $ex)
    {
      $response->addError($ex->getMessage());
      $response->setStatus(500);
    }

    return $response;
  }

  protected function getAuth()
  {
    return $this->auth;
  }

  protected function getPayloadParameter($name, $default = null)
  {
    if (!is_array($this->payloadData))
    {
      return $default;
    }

    return (array_key_exists($name, $this->payloadData))
      ? $this->payloadData[$name]
      : $default;
  }
}