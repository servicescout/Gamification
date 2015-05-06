<?php

namespace Auth;

class Factory
{
  /**
   * 
   * @param \Slim\Slim $app
   * @return \Auth\Auth
   */
  public function fromApp(\Slim\Slim $app)
  {
    // standard Authorization
    if (isset($_SERVER['HTTP_AUTHORIZATION']))
    {
      return new Token($_SERVER['HTTP_AUTHORIZATION']);
    }
    // Apache-specific Authorization behavior (stripped from $_SERVER by default)
    elseif (function_exists('apache_request_headers'))
    {
      $headers = apache_request_headers();

      if (isset($headers['Authorization']))
      {
        return new Token($headers['Authorization']);
      }
    }

    return new Web();
  }
}