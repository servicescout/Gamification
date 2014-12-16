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

$app->get('/api/auth/verify', function() use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Auth\Verify($factory->fromApp($app)));
});

$app->post('/api/auth/login', function() use ($app)
{
  $app->processAPI(new API\Auth\Login(new Auth\PublicAccess()));
});

$app->get('/api/auth/logout', function() use ($app)
{
  $app->processAPI(new API\Auth\Logout(new Auth\PublicAccess()));
});

$app->get('/api/guild/list', function() use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Guild\ListAPI($factory->fromApp($app)));
});

$app->get('/api/guild/get/:guildId', function($guildId) use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Guild\Get($factory->fromApp($app), $guildId));
});

$app->post('/api/guild/edit/:guildId', function($guildId) use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Guild\Edit($factory->fromApp($app), $guildId));
});

$app->post('/api/guild/create', function() use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Guild\Create($factory->fromApp($app)));
});

$app->get('/api/player/list', function() use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Player\ListAPI($factory->fromApp($app)));
});

$app->get('/api/player/get/:playerId', function($playerId) use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Player\Get($factory->fromApp($app), $playerId));
});

$app->post('/api/player/edit/:playerId', function($playerId) use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Player\Edit($factory->fromApp($app), $playerId));
});

$app->post('/api/player/create', function() use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Player\Create($factory->fromApp($app)));
});

$app->get('/api/event/recent', function() use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Event\Recent($factory->fromApp($app)));
});

$app->get('/api/event/missed', function() use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Event\Missed($factory->fromApp($app)));
});

$app->post('/api/event/giveXP', function() use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Event\GiveXP($factory->fromApp($app)));
});

$app->post('/api/event/transferGold', function() use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\Event\TransferGold($factory->fromApp($app)));
});

$app->get('/api/event/levelAnnounce', function() use ($app)
{
  $app->processAPI(new API\Event\LevelAnnounce(new \Auth\PublicAccess()));
});

$app->get('/api/characterClass/list', function() use ($app)
{
  $factory = new Auth\Factory();
  $app->processAPI(new API\CharacterClass\ListAPI($factory->fromApp($app)));
});