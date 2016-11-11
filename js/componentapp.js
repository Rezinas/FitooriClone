'use strict';
var searchapp = angular.module('componentsearch', ['rzModule', 'ui.bootstrap']);
//https://github.com/angular-slider/angularjs-slider
searchapp.controller('MainController', ['$scope', '$rootScope', '$window',
    function($scope, $rootScope, $window) {
        $scope.allComponents = $window.model.components;
        $scope.isAgent = $window.model.isAgent;
        $scope.currentPage = 0;
        $scope.pageSize = 50;
        $scope.materials = $window.model.materials;
        $scope.colors = $window.model.colors;
        $scope.sources = $window.model.sources;
        $scope.selectedMaterial =[];
        $scope.selectedSources =[];
        $scope.selectedColor=[];
        $scope.selectedSort ="phigh";
        $scope.reverseorder = true;
        $scope.sortItem="'costpercomp'";



         var findInString = function(str, arr) {
            var found= false;
            $.each(arr, function(ind, val){
                if(str.indexOf(val) > -1) {
                    found =true;
                    return false;
                }
                found = false;
            });
            return found;
         }


        $scope.orderByOptions = function() {
            // if($scope.selectedSort == "new") {
            //     $scope.reverseorder = true;
            //     $scope.sortItem = "'dateAdded'";
            // }
            if($scope.selectedSort == "phigh" ){
                $scope.reverseorder = true;
                $scope.sortItem = "'costpercomp'";
            }
            else if( $scope.selectedSort == "plow") {
                $scope.reverseorder = false;
                $scope.sortItem = "'costpercomp'";
            }
        };

         $scope.priceSlider = {
                 min: 0,
                  max: 60,
                  options: {
                    floor: 0,
                    step: 1,
                    ceil: 100,
                    translate: function(value) {
                      return 'Rs.' + value;
                    },
                      onChange : function(sliderId, modelValue, highValue) {
                      $scope.currentPage = 0;
                    }
                  }
            };

        // toggle selection for a given material by name
        $scope.toggleSelection = function toggleSelection(matIndex) {
        $scope.currentPage = 0;

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

        // toggle selection for a given source by name
        $scope.toggleSourceSelection = function toggleSourceSelection(sourceItem) {
        $scope.currentPage = 0;

            var idx = $scope.selectedSources.indexOf(sourceItem);
            // is currently selected
            if (idx > -1) {
              $scope.selectedSources.splice(idx, 1);
            }
            // is newly selected
            else {
              $scope.selectedSources.push(sourceItem);
            }
        };

		// toggle selection for a given color by name
        $scope.toggleColorSelection = function toggleColorSelection(clrIndex) {
        $scope.currentPage = 0;

            var idx = $scope.selectedColor.indexOf(clrIndex);
            // is currently selected
            if (idx > -1) {
              $scope.selectedColor.splice(idx, 1);
            }
            // is newly selected
            else {
              $scope.selectedColor.push(clrIndex);
            }
        };



        $scope.criterias = function(item) {
            var foundMat = false;
            var foundPrice = false;
            var foundColor = false;
            var foundSources = false;

            if(($scope.selectedMaterial.length == 0) || ($scope.selectedMaterial.indexOf(item.material) > -1)){
                foundMat = true;
            }
            if(($scope.selectedSources.length == 0) || (findInString(item.source, $scope.selectedSources))){
                foundSources    = true;
            }
            if(item.costpercomp >= $scope.priceSlider.min && item.costpercomp <= $scope.priceSlider.max ){
                foundPrice = true;
            }
            if(($scope.selectedColor.length == 0) || (findInString(item.color, $scope.selectedColor))){
                foundColor = true;
            }
            return (foundMat && foundPrice && foundColor && foundSources);
        };

        $scope.numberOfPages=function(){
            return Math.ceil($scope.resultSet.length/$scope.pageSize);
        };
        $scope.prevPage = function() {
          $scope.currentPage=$scope.currentPage-1;
          window.scrollTo(0,0);
        };
        $scope.nextPage = function() {
          $scope.currentPage=$scope.currentPage+1;
          window.scrollTo(0,0);
        };


}]);

searchapp.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
});
