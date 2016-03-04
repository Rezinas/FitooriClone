<?php
   error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
   require_once($_SERVER['DOCUMENT_ROOT']."/plumms/utils/functions.php");
   $currenttab = "";
   if(isset($_GET["products"])) {
       $currenttab = 'products';
   }
   else if(isset($_GET["customize"])) {
       $currenttab = 'customize';
   }
   else if(isset($_GET["offers"])) {
       $currenttab = 'offers';
   }
   else if(isset($_GET["join"])) {
       $currenttab = 'join';
   }
   else if(isset($_GET["about"])) {
       $currenttab = 'about';
   }
   else if(isset($_GET["contact"])) {
       $currenttab = 'contact';
   }
   else if(isset($_GET["support"])) {
       $currenttab = 'support';
   }
   else if(isset($_GET["privacy"])) {
       $currenttab = 'privacy';
   }
   else if(isset($_GET["sr"])) {
       $currenttab = 'sr';
   }
   else if(isset($_GET["tc"])) {
       $currenttab = 'tc';
   }
   else if(isset($_GET["orders"])) {
       $currenttab = 'orders';
   }
   else if(isset($_GET["register"])) {
       $currenttab = 'register';
   }
   else if(isset($_GET["checkout"])) {
       $currenttab = 'checkout';
   }
   else if(isset($_GET["single"])) {
       $currenttab = 'single';
   }
   else if(isset($_GET["myaccount"])){
    if(!isset($_SESSION["userid"])) $currenttab = 'register';
    else $currenttab = 'myaccount';
   }
   else if(isset($_GET["logout"])){
     $currenttab = 'logout';
   }
   else {
       $currenttab = 'home';
   }

if(isset($_SESSION['orderStatus'])  && $_SESSION['orderStatus'] == "confirmed" && $currenttab != "orders" ) {
  unset($_SESSION['orderStatus']);
  unset($_SESSION['orderID']);
  unset($_SESSION['cartids']);
  unset($_SESSION['cartPrice']);
}


   ?>
<!DOCTYPE html>
<html>
   <head>
      <title>Fitoori</title>
      <!-- Custom Theme files -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="keywords" content="" />
      <script type="application/x-javascript">
         addEventListener("load", function() {
          setTimeout(hideURLbar, 0); }, false);
          function hideURLbar(){ window.scrollTo(0,1);
         }
      </script>
      <!-- //Custom Theme files -->
      <link rel="icon" type="image/ico" href="images/favicon.ico">
      <link href="css/bootstrap.css" type="text/css" rel="stylesheet" media="all">
      <link href="css/style.css" type="text/css" rel="stylesheet" media="all">
      <link href="css/font-awesome/css/font-awesome.css" rel="stylesheet" />
      <link href="css/form.css" rel="stylesheet" type="text/css" media="all" />

       <!-- js -->
      <script type="text/javascript"  src="js/jquery.min.js"></script>
      <script type="text/javascript" src="js/bootstrap-3.1.1.min.js"></script>
      <script src="js/jquery.validate.min.js" type="text/javascript"></script>
      <script src="js/additional-methods.js" type="text/javascript"></script>
      <script src="js/login.js" type="text/javascript"></script>
      <script src="js/cart.js" type="text/javascript"></script>

      <?php if($currenttab == "home") { ?>
        <link href="css/home.css" rel="stylesheet" type="text/css" media="all" />
      <?php  } ?>

       <?php if($currenttab == "join") { ?>
        <link href="css/join.css" rel="stylesheet" type="text/css" media="all" />
      <?php  } ?>

      <?php if($currenttab == "contact") { ?>
        <link href="css/contact.css" rel="stylesheet" type="text/css" media="all" />
      <?php  } ?>

      <?php if($currenttab == "support") { ?>
       <link href="css/support.css" rel="stylesheet" type="text/css" media="all" />
      <?php  } ?>

      <?php if($currenttab == "sr" || $currenttab == "privacy" || $currenttab == "tc"){?>
       <link href="css/tc.css" rel="stylesheet" type="text/css" media="all" />
      <?php }?>

      <?php if($currenttab == "checkout" || $currenttab == "orders") { ?>
        <link href="css/cartpage.css" type="text/css" rel="stylesheet" media="all">
        <script src="js/checkout.js"></script>
        <script src="js/checkoutMenu.js"></script>
      <?php  } ?>

      <?php if($currenttab == "single") {?>
        <link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
        <link href="css/single.css" type="text/css" rel="stylesheet" media="all">
        <script defer src="js/jquery.flexslider.js"></script>
        <script src="js/imagezoom.js"></script>
        <script>
           $(window).load(function() {
             $('.flexslider').flexslider({
            animation: "slide",
            controlNav: "thumbnails",
            minItems : 2,
            itemWidth: "100px",
            direction:"vertical"
             });
           });
        </script>
      <?php  } ?>

      <?php if($currenttab == "register") {?>
        <link href="css/register.css" type="text/css" rel="stylesheet" media="all">
      <?php } ?>

      <?php if($currenttab == "myaccount") { ?>
        <link href="css/myaccount.css" type="text/css" rel="stylesheet" media="all">
        <script src="js/myaccount.js" type="text/javascript"></script>
      <?php }?>

      <?php if($currenttab == "customize") { ?>
        <link rel="stylesheet" href="css/design.css">
        <script src="js/angular.min.js"></script>
        <script src="js/design.js"></script>
      <?php } ?>

      <?php if($currenttab == "products") { ?>
        <link href="css/rzslider.min.css" type="text/css" rel="stylesheet" media="all">
        <script src="js/angular.min.js"></script>
        <script src="js/rzslider.min.js"></script>
        <script src="js/searchapp.js"></script>
      <?php } ?>

      <?php if($currenttab == "logout") {
         session_start();//session is a way to store information (in variables) to be used across multiple pages.
         session_destroy();
         header("Location: index.php");//use for the redirection to some page
      }?>
   </head>
   <body>
      <!--header-->
      <div class="header">
         <!-- <div class="container"> -->
         <nav class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
               <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
               <span class="sr-only">Toggle navigation</span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               </button>
               <h1 class="navbar-brand"><img src="images/logo2.png"  class="img-responsive" id="logo"/><a  href="index.php" class="hidden-xs">Fitoori</a></h1>
            </div>
            <!--navbar-header-->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
               <ul class="nav navbar-nav">
                  <li>
                     <a href="index.php" <?php if($currenttab == "home") echo 'class="active"'; ?>>
                        Home<!-- <img src="images/home.png"/> -->
                     </a>
                  </li>
                  <li class="dropdown">
                     <a href="#" class="dropdown-toggle <?php if($currenttab == "products") echo 'active'; ?>" data-toggle="dropdown" >Products<b class="caret"></b></a>
                     <ul class="dropdown-menu multi-column columns-3">
                        <div class="row">
                           <div class="col-sm-6">
                              <h4>Type</h4>
                              <ul class="multi-column-dropdown">
                                 <li><a class="list" href="index.php?products">All</a></li>
                                 <li class="list">Bracelets - <span>Coming Soon<span></li>
                                 <li><a class="list" href="index.php?products">Earrings</a></li>
                                 <li class="list">Necklace - <span>Coming Soon<span></li>
                                 <li class="list">Pendant Sets - <span>Coming Soon<span></li>
                              </ul>
                           </div>
                           <div class="col-sm-6">
                              <h4>Category</h4>
                              <ul class="multi-column-dropdown">
                                 <li><a class="list" href="index.php?products">All</a></li>
                                 <li><a class="list" href="index.php?products">Beaded</a></li>
                                 <li><a class="list" href="index.php?products">Metal</a></li>
                                 <li><a class="list" href="index.php?products">Teracotta</a></li>
                              </ul>
                           </div>
                        </div>
                     </ul>
                  </li>
                  <li class="dropdown grid">
                     <a href="#" class="dropdown-toggle list1 <?php if($currenttab == "customize") echo 'active'; ?>" data-toggle="dropdown">Customize<b class="caret"></b></a>
                     <ul class="dropdown-menu multi-column columns-3">
                        <div class="row">
                           <div class="col-sm-6">
                              <h4>Items</h4>
                              <ul class="multi-column-dropdown">
                                 <li class="list">Bracelets - <span>Coming Soon<span></li>
                                 <li><a class="list" href="index.php?customize">Earrings</a></li>
                                 <li class="list">Necklace - <span>Coming Soon<span></li>
                                 <li class="list">Pendant Sets - <span>Coming Soon<span></li>
                              </ul>
                           </div>
                           <div class="col-sm-6">
                              <h4>Take a Tour</h4>
                              <ul class="multi-column-dropdown">
                                 <li><a class="list" href="index.php?customize"><i class="glyphicon glyphicon-film" style="    font-size: 15px;position: relative;top: 3px; margin-right: 3px;"></i> How to Videos</a></li>
                                 <li><a class="list" href="index.php?customize"><i class="glyphicon glyphicon-info-sign" style="font-size: 15px;position: relative;top: 3px; margin-right: 3px;"></i> How to Tutorials</a></li>
                              </ul>
                           </div>
                        </div>
                     </ul>
                  </li>
                  <li class="dropdown grid">
                     <a href="#" class="dropdown-toggle list1 <?php if($currenttab == "offers") echo 'active'; ?>" data-toggle="dropdown" >
                        Offers<!--<b class="caret"></b>-->
                     </a>
                  </li>
                  <li><a href="index.php?join" <?php if($currenttab == "join") echo 'class="active"'; ?>>Join Us</a></li>
               </ul>
               <!--/.navbar-collapse-->
            </div>
            <!--//navbar-header-->
         </nav>
         <div class="header-info">
            <div class="header-right search-box">
               <a href="#"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
               <div class="header-popup search">
                  <form class="navbar-form">
                     <input type="text" class="form-control">
                     <button type="submit" class="btn btn-default" aria-label="Left Align">
                     Search
                     </button>
                  </form>
               </div>
            </div>
            <div class="header-right login">
               <a href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
               <div class="header-popup" id="loginBox">
                  <form  id="loginForm" name="loginForm" role="form" <?php  if(isset($_SESSION["useremail"])) echo "style='display:none;'" ?>>
                     <div class="alert alert-danger hide" id="loginFailedMsg" role="alert"> Login Failed! Please try again</div>
                     <div class="alert alert-danger hide" id="systemErrorMsg" role="alert"> System Failure! please try later.</div>
                     <div class="alert alert-danger hide" id="forgotpwdMessage" role="alert"> Please provide yor email address, you will recieve an email with your password.</div>
                     <div class="alert alert-danger hide" id="fgtPwdMsg" role="alert">We have sent you an Email with password.</div>

                     <fieldset id="body">
                        <div class="form-group">
                           <label for="email">Email Address</label>
                           <input class="form-control" placeholder="Email Address" name="email" type="email" id="emailAddr" autofocus>
                        </div>
                        <div class="form-group">
                           <label for="password" id="pwdLabel">Password</label>
                           <input class="form-control" placeholder="Password" name="pass" type="password" value="" id="pwd">
                        </div>
                        <input type="submit" id="login" value="Login" name="login">
                        <input type="submit" id="submitEmail" value="Sumit" name="submit" class="hide">
                        <label for="checkbox"><input type="checkbox" id="checkbox"> <i>Remember me</i></label>
                     </fieldset>
                     <p>New User ? <a class="sign" href="index.php?register">Sign Up</a>
                     <span><a href="#" id="fgtpwd">Forgot your password?</a></span></p>
                  </form>
                  <div class="userprofile"  <?php  if(!isset($_SESSION["useremail"])) echo "style='display:none;'" ?>>
                     <ul class="dropdown-menu dropdown-user" id="userList" style=
                        "display:block">
                        <li>
                           <span class="username">
                           <a id="loginemail" href="#"><?php  if(isset($_SESSION["useremail"])) echo $_SESSION["useremail"]; ?></a>
                           </span>
                           <span class="logout">
                           <a href="index.php?logout"><i class="fa fa-sign-out fa-fw"></i>Logout</a>
                           </span>
                        </li>
                        <li><a href="index.php?myaccount=profile"><i class="fa fa-user fa-fw"></i>My Account</a>
                        </li>
                        <li><a href="index.php?myaccount=orders"><i class="fa fa-shopping-cart fa-fw"></i>My Orders</a>
                        </li>
                        <li><a href="index.php?myaccount=credits"><i class="fa fa-gift fa-fw"></i>My Credits</a>
                        <li><a href="index.php?myaccount=wishlist"><i class="fa fa-heart-o fa-fw"></i>My Wishlist</a>
                        </li>
                     </ul>
                  </div>
                  <div class="submitForm">
                    <form  id="submitForm" name="submitForm" role="form" style="display:none;">
                       <div class="alert alert-danger hide" id="forgotpwdMessage" role="alert"> Please provide yor email address, you will recieve an email with your password.</div>
                       <div class="alert alert-danger hide" id="fgtPwdMsg" role="alert">We have sent you an Email with password.</div>
                       <div class="alert alert-danger hide" id="errorMsg" role="alert">Some Error</div>
                       <fieldset id="body">
                          <div class="form-group">
                             <label for="email">Email Address</label><br/>
                             <input class="form-control" placeholder="Email Address" name="email" type="email" id="emailAddr" autofocus>
                          </div>
                          <input type="submit" id="submitEmail" value="Submit" name="submitEmail">
                       </fieldset>
                    </form>
                  </div>
               </div>
            </div>
            <div class="header-right cart">
               <a href="#"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span><span class="badge">0</span></a>
               <div class="header-popup cart-box">
                  <h4><a href="#">
                     <span class="simpleCart_total"> &#8377; 0.00 </span> (<span id="simpleCart_quantity" class="simpleCart_quantity"> 0 </span>)
                     </a>
                  </h4>
                  <p><a href="index.php?checkout">View cart</a></p>
                  <!-- <p><a href="javascript:window.cart.emptyCart();" class="simpleCart_empty">Empty cart</a></p> -->
                  <div class="clearfix"> </div>
               </div>
            </div>
            <div class="header-right track-box">
               <a href="#"><span aria-hidden="true"><img src="images/track.png"/></span></a>
               <div class="header-popup track">
                  <form class="navbar-form">
                     <p>Order ID &nbsp;<input type="text" class="form-control">
                        <button type="submit" class="btn btn-default" aria-label="Left Align">
                        Track
                        </button>
                     </p>
                  </form>
               </div>
            </div>
            <div class="clearfix"> </div>
         </div>
         <div class="clearfix"> </div>
         <!-- </div> -->
      </div>
      <!--//header-->
      <?php
         if($currenttab == "home") {
             include(SITE_ROOT. "index.html");
         }
         else if($currenttab == "products") {
              include(SITE_ROOT. "php/productsearch.php");
             include(SITE_ROOT. "products.html");
         }
         else if($currenttab == "customize") {
              // if(empty($username)) {
              //   echo '<link rel="stylesheet" href="css/customize.css">';
              //   include(SITE_ROOT. "customize.html");
              // }
              // else {
                // include(SITE_ROOT. "customize.html");
                include(SITE_ROOT. "php/design.php");
                include(SITE_ROOT. "design.html");
              // }
            }
         else if($currenttab == "offers") {
             include(SITE_ROOT. "offers.html");
         }
         else if($currenttab == "join") {
             include(SITE_ROOT. "joinus.html");
         }
         else if($currenttab == "about") {
             include(SITE_ROOT. "about.html");
         }
         else if($currenttab == "contact") {
             include(SITE_ROOT. "contact.html");
         }
         else if($currenttab == "support") {
             include(SITE_ROOT. "support.html");
         }
         else if($currenttab == "privacy") {
             include(SITE_ROOT. "privacy.html");
         }
         else if($currenttab == "sr") {
             include(SITE_ROOT. "SR.html");
         }
         else if($currenttab == "tc") {
             include(SITE_ROOT. "T&C.html");
         }
         else if($currenttab == "register") {
             include(SITE_ROOT. "register.html");
         }
         else if($currenttab == "checkout") {
             include(SITE_ROOT. "php/checkout.php");
             include(SITE_ROOT. "checkoutCart.html");
         }
         else if($currenttab == "single") {
             include(SITE_ROOT. "single.html");
         }
        else if($currenttab == "myaccount") {
            include(SITE_ROOT. "php/user.php");
            include(SITE_ROOT. "myAccount.html");
        }
        else if($currenttab == "orders") {
            include(SITE_ROOT. "php/checkout.php");
            include(SITE_ROOT. "php/orders.php");
            include(SITE_ROOT. "orderpage.html");
        }
         ?>
      <!--footer-->
      <div class="footer">
         <div class="container">
            <div class="footer-grids">
               <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 footer-grid">
                  <h4>company</h4>
                  <ul>
                     <li><a href="index.php?about">About Us</a></li>
                     <li><a href="index.php?products">Products</a></li>
                     <li><a href="index.php?join">Work Here</a></li>
                  </ul>
               </div>
               <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 footer-grid">
                  <h4>service</h4>
                  <ul>
                     <li>FAQ</li>
                     <li><a href="index.php?contact">Contact Us</a></li>
                     <li><a href="index.php?support">Support</a></li>
                  </ul>
               </div>
               <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 footer-grid">
                  <h4>order & returns</h4>
                  <ul>
                     <li><a href="index.php?tc#OrderStatus">Order Status</a></li>
                     <li><a href="index.php?tc#ShippingPolicy">Shipping Policy</a></li>
                     <li><a href="index.php?tc#ReturnPolicy">Return Policy</a></li>
                  </ul>
               </div>
               <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 footer-grid">
                  <h4>legal</h4>
                  <ul>
                     <li><a href="index.php?privacy">Privacy</a></li>
                     <li><a href="index.php?tc">Terms and Conditions</a></li>
                     <li>Social Responsibility</li>
                  </ul>
               </div>
               <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 footer-grid icons">
                  <h4>Connect with Us</h4>
                  <ul>
                  <li><img src="images/i1.png" alt=""/>&nbsp;Facebook</li>
                  <li><img src="images/i2.png" alt=""/>&nbsp;Twitter</li>
               </div>
            </div>
            <div class="clearfix"></div>
         </div>
      </div>
      </div>
      <!--//footer-->
      <div class="footer-bottom">
         <div class="container">
            <p> © 2016 Fitoori . All rights reserved.</p>
         </div>
      </div>
   </body>
</html>
