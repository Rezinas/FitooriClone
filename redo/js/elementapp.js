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
        $scope.styles = $window.model.styles;
        $scope.selectedMaterial =[];
        $scope.selectedStyles =[];
        $scope.selectedTags =[];
        $scope.selectedItem=[3];
        $scope.selectedSort ="phigh";
        $scope.selectedQuantity= '';
        $scope.reverseorder = true;
        $scope.sortItem="'price'";
        $scope.toppoints=0;
        $scope.bottompoints=0;

        var uniqueTags = function unique(array) {
            return $.grep(array, function(el, index) {
                return index == $.inArray(el, array);
            });
        };

         $scope.tags = uniqueTags($window.model.tags);

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
                $scope.sortItem = "'price'";
            }
            else if( $scope.selectedSort == "plow") {
                $scope.reverseorder = false;
                $scope.sortItem = "'price'";
            }
        };

         $scope.priceSlider = {
                 min: 0,
                  max: 200,
                  options: {
                    floor: 0,
                    step: 5,
                    ceil: 500,
                    translate: function(value) {
                      return 'Rs.' + value;
                    }
                  }
            };

        // toggle selection for a given material by name
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

        // toggle selection for a given style by name
        $scope.toggleStyleSelection = function toggleStyleSelection(styleItem) {
            var idx = $scope.selectedStyles.indexOf(styleItem);
            // is currently selected
            if (idx > -1) {
              $scope.selectedStyles.splice(idx, 1);
            }
            // is newly selected
            else {
              $scope.selectedStyles.push(styleItem);
            }
        };

        $scope.toggleTagSelection = function toggleTagSelection(tagIndx) {
            var idx = $scope.selectedTags.indexOf(tagIndx);
            // is currently selected
            if (idx > -1) {
              $scope.selectedTags.splice(idx, 1);
            }
            // is newly selected
            else {
              $scope.selectedTags.push(tagIndx);
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
              $scope.selectedItem.push(itmIndex);
            }
        };



        $scope.criterias = function(item) {
            var foundMat = false;
            var foundPrice = false;
            var foundpoints = false;
            var foundItem = false;
            var foundTags = false;
            var foundStyles = false;
            var foundQuantity = false;
            if( ($scope.bottompoints == 0 && $scope.toppoints == 0) || ($scope.bottompoints == item.bottompoints && $scope.toppoints == item.toppoints)){
                foundpoints = true;
            }
            if(($scope.selectedMaterial.length == 0) || ($scope.selectedMaterial.indexOf(item.material) > -1)){
                foundMat = true;
            }
             if(($scope.selectedTags.length == 0) || (findInString(item.admintags, $scope.selectedTags) )){
                foundTags    = true;
            }
            if(($scope.selectedStyles.length == 0) || (findInString(item.style, $scope.selectedStyles))){
                foundStyles    = true;
            }
            if($scope.selectedQuantity == '' || parseInt($scope.selectedQuantity, 10) >= item.quantity ) {
                foundQuantity = true;
            }
            if(item.price >= $scope.priceSlider.min && item.price <= $scope.priceSlider.max ){
                foundPrice = true;
            }
            if(($scope.selectedItem.length == 0) || ($scope.selectedItem.indexOf(item.bodypart) > -1)){
                foundItem = true;
            }
            return (foundQuantity && foundMat && foundpoints && foundPrice && foundItem && foundTags && foundStyles);
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
