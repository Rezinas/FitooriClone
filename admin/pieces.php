<?php
$itemsArr= explode("|", PRD_ITEM) ;
$colorsArr= explode("|", COLORS) ;
$texturesArr= explode("|", TEXTURES) ;
$categoriesArr= explode("|", CATEGORY) ;


function getFileInpHTML($ctArr){
     return  '<div class="col-md-2"><div class="input-group " >'.
                    '<input type="text" class="form-control" readonly=""  value="'. $ctArr['imagefile'].'">'.
                   ' <span class="input-group-btn">'.
                        '<span class="btn btn-primary btn-file">'.
                           ' Browse… <input type="file" class="imguploads" name="'.$ctArr['color']  .'_'. $ctArr['design'].'">'.
                      ' </span>'.
                    '</span>'.
            '</div></div>';
}
function getFileInpNameHTML($ctArr) {
  return '<div class="col-md-2"><h4>'.$ctArr['color'].' - ' .$ctArr['design']. '</h4></div>';
}
function getFilePreviewHTM($ctArr) {
  return  '<div class="col-md-4" id="'.$ctArr['color'].'_' .$ctArr['design']. 'Img">'.
                  '<img src="../productImages/'.$ctArr['imagefile'].'">'.
              '</div>';
}
function getFileRowHTML($ctArr){
  return '<div class="row altImg" id="'.$ctArr['color'].'_' .$ctArr['design']. '">'. getFileInpNameHTML($ctArr) .getFileInpHTML($ctArr).getFilePreviewHTM($ctArr) .' </div>';
}

function compareArr($origArr, $newArr){
	$resultArr=[];
	if(count($origArr) == 0) $resultArr = $newArr;
	else {
		foreach($newArr as $nArr) {
			$dup = false;
			foreach($origArr as $oArr) {
				if($nArr['color'] == $oArr['color'] && $nArr['design'] == $oArr['design']) {
					array_push($resultArr, $oArr);
					$dup = true;
				}
			}
			if($dup === false)
				array_push($resultArr, $nArr);
		}
	}
	return $resultArr;
}

function fetchImages($p_id, $dbcon) {
	  $qry2 = "SELECT color, design, imagefile, imageid, pieceid from pieceimages  WHERE pieceid=$p_id";
		$stmt = $dbcon->prepare($qry2);
		if(!$stmt->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		$stmt->store_result();
		$stmt->bind_result($a,$b, $c, $d, $e);
		$imgs=[];
		while ($stmt->fetch()) {
		    $imgs[] = ['color' => $a, 'design' => $b, 'imagefile' => $c, 'imageid' => $d, 'pieceid' => $e];
		}

		for($ii = 0; $ii < count($imgs); $ii++){
			unset($imgs['imageid']);
			unset($imgs['pieceid']);
		}
		$stmt->close();
		if(count($imgs ==0 ) ){
			//return error message
		}
		return $imgs;
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
$material = "";
$tags="";
$admintags="";

if(isset($_GET["pieces"]) && isset($_GET["id"]) ) {
    $pieceid=trim($_GET["id"]);
    $pcmode = "edit";

 	$qry = "SELECT  id, carouselImg, bodypart, toppoints, topX, topY, bottompoints, botX, botY, color, texture, tags, admintags, material  from pieces WHERE id=$pieceid";
 	if(!$stmt = $dbcon->prepare($qry)){
	    die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if(!$stmt->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$stmt->store_result();
	$stmt->bind_result($a,$b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n);
	while ($stmt->fetch()) {
		$parr = ['id' => $a, 'carouselImg' => $b, 'bodypart' => $c, 'toppoints' => $d, 'topX' => $e, 'topY' => $f, 'bottompoints' => $g, 'botX' => $h, 'botY' => $i, 'color' => $j, 'texture' => $k, 'tags' => $l, 'admintags' => $m, 'material' => $n];
	}
	$stmt->close();
	$pieceid=$parr['id'];
	$pcbody=$parr['bodypart'];
	$pctop=$parr['toppoints']."";
	$pcbot=$parr['bottompoints']."";
	$carouselImg=$parr['carouselImg']."";
	$material=$parr['material'];
	$tags=$parr['tags'];
	$admintags=$parr['admintags'];
	$topx = explode(",", $parr['topX']);
	$topy = explode(",", $parr['topY']);
	$bottomx = explode(",", $parr['botX']);
	$bottomy = explode(",", $parr['botY']);
	$pccolors = explode(",", $parr['color']);
	$pcdesign = explode(",", $parr['texture']);
	$cartesianArr = fetchImages($pieceid, $dbcon);
}

if (!empty($_POST)) {
// echo "<pre>";
// var_dump($_FILES);
// echo "</pre>";
	// if (file_exists($filename)) {
	//unlink('path/to/file.jpg');
	$error = "";

	  $pcmode  = prepare_input($_POST['pcmode' ]);
	  $pieceid  = prepare_input($_POST['pieceid' ]);

	  if(!empty($pieceid)) {
	  	$carouselImg = prepare_input($_POST['carouselImgname']);
		$cartesianArr = fetchImages($pieceid, $dbcon);
// 		echo "<pre>";
// var_dump($cartesianArr);
// echo "</pre>";
	  }



	  $pctop  = prepare_input($_POST['pctop' ]);
	  $pcbody  = prepare_input($_POST['pcbody' ]);
	  $pcbot  = prepare_input($_POST['pcbot' ]);
	 if (isset($_POST['pccolors' ]))
	 	$pccolors  = $_POST['pccolors' ];
	  if (isset($_POST['pcdesign' ]))
	  	$pcdesign  = $_POST['pcdesign' ];

	  for($i=0; $i<intval($pctop); $i++){
	  	$topx[$i] = prepare_input($_POST['topx'.$i ])."";
	  	$topy[$i] = prepare_input($_POST['topy'.$i ])."";
	  }
	  for($i=0; $i<intval($pcbot); $i++){
	  	$bottomx[$i] = prepare_input($_POST['bottomx'.$i ]);
	  	$bottomy[$i] = prepare_input($_POST['bottomy'.$i ]);
	  }

	  $material = prepare_input($_POST['material' ]);
	  $tags = prepare_input($_POST['tags' ]);
	  $admintags = prepare_input($_POST['admintags' ]);

	if($_FILES['carouselImg']['error'] == 0) {
		$carouselImg = uploadPrdImage($_FILES['carouselImg'] ['tmp_name'], $_FILES['carouselImg'] ['name']);
		if(strpos($carouselImg,'ERROR') !== false) { $error .= "Image upload error"; }

	}

//TBD: Validations

	  if( (count($pccolors) > 0 && count($pcdesign)> 0) ) {
	  	$newcartesianArr = cartesian(array($pccolors, $pcdesign));
	  	$cartesianArr = compareArr($cartesianArr, $newcartesianArr);
	  	foreach($cartesianArr as $ind => &$product) {
		  	$filename = $product['color']."_".$product['design'];
		  	if($_FILES[$filename]['error'] == 0) {
		  		$newfile = uploadPrdImage($_FILES[$filename] ['tmp_name'], $_FILES[$filename] ['name'], $_FILES[$filename] ['error']);
				if(strpos($newfile,'ERROR') !== false) { $error .= "Image upload error"; }
			  	else  { $product['imagefile'] = $newfile; }
		  	}
	  	}
	  }

    if (empty($error))
    {
    	if($pcmode == "new") {
    	//insert into database all product values


	$query = "INSERT INTO `pieces` (`carouselImg`,`bodypart`, `toppoints`,  `topX`, `topY`,`bottompoints`,  `botX`, `botY`,`color`, `texture`,`material`,`tags`, `admintags`) VALUES (?, ?, ?, ?,?,?, ?,?, ?, ?,?,?,?)";
	$ins_stmt = $dbcon->prepare($query);
	if(!$ins_stmt) {
	 die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}
	// bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
	$ins_stmt->bind_param('siississssiss', $carouselImg, intval($pcbody), intval($pctop), implode(",", $topx), implode(",", $topy),  intval($pcbot), implode(",", $bottomx), implode(",", $bottomy), implode(",", $pccolors),  implode(",", $pcdesign), $material, $tags, $admintags);

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
		$ins_stmt1->bind_param('isss', $pieceid, $prod['color'], $prod['design'], $prod['imagefile']);

		if(!$ins_stmt1->execute()){
		    die('Image Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
	}
	$ins_stmt1->close();

    	}
    	else if($pcmode == "edit") {
    	 	//run the update query for the $pieceid.
    	 	$updQuery1 =  "UPDATE pieces  SET `carouselImg`=?, `bodypart` = ?, `toppoints` = ?,  `topX`= ?, `topY`=?,`bottompoints` = ?, `botX`=?, `botY`=?, `color` = ?, `texture` = ? , `material` = ? , `tags` = ?, `admintags` = ? WHERE id=$pieceid ";

    	 	$stmt = $dbcon->prepare($updQuery1);
		$stmt->bind_param('siississssiss', $carouselImg, intval($pcbody), intval($pctop), implode(",", $topx), implode(",", $topy),  intval($pcbot), implode(",", $bottomx), implode(",", $bottomy), implode(",", $pccolors),  implode(",", $pcdesign), $material, $tags, $admintags);

		if(!$stmt->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		$stmt->close();


		$qry = "DELETE  from pieceimages WHERE pieceid=?";
		$stmt1 = $dbcon->prepare($qry);
		$stmt1->bind_param('i', $pieceid);
		$stmt1->execute();
		$stmt1->close();

		$ins_stmt1 = $dbcon->prepare("INSERT INTO `pieceimages` (`pieceid`, `color`, `design`, `imagefile`) VALUES (?,?,?,?)");

		foreach ($cartesianArr as $findex =>$prod) {
			$ins_stmt1->bind_param('isss', $pieceid, $prod['color'], $prod['design'], $prod['imagefile']);

			if(!$ins_stmt1->execute()){
			    die('Image Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
			}
		}
		$ins_stmt1->close();
    	}
    }
    else {
    	echo "Error = ".$error;
 	   exit();
	}
}

?>