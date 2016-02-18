<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
require_once($_SERVER['DOCUMENT_ROOT']."/plumms/utils/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT']."/plumms/utils/constants.php");
//require_once($_SERVER['DOCUMENT_ROOT']."/plumms/utils/email_functions.php");

if(isset($_POST['cnct']))
  {
      /* Sending Email along with the link to open the file for the user to view the post*/

      $messageDetail = "Message was sent by ".$_POST['email']. "<br/> Their contact information is - ".$_POST['phone']. "<br/> The message sent is - ". $_POST['messageArea'];
      $user_email = $_POST['email'];
      $to      = 'abdullahk84@gmail.com';
      $subject = 'Email From Contact page. Sent by'. $user_email ;
      $message = $messageDetail;
      $headers = 'From: webmaster@example.com' . "\r\n" .
      'Reply-To: webmaster@example.com' . "\r\n" .
      'X-Mailer: PHP/' . phpversion();

      mail($to, $subject, $message,$headers);
      header("Location: ".SITE_URL. "index.php");
  }
?>