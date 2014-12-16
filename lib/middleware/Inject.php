<?php

namespace Middleware;

class Inject extends \Slim\Middleware
{
  const INJECT_CSS = '<!--INJECT_CSS-->';
  const INJECT_JS = '<!--INJECT_JS-->';

  const LEVEL_BASE = 1;
  const LEVEL_TEMPLATE = 2;
  const LEVEL_ACTION = 3;
  const LEVEL_PARTIAL = 4;

  private static $instance;

  /**
   * 
   * @return Inject
   */
  public static function get()
  {
    if (is_null(self::$instance))
    {
      self::$instance = new Inject();
    }

    return self::$instance;
  }

  private $css = array();
  private $js = array();

  public function call()
  {
    $this->getNextMiddleware()->call();

    $response = $this->getApplication()->response();
    $body = $response->getBody();

    $body = $this->injectCSS($body);
    $body = $this->injectJS($body);

    $response->setBody($body);
  }

  public function addCSS($file, $level = self::LEVEL_PARTIAL)
  {
    $this->css[$level][] = $file;
  }

  public function addJS($file, $level = self::LEVEL_PARTIAL)
  {
    $this->js[$level][] = $file;
  }

  private function injectCSS($body)
  {
    $css = '';

    ksort($this->css);

    foreach ($this->css as $level)
    {
      $level = array_unique($level);

      foreach ($level as $file)
      {
        $css .= '<link rel="stylesheet" href="' . $file . '" type="text/css">' . "\n";
      }
    }

    return str_replace(self::INJECT_CSS, $css, $body);
  }

  private function injectJS($body)
  {
    $js = '';

    ksort($this->js);

    foreach ($this->js as $level)
    {
      $level = array_unique($level);

      foreach ($level as $file)
      {
        $js .= '<script src="' . $file . '"></script>' . "\n";
      }
    }

    return str_replace(self::INJECT_JS, $js, $body);
  }
}