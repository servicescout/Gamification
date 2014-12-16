(function($)
{
  var app = angular.module('Game');

  app.controller('LoginController', ['gameApi', '$location', function(gameApi, $location)
  {
    var me = this;

    me.credentials = {};

    me.login = function()
    {
      console.log('test');
      gameApi.auth.login(me.credentials).success(function()
      {
        $location.path('/');
      });
    };
  }]);
})(jQuery);
