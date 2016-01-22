<?php
$itemsArr= explode("|", PRD_ITEM) ;
$colorsArr= explode("|", COLORS) ;
$texturesArr= explode("|", TEXTURES) ;

function getFileInpHTML($prod) {
	$prod=["",""];
	echo '<div class="row">'.
        '<div class="col-md-2"><h4>Filename</h4></div>'.
        '<div class="col-md-2">'.
              '<div class="input-group " >'.
                    '<input type="text" class="form-control" readonly="" >'.
                    '<span class="input-group-btn">'.
                    '<span class="btn btn-primary btn-file">'.
                    'Browse… <input type="file" class="imguploads" name="'.$prod[0]  .'_'. $prod[1].'">'.
                     '</span>'.
                   '</span>'.
                    '</div>'.
        '</div>'.
        '<div class="col-md-4">'.
          '<img src="../productImages/bot5.jpg">'.
        '</div>'.
      '</div>';
}


$pcmode = "new";
$pctop = "";
$pcbot = "";
$pcbody = "";
$pccolors=[];
$pcdesign = [];
$pieceid= "";
$cartesianArr= [];
$topx=[];
$topy=[];
$bottomx=[];
$bottomy=[];
$carouselImg="";


if(isset($_GET["pieces"]) && isset($_GET["id"]) ) {
    $pieceid=trim($_GET["id"]);
    $pcmode = "edit";

 	$qry = "SELECT * from pieces WHERE id=$pieceid";
 	$stmt = $dbcon->prepare($qry);
	if(!$stmt->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}
	if (!($res = $stmt->get_result()))
		die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	$parr = $res->fetch_assoc();
	$stmt->close();

	$pieceid=$parr['id'];
	$pcbody=$parr['bodypart'];
	$pctop=$parr['toppoints']."";
	$pcbot=$parr['bottompoints']."";
	$pccolors = explode(",", $parr['color']);
	$pcdesign = explode(",", $parr['texture']);

	 $cartesianArr = cartesian(array($pccolors, $pcdesign));

	$qry2 = "SELECT * from pieceimages WHERE pieceid=$pieceid";
	$stmt = $dbcon->prepare($qry2);
	if(!$stmt->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}
	if (!($res = $stmt->get_result()))
		die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	$imgs = $res->fetch_all();

	for($ii = 0; $ii < count($cartesianArr); $ii++){
		if($cartesianArr[$ii][0] == $imgs[$ii][2]  && $cartesianArr[$ii][1] == $imgs[$ii][3] ) {
			$cartesianArr[$ii][2]	= $imgs[$ii][4];
		}
	}

	$stmt->close();
// exit();

}

if (!empty($_POST)) {
var_dump($_POST);
	// if (file_exists($filename)) {
	//unlink('path/to/file.jpg');
	$error = "";

	  $pcmode  = prepare_input($_POST['pcmode' ]);
	  $pieceid  = prepare_input($_POST['pieceid' ]);


	  $pctop  = prepare_input($_POST['pctop' ]);
	  $pcbody  = prepare_input($_POST['pcbody' ]);
	  $pcbot  = prepare_input($_POST['pcbot' ]);
	  $pccolors  = $_POST['pccolors' ];
	  $pcdesign  = $_POST['pcdesign' ];

	  for($i=0; $i<intval($pctop); $i++){
	  	$topx[$i] = prepare_input($_POST['topx'.$i ]);
	  	$topy[$i] = prepare_input($_POST['topy'.$i ]);
	  }
	  for($i=0; $i<intval($pcbot); $i++){
	  	$bottomx[$i] = prepare_input($_POST['bottomx'.$i ]);
	  	$bottomy[$i] = prepare_input($_POST['bottomy'.$i ]);
	  }


	$carouselImg = uploadPrdImage($_FILES['carouselImg'] ['tmp_name'], $_FILES['carouselImg'] ['name'], $_FILES['carouselImg'] ['error']);
	if(strpos($carouselImg,'ERROR') !== false) { $error .= "Image upload error"; }

	  //validation : TBD
	  // if($pctop == "" || $pcbody == "" || $pcbot == "" || count($pccolors) <= 0 || count($pcdesign) <= 0) {
	  // 	$error .= "Form Error.. some input is empty.";
	  // }

	  if($pcmode == "new" ) {
	  	$cartesianArr = cartesian(array($pccolors, $pcdesign));
	  	foreach($cartesianArr as $ind => &$product) {
		  	$filename = $product[0]."_".$product[1];
		  	$newfile = uploadPrdImage($_FILES[$filename] ['tmp_name'], $_FILES[$filename] ['name'], $_FILES[$filename] ['error']);
			if(strpos($newfile,'ERROR') !== false) { $error .= "Image upload error"; }
		  	else  { $product[2] = $newfile; }
	  	}
	  }

	 //  var_dump($cartesianArr);
	//var_dump($_FILES);
    if (empty($error))
    {
    	if($pcmode == "new") {
    	//insert into database all product values


	$query = "INSERT INTO `pieces` (`carouselImg`,`bodypart`, `toppoints`,  `topX`, `topY`,`bottompoints`,  `botX`, `botY`,`color`, `texture`) VALUES (?, ?, ?, ?,?,?, ?,?, ?, ?)";
	$ins_stmt = $dbcon->prepare($query);
	if(!$ins_stmt) {
	 die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}
	// bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
	$ins_stmt->bind_param('siississss', $carouselImg, intval($pcbody), intval($pctop), implode(",", $topx), implode(",", $topy),  intval($pcbot), implode(",", $bottomx), implode(",", $bottomy), implode(",", $pccolors),  implode(",", $pcdesign));

	if($ins_stmt->execute()){
		$pcmode = "edit";
    		$pieceid=$ins_stmt->insert_id;
  	}else{
	    die('Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
	}
	$ins_stmt->close();

	//query for pieceimages table
	$qry = "INSERT INTO `pieceimages` (`pieceid`, `color`, `design`, `imagefile`) VALUES (?,?,?,?)";
	$ins_stmt1 = $dbcon->prepare($qry);

	foreach ($cartesianArr as $findex =>$prod) {
		$ins_stmt1->bind_param('isss', $pieceid, $prod[0], $prod[1], $prod[2]);

		if(!$ins_stmt1->execute()){
		    die('Image Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
	}
	$ins_stmt1->close();

    	}
    	else if($pcmode == "edit") {
    	 	//run the update query for the $pieceid.
    	 	$updQuery1 =  "UPDATE pieces  SET  `bodypart` = ?, `toppoints` = ?, `bottompoints` = ?, `color` = ?, `texture` = ? WHERE id=$pieceid ";

    	 	$stmt = $dbcon->prepare($updQuery1);
		$stmt->bind_param('iiiss', intval($pcbody), intval($pctop), intval($pcbot),  implode(",", $pccolors),  implode(",", $pcdesign));
		if(!$stmt->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		$stmt->close();


	// $qry = "UPDATE  `pieceimages` SET  `color` = ? , `design`= ?, `imagefile`=? WHERE pieceid=$pieceid";
	// $stmt1 = $dbcon->prepare($qry);

	// foreach ($cartesianArr as $findex =>$prod) {
	// 	$stmt1->bind_param('sss', $prod[0], $prod[1], $prod[2]);

	// 	if(!$stmt1->execute()){
	// 	    die('Image Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
	// 	}
	// }
	// $stmt1->close();
    	}
    }
    else {
    	echo "Error = ".$error;
 	   exit();
	}
}

?>