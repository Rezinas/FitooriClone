<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
require_once($_SERVER['DOCUMENT_ROOT']."/utils/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT']."/utils/constants.php");
require_once($_SERVER['DOCUMENT_ROOT']."/utils/functions.php");
session_start();

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

          $subject = "Fitoori Registration";
          $message = "Thank you for registering with Fitoori. Have fun designing your own custom Jewelry. Visit <a href='http://fitoori.com'>Fitoori.com</a>";
          sendemail($user_email,$subject,$message);

          $adminMessage = "New User registered: ". $user_fn . ' '. $user_ln;
        sendemail("rezinas@gmail.com", "New User Registration ", $adminMessage);

      if(isset($_POST["backto"])) {
        $gotoPage = "/index.php?".$_POST["backto"];
        header("Location: ".SITE_URL. $gotoPage);
      }
      else  {
       header("Location: ".SITE_URL. "index.php");

      }

    }
  }
  ?>