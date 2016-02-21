'use strict';
var searchapp = angular.module('productsearch', ['rzModule']);


searchapp.config(function($locationProvider) {
  $locationProvider.html5Mode({
        enabled: true,
        requireBase: false
      });
});

//https://github.com/angular-slider/angularjs-slider
searchapp.controller('MainController', ['$scope', '$rootScope', '$window', '$location',
    function($scope, $rootScope, $window, $location) {

        $scope.siteUrl = $window.model.siteUrl;
        $scope.allProducts = $window.model.products;
        $scope.isAgent = $window.model.isAgent;
        $scope.currentPage = 0;
        $scope.pageSize = 20;
        $scope.materials = $window.model.materials;
        $scope.tags = $.unique($window.model.tags);
        $scope.items = $window.model.items;
        $scope.selectedMaterial =[];
        $scope.selectedTags =[];
        $scope.selectedItem=[3];
        $scope.selectedSort ="new";
        $scope.reverseorder = true;
        $scope.sortItem="'dateAdded'";
        $scope.prdStatus={ "active" : "",
                            "custom": "",
                            "despick": ""};
        var queryParam = $location.search();
        if(queryParam.m){
              $scope.selectedMaterial.push(parseInt(queryParam.m,10));
        }

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
             var foundItem = false;
             var foundStatus=false;
             var foundCustom=false;
             var foundDpick=false;
             var foundTags= false;
             if(!$scope.isAgent || $scope.prdStatus.active == "") foundStatus = true;
             else {
                if(parseInt($scope.prdStatus.active, 10) == item.status){
                    foundStatus= true;
                }
             }
              if(($scope.selectedTags.length == 0) || ($scope.selectedTags.indexOf(item.tags) > -1)){
                foundTags = true;
            }
             if(!$scope.isAgent || $scope.prdStatus.despick == "") foundDpick = true;
             else {
                if(parseInt($scope.prdStatus.despick, 10) == item.designerPick){
                    foundDpick= true;
                }
             }
            if(!$scope.isAgent || $scope.prdStatus.custom == "") foundCustom = true;
             else {
                if(parseInt($scope.prdStatus.custom, 10) == item.customized){
                    foundCustom= true;
                }
             }
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
            if(($scope.selectedItem.length == 0) || ($scope.selectedItem.indexOf(item.bodypart) > -1)){
                foundItem = true;
            }
            return (foundMat && foundPrice && foundItem && foundStatus && foundCustom && foundDpick && foundTags);
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
