<?php
error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT']."/utils/functions.php");

//$_SESSION['current_page'] = "admins";

if(!empty($username)){
    //Redirect not logged in user
    //TBD: later have to add check for users who are not agents.
    //TBD: customers should not access agent dashboard
    //TBD: agents should redirect to dashboard and other users should go back to index page.
      header("Location: ".SITE_URL. "/admin/dashboard.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <link href="../css/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
</head>
<body class="body-Login-back">

    <div class="container">

        <div class="row">
            <div class="col-md-4 col-md-offset-4 text-center logo-margin ">
              <img src="images/logo.png" alt="" height="60" width="180"/>
                </div>
            <div class="col-md-4 col-md-offset-4">
                <div class="alert alert-danger hide" id="loginFailedMsg" role="alert"> Login Failed! Please try again</div>
                <div class="alert alert-danger hide" id="systemErrorMsg" role="alert"> System Failure! please try later.</div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form  id="loginForm" name="loginForm" role="form">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="pass" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                <input class="btn btn-lg btn-success btn-block" type="submit" value="Login" name="login" >
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <script src="../js/jquery.min.js" type="text/javascript"></script>
  <script src="../js/jquery.validate.min.js" type="text/javascript"></script>
  <script type="text/javascript">
	$(function() {
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
                        $.ajax({
                            type: "POST",
                            url: "<?php echo SITE_URL ?>admin/login.php",
                            data: $(form).serialize(),
                            timeout: 3000,
                            success: function(data) {
                                if (data && data == "SUCCESS") {
                                    window.location = "<?php echo SITE_URL ?>admin/dashboard.php";
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

});
 </script>

</body>

</html>