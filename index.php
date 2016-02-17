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
else if(isset($_GET["account"])) {
    $currenttab = 'account';
}
else if(isset($_GET["checkout"])) {
    $currenttab = 'checkout';
}
else if(isset($_GET["single"])) {
    $currenttab = 'single';
}
else if(isset($_GET["myacount"])){
  $currenttab = 'myaccount';
}
else if(isset($_GET["forgotpassword"])){
  $currenttab = 'forgotpassword';
}
else {
    $currenttab = 'home';
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
<!-- <link href="css/admin.css" type="text/css" rel="stylesheet" media="all"> -->
<!-- js -->
<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap-3.1.1.min.js"></script>
<!-- //js -->
<!-- cart -->
<script src="js/simpleCart.min.js"> </script>
<!-- Login Script-->
  <script src="js/jquery.validate.min.js" type="text/javascript"></script>
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
                      var email_add = $(form).find("input[name='email']").val();
                        $.ajax({
                            type: "POST",
                            url: "<?php echo SITE_URL ?>login.php",
                            data: $(form).serialize(),
                            timeout: 3000,
                            success: function(data) {
                                if (data && data == "SUCCESS") {
                                   // window.location = "<?php echo SITE_URL ?>index.php";
                                    $("#loginForm").css("display","none");
                                    $(".userprofile #loginemail").html(email_add);
                                    $(".userprofile").css("display","block");

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

<?php if($currenttab == "home") { ?>
	<style>
	#imageBox .hoverImg, #imageBox1 .hoverImg1, #imageBox2 .hoverImg2, #imageBox3 .hoverImg3  {
	            position: absolute;
	            /*left: 0;*/
	            top: 0;
	            display: none;
	        }
	        #imageBox:hover .hoverImg, #imageBox1:hover .hoverImg1, #imageBox2:hover .hoverImg2, #imageBox3:hover .hoverImg3 {
	            display: block;
	            cursor:pointer;
	        }
	</style>
<?php  } ?>
<?php if($currenttab == "products") { ?>
	<!-- the jScrollPane script -->
	<script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>
			<script type="text/javascript" id="sourcecode">
				$(function()
				{
					$('.scroll-pane').jScrollPane();
				});
			</script>
	<!-- //the jScrollPane script -->
	<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
	<!-- the mousewheel plugin -->
<?php  } ?>

<?php if($currenttab == "customize") { ?>
	<style>
		.avlOptions div{
			float: left;
		}
		.avlOptions #imgOptions{
			position: relative;
			width:100%;
			margin-top: -25px;
			text-align: center;
		}
		.avlOptions #imgOptions img{
			margin-bottom: 19px;
			margin-top: 17px;
		}
		.avlOptions img{
		    margin-bottom: 20px;
		    margin-top: 40px;
		    margin-right: 7px;
	    	width: 60px;
		}
		#ear{
			width:100%;
		}

		#ear img{
			 width: 100%;

		}
		#chkBtn input[type="submit"] {
	        border: 2px solid #F07818;
	    color: #fff;
	    background: #F07818;
	    cursor: pointer;
	    padding: 7px 0;
	    width: 30%;
	    border-radius: 4px;
	    display: inline-block;
	    margin: 1em 0 0;
	    font-size: 1em;
	    outline: none;
	    -webkit-transition: all 0.5s;
	    -moz-transition: all 0.5s;
	    transition: all 0.5s;
	    position: relative;
	    margin-right: 14px;
	    left: -12px;
	    margin-bottom: 20px;
	}
	  	#chkBtn input[type="submit"]:hover {
	      background-color: transparent;
	      color: #5D4B33;
	      border-color: #5D4B33;
	  	}
		#chkBtn{
			width:100%;
		}

		#chkBtn1{
			width: 100%;
		}

		#chkBtn1 input[type="submit"] {
	      border: 2px solid #F07818;
	      color: #fff;
	      background: #F07818;
	      cursor: pointer;
	      padding: 7px 0;
	      width: 20%;
	      border-radius: 4px;
	      display: inline-block;
	      margin: 1em 0 0;
	      font-size: 1em;
	      outline: none;
	      -webkit-transition: all 0.5s;
	      -moz-transition: all 0.5s;
	      transition: all 0.5s;
	      top: -20px;
	      position: relative;
		  margin-top: 28px;
		  margin-bottom: 0px;
		  float: right;
		}
		#chkBtn1 input[type="submit"]:hover {
	      background-color: #5D4B33;
	      color: #FFFFFF;
	      border-color: #5D4B33;
	  	}

		div#type {
		   border:0px solid;
		}

		div#type input[type="radio"] {
		    margin-top: 11px;
		    margin-left: 50px;
		    vertical-align: baseline;
		}
		div#material{
			float: left;
		    margin-top: 10px;
		    position: relative;
		    font-size: 17px;
		    width:16%;
		}

		div#material input[type="radio"] {
		    margin-bottom: 30px;
		    margin-top: 30px;
		    vertical-align: middle;
		}
		div#sampleImgs{
		    border: 0px solid;
		    width: 100%;
		    float: left;
		    position: relative;
		    top: 10px;
		}
		div#sampleImgs img{
			margin: 20px 0px 20px 5px;
		}
		div#sampleImgs h4 {
		    margin-bottom: 1%;
		    font-size: 15px;
		    margin-top: 1%;
		}
		div#sampleImgs span{
		    position: relative;
	 	   float: right;
		}
		div#sampleImgs h4 span a{
		   font-size: 12px;
		   float: right;
		}
		div#designerPick{
			position: relative;
			top: 13px;
		}
		div#designerPick h4{
			font-size: 15px;
		}
		div#designerPick img {
		    width: 100%;
		    margin-bottom: 15px;
		}

		.about {
		    background: #fff;
		    padding: 65px 0 0px;
		}

	/**** LIGHTBOX CODE ******/
		.black_overlay{
			display: none;
			position: absolute;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			background-color: black;
			z-index:1001;
			-moz-opacity: 0.8;
			opacity:.80;
			filter: alpha(opacity=80);
		}
		.white_content {
			display: none;
		    position: absolute;
		    top: 0;
		    left: 0;
		    width: 100%;
		    height: 100%;
		    padding: 16px;
		    border: 1px solid orange;
		    z-index: 1002;
		    overflow: auto;
		}
		#light a, #light1 a, #light2 a{
			color: #fff;
		    text-transform: Uppercase;
		    float: right;
		}
		div#light img, div#light1 img, div#light2 img {
		    margin: 10px 14px 6px 5px;
		}

	</style>
<?php  } ?>

<?php if($currenttab == "join") { ?>
	<style>
		.contact ul li {
		    margin-left: 34px;
		    color: #999;
		}
		.contact-form input[type="text"] {
			width:32.3%;
		}
		.contact-form textarea {
			display: inline-block;
			background:  #fff;
			border: 1px solid #5D4B33;
			width: 32.3%;
			outline: none;
			padding: 10px 15px 10px 15px;
			font-size: 13px;
			color: #999;
			margin-bottom: 1.5em;
		}
		.contact-form textarea[type="text"]:nth-child(2) {
		    margin: 0 1em;
		}
	</style>
<?php  } ?>

<?php if($currenttab == "contact") { ?>
	<style>
		.contact-form input[type="text"] {
			width:32.3%;
		}
		.contact-form input[type="text"]:nth-child(2) {
		    margin: 0 1em;
		}
	</style>
<?php  } ?>

<?php if($currenttab == "sr" || $currenttab == "privacy" || $currenttab == "tc"){?>
	<style>
	.about {
		background-color: #fff;
	}
</style>
<?php }?>

<?php if($currenttab == "checkout") { ?>

    <style>
      .address {
          padding: 10px 0;
          margin-bottom: -30px;
      }
      .contact-form textarea{
        width: 90.5%;
      }
      .about .container{
        height: 150px;
      }
      .about-inform {
        margin-top:-10px;
      }
      .contact-form input[type="radio"] {
          font-size: 1em;
          color: #F07818;
      }
      .rdbtn {
          color: #F07818;
      }
      #chkBtn input[type="submit"] {
          border: 2px solid #F07818;
          color: #fff;
          background: #F07818;
          cursor: pointer;
          padding: 7px 0;
          width: 15%;
          border-radius: 4px;
          display: inline-block;
          margin: 1em 0 0;
          font-size: 1em;
          outline: none;
          -webkit-transition: all 0.5s;
          -moz-transition: all 0.5s;
          transition: all 0.5s;
          top: -28px;
          position: relative;
      }
      #chkBtn1 input[type="submit"]:hover {
          background-color: #5D4B33;
          color: #FFFFFF;
          border-color: #5D4B33;
      }
      #chkBtn1 input[type="submit"] {
          border: 2px solid #F07818;
          color: #fff;
          background: #F07818;
          cursor: pointer;
          padding: 7px 0;
          width: 15%;
          border-radius: 4px;
          display: inline-block;
          margin: 1em 0 0;
          font-size: 1em;
          outline: none;
          -webkit-transition: all 0.5s;
          -moz-transition: all 0.5s;
          transition: all 0.5s;
          top: -28px;
          position: relative;
          margin-right: 0px;
      }
      #chkBtn input[type="submit"]:hover {
          background-color: #5D4B33;
          color: #FFFFFF;
          border-color: #5D4B33;
      }
      .contact-form input[type="text"] {
          width: 30%;
      }
      .cart-item img {
          width: 75%;
      }
      article.accordion {
          display: block;
          width: 100%;
          margin: 0 auto;
          background-color: #666;
          overflow: auto;
          border-radius: 5px;
          box-shadow: 0 3px 3px rgba(0, 0, 0, 0.3);
      }
      article.accordion section {
          position: relative;
          display: block;
          float: left;
          /*width: 2em;*/

          width: 5.3em;
          /*height: 12em;*/

          height: 54.8em;
          margin: 0.5em 0 0.5em 0.5em;
          color: #333;
          background-color: #333;
          overflow: hidden;
          border-radius: 3px;
      }
      article.accordion section h2 {
          position: absolute;
          font-size: 1em;
          font-weight: bold;
          width: 12em;
          height: 2em;
          top: 12em;
          left: 0;
          text-indent: 1em;
          padding: 0;
          margin: 0;
          color: #ddd;
          -webkit-transform-origin: 0 0;
          -moz-transform-origin: 0 0;
          -ms-transform-origin: 0 0;
          -o-transform-origin: 0 0;
          transform-origin: 0 0;
          -webkit-transform: rotate(-90deg);
          -moz-transform: rotate(-90deg);
          -ms-transform: rotate(-90deg);
          -o-transform: rotate(-90deg);
          transform: rotate(-90deg);
      }
      article.accordion section h2 a {
          display: block;
          width: 100%;
          line-height: 2em;
          text-decoration: none;
          color: inherit;
          outline: 0 none;
      }
      article.accordion section:target {
          /*width: 30em;*/

          width: 70%;
          padding: 0 1em;
          color: #333;
          background-color: #fff;
          height: 765px;
      }
      article.accordion section:target h2 {
          position: relative;
          font-size: 2.3em;
          text-indent: 0;
          color: #333;
          top: -40px;
          -webkit-transform: rotate(0deg);
          -moz-transform: rotate(0deg);
          -ms-transform: rotate(0deg);
          -o-transform: rotate(0deg);
          transform: rotate(0deg);
      }
      p.accnav {
          font-size: 1.1em;
          text-transform: uppercase;
          text-align: right;
      }
      p.accnavB {
          font-size: 1.1em;
          text-transform: uppercase;
          text-align: left;
          position: relative;
          top: 21px;
      }
      p.accnav a,
      p.accnavB a {
          text-decoration: none;
          color: #999;
      }
      .cart-items table {
          border: 0px solid;
          font-size: 16px;
      }
      .cart-items table th {
          width: 168px;
          padding: 5px;
      }
      .cart-items table tr {
          border-bottom: 1px solid;
      }
      .breadcrumb-body {
          list-style: outside none none;
          margin: 30px auto;
          width: 600px;
      }
      .breadcrumb-body li {
          width: 135px;
          display: inline-block;
      }
      .breadcrumb-body .current .breadcrumb-stage-name {
          color: #000;
      }
      .breadcrumb-body li .breadcrumb-stage-name {
          margin: 0 auto;
          padding: 2px 5px;
          text-align: center;
          /*font-size: 13px;*/

          font-size: 16px;
      }
      .breadcrumb-body li .breadcrumb-stage-container {
          position: relative;
      }
      .breadcrumb-body li .breadcrumb-stage-container .breadcrumb-dots {
          color: #ccc;
          font-size: 23px;
          font-weight: bolder;
          position: absolute;
          top: 0;
          left: 70px;
          letter-spacing: 3px;
          cursor: default;
          line-height: 12px;
          -webkit-user-select: none;
          -moz-user-select: none;
          -ms-user-select: none;
          -o-user-select: none;
          user-select: none;
      }
      .breadcrumb-body .future.disable {
          pointer-events: none;
          cursor: default;
      }
      .breadcrumb-body .disable {
          pointer-events: none;
          cursor: default;
      }
      .breadcrumb-body .current .breadcrumb-stage {
          background-color: #5D4B33;
          color: #fff;
      }
      .breadcrumb-body li .breadcrumb-stage-container .breadcrumb-stage {
          height: 30px;
          width: 100px;
          border-radius: 16px;
          text-align: center;
          line-height: 27px;
          font-size: 14px;
          font-weight: normal;
          margin: 2px auto;
      }
      .breadcrumb-body .future .breadcrumb-stage-name {
          color: #666;
      }
      .breadcrumb-dots i.glyphicon.glyphicon-chevron-right {
          position: relative;
          left: 55px;
      }
      .cart-header,
      .cart-header2,
      .cart-header3 {
          width: 100%;
      }
      .about {
          background: #f3f3f3;
          padding: 5em 0;
      }
      .contact {
          background: #fff;
          padding: 5em 0;
      }
      .contact-form .btn2 {
          border: 2px solid #F07818;
          color: #fff;
          background: #F07818;
          cursor: pointer;
          padding: 7px 0;
          width: 30%;
          border-radius: 4px;
          display: inline-block;
          margin: 1em 0 0;
          font-size: 1em;
          outline: none;
          -webkit-transition: all 0.5s;
          -moz-transition: all 0.5s;
          transition: all 0.5s;
      }
      .single-grid1 {
          line-height: 5;
      }
      .address p{
        font-size: 18px;
      }
    </style>

    <!-- SCRIPTS START HERE -->
   <script>$(document).ready(function (c) {
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
   </script>
<?php } ?>

<?php if($currenttab == "single") {?>
	<!-- FlexSlider -->
	<script defer src="js/jquery.flexslider.js"></script>
	<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
	<script>
		// Can also be used with $(document).ready()
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
	<!--//FlexSlider -->
	<script src="js/imagezoom.js"></script>
  <!-- //js -->
  <style>
    .single{
      margin-bottom: -92px;
    }
    .single h2{
      margin-top: 65px;
    }
    .single-grid h3{
      font-size: 2.2em;
    }
    .single-grid p{
      text-align: justify;
    }
    .related-products h3{
      margin: -35px 0px;
    }
    h4.panel-title a{
      color: #F07818;
    }
    .panel-default > .panel-heading{
      background-color: transparent;
      border-color:transparent;
    }
    .panel{
      border:0px;
      box-shadow: none;
      text-align: justify;
    }
  </style>
<?php } ?>

  <?php if($currenttab == "account") {?>
    <style>
      #regForm #register {
          background: #F07818;
          color: #fff;
          font-size: 1em;
          padding: 5px 20px;
          border: 1px solid #F07818;
          transition: all .5s;
          -webkit-transition: all .5s;
          -moz-transition: all .5s;
          -o-transition: all .5s;
          font-family: 'Roboto-Regular';
      }
      #regForm #register:hover {
        background: #fff;
        color: #F07818;
      }
      label{
        margin: 0 0px 0 4px;
      }
      .register-top-grid span, .register-bottom-grid span{
        color: #F07818;
      }
    </style>
  <?php } ?>
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
					<h1 class="navbar-brand"><img src="images/logo1.png"  class="img-responsive" style="    position: relative;top: -13px;float: left;"/><a  href="index.php">Fitoori</a></h1>
				</div>
				<!--navbar-header-->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="index.php" <?php if($currenttab == "home") echo 'class="active"'; ?>>Home<!-- <img src="images/home.png"/> --></a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" <?php if($currenttab == "products") echo 'class="active"'; ?>>Products<b class="caret"></b></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="row">
									<div class="col-sm-6">
										<h4>Type</h4>
										<ul class="multi-column-dropdown">
											<li><a class="list" href="index.php?products">All</a></li>
											<li><a class="list" href="index.php?products">Bracelets</a></li>
											<li><a class="list" href="index.php?products">Earrings</a></li>
											<li><a class="list" href="index.php?products">Necklace</a></li>
											<li><a class="list" href="index.php?products">Pendant Sets</a></li>
										</ul>
									</div>
									<div class="col-sm-6">
										<h4>Category</h4>
										<ul class="multi-column-dropdown">
											<li><a class="list" href="index.php?products">All</a></li>
											<li><a class="list" href="index.php?products">Beaded</a></li>
											<li><a class="list" href="index.php?products">Fashion</a></li>
											<li><a class="list" href="index.php?products">Teracotta</a></li>
											<li><a class="list" href="index.php?products">Quilled</a></li>
										</ul>
									</div>
								</div>
							</ul>
						</li>
						<li class="dropdown grid">
							<a href="#" class="dropdown-toggle list1" data-toggle="dropdown" <?php if($currenttab == "customize") echo 'class="active"'; ?>>Customize<b class="caret"></b></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="row">
									<div class="col-sm-6">
										<h4>Items</h4>
										<ul class="multi-column-dropdown">
											<li><a class="list" href="index.php?customize">Bracelets</a></li>
											<li><a class="list" href="index.php?customize">Earrings</a></li>
											<li><a class="list" href="index.php?customize">Necklace</a></li>
											<li><a class="list" href="index.php?customize">Pendant Sets</a></li>
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
							<a href="#" class="dropdown-toggle list1" data-toggle="dropdown" <?php if($currenttab == "offers") echo 'class="active"'; ?>>Offers<!--<b class="caret"></b>--></a>
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
					<div class="search">
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
					<div id="loginBox">
            <form  id="loginForm" name="loginForm" role="form" <?php  if(isset($_SESSION["useremail"])) echo "style='display:none;'" ?>>
              <div class="alert alert-danger hide" id="loginFailedMsg" role="alert"> Login Failed! Please try again</div>
              <div class="alert alert-danger hide" id="systemErrorMsg" role="alert"> System Failure! please try later.</div>
              <fieldset id="body">
                  <div class="form-group">
                      <label for="email">Email Address</label>
                      <input class="form-control" placeholder="Email Address" name="email" type="email" autofocus>
                  </div>
                  <div class="form-group">
                      <label for="password">Password</label>
                      <input class="form-control" placeholder="Password" name="pass" type="password" value="">
                  </div>
                  <input type="submit" id="login" value="Login" name="login">
                  <label for="checkbox"><input type="checkbox" id="checkbox"> <i>Remember me</i></label>
              </fieldset>
              <p>New User ? <a class="sign" href="index.php?account">Sign Up</a> <span><a href="index.php?forgotpassword">Forgot your password?</a></span></p>
            </form>
            <div class="userprofile"  <?php  if(!isset($_SESSION["useremail"])) echo "style='display:none;'" ?>>
               <ul class="dropdown-menu dropdown-user" id="userList" style=
               "display:block">
              <li><a id="loginemail" href="#"><?php  if(isset($_SESSION["useremail"])) echo $_SESSION["useremail"]; ?></a></li>
              <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i>Logout</a></li>
              <li class="divider"></li>
              <li><a href="index.php?myaccount"><i class="fa fa-user fa-fw"></i>My Account</a>
              </li>
              <li><a href="#"><i class="fa fa-gear fa-fw"></i>My Orders</a>
              </li>
              <li><a href="#"><i class="fa fa-gear fa-fw"></i>My Wishlist</a>
              </li>
              </ul>
            </div>
            </div>
        </div>
				<div class="header-right cart">
					<a href="index.php?checkout"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></a>
					<div class="cart-box">
						<h4><a href="#">
							<span class="simpleCart_total"> $0.00 </span> (<span id="simpleCart_quantity" class="simpleCart_quantity"> 0 </span>)
						</a></h4>
						<p><a href="javascript:;" class="simpleCart_empty">Empty cart</a></p>
						<div class="clearfix"> </div>
					</div>
				</div>
				<div class="header-right track-box">
					<a href="#"><span aria-hidden="true"><img src="images/track.png"/></span></a>
					<div class="track">
						<form class="navbar-form">
							<p>Order ID &nbsp;<input type="text" class="form-control">
							<button type="submit" class="btn btn-default" aria-label="Left Align">
								Track
							</button></p>
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
	    include(SITE_ROOT. "products.html");
	}
	else if($currenttab == "customize") {
	    include(SITE_ROOT. "customize.html");
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
	else if($currenttab == "account") {
	    include(SITE_ROOT. "account.html");
	}
	else if($currenttab == "checkout") {
	    include(SITE_ROOT. "checkoutCart.html");
	}
	else if($currenttab == "single") {
	    include(SITE_ROOT. "single.html");
	}
  else if($currenttab == "myaccount") {
      include(SITE_ROOT. "myAccount.html");
  }
  else if($currenttab == "forgotpassword") {
      include(SITE_ROOT. "forgotPassword.html");
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
						<li><a href="#">FAQ</a></li>
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
						<li><a href="index.php?sr">Social Responsibility</a></li>
					</ul>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 footer-grid icons">
					<h4>Connect with Us</h4>
					<ul>
						<li><a href="#"><img src="images/i1.png" alt=""/>Facebook</a></li>
						<li><a href="#"><img src="images/i2.png" alt=""/>Twitter</a></li>
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
