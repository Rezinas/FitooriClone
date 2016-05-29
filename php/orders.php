<?php
require_once($_SERVER['DOCUMENT_ROOT']."/utils/constants.php");
require_once(SITE_ROOT."/utils/functions.php");


//Session variables to track the same order
$sess_orderID = (isset($_SESSION['orderID'])) ? $_SESSION['orderID'] : -1;
$sess_orderStatus = (isset($_SESSION['orderStatus'])) ? $_SESSION['orderStatus'] : '';
// $todayDate = $date = date('m/d/Y h:i:s a', time());

//we will enable this when we do payment gateway
// if(!empty($_POST) && isset($_POST['payInfo']) ){
//     header("Location: ".SITE_URL. "php/index.php?orders");

// }

//When confirm order is clicked.
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

     $deliverydate=  date('F dS, Y', strtotime("+8 days"))." - " . date('F dS, Y', strtotime("+14 days"));


    $message= "<strong>Hi $userflname, </strong><br>";
    $message .= "<p>Thank you for choosing Fitoori. Here is your order information.</p>";
    $message .= '<div>';
    $message .= '<h4>Order Number: ORD000'. $sess_orderID .'</h4>';
    $message .= '<table style="width: 50%;border: 1px solid #f07818">';
    $message .= '<tr>';
    $message .= '<th>Item</th>';
    $message .= '<th>Price</th>';
    $message .= '</tr>';
    foreach($cartItemlist as $citem) {
        $message .= '<tr>';
        $message .= '<td style="border-right: 1px solid #f07818; border-top: 1px solid #f07818;">';
        $message .= '<strong>'.$citem["name"] ? $citem["name"] : "My Design".'</strong>';
        $message .= '<p><img src="http://fitoori.com/productImages/'.$citem["mainimg"] .'"/><br>';
        $message .= 'Item ID: &nbsp;<span>PNR00'.$citem["pid"].'</span><br>';
        $message .= 'Quantity: &nbsp;<span> '.$citem["quantity"].'</span><br>';
        $message .= '</td>';
        $message .= '<td style="border-top: 1px solid #f07818">';
        $message .= '<span>  &#8377;'.round(floatval($citem["price"]) * intval($citem["quantity"])).'</span>';
        $message .= '</td>';
        $message .= '</tr>';
    }
    $message .= '<tr>';
    $message .= '<td style="border-right: 1px solid #f07818; border-top: 1px solid #f07818;">Total( including Tax)</td>';
    $message .= '<td style="border-top: 1px solid #f07818" >: &#8377; <span id="subTotal">'. money_format("%!i", round($ctotal, 0)) .'</span></td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td style="border-right: 1px solid #f07818; border-top: 1px solid #f07818;">Shipping</td>';
    $message .= '<td style="border-top: 1px solid #f07818">: &#8377;'. money_format("%!i", SHIPPINGCHARGES_SMALL).'</td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td style="border-right: 1px solid #f07818; border-top: 1px solid #f07818;color:#333;font-weight:bold;">Grand - Total</td>';
    $message .= '<td style="border-top: 1px solid #f07818">: &#8377; <span id="grandTotal">'. money_format("%!i",round($ctotal + SHIPPINGCHARGES_SMALL, 0)) .'</span></td>';
    $message .= '</tr>';
    $message .= '</table>';
    $message .= '</div>';
    $message .= '<div class="estimate" style="width: 50%;text-align: right;">';
    $message .= '<p>Estimated time of delivery : '.$deliverydate.' </p>';
    $message .= '</div>';
    $message .= '<div> <strong>Shipping Address:</strong> <br/>';
    $message .= $shippingaddr;
    $message .= '</div> <br>';
    $message .= '<div> <strong>Payment Method:</strong> Cash on Delivery </div>';


    //send email to user and admin about the order: TBD
    // the body can contain just a link to the orderpage.
    // we still have to build the order page.



    $subject = 'Fitoori Order Confirmation';
    sendemail($toemail, $subject, $message);
    $adminMessage = "New Order Placed: ".$sess_orderID;
    sendemail("rezinas@gmail.com", "Fitoori Order Notification", $adminMessage);

    unset($_SESSION['cartitemlist']);
    unset($_SESSION['shippingaddress']);
    unset($_SESSION['orderemail']);
    unset($_SESSION['userflname']);


    echo "SUCCESS";
        exit();
}

//When payment information is updated
if(!empty($_POST) && isset($_POST['shippay']) ){
    $email_info = prepare_input($_POST['email_info']);
    $phone = prepare_input($_POST['phone']);
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
    $updQuery1 =  "UPDATE `orders` SET `status` = ?, `paymenttype` = ?, `shippingaddress1` = ?, `shippingaddress2` = ?, `shippingstate` = ?, `shippingcity` = ?, `shippingpostal` = ?, `billingaddress1` = ?, `billingaddress2` = ?, `billingcity` = ?, `billingstate` = ?, `billingpostal` = ?, `useremail`= ?, `phone`= ?  WHERE `orders`.`orderid` = $sess_orderID ";

    $stmt = $dbcon->prepare($updQuery1);
    $stmt->bind_param('ssssssssssssss', $sess_orderStatus, $paytype, $ship_address1, $ship_address2, $ship_state, $ship_city, $ship_postalcode, $bill_address1, $bill_address2, $bill_state, $bill_city, $bill_postalcode, $email_info, $phone);

    if(!$stmt->execute()){
        die('Error : ('. $dbcon->errno .') '. $dbcon->error);
    }
    $stmt->close();

    $shipAddressStr = $ship_address1.", <br>";
    if(!empty($ship_address2)) { $shipAddressStr .= $ship_address2 .", <br>"; }
    $shipAddressStr .= $ship_city. ", ".$ship_state.", India <br>";
    $shipAddressStr .= $ship_postalcode;
    $shipAddressStr .= "<br> Phone Number: ".$phone;

    $_SESSION["shippingaddress"] = $shipAddressStr;
    $_SESSION["orderemail"] = $email_info;

    if(isset($_SESSION["useraddress"])){
        $uid = $_SESSION['userid'];
        $updQuery1 =  "UPDATE `user` SET `address1` = ?, `address2` = ?, `city` = ?, `state` = ?, `postalcode` = ?, `phone` = ? WHERE `user`.`userid` = $uid";

        $stmt = $dbcon->prepare($updQuery1);
        $stmt->bind_param('ssssss', $ship_address1, $ship_address2, $ship_city, $ship_state, $ship_postalcode, $phone);

        if(!$stmt->execute()){
            die('Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $stmt->close();
        unset($_SESSION['useraddress']);

    }

    header("Location: ".SITE_URL. "index.php?orders");
    exit();
}


//when new order is being created set the order status as new.
//new order to be created if there was no orderid in the session
if($sess_orderID == -1) {
    //order status could be new, shippingInfo, review, confirmed, cancelled, shipped, delivered, completed
    $_SESSION['orderStatus'] = $sess_orderStatus = "new";

}

//in all above scenarios, the rest of the page needs to be executed
$currUserEmail = isGuest() ? "" :  getCurrentUserEmail();
$currUsertype="2";
$curr_user = ['userid' => '', 'firstname' => '', 'lastname' => '', 'email' => '', 'phone' => '', 'gender' => '', 'address1' => '', 'address2' => '', 'city' => '', 'state' => '', 'postalcode' => ''];
$order_add = ['email' => '','email' => '', 'ship_add1' => '', 'ship_add2' => '', 'ship_city' => '', 'ship_state' => '', 'ship_postal' => '', 'bill_add1' => '', 'bill_add2' => '', 'bill_city' => '', 'bill_state' => '', 'bill_postal' => '', 'paymenttype' => 'COD'];


//if the user is not a guest, get the current user details to prefill the order form
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
        if(!isset($e)) $e= " ";
        $curr_user = ['userid' => $a, 'firstname' => $b, 'lastname' => $c, 'email' => $d, 'phone' => $e, 'gender' => $f, 'address1' => $g, 'address2' => $h, 'city' => $i, 'state' => $j, 'postalcode' => $k, 'usertype' => $l];
    }
    $stmt->close();

    if($curr_user['address1'] == "" || is_null($curr_user['address1'])) {
        $_SESSION["useraddress"] = 0;
        $_SESSION["userflname"] = $curr_user['firstname'] ." ".$curr_user["lastname"];
    }

    if($sess_orderStatus == "new") {
        $_SESSION['orderStatus'] = $sess_orderStatus = "shippingInfo";
        $updQuery1 =  "UPDATE `orders` SET `status` = ?,  `useremail` =?, `phone` =?, `usertype` =?, `shippingaddress1` = ?,`shippingaddress2`=?,`shippingcity`=?,`shippingstate` =?, `shippingpostal`=? WHERE `orders`.`orderid` = $sess_orderID";
        $stmt = $dbcon->prepare($updQuery1);
        $stmt->bind_param('sssisssss', $sess_orderStatus, $curr_user['email'], $curr_user['phone'], $curr_user['usertype'], $curr_user['address1'], $curr_user['address2'], $curr_user['city'], $curr_user['state'], $curr_user['postalcode']);

        if(!$stmt->execute()){
            die('Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $stmt->close();
    }

    $currUsertype= $curr_user['usertype'];
    $order_add['email'] = $curr_user['email'];
    $order_add['phone'] = $curr_user['phone'];
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
        $ord_qry  = "insert into orders (`useremail`,`phone`, `usertype`, `status`, `shippingaddress1`,`shippingaddress2`,`shippingcity`,`shippingstate`,`shippingpostal`,`billingaddress1`,`billingaddress2`,`billingcity`,`billingstate`,`billingpostal`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $ins_stmt = $dbcon->prepare($ord_qry);
        if(!$ins_stmt) {
         die('Prepare Error 3: ('. $dbcon->errno .') '. $dbcon->error);
        }
        $ins_stmt->bind_param('ssisssssssssss', $currUserEmail, $curr_user["phone"], $currUsertype, $sess_orderStatus, $curr_user["address1"], $curr_user["address2"], $curr_user["city"], $curr_user["state"], $curr_user["postalcode"], $curr_user["address1"], $curr_user["address2"], $curr_user["city"], $curr_user["state"], $curr_user["postalcode"]);
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
    $qry = "SELECT  useremail, phone, shippingaddress1, shippingaddress2, shippingcity, shippingstate, shippingpostal,billingaddress1, billingaddress2, billingcity, billingstate, billingpostal, paymenttype  from orders WHERE orderid=$sess_orderID";

    if(!$stmt = $dbcon->prepare($qry)){
        die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
    }

    if(!$stmt->execute()){
        die('Error : ('. $dbcon->errno .') '. $dbcon->error);
    }

    $stmt->store_result();
    $stmt->bind_result( $aa, $ph, $a,$b, $c, $d, $e, $f, $g, $h, $i, $j, $k);
    while ($stmt->fetch()) {
       $order_add = ['email' => $aa, 'phone'=>$ph, 'ship_add1' => $a, 'ship_add2' => $b, 'ship_city' => $c, 'ship_state' => $d, 'ship_postal' => $e, 'bill_add1' => $f, 'bill_add2' => $g, 'bill_city' => $h, 'bill_state' => $i, 'bill_postal' => $j, 'paymenttype'=> $k];
    }
    $stmt->close();

}

    $offer = false;
    if(count($cartProducts) > 1)  $offer = true;

    if(isset($_SESSION['orderemail'])) {
        $uemail =  $_SESSION['orderemail'];
        $check_user="select orderid from orders WHERE useremail='$uemail'";
         $result=mysqli_query($dbcon,$check_user);
         if ($result && mysqli_num_rows($result) > 0)
         {
            $offer = true;
            mysqli_free_result($result);
          }

    }

    if($offer) {
          $offQ =  "UPDATE `orders` SET `offercode` = ? WHERE `orders`.`orderid` = $sess_orderID";
        $offercode ="FREESHIPPING";
        $stmt = $dbcon->prepare($offQ);
        $stmt->bind_param('s', $offercode );

        if(!$stmt->execute()){
            die(' UPDATE offer Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $stmt->close();

    }
?>