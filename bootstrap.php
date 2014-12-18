<?php

date_default_timezone_set('America/Los_Angeles');

set_include_path(
  get_include_path() . PATH_SEPARATOR . __DIR__
);

// composer autoloader
require __DIR__ . '/vendor/autoload.php';

// TakeLessons autoloading
require __DIR__ . '/autoload.php';
spl_autoload_register('Autoload::autoload');

Autoload::registerDirectory('lib');

$configInstance = \Util\Config::get();
$configInstance->importDirectory(__DIR__ . '/config');
$configInstance->setValue('basePath', __DIR__);