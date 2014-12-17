(function($)
{
  var app = angular.module('Game', ['ngRoute']);

  var cacheKey = Math.random().toString(36).substring(7);
  
  app.config(['$routeProvider', function($routeProvider)
  {
    // public routes
    $routeProvider.when('/login', {
      templateUrl: '/partial/Login.html#' + cacheKey,
      controller: 'LoginController',
      controllerAs: 'login'
    })

    .when('/', {
      templateUrl: '/partial/Home.html#' + cacheKey,
      controller: 'HomeController',
      controllerAs: 'home'
    })
    .when('/guilds', {
      templateUrl: '/partial/Guilds.html#' + cacheKey,
      controller: 'GuildsController',
      controllerAs: 'gc'
    })
    .when('/guilds/:guildId', {
      templateUrl: '/partial/guilds/Edit.html#' + cacheKey,
      controller: 'GuildEditController',
      controllerAs: 'gc'
    })
    .when('/players', {
      templateUrl: '/partial/Players.html#' + cacheKey,
      controller: 'PlayersController',
      controllerAs: 'pc'
    })
    .when('/players/:playerId', {
      templateUrl: '/partial/players/Edit.html#' + cacheKey,
      controller: 'PlayerEditController',
      controllerAs: 'pc'
    })
    .when('/events', {
      templateUrl: '/partial/Events.html#' + cacheKey,
      controller: 'EventsController',
      controllerAs: 'events'
    })
    .when('/events/transfer-gold', {
      templateUrl: '/partial/events/TransferGold.html#' + cacheKey,
      controller: 'TransferGoldController',
      controllerAs: 'transfer'
    })
    .when('/events/give-xp', {
      templateUrl: '/partial/events/GiveXP.html#' + cacheKey,
      controller: 'GiveXPController',
      controllerAs: 'xp'
    })
    .otherwise({
      redirectTo: '/'
    });
  }]);

  app.controller('AppController', ['gameApi', '$location', function(gameApi, $location)
  {
    var me = this;

    me.menuItems = [];

    me.rebuildMenu = function(account)
    {
      // TODO: Change menu items based on account permissions

      me.menuItems = [
        {
          name: 'Home',
          path: '/'
        },
        {
          name: 'Guilds',
          path: '/guilds'
        },
        {
          name: 'Players',
          path: '/players'
        },
        {
          name: 'Events',
          path: '/events'
        }
      ];
    };

    gameApi.auth.verify().success(function(data)
    {
      me.rebuildMenu(data.params.account);
    })
    .error(function()
    {
      $location.path('/login');
    });
  }]);

  app.controller('HomeController', [function()
  {

  }]);
})(jQuery);
