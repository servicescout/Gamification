(function($)
{
  var app = angular.module('Game');

  app.controller('PlayerEditController', ['gameApi', '$routeParams', '$location',
    function(gameApi, $routeParams, $location)
  {
    var me = this;

    me.player = {};
    me.newPlayer = ($routeParams.playerId === 'new');
    me.guilds = [];
    me.characterClasses = [];

    if (!me.newPlayer)
    {
      gameApi.player.get($routeParams.playerId).success(function(data)
      {
        me.player = data.params.player;
      });
    }

    me.save = function()
    {
      if (me.newPlayer)
      {
        gameApi.player.create(me.player).success(function()
        {
          $location.path('/players');
        });
      }
      else
      {
        gameApi.player.edit(me.player.id, me.player).success(function()
        {
          $location.path('/players');
        });
      }
    };

    gameApi.guild.list().success(function(data)
    {
      me.guilds = data.params.guilds;
    });

    gameApi.characterClass.list().success(function(data)
    {
      me.characterClasses = data.params.classes;
    });
  }]);
})(jQuery);
