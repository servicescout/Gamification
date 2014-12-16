(function($)
{
  var app = angular.module('Game');

  app.controller('GuildEditController', ['gameApi', '$routeParams', '$location',
    function(gameApi, $routeParams, $location)
  {
    var me = this;

    me.guild = {};
    me.newGuild = ($routeParams.guildId === 'new');

    if (!me.newGuild)
    {
      gameApi.guild.get($routeParams.guildId).success(function(data)
      {
        me.guild = data.params.guild;
      });
    }

    me.save = function()
    {
      if (me.newGuild)
      {
        gameApi.guild.create(me.guild).success(function()
        {
          $location.path('/guilds');
        });
      }
      else
      {
        gameApi.guild.edit(me.guild.id, me.guild).success(function()
        {
          $location.path('/guilds');
        });
      }
    };
  }]);
})(jQuery);
