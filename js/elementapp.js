'use strict';
var searchapp = angular.module('elementsearch', ['rzModule']);
//https://github.com/angular-slider/angularjs-slider
searchapp.controller('MainController', ['$scope', '$rootScope', '$window',
    function($scope, $rootScope, $window) {
        $scope.allPieces = $window.model.pieces;
        $scope.isAgent = $window.model.isAgent;
        $scope.currentPage = 0;
        $scope.pageSize = 50;
        $scope.materials = $window.model.materials;
        $scope.items = $window.model.items;
        $scope.selectedMaterial =[];
        $scope.selectedItem=[3];
         $scope.selectedSort ="phigh";
         $scope.reverseorder = true;
         $scope.sortItem="'price'";
         $scope.toppoints=0;
         $scope.bottompoints=0;

        console.log($scope.allPieces);

        $scope.orderByOptions = function() {
            // if($scope.selectedSort == "new") {
            //     $scope.reverseorder = true;
            //     $scope.sortItem = "'dateAdded'";
            // }
            if($scope.selectedSort == "phigh" ){
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
                  max: 50,
                  options: {
                    floor: 0,
                    step: 5,
                    ceil: 500,
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
        $scope.toggleItemSelection = function toggleItemSelection(itmIndex) {
            var idx = $scope.selectedItem.indexOf(itmIndex);
            // is currently selected
            if (idx > -1) {
              $scope.selectedItem.splice(idx, 1);
            }
            // is newly selected
            else {
              $scope.selectedItem.push(matIndex);
            }
        };



        $scope.criterias = function(item) {
            var foundMat = false;
            var foundPrice = false;
            var foundpoints = false;
            var foundItem = false;
            if( ($scope.bottompoints == 0 && $scope.toppoints == 0) || ($scope.bottompoints == item.bottompoints && $scope.toppoints == item.toppoints)){
                foundpoints = true;
            }
            if(($scope.selectedMaterial.length == 0) || ($scope.selectedMaterial.indexOf(item.material) > -1)){
                foundMat = true;
            }
            if(item.price >= $scope.priceSlider.min && item.price <= $scope.priceSlider.max ){
                foundPrice = true;
            }
            if(($scope.selectedItem.length == 0) || ($scope.selectedItem.indexOf(item.bodypart) > -1)){
                foundItem = true;
            }
            return (foundMat && foundpoints && foundPrice && foundItem);
        };

        $scope.numberOfPages=function(){
            return Math.ceil($scope.allPieces.length/$scope.pageSize);
        };

}]);

searchapp.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
});
