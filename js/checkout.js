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

  });

   $(".cartUpdate").on('click', function(e){
      var thisId = (this.id).split("_");
      var qtyObj = $("tr#cart"+thisId[1]).find(".cartQty");
      var priceObj = $("tr#cart"+thisId[1]).find(".cartPrice");
      var rowTotalObj = $("tr#cart"+thisId[1]).find(".rowTotal");

      var currQty = $.trim(qtyObj.html());
      var currPrice = parseFloat($.trim(priceObj.html()), 10);
      currQty = parseInt(currQty, 10);
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


 });