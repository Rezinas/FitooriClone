<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);


require_once($_SERVER['DOCUMENT_ROOT']."/utils/functions.php");
if(empty($username)){
    //Redirect not logged in user
    //TBD: later have to add check for users who are not agents.
    //TBD: customers should not access agent dashboard
      header("Location: ".SITE_URL. "admin/index.php");
}

if(isset($_GET["custom"]) && isset($_GET["deleteid"]) ) {
      $prdid = $_GET["deleteid"];
    $qry = "DELETE  from customdesign WHERE productid=?";
    $stmt1 = $dbcon->prepare($qry);
    $stmt1->bind_param('i', $prdid);
    $stmt1->execute();
    $stmt1->close();

    $qry = "DELETE from products WHERE productid=?";
    $stmt1 = $dbcon->prepare($qry);
    $stmt1->bind_param('i', $prdid);
    $stmt1->execute();
    $stmt1->close();
}

if(isset($_GET["product"]) && isset($_GET["deleteid"]) ) {
    $prdid = $_GET["deleteid"];

    $qry = "DELETE from customdesign WHERE productid=?";
    $stmt1 = $dbcon->prepare($qry);
    $stmt1->bind_param('i', $prdid);
    $stmt1->execute();
    $stmt1->close();

    $qry = "DELETE  from products WHERE productid=?";
    $stmt1 = $dbcon->prepare($qry);
    $stmt1->bind_param('i', $prdid);
    $stmt1->execute();
    $stmt1->close();
}


if(isset($_GET["pieces"]) && isset($_GET["deleteid"]) ) {
  $pieceid = trim($_GET["deleteid"]);
  $qry = "SELECT imagefile from pieceimages  WHERE pieceid=$pieceid";
  $stmt = $dbcon->prepare($qry);
  if(!$stmt->execute()){
      die('Error : ('. $dbcon->errno .') '. $dbcon->error);
  }
  $stmt->store_result();
  $stmt->bind_result($a);
  while ($stmt->fetch()) {
     unlink("../productImages/".$a);
  }
  $stmt->close();

  $qry = "SELECT carouselImg from pieces  WHERE id=$pieceid";
  $stmt = $dbcon->prepare($qry);
  if(!$stmt->execute()){
      die('Error : ('. $dbcon->errno .') '. $dbcon->error);
  }
  $stmt->store_result();
  $stmt->bind_result($a);
  $imgs=[];
  while ($stmt->fetch()) {
    if (file_exists("../productImages/".$a)) {
         unlink("../productImages/".$a);
    }
  }
  $stmt->close();

  $qry = "DELETE  from pieceimages WHERE pieceid=?";
  $stmt1 = $dbcon->prepare($qry);
  $stmt1->bind_param('i', $pieceid);
  $stmt1->execute();
  $stmt1->close();

  $qry = "DELETE  from pieces WHERE id=?";
  $stmt1 = $dbcon->prepare($qry);
  $stmt1->bind_param('i', $pieceid);
  $stmt1->execute();
  $stmt1->close();
        header("Location: ".SITE_URL. "admin/dashboard.php?pieces");

}

if(isset($_GET["components"]) && isset($_GET["cdeleteid"]) ) {
  $compid = trim($_GET["cdeleteid"]);
  echo $compid;
  $qry = "SELECT compimg from components WHERE compid='$compid'";
  echo $qry;
  $stmt = $dbcon->prepare($qry);
  if(!$stmt->execute()){
      die('Error : ('. $dbcon->errno .') '. $dbcon->error);
  }
  $stmt->store_result();
  $stmt->bind_result($a);
  while ($stmt->fetch()) {
    if (file_exists("../componentImages/".$a)) {
        unlink("../componentImages/".$a);
    }
  }
  $stmt->close();

  $qry = "DELETE  from components WHERE compid=?";
  $stmt1 = $dbcon->prepare($qry);
  $stmt1->bind_param('s', $compid);
  $stmt1->execute();
  $stmt1->close();
        header("Location: ".SITE_URL. "admin/dashboard.php?componentsearch");

}

$sitevariable  = "";
if(isset($_POST["siteUpdate"])) {

  $sitevariable  = $_POST['sitevariable'];

  //run the update query for the $pid.

  $updQuery1 =  "UPDATE sitevariable SET `is_undermaintenance` = ?";
  $stmt = $dbcon->prepare($updQuery1);
  $stmt->bind_param('i', $sitevariable);
  if(!$stmt->execute()){
    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
  }
  $stmt->close();
}



$currenttab = "";
if(isset($_GET["search"])) {
    $currenttab = 'search';
}
else if(isset($_GET["elements"])) {
    $currenttab = 'elements';
}
else if(isset($_GET["custom"])) {
    $currenttab = 'custom';
}
else if(isset($_GET["orders"])) {
    $currenttab = 'orders';
}
else if(isset($_GET["pieces"])) {
    $currenttab = 'pieces';
}
else if(isset($_GET["product"])) {
    $currenttab = 'product';
}
else if(isset($_GET["components"])) {
    $currenttab = 'components';
}
else if(isset($_GET["componentsearch"])) {
    $currenttab = 'componentsearch';
}
else if(isset($_GET["customearrings"])) {
       $currenttab = "customearrings";
}
else if(isset($_GET["designearrings"])) {
    $currenttab = 'designearrings';
}
else if (isset($_GET["report"])) {
    $currenttab = 'report';
}
else if (isset($_GET["email"])) {
    $currenttab = 'email';
}
else {
    $currenttab = 'dash';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboad</title>
    <link href="../css/theme.css" rel="stylesheet" />
    <link href="../css/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="../css/form.css" rel="stylesheet" type="text/css" media="all" />
    <link href="../css/main.css" type="text/css" rel="stylesheet" media="all">
    <link href="../css/admin.css" type="text/css" rel="stylesheet" media="all">

<?php if($currenttab == "search") { ?>
        <link rel="stylesheet" href="../css/products.css">
      <?php } ?>

<?php if($currenttab == "customearrings") { ?>
        <link rel="stylesheet" href="../css/landingPage.css">
      <?php } ?>

<?php if($currenttab == "designearrings") { ?>
        <link href="../css/animate.css" type="text/css" rel="stylesheet" media="all">
        <link href="../css/jquery.mCustomScrollbar.min.css" type="text/css" rel="stylesheet" media="all">
        <link href="../css/design.css" type="text/css" rel="stylesheet" media="all">
        <link href="../css/design_media.css" type="text/css" rel="stylesheet" media="all">


<?php  } ?>
<?php if($currenttab == "orders") { ?>
     <link rel="stylesheet" href="../css/cartpage.css">
     <link rel="stylesheet" href="../css/myaccount.css">
<?php  } ?>
   </head>
<body>

<div class="header">
              <nav class="navbar navbar-default navbar-fixed-top <?php if($currenttab == "customearrings" || $currenttab == "designearrings") echo 'homepage'; ?>" role="navigation" id="navbar"  >
                <div class="navbar-header">
                   <a  href="dashboard.php" class="navbar-brand"><img src="../images/logo_dark.png"  class="img-responsive"/></a>
                   <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                     <span class="sr-only">Toggle navigation</span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                   </button>

                </div>
                <!--navbar-links-->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                   <ul class="nav navbar-nav">
                           <li>
                              <a href="dashboard.php?customearrings" <?php if($currenttab == "customearrings" || $currenttab == "designearrings") echo 'class="active"'; ?>>Design Product</a>
                          </li>
                          <li >
                              <a href="dashboard.php?custom" <?php if($currenttab == "custom") echo 'class="active"'; ?>>Custom Designs</a>
                          </li>
                          <li class="dropdown grid">
                             <a href="javascript:void(0);" <?php if($currenttab == "elements" || $currenttab == "pieces" ) echo 'class="active"'; ?> data-toggle="dropdown">Elements</a>
                             <ul class="dropdown-menu">
                                         <li><a href="dashboard.php?elements"><i class="fa fa-search fa-fw"></i>Elements Search</a></li>
                                         <li> <a href="dashboard.php?pieces"><i class="fa fa-edit fa-fw"></i> Manage Elements</a></li>
                             </ul>
                          </li>

                          <li class="dropdown grid">
                             <a href="javascript:void(0);" <?php if($currenttab == "product" || $currenttab == "search" ) echo 'class="active"'; ?> data-toggle="dropdown">Products</a>
                             <ul class="dropdown-menu">
                                         <li><a href="dashboard.php?product"><i class="fa fa-edit fa-fw"></i> Manage Product</a></li>
                                         <li> <a href="dashboard.php?search"><i class="fa fa-search fa-fw"></i>Product Search</a></li>
                             </ul>
                          </li>

                           <li class="dropdown grid">
                             <a href="javascript:void(0);" <?php if($currenttab == "components" || $currenttab == "componentsearch" ) echo 'class="active"'; ?> data-toggle="dropdown">Components</a>
                             <ul class="dropdown-menu">
                                         <li><a href="dashboard.php?components"><i class="fa fa-edit fa-fw"></i> Manage Component</a></li>
                                         <li> <a href="dashboard.php?componentsearch"><i class="fa fa-search fa-fw"></i>Component Search</a></li>
                             </ul>
                          </li>

                          <li>
                              <a href="dashboard.php?orders"  <?php if($currenttab == "orders" || $currenttab == "email" ) echo 'class="active"'; ?>>Orders</a>
                          </li>
                     </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                                 <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
                                    <ul class="dropdown-menu dropdown-user">
                                                <li><a href="#"><i class="fa fa-user fa-fw"></i>User Profile</a>
                                                </li>
                                                <li><a href="#"><i class="fa fa-gear fa-fw"></i>Settings</a>
                                                </li>
                                                <li class="divider"></li>
                                                <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i>Logout</a>
                                                </li>
                                        </ul>
                        </li>
                    </ul>
                </div><!--/.navbar-collapse-->
                <!--//navbar-links-->
            </nav>
            <div class="clearfix"> </div>
</div>




    <!--  wrapper -->
    <div id="wrapper">
        <!--  page-wrapper -->
        <div id="page-wrapper">


<?php
if($currenttab == "dash") {
    include(SITE_ROOT. "/admin/dashboard.html");
}
else if($currenttab == "customearrings") {
    include(SITE_ROOT. "/customizationLandingPage.html");
}
else if($currenttab == "designearrings") {
    include(SITE_ROOT. "/php/design.php");
    include(SITE_ROOT. "/design.html");
}
else if($currenttab == "custom") {
    include(SITE_ROOT. "/admin/custom.php");
    include(SITE_ROOT. "/admin/custom.html");
}
else if($currenttab == "search") {
    include(SITE_ROOT. "/php/productsearch.php");
    include(SITE_ROOT. "/products.html");
}
else if($currenttab == "elements") {
    include(SITE_ROOT. "/admin/elements.php");
    include(SITE_ROOT. "/admin/elements.html");
}
else if($currenttab == "product") {
    include(SITE_ROOT. "/admin/products.php");
    include(SITE_ROOT. "/admin/product.html");
}
else if($currenttab == "components") {
    include(SITE_ROOT. "/admin/components.php");
    include(SITE_ROOT. "/admin/components.html");
}
else if($currenttab == "componentsearch") {
    include(SITE_ROOT. "/admin/componentsearch.php");
    include(SITE_ROOT. "/admin/componentsearch.html");
}
else if($currenttab == "pieces") {
    include(SITE_ROOT. "/admin/pieces.php");
    include(SITE_ROOT. "/admin/pieces.html");
}
else if($currenttab == "orders") {
    include(SITE_ROOT. "/admin/orders.html");
}
else if($currenttab == "email") {
    include(SITE_ROOT. "/admin/email.html");
}
else if($currenttab == "report") {
    include(SITE_ROOT. "/admin/report.html");
}
?>





        </div>
        <!-- end page-wrapper -->
    </div>
    <!-- end wrapper -->

    <!-- Core Scripts - Include with every page -->
    <script src="../js/jquery.min.js" type="text/javascript"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>



   <?php if($currenttab == "custom") { ?>
    <script type='text/javascript'>//<![CDATA[
    $(document).ready( function() {
          $('input:radio[name=adminUser]').change(function () {
            if ($("input[name='adminUser']:checked").val() == '0') {
                $(".product-grid.userDes").hide();
                $(".product-grid.adminDes").show();
                $(".product-grid.guestDes").hide();
            }
            else if ($("input[name='adminUser']:checked").val() == '1') {
                $(".product-grid.adminDes").hide();
                $(".product-grid.userDes").show();
                $(".product-grid.guestDes").hide();

            }
            else if ($("input[name='adminUser']:checked").val() == '2') {
                $(".product-grid.adminDes").hide();
                $(".product-grid.userDes").hide();
                $(".product-grid.guestDes").show();
            }
            else{
                $(".product-grid.guestDes").show();
                $(".product-grid.adminDes").show();
                $(".product-grid.userDes").show();
            }
        });

        $('input:radio[name=prdAdded]').change(function () {
            if ($("input[name='prdAdded']:checked").val() == '0') {
                $(".product-grid.prdDes").hide();
                $(".product-grid.customDes").show();
            }
            else if ($("input[name='prdAdded']:checked").val() == '1') {
                $(".product-grid.customDes").hide();
                $(".product-grid.prdDes").show();
            }
            else{
                $(".product-grid.customDes").show();
                $(".product-grid.prdDes").show();
            }
        });
     });
    //]]>
    </script>
   <?php } ?>

   <?php if($currenttab == "designearrings") { ?>
  <script src="../js/angular.min.js"></script>
  <script src="../js/jquery.mCustomScrollbar.js"></script>
  <script src="../js/scrollbars.min.js"></script>
  <script src="../js/design.js"></script>

   <?php } ?>
   <?php if($currenttab == "search") { ?>
  <link href="../css/rzslider.min.css" type="text/css" rel="stylesheet" media="all">
        <script src="../js/angular.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-animate.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-sanitize.js"></script>
        <script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-2.1.3.js"></script>
        <script src="../js/rzslider.min.js"></script>
        <script src="../js/searchapp.js"></script>
   <?php } ?>

   <?php if($currenttab == "elements") { ?>
  <link href="../css/rzslider.min.css" type="text/css" rel="stylesheet" media="all">
  <script src="../js/angular.min.js"></script>
  <script src="../js/rzslider.min.js"></script>
  <script src="../js/elementapp.js"></script>
   <?php } ?>

   <?php if($currenttab == "componentsearch") { ?>
  <link href="../css/rzslider.min.css" type="text/css" rel="stylesheet" media="all">
  <script src="../js/angular.min.js"></script>
  <script src="../js/rzslider.min.js"></script>
  <script src="../js/componentapp.js"></script>
   <?php } ?>

   <?php if($currenttab == "email") { ?>
  <script src="../js/jquery.validate.min.js" type="text/javascript"></script>
  <script src="../js/additional-methods.js" type="text/javascript"></script>
              <script type="text/javascript">
  $(function() {

        $("#emailForm").validate({
            rules: {
                subject:{
                    required: true
                },
                messageArea: {
                    required: true
                }
            },
            messages: {
                subject:{
                    required: "Please enter subject"
                },
                messageArea: {
                    required: "The message cannot be empty"
                }
            },
            submitHandler: function(form) {
                      $.ajax({
                       type: "POST",
                       url: "../php/ajax.php?sendemail",
                       data: $(form).serialize(),
                       timeout: 3000,
                       success: function(data) {
                            if(data == "EMAIL SENT") {
                                $(".alert").removeClass("hide");
                            }
                       },
                       error: function (request, status, error) {
                            alert(request.responseText);
                        }
                      });
                    return false;
                }
            });
        });
 </script>
   <?php } ?>


	<?php if($currenttab == "pieces") { ?>
  <script src="../js/jquery.validate.min.js" type="text/javascript"></script>
  <script src="../js/additional-methods.js" type="text/javascript"></script>
  <script src="../js/pieces.js" type="text/javascript"></script>
   <?php } ?>

   <?php if($currenttab == "orders") { ?>
           <script type="text/javascript"  src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
        <script type="text/javascript"  src="../js/paging.js"></script>
    <script type='text/javascript'>//<![CDATA[
      $(document).ready( function() {
        // $('#orderstb').paging({limit:5});
        $(".orderaction").change(function(){
           var oid = this.id.split("_");
           var ostat = this.value;
           if(ostat == 0) return;
            var payload = { "status" : ostat, "oid" : oid[1]};

              $.ajax({
                type: "POST",
                data: payload,
                url: "../php/ajax.php?orderUpdate",
                success: function(data) {
                    location.reload();
                  }
              });
        });

      });
          //]]>
    </script>
  <?php  } ?>


   <?php if($currenttab == "product") { ?>
  <script src="../js/jquery.validate.min.js" type="text/javascript"></script>
  <script src="../js/additional-methods.js" type="text/javascript"></script>

    <script type='text/javascript'>//<![CDATA[
    function readURL(input, outputImg) {
        var files = input.files ? input.files : input.currentTarget.files;
        if (files && files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(outputImg).attr('src', e.target.result);
            }
            reader.readAsDataURL(files[0]);
        }
    }

        $(document).on('change', '.btn-file :file', function() {
                var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
        });
        $(document).ready( function() {
            $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
                var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;
                if( input.length ) {
                    input.val(log);
                } else {
                     if( log ) alert(log);
                }
                var inpname = this.name;
                 readURL(this, ".imgpreview."+inpname+" img");
            });

            $('#productForm input[type="radio"]').on('change', function() {
                    var elemname = this.name;
                    if($(this).val() == "1") $("."+elemname+"Imgdiv").removeClass("hide");
                    else if($(this).val() == "0") $("."+elemname+"Imgdiv").addClass("hide");
                });

             $("#productForm").validate({
                 rules: {
                    pname: { required: true},
                    pprice: { required: true, number:true},
                    pquantity: { required: true, number:true},
                    sdesc: { required:true},
                    pdesc: { required:true},
                    addinfo: { required:true},
                    featured: { required: true},
                    pcategory: {required: true },
                    pitem: { required: true },
                    pavail: { required: true },
                    pstatus: { required: true },
                    mainfile: { required: {
                             depends: function(element) {
                                    return ($("#mainreadonly").val() == '');}
                        },
                        accept: "png|jpeg|gif", filesize: 1048576  },
                    alt1file: { required: {
                             depends: function(element) {
                                    return ($("#alt1readonly").val() == '');}
                        }, accept: "png|jpeg|gif", filesize: 1048576  },
                    alt2file: { required: {
                             depends: function(element) {
                                    return ($("#alt2readonly").val() == '');}
                        },accept: "png|jpeg|gif", filesize: 1048576  }
                },
                messages: {
                    pname: { required: "This is required"},
                    pprice: { required: "This is required", number:"Please enter a number"},
                    pquantity: { required: "This is required", number:"Please enter a number"},
                    sdesc: { required: "This is required"},
                    pdesc: { required: "This is required"},
                    addinfo: { required: "This is required"},
                    featured: { required: "This is required"},
                     pcategory: { required: "This is required"},
                     pitem: { required: "This is required"},
                     pavail: { required: "This is required"},
                     pstatus: { required: "This is required"},
                    mainfile: "This is required. File must be JPG, GIF or PNG, less than 1MB",
                    alt1file: "This is required. File must be JPG, GIF or PNG, less than 1MB",
                    alt2file: "This is required. File must be JPG, GIF or PNG, less than 1MB",

                },
                 errorPlacement: function(error, element) {

                    if (element.attr("name") == "mainfile" ) {
                         error.insertAfter("#mainfile");
                      }

                    else if (element.attr("name") == "alt1file" ) {
                         error.insertAfter("#alt1file");
                      }

                    else if (element.attr("name") == "alt2file" ) {
                         error.insertAfter("#alt2file");
                      }

                    else if (element.attr("type") == "radio" && element.attr("name") == "featured" ) {
                        error.insertAfter("#featuredRadio");
                    }
                    else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                       $(form).submit();
                        return false;
                    }
                });

     });
    //]]>
    </script>
   <?php } ?>

</body>

</html>
