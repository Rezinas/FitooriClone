<?php
require_once($_SERVER['DOCUMENT_ROOT']."/utils/functions.php");

// phpinfo();

$toemail = "zulaikha.k@gmail.com";
  $subject = "test";
  $messagebody = "test";

  echo sendemail($toemail, $subject, $messagebody);

?>