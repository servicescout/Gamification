(function($)
{
  var app = angular.module('Game');

  app.controller('TransferGoldController', ['gameApi', '$location', function(gameApi, $location)
  {
    var me = this;

    me.transfer = {};
    me.players = [];
    
    me.save = function()
    {
      gameApi.event.transferGold(me.transfer).success(function()
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
