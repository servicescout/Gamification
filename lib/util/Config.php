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
    return array_key_exists($name, $this->config)
      ? $this->config[$name]
      : $default;
  }

  public function setValue($name, $value)
  {
    $this->config[$name] = $value;
  }
}