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
    return new Web();
  }
}