<?php

$cartItems = (isset($_SESSION['cartids'])) ? $_SESSION['cartids'] : [];

if(count($cartItems)  > 0) {
	$cart = array_count_values($cartItems);
	$cartids = array_keys($cart);
	$cartProducts=[];
	$cartTotal=0;
	$qry = "SELECT  `productid`,`name`, `price`, `mainimg`, customized from products WHERE productid IN (".implode($cartids, ",")." )";
	if(!$stmt = $dbcon->prepare($qry)){
	die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if(!$stmt->execute()){
	die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$stmt->store_result();
	$stmt->bind_result( $a,$b, $c, $d, $e);
	while ($stmt->fetch()) {
		$cartTotal = $cartTotal + $c;
		$cartProducts[] = [ 'productid' => $a, 'name' => $b, 'price' => $c, 'mainimg'=>$d, 'customized' => $e];
	}
	$stmt->close();
	$marginFactor = 1 + PROFITPERCENT/100;
	$overheadFactor = 1 + OVERHEADS/100;
	$taxFactor = 1 + TAXPERCENT/100;

	$cartTotal = $cartTotal * $marginFactor * $overheadFactor * $taxFactor;
	setlocale(LC_MONETARY, 'en_IN');

}

?>