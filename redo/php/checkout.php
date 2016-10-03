<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/signature/merchant/cart/html/MerchantHTMLCartFactory.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/signature/common/cart/xml/XMLCartFactory.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/signature/common/signature/SignatureCalculator.php');

// seller credentials - enter your own here
$merchantID="A111O19OHHZ2JW";
$accessKeyID="AKIAJVP37DC3GTETQI4A";
$secretKeyID="bcoOQlP+GMQLff/RCaWaNjDo+/XFYe+dcV3CNt32";



$categoriesArr= explode("|", CATEGORY) ;
$cartItems = (isset($_SESSION['cartids'])) ? $_SESSION['cartids'] : [];

$cartTotal=0;
if(count($cartItems)  > 0) {
	$cart = array_count_values($cartItems);
	$cartids = array_keys($cart);
	$cartProducts=[];

	$cartLowPrice=0;
	$cartHighPrice=500;

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

		if(floatval($c) > $cartLowPrice ) {
			$cartLowPrice = floatval($c);
		}
		if(floatval($c) < $cartHighPrice){
			$cartHighPrice = floatval($c);
		}

		// $desArr=[];
		// if($f == 1 ){
		// 	$design_qry = "SELECT elementid, leftPos,topPos,selectedImage,isProduct from customdesign where productid=$a";

		// 	$stmt1 = $dbcon->prepare($design_qry);
		// 	if(!$stmt1->execute()){
		// 	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		// 	}
		// 	$stmt1->store_result();
		// 	$stmt1->bind_result($a1,$b1, $c1, $d1, $e1);
		// 	while ($stmt1->fetch()) {
		// 		$desArr[] = ['elementid' => $a1, 'leftPos' => $b1, 'topPos' =>$c1, 'selectedImage' => $d1, 'isProduct' => $e1];
		// 	}
		// 	$stmt1->close();
		// }

		$mat = ($g === NULL) ?  "" : $categoriesArr[$g-1];

	$cartProducts[] = [ 'productid' => $a, 'name' => $b, 'price' => $c, 'size'=>$d, 'mainimg' => $e, 'customized'=> $f,  'material' => $mat];

	}
	$stmt->close();

	setlocale(LC_MONETARY, 'en_IN');

	$cartItemList = [];
	foreach ($cartProducts as $cp) {
		$cartItemList [] =['pid' => $cp['productid'], 'name' => $cp['name'], 'mainimg' => $cp['mainimg'], 'price' => $cp['price'], 'customized' => $cp['customized'], 'quantity' => $cart[$cp['productid']]];
	}
	$_SESSION['cartitemlist'] = $cartItemList;
	$_SESSION['cartTotal'] = $cartTotal;



	$cartLowPrice = abs($cartLowPrice - 100);
	$cartHighPrice = abs($cartHighPrice + 100);

	$rqry = "SELECT productid, name, price, bodypart, mainimg FROM products WHERE productid  not IN ( ".implode($cartids, ",")." ) AND  status=1 AND price <= $cartHighPrice AND price >= $cartLowPrice  ORDER BY featured, price ASC LIMIT 4";

	 if(!$stmt = $dbcon->prepare($rqry)){
	    die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if(!$stmt->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$stmt->store_result();
	$stmt->bind_result($a,$b, $c, $d, $e);
	$relatedPrd =[];
	while ($stmt->fetch()) {
		$relatedPrd[] = ['productid' => $a, 'name' => $b, 'price' => $c, 'bodypart' => $d, 'mainimg' => $e];
	}
	$stmt->close();

}

$cartFactory = new XMLCartFactory();
$calculator = new SignatureCalculator();

$cart = $cartFactory->getSignatureInput($merchantID, $accessKeyID);
$signature = $calculator->calculateRFC2104HMAC($cart, $secretKeyID);
$cartHtml = $cartFactory->getCartHTML($merchantID, $accessKeyID, $signature);

?>

