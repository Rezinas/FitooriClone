'use strict';

var des = angular.module('cdesign', ['slick']);

des.controller('MainController', ['$scope', '$rootScope', '$window',
    function($scope, $rootScope, $window) {

        $scope.itemModel = {};
        $scope.itemModel.items = $window.model.items;
        $scope.itemModel.selectedItem = $scope.itemModel.items[2];

        $scope.itemModel.materials = $window.model.materials;
        $scope.itemModel.selectedMaterial = "";

        $scope.uniqueTags = [];
        $scope.designObj = {};
        $scope.filteredSet = [];
        $scope.myAltItems = [];
        $scope.mySelectedItems = [];
        $scope.tagFilterSelected = [];
        $scope.designLevel = 0;
        $scope.myProductImgs = [];

        var elements = $window.model.elements;
        var tags = "";

        var findConnectionElements = function(eitems, num) {
            var resArr = [];
            $.each(eitems, function(ind, row) {
                if ($scope.designLevel == 0) {
                    if (row.toppoints == 0) {
                        resArr.push(row);
                    }
                } else {
                    if (row.toppoints <= num && row.toppoints != 0) {
                        resArr.push(row);
                    }
                }

            });
            return resArr;
        };

        var getFilteredSet = function() {
            var topPoints = 0;
            var prevItem;
            var resArr = [];
            if ($scope.designLevel != 0) {
                prevItem = $scope.mySelectedItems[$scope.designLevel - 1];
                topPoints = prevItem.bottompoints;
            }
            if ($scope.designLevel != 0 && topPoints == 0) {
                return resArr;
            }

            var currentFilteredElements = findConnectionElements($scope.designObj.Earrings, topPoints);
            var matIndex = $.inArray($scope.itemModel.selectedMaterial, model.materials);
            matIndex++;
            $.each(currentFilteredElements, function(idx, elem) {
                $.each($scope.tagFilterSelected, function(i, tag) {
                    //one of elem.tags (a,b,c) should be in one of tags [b,c,d]
                    if (elem.tags.indexOf(tag) > -1 && elem.material == matIndex) {
                        resArr.push(elem);
                    }
                });
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
            tags += element.tags + ",";
        });

        tags = tags.replace(/,\s*$/, "");
        tags = tags.replace(/\s*,\s*/, ",");
        tags = tags.split(",");
        tags = tags.filter(function(itm, i, a) {
            itm = itm.trim();
            return i == a.indexOf(itm);
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
            if (mainlist) {
                $scope.mySelectedItems.splice(pos, 1)
                $scope.myProductImgs.splice(pos, 1);
                $scope.mySelectedItems[pos] = elem;
                $scope.myProductImgs[pos] = elem.images[0].imagefile;
            } else {
                $scope.myProductImgs[pos] = elem;
            }
        };

        $scope.updateLevel = function() {
            if ($scope.myProductImgs.length - $scope.designLevel >= 1) {
                $scope.designLevel++;
                $scope.filteredSet = getFilteredSet();
            } else {
                alert("you havent selected elements for this level yet.");
            }

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
            designLevel: '='
        },
        link: function(scope, element, $attrs) {
            var thisScope = scope;
            var curElem = element;
            scope.$watch('imgItem', function(newVal, oldVal) {
                var currentItem = thisScope.selectedItems[thisScope.designLevel];
                var topPos = '0px';
                var leftPos = '0px';
                if (thisScope.designLevel != 0) {
                    topPos = $(curElem).prev().height() + "px";
                    var prevElem = thisScope.selectedItems[thisScope.designLevel - 1]
                    leftPos = (prevElem.botX - currentItem.topX) + "px";
                }
                $(curElem).css("left", leftPos);
                $(curElem).css("top", topPos);
            }, true);
        }
    };
});

des.factory('elementFactory', function() {
    var factory = {};
    return factory;
});