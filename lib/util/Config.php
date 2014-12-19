<?php

namespace Util;

class Config
{
  private static $instance;

  /**
   * 
   * @return Config
   */
  public static function get()
  {
    if (is_null(self::$instance))
    {
      self::$instance = new Config();
    }

    return self::$instance;
  }

  private $config = array();

  public function importDirectory($dir)
  {
    $config = array();

    if (file_exists($dir . '/config.php'))
    {
      require_once $dir . '/config.php';
    }

    if (file_exists($dir . '/config.local.php'))
    {
      require_once $dir . '/config.local.php';
    }

    $this->config = $config;
  }

  public function getValue($name, $default = null)
  {
    // a . signifies a nested item
    $parts = explode('.', $name);
    $firstPart = array_shift($parts);

    $val = array_key_exists($firstPart, $this->config)
      ? $this->config[$firstPart]
      : $default;

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
          $val = $default;
          break;
        }
      }
    }

    return $val;
  }

  public function setValue($name, $value)
  {
    $this->config[$name] = $value;
  }
}