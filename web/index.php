<?php

set_include_path(
  get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . '/../')
);

// composer autoloader
require 'vendor/autoload.php';

// TakeLessons autoloading
require 'autoload.php';
spl_autoload_register('Autoload::autoload');

Autoload::registerDirectory('lib');

require 'config/loader.php';
$config = _loadConfig();

$container = new \Illuminate\Container\Container();
$conFactory = new Illuminate\Database\Connectors\ConnectionFactory($container);
$con = $conFactory->make($config['database']);

$resolver = new Illuminate\Database\ConnectionResolver();
$resolver->addConnection('default', $con);
$resolver->setDefaultConnection('default');

\Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);

$app = new \Slim\Slim(array(
  'log.writer' => new \Log\Syslog(),
  'debug' => false,
));

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
