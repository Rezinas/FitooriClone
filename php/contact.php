<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
require_once($_SERVER['DOCUMENT_ROOT']."/utils/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT']."/utils/constants.php");
require_once($_SERVER['DOCUMENT_ROOT']."/utils/functions.php");


if(isset($_POST['cnct']))
  {
      /* Sending Email along with the link to open the file for the user to view the post*/

      $messageDetail = "Message was sent by ".$_POST['email']. "<br/> Their contact information is - ".$_POST['phone']. "<br/> The message sent is - ". $_POST['messageArea'];
      $user_email = $_POST['email'];
      $to      = 'rezinas@gmail.com';
      $subject = 'Email From Contact page. Sent by'. $user_email ;

      echo sendemail($to, $subject, $messageDetail);
  }

if(isset($_POST['support']))
  {
      /* Sending Email along with the link to open the file for the user to view the post*/

      $user_email = $_POST['email'];
      $messageDetail = "Message was sent by ".$user_email. "<br/> Their contact information is - ".$_POST['phone']. "<br/> Query is regarding - ".$_POST['queryBox']."<br/>The message sent is - ". $_POST['messageArea'];
      $to      = 'rezinas@gmail.com';
      $subject = 'Email From Support page. Sent by'. $user_email ;

      echo sendemail($to, $subject, $messageDetail);
  }

if(isset($_POST['joinSubmit']))
  {
      /* Sending Email along with the link to open the file for the user to view the post*/

      $user_email = $_POST['email'];
      $user_name = $_POST['cname'];
      $user_phone = $_POST['phone'];
      $user_address = $_POST['address'];
      $user_expertise = $_POST['expertise'];
      $user_desc = $_POST['desc'];
      $to      = 'rezinas@gmail.com';
      $subject = 'Email From Join Us page. Sent by'. $user_email ;

      $messageDetail = "User Name :  $user_name <br/>";
      $messageDetail += "User Email :  $user_email <br/>";
      $messageDetail += "User Phone :  $user_phone <br/>";
      $messageDetail += "User Address :  $user_address <br/>";
      $messageDetail += "User Expertise :  $user_expertise <br/>";
      $messageDetail += "User Description :  $user_desc <br/>";

  echo sendemail($toemail, $subject, $messagebody);
  }

?>