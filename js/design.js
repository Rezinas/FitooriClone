'use strict';

var des = angular.module('cdesign', ['slick']);

des.controller('MainController', ['$scope', '$rootScope', '$window',
    function($scope, $rootScope, $window) {

        $scope.designObj = {};
        $scope.filteredSet = [];
        $scope.myAltItems = [];
        $scope.mySelectedItems = [];
        $scope.designLevel = 0;
        $scope.myProductImgs = [];
        $scope.pageSize = 12;
        $scope.currentPage = 0;
        $scope.levelFilled = false;
        $scope.prdIndex = [];
        var elements = $window.model.elements;
        var bodyparts = $window.model.items;

        var findConnectionElements = function(eitems) {
            var resArr = [];
            var prevItem;
            var topPoints = 0;

            if ($scope.designLevel != 0) {
                prevItem = $scope.mySelectedItems[$scope.designLevel - 1];
                topPoints = prevItem.bottompoints;
            }
            if ($scope.designLevel != 0 && topPoints == 0) {
                return resArr;
            }

            $.each(eitems, function(ind, row) {
                if ($scope.designLevel == 0) {
                    if (row.toppoints == 0) {
                        resArr.push(row);
                    }
                } else {
                    if(row.toppoints == 1){
                        resArr.push(row);
                    }
                    if (row.toppoints == topPoints && row.toppoints != 1) {
                        var prevXs = prevItem.botX.split(",");
                        var currXs = row.topX.split(",");
                        var matchingPoints =true;
                        for(var i=0; i< topPoints && matchingPoints; i++){

                            if(Math.abs((currXs[i+1] - currXs[i]) - (prevXs[i+1] - prevXs[i])) > 5)
                                matchingPoints=false;
                        }
                        if(matchingPoints)
                         resArr.push(row);
                    }
                }

            });
            return resArr;
        };



        $.each(bodyparts, function(index, item) {
            $scope.designObj[item] = [];
        });

        $.each(elements, function(ind, element) {
            if (element.bodypart == 3) {
                $scope.designObj["Earrings"].push(element);
            }
            if (element.bodypart == 1) {
                $scope.designObj["Anklets"].push(element);
            }
        });

        $scope.filteredSet =  findConnectionElements($scope.designObj.Earrings);

        $scope.selectImage = function(elem, elementPos, mainlist) {
            $scope.levelFilled = true;
            var bpoints = ($scope.mySelectedItems.length ==0) ? 1 : 0;
            if($scope.designLevel > 0) {
                var prevElsArr = $scope.prdIndex[$scope.designLevel-1];
                var numberOfElemInPrevLevel = prevElsArr.length;
                $.each(prevElsArr, function(ix, elPos){
                   bpoints += $scope.mySelectedItems[elPos].bottompoints;
                });
            }
            var tpoints = elem.toppoints;
                var indexToRemove = 0;
                var numberToRemove = 0;

            if($scope.prdIndex[$scope.designLevel]) {
                indexToRemove = $scope.prdIndex[$scope.designLevel][0];
                numberToRemove = $scope.prdIndex[$scope.designLevel].length;
            }

            $scope.myProductImgs.splice(indexToRemove, numberToRemove);
            if (mainlist) {
                $scope.prdIndex.splice($scope.designLevel, 1);
                $scope.mySelectedItems.splice(indexToRemove, numberToRemove);
                var pos = $scope.mySelectedItems.length;
                if(tpoints == bpoints) {
                     tpoints = 1; bpoints= 1;
                }
                var indexArr =[];
                for(var i=0; i< bpoints; i++){
                    indexArr[i] = pos;
                    var element = {};
                    angular.copy(elem, element)
                    $scope.mySelectedItems.push(element);
                    $scope.myProductImgs[pos] = elem.images[0].imagefile;
                    pos++;
                }
                $scope.prdIndex.push(indexArr);
                console.log($scope.prdIndex);
            } else {
                var pos = ($scope.designLevel > 0) ? numberOfElemInPrevLevel : 0;
                for (var i = 0; i < bpoints; i++) {
                    $scope.myProductImgs[pos] = elem; pos++;
                }
            }
            // console.log($scope.myProductImgs);
        };

        $scope.updateLevel = function() {
            if($scope.levelFilled) {
                $scope.designLevel++;
                $scope.levelFilled = false;
                $scope.filteredSet = findConnectionElements($scope.designObj.Earrings);
            } else {
                alert("you havent selected elements for this level yet.");
            }

        };

        $scope.confirmDesign = function() {

        };

        $scope.gobackLevel = function() {
            if($scope.prdIndex[$scope.designLevel]){
                var indexToRemove = $scope.prdIndex[$scope.designLevel][0];
                var numberToRemove = $scope.prdIndex[$scope.designLevel].length;
                $scope.myProductImgs.splice(indexToRemove, numberToRemove);
                $scope.mySelectedItems.splice(indexToRemove, numberToRemove);
            }

            $scope.designLevel--;
            $scope.levelFilled = false;
            $scope.filteredSet = findConnectionElements($scope.designObj.Earrings);
        }


    }
]);

/*

offset = topimg_botconnpont.x - curimg_topconnpt.x
*/
des.directive('prdPosition', function($timeout) {
    return {
        restrict: "AE",
        scope: {
            selectedItems: '=',
            designLevel: '=',
            elemNumber: '=',
            prdIndex: '='
        },
        link: function(scope, element, $attrs) {
            var thisScope = scope;
            var curElem = element;
            scope.$watch('imgItem', function(newVal, oldVal) {
                var topPos = 0;
                var leftPos = 0;
                var currentElem = thisScope.selectedItems[thisScope.elemNumber];

                if (thisScope.designLevel != 0) {
                    //single level elements
                    var currLevelArr = thisScope.prdIndex[thisScope.designLevel];
                    var prevElsArr = thisScope.prdIndex[thisScope.designLevel-1];
                    var prevElement = null;

                    var currIndexCurrElem = $.inArray(thisScope.elemNumber, currLevelArr);

                    var prevIndex = prevElsArr[currIndexCurrElem] ? prevElsArr[currIndexCurrElem] : 0;
                    prevElement = thisScope.selectedItems[prevIndex];
                    topPos += prevElement.topPos;
                    leftPos += prevElement.leftPos;
                    if(prevElement.bottompoints > 1){
                         var botPoints = prevElement.botY.split(",");
                         topPos += parseInt(botPoints[currIndexCurrElem], 10);
                         var tPoints = prevElement.botX.split(",");
                         if(currentElem.toppoints == 1) {
                            leftPos += parseInt(tPoints[currIndexCurrElem], 10) - parseInt(currentElem.topX, 10);
                         }
                    }
                    else {
                         topPos += parseInt(prevElement.botY, 10);
                         if(currentElem.toppoints == 1) {
                            leftPos += parseInt(prevElement.botX, 10) - parseInt(currentElem.topX, 10);
                         }
                    }

                }
                    thisScope.selectedItems[thisScope.elemNumber].topPos = topPos;
                    thisScope.selectedItems[thisScope.elemNumber].leftPos = leftPos;
                $(curElem).css("left", leftPos+"px");
                $(curElem).css("top", topPos+"px");
            }, true);
        }
    };
});

des.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
}
});

des.factory('elementFactory', function() {
    var factory = {};
    return factory;
});