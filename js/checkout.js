$(window).load(function(){
  //your code here
 console.log("loaded");

 $(".cdesign").each(function(){
      var children_divs = $(this).children();
      var heightpx = 0;
      var toppx = 0;
      $.each(children_divs, function(indx, elem){
        if(elem.offsetTop > toppx) {
          toppx = elem.offsetTop;
          heightpx = elem.offsetHeight;
        }
      });
      $(this).css("height", (toppx+heightpx)+"px");

    });
});