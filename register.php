<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
require_once($_SERVER['DOCUMENT_ROOT']."/plumms/utils/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT']."/plumms/utils/constants.php");

if(isset($_POST['register']))
{
    $user_type = 1;
    $user_fn=$_POST['fname'];
    $user_ln=$_POST['lname'];
    $user_email=$_POST['email'];
    $user_pass=$_POST['pass'];

  $query = "INSERT INTO `user` (`firstname`,`lastname`, `email`,`password`, `usertype`) VALUES (?, ?, ?, ?,?)";
  $ins_stmt = $dbcon->prepare($query);
  if(!$ins_stmt) {
   die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
  }
  // bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
  $ins_stmt->bind_param('ssssi', $user_fn, $user_ln,$user_email,$user_pass,$user_type);

  if($ins_stmt->execute()){
      $userid=$ins_stmt->insert_id;
    }else{
      die('Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
  }
  $ins_stmt->close();
    if(!empty($userid))
    {
      $_SESSION["username"] = $user_fn . ' '. $user_ln;
      $_SESSION["userid"] = $userid;
      $_SESSION["useremail"] =  $user_email;
      header("Location: ".SITE_URL. "index.php");
    }
  }
  ?>