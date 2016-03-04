<?php
$categoriesArr= explode("|", CATEGORY) ;
$cartItems = (isset($_SESSION['cartids'])) ? $_SESSION['cartids'] : [];


if(count($cartItems)  > 0) {
	$cart = array_count_values($cartItems);
	$cartids = array_keys($cart);
	$cartProducts=[];
	$cartTotal=0;
	$qry = "SELECT  `productid`,`name`, `price`, `size`, `mainimg`, `customized`, `material` from products WHERE productid IN (".implode($cartids, ",")." )";
	if(!$stmt = $dbcon->prepare($qry)){
	die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if(!$stmt->execute()){
	die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$stmt->store_result();
	$stmt->bind_result( $a,$b, $c, $d, $e, $f, $g);
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

$cartProducts[] = [ 'productid' => $a, 'name' => $b, 'price' => $c, 'size'=>$d, 'mainimg' => $e, 'customized'=> $f, 'design' => $desArr, 'material' => $categoriesArr[$g]];

	}
	$stmt->close();

	$cartTotal = $cartTotal;
	setlocale(LC_MONETARY, 'en_IN');

}


?>