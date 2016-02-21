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

// //get all designerPick products
//     $qry = "SELECT  `name`, `price`, `bodypart`, `material`, `mainimg`, `alt1img`, `alt2img`, `promotedimg`,`featuredimg`, `status`, `shortdesc`, `detaildesc`, `addinfo`, `featured`, `promoted`, `addedUsertype`, `addedbyUserEmail`, `quantity`,  `tags`,  `size`, `designerPick` from products WHERE productid=$pid";
//  	if(!$stmt = $dbcon->prepare($qry)){
// 	    die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
// 	}

// 	if(!$stmt->execute()){
// 	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
// 	}

// 	$stmt->store_result();
// 	$stmt->bind_result($a,$b, $c, $d, $i1, $i2, $i3, $i4, $i5, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p);
// 	while ($stmt->fetch()) {
// 		$parr = ['name' => $a, 'price' => $b,  'bodypart' => $c, 'material' => $d, 'mainimg' => $i1, 'alt1img' => $i2, 'alt2img' => $i3, 'promotedimg' => $i4, 'featuredimg' => $i5, 'status' => $e, 'shortdesc' => $f, 'detaildesc' => $g, 'addinfo' => $h, 'featured' => $i, 'promoted' => $j, 'addedUsertype' => $k, 'addedbyUserEmail' => $l, 'quantity' => $m, 'tags' => $n, 'size' => $o, 'designerPick' => $p];
// 	}
// 	$stmt->close();


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