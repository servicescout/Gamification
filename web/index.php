<?php

require_once realpath(__DIR__ . '/../bootstrap.php');
require_once 'bootstrapDb.php';

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
