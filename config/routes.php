<?php

$app->get('/', function()
{
  require_once('template/Layout.html');
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