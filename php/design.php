<?php
$itemsArr= explode("|", PRD_ITEM) ;
$categoriesArr= explode("|", CATEGORY) ;
$colorsArr= explode("|", COLORS) ;
$styleArr= explode("|", STYLES) ;
$texturesArr= explode("|", TEXTURES) ;
$elements =[];
$showHelp = 1
;if(!isset($_SESSION['showhelp'])){
	 $_SESSION['showhelp'] = 1;
	 $showHelp = 0;
}

$startStyle = 'jhumka';
if(isset($_REQUEST["designearrings"]) && $_REQUEST["designearrings"] != '') {
	$startStyle = $_REQUEST["designearrings"];
}
if(isset($_REQUEST["designearrings1"]) && $_REQUEST["designearrings1"] != '') {
	$startStyle = $_REQUEST["designearrings1"];
}
$_SESSION["startStyle"] = $startStyle;


//get only earring parts and filter by the startStyle.
$qry = "SELECT id, carouselImg,  imgheight, imgwidth, bodypart, centerx, centery,  toppoints, topX, topY, bottompoints, botX, botY, color, texture, style, admintags, material, price, name, priority, hookImg, availability, complist, compquantity from pieces where bodypart=3 and find_in_set('$startStyle', style) <> 0 order by priority desc";


 	$stmt = $dbcon->prepare($qry);
if(!$stmt->execute()){
    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
}
$stmt->store_result();
$stmt->bind_result($a,$b,$bh, $bw, $c,  $cx, $cy, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $s, $t, $u, $v, $w);

while ($stmt->fetch()) {
	$row=[];
	$row = ['id' => $a, 'carouselImg' => $b, 'imgheight'=>  $bh, 'imgwidth'=>  $bw, 'bodypart' => $c,  'centerx' => $cx, 'centery' => $cy, 'toppoints' => $d, 'topX' => $e, 'topY' => $f, 'bottompoints' => $g, 'botX' => $h, 'botY' => $i, 'color' => $j, 'texture' => $k, 'style' => $l, 'admintags' => $m, 'material' => $n, 'price' => $o, 'name' => $p, 'priority' => $s, 'hookImg' => $t, 'availability' => $u, 'complist' => $v, 'compquantity' => $w];

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
	$elements[] = $row;
}
$stmt->close();

$jsondata = array(
	"siteUrl"  => SITE_URL,
	"isAgent" => isAgent(),
	"startStyle" => $startStyle,
	"materials" => $categoriesArr,
	"styles" => array('hook', 'jhumka', 'chandelier', 'dangler', 'stud', 'hoop'),
	"colorsArr" => $colorsArr,
	"elements" =>$elements,
	"overheads" => OVERHEADS,
	"vat" => TAXPERCENT,
	"margin" => PROFITPERCENT,
	"showHelp" => $showHelp,
	"shipping" => [SHIPPINGCHARGES_SMALL, SHIPPINGCHARGES_MEDIUM, SHIPPINGCHARGES_LARGE]
);

// var_dump($elements);
?>
<script type="text/javascript">
var model = <?php echo json_encode($jsondata) ?>;
</script>