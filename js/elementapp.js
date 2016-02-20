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
        $scope.selectedMaterial =[];
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
                  max: 30,
                  options: {
                    floor: 0,
                    step: 5,
                    ceil: 500
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
            var foundpoints = false;
            if( ($scope.bottompoints == 0 && $scope.toppoints == 0) || ($scope.bottompoints == item.bottompoints && $scope.toppoints == item.toppoints)){
                foundpoints = true;
            }
            if(($scope.selectedMaterial.length == 0) || ($scope.selectedMaterial.indexOf(item.material) > -1)){
                foundMat = true;
            }
            if(item.price >= $scope.priceSlider.min && item.price <= $scope.priceSlider.max ){
                foundPrice = true;
            }
            return (foundMat && foundpoints && foundPrice);
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
