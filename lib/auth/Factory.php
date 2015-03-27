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
    if (isset($_SERVER['HTTP_AUTHORIZATION']))
    {
      return new Token($_SERVER['HTTP_AUTHORIZATION']);
    }

    return new Web();
  }
}