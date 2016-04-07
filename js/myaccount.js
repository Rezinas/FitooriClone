function submitAjaxForms(form, posturl) {
  var $form = $(form);
  // let's select and cache all the fields
  var $inputs = $form.find("input, select, button, textarea");
  // serialize the data in the form
  var serializedData = $form.serialize();

  // let's disable the inputs for the duration of the ajax request
  $inputs.prop("disabled", true);

  // fire off the request to /form.php

  request = $.ajax({
    url: posturl,
    type: "post",
    data: serializedData
  });

  // callback handler that will be called on success
  request.done(function(response, textStatus, jqXHR) {
    if(response == "SUCCESS") {
      if(posturl.indexOf("profile") > -1){
        $('#response').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button> Profile Successfully Updated.</div>').show();
      }
      else if(posturl.indexOf("changePass") > -1){
        $('#response').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button> Password Successfully Changed.</div>').show();
      }
      else if(posturl.indexOf("address") > -1){
        $('#response').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button> Shipping Details Successfully Updated.</div>').show();
      }
    }
    else
    $('#response').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button> Update Failed.</div>');
  });

  // callback handler that will be called on failure
  request.fail(function(jqXHR, textStatus, errorThrown) {
    // log the error to the console
    console.error(
      "The following error occured: " + textStatus, errorThrown);
  });

  // callback handler that will be called regardless
  // if the request failed or succeeded
  request.always(function() {
    // reenable the inputs
    $inputs.prop("disabled", false);
  });
}
$(function() {

  jQuery.extend({
    getQueryParameters: function(str) {
      return (str || document.location.search).replace(/(^\?)/, '').split("&").map(function(n) {
        return n = n.split("="), this[n[0]] = n[1], this
      }.bind({}))[0];
    }
  });

    var queryParams = $.getQueryParameters();

  if (queryParams.myaccount == "profile") {
    $('#editProfile').fadeIn('fast');
  } else if (queryParams.myaccount == "orders") {
    $('#viewOrders').fadeIn('fast');
     $('#allorders').paging({limit:5});
  } else if (queryParams.myaccount == "credits") {
    $('#credits').fadeIn('fast');
  } else if (queryParams.myaccount == "wishlist") {
    $('#wishlist').fadeIn('fast');
  }

  $("#chngPwdForm").validate({
    rules: {
      pass: {
        required: true,
        minlength: 5
      },
      cpass: {
        required: true,
        equalTo: "#pass",
        minlength: 5
      }
    },
    messages: {
      pass: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      cpass: {
        required: "Please provide a confirm password",
        equalTo: "Password and Confirm password do not match",
        minlength: "Your password must be at least 5 characters long"

      }
    },
    submitHandler: function(form) {
      submitAjaxForms(form, "php/user.php?changePass");
      return false;
    }
  });


  $("#AddressForm").validate({
    rules: {
      fullname: {
        required: true,
        minlength: 2,
        maxlength: 80,
        lettersonly: "letters only mate"
      },
      email: {
        required: true,
        email: true
      },
      phone: {
        required: true,
        minlength: 10,
        numbersonly: "0-9"
      },
      messageArea: {
        required: true,
        minlength: 2,
        maxlength: 5000
      }
    },
    messages: {
      fullname: {
        required: "Please provide a first name",
        minlength: "Your fullname must be at least 2 characters long",
        maxlength: "Your fullname must not exceed 100 characters",
        lettersonly: "Letters and spaces only please"

      },
      messageArea: {
        required: "Please provide some details",
        minlength: "Your address must be at least 2 characters long",
        maxlength: "Your lastname must not exceed 5000 characters"

      },
      phone: {
        required: "Please provide a contact details",
        minlength: "Your contact number must be at least 10 characters long",
        numbersonly: "Enter only digits"
      },
      email: "Please enter a valid email address"
    },
    submitHandler: function(form) {
      submitAjaxForms(form, "php/user.php?address");
      return false;
    }
  });

  $("#profileForm").validate({
    rules: {
      fname: {
        required: true,
        minlength: 2,
        maxlength: 80,
        lettersonly: "letters only mate"
      },
      lname: {
        required: true,
        minlength: 2,
        maxlength: 80,
        lettersonly: "letters only mate"
      },
      email: {
        required: true,
        email: true
      },
      phone: {
        required: true,
        minlength: 10,
        numbersonly: "0-9"
      },
      gender: {
        required: true
      }

    },
    messages: {
      fname: {
        required: "Please provide a first name",
        minlength: "Your firstname must be at least 2 characters long",
        maxlength: "Your firstname must not exceed 100 characters",
        lettersonly: "Letters and spaces only please"
      },
      lname: {
        required: "Please provide a first name",
        minlength: "Your lasttname must be at least 2 characters long",
        maxlength: "Your lastname must not exceed 100 characters",
        lettersonly: "Letters and spaces only please"
      },
      phone: {
        required: "Please provide a contact details",
        minlength: "Your contact number must be at least 10 characters long",
        numbersonly: "Enter only digits"
      },
      gender: { // <- NAME of every radio in the same group
        required: true
      },
      email: "Please enter a valid email address"
    },
    submitHandler: function(form) {
      submitAjaxForms(form, "php/user.php?profile");
      return false;
    }
  });



  $('#passwd').on('click', function(c) {
    $(".pagecontent").fadeOut('fast');
    $('#chngPwd').fadeIn('fast');
  });
  $('#ep').on('click', function(c) {
    $(".pagecontent").fadeOut('fast');
    $('#editProfile').fadeIn('fast');
  });
  $('#ea').on('click', function(c) {
    $(".pagecontent").fadeOut('fast');
    $('#editAddress').fadeIn('fast');
  });
  $('#vo').on('click', function(c) {
    $(".pagecontent").fadeOut('fast');
    $('#viewOrders').fadeIn('fast');
  });
  $('#credit').on('click', function(c) {
    $(".pagecontent").fadeOut('fast');
    $('#credits').fadeIn('fast');
  });
  $('#wish').on('click', function(c) {
    $(".pagecontent").fadeOut('fast');
    $('#wishlist').fadeIn('fast');
  });



});