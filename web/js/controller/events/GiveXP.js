(function($)
{
  var app = angular.module('Game');

  app.controller('GiveXPController', ['gameApi', '$location', function(gameApi, $location)
  {
    var me = this;

    me.accrual = {};
    me.players = [];
    
    me.save = function()
    {
      gameApi.event.giveXP(me.accrual).success(function()
      {
        $location.path('/events');
      });
    };

    gameApi.player.list().success(function(data)
    {
      me.players = data.params.players;
    });
  }]);
})(jQuery);
