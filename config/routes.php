<?php

$app->get('/', function() use ($app)
{
  $app->view('View\Standard');

  $inject = \Middleware\Inject::get();

  $inject->addCSS('//fonts.googleapis.com/css?family=VT323', \Middleware\Inject::LEVEL_BASE);
  $inject->addCSS('/css/App.css', \Middleware\Inject::LEVEL_TEMPLATE);
  $inject->addCSS('/css/MainContent.css', \Middleware\Inject::LEVEL_TEMPLATE);

  // grab CSS partial files
  foreach (Autoload::getFiles(realpath(__DIR__ . '/../web/css/partial'), '*.css') as $file)
  {
    $file = preg_replace('#.*?/web/css/#', '/css/', $file);
    $inject->addCSS($file, \Middleware\Inject::LEVEL_PARTIAL);
  }

  $inject->addJS('//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', \Middleware\Inject::LEVEL_BASE);
  $inject->addJS('//ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular.min.js', \Middleware\Inject::LEVEL_BASE);
  $inject->addJS('//ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular-route.min.js', \Middleware\Inject::LEVEL_BASE);
  $inject->addJS('/js/App.js', \Middleware\Inject::LEVEL_TEMPLATE);
  $inject->addJS('/js/service/Api.js', \Middleware\Inject::LEVEL_TEMPLATE);

  // grab js controllers
  foreach (Autoload::getFiles(realpath(__DIR__ . '/../web/js/controller'), '*.js') as $file)
  {
    $file = preg_replace('#.*?/web/js/#', '/js/', $file);
    $inject->addJS($file, \Middleware\Inject::LEVEL_ACTION);
  }

  $app->render('template/Layout.html');
})->name('home');

$app->get('/api/guild/list', function() use ($app)
{
  $api = new API\Guild\ListAPI();
  $api->execute();

  $app->render(null, $api->getData());
});

$app->get('/api/guild/get/:guildId', function($guildId) use ($app)
{
  $api = new API\Guild\Get($guildId);
  $api->execute();

  $app->render(null, $api->getData());
});

$app->post('/api/guild/edit/:guildId', function($guildId) use ($app)
{
  $api = new API\Guild\Edit($guildId);
  $api->execute();

  $app->render(null, $api->getData());
});

$app->post('/api/guild/create', function() use ($app)
{
  $api = new API\Guild\Create();
  $api->execute();

  $app->render(null, $api->getData());
});

$app->get('/api/player/list', function() use ($app)
{
  $api = new API\Player\ListAPI();
  $api->execute();

  $app->render(null, $api->getData());
});

$app->get('/api/player/get/:playerId', function($playerId) use ($app)
{
  $api = new API\Player\Get($playerId);
  $api->execute();

  $app->render(null, $api->getData());
});

$app->post('/api/player/edit/:playerId', function($playerId) use ($app)
{
  $api = new API\Player\Edit($playerId);
  $api->execute();

  $app->render(null, $api->getData());
});

$app->post('/api/player/create', function() use ($app)
{
  $api = new API\Player\Create();
  $api->execute();

  $app->render(null, $api->getData());
});

$app->get('/api/event/recent', function() use ($app)
{
  $api = new API\Event\Recent();
  $api->execute();

  $app->render(null, $api->getData());
});

$app->get('/api/event/missed', function() use ($app)
{
  $api = new API\Event\Missed();
  $api->execute();

  $app->render(null, $api->getData());
});

$app->post('/api/event/giveXP', function() use ($app)
{
  $api = new API\Event\GiveXP();
  $api->execute();

  $app->render(null, $api->getData());
});

$app->post('/api/event/transferGold', function() use ($app)
{
  $api = new API\Event\TransferGold();
  $api->execute();

  $app->render(null, $api->getData());
});

$app->get('/api/characterClass/list', function() use ($app)
{
  $api = new API\CharacterClass\ListAPI();
  $api->execute();

  $app->render(null, $api->getData());
});