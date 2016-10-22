<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/signature/merchant/cart/html/MerchantHTMLCartFactory.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/signature/common/cart/xml/XMLCartFactory.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/signature/common/signature/SignatureCalculator.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/redo/php/amazonconfig.php');




$categoriesArr= explode("|", CATEGORY) ;
$cartItems = (isset($_SESSION['cartids'])) ? $_SESSION['cartids'] : [];
//Session variables to track the same order
$sess_orderID = (isset($_SESSION['orderID'])) ? $_SESSION['orderID'] : -1;
$sess_orderStatus = (isset($_SESSION['orderStatus'])) ? $_SESSION['orderStatus'] : '';



$cartTotal =0;
$cartLowPrice=0;
$cartHighPrice=500;


if(!empty($_POST) ){
	$itemId =  prepare_input($_POST['itemID']);
	$itemId = intval($itemId);

	$itemQty =  prepare_input($_POST['cartQty']);
	$itemQty = intval($itemQty);

    if (isset($_POST['remove'])) {
        $itemQty = 0;
    }

	$newCartItems =[];
	$qtyCounter = 0;
	foreach($cartItems as $ci) {
		if($ci != $itemId ) {
			$newCartItems[] = $ci;
		}
	}
	for($i=0; $i<$itemQty; $i++) {
		$newCartItems[] = $itemId;
	}

	$cartItems= $_SESSION['cartids'] =$newCartItems;

}

if(count($cartItems)  == 0) {
		if($sess_orderID != -1) {
			$qry = "DELETE  from order_products WHERE orderid=?";
	        $stmt1 = $dbcon->prepare($qry);
	        $stmt1->bind_param('i', $sess_orderID);
	        $stmt1->execute();
	        $stmt1->close();

			$qry = "DELETE  from orders WHERE orderid=?";
	        $stmt1 = $dbcon->prepare($qry);
	        $stmt1->bind_param('i', $sess_orderID);
	        $stmt1->execute();
	        $stmt1->close();

		}
        unset($_SESSION['orderID']);
        unset($_SESSION['orderStatus']);
        unset($_SESSION['cartids']);
        unset($_SESSION['cartitemlist']);
        unset($_SESSION['cartPrice']);

}
if(count($cartItems)  > 0) {
    $mycartCount = array_count_values($cartItems);
    $cartids = array_keys($mycartCount);
    $cartProducts=[];


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
        $cartTotal = $cartTotal + ($c * $mycartCount[$a]);

        if(floatval($c) > $cartLowPrice ) {
            $cartLowPrice = floatval($c);
        }
        if(floatval($c) < $cartHighPrice){
            $cartHighPrice = floatval($c);
        }
        $mat = ($g === NULL) ?  "" : $categoriesArr[$g-1];

    $cartProducts[] = [ 'productid' => $a, 'name' => $b, 'price' => $c, 'size'=>$d, 'mainimg' => $e, 'customized'=> $f,  'material' => $mat, 'quantity' => $mycartCount[$a] ];

    }
    $stmt->close();

    setlocale(LC_MONETARY, 'en_IN');


    $_SESSION['cartitemlist'] = $cartProducts;
    $_SESSION['cartPrice'] = $cartTotal;



	/** Related products in cart **/
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


	/* order management starts here */
	$currUserEmail = "";
	if(isset($_SESSION["useremail"])) 	$currUserEmail = $_SESSION["useremail"];
	if(isset($_SESSION["guestemail"]))	$currUserEmail = $_SESSION["guestemail"];

	var_dump($currUserEmail);

	if($sess_orderID == -1) {
	    //order status could be new -when user has not initiated amazon payment.
        //placed - when amazon sends to thankyou page
	    //confirmed - when amazon has confirmed the order in seller central.
	    //shipped - when shipping is done.
	    //completed - when delivery is done.
	    //abandoned - when order was never completed
	    //cancelled - when amazon cancelled the order

	    $_SESSION['orderStatus'] = $sess_orderStatus = "new";
	     $ord_qry  = "insert into orders (`useremail`, `status`) VALUES (?,?)";
        $ins_stmt = $dbcon->prepare($ord_qry);
        if(!$ins_stmt) {
         die('Prepare Error 3: ('. $dbcon->errno .') '. $dbcon->error);
        }
        $ins_stmt->bind_param('ss', $currUserEmail, $sess_orderStatus);
        if($ins_stmt->execute()){
              $sess_orderID =$ins_stmt->insert_id;
              $_SESSION['orderID'] = $sess_orderID;
          }else{
            die('Insert Error 3 : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $ins_stmt->close();

        $ord_prd_qry = "INSERT into order_products (`orderid`, `productid`, `name`, `quantity`, `order_price`) VALUES (?,?,?,?,?)";
        $ins_stmt1 = $dbcon->prepare($ord_prd_qry);

        if(!$ins_stmt1) {
         die('Prepare Error 4: ('. $dbcon->errno .') '. $dbcon->error);
        }

        foreach($cartProducts as $prd){
          $ins_stmt1->bind_param('iisid', $sess_orderID, $prd['productid'],$prd['name'], $prd['quantity'], $prd['price']);

          if(!$ins_stmt1->execute()){
              die('Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
          }
        }
        $ins_stmt1->close();
	}
	else {
		$qry = "DELETE  from order_products WHERE orderid=?";
        $stmt1 = $dbcon->prepare($qry);
        $stmt1->bind_param('i', $sess_orderID);
        $stmt1->execute();
        $stmt1->close();

        $ord_update =  "UPDATE `orders` SET `useremail` = ? WHERE `orders`.`orderid` = $sess_orderID";
        $stmt = $dbcon->prepare($ord_update);
        $stmt->bind_param('s', $currUserEmail );

        if(!$stmt->execute()){
            die(' UPDATE order update Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $stmt->close();



        $ord_prd_qry = "INSERT into order_products (`orderid`, `productid`, `name`, `quantity`, `order_price`) VALUES (?,?,?,?,?)";
        $ins_stmt1 = $dbcon->prepare($ord_prd_qry);

        if(!$ins_stmt1) {
        die('Prepare Error 4: ('. $dbcon->errno .') '. $dbcon->error);
        }

        foreach($cartProducts as $prd){
            $ins_stmt1->bind_param('iisid', $sess_orderID, $prd['productid'], $prd['name'], $prd['quantity'], $prd['price']);

            if(!$ins_stmt1->execute()){
              die('Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
            }
        }
        $ins_stmt1->close();
	}



}

/* amazon checkout */
$cartFactory = new XMLCartFactory();
$calculator = new SignatureCalculator();

$cart = $cartFactory->getSignatureInput($merchantID, $accessKeyID);
$signature = $calculator->calculateRFC2104HMAC($cart, $secretKeyID);
$cartHtml = $cartFactory->getCartHTML($merchantID, $accessKeyID, $signature);

?>

