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
$pcenterx ="";
$pcentery ="";
$pctop = "";
$pcbot = "";
$pcbody = "";
$pccolors=[];
$pcdesign = [];
$style_list = [];
$pieceid= "";
$pimgheight ="";
$pimgwidth ="";
$pprice ="";
$pname ="";
// $pquantity ="";
$priority ="";
$cartesianArr= [];
$topx=[];
$topy=[];
$bottomx=[];
$bottomy=[];
$carouselImg="";
$hookImg="";
$material = "";
$admintags="";
$availability="";
$complist ="";
$compquantity ="";

if(isset($_GET["pieces"]) && isset($_GET["id"]) ) {
    $pieceid=trim($_GET["id"]);
    $pcmode = "edit";
 	$qry = "SELECT  id, carouselImg, priority, imgheight, imgwidth, bodypart, centerx, centery, toppoints, topX, topY, bottompoints, botX, botY, color, texture, style, admintags, material, price, name, hookImg, availability, complist, compquantity  from pieces WHERE id=$pieceid";
 	if(!$stmt = $dbcon->prepare($qry)){
	    die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	if(!$stmt->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}

	$stmt->store_result();
	$stmt->bind_result($a,$b, $pr, $bh, $bw, $c, $cx, $cy,  $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $r, $av, $cl, $cq);
	while ($stmt->fetch()) {
		$parr = ['id' => $a, 'carouselImg' => $b, 'priority' => $pr,'imgheight'=>  $bh, 'imgwidth'=>  $bw,  'bodypart' => $c, 'centerx' => $cx, 'centery' => $cy, 'toppoints' => $d, 'topX' => $e, 'topY' => $f, 'bottompoints' => $g, 'botX' => $h, 'botY' => $i, 'color' => $j, 'texture' => $k, 'style' => $l, 'admintags' => $m, 'material' => $n, 'price' => $o, 'name' => $p, 'hookImg' => $r, 'availability' => $av, 'complist' => $cl, 'compquantity' => $cq];
	}
	$stmt->close();
	$pieceid=$parr['id']."";
	$pcbody=$parr['bodypart'];
	$pcenterx=$parr['centerx']."";
	$pcentery=$parr['centery']."";
	$pctop=$parr['toppoints']."";
	$pcbot=$parr['bottompoints']."";
	$pimgheight=$parr['imgheight']."";
	$pimgwidth=$parr['imgwidth']."";
	$carouselImg=$parr['carouselImg']."";
	$hookImg=$parr['hookImg']."";
	$material=$parr['material'];
	$style_list=explode(',', $parr['style']);
	$pprice=$parr['price'];
	$pname=$parr['name'];
	// $pquantity=$parr['quantity'];
	$priority=$parr['priority'];
	$admintags=$parr['admintags'];
	$topx = explode(",", $parr['topX']);
	$topy = explode(",", $parr['topY']);
	$bottomx = explode(",", $parr['botX']);
	$bottomy = explode(",", $parr['botY']);
	$pccolors = explode(",", $parr['color']);
	$pcdesign = explode(",", $parr['texture']);
	$cartesianArr = fetchImages($pieceid, $dbcon);
	$availability=$parr['availability'];
	$complist= $parr['complist'];
	$compquantity= $parr['compquantity'];
}

if (!empty($_POST)) {
//echo "<pre>";
// var_dump($_POST);
// echo "</pre>";
	$error = "";

	  $pcmode  = prepare_input($_POST['pcmode' ]);
	  $pieceid  = prepare_input($_POST['pieceid' ]);

	  if(!empty($pieceid)) {
	  	$carouselImg = prepare_input($_POST['carouselImgname']);
	  	$hookImg = prepare_input($_POST['hookImgName']);
		$cartesianArr = fetchImages($pieceid, $dbcon);


// echo "cartesianArr"; echo "<pre>";
// var_dump($cartesianArr);
//  echo "</pre>";
	  }

	  $pcenterx  = prepare_input($_POST['pcenterx' ]);
	  $pcentery  = prepare_input($_POST['pcentery' ]);
	  $pctop  = prepare_input($_POST['pctop' ]);
	  $pcbody  = prepare_input($_POST['pcbody' ]);
	  $pcbot  = prepare_input($_POST['pcbot' ]);
	  $pprice  = prepare_input($_POST['pprice' ]);
	  $pname  = prepare_input($_POST['pname' ]);
	  $priority  = prepare_input($_POST['priority' ]);
	  $pimgheight  = prepare_input($_POST['pimgheight' ]);
	  $pimgwidth  = prepare_input($_POST['pimgwidth' ]);
	  $complist  = prepare_input($_POST['complist' ]);
	  $compquantity  = prepare_input($_POST['compquantity' ]);
	  if(isset($_POST['style_list'])) {
	  	$style_list = $_POST['style_list'];
	  }
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
	  // $pstyle = prepare_input($_POST['pstyle' ]);
	  $pstyle = "1";
	  $admintags = prepare_input($_POST['admintags' ]);

	if($_FILES['carouselImg']['error'] == 0) {
		$carouselImg = uploadPrdImage($_FILES['carouselImg'] ['tmp_name'], $_FILES['carouselImg'] ['name'], $_FILES['carouselImg'] ['error']);
		if(strpos($carouselImg,'ERROR') !== false) { $error .= "Carousel Image upload error"; }

	}


	if($_FILES['hookImg']['error'] == 0) {
		$hookImg = uploadPrdImage($_FILES['hookImg'] ['tmp_name'], $_FILES['hookImg'] ['name'], $_FILES['hookImg'] ['error']);
		if(strpos($hookImg,'ERROR') !== false) { $error .= "hook Image upload error"; }

	}

//TBD: Validations



	  if( (count($pccolors) > 0 && count($pcdesign)> 0) ) {
	  	$newcartesianArr = cartesian(array($pccolors, $pcdesign));
	  	$cartesianArr =compareArr($cartesianArr, $newcartesianArr);

//echo"<pre>"; var_dump($pccolors); var_dump($pcdesign); var_dump($cartesianArr);   echo "</pre>";

	  	foreach($cartesianArr as $ind => &$product) {

                //   echo "<pre>"; echo $_FILES[$filename]['error']; echo "</pre>";
		  	$filename = $product['color']."_".$product['design'];
		  	if($_FILES[$filename]['error'] == 0) {
		  		$newfile = uploadPrdImage($_FILES[$filename] ['tmp_name'], $_FILES[$filename] ['name'], $_FILES[$filename] ['error']);
				if(strpos($newfile,'ERROR') !== false) { $error .= "here options Image upload error"; }
			  	else  { $product['imagefile'] = $newfile; }
		  	}
	  	}
	  }

    if (empty($error))
    {
    	if($pcmode == "new") {
    	//insert into database all product values


    $query = "INSERT INTO `pieces` (`carouselImg`,`imgheight`, `imgwidth`,`bodypart`, `centerx`, `centery`, `toppoints`,  `topX`, `topY`,`bottompoints`,  `botX`, `botY`,`color`, `texture`,`material`,`style`, `admintags`, `price`, `name`, `priority`, `hookImg`, `complist`, `compquantity`) VALUES (?, ?, ?, ?, ?, ?,?,?, ?,?, ?, ?,?,?,?,?,?, ?,?,?,?, ?,?)";
	$ins_stmt = $dbcon->prepare($query);
	if(!$ins_stmt) {
	 die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error. ' query= '.$query);
	}
	// bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
	$ins_stmt->bind_param('siiiiiississssissdsisss', $carouselImg, intval($pimgheight), intval($pimgwidth), intval($pcbody),  intval($pcenterx),  intval($pcentery), intval($pctop), implode(",", $topx), implode(",", $topy),  intval($pcbot), implode(",", $bottomx), implode(",", $bottomy), implode(",", $pccolors),  implode(",", $pcdesign), $material, implode(",", $style_list), $admintags, $pprice, $pname, $priority, $hookImg, $complist, $compquantity);
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
    	 	$updQuery1 =  "UPDATE pieces  SET `carouselImg`=?, `imgheight` =?, `imgwidth` =?, `bodypart` = ?, `centerx` = ?, `centery` = ?, `toppoints` = ?,  `topX`= ?, `topY`=?,`bottompoints` = ?, `botX`=?, `botY`=?, `color` = ?, `texture` = ? , `material` = ? , `style` = ?, `admintags` = ?, `price` = ?, `name` = ?, `priority` = ?, `hookImg`=?, `complist`=?, `compquantity`=? WHERE id=$pieceid ";
    	 	$stmt = $dbcon->prepare($updQuery1);

    	 $stmt->bind_param('siiiiiississssissdsisss', $carouselImg, intval($pimgheight), intval($pimgwidth), intval($pcbody), intval($pcenterx), intval($pcentery), intval($pctop), implode(",", $topx), implode(",", $topy),  intval($pcbot), implode(",", $bottomx), implode(",", $bottomy), implode(",", $pccolors),  implode(",", $pcdesign), $material, implode(",", $style_list), $admintags, $pprice, $pname, $priority, $hookImg, $complist, $compquantity);

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