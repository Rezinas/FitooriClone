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
                        $(window).load(function(){
                         $( "#slider-range" ).slider({
                                    range: true,
                                    min: 0,
                                    max: 100000,
                                    values: [ 500, 100000 ],
                                    slide: function( event, ui ) {  $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
                                    }
                         });
                        $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) + " - $" + $( "#slider-range" ).slider( "values", 1 ) );
                        });

                          $(document).ready(function(){
                                $(".tab1 .single-bottom").hide();
                                $(".tab2 .single-bottom").hide();
                                $(".tab3 .single-bottom").hide();
                                $(".tab4 .single-bottom").hide();
                                $(".tab5 .single-bottom").hide();

                                $(".tab1 ul").click(function(){
                                    $(".tab1 .single-bottom").slideToggle(300);
                                    $(".tab2 .single-bottom").hide();
                                    $(".tab3 .single-bottom").hide();
                                    $(".tab4 .single-bottom").hide();
                                    $(".tab5 .single-bottom").hide();
                                })
                                $(".tab2 ul").click(function(){
                                    $(".tab2 .single-bottom").slideToggle(300);
                                    $(".tab1 .single-bottom").hide();
                                    $(".tab3 .single-bottom").hide();
                                    $(".tab4 .single-bottom").hide();
                                    $(".tab5 .single-bottom").hide();
                                })
                                $(".tab3 ul").click(function(){
                                    $(".tab3 .single-bottom").slideToggle(300);
                                    $(".tab4 .single-bottom").hide();
                                    $(".tab5 .single-bottom").hide();
                                    $(".tab2 .single-bottom").hide();
                                    $(".tab1 .single-bottom").hide();
                                })
                                $(".tab4 ul").click(function(){
                                    $(".tab4 .single-bottom").slideToggle(300);
                                    $(".tab5 .single-bottom").hide();
                                    $(".tab3 .single-bottom").hide();
                                    $(".tab2 .single-bottom").hide();
                                    $(".tab1 .single-bottom").hide();
                                })
                                $(".tab5 ul").click(function(){
                                    $(".tab5 .single-bottom").slideToggle(300);
                                    $(".tab4 .single-bottom").hide();
                                    $(".tab3 .single-bottom").hide();
                                    $(".tab2 .single-bottom").hide();
                                    $(".tab1 .single-bottom").hide();
                                });
                            });

                        //]]>
                    </script>
   <?php } ?>

</body>

</html>
