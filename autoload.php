<?php

class Autoload
{
  private static $instance;

  private $map = array();

  /**
   * Recursively scan the given directory adding all
   * PHP classes to the autoloader
   */
  public static function registerDirectory($path)
  {
    if (substr($path, 0, 1) !== '/')
    {
      $path = __DIR__ . "/{$path}";
    }

    $auto = self::getInstance();

    foreach (self::getFiles($path) as $file)
    {
      foreach (self::getPHPClasses($file) as $class)
      {
        $auto->map[$class] = $file;
      }
    }
  }

  public static function autoload($name)
  {
    $auto = self::getInstance();

    if (array_key_exists($name, $auto->map))
    {
      require_once($auto->map[$name]);
    }
  }

  private function __construct() {}
  private static function getInstance()
  {
    if (!(self::$instance instanceof Autoload))
    {
      self::$instance = new Autoload();
    }

    return self::$instance;
  }

  public static function getFiles($path, $pattern = '*.php')
  {
    return self::rglob($pattern, 0, $path);
  }

  private static function getPHPClasses($file)
  {
    $code = file_get_contents($file);
    $classes = array();
    $tokens = token_get_all($code);
    $count = count($tokens);
    $namespace = '';

    for ($i = 2; $i < $count; $i++)
    {
      if ($tokens[$i - 2][0] == T_NAMESPACE
        && $tokens[$i - 1][0] == T_WHITESPACE
        && $tokens[$i][0] == T_STRING)
      {
        $namespace = $tokens[$i][1] . '\\';

        $ii = $i;
        while (isset($tokens[$ii + 1][1]) && $tokens[$ii + 1][1] == '\\')
        {
          $namespace .= $tokens[$ii + 2][1] . '\\';
          $ii += 2;
        }
      }

      if (($tokens[$i - 2][0] == T_CLASS || $tokens[$i - 2][0] == T_INTERFACE)
        && $tokens[$i - 1][0] == T_WHITESPACE
        && $tokens[$i][0] == T_STRING)
      {
        $class_name = $namespace . $tokens[$i][1];
        $classes[] = $class_name;
      }
    }

    return $classes;
  }

  private static function rglob($pattern, $flags = 0, $path = '')
  {
    $paths = glob($path . '*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files = glob($path . $pattern, $flags);

    foreach ($paths as $path)
    {
      $files = array_merge($files, self::rglob($pattern, $flags, $path));
    }

    return $files;
  }
}
