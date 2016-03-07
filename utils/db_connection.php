<?php

$db_user = "fitoori_admin";
$db_pass = "custom123";
$db_host = "localhost";
$db_name = "fitoori_fitooritest";

// $db_user = "root";
// $db_pass = "";
// $db_host = "localhost";
// $db_name = "fitoori";

//  $db_user = "samplep1_web";
// $db_pass = "Site12";
// $db_host = "localhost";
// $db_name = "samplep1_puh";


//  $db_user = "fitoori_admin";
// $db_pass = "custom123";
// $db_host = "localhost";
// $db_name = "fitoori_fitooritest";

$dbcon=mysqli_connect($db_host, $db_user, $db_pass, $db_name);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>