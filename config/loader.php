<?php

function _loadConfig()
{
  $config = array();
  require_once 'config.php';

  if (file_exists(__DIR__ . '/config.local.php'))
  {
    require_once 'config.local.php';
  }

  return $config;
}
