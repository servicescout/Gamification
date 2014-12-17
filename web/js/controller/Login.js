(function($)
{
  var app = angular.module('Game');

  app.controller('LoginController', ['gameApi', '$location', '$scope', function(gameApi, $location, $scope)
  {
    var me = this;

    me.credentials = {};

    me.login = function()
    {
      gameApi.auth.login(me.credentials).success(function(data)
      {
        $scope.app.rebuildMenu(data.params.account);
        $location.path('/');
      });
    };
  }]);
})(jQuery);
