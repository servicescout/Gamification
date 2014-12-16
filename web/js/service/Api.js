(function($)
{
  var app = angular.module('Game');

  app.service('gameApi', ['$http', function($http)
  {
    this.guild = {
      list: function()
      {
        return $http.get('/api/guild/list');
      },
      get: function(id)
      {
        return $http.get('/api/guild/get/' + id);
      },
      edit: function(id, data)
      {
        return $http.post('/api/guild/edit/' + id, data);
      },
      create: function(data)
      {
        return $http.post('/api/guild/create', data);
      }
    };

    this.player = {
      list: function()
      {
        return $http.get('/api/player/list');
      },
      get: function(id)
      {
        return $http.get('/api/player/get/' + id);
      },
      edit: function(id, data)
      {
        return $http.post('/api/player/edit/' + id, data);
      },
      create: function(data)
      {
        return $http.post('/api/player/create', data);
      }
    };

    this.event = {
      recent: function()
      {
        return $http.get('/api/event/recent');
      },
      missed: function()
      {
        return $http.get('/api/event/missed');
      },
      giveXP: function(accrual)
      {
        return $http.post('/api/event/giveXP', accrual);
      },
      transferGold: function(transfer)
      {
        return $http.post('/api/event/transferGold', transfer);
      }
    };

    this.characterClass = {
      list: function()
      {
        return $http.get('/api/characterClass/list');
      }
    };
  }]);
})(jQuery);