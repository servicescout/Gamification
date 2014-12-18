<?php

namespace API;

class Factory
{
  public function eventGiveXP(\Auth\Auth $auth, $payload)
  {
    return new Event\GiveXP($auth, $payload);
  }
}