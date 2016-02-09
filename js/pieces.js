//for file upload input
    $(document).on('change', '.btn-file :file', function() {
                var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');

                var inpname = this.name;

                  var inputLabel = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;
                if( inputLabel.length ) {
                    inputLabel.val(log);
                } else {
                     if( log ) alert(log);
                }
                if(inpname == "carouselImg")
                 readURL(this, '#carouselImg img');
               else {
                 readURL(this, "#"+inpname+"Img img");
               }
        });

    function cartesian() {
    var r = [], arg = arguments, max = arg.length-1;
    function helper(arr, i) {
        for (var j=0, l=arg[i].length; j<l; j++) {
            var a = arr.slice(0); // clone arr
            a.push(arg[i][j]);
            if (i==max)
                r.push(a);
            else
                helper(a, i+1);
        }
    }
    helper([], 0);
    return r;
}
function getFileInpHTML(ctArr){
     return  '<div class="col-md-2"><div class="input-group " >'+
                    '<input type="text" class="form-control" readonly="">'+
                   ' <span class="input-group-btn">'+
                        '<span class="btn btn-primary btn-file">'+
                           ' Browse… <input type="file" class="imguploads" name="'+ctArr[0]  +'_'+ ctArr[1]+'">'+
                      ' </span>'+
                    '</span>'+
            '</div></div>';
}
function getFileInpNameHTML(ctArr) {
  return '<div class="col-md-2"><h4>'+ctArr[0]+' - ' +ctArr[1]+ '</h4></div>';
}
function getFilePreviewHTM(ctArr) {
  return  '<div class="col-md-4" id="'+ctArr[0]+'_' +ctArr[1]+ 'Img">'+
                  '<img src="../images/placeholder.png">'+
              '</div>';
}
function getFileRowHTML(ctArr){
  return '<div class="row altImg" id="'+ctArr[0]+'_' +ctArr[1]+ '">'+ getFileInpNameHTML(ctArr) +getFileInpHTML(ctArr)+getFilePreviewHTM(ctArr) +' </div>';
}

function bringback(domElem, ctArr){
  var self =  this;
  self.ctArr = ctArr;
  var result = null;
  $.each(domElem, function(index, domE){
    if(domE.id == self.ctArr[0]+'_' +self.ctArr[1]) {
      result=domE;
      return false;
    }
  });
  return result;
}

function bringbackCoords(domElem, divName) {
  var self= this;
  self.divName= divName;
  var result = null;
  $.each(domElem, function(ind, domE){
    if($(domE).hasClass(self.divName)) result= domE;
    return false;
  });
  return result;
}


function showFileInp(filesDom) {
    var self= this;
    self.present=false;
    var selectedcolors = $("#pccolors").val();
    var selecteddesigns = $("#pcdesign").val();
    if(!selectedcolors   || !selecteddesigns) {
       return;
    } else {
        var imageArr = cartesian(selectedcolors, selecteddesigns);
        var imageIds = imageArr.map(function(index) {
              return index[0]+"_"+index[1];
          });
        if($(".altImg").length) {
            $.each($('.altImg'), function (index, altImg) {
                  var curr_id =altImg.id;
                  if($.inArray(curr_id,imageIds) == -1)
                     $("#"+curr_id).remove();
              });
        }
        $.each(imageArr, function(inx, combo){
            if(!$("#"+combo[0]+"_"+combo[1]).length) {
              var bringbackDom= bringback(filesDom, combo);
              if(bringbackDom == null)
                  $("#alternateImg").append(getFileRowHTML(combo));
              else
                $("#alternateImg").append($(bringbackDom));
            }
        });
    }
}


function readURL(input, outputImg) {
var files = input.files ? input.files : input.currentTarget.files;
    if (files && files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(outputImg).attr('src', e.target.result);
        }
        reader.readAsDataURL(files[0]);
    }
}


function xyInputs(domE, points, ptype){
            var coordDiv = (ptype == "top") ? "tcoords" : "bcoords";
            var parentD =  (ptype == "top") ? "#topPdiv" :  "#bottomPdiv";
            var handleD = (ptype == "top") ? "topPoints tp" : "bottomPoints bp";
            var pointerD = (ptype == "top") ? "tp" : "bp";

            var existingCoords = $("."+coordDiv).length;
            if( existingCoords < points) {
                 for(var i =existingCoords; i <points; i++ ){
                  var bringbackDom = bringbackCoords(domE, coordDiv+i);

                  if(bringbackDom) {
                     $(parentD).append($(bringbackDom));
                  }
                  else {
                     $(parentD).append('<div class="'+coordDiv+'  '+coordDiv+i+'"><label>x'+i+'</label><input type="text"  placeholder="x'+i+'" name="'+ptype+'x'+i +'" /> &nbsp;<label>y'+i+'</label><input type="text"  placeholder="y'+ i+'" name="'+ptype+'y'+ i+'" /></div>');
                  }
                    $("#carouselImg").append("<div class='points "+handleD+i+"'></div>");
                    $("input[name="+ptype+"x"+i+"]").trigger("change");
                }
            }
            else {
                for(var i= points ; i<existingCoords; i++) {
                        $("."+coordDiv+i).remove();
                        $("."+pointerD+i).remove();

                }
            }
}



$(document).ready( function() {
        $('form input').on('keypress', function(e) {
            return e.which !== 13;
        });

        $("#clearColors").click(function(){
            $("#pccolors").val("");
            $(".altImg").remove();
        });
         $("#clearTexture").click(function(){
            $("#pcdesign").val("");
            $(".altImg").remove();

        });

        $("input[name=pctop]").change(function(){
            var topPoints = parseInt(this.value, 10);
            if(topPoints == 0) {
              $(".pcenterx").show();
              $(".pcentery").show();

            }
            else {
              $(".pcenterx").hide();
              $(".pcentery").hide();
            }
            xyInputs(tcoordsDom, topPoints, "top");

        });
        $("input[name=pcbot]").change(function(){
            var botPoints = parseInt(this.value, 10);
             xyInputs(bcoordsDom, botPoints, "bottom");
        });

        $("input[name=pcenterx]").change(function(){
           var mLeft = parseInt(this.value, 10);
           $("#carouselImg").css("margin-left", mLeft+"px");

        });
        $("input[name=pcentery]").change(function(){
           var mTop = parseInt(this.value, 10);
           $("#carouselImg").css("margin-top", mTop+"px");
        });

        $("#pccolors").blur(function(){
            showFileInp(filesDom);
        });
        $("#pcdesign").blur(function(){
           showFileInp(filesDom);
        });


$(document).on('change', '.tcoords input[type=text], .bcoords input[type=text]', function() {
       var inpE= this.name;
       var inpV = this.value;
        if(inpE.indexOf("top") > -1) {
            if(inpE.indexOf("x") > -1) {
                var pos= inpE.split('x');
                $(".points.tp"+pos[1]).css("left", inpV+"px");
            }
            else  if(inpE.indexOf("y") > -1) {
                var pos= inpE.split('y');
                $(".points.tp"+pos[1]).css("top", inpV+"px");
            }
    }
    else if(inpE.indexOf("bottom") > -1) {
         if(inpE.indexOf("x") > -1) {
                var pos= inpE.split('x');
                $(".points.bp"+pos[1]).css("left", inpV+"px");
            }
            else  if(inpE.indexOf("y") > -1) {
                var pos= inpE.split('y');
                $(".points.bp"+pos[1]).css("top", inpV+"px");
            }
    }
  // Does some stuff and logs the event to the console
});

        var filesDom = $(".altImg");
        var tcoordsDom = $(".tcoords");
        var bcoordsDom = $(".bcoords");

        $.each($(".tcoords"), function(index, elem) {
          $("#carouselImg").append("<div class='points tp"+index+"'></div>");
        });

        $.each($(".bcoords"), function(index, elem) {
               $("#carouselImg").append("<div class='points bp"+index+"'></div>");
          });

       $("#carouselImg img").load(function() {
                            var h=  $(this).height();
                            var w = $(this).width();
                            $("#carouselImg").css("height", h+"px");
                            $("#carouselImg").css("width", w+"px");
                            $("#carouselDimensions .imgheight").html(h);
                            $("#carouselDimensions .imgwidth").html(w);
         }).each(function(){
                            if(this.complete) {
                              $(this).trigger('load');
                            }
                          });
$(".bcoords input[type=text]").trigger("change");
$(".tcoords input[type=text]").trigger("change");
$("input[name=pcenterx]").trigger("change");
$("input[name=pcentery]").trigger("change");

$("button[type='reset']").on("click", function(event){
       $("#patterns").empty();
        $("#fileupl").empty();
 });
  //  $.validator.addMethod("acceptImageTypes", $.validator.methods.accept,  "Selected File must be an image.");

  // $("#piecesForm").validate({
  //                rules: {
  //                   pcname: { required: true},
  //                   pctop: { required: true, number:true},
  //                   pcbot: { required: true, number:true},
  //                   pcbody: { required:true},
  //                   pcstatus: { required:true},
  //                   pccolors: { required:true},
  //                   pcdesign: { required: true},
  //               },
  //               messages: {
  //                   pcname: { required: "This is required"},
  //                   pctop: { required: "This is required", number:"Please enter a number"},
  //                   pcbot: { required: "This is required", number:"Please enter a number"},
  //                   pcbody: { required: "This is required"},
  //                   pcstatus: { required: "This is required"},
  //                   pccolors: { required: "This is required"},
  //                   pcdesign: { required: "This is required"},
  //               },
  //                errorPlacement: function(error, element) {
  //                   if (element.attr("type") == "file" ) {
  //                       var inpParent = element.parents(".input-group");
  //                       error.insertAfter(inpParent);
  //                   }
  //                   else {
  //                       error.insertAfter(element);
  //                   }
  //               },
  //               submitHandler: function(form) {
  //                      $(form).submit();
  //                       return false;
  //                   }
  //               });


        // $.validator.addClassRules("imguploads", {
        //     required:true
        //    // acceptImageTypes: "jpg|png|gif"
        // });

     });