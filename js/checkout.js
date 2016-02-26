$(document).ready(function (c) {

  $(".cartDelete").on('click', function(e){
      var thisId = (this.id).split("_");
      var qtyObj = $("div#cart"+thisId[1]).find("span.cartQty");
      var priceObj = $("div#cart"+thisId[1]).find("span.cartPrice");
      console.log(qtyObj);
      var currQty = $.trim(qtyObj.html());
      var currPrice = $.trim(priceObj.html());
      if(currQty-1 != 0) {
         qtyObj.html(currQty-1)
      }
      else {
        $("div#cart"+thisId[1]).html("1 Item removed");
      }
      window.cart.removeItem(currQty, currPrice);
      var updatedPrice=window.cart.getCartTotalPrice();
      $("#subTotal").html(window.cart.formatCurrency(updatedPrice));
      $("#grandTotal").html( window.cart.formatCurrency(updatedPrice+ window.cart.shipping[0]));

  });


 //Cart Details page Action
    $('#proceed').on('click', function (c) {
        $('#cartDetails').fadeOut('fast', function (c) {
            $('#cartDetails').addClass("hide");
            $('#cartDetails').removeClass("show");
            $('#chk').addClass("future disable");
            $('#chk').removeClass("current");

        });
        $('#shippingDetails').fadeIn('fast', function (c) {
            $('#shippingDetails').addClass("show");
           $('#shippingDetails').removeClass("hide");
            $('#shp').addClass("current");
            $('#shp').removeClass("future disable");
        });
    });


    // Shipping Details page Action
    $('#dlv').on('click', function (c) {
        $('#shippingDetails').fadeOut('fast', function (c) {
             $('#shippingDetails').addClass("hide");
            $('#shippingDetails').removeClass("show");
            $('#shp').addClass("future disable");
            $('#shp').removeClass("current");

        });

        $('#paymentDetails').fadeIn('fast', function (c) {
            $('#paymentDetails').addClass("show");
            $('#paymentDetails').removeClass("hide");
            $('#pymt').addClass("current");
            $('#pymt').removeClass("future disable");
        });
    });

 $('#dlvy').on('click', function (c) {
        $('#shippingDetails').fadeOut('fast', function (c) {
             $('#shippingDetails').addClass("hide");
            $('#shippingDetails').removeClass("show");
            $('#shp').addClass("future disable");
            $('#shp').removeClass("current");

        });

        $('#paymentDetails').fadeIn('fast', function (c) {
            $('#paymentDetails').addClass("show");
            $('#paymentDetails').removeClass("hide");
            $('#pymt').addClass("current");
            $('#pymt').removeClass("future disable");
        });
    });


   $('#bck').on('click', function (c) {
        $('#shippingDetails').fadeOut('fast', function (c) {
            $('#shippingDetails').addClass("hide");
           $('#shippingDetails').removeClass("show");
            $('#shp').addClass("future disable");
            $('#shp').removeClass("current");

        });
        $('#cartDetails').fadeIn('fast', function (c) {
             $('#cartDetails').addClass("show");
             $('#cartDetails').removeClass("hide");
            $('#chk').addClass("current");
            $('#chk').removeClass("future disable");
        });
    });

   $('#pcdChk').on('click', function (c) {
        $('#paymentDetails').fadeOut('fast', function (c) {
          $('#paymentDetails').addClass("hide");
            $('#paymentDetails').remove();
            $('#pymt').addClass("future disable");
            $('#pymt').removeClass("current");

        });

        $('#confirmDetails').fadeIn('fast', function (c) {
            $('#confirmDetails').addClass("show");
            $('#confirmDetails').removeClass("hide");
            $('#cnfm').addClass("current");
            $('#cnfm').removeClass("last future disable");
        });
    });

   $('#back').on('click', function (c) {
        $('#paymentDetails').fadeOut('fast', function (c) {
           $('#paymentDetails').addClass("hide");
          $('#paymentDetails').removeClass("show");
          $('#pymt').addClass("future disable");
          $('#pymt').removeClass("current");

        });

        $('#shippingDetails').fadeIn('fast', function (c) {
            $('#shippingDetails').addClass("show");
            $('#shippingDetails').removeClass("hide");
            $('#shp').addClass("current");
            $('#shp').removeClass("future disable");
        });
    });


 $('#CnfmBtn').on('click', function (c) {
        $('#confirmDetails').fadeOut('fast', function (c) {
          $('#confirmDetails').addClass("hide");
            $('#confirmDetails').removeClass("show");
            $('#cnfm').addClass("future disable");
            $('#cnfm').removeClass("current");

        });
        window.location.href = "<?php echo SITE_URL; ?>index.php";
        //window.location.href = "http://samplepapers.com/rezi/NewPlumms/index.html";
    });

 });