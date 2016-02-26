<?php
require_once($_SERVER['DOCUMENT_ROOT']."/plumms/utils/functions.php");

if(isset($_REQUEST["cartUpdate"])) {
    $_SESSION['cartids'] = $_POST['productids'];
    $_SESSION['cartPrice'] = $_POST['totalPrice'];
  exit();
}
if(isset($_REQUEST["getCart"])) {
  $cpids = isset($_SESSION['cartids']) ? $_SESSION['cartids'] : [];
  $cprice = isset($_SESSION['cartPrice']) ? $_SESSION['cartPrice'] : 0;
      $jsondata = array(
      "productids"  => $cpids,
      "totalPrice"  => $cprice,
      "vat" => TAXPERCENT,
      "overhead" => OVERHEADS,
      "margin" => PROFITPERCENT,
      "shipping" => [SHIPPINGCHARGES_SMALL, SHIPPINGCHARGES_MEDIUM, SHIPPINGCHARGES_LARGE]
    );
    echo json_encode($jsondata);
  exit();
}

if(isset($_GET["checkEmail"])) {
$requestedEmail  = $_REQUEST['email'];
  $check_user="select userid from user WHERE email='$requestedEmail'";
  $result=mysqli_query($dbcon,$check_user);
  if ($result && mysqli_num_rows($result) > 0)
  {
   // Free result set
   mysqli_free_result($result);
    echo 'false';
  }
  else
    echo   'true';

exit();
}

if(isset($_GET["addcustom"])) {
    if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
      $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
      // var_dump($_POST["custom_product"]);

      if(!empty($_POST["custom_product"])){
        $prd_qry  = "insert into products () VALUES ()";

        $ins_stmt = $dbcon->prepare($prd_qry);
        if(!$ins_stmt) {
         die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        if($ins_stmt->execute()){
              $prodid=$ins_stmt->insert_id;
          }else{
            die('Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $ins_stmt->close();

        $elements = $_POST["custom_product"];
        $currUserEmail =getCurrentUserEmail();
        $currUsertype="";

        $qry = "SELECT usertype  from user WHERE email=?";
        $result=mysqli_query($dbcon,$qry);
       if ($result && mysqli_num_rows($result) > 0)
        {
            while ($row=mysqli_fetch_row($result))
            {
              $currUsertype = $row[0];
            }
              mysqli_free_result($result);
        }


        $elem_qry = "INSERT into customdesign (`productid`, `elementid`,`leftPos`, `topPos`, `selectedImage`, `addedBy`, `addedByType` ) VALUES (?,?,?,?,?,?,?)";
        $ins_stmt1 = $dbcon->prepare($elem_qry);

        if(!$ins_stmt1) {
         die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
        }

        foreach($elements as $elem){
        //  var_dump($elem);
          $ins_stmt1->bind_param('iiiisss', $prodid, $elem['id'], $elem['leftPos'], $elem['topPos'], $elem['selectedImage'],$currUserEmail, $currUsertype);

          if(!$ins_stmt1->execute()){
              die('Image Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
          }
        }
        $ins_stmt1->close();
        echo "SUCCESS";
      }
      else echo "ERROR";
      exit();
  }
}


if(isset($_GET["despicks"])) {
  if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
      $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
      // var_dump($_POST["custom_product"]);

    if(!empty($_POST["elementids"])){
      $elementStr = implode($_POST['elementids'], ",");
      $pids =[];
      $desprod=[];
      $qry = "SELECT productid from customdesign WHERE elementid IN ( $elementStr )";
       if(!$stmt = $dbcon->prepare($qry)){
         die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
     }

     if(!$stmt->execute()){
         die('Error : ('. $dbcon->errno .') '. $dbcon->error);
     }

     $stmt->store_result();
     $stmt->bind_result($a);
     while ($stmt->fetch()) {
      $pids[] = $a;
     }
     $stmt->close();
     if(count($pids) > 0) {
        $pidStr = implode($pids, ",");
        $qry = "SELECT productid, name, price, mainimg from products WHERE productid IN ( $pidStr )  AND mainimg<>'' LIMIT 2 ";
         if(!$stmt = $dbcon->prepare($qry)){
           die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
       }

       if(!$stmt->execute()){
           die('Error : ('. $dbcon->errno .') '. $dbcon->error);
       }

       $stmt->store_result();
       $stmt->bind_result($a,$b, $c, $d);
       while ($stmt->fetch()) {
        $desprod[] = ['productid' => $a, 'name' => $b,  'price' => $c, 'mainimg' => $d];
     }
     $stmt->close();
    $jsondata = array(
      "products"  => $desprod,
      "error" => ""
    );

     }
     else {
      //there are no designer picks for these elements.
        $jsondata = array(
          "error" => ""
        );
     }

    }
    else {
        $jsondata = array(
          "error" => ""
        );
    }

     echo json_encode($jsondata);
    exit();
  }
}

?>