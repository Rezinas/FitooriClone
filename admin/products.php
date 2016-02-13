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
$pavail  = "";
$pstatus  = "";
$featured  = "";
$promoted  = "";
$mainimage = "";
$alt1image = "";
$alt2image = "";
$featuredimage = "";
$promotedimage = "";
$pid= "";


if(isset($_GET["product"]) && isset($_GET["id"]) ) {
    $pid=trim($_GET["id"]);
    $mode = "edit";
    //retrieve the product with this id and populate all the above variables.

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
	  $pavail  = prepare_input($_POST['pavail' ]);
	  $pstatus  = prepare_input($_POST['pstatus' ]);
	  $featured  = prepare_input($_POST['featured' ]);
	  $promoted  = prepare_input($_POST['promoted' ]);

	  if($pname == ""  || $sdesc == "" || $pprice == "" || $pdesc == "" || $pquantity == "" || $addinfo == "" || $pcategory == "" || $pitem == "" || $pavail == "" || $pstatus == "" || $featured == "" || $promoted == "" ) {
	  	$error .= "Form Error.. some input is empty.";
	  }

	  var_dump($_FILES);
	$mainimage =  uploadPrdImage($_FILES['mainfile'] ['tmp_name'], $_FILES['mainfile'] ['name'], $_FILES['mainfile'] ['error']);
	$alt1image =  uploadPrdImage($_FILES['alt1file'] ['tmp_name'], $_FILES['alt1file'] ['name'], $_FILES['alt1file'] ['error']);
	$alt2image =  uploadPrdImage($_FILES['alt2file'] ['tmp_name'], $_FILES['alt2file'] ['name'], $_FILES['alt2file'] ['error']);

	if ((strpos($mainimage,'ERROR') !== false || strpos($alt1image,'ERROR') !== false || strpos($alt2image,'ERROR') !== false)   && $mode == "new") {
	  	$error  .= "Form Error.. image uploads failed.".$_FILES['mainfile']['error'];
	}

	if($featured == "1") {
		$featuredimage =  uploadPrdImage($_FILES['featured'] ['tmp_name'], $_FILES['featured'] ['name'], $_FILES['featured'] ['error']);
		if (strpos($featuredimage,'ERROR') !== false ) {
			$error .= "Form Error... featured  image upload failed";
		}
	}
	if($promoted == "1") {
		$promotedimage =  uploadPrdImage($_FILES['promoted'] ['tmp_name'], $_FILES['promoted'] ['name'], $_FILES['promoted'] ['error']);
		if (strpos($promotedimage,'ERROR') !== false ) {
			$error .= "Form Error... promoted  image upload failed";
		}
	}

    if ($error  == "")
    {


    	if($mode == "new") {
    	//insert into database all product values
        	//get the id of the inserted row.
    	//set mode as edit and set $pid.
    	// 	$mode="edit";
    	// 	$pid ="1";
    	// }

	  $curr_userID = getCurrentUserID();
	  $curr_userType = getCurrentUserType();
	  $curr_userEmail = getCurrentUserEmail();

	$query = "INSERT INTO `products` (`name`, `price`, `item`, `category`, `status`, `shortdesc`, `detaildesc`, `addinfo`, `featured`, `promoted`, `addedUsertype`, `addedbyUserEmail`, `quantity`) VALUES ( ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?)";
	$statement = $dbcon->prepare($query);

	//bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
	$statement->bind_param('sdiiisssiiisi', $pname, floatval($pprice), intval($pitem), intval($pcategory), intval($pstatus), $sdesc, $pdesc, $addinfo, intval($featured), intval($promoted), $curr_userType, $curr_userEmail, intval($pquantity) );

	if($statement->execute()){
		$mode = "edit";
    		$pid=$statement->insert_id;

  	}else{
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}
	$statement->close();

	//query for productimages table

	$query1 = "INSERT INTO `productimages` (`prdid`, `prdimage_name`, `prdimage_type`) VALUES (?,?,?)";
	$statement1 = $dbcon->prepare($query1);
	$mi = MAINIMG;
	$statement1->bind_param('isi', $pid, $mainimage, $mi);

	if(!$statement1->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}
	$ai1 = ALTERNATE1IMG;
	$statement1->bind_param( 'isi', $pid, $alt1img, $ai1);

	if(!$statement1->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$ai2 = ALTERNATE2IMG;
	$statement1->bind_param( 'isi', $pid, $alt2img, $ai2);

	if(!$statement1->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if($promoted == "1") {
		$primg= PROMOTEDIMG;
		$statement1->bind_param( 'isi', $pid, $promotedimage, $primg);

		if(!$statement1->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
	}
	if($featured == "1") {
		$fimg= FEATUREDIMG;
		$statement1->bind_param( 'isi', $pid, $featuredimage, $fimg);

		if(!$statement1->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
	}
	$statement1->close();

    	}
    	else if($mode == "edit") {
    	 	//run the update query for the $pid.
    	 	//UPDATE tblFacilityHrs SET title =? description = ? WHERE uid = ?
    	 	$updQuery1 =  "UPDATE products SET `name` = ?, `price` = ?, `item` = ?, `category` = ?, `status` = ?, `shortdesc` = ?, `detaildesc` = ?, `addinfo` = ?, `featured` = ?, `promoted` = ?, `addedUsertype` = ?, `addedbyUserEmail` = ?, `quantity`=? WHERE productid=$pid ";

    	 	$stmt = $dbcon->prepare($updQuery1);
		$stmt->bind_param('sdiiisssiiisi', $pname, floatval($price), intval($pitem), intval($pcategory), intval($pstatus), $sdesc, $pdesc, $addinfo, intval($featured), intval($promoted), $curr_userType, $curr_userEmail, intval($pquantity) );
		if(!$stmt->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		$stmt->close();

		//prdimages table update
		if (strpos($mainimage,'ERROR') === false){
			$updQry2 = "UPDATE productimages SET `prdimage_name` =?  WHERE prdid = $pid AND prdimage_type='".MAINIMG."'";
			if (!mysqli_query($dbcon, $updQry2)) {
				 echo "Error updating record: " . mysqli_error($dbcon);
			}
		}
		if (strpos($alt1image,'ERROR') === false){
			$updQry2 = "UPDATE productimages SET `prdimage_name` =?  WHERE prdid = $pid AND prdimage_type='".ALTERNATE1IMG."'";
			if (!mysqli_query($dbcon, $updQry2)) {
				 echo "Error updating record: " . mysqli_error($dbcon);
			}
		}
		if (strpos($alt2image,'ERROR') === false){
			$updQry2 = "UPDATE productimages SET `prdimage_name` =?  WHERE prdid = $pid AND prdimage_type='".ALTERNATE2IMG."'";
			if (!mysqli_query($dbcon, $updQry2)) {
				 echo "Error updating record: " . mysqli_error($dbcon);
			}
		}
		if (strpos($featuredimage,'ERROR') === false){
			$updQry2 = "UPDATE productimages SET `prdimage_name` =?  WHERE prdid = $pid AND prdimage_type='".FEATUREDIMG."'";
			if (!mysqli_query($dbcon, $updQry2)) {
				 echo "Error updating record: " . mysqli_error($dbcon);
			}
		}
		if (strpos($promotedimage,'ERROR') === false){
			$updQry2 = "UPDATE productimages SET `prdimage_name` =?  WHERE prdid = $pid AND prdimage_type='".PROMOTEDIMG."'";
			if (!mysqli_query($dbcon, $updQry2)) {
				 echo "Error updating record: " . mysqli_error($dbcon);
			}
		}

    	}
    }
    else {
    	echo "Error = ".$error;
 	   exit();
	}
}

?>