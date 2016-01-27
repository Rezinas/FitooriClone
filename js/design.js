'use strict';

 var des = angular.module('cdesign', ['slick']);

des.controller('MainController', ['$scope', '$rootScope', '$window',
    function($scope, $rootScope, $window) {

    	$scope.itemModel ={};
    	$scope.itemModel.items = $window.model.items;
    	$scope.itemModel.selectedItem=$scope.itemModel.items[2];
    	var elements = $window.model.elements;

            var findTopPointsElements = function (eitems, num) {
                var resArr = [];
                $.each(eitems, function(ind, row){
                    if(row.toppoints ==  num) resArr.push(row);
                });
                return resArr;
            };


            $scope.designObj= {};

            $.each($scope.itemModel.items, function(index, item){
                $scope.designObj[item] = [];
            });

            $.each(elements, function(ind, element){
                if(element.bodypart == 3) {
                    $scope.designObj ["Earrings"].push(element);
                }
                if(element.bodypart == 1)   {
                    $scope.designObj ["Anklets"].push(element);
                }
            });

            $scope.firstSlider =  findTopPointsElements($scope.designObj.Earrings, 0);
            $scope.mySelectedItems = [];
              $scope.selectImage = function(elem, pos) {
                $scope.mySelectedItems[pos] = elem;
                if(pos == 0) setSecondSlider();
              //  if(pos == 1) setThirdSlider();

              };

            $scope.secondSlider=[];
            var setSecondSlider = function() {
                $scope.secondSlider =  findTopPointsElements($scope.designObj.Earrings, $scope.mySelectedItems[0].bottompoints);
                console.log($scope.secondSlider);
            };


}]);

/*

offset = topimg_botconnpont.x - curimg_topconnpt.x
*/


des.factory('elementFactory', function() {
    var factory = {};
    return factory;
})
