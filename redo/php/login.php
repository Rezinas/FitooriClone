<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/utils/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT']."/utils/constants.php");

if(isset($_POST['login']))
{

    $user_email=$_POST['email'];
    $user_pass=$_POST['pass'];

    $check_user="select * from user WHERE email='$user_email' AND password='$user_pass'";

    $result=mysqli_query($dbcon,$check_user);

   if ($result && mysqli_num_rows($result) > 0)
    {
        while ($row=mysqli_fetch_row($result))
        {
            $_SESSION["username"] = $row[1] . ' '. $row[2];
            $_SESSION["userid"] = $row[0];
            $_SESSION["useremail"] =  $row[3];
        }
          // Free result set
          mysqli_free_result($result);

      if(isset($_POST["backto"])) {
        $gotoPage = "/index.php?".$_POST["backto"];
        header("Location: ".SITE_URL. $gotoPage);
        exit();
      }

       if(isset($_SESSION['customEarrings'])) {
        $custEarrings = $_SESSION['customEarrings'];
        $prdids = [];
        foreach($custEarrings as $cEarr) {
          $prdid[] = $cEarr["pid"];
        }

    $updQry = "UPDATE products SET addedUserType = 1, addedByUserEmail = '". $_SESSION["useremail"] ."' WHERE productid IN ( ". implode(",", $prdid) .")";

    $result=mysqli_query($dbcon,$updQry);

    $updQry1 = "UPDATE customdesign SET addedByType = 1 WHERE productid IN ( ". implode(",", $prdid) .")";

    $result1=mysqli_query($dbcon,$updQry1);

    if(!$result || !$result1){
        die('Error : ('. $dbcon->errno .') '. $dbcon->error);
    }
      }

    echo   "SUCCESS";
    }
    else
    {
      echo  ERROR." LOGIN QUERY: ".$check_user;
    }
}
?>