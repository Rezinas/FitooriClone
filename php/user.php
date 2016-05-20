<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
require_once($_SERVER['DOCUMENT_ROOT']."/utils/functions.php");
if(isset($_GET["emailPass"])) {
	if(!empty($_POST)){
		$user_email = prepare_input($_POST['email']);
		$qry =  "SELECT password from user WHERE email='$user_email'";
		if(!$stmt = $dbcon->prepare($qry)){
		    die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error . ': qry = '.$qry );
		}
		if(!$stmt->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		$stmt->store_result();

		if($stmt->num_rows > 0) {
			$stmt->bind_result($a);
			while ($stmt->fetch()) {
			    $to = $user_email;
			    $subject = "Fitoori Login information";
			    $message = "Your password is ".$a;
			    $headers = "From: Admin@fitoori.com";
			    mail($to,$subject,$message, $headers);
			}
			echo "SUCCESS";
		}
		else  {
			echo "NOEMAIL";
		}
		$stmt->close();
	}
	else {
		echo "ERROR";
	}
	exit();
}
if(isset($_GET["address"])) {
	if(!empty($_POST)){
		$user_id=$_SESSION['userid'];
		$addr1 = prepare_input($_POST['address1']);
		$addr2 = prepare_input($_POST['address2']);
		$city = prepare_input($_POST['city']);
		$state = prepare_input($_POST['state']);
		$postalcode = prepare_input($_POST['postalcode']);

		$updQuery1 =  "UPDATE user  SET `address1`=?, `address2` =?, `city` =?, `state` = ?, `postalcode` = ? WHERE userid=$user_id";
    	 	$stmt = $dbcon->prepare($updQuery1);

		$stmt->bind_param('sssss', $addr1, $addr2, $city, $state, $postalcode);

		if(!$stmt->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		$stmt->close();
		echo "SUCCESS";
	}
	exit();
}
else if(isset($_GET["changePass"])) {
		if(!empty($_POST)){
		$user_id=$_SESSION['userid'];
		$pass = prepare_input($_POST['cpass']);

		$updQuery1 =  "UPDATE user  SET `password`=? WHERE userid=$user_id";
    	 	$stmt = $dbcon->prepare($updQuery1);

		$stmt->bind_param('s', $pass);

		if(!$stmt->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		$stmt->close();
		echo "SUCCESS";
	}
	exit();
}
else if(isset($_GET["profile"])) {
	if(!empty($_POST)){
		$user_id=$_SESSION['userid'];
		$fname = prepare_input($_POST['fname']);
		$lname = prepare_input($_POST['lname']);
		$email = prepare_input($_POST['email']);
		$phone = prepare_input($_POST['phone']);
		$gender = prepare_input($_POST['gender']);

		$updQuery1 =  "UPDATE user  SET `firstname`=?, `lastname` =?, `email` =?, `phone` = ?, `gender` = ? WHERE userid=$user_id";
    	 	$stmt = $dbcon->prepare($updQuery1);

		$stmt->bind_param('sssss', $fname, $lname, $email, $phone, $gender);

		if(!$stmt->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		$stmt->close();
		echo "SUCCESS";
	}
	exit();
}
else {
	$user_id=$_SESSION['userid'];
	$curr_user =[];

	$qry = "SELECT  userid, firstname, lastname, email, phone, gender, address1, address2, city, state, postalcode  from user WHERE userid=$user_id";
 	if(!$stmt = $dbcon->prepare($qry)){
	    die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if(!$stmt->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$stmt->store_result();
	$stmt->bind_result($a,$b,$c,$d, $e, $f, $g, $h, $i, $j, $k);
	while ($stmt->fetch()) {
		$curr_user = ['userid' => $a, 'firstname' => $b, 'lastname' => $c, 'email' => $d, 'phone' => $e, 'gender' => $f, 'address1' => $g, 'address2' => $h, 'city' => $i, 'state' => $j, 'postalcode' => $k];
	}
	$stmt->close();


	$em = $curr_user['email'];
	$ordqry = "SELECT orderid, useremail, status, shippingaddress1,shippingaddress2,shippingcity,shippingstate,shippingpostal, paymenttype from orders  where dateCreated  >= date_sub(now(), interval 6 month) and useremail='$em' and (status like 'confirmed' OR status like 'cancelled' OR status like 'shipped' OR status like 'delivered' OR status like 'completed') order by dateCreated DESC";

	// echo $ordqry;

 	if(!$stmt = $dbcon->prepare($ordqry)){
	    die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if(!$stmt->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$stmt->store_result();
	$stmt->bind_result($a,$b,$c,$d, $e, $f, $g, $h, $i);
	$curr_orders=[];
	while ($stmt->fetch()) {
		if($c == "shippinginfo" || $c == "new" || $c == "Review") { $c= "Not Placed" ; }
		$curr_orders[] = ['orderid' => $a, 'useremail' => $b, 'status' => $c, 'shippingaddress1' => $d, 'shippingaddress2' => $e, 'shippingcity' => $f, 'shippingstate' => $g, 'shippingpostal' => $h, 'paymenttype' => $i];
	}
	$stmt->close();


}



?>