(function($)
{
  var app = angular.module('Game');

  app.controller('PlayersController', ['gameApi', function(gameApi)
  {
    var me = this;

    me.guilds = [];

    gameApi.guild.list().success(function(data)
    {
      me.guilds = data.params.guilds;
    });
  }]);

  app.controller('CardController', ['$scope', function($scope)
  {
    var me = this;

    me.gamer = $scope.gamer;
  }]);
})(jQuery);
