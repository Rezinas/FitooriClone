$(document).ready(function (c) {

  $(".cartDelete").on('click', function(e){
      var thisId = (this.id).split("_");
      var qtyObj = $("tr#cart"+thisId[1]).find("input.cartQty");
      var priceObj = $("tr#cart"+thisId[1]).find("span.cartPrice");
      var rowTotalObj = $("tr#cart"+thisId[1]).find("span.rowTotal");
      var currQty = parseInt($.trim(qtyObj.val()), 10);
      var currPrice = parseFloat($.trim(priceObj.html()), 10);
      currQty--;
      var rowTotalPrice = currPrice * currQty;
      if(currQty != 0) {
         qtyObj.val(currQty);
         rowTotalObj.html(rowTotalPrice);

      }
      else {
        $("tr#cart"+thisId[1]).remove();
      }
      window.cart.removeItem(currQty, currPrice);
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

   $(".cartUpdate").on('click', function(e){
      var thisId = (this.id).split("_");
      var qtyObj = $("tr#cart"+thisId[1]).find("input.cartQty");
      var priceObj = $("tr#cart"+thisId[1]).find("span.cartPrice");
      var currQty = $.trim(qtyObj.val());
      var currPrice = $.trim(priceObj.html());
      currQty = parseInt(currQty, 10);
      qtyObj.val(currQty +1)
      window.cart.updateCart(currQty, currPrice);

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