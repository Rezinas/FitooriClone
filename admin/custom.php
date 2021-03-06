<?php
$itemsArr= explode("|", PRD_ITEM) ;
$categoriesArr= explode("|", CATEGORY) ;
$colorsArr= explode("|", COLORS) ;
$styleArr= explode("|", STYLES) ;
$texturesArr= explode("|", TEXTURES) ;

$design_qry = "SELECT customdesign.productid,GROUP_CONCAT(elementid SEPARATOR ',') as elementids,GROUP_CONCAT(leftPos SEPARATOR ',') as leftpositions,GROUP_CONCAT(topPos SEPARATOR ',') as toppositions,GROUP_CONCAT(selectedImage SEPARATOR ',') as images, addedBy, addedByType, isProduct, products.mainimg from customdesign, products  where  products.productid = customdesign.productid group by productid";

	$stmt = $dbcon->prepare($design_qry);
	if(!$stmt->execute()){
	    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
	}
	$stmt->store_result();
	$stmt->bind_result($a,$b, $c, $d, $e, $f, $f1, $g, $h);
	$designs =[];
	while ($stmt->fetch()) {
		$designs[] = ['productid' => $a, 'elementids' => explode(",",$b), 'leftpositions' => explode(",",$c), 'toppositions' => explode(",",$d), 'images' => explode(",",$e), 'addedBy' => $f, 'addedByType' => $f1, 'isProduct'=>$g, 'mainimg' => $h];
	}
	$stmt->close();

?>
