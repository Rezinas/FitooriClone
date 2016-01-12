<?php

$db_user = "root";
$db_pass = "";
$db_host = "localhost";
$db_name = "plumms";

//  $db_user = "samplep1_web";
// $db_pass = "Site12";
// $db_host = "localhost";
// $db_name = "samplep1_puh";
$dbcon=mysqli_connect($db_host, $db_user, $db_pass, $db_name);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>