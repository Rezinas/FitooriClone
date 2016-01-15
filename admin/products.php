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

//var_dump($_POST);

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

	$mainimage =  uploadPrdImage($_FILES['mainfile'] ['tmp_name'], $_FILES['mainfile'] ['name'], $_FILES['mainfile'] ['error']);
	$alt1image =  uploadPrdImage($_FILES['alt1file'] ['tmp_name'], $_FILES['alt1file'] ['name'], $_FILES['alt1file'] ['error']);
	$alt2image =  uploadPrdImage($_FILES['alt2file'] ['tmp_name'], $_FILES['alt2file'] ['name'], $_FILES['alt2file'] ['error']);

	if (strpos($mainimage,'ERROR') !== false || strpos($alt1image,'ERROR') !== false || strpos($alt2image,'ERROR') !== false) {
	  	$error  .= "Form Error.. image uploads failed.";
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
    		$mode = "edit";
    		$pid= "1";
    	}
    	else if($mode == "edit") {
    	 //run the update query for the $pid.
    	}
    }
    else {
    	echo "Error = ".$error;
 	   exit();
	}
}

?>