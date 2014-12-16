(function($)
{
  var app = angular.module('Game');

  app.controller('GuildsController', ['gameApi', function(gameApi)
  {
    var me = this;

    me.guilds = [];

    gameApi.guild.list().success(function(data)
    {
      me.guilds = data.params.guilds;
    });
  }]);
})(jQuery);
