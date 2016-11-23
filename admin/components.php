<?php
$categoriesArr= explode("|", CATEGORY) ;
$colorsArr= explode("|", COLORS) ;
$sourceArr= explode("|", SOURCES) ;

$compmode="new";
$compid="";
$compimg="";
$compcost="";
$cutoff="";
$warn="";
$stock="";
$color="";
$source="";
$material="";
$cid="";


if(isset($_GET["components"]) && isset($_GET["cid"]) ) {
    $compid=trim($_GET["cid"]);
    $compmode = "edit";

 	$qry = "SELECT  compid, compimg, stock, costpercomp, material, color, source, cutoff, warning  from components WHERE compid='$compid'";
 	if(!$stmt = $dbcon->prepare($qry)){
	    die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if(!$stmt->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$stmt->store_result();
	$stmt->bind_result($a,$b, $c, $d, $e, $f, $g, $h, $i);
	while ($stmt->fetch()) {
		$parr = ['compid' => $a, 'compimg' => $b, 'stock' => $c, 'costpercomp' => $d,'material'=>  $e, 'color'=>  $f,  'source' => $g, 'cutoff' => $h, 'warning' => $i];
	}
	$stmt->close();
	$compid=$parr['compid']."";
	$compimg=$parr['compimg'];
	$stock=$parr['stock']."";
	$compcost=$parr['costpercomp']."";
	$material=$parr['material']."";
	$color=$parr['color']."";
	$source=$parr['source']."";
	$cutoff=$parr['cutoff']."";
	$warn=$parr['warning']."";

}



if (!empty($_POST)) {

	  $error = "";
	  $compmode  = prepare_input($_POST['compmode' ]);
	  $compid  = prepare_input($_POST['compid' ]);
	  $compcost  = prepare_input($_POST['costpp' ]);
	  $compimg  = prepare_input($_POST['compimg' ]);
	  $cutoff = prepare_input($_POST['cutoff' ]);
	  $warn  = prepare_input($_POST['warn' ]);
	  $stock  = prepare_input($_POST['stock' ]);
	  $color  = prepare_input($_POST['ccolor' ]);
	  $source  = prepare_input($_POST['csource' ]);
	  $material  = prepare_input($_POST['material' ]);

	if($_FILES['compimg']['error'] == 0) {
		$compimg = uploadCmpImage($_FILES['compimg'] ['tmp_name'], $_FILES['compimg'] ['name'], $_FILES['compimg'] ['error']);
		if(strpos($compimg,'ERROR') !== false) { $error .= "Image upload error"; }
	  }


	if ($error  == "")
	 {

		if ($compmode == "new") {

		$query ="INSERT INTO `components` (`compid`, `compimg`, `stock`, `costpercomp`, `material`, `color`, `source`, `cutoff`, `warning`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$statement = $dbcon->prepare($query);

		//bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
		$statement->bind_param('ssidissii', $compid, $compimg, intval($stock), floatval($compcost), $material, $color, $source, intval($cutoff), intval($warn));

		if(!$statement->execute()){
		die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		else{
    		$compmode = "edit";

			echo "<br /><br /> Record Added Successfully!<br /><br /><br />";
		}
	 	$statement->close();

		}

		else if ($compmode == "edit"){
			//run the update query for the $pieceid.

    	 	$updQuery1 =  "UPDATE components  SET `compimg`=?, `stock`=?, `costpercomp`=?, `material`=?, `color`=?, `source`=?, `cutoff`=?, `warning`=? WHERE compid='$compid'";
    	 	$stmt = $dbcon->prepare($updQuery1);

			$stmt->bind_param('sidissii', $compimg, intval($stock), floatval($compcost), $material, $color, $source, intval($cutoff), intval($warn));

			if(!$stmt->execute()){
				die('Error : ('. $dbcon->errno .') '. $dbcon->error);
				echo "<br /><br />Action FAILED!";
			}
			else {
				echo "<br /><br /> Record Added Successfully!<br /><br /><br />";
			}
			$stmt->close();
			echo "<br /><br />Record updated Successfully!<br /><br />";
		}
	 }
	 else
	 {
		echo "Error = ".$error;
 	   exit();
	 }


}



?>