'use strict';
var des = angular.module('cdesign', []);

des.controller('MainController', ['$scope', '$rootScope', '$http', '$window', '$document',
    function($scope, $rootScope, $http, $window, $document) {

        $scope.siteUrl = $window.model.siteUrl;
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
        $scope.isAgent = $window.model.isAgent;
        var elements = $window.model.elements;
        var bodyparts = $window.model.items;

        var isOdd = function(num){
            return num % 2;
        };

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
            var fits = true;


            // This is when different images in carousel is selected after
            //the level is filled but next level is not  reached.
            if($scope.prdIndex[$scope.designLevel]) {
                indexToRemove = $scope.prdIndex[$scope.designLevel][0];
                numberToRemove = $scope.prdIndex[$scope.designLevel].length;
            }

            if($scope.designLevel > 0) {
              prevElsArr = $scope.prdIndex[$scope.designLevel-1];
              numberOfElemInPrevLevel = prevElsArr.length;

                //for multi point elements we need to figure out if there are going to be
                //multiple next level elements or only one in the middle
                $.each(prevElsArr, function(ix, elPos){
                    var cElem = $scope.mySelectedItems[elPos];
                    var curBpoints =  cElem.bottompoints;
                    if(isOdd(curBpoints) && curBpoints > 1 && elem.toppoints == 1) {
                        var botXs=  cElem.botX.split(",");
                        var curWidth = elem.imgwidth;
                        for(var i=0; i < botXs.length && fits; i++){
                            if(botXs[i+1] - botXs[i] <= curWidth){
                                fits=false;
                            }
                        }
                    }
                        if(fits) {
                             bpoints += $scope.mySelectedItems[elPos].bottompoints;
                        }
                        else {
                             bpoints += 1;
                        }
                });

                var prevConnArrLength = $scope.allConnArr[$scope.designLevel-1].length;
                $.each($scope.allConnArr[$scope.designLevel-1], function(ix, elPos){
                    if(fits) {
                        lastLevelEl.push($scope.mySelectedItems[elPos.split(',')[0]]);
                    }
                    else if(ix == Math.floor(prevConnArrLength/2))  {
                        lastLevelEl.push($scope.mySelectedItems[elPos.split(',')[0]]);
                    }
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
                    if(!fits) {
                        connArr.push(pos+","+Math.floor(prevConnArrLength/2));
                    }
                    else {
                        for(var j=0; j< currBottomPoints; j++) {
                           connArr.push(pos+","+j);
                        }
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
                        var connPoint;
                        if(fits) {
                            connPoint = $scope.allConnArr[$scope.designLevel-1][i];
                        }
                        else  {
                            connPoint = $scope.allConnArr[$scope.designLevel-1][Math.floor(prevConnArrLength/2)];
                        }

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
                    else {
                        leftPos += parseInt(element.centerx, 10);
                        topPos += parseInt(element.centery, 10);
                    }
                    element.topPos = topPos;
                    element.leftPos = leftPos;
                    $scope.mySelectedItems.push(element);
                    pos++;
                }
                $scope.prdIndex.push(indexArr);
                $scope.allConnArr.push(connArr);
            } else {
                var pos = ($scope.designLevel > 0) ? numberOfElemInPrevLevel : 0;
                bpoints = ($scope.designLevel == 0 ) ? 1 : bpoints;
                for (var i = 0; i < bpoints; i++) {
                    $scope.mySelectedItems[pos].selectedImage = elem;
                     pos++;
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

        $scope.processForm = function() {
          var payload = {
                        custom_product : $scope.mySelectedItems
                        };

          $http({
          method  : 'POST',
          url     : $scope.siteUrl+'ajax.php?addcustom',
          data    : payload  // pass in data as strings
         })
          .success(function(data) {
            if(data == "SUCCESS"){
                if($scope.isAgent)
                    $window.location = $scope.siteUrl+"dashboard.php?custom";
                else
                    $window.location =$scope.siteUrl+"index.php?checkout";
            }
            else
                alert("insert into database failed");
          });
        };

        $scope.gobackLevel = function() {
            if($scope.prdIndex[$scope.designLevel]){
                var indexToRemove = $scope.prdIndex[$scope.designLevel][0];
                var numberToRemove = $scope.prdIndex[$scope.designLevel].length;
                $scope.mySelectedItems.splice(indexToRemove, numberToRemove);
            }
            $scope.prdIndex.splice($scope.designLevel, 1);
            $scope.allConnArr.splice($scope.designLevel, 1);

            $scope.designLevel--;
            $scope.levelFilled = true;
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


des.filter('category', function() {
  return function(inputArr, catType) {
    inputArr = inputArr || [];
    var out = [];
    for (var i = 0; i < inputArr.length; i++) {
      if(inputArr[i].material == catType)
        out.push(inputArr[i]);
    }
    return out;
  };
})

des.factory('elementFactory', function() {
    var factory = {};
    return factory;
});