$(function() {

   $(".header-popup").click(function(event){
      event.stopPropagation();
   });

    $(".header-right.track-box").click(function(event){
      $(".header-popup").not("div.track").hide();
      $("div.track").toggle();

    });

    $(".header-right.search-box").click(function(event){
      $(".header-popup").not("div.search").hide();
      $("div.search").toggle();

    });
    $(".header-right.cart").click(function(event){
      $(".header-popup").not("div.cart-box").hide();
      $("div.cart-box").toggle();

    });
    $(".header-right.login").click(function(event){
      $(".header-popup").not("div#loginBox").hide();
      $("div#loginBox").toggle();

    });

    $("#loginForm").validate({
     rules: {
      email: {
       required: true,
       email: true
      },
      pass: {
       required: true,
       minlength: 5
      }
     },
     messages: {
      pass: {
       required: "Please provide a password",
       minlength: "Your password must be at least 5 characters long"
      },
      email: "Please enter a valid email address"
     },
     submitHandler: function(form) {
      var email_add = $(form).find("input[name='email']").val();
      $.ajax({
       type: "POST",
       url: "php/login.php",
       data: $(form).serialize(),
       timeout: 3000,
       success: function(data) {
        if (data && data == "SUCCESS") {
         // window.location = "<?php echo SITE_URL ?>index.php";
         $("#loginForm").hide();
         $(".userprofile #loginemail").html(email_add);
         $(".userprofile").show();

        } else {
         $(".alert-danger").addClass("hide");
         $("#loginFailedMsg").removeClass("hide");
        }
       },
       error: function() {
        $(".alert-danger").addClass("hide");
        $("#systemErrorMsg").removeClass("hide");
       }
      });
      return false;
     }
    });


    $("#loginForm #fgtpwd").click(function() {
     $("#loginForm").css('display', 'none');
     $("div.submitForm").show();
     $("#submitForm").css('display', 'block');
     $("#submitForm #forgotpwdMessage").removeClass("hide");
    });


    $("#loginBox #submitForm").validate({
     rules: {
      email: {
       required: true,
       email: true
      }
     },
     messages: {
      email: "Please enter a valid email address"
     },
     submitHandler: function(form) {
      var email_add = $(form).find("input[name='email']").val();
      $.ajax({
       type: "POST",
       url: "php/user.php?emailPass",
       data: $(form).serialize(),
       timeout: 3000,
       success: function(data) {
        if (data && data == "SUCCESS") {
         //$(".alert-danger").removeClass("alert-danger").addClass("alert-success").html("Email has been sent successfully");
         $(".alert-danger").addClass("hide");
         $("#fgtPwdMsg").removeClass("hide");
         $("div.submitForm").hide();
         $("#loginForm").css("display", "block");

        }
       },
       error: function() {
        $(".alert-danger").addClass("hide");
        $("#errorMsg").removeClass("hide");
       }
      });
      return false;
     }
    });

    $("#trackorderForm").validate({
      rules: {
        orderid: {required: true}
      },
      messages: {
        orderid: {required: "Please enter an order id."}
      },
     errorPlacement: function(error, element) {
            error.insertAfter(element.parents("p"));
    },
      submitHandler: function(form) {
          form.submit();
          return false;
      }
    });

});