<?php
error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");
require_once($_SERVER['DOCUMENT_ROOT']."/utils/functions.php");

// Trims an image then optionally adds padding around it.
// $im  = Image link resource
// $bg  = The background color to trim from the image
// $pad = Amount of padding to add to the trimmed image
//        (acts simlar to the "padding" CSS property: "top [right [bottom [left]]]")


function imagetrim(&$im, $bg, $pad=null){
    // Calculate padding for each side.
    if (isset($pad)){
        $pp = explode(' ', $pad);
        if (isset($pp[3])){
            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[3]);
        }else if (isset($pp[2])){
            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[1]);
        }else if (isset($pp[1])){
            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[0], (int) $pp[1]);
        }else{
            $p = array_fill(0, 4, (int) $pp[0]);
        }
    }else{
        $p = array_fill(0, 4, 0);
    }

    // Get the image width and height.
    $imw = imagesx($im);
    $imh = imagesy($im);

    // Set the X variables.
    $xmin = $imw;
    $xmax = 0;

    // Start scanning for the edges.
    for ($iy=0; $iy<$imh; $iy++){
        $first = true;
        for ($ix=0; $ix<$imw; $ix++){
            $ndx = imagecolorat($im, $ix, $iy);
            if ($ndx != $bg){
                if ($xmin > $ix){ $xmin = $ix; }
                if ($xmax < $ix){ $xmax = $ix; }
                if (!isset($ymin)){ $ymin = $iy; }
                $ymax = $iy;
                if ($first){ $ix = $xmax; $first = false; }
            }
        }
    }

    // The new width and height of the image. (not including padding)
    $imw = 1+$xmax-$xmin; // Image width in pixels
    $imh = 1+$ymax-$ymin; // Image height in pixels

    // Make another image to place the trimmed version in.
    $im2 = imagecreatetruecolor($imw+$p[1]+$p[3], $imh+$p[0]+$p[2]);
     imagesavealpha($im2, true);

    // Make the background of the new image the same as the background of the old one.
    $bg2 = imagecolorallocatealpha($im2, 0,0,0,127);
    imagefill($im2, 0, 0, $bg2);

    // Copy it over to the new image.
    imagecopy($im2, $im, $p[3], $p[0], $xmin, $ymin, $imw, $imh);

    // To finish up, we replace the old image which is referenced.
    $im = $im2;
}

 function shadow_text($im, $size, $x, $y, $font, $text)
  {
    $fcolor = imagecolorallocate($im, 128, 128, 128);
    imagettftext($im, $size, 0, $x + 1, $y + 1, $fcolor, $font, $text);
  }

function createCustomPrdImage($elemArr)
 {
       //get total height of the design
    $totalheight =200; //initial offset
    $totalwidth =250; //initial offset
    foreach($elemArr as $elm) {
        $totalheight += $elm['imgheight'];
        $totalwidth += $elm['imgwidth'];
    };


    $img = imagecreatetruecolor($totalwidth, $totalheight);
    imagesavealpha($img, true);
    $color = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagefill($img, 0, 0, $color);

     //start constructing the image
    $offsetx = 15;
    $offsety = 15;
     foreach($elemArr as $key => $elm) {
      $imgpart = imagecreatefrompng("../productImages/".$elm['selectedImage']);
        $orig_w = $elm["imgwidth"];
        $orig_h = $elm["imgheight"];

        $dst_x = $elm['leftPos'];
        $dst_y = $elm['topPos'];
        if($dst_x < 0 && $key == 0) {
            $offsetx += abs($dst_x);
        }
        $dst_x += $offsetx;
        $dst_y += $offsety;

      imagealphablending($imgpart, false);
 //     bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
      imagecopyresampled($img, $imgpart, $dst_x, $dst_y, 0, 0, $orig_w, $orig_h, $orig_w, $orig_h);
      imagesavealpha( $img, true );
    }

    // if(!imagepng($img, "../productImages/test0.png", 1)){
    //   return "ERROR";
    // }

    imagetrim($img,$color, '10 10 10 10');
    $ow  = imagesx($img);
    $oh = imagesy($img);


    $out_w = $ow*2;
    $out = imagecreatetruecolor($out_w, $oh+20);
    imagesavealpha($out, true);
    imagefill($out, 0, 0, $color);

    $curr_x = 0;
    $curr_y = 0;
    while($curr_x < $out_w){
    imagealphablending($out, false);
    imagecopy($out, $img, $curr_x, $curr_y, 0, 0, $ow, $oh);
    imagesavealpha( $out, true );

    $curr_x += $ow;
    $curr_y = 15;

    }

         $font = '../fonts/arial.ttf';
         $size = 6;

        $bbox = imagettfbbox($size, 0, $font, 'ky');
        $x =  $ow-10; $y = $bbox[5]+12;

        $text = 'FITOORI DESIGNS';
        shadow_text($out, $size, $x, $y, $font, $text);


   $fn = md5(microtime()."new")."_custom.png";

    $result;
    if(imagepng($out, "../productImages/".$fn, 9)){
      $result = $fn;
    }
    else {
      $result = "ERROR";
    }
    imagedestroy($img);
    imagedestroy($out);
    return $result;
 }



if(isset($_REQUEST["sendemail"])) {
  $toemail = $_POST["toemail"];
  $subject = $_POST["subject"];
  $messagebody = $_POST["messageArea"];
   echo sendemail($toemail, $subject, $messagebody);
  exit();
}


if(isset($_REQUEST["orderUpdate"])) {
   $ostat = $_POST['status'];
   $oid = $_POST['oid'];
   $statement = $dbcon->prepare("UPDATE orders SET status=? WHERE orderid=?");

//bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
$statement->bind_param('si', $ostat, $oid);
$results =  $statement->execute();
if($results){
    print $ostat;
}else{
    print 'Error : ('. $dbcon->errno .') '. $dbcon->error;
}

    exit();
}





if(isset($_REQUEST["cartUpdate"])) {
    $_SESSION['cartids'] = $_POST['productids'];

    $totalPrice = $_POST['totalPrice'];
    if($totalPrice == "0") $totalPrice =0;
    $_SESSION['cartPrice'] = round(floatval($totalPrice));
  exit();
}

if(isset($_REQUEST["getCart"])) {
  $cpids = isset($_SESSION['cartids']) ? $_SESSION['cartids'] : [];
  $cprice = isset($_SESSION['cartPrice']) ? $_SESSION['cartPrice'] : 0;
      $jsondata = array(
      "productids"  => $cpids,
      "totalPrice"  => $cprice,
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

if(isset($_GET["getCustom"])) {
    $custEarrings = isset($_SESSION['customEarrings']) ? $_SESSION['customEarrings'] : [];
       $jsondata = array(
          "customizedEarrings" => $custEarrings
      );
     echo json_encode($jsondata);
  exit();
}

if(isset($_GET["addcustom"])) {
    if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
      $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
      // var_dump($_POST["custom_product"]);

      $result ="";

      if(!empty($_POST["custom_product"])){

        $elements = $_POST["custom_product"];

        $customImgName = createCustomPrdImage($elements);

        if($customImgName == "ERROR"){
          echo "ERROR";
          exit();
        }

        $prod_price = $_POST["product_price"];
        $customized = 1;

        $prd_qry  = "insert into products (price, mainimg, customized) VALUES (?, ?, ?)";

        $ins_stmt = $dbcon->prepare($prd_qry);
        if(!$ins_stmt) {
         die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $ins_stmt->bind_param('dsi', $prod_price, $customImgName, $customized);

        if($ins_stmt->execute()){
              $prodid=$ins_stmt->insert_id;
          }else{
            die('Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $ins_stmt->close();

        $currUserEmail = isGuest() ? "guest" :  getCurrentUserEmail();
        $currUsertype= isGuest() ? "2" : "1";


        $elem_qry = "INSERT into customdesign (`productid`, `elementid`,`leftPos`, `topPos`, `selectedImage`, `addedBy`, `addedByType` ) VALUES (?,?,?,?,?,?,?)";
        $ins_stmt1 = $dbcon->prepare($elem_qry);
        if(!$ins_stmt1) {
         die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
        }

        //update pieces table for quantity
        $pqry = "UPDATE pieces set quantity = quantity-2 where id=?";
        $upd_stmt = $dbcon->prepare($pqry);
        if(!$upd_stmt) {
          die('Update Error : ('. $dbcon->errno .') '. $dbcon->error);
        }

        $matlist = "";
        $previd = -1;
        foreach($elements as $elem){
        //  var_dump($elem);
          if($previd != $elem["id"]) {
            $matlist .=  $elem['name']."<br>";
          }
          $previd = $elem["id"];

          $ins_stmt1->bind_param('iiiisss', $prodid, $elem['id'], $elem['leftPos'], $elem['topPos'], $elem['selectedImage'],$currUserEmail, $currUsertype);

          if(!$ins_stmt1->execute()){
              die('Custom Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
          }
          $upd_stmt->bind_param('i', $elem['id']);
          if(!$upd_stmt->execute()){
              die('Update pieces Error : ('. $dbcon->errno .') '. $dbcon->error);
          }

          // if $elem.quantity -2 < 2 then send email from here
          if(($elem['quantity'] -2 ) <= 2) {
             $messageDetail = "The element low on quantity is ID: ".$elem['id']." Image:  <img src='http://fitoori.com/productImages/".$elem['selectedImage']."' />";
           // $result =  sendemail('rezinas@gmail.com', "Pieces low on Quantity",  $messageDetail);
          }

        }

        $ins_stmt1->close();
        $upd_stmt->close();

        $result = "SUCCESS";
        $earObj = array(
                    "pid" => $prodid,
                    "imgName" => $customImgName,
                    "price" => $prod_price
                  );
        $custEarrings = isset($_SESSION['customEarrings']) ? $_SESSION['customEarrings'] : [];
        $custEarrings[] = $earObj;
        $_SESSION['customEarrings'] = $custEarrings;

        //get new pieces

//get only earring parts and filter by the startStyle.
        $startStyle = $_SESSION['startStyle'];
$newpieces =[];

$qry = "SELECT id, carouselImg,  imgheight, imgwidth, bodypart, centerx, centery,  toppoints, topX, topY, bottompoints, botX, botY, color, texture, style, admintags, material, price, name, quantity, priority from pieces where bodypart=3 and  quantity >= 2 and find_in_set('$startStyle', style) <> 0 order by priority";


  $stmt = $dbcon->prepare($qry);
if(!$stmt->execute()){
    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
}
$stmt->store_result();
$stmt->bind_result($a,$b,$bh, $bw, $c,  $cx, $cy, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $q, $s);
while ($stmt->fetch()) {
  $row=[];
  $row = ['id' => $a, 'carouselImg' => $b, 'imgheight'=>  $bh, 'imgwidth'=>  $bw, 'bodypart' => $c,  'centerx' => $cx, 'centery' => $cy, 'toppoints' => $d, 'topX' => $e, 'topY' => $f, 'bottompoints' => $g, 'botX' => $h, 'botY' => $i, 'color' => $j, 'texture' => $k, 'style' => $l, 'admintags' => $m, 'material' => $n, 'price' => $o, 'name' => $p, 'quantity' =>$q, 'priority' => $s];

  $qry2 = "SELECT color, design, imagefile, imageid, pieceid from pieceimages where pieceid = ".$a;
  $stmt1 = $dbcon->prepare($qry2);
  if(!$stmt1->execute()){
    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
  }

  $stmt1->store_result();
  $stmt1->bind_result($a,$b, $c, $d, $e);
  $images=[];
  while ($stmt1->fetch()) {
      $images[] = ['color' => $a, 'design' => $b, 'imagefile' => $c, 'imageid' => $d, 'pieceid' => $e];
  }

  $stmt1->close();
    $row["images"] = $images;
  $newpieces[] = $row;
}
$stmt->close();

      }
      else $result = "ERROR";

       $jsondata = array(
          "result"  => $result,
          "pprice" => $prod_price,
          "pid" => $prodid,
          "pimg" => $customImgName,
          "customizedEarrings" => $custEarrings,
          "newpieces" => $newpieces,
          "matlist" => $matlist
    );
     echo json_encode($jsondata);

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
      $qry = "SELECT productid from customdesign WHERE elementid IN ( $elementStr ) AND isProduct=1";
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
        $qry = "SELECT productid, name, price, mainimg from products WHERE productid IN ( $pidStr )  AND designerPick=1 LIMIT 2 ";
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