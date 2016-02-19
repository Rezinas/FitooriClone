'use strict';
var searchapp = angular.module('productsearch', []);

searchapp.controller('MainController', ['$scope', '$rootScope', '$window',
    function($scope, $rootScope, $window) {
        $scope.allProducts = $window.model.products;
        $scope.isAgent = $window.model.isAgent;
        $scope.currentPage = 0;
        $scope.pageSize = 10;
        $scope.selectedMaterial =1;
        $scope.lowerPrice = 0;
        $scope.upperPrice = 3000;

        $scope.criterias = function(item) {
            return (item.material == $scope.selectedMaterial && item.price >= $scope.lowerPrice && item.price <= $scope.upperPrice);
        };

        $scope.numberOfPages=function(){
            return Math.ceil($scope.allProducts.length/$scope.pageSize);
        };

}]);

searchapp.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
});
