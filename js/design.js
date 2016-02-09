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
        $scope.allConnArr =[];
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
            var tpoints = elem.toppoints;
            var indexToRemove = 0;
            var numberToRemove = 0;
            var prevElsArr=[];
            var numberOfElemInPrevLevel =0;
            var lastLevelEl = [];



            // This is when different images in carousel is selected after
            //the level is filled but next level is not  reached.
            if($scope.prdIndex[$scope.designLevel]) {
                indexToRemove = $scope.prdIndex[$scope.designLevel][0];
                numberToRemove = $scope.prdIndex[$scope.designLevel].length;
            }

            if($scope.designLevel > 0) {
              prevElsArr = $scope.prdIndex[$scope.designLevel-1];
                numberOfElemInPrevLevel = prevElsArr.length;
                $.each(prevElsArr, function(ix, elPos){
                   bpoints += $scope.mySelectedItems[elPos].bottompoints;
                });
                $.each($scope.allConnArr[$scope.designLevel-1], function(ix, elPos){
                    lastLevelEl.push($scope.mySelectedItems[elPos.split(',')[0]]);
                });
            }
            if (mainlist) {
                $scope.prdIndex.splice($scope.designLevel, 1);
                $scope.allConnArr.splice($scope.designLevel, 1);
                $scope.mySelectedItems.splice(indexToRemove, numberToRemove);

                var pos = $scope.mySelectedItems.length;
                if(tpoints == bpoints) {
                     tpoints = 1; bpoints= 1;
                }

                var indexArr =[];
                var connArr =[];
                for(var i=0; i< bpoints; i++){
                    var currBottomPoints = (elem.bottompoints == 0) ? 1 : parseInt(elem.bottompoints, 10);
                    for(var j=0; j< currBottomPoints; j++) {
                       connArr.push(pos+","+j);
                    }
                    indexArr.push(pos);

                    var element = {};
                    angular.copy(elem, element)
                    element.selectedImage = element.carouselImg;
                                //Calculating topPos and leftPos
                    var topPos = 0;
                    var leftPos = 0;

                    if ($scope.designLevel != 0) {
                        var prevElement = null;
                        prevElement = lastLevelEl[i];
                        topPos += parseInt(prevElement.topPos, 10);
                        leftPos += parseInt(prevElement.leftPos, 10);
                        var connPoint = $scope.allConnArr[$scope.designLevel-1][i];

                        if(prevElement.bottompoints > 1){
                             var botPoints = prevElement.botY.split(",");
                             topPos += parseInt(botPoints[connPoint.split(",")[1]], 10);
                             var tPoints = prevElement.botX.split(",");
                             if(element.toppoints == 1) {
                                leftPos += parseInt(tPoints[connPoint.split(",")[1]], 10) - parseInt(element.topX, 10);
                             }
                        }
                        else {
                             topPos += parseInt(prevElement.botY, 10);
                             if(element.toppoints == 1) {
                                leftPos += parseInt(prevElement.botX, 10) - parseInt(element.topX, 10);
                             }
                        }
                    }
                    element.topPos = topPos;
                    element.leftPos = leftPos;
                    $scope.mySelectedItems.push(element);
                    pos++;
                }
                $scope.prdIndex.push(indexArr);
                $scope.allConnArr.push(connArr);
                console.log($scope.prdIndex);
                console.log($scope.allConnArr);
            } else {
                var pos = ($scope.designLevel > 0) ? numberOfElemInPrevLevel : 0;
                for (var i = 0; i < bpoints; i++) {
                    $scope.myProductImgs[pos] = elem; pos++;
                }
            }
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
            selectedItem: '='
        },
        link: function(scope, element, $attrs) {
            var curElem = element;
            var thisScope = scope;
            scope.$watch('selectedItem.selectedImage', function(newVal, oldVal){
             $(curElem).css("left", thisScope.selectedItem.leftPos+"px");
             $(curElem).css("top", thisScope.selectedItem.topPos+"px");
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