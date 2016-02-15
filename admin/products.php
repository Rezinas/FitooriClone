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
$promoted  = "";
$mainimage = "";
$alt1image = "";
$alt2image = "";
$featuredimage = "";
$promotedimage = "";
$pid= "";
$mi="";
$ai1 = "";
$ai2 = "";
$primg="";
$fimg= "";


if(isset($_GET["product"]) && isset($_GET["id"]) ) {
    $pid=trim($_GET["id"]);
    $mode = "edit";

    $qry = "SELECT  `name`, `price`, `bodypart`, `material`, `mainimg`, `alt1img`, `alt2img`, `promotedimg`,`featuredimg`, `status`, `shortdesc`, `detaildesc`, `addinfo`, `featured`, `promoted`, `addedUsertype`, `addedbyUserEmail`, `quantity`  from products WHERE productid=$pid";
 	if(!$stmt = $dbcon->prepare($qry)){
	    die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if(!$stmt->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$stmt->store_result();
	$stmt->bind_result($a,$b, $c, $d, $i1, $i2, $i3, $i4, $i5, $e, $f, $g, $h, $i, $j, $k, $l, $m);
	while ($stmt->fetch()) {
		$parr = ['name' => $a, 'price' => $b,  'bodypart' => $c, 'material' => $d, 'mainimg' => $i1, 'alt1img' => $i2, 'alt2img' => $i3, 'promotedimg' => $i4, 'featuredimg' => $i5, 'status' => $e, 'shortdesc' => $f, 'detaildesc' => $g, 'addinfo' => $h, 'featured' => $i, 'promoted' => $j, 'addedUsertype' => $k, 'addedbyUserEmail' => $l, 'quantity' => $m];
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
	$promoted  = $parr['promoted'];
	$mainimage = ($parr['mainimg'] == "") ? "ERROR" : $parr['mainimg'];
	$alt1image = ($parr['alt1img'] == "") ? "ERROR" : $parr['alt1img'];
	$alt2image = ($parr['alt2img'] == "") ? "ERROR" : $parr['alt2img'];
	$featuredimage = ($parr['promotedimg'] == "") ? "ERROR" : $parr['promotedimg'];
	$promotedimage = ($parr['featuredimg'] == "") ? "ERROR" : $parr['featuredimg'];
	$mi = $parr['mainimg'];
	$ai1 = $parr['alt1img'];
	$ai2 = $parr['alt2img'];
	$fimg =  $parr['promotedimg'];
	$primg = $parr['featuredimg'];
}

if (!empty($_POST)) {

	$error = "";
	  $mode  = prepare_input($_POST['mode' ]);
	  $pid  = prepare_input($_POST['product_id' ]);


	  $pname  = prepare_input($_POST['pname' ]);
	  $sdesc  = prepare_input($_POST['sdesc' ]);
	  $pprice  = prepare_input($_POST['pprice' ]);
	  $pdesc  = prepare_input($_POST['pdesc' ]);
	  $pquantity  = prepare_input($_POST['pquantity' ]);
	  $addinfo  = prepare_input($_POST['addinfo' ]);
	  $pcategory  = prepare_input($_POST['pcategory' ]);
	  $pitem  = prepare_input($_POST['pitem' ]);
	  $pstatus  = prepare_input($_POST['pstatus' ]);
	  $featured  = prepare_input($_POST['featured' ]);
	  $promoted  = prepare_input($_POST['promoted' ]);

	  if($mode != "new"){
		  $mi = prepare_input($_POST['mi' ]);
		  $ai1 = prepare_input($_POST['ai1' ]);
		  $ai2 = prepare_input($_POST['ai2' ]);
		  $primg = prepare_input($_POST['primg' ]);
		  $fimg = prepare_input($_POST['fimg' ]);
	  }


	  if($pname == ""  || $sdesc == "" || $pprice == "" || $pdesc == "" || $pquantity == "" || $addinfo == "" || $pcategory == "" || $pitem == "" ||  $pstatus == "" || $featured == "" || $promoted == "" ) {
	  	$error .= "Form Error.. some input is empty.";
	  }

	$mainimage =  uploadPrdImage($_FILES['mainfile'] ['tmp_name'], $_FILES['mainfile'] ['name'], $_FILES['mainfile'] ['error']);
	$alt1image =  uploadPrdImage($_FILES['alt1file'] ['tmp_name'], $_FILES['alt1file'] ['name'], $_FILES['alt1file'] ['error']);
	$alt2image =  uploadPrdImage($_FILES['alt2file'] ['tmp_name'], $_FILES['alt2file'] ['name'], $_FILES['alt2file'] ['error']);

	if (strpos($mainimage,'ERROR') !== false  && $mode == "new") {
	  	$error  .= "Form Error.. main image uploads failed.".$_FILES['mainfile']['error'];
	}

	if($featured == "1") {
		$featuredimage =  uploadPrdImage($_FILES['featured'] ['tmp_name'], $_FILES['featured'] ['name'], $_FILES['featured'] ['error']);
		if (strpos($featuredimage,'ERROR') !== false  && $mode == "new" ) {
			$error .= "Form Error... featured  image upload failed";
		}
	}
	if($promoted == "1") {
		$promotedimage =  uploadPrdImage($_FILES['promoted'] ['tmp_name'], $_FILES['promoted'] ['name'], $_FILES['promoted'] ['error']);
		if (strpos($promotedimage,'ERROR') !== false  && $mode == "new" ) {
			$error .= "Form Error... promoted  image upload failed";
		}
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
	if($promoted == "1") {
		 $primg = $promotedimage;
	}
	if($featured == "1") {
		$fimg= $featuredimage;
	}

    if ($error  == "")
    {
    	if($mode == "new") {

	  $curr_userID = getCurrentUserID();
	  $curr_userType = getCurrentUserType();
	  $curr_userEmail = getCurrentUserEmail();

	$query = "INSERT INTO `products` (`name`, `price`, `bodypart`, `material`, `mainimg`, `alt1img`, `alt2img`, `promotedimg`,`featuredimg`, `status`, `shortdesc`, `detaildesc`, `addinfo`, `featured`, `promoted`, `addedUsertype`, `addedbyUserEmail`, `quantity`) VALUES ( ?, ?, ?, ?, ?,?,?,?,?, ?,?, ?, ?, ?, ?, ?, ?, ?)";
	$statement = $dbcon->prepare($query);

	//bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
	$statement->bind_param('sdiisssssisssiiisi', $pname, floatval($pprice), intval($pitem), intval($pcategory), $mi, $ai1, $ai2, $primg, $fimg, intval($pstatus), $sdesc, $pdesc, $addinfo, intval($featured), intval($promoted), $curr_userType, $curr_userEmail, intval($pquantity) );

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
    	 	$updQuery1 =  "UPDATE products SET `name` = ?, `price` = ?, `bodypart` = ?, `material` = ?,  `mainimg` = ?, `alt1img` = ?, `alt2img` = ?, `promotedimg` = ?,`featuredimg` = ?,`status` = ?, `shortdesc` = ?, `detaildesc` = ?, `addinfo` = ?, `featured` = ?, `promoted` = ?, `addedUsertype` = ?, `addedbyUserEmail` = ?, `quantity`=? WHERE productid=$pid ";

    	 	$stmt = $dbcon->prepare($updQuery1);
		$stmt->bind_param('sdiisssssisssiiisi', $pname, floatval($pprice), intval($pitem), intval($pcategory), $mi, $ai1, $ai2, $primg, $fimg,  intval($pstatus), $sdesc, $pdesc, $addinfo, intval($featured), intval($promoted), $curr_userType, $curr_userEmail, intval($pquantity) );
		if(!$stmt->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		$stmt->close();
    	}
    }
    else {
    	echo "Error = ".$error;
 	   exit();
	}
}

?>