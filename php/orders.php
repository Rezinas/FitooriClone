    <?php
    //Session variables to track the same order
// $_SESSION['orderID']
// $_SESSION['orderStatus']
$sess_orderID = (isset($_SESSION['orderID'])) ? $_SESSION['orderID'] : -1;
$sess_orderStatus = (isset($_SESSION['orderStatus'])) ? $_SESSION['orderStatus'] : "";

$currUserEmail = isGuest() ? "guest" :  getCurrentUserEmail();
$currUsertype="";

if(!isGuest()) {
    $qry = "SELECT usertype  from user WHERE email=$currUserEmail";
    $result=mysqli_query($dbcon,$qry);
   if ($result && mysqli_num_rows($result) > 0)
    {
        while ($row=mysqli_fetch_row($result))
        {
          $currUsertype = $row[0];
        }
          mysqli_free_result($result);
    }
}
else {
   $currUsertype= "2";
}


$curr_user = ['userid' => '', 'firstname' => '', 'lastname' => '', 'email' => '', 'phone' => '', 'gender' => '', 'address1' => '', 'address2' => '', 'city' => '', 'state' => '', 'postalcode' => ''];

if(isset($_SESSION['userid'])) {
    $user_id=$_SESSION['userid'];
    $curr_user=[];

    $qry = "SELECT  userid, firstname, lastname, email, phone, gender, address1, address2, city, state, postalcode  from user WHERE userid=$user_id";
    if(!$stmt = $dbcon->prepare($qry)){
        die('Prepare Error 1 : ('. $dbcon->errno .') '. $dbcon->error);
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
}

if($sess_orderID == -1) {

    //new order to be created now
    //order status could be new, confirmed, cancelled, shipped, delivered, completed
    $orderid = -1;
    $orderStatus = "entered";
    $_SESSION['orderStatus'] = $orderStatus;

    if(count($cartItems) > 0) {
        $ord_qry  = "insert into orders (`useremail`, `usertype`, `status`, `shippingaddress1`,`shippingaddress2`,`shippingcity`,`shippingstate`,`shippingpostal`,`billingaddress1`,`billingaddress2`,`billingcity`,`billingstate`,`billingpostal`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $ins_stmt = $dbcon->prepare($ord_qry);
        if(!$ins_stmt) {
         die('Prepare Error 3: ('. $dbcon->errno .') '. $dbcon->error);
        }
        $ins_stmt->bind_param('sisssssssssss', $currUserEmail, $currUsertype, $orderStatus, $curr_user["address1"], $curr_user["address2"], $curr_user["city"], $curr_user["state"], $curr_user["postalcode"], $curr_user["address1"], $curr_user["address2"], $curr_user["city"], $curr_user["state"], $curr_user["postalcode"]);
        if($ins_stmt->execute()){
              $orderid=$ins_stmt->insert_id;
              $_SESSION['orderID'] = $orderid;
          }else{
            die('Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $ins_stmt->close();

        $ord_prd_qry = "INSERT into order_products (`orderid`, `productid`,`quantity`, `order_price`) VALUES (?,?,?,?)";
        $ins_stmt1 = $dbcon->prepare($ord_prd_qry);

        if(!$ins_stmt1) {
         die('Prepare Error 4: ('. $dbcon->errno .') '. $dbcon->error);
        }

        foreach($cartProducts as $prd){
          $ins_stmt1->bind_param('iiid', $orderid, $prd['productid'], $cart[$prd['productid']], $prd['price']);

          if(!$ins_stmt1->execute()){
              die('Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
          }
        }
        $ins_stmt1->close();
    }
}
else {
    $orderid = $sess_orderID;
    $orderStatus = $sess_orderStatus;
}




?>