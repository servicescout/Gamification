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
    $headers = apache_request_headers();

    if (isset($headers['Authorization']))
    {
      return new Token($headers['Authorization']);
    }

    return new Web();
  }
}