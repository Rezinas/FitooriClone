<?php

$cartItems = (isset($_SESSION['cartids'])) ? $_SESSION['cartids'] : [];


if($sess_orderID != -1 ){
	   //fetch the current order from database and populate the values below
      // $qry = "SELECT  `orderid`,`useremail`, `usertype`, `status`, `paymenttype`,`shippingaddress1`,`shippingaddress2`,`shippingcity`,`shippingstate`,`shippingpostal`,`billingaddress1`,`billingaddress2`,`billingcity`,`billingstate`,`billingpostal` from user WHERE orderid=$sess_orderID";
      //       if(!$stmt = $dbcon->prepare($qry)){
      //           die('Prepare Error 1 : ('. $dbcon->errno .') '. $dbcon->error);
      //       }

      //       if(!$stmt->execute()){
      //           die('Error : ('. $dbcon->errno .') '. $dbcon->error);
      //       }

      //       $stmt->store_result();
      //       $stmt->bind_result($a,$b,$c,$d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o);
      //       while ($stmt->fetch()) {
      //           $curr_user = ['orderid' => $a, 'useremail' => $b, 'usertype' => $c, 'status' => $d, 'paymenttype' => $e, 'shippingaddress1' => $f, 'shippingaddress2' => $g, 'shippingcity' => $h, 'shippingstate' => $i, 'shippingpostal' => $j, 'billingaddress1' => $k, 'billingaddress2' => $l, 'billingcity' => $m,'billingstate' => $n,'billingpostal' => $o ];
      //       }
      //       $stmt->close();
 }

if(count($cartItems)  > 0) {
	$cart = array_count_values($cartItems);
	$cartids = array_keys($cart);

	$cartProducts=[];
	$cartTotal=0;
	$qry = "SELECT  `productid`,`name`, `price`, `size`, `mainimg`, customized from products WHERE productid IN (".implode($cartids, ",")." )";
	if(!$stmt = $dbcon->prepare($qry)){
	die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if(!$stmt->execute()){
	die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$stmt->store_result();
	$stmt->bind_result( $a,$b, $c, $d, $e, $f);
	while ($stmt->fetch()) {
		$cartTotal = $cartTotal + ($c * $cart[$a]);
		$desArr=[];
		if($f == 1 ){
			$design_qry = "SELECT elementid, leftPos,topPos,selectedImage from customdesign where productid=$a";
			$stmt1 = $dbcon->prepare($design_qry);
			if(!$stmt1->execute()){
			    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
			}
			$stmt1->store_result();
			$stmt1->bind_result($a1,$b1, $c1, $d1);
			while ($stmt1->fetch()) {
				$desArr[] = ['elementid' => $a1, 'leftPos' => $b1, 'topPos' =>$c1, 'selectedImage' => $d1];
			}
			$stmt1->close();
		}
$cartProducts[] = [ 'productid' => $a, 'name' => $b, 'price' => $c, 'size'=>$d, 'mainimg' => $e, 'customized'=> $f, 'design' => $desArr];

	}
	$stmt->close();

	$cartTotal = $cartTotal;
	setlocale(LC_MONETARY, 'en_IN');

}


?>