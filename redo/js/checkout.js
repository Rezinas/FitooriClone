$(document).ready(function (c) {

  $(".removeItem").on('click', function(e){
    var thisId = (this.id).split("_");
    var qtyObj = $("tr#cart"+thisId[1]).find(".cartQty");
    var priceObj = $("tr#cart"+thisId[1]).find(".cartPrice");
    var currQty = parseInt($.trim(qtyObj.html()), 10);
    var currPrice = parseFloat($.trim(priceObj.html()), 10);

    for(var i=0; i<currQty; i++){
      window.cart.removeItem(thisId[1], currPrice);
    }

     $("tr#cart"+thisId[1]).remove();
    var updatedTotalQty = window.cart.getCartTotalItems();
      if(updatedTotalQty == 0) {
          $(".cartRows").remove();
          $("#emptyCartMsg").removeClass("hide");
          $("#cart-cost").hide();
      }
      else {
          var updatedPrice=window.cart.getCartTotalPrice();
          $("#subTotal").html(window.cart.formatCurrency(updatedPrice));
          $("#grandTotal").html( window.cart.formatCurrency(updatedPrice+ window.cart.shipping[0]));
      }
  });
  $(".cartDelete").on('click', function(e){
      var thisId = (this.id).split("_");
      var qtyObj = $("tr#cart"+thisId[1]).find(".cartQty");
      var priceObj = $("tr#cart"+thisId[1]).find(".cartPrice");
      var rowTotalObj = $("tr#cart"+thisId[1]).find(".rowTotal");
      var currQty = parseInt($.trim(qtyObj.html()), 10);
      var currPrice = parseFloat($.trim(priceObj.html()), 10);
      currQty--;
      var rowTotalPrice = currPrice * currQty;
      qtyObj.html(currQty);
      rowTotalObj.html(rowTotalPrice);
      if(currQty == 1) {
        $("tr#cart"+thisId[1]).find(".cartDelete").addClass('hide');
      }
      window.cart.removeItem(thisId[1], currPrice);

      var updatedPrice=window.cart.getCartTotalPrice();
      $("#subTotal").html(window.cart.formatCurrency(updatedPrice));
      $("#grandTotal").html( window.cart.formatCurrency(updatedPrice+ window.cart.shipping[0]));

  });

   $(".cartUpdate").on('click', function(e){
      var thisId = (this.id).split("_");
      var qtyObj = $("tr#cart"+thisId[1]).find(".cartQty");
      var priceObj = $("tr#cart"+thisId[1]).find(".cartPrice");
      var rowTotalObj = $("tr#cart"+thisId[1]).find(".rowTotal");

      var currQty = $.trim(qtyObj.html());
      var currPrice = parseFloat($.trim(priceObj.html()), 10);
      currQty = parseInt(currQty, 10);
      if(currQty >= 10 ) {
        alert("For bulk orders of more than 10, please contact us through the contact us page.");
        return;
      }
      qtyObj.html(++currQty);

      var rowTotalPrice = currPrice * currQty;
      rowTotalObj.html(rowTotalPrice);

      window.cart.updateCart(thisId[1], currPrice);
      $("tr#cart"+thisId[1]).find(".cartDelete").removeClass('hide');

      var updatedPrice=window.cart.getCartTotalPrice();
      $("#subTotal").html(window.cart.formatCurrency(updatedPrice));
      $("#grandTotal").html( window.cart.formatCurrency(updatedPrice+ window.cart.shipping[0]));
  });


   $("input[name='sameBilling'").click(function(e){
       if (!$(this).is(':checked')) {
            $("input[name='bill_address1").val("");
            $("input[name='bill_address2").val("");
            $("input[name='bill_city").val("");
            $("input[name='bill_state").val("");
            $("input[name='bill_postalcode").val("");
        }
        else {
            $("input[name='bill_address1").val($("input[name='ship_address1").val());
            $("input[name='bill_address2").val($("input[name='ship_address2").val());
            $("input[name='bill_city").val($("input[name='ship_city").val());
            $("input[name='bill_state").val($("input[name='ship_state").val());
            $("input[name='bill_postalcode").val($("input[name='ship_postalcode").val());

        }
   });


    $("#addressForm").validate({
         rules: {
          email_info: {required:true, email:true},
          phone: {required:true,  minlength:9, maxlength:10},
          ship_address1: { required: true  },
          ship_city: { required: true },
          ship_state: { required: true },
          ship_postalcode: { required: true }

         },
         messages: {
          email_info: {required:"Please enter your email address", email:"Please enter valid email address"},
          phone: {required:"Please enter your phone number"},
          ship_address1: { required: "Please Provide Address"  },
          ship_city: { required: "Please Provide City" },
          ship_state: { required: "Please Provide State" },
          ship_postalcode: { required: "Please Provide Postal Code" },
         submitHandler: function(form) {
          form.submit();
          return false;
         }
       }
    });

    $("a.placeorder").click(function(e){
       $.get("php/orders.php?confirmOrder", function(data, status){
          console.log(data);
          if(data == "SUCCESS") {
            $("#collapseThree").removeClass("in");
            $("#collapseFour").addClass("in");
            $("#panelThree span").removeClass("hide");

          }
      });
    });

 });

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