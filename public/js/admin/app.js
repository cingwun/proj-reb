var app = angular.module('adminApp',[]);

//change symbol
app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});


//users service
app.factory('usersService',['$http',function($http){
  return {
    delete:function(id){
      var promise = $http({
          method: 'DELETE',
          url: '/admin/users/'+id
      });
      return promise;
    }
  };
}]);

//permissions service
app.factory('permissionsService',['$http',function($http){
  return {
    delete:function(id){
      var promise = $http({
          method: 'DELETE',
          url: '/admin/permissions/'+id
      });
      return promise;
    }
  };
}]);

//groups service
app.factory('groupsService',['$http',function($http){
  return {
    delete:function(id){
      var promise = $http({
          method: 'DELETE',
          url: '/admin/groups/'+id
      });
      return promise;
    }
  };
}]);

//ranks service
app.factory('ranksService',['$http',function($http){
  return {
    delete:function(id){
      var promise = $http({
          method: 'DELETE',
          url: '/admin/ranks/'+id
      });
      return promise;
    }
  };
}]);

//articles service
app.factory('articlesService',['$http',function($http){
  return {
    delete:function(id){
      var promise = $http({
          method: 'DELETE',
          url: '/admin/articles/'+id
      });
      return promise;
    }
  };
}]);

//technologies service
app.factory('technologiesService',['$http',function($http){
  return {
    delete:function(id){
      var promise = $http({
          method: 'DELETE',
          url: '/admin/technologies/'+id
      });
      return promise;
    }
  };
}]);

//Services service
/*
app.factory('servicesService',['$http',function($http){
  return {
    delete:function(id){
      var promise = $http({
          method: 'DELETE',
          url: '/admin/services/'+id
      });
      return promise;
    }
  };
}]);

//Faqs service
app.factory('faqsService',['$http',function($http){
  return {
    delete:function(id){
      var promise = $http({
          method: 'DELETE',
          url: '/admin/faqs/'+id
      });
      return promise;
    }
  };
}]);*/



//users controller
app.controller('usersCtrl',['$scope','usersService',function($scope,usersService){

  $scope.deleteUser = function(id){
    if(confirm('確定刪除?')){
      usersService.delete(id).success(function(){
        location.href=location.href;
      });
    }
  };
}]);

//permissions controller
app.controller('permissionsCtrl',['$scope','permissionsService',function($scope,permissionsService){

  $scope.deletePermission = function(id){
    if(confirm('確定刪除?')){
      permissionsService.delete(id).success(function(data){
        location.href=location.href;
      });
    }
  };
}]);

//groups controller
app.controller('groupsCtrl',['$scope','groupsService',function($scope,groupsService){

  $scope.deleteGroup = function(id){
    if(confirm('確定刪除?')){
      groupsService.delete(id).success(function(){
        location.href=location.href;
      });
    }
  };
}]);

//ranks controller
app.controller('ranksCtrl',['$scope','ranksService',function($scope,ranksService){

  $scope.deleteRank = function(id){
    if(confirm('確定刪除?')){
      ranksService.delete(id).success(function(){
        location.href=location.href;
      });
    }
  };
}]);

//articles controller
app.controller('articlesCtrl',['$scope','articlesService',function($scope,articlesService){

  $scope.deleteArticle = function(id){
    if(confirm('確定刪除?')){
      articlesService.delete(id).success(function(){
        location.href=location.href;
      });
    }
  };
}]);

//technologies controller
app.controller('technologiesCtrl',['$scope','technologiesService',function($scope,technologiesService){

  $scope.deleteTechnology = function(id){
    if(confirm('確定刪除?')){
      technologiesService.delete(id).success(function(){
        location.href=location.href;
      });
    }
  };
}]);
/*
//Services controller
app.controller('servicesCtrl',['$scope','servicesService',function($scope,servicesService){

  $scope.deleteService = function(id){
    if(confirm('確定刪除?')){
      servicesService.delete(id).success(function(){
        location.href=location.href;
      });
    }
  };
  $scope.deleteService_category = function(id){
    if(confirm('確定刪除?')){
      servicesService.delete(id).success(function(){
        location.href="services";
      });
    }
  };
}]);

//Faqs controller
app.controller('faqsCtrl',['$scope','faqsService',function($scope,faqsService){

  $scope.deletefaq = function(id){
    if(confirm('確定刪除?')){
      faqsService.delete(id).success(function(){
        location.href=location.href;
      });
    }
  };
  $scope.deletefaq_category = function(id){
    if(confirm('確定刪除?')){
      faqsService.delete(id).success(function(){
        location.href="faqs";
      });
    }
  };
}]);*/
