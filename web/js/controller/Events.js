(function($)
{
  var app = angular.module('Game');

  app.controller('EventsController', ['gameApi', function(gameApi)
  {
    var me = this;

    me.recentEvents = [];
    me.missedEvents = [];
    
    gameApi.event.recent().success(function(data)
    {
      me.recentEvents = data.params.recent;
    });
    
    gameApi.event.missed().success(function(data)
    {
      me.missedEvents = data.params.missed;
    });
  }]);
})(jQuery);
