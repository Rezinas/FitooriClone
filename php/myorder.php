<?php
if(!empty($_REQUEST)){

    $currUserEmail = getCurrentUserEmail();
    $orderid = $_REQUEST['orderid'];
    $mainErrorMsg ="";
    if (strpos($orderid, 'ORD000') !== false) {
        $orderid =  intval(str_replace("ORD000", "", $orderid));
        $ordqry = "SELECT UNIX_TIMESTAMP(dateCreated) as dateCreated, useremail, status, paymenttype,shippingaddress1,shippingaddress2,shippingcity,shippingstate,shippingpostal,billingaddress1,billingaddress2,billingcity,billingstate,billingpostal from orders where orderid=$orderid";

        $stmt = $dbcon->prepare($ordqry);
        if(!$stmt->execute()){
            die('Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $stmt->store_result();
        $stmt->bind_result($a,$b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n);
        $currOrder =[];
        while ($stmt->fetch()) {
            $currOrder = ['dateCreated' => date('F/j/Y',$a), 'useremail' => $b, 'status' => $c, 'paymenttype' => $d, 'ship_add1' => $e, 'ship_add2' => $f, 'ship_city' => $g, 'ship_state' => $h, 'ship_postal' => $i, 'bill_add1' => $j, 'bill_add2' => $k, 'bill_city' => $l, 'bill_state' => $m, 'bill_postal' => $n];
        }
        $stmt->close();

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
                if($f == 1 ){
                    $design_qry = "SELECT elementid, leftPos,topPos,selectedImage,isProduct from customdesign where productid=$a";
                    $stmt1a  = $dbcon->prepare($design_qry);
                    if(!$stmt1a ->execute()){
                        die('Error : ('. $dbcon->errno .') '. $dbcon->error);
                    }
                    $stmt1a ->store_result();
                    $stmt1a ->bind_result($a1,$b1, $c1, $d1, $e1);
                    while ($stmt1a ->fetch()) {
                        $desArr[] = ['elementid' => $a1, 'leftPos' => $b1, 'topPos' =>$c1, 'selectedImage' => $d1, 'isProduct' => $e1];
                    }
                    $stmt1a ->close();
              }

                $orderProducts[] = ['productid' => $a, 'quantity' => $b, 'order_price' => $c, 'name' => $d, 'mainimg' => $e, 'customized' => $f, 'design' => $desArr];
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
