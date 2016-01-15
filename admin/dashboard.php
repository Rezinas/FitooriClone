<?php
require_once($_SERVER['DOCUMENT_ROOT']."/plumms/utils/functions.php");
if(empty($username)){
    //Redirect not logged in user
    //TBD: later have to add check for users who are not agents.
    //TBD: customers should not access agent dashboard
      header("Location: ".SITE_URL. "admin/index.php");
}

$currenttab = "";
if(isset($_GET["search"])) {
    $currenttab = 'search';
}
else if(isset($_GET["orders"])) {
    $currenttab = 'orders';
}
else if(isset($_GET["product"])) {
    $currenttab = 'product';
}
else if (isset($_GET["report"])) {
    $currenttab = 'report';
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
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <link href="../css/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="../css/form.css" rel="stylesheet" type="text/css" media="all" />
    <link href="../css/style.css" type="text/css" rel="stylesheet" media="all">


   </head>
<body>


    <div class="header">
        <div class="container">
            <nav class="navbar navbar-default" role="navigation" id="navbar" style="width: 88%"    >
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <h1 class="navbar-brand"><a  href="index.html" style="font-family:Brush Script MT">Plumms</a></h1>
                </div>
                <!--navbar-header-->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                   <ul class="nav navbar-nav">
                            <li <?php if($currenttab == "dash") echo 'class="selected"'; ?>>
                                <a href="dashboard.php"><i class="fa fa-dashboard fa-fw"></i>Dashboard</a>
                            </li>
                            <li <?php if($currenttab == "search") echo 'class="selected"'; ?>>
                                <a href="dashboard.php?search"><i class="fa fa-search fa-fw"></i>Product Search</a>
                            </li>
                            <li <?php if($currenttab == "product") echo 'class="selected"'; ?>>
                                <a href="dashboard.php?product"><i class="fa fa-edit fa-fw"></i> Manage Product</a>
                            </li>
                            <li <?php if($currenttab == "orders") echo 'class="selected"'; ?>>
                                <a href="dashboard.php?orders"><i class="fa fa-table fa-fw"></i>Orders</a>
                            </li>
                            <li <?php if($currenttab == "reports") echo 'class="selected"'; ?>>
                                <a href="dashboard.php?reports"><i class="fa fa-bar-chart-o fa-fw"></i>Reports</a>
                            </li>
                   </ul><!--/.navbar-collapse-->
                </div>
                <!--//navbar-header-->
            </nav>

                <div class="header-right login dropdown" >
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
                </div>
                <div class="user-info" style="float:right; color:#fff; padding: 20px 2px 10px 10px; font-size: 10px;">
                            <div><strong>Admin Admin</strong></div>
                 </div>

    </div><!--container ends -->

    </div> <!-- end of header -->
    <!--  wrapper -->
    <div id="wrapper">
        <!--  page-wrapper -->
        <div id="page-wrapper">


<?php
if($currenttab == "dash") {
    include(SITE_ROOT. "admin/dashboard.html");
}
else if($currenttab == "search") {
    include(SITE_ROOT. "productcombined.html");
}
else if($currenttab == "product") {
    include(SITE_ROOT. "admin/products.php");
    include(SITE_ROOT. "admin/product.html");
}
else if($currenttab == "orders") {
    include(SITE_ROOT. "admin/orders.html");
}
else if($currenttab == "report") {
    include(SITE_ROOT. "admin/report.html");
}
?>





        </div>
        <!-- end page-wrapper -->
    </div>
    <!-- end wrapper -->

    <!-- Core Scripts - Include with every page -->
    <script src="../js/jquery.min.js" type="text/javascript"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
   <script src="../js/jquery.validate.min.js" type="text/javascript"></script>

   <?php if($currenttab == "search") { ?>
    <script type="text/javascript" src="../js/jquery-ui.min.js"></script>
                <link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
                    <script type='text/javascript'>//<![CDATA[
                        $(document).ready(function(){
                         $( "#slider-range" ).slider({
                                    range: true,
                                    min: 0,
                                    max: 10000,
                                    values: [ 50, 10000 ],
                                    slide: function( event, ui ) {  $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
                                    }
                         });
                        $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) + " - $" + $( "#slider-range" ).slider( "values", 1 ) );
                        });
                        //]]>
                    </script>
   <?php } ?>

   <?php if($currenttab == "product") { ?>
  <script src="../js/jquery.validate.min.js" type="text/javascript"></script>
    <script type='text/javascript'>//<![CDATA[
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
                    promoted: { required: true},
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
                        },, accept: "png|jpeg|gif", filesize: 1048576  },
                    featuredfile: { required: {
                             depends: function(element) {
                                    return ($("input[name=featured").val() == '1');}
                        }, accept: "png|jpeg|gif", filesize: 1048576  },
                    promotedfile: { required: {
                        depends: function(element) {
                            return ($("input[name=promoted").val() == '1');  }
                        }, accept: "png|jpeg|gif", filesize: 1048576  }
                },
                messages: {
                    pname: { required: "This is required"},
                    pprice: { required: "This is required", number:"Please enter a number"},
                    pquantity: { required: "This is required", number:"Please enter a number"},
                    sdesc: { required: "This is required"},
                    pdesc: { required: "This is required"},
                    addinfo: { required: "This is required"},
                    promoted: { required: "This is required"},
                    featured: { required: "This is required"},
                     pcategory: { required: "This is required"},
                     pitem: { required: "This is required"},
                     pavail: { required: "This is required"},
                     pstatus: { required: "This is required"},
                    mainfile: "This is required. File must be JPG, GIF or PNG, less than 1MB",
                    alt1file: "This is required. File must be JPG, GIF or PNG, less than 1MB",
                    alt2file: "This is required. File must be JPG, GIF or PNG, less than 1MB",
                    featuredfile: "File must be JPG, GIF or PNG, less than 1MB",
                    promotedfile: "File must be JPG, GIF or PNG, less than 1MB",
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
                       else if (element.attr("name") == "promotedfile" ) {
                         error.insertAfter("#promotedfile");
                      }
                       else if (element.attr("name") == "featuredfile" ) {
                         error.insertAfter("#featuredfile");
                      }
                    else if (element.attr("type") == "radio" && element.attr("name") == "featured" ) {
                        error.insertAfter("#featuredRadio");
                    }
                    else if (element.attr("type") == "radio" && element.attr("name") == "promoted" ) {
                        error.insertAfter("#promotedRadio");
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
