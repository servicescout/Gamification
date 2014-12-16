<?php

$basePath = realpath(__DIR__ . '/../');

set_include_path(
  get_include_path() . PATH_SEPARATOR . $basePath
);

// composer autoloader
require 'vendor/autoload.php';

// TakeLessons autoloading
require 'autoload.php';
spl_autoload_register('Autoload::autoload');

Autoload::registerDirectory('lib');

$configInstance = \Util\Config::get();
$configInstance->importDirectory($basePath . '/config');
$configInstance->setValue('basePath', $basePath);

$container = new \Illuminate\Container\Container();
$conFactory = new Illuminate\Database\Connectors\ConnectionFactory($container);
$con = $conFactory->make($configInstance->getValue('database'));

$resolver = new Illuminate\Database\ConnectionResolver();
$resolver->addConnection('default', $con);
$resolver->setDefaultConnection('default');

\Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);

$app = new \Game\Slim(array(
  'log.writer' => new \Log\Syslog(),
  'debug' => false,
));

$app->add(\Middleware\Inject::get());
$app->view('View\JSON');

$app->error(function (Exception $ex) use ($app)
{
  $app->log->error($ex);
  $app->render(null, array(
    'messages' => array(
      'errors' => array(
        'type' => get_class($ex),
        'code' => $ex->getCode(),
        'message' => $ex->getMessage(),
      ),
    ),
  ));
});

$app->notFound(function () use ($app)
{
  $app->render(null, array(
    'content' => '404 Not Found',
  ));
});

require_once('config/routes.php');

$app->run();
