$(function() {

    //scroll event here

    //jQuery to collapse the navbar on scroll
    $(window).scroll(function() {
        if ($(".navbar").offset().top > 60) {
            $(".navbar-fixed-top").addClass("top-nav-collapse");
        } else {
            $(".navbar-fixed-top").removeClass("top-nav-collapse");
        }
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
     $("#submitfield #fgtcancel").click(function() {
       $("#submitForm").css('display', 'none');
       $("#submitForm #forgotpwdMessage").removeClass("hide");
       $("#loginForm").css('display', 'block');
       $("div.submitForm").hide();

      });


    $("#submitForm").validate({
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
        else if(data && data == "NOEMAIL") {
            $(".alert-danger").html("Email not found. Please register.");
        }
        else if(data && data == "ERROR") {
            $(".alert-danger").html("Technical Error. We apologize.");
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


    $('input[name=collapseGroup]').click(function(e){
      var elem  = $(this);
      var target  = elem.data("target");
      if(target == "#guestLogin") {
        $("#userLogin").removeClass("in");
      }
       if(target == "#userLogin") {
        $("#guestLogin").removeClass("in");
      }
    });

});