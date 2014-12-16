<?php

namespace Auth;

class Session
{
  private static $instance;

  public static function get()
  {
    if (is_null(self::$instance))
    {
      self::$instance = new Session();
      session_start();
    }

    return self::$instance;
  }

  public function setValue($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  public function getValue($name)
  {
    // a . signifies a nested item
    $parts = explode('.', $name);
    $firstPart = array_shift($parts);

    $val = isset($_SESSION[$firstPart])
      ? $_SESSION[$firstPart]
      : null;

    if (!is_null($val))
    {
      foreach ($parts as $part)
      {
        if (isset($val[$part]))
        {
          $val = $val[$part];
        }
        else
        {
          $val = null;
          break;
        }
      }
    }

    return $val;
  }
}