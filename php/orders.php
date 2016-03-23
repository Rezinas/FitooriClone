<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/utils/constants.php");
    require_once(SITE_ROOT."/utils/functions.php");


//Session variables to track the same order
// $_SESSION['orderID']
// $_SESSION['orderStatus']
$sess_orderID = (isset($_SESSION['orderID'])) ? $_SESSION['orderID'] : -1;
$sess_orderStatus = (isset($_SESSION['orderStatus'])) ? $_SESSION['orderStatus'] : '';

//we will enable this when we do payment gateway
// if(!empty($_POST) && isset($_POST['payInfo']) ){
//     header("Location: ".SITE_URL. "php/index.php?orders");

// }

if(isset($_REQUEST["confirmOrder"])) {

    $cartItemlist = $_SESSION['cartitemlist'];
    $shippingaddr = $_SESSION['shippingaddress'];
    $toemail=$_SESSION['orderemail'];
    $userflname=isset($_SESSION['userflname']) ? $_SESSION['userflname'] : "Guest";
    $ctotal=$_SESSION['cartPrice'];


    $_SESSION['orderStatus'] = $sess_orderStatus = "confirmed";
    $updQuery1 =  "UPDATE `orders` SET `status` = ? WHERE `orders`.`orderid` = $sess_orderID";
    $stmt = $dbcon->prepare($updQuery1);
    $stmt->bind_param('s', $sess_orderStatus);

    if(!$stmt->execute()){
        die('Error : ('. $dbcon->errno .') '. $dbcon->error);
    }
    $stmt->close();
    $message= "<strong>Dear $userflname, </strong><br>";
    $message .= "<p>Thank you for choosing Fitoori. Here is your order information.</p>";
    $message .= '<div class="tableClass">';
    $message .= '<h4>Order Number: ORD000'. $sess_orderID .'</h4>';
    $message .= '<table style="width: 100%;border: 1px solid #eee">';
    $message .= '<tr>';
    $message .= '<th>Item</th>';
    $message .= '<th>Price</th>';
    $message .= '</tr>';
    foreach($cartItemlist as $citem) {
        $message .= '<tr>';
        $message .= '<td style="border-right: 1px solid #eee; border-top: 1px solid #eee;border-bottom: 1px solid #eee">';
        $message .= '<p>'.$citem["name"] ? $citem["name"] : "My Design".'</p>';
        $message .= '<p>Item ID: &nbsp;<span>PNR00'.$citem["pid"].'</span></p>';
        $message .= '<p>Quantity: &nbsp;<span> '.$citem["quantity"].'</span></p>';
        $message .= '</td>';
        $message .= '<td style="border-bottom: 1px solid #eee; border-top: 1px solid #eee">';
        $message .= '<span>  &#8377;'. floatval($citem["price"]) * intval($citem["quantity"]) .'</span>';
        $message .= '</td>';
        $message .= '</tr>';
    }
    $message .= '<tr>';
    $message .= '<td>Total( including Tax)</td>';
    $message .= '<td>: &#8377; <span id="subTotal">'. money_format("%!i", round($ctotal, 0)) .'</span></td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td>Shipping</td>';
    $message .= '<td>: &#8377;'. money_format("%!i", SHIPPINGCHARGES_SMALL).'</td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td style="color:#F07818;font-weight:bold;">Grand - Total</td>';
    $message .= '<td>: &#8377; <span id="grandTotal">'. money_format("%!i",round($ctotal + SHIPPINGCHARGES_SMALL, 0)) .'</span></td>';
    $message .= '</tr>';
    $message .= '</table>';
    $message .= '</div>';
    $message .= '<div class="estimate" style="text-align: right;">';
    $message .= '<p>Estimated time of delivery : 6 - 10 days </p>';
    $message .= '</div>';
    $message .= '<div> <strong>Shipping Address:</strong> <br/>';
    $message .= $shippingaddr;
    $message .= '</div> <br>';
    $message .= '<div> <strong>Payment Method:</strong> Cash on Delivery </div>';


    //send email to user and admin about the order: TBD
    // the body can contain just a link to the orderpage.
    // we still have to build the order page.



$subject = 'Fitoori Order Confirmation';

$headers  = "From: Admin@fitoori.com";
$headers .= "Content-type: text/html";

// mail($toemail, $subject, $message, $headers);

$adminMessage = "New Order Placed: ".$sess_orderID;

$adminheaders = "From: Admin@fitoori.com";

// mail("rezinas@gmail.com", "Fitoori Order Notification", $adminMessage, $adminheaders);

unset($_SESSION['cartitemlist']);
unset($_SESSION['shippingaddress']);
unset($_SESSION['orderemail']);
unset($_SESSION['userflname']);


echo "SUCCESS";
    exit();
}

if(!empty($_POST) && isset($_POST['shippay']) ){
    $email_info = prepare_input($_POST['email_info']);
    $ship_address1 = prepare_input($_POST['ship_address1']);
    $ship_address2 = prepare_input($_POST['ship_address2']);
    $ship_city = prepare_input($_POST['ship_city']);
    $ship_state = prepare_input($_POST['ship_state']);
    $ship_postalcode = prepare_input($_POST['ship_postalcode']);
    $sameBilling = prepare_input($_POST['sameBilling']);
    $bill_address1 = ($sameBilling == "1") ? $ship_address1 : prepare_input($_POST['bill_address1']);
    $bill_address2 =  ($sameBilling == "1") ? $ship_address2 :prepare_input($_POST['bill_address2']);
    $bill_city =  ($sameBilling == "1") ? $ship_city :prepare_input($_POST['bill_city']);
    $bill_state =  ($sameBilling == "1") ? $ship_state :prepare_input($_POST['bill_state']);
    $bill_postalcode =  ($sameBilling == "1") ? $ship_postalcode :prepare_input($_POST['bill_postalcode']);
    $paytype = prepare_input($_POST['paytype']);

    $sess_orderID = prepare_input($_POST["currOrderId"]);
    $_SESSION['orderStatus'] = $sess_orderStatus = "review";

    //update orders table with these values.
            //run the update query for the $pieceid.
    $updQuery1 =  "UPDATE `orders` SET `status` = ?, `paymenttype` = ?, `shippingaddress1` = ?, `shippingaddress2` = ?, `shippingstate` = ?, `shippingcity` = ?, `shippingpostal` = ?, `billingaddress1` = ?, `billingaddress2` = ?, `billingcity` = ?, `billingstate` = ?, `billingpostal` = ?, `useremail`= ? WHERE `orders`.`orderid` = $sess_orderID ";

    $stmt = $dbcon->prepare($updQuery1);
    $stmt->bind_param('sssssssssssss', $sess_orderStatus, $paytype, $ship_address1, $ship_address2, $ship_state, $ship_city, $ship_postalcode, $bill_address1, $bill_address2, $bill_state, $bill_city, $bill_postalcode, $email_info);

    if(!$stmt->execute()){
        die('Error : ('. $dbcon->errno .') '. $dbcon->error);
    }
    $stmt->close();

    $shipAddressStr = $ship_address1.", <br>";
    if(!empty($ship_address2)) { $shipAddressStr .= $ship_address2 .", <br>"; }
    $shipAddressStr .= $ship_city. ", ".$ship_state.", India <br>";
    $shipAddressStr .= $ship_postalcode;

    $_SESSION["shippingaddress"] = $shipAddressStr;
    $_SESSION["orderemail"] = $email_info;

    if(isset($_SESSION["useraddress"])){
        $uid = $_SESSION['userid'];
        $updQuery1 =  "UPDATE `user` SET `address1` = ?, `address2` = ?, `city` = ?, `state` = ?, `postalcode` = ? WHERE `user`.`userid` = $uid";

        $stmt = $dbcon->prepare($updQuery1);
        $stmt->bind_param('sssss', $ship_address1, $ship_address2, $ship_city, $ship_state, $ship_postalcode);

        if(!$stmt->execute()){
            die('Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $stmt->close();
        unset($_SESSION['useraddress']);

    }

    header("Location: ".SITE_URL. "index.php?orders");
    exit();
}


if($sess_orderID == -1) {
    //new order to be created if there was no orderid in the session
    //order status could be new, shippingInfo, review, confirmed, cancelled, shipped, delivered, completed
    $_SESSION['orderStatus'] = $sess_orderStatus = "new";

}

$currUserEmail = isGuest() ? "" :  getCurrentUserEmail();
$currUsertype="2";
$curr_user = ['userid' => '', 'firstname' => '', 'lastname' => '', 'email' => '', 'phone' => '', 'gender' => '', 'address1' => '', 'address2' => '', 'city' => '', 'state' => '', 'postalcode' => ''];
$order_add = ['email' => '', 'ship_add1' => '', 'ship_add2' => '', 'ship_city' => '', 'ship_state' => '', 'ship_postal' => '', 'bill_add1' => '', 'bill_add2' => '', 'bill_city' => '', 'bill_state' => '', 'bill_postal' => '', 'paymenttype' => 'COD'];



if(isset($_SESSION['userid'])) {
    $user_id=$_SESSION['userid'];
    $curr_user=[];

    $qry = "SELECT  userid, firstname, lastname, email, phone, gender, address1, address2, city, state, postalcode, usertype from user WHERE userid=$user_id";
    if(!$stmt = $dbcon->prepare($qry)){
        die('Prepare Error 1 : ('. $dbcon->errno .') '. $dbcon->error);
    }

    if(!$stmt->execute()){
        die('Error : ('. $dbcon->errno .') '. $dbcon->error);
    }

    $stmt->store_result();
    $stmt->bind_result($a,$b,$c,$d, $e, $f, $g, $h, $i, $j, $k, $l);
    while ($stmt->fetch()) {
        $curr_user = ['userid' => $a, 'firstname' => $b, 'lastname' => $c, 'email' => $d, 'phone' => $e, 'gender' => $f, 'address1' => $g, 'address2' => $h, 'city' => $i, 'state' => $j, 'postalcode' => $k, 'usertype' => $l];
    }
    $stmt->close();

    if($curr_user['address1'] == "" || is_null($curr_user['address1'])) {
        $_SESSION["useraddress"] = 0;
        $_SESSION["userflname"] = $curr_user['firstname'] ." ".$curr_user["lastname"];
    }

    if($sess_orderStatus == "new") {
        $_SESSION['orderStatus'] = $sess_orderStatus = "shippingInfo";
        $updQuery1 =  "UPDATE `orders` SET `status` = ?,  `useremail` =?, `usertype` =?, `shippingaddress1` = ?,`shippingaddress2`=?,`shippingcity`=?,`shippingstate` =?, `shippingpostal`=? WHERE `orders`.`orderid` = $sess_orderID";
        $stmt = $dbcon->prepare($updQuery1);
        $stmt->bind_param('ssisssss', $sess_orderStatus, $curr_user['email'], $curr_user['usertype'], $curr_user['address1'], $curr_user['address2'], $curr_user['city'], $curr_user['state'], $curr_user['postalcode']);

        if(!$stmt->execute()){
            die('Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $stmt->close();
    }

    $currUsertype= $curr_user['usertype'];
    $order_add['email'] = $curr_user['email'];
    $order_add['ship_add1'] = $curr_user['address1'];
    $order_add['ship_add2'] = $curr_user['address2'];
    $order_add['ship_city'] = $curr_user['city'];
    $order_add['ship_state'] = $curr_user['state'];
    $order_add['ship_postal'] = $curr_user['postalcode'];
}

if($sess_orderID == -1) {

    //new order to be created now
    //order status could be new, shippingInfo, review, confirmed, cancelled, shipped, delivered, completed
    if(count($cartItems) > 0) {
        $ord_qry  = "insert into orders (`useremail`, `usertype`, `status`, `shippingaddress1`,`shippingaddress2`,`shippingcity`,`shippingstate`,`shippingpostal`,`billingaddress1`,`billingaddress2`,`billingcity`,`billingstate`,`billingpostal`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $ins_stmt = $dbcon->prepare($ord_qry);
        if(!$ins_stmt) {
         die('Prepare Error 3: ('. $dbcon->errno .') '. $dbcon->error);
        }
        $ins_stmt->bind_param('sisssssssssss', $currUserEmail, $currUsertype, $sess_orderStatus, $curr_user["address1"], $curr_user["address2"], $curr_user["city"], $curr_user["state"], $curr_user["postalcode"], $curr_user["address1"], $curr_user["address2"], $curr_user["city"], $curr_user["state"], $curr_user["postalcode"]);
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
          $ins_stmt1->bind_param('iisid', $sess_orderID, $prd['productid'],$prd['name'], $cart[$prd['productid']], $prd['price']);

          if(!$ins_stmt1->execute()){
              die('Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
          }
        }
        $ins_stmt1->close();
    }
}
else {


    //cart could have been updated. so we have update the order_products table as well.
        $qry = "DELETE  from order_products WHERE orderid=?";
        $stmt1 = $dbcon->prepare($qry);
        $stmt1->bind_param('i', $sess_orderID);
        $stmt1->execute();
        $stmt1->close();


        $ord_prd_qry = "INSERT into order_products (`orderid`, `productid`, `name`, `quantity`, `order_price`) VALUES (?,?,?,?,?)";
        $ins_stmt1 = $dbcon->prepare($ord_prd_qry);

        if(!$ins_stmt1) {
        die('Prepare Error 4: ('. $dbcon->errno .') '. $dbcon->error);
        }

        foreach($cartProducts as $prd){
            $ins_stmt1->bind_param('iisid', $sess_orderID, $prd['productid'], $prd['name'], $cart[$prd['productid']], $prd['price']);

            if(!$ins_stmt1->execute()){
              die('Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
            }
        }
        $ins_stmt1->close();

    $order_add =[];
    $qry = "SELECT  useremail,shippingaddress1, shippingaddress2, shippingcity, shippingstate, shippingpostal,billingaddress1, billingaddress2, billingcity, billingstate, billingpostal, paymenttype  from orders WHERE orderid=$sess_orderID";

    if(!$stmt = $dbcon->prepare($qry)){
        die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
    }

    if(!$stmt->execute()){
        die('Error : ('. $dbcon->errno .') '. $dbcon->error);
    }

    $stmt->store_result();
    $stmt->bind_result( $aa, $a,$b, $c, $d, $e, $f, $g, $h, $i, $j, $k);
    while ($stmt->fetch()) {
       $order_add = ['email' => $aa, 'ship_add1' => $a, 'ship_add2' => $b, 'ship_city' => $c, 'ship_state' => $d, 'ship_postal' => $e, 'bill_add1' => $f, 'bill_add2' => $g, 'bill_city' => $h, 'bill_state' => $i, 'bill_postal' => $j, 'paymenttype'=> $k];
    }
    $stmt->close();

}

?>