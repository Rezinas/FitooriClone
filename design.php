<?php
$itemsArr= explode("|", PRD_ITEM) ;
$categoriesArr= explode("|", CATEGORY) ;
$colorsArr= explode("|", COLORS) ;
$styleArr= explode("|", STYLES) ;
$texturesArr= explode("|", TEXTURES) ;
$elements =[];

$qry = "SELECT id, carouselImg,  imgheight, imgwidth, bodypart, centerx, centery,  toppoints, topX, topY, bottompoints, botX, botY, color, texture, style, admintags, material from pieces";

 	$stmt = $dbcon->prepare($qry);
if(!$stmt->execute()){
    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
}
$stmt->store_result();
$stmt->bind_result($a,$b,$bh, $bw, $c,  $cx, $cy, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n);
while ($stmt->fetch()) {
	$row=[];
	$row = ['id' => $a, 'carouselImg' => $b, 'imgheight'=>  $bh, 'imgwidth'=>  $bw, 'bodypart' => $c,  'centerx' => $cx, 'centery' => $cy, 'toppoints' => $d, 'topX' => $e, 'topY' => $f, 'bottompoints' => $g, 'botX' => $h, 'botY' => $i, 'color' => $j, 'texture' => $k, 'style' => $l, 'admintags' => $m, 'material' => $n];

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
	"items" => $itemsArr,
	"materials" => $categoriesArr,
	"colorsArr" => $colorsArr,
	"elements" =>$elements
);

// var_dump($elements);
?>
<script type="text/javascript">
var model = <?php echo json_encode($jsondata) ?>;
</script>