'use strict';
var searchapp = angular.module('productsearch', ['rzModule']);
//https://github.com/angular-slider/angularjs-slider
searchapp.controller('MainController', ['$scope', '$rootScope', '$window',
    function($scope, $rootScope, $window) {
        $scope.allProducts = $window.model.products;
        $scope.isAgent = $window.model.isAgent;
        $scope.currentPage = 0;
        $scope.pageSize = 20;
        $scope.materials = $window.model.materials;
        $scope.selectedMaterial =[];
        $scope.selectedSort ="new";
        $scope.reverseorder = true;
        $scope.sortItem="'dateAdded'";


        $.each($scope.allProducts, function(ix, prd){
             prd.dateAdded = new Date(prd.dateAdded* 1000);
         });

        console.log($scope.allProducts);

        $scope.orderByOptions = function() {
            if($scope.selectedSort == "new") {
                $scope.reverseorder = true;
                $scope.sortItem = "'dateAdded'";
            }
            else if($scope.selectedSort == "phigh" ){
                $scope.reverseorder = true;
                $scope.sortItem = "'price'";
            }
            else if( $scope.selectedSort == "plow") {
                $scope.reverseorder = false;
                $scope.sortItem = "'price'";
            }
        };

         $scope.priceSlider = {
                 min: 0,
                  max: 500,
                  options: {
                    floor: 0,
                    step: 10,
                    ceil: 1000,
                    translate: function(value) {
                      return 'Rs.' + value;
                    }
                  }
            };

        // toggle selection for a given fruit by name
        $scope.toggleSelection = function toggleSelection(matIndex) {
            var idx = $scope.selectedMaterial.indexOf(matIndex);
            // is currently selected
            if (idx > -1) {
              $scope.selectedMaterial.splice(idx, 1);
            }
            // is newly selected
            else {
              $scope.selectedMaterial.push(matIndex);
            }
        };

        $scope.criterias = function(item) {
            var foundMat = false;
            var foundPrice = false;
            if($scope.selectedMaterial.length > 0) {
                if($scope.selectedMaterial.indexOf(item.material) > -1){
                    foundMat = true;
                }
            }
            else {
                foundMat = true;//make it true for all materials here.
            }
            if(item.price >= $scope.priceSlider.min && item.price <= $scope.priceSlider.max ){
                foundPrice = true;
            }
            return (foundMat && foundPrice);
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
