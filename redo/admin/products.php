<?php
$mode = "new";
$pname  = "";
$sdesc  = "";
$pprice  = "";
$pdesc  = "";
$pquantity  = "";
$addinfo  = "";
$pcategory  = "";
$pitem  = "";
$pstatus  = "";
$featured  = "";
$mainimage = "";
$alt1image = "";
$alt2image = "";
$pid= "";
$mi="";
$ai1 = "";
$ai2 = "";
$ptags ="";
$psize ="";
$pcustomized ="0";
$desArr = [];
$style_list = [];
$designerPick ="0";


if(isset($_GET["product"]) && isset($_GET["id"]) ) {
    $pid=trim($_GET["id"]);
    $mode = "edit";

    if(isset($_GET["cdesign"])){
    	$pcustomized ="1";
    }

    $qry = "SELECT  `name`, `price`, `bodypart`, `material`, `mainimg`, `alt1img`, `alt2img`, `status`, `shortdesc`, `detaildesc`, `addinfo`, `featured`, `addedUsertype`, `addedbyUserEmail`, `quantity`,  `tags`,  `size`, `designerPick`,  `style` from products WHERE productid=$pid";
 	if(!$stmt = $dbcon->prepare($qry)){
	    die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if(!$stmt->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$stmt->store_result();
	$stmt->bind_result($a,$b, $c, $d, $i1, $i2, $i3, $e, $f, $g, $h, $i,  $k, $l, $m, $n, $o, $p, $q);
	while ($stmt->fetch()) {
		$parr = ['name' => $a, 'price' => $b,  'bodypart' => $c, 'material' => $d, 'mainimg' => $i1, 'alt1img' => $i2, 'alt2img' => $i3, 'status' => $e, 'shortdesc' => $f, 'detaildesc' => $g, 'addinfo' => $h, 'featured' => $i, 'addedUsertype' => $k, 'addedbyUserEmail' => $l, 'quantity' => $m, 'tags' => $n, 'size' => $o, 'designerPick' => $p, 'style' => $q];
	}
	$stmt->close();

	$pname  = $parr['name'];
	$sdesc  = $parr['shortdesc'];
	$pprice  = $parr['price'];
	$pdesc  = $parr['detaildesc'];
	$pquantity  = $parr['quantity'];
	$addinfo  = $parr['addinfo'];
	$pcategory  = $parr['material'];
	$pitem  = $parr['bodypart'];
	$pstatus  = $parr['status'];
	$featured  = $parr['featured'];
	$style_list=explode(',', $parr['style']);
	$mainimage = ($parr['mainimg'] == "") ? "ERROR" : $parr['mainimg'];
	$alt1image = ($parr['alt1img'] == "") ? "ERROR" : $parr['alt1img'];
	$alt2image = ($parr['alt2img'] == "") ? "ERROR" : $parr['alt2img'];
	$mi = $parr['mainimg'];
	$ai1 = $parr['alt1img'];
	$ai2 = $parr['alt2img'];
	$psize = $parr['size'];
	$designerPick = $parr['designerPick'];
	$ptags = $parr['tags'];
}
if (!empty($_POST)) {

	$error = "";
	  $mode  = prepare_input($_POST['mode' ]);
	  $pid  = prepare_input($_POST['product_id' ]);


	  $pname  = prepare_input($_POST['pname' ]);
	  $sdesc  = prepare_input($_POST['sdesc' ]);
	  $pprice  = prepare_input($_POST['pprice' ]);
	  $psize  = prepare_input($_POST['psize' ]);
	  $ptags  = prepare_input($_POST['ptags' ]);
	  $pdesc  = prepare_input($_POST['pdesc' ]);
	  $pquantity  = prepare_input($_POST['pquantity' ]);
	  $addinfo  = prepare_input($_POST['addinfo' ]);
	  $pcategory  = prepare_input($_POST['pcategory' ]);
	  $pitem  = prepare_input($_POST['pitem' ]);
	  $pstatus  = prepare_input($_POST['pstatus' ]);
	  $featured  = prepare_input($_POST['featured' ]);
	   if(isset($_POST['style_list'])) {
	  	$style_list = $_POST['style_list'];
	  }

	  if($pstatus == "") $pstatus =1;

	  if($mode != "new"){
		  $mi = prepare_input($_POST['mi' ]);
		  $ai1 = prepare_input($_POST['ai1' ]);
		  $ai2 = prepare_input($_POST['ai2' ]);
		  $pcustomized  = prepare_input($_POST['pcustomized' ]);
		  if(isset($_POST['designerPick'])){
		  	$designerPick = $_POST["designerPick"];
		  }

	  }


	  if($pname == ""  || $sdesc == "" || $pprice == "" || $pdesc == "" || $pquantity == "" || $addinfo == "" || $pcategory == "" || $pitem == "" ||  $pstatus == "" || $featured == "" ) {
	  	$error .= "Form Error.. some input is empty.";
	  }

	$mainimage =  uploadPrdImage($_FILES['mainfile'] ['tmp_name'], $_FILES['mainfile'] ['name'], $_FILES['mainfile'] ['error']);
	$alt1image =  uploadPrdImage($_FILES['alt1file'] ['tmp_name'], $_FILES['alt1file'] ['name'], $_FILES['alt1file'] ['error']);
	$alt2image =  uploadPrdImage($_FILES['alt2file'] ['tmp_name'], $_FILES['alt2file'] ['name'], $_FILES['alt2file'] ['error']);

	if (strpos($mainimage,'ERROR') !== false  && $mode == "new") {
	  	$error  .= "Form Error.. main image uploads failed.".$_FILES['mainfile']['error'];
	}



	if (strpos($mainimage,'ERROR') === false){
			$mi = $mainimage;
	}
	if (strpos($alt1image,'ERROR') === false){
		$ai1 = $alt1image;
		}
	if (strpos($alt2image,'ERROR') === false){
		$ai2 = $alt2image;
	}


    if ($error  == "")
    {
    	if($mode == "new") {

	  $curr_userID = getCurrentUserID();
	  $curr_userType = isAgent()? 0 : 1;
	  $curr_userEmail = getCurrentUserEmail();

	$query = "INSERT INTO `products` (`name`, `price`, `bodypart`, `material`, `mainimg`, `alt1img`, `alt2img`,  `status`, `shortdesc`, `detaildesc`, `addinfo`, `featured`, `addedUsertype`, `addedbyUserEmail`, `quantity`, `size`, `tags`, `style`) VALUES ( ?, ?, ?,?,?,?, ?,?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";
	$statement = $dbcon->prepare($query);

	//bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
	$statement->bind_param('sdiisssisssiisisss', $pname, floatval($pprice), intval($pitem), intval($pcategory), $mi, $ai1, $ai2,  intval($pstatus), $sdesc, $pdesc, $addinfo, intval($featured), $curr_userType, $curr_userEmail, intval($pquantity), $psize, $ptags, implode(",", $style_list));

	if($statement->execute()){
		$mode = "edit";
    		$pid=$statement->insert_id;

  	}else{
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}
	$statement->close();



    	}
    	else if($mode == "edit") {
    	 	//run the update query for the $pid.
    	 	//UPDATE tblFacilityHrs SET title =? description = ? WHERE uid = ?
    	 	$updQuery1 =  "UPDATE products SET `name` = ?, `price` = ?, `bodypart` = ?, `material` = ?,  `mainimg` = ?, `alt1img` = ?, `alt2img` = ?, `status` = ?, `shortdesc` = ?, `detaildesc` = ?, `addinfo` = ?, `featured` = ?, `addedUsertype` = ?, `addedbyUserEmail` = ?, `quantity`=?,  `size`=?, `tags`=?, `customized`=?, `designerPick`=?, `style`=?  WHERE productid=$pid ";

    	 	$stmt = $dbcon->prepare($updQuery1);
		$stmt->bind_param('sdiisssisssiisissiis', $pname, floatval($pprice), intval($pitem), intval($pcategory), $mi, $ai1, $ai2,  intval($pstatus), $sdesc, $pdesc, $addinfo, intval($featured), $curr_userType, $curr_userEmail, intval($pquantity), $psize, $ptags, intval($pcustomized), intval($designerPick), implode(",", $style_list) );
		if(!$stmt->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		else {
			if($pcustomized == "1") {
				//query to update custom design isProduct
				$qry = "UPDATE customdesign SET `isProduct`=? WHERE productid=$pid";
				$stmt1 = $dbcon->prepare($qry);
				$stmt1->bind_param('i', $pcustomized);
				if(!$stmt1->execute()){
				    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
				}
				$stmt1->close();
			}
		}
		$stmt->close();
    	}
    }
    else {
    	echo "Error = ".$error;
 	   exit();
	}
}

// if($pcustomized == "1") {
// 	$design_qry = "SELECT elementid, leftPos,topPos,selectedImage, addedBy, addedByType, isProduct from customdesign where productid=$pid";

// 			$stmt = $dbcon->prepare($design_qry);
// 			if(!$stmt->execute()){
// 			    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
// 			}
// 			$stmt->store_result();
// 			$stmt->bind_result($a,$b, $c, $d, $e, $f, $g);
// 			while ($stmt->fetch()) {
// 				$desArr[] = ['elementid' => $a, 'leftPos' => $b, 'topPos' =>$c, 'selectedImage' => $d, 'addedBy' => $e,'addedByType' => $f, 'isProduct'=>$g];
// 			}
// 			$stmt->close();
// 			// var_dump($desArr);
// }
?>