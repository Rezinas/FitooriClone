<?php
if(!empty($_REQUEST)){

    $currUserEmail = getCurrentUserEmail();
    $orderid = $_REQUEST['orderid'];
    $orderAction = isset($_REQUEST['cancel']) ? "cancel" : "";
    $userflname=isset($_SESSION['userflname']) ? $_SESSION['userflname'] : "Guest";
    $mainErrorMsg ="";

    //echo $currUserEmail;

    if (strpos($orderid, 'ORD000') !== false) {
        $orderid =  intval(str_replace("ORD000", "", $orderid));

        if(!empty($orderAction)){
             $statement = $dbcon->prepare("UPDATE orders SET status='cancelled' WHERE orderid=?");

            //bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
            $statement->bind_param('i',  $orderid);
            $results =  $statement->execute();
            if(!$results){
                die('Error : ('. $dbcon->errno .') '. $dbcon->error);
            }


            //Notify user and admin about order cancellation.
    $subject = 'Fitoori Order Cancelled';
    $message= "<strong>Hi $userflname, </strong><br>";
    $message .= "<p>Thank you for choosing Fitoori. Your order ".$_REQUEST['orderid']." has been successfully cancelled. Please contact admin@fitoori.com  for any questions.</p> <p> Fitoori Team.</p>";

   sendemail($currUserEmail, $subject, $message);

    $adminMessage = "The following order has been cancelled: ".$_REQUEST['orderid'];

   sendemail("rezinas@gmail.com", "Fitoori Order Cancellation", $adminMessage);

        }

        $ordqry = "SELECT UNIX_TIMESTAMP(dateCreated) as dateCreated, useremail, status, paymenttype,shippingaddress1,shippingaddress2,shippingcity,shippingstate,shippingpostal,billingaddress1,billingaddress2,billingcity,billingstate,billingpostal, offercode from orders where orderid=$orderid";

        $stmt = $dbcon->prepare($ordqry);
        if(!$stmt->execute()){
            die('Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $stmt->store_result();
        $stmt->bind_result($a,$b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o);
        $currOrder =[];
        while ($stmt->fetch()) {
            $currOrder = ['dateCreated' => $a, 'useremail' => $b, 'status' => $c, 'paymenttype' => $d, 'ship_add1' => $e, 'ship_add2' => $f, 'ship_city' => $g, 'ship_state' => $h, 'ship_postal' => $i, 'bill_add1' => $j, 'bill_add2' => $k, 'bill_city' => $l, 'bill_state' => $m, 'bill_postal' => $n, 'offercode' => $o];
        }
        $stmt->close();

        // var_dump($currOrder);

        if($currOrder['useremail'] != $currUserEmail) {
            $mainErrorMsg = "This order id not found. Please contact admin@fitoori.com, ".$currOrder['useremail']." and $currUserEmail";
        }



        $cartTotal = 0;
        if(empty($mainErrorMsg)) {
             $ordprdqry = "SELECT order_products.productid, order_products.quantity, order_products.order_price, products.name, products.mainimg, products.customized FROM order_products inner join products  on order_products.productid=products.productid WHERE order_products.orderid=$orderid";

             $stmt = $dbcon->prepare($ordprdqry);
            if(!$stmt->execute()){
                die('Error : ('. $dbcon->errno .') '. $dbcon->error);
            }
            $stmt->store_result();
            $stmt->bind_result($a,$b,$c, $d, $e, $f);
            $orderProducts =[];
            $desArr=[];
            while ($stmt->fetch()) {
                $cartTotal = $cartTotal + $c;
                $orderProducts[] = ['productid' => $a, 'quantity' => $b, 'order_price' => $c, 'name' => $d, 'mainimg' => $e, 'customized' => $f];
            }
            $stmt->close();
        }
    }
    else {
        //$mainErrorMsg = "This order id not found. Please contact admin@fitoori.com, ".$currOrder['useremail']." and $currUserEmail";
        $mainErrorMsg = "This order id not found. Please contact admin@fitoori.com";
    }
}

?>
