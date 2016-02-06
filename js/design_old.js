'use strict';

var des = angular.module('cdesign', ['slick']);

des.controller('MainController', ['$scope', '$rootScope', '$window',
    function($scope, $rootScope, $window) {

        $scope.itemModel = {};
        $scope.itemModel.items = $window.model.items;
        $scope.itemModel.selectedItem = $scope.itemModel.items[2];

        $scope.itemModel.materials = $window.model.materials;
        $scope.itemModel.selectedMaterial = "";
        $scope.itemModel.styles = $window.model.styles;
        $scope.itemModel.selectedStyle = "";


        $scope.uniqueTags = [];
        $scope.designObj = {};
        $scope.filteredSet = [];
        $scope.myAltItems = [];
        $scope.mySelectedItems = [];
        $scope.tagFilterSelected = [];
        $scope.designLevel = 0;
        $scope.myProductImgs = [];
        $scope.pageSize = 12;
        $scope.currentPage = 0;
        var elements = $window.model.elements;
        var tags = "";

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
                        var Itempoints = topPoints.split(",");
                        var rowpoints = row.toppoints.split(",");
                        // for(var i=0; i<Itempoints.length; i++){
                        //     Itempoints[]
                        // }
                        resArr.push(row);
                    }
                }

            });
            return resArr;
        };

        var getFilteredSet = function() {
            var resArr = [];

            var currentFilteredElements = findConnectionElements($scope.designObj.Earrings);
            var matIndex = $.inArray($scope.itemModel.selectedMaterial, $window.model.materials);
            var styleIndex = $.inArray($scope.itemModel.selectedStyle, $window.model.styles);
            matIndex++;
            styleIndex++;
            $.each( currentFilteredElements, function(idx, elem) {
                if (($scope.designLevel == 0 && elem.material == "1") || (elem.material == matIndex && elem.style == styleIndex)) {
                    resArr.push(elem);
                }
            });
            return resArr;
        };

        $.each($scope.itemModel.items, function(index, item) {
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

        $scope.uniqueTags = tags;

        $scope.toggleSelection = function(stag) {
            var idx = $scope.tagFilterSelected.indexOf(stag);
            // is currently selected
            if (idx > -1) {
                $scope.tagFilterSelected.splice(idx, 1);
            }
            // is newly selected
            else {
                $scope.tagFilterSelected.push(stag);
            }
            $scope.filteredSet = getFilteredSet();
        };

        $scope.$watch('itemModel.selectedMaterial', function(value) {
            $scope.filteredSet = getFilteredSet();
        });


        $scope.selectImage = function(elem, pos, mainlist) {
            var bpoints = 1;
            if(pos > 0) {
                var prevElem = $scope.mySelectedItems[pos -1];
                bpoints = prevElem.bottompoints;
            }
            var tpoints = elem.toppoints;

            $scope.myProductImgs.splice(pos, 1);
            if (mainlist) {
                $scope.mySelectedItems.splice(pos, 1);
                if(tpoints == bpoints) {
                     tpoints = 1; bpoints= 1;
                }
                for(var i=0; i< bpoints; i++){
                     $scope.mySelectedItems[pos] = elem;
                     $scope.myProductImgs[pos] = elem.images[0].imagefile;
                     pos++;
                }
            } else {
                for (var i = 0; i < bpoints; i++) {
                    $scope.myProductImgs[pos] = elem;
                }
            }
            console.log($scope.myProductImgs);
        };

        $scope.updateLevel = function() {
            if ($scope.myProductImgs.length - $scope.designLevel >= 1) {
                $scope.designLevel++;
                $scope.filteredSet = getFilteredSet();
            } else {
                alert("you havent selected elements for this level yet.");
            }

        };

        $scope.confirmDesign = function() {

        };

        $scope.gobackLevel = function() {
            $scope.myProductImgs.splice($scope.designLevel, 1);
            $scope.mySelectedItems.splice($scope.designLevel, 1)
            $scope.designLevel--;
            $scope.filteredSet = getFilteredSet();
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
            imgItem: '=',
            selectedItems: '=',
            designLevel: '=',
            elemNumber: '='
        },
        link: function(scope, element, $attrs) {
            var thisScope = scope;
            var curElem = element;
            scope.$watch('imgItem', function(newVal, oldVal) {
                var currentItem = thisScope.selectedItems[thisScope.designLevel];
                var topPos = 0;
                var leftPos = 0;
                if (thisScope.designLevel != 0) {
                    $.each(thisScope.selectedItems, function(idx, childElem){
                        if(idx == thisScope.designLevel) return false;
                        if(childElem.bottompoints > 1){
                            var botPoints = childElem.botY.split(",");
                            topPos = topPos + parseInt(botPoints[thisScope.elemNumber], 10);
                        }
                        else{
                            topPos = topPos + parseInt(childElem.botY, 10);
                        }
                    });
                    // topPos = $(curElem).prev().height();
                    var prevElem = thisScope.selectedItems[thisScope.designLevel - 1];
                    if(prevElem.bottompoints > 0) {
                        var tPoints = prevElem.botX.split(",");
                        leftPos = parseInt(tPoints[thisScope.elemNumber], 10) - parseInt(currentItem.topX, 10);
                    }
                    else
                        leftPos = parseInt(prevElem.botX, 10) - parseInt(currentItem.topX, 10);
                }
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