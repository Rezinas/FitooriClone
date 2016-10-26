<?php
$categoriesArr= explode("|", CATEGORY) ;
$itemsArr= explode("|", PRD_ITEM) ;


$allproducts=[];

$qry = "SELECT  `productid`,`name`, `price`, `bodypart`, `material`, `mainimg`, `alt1img`, `alt2img`, `status`, `shortdesc`, `detaildesc`, `addinfo`, `featured`,  `addedUsertype`, `addedbyUserEmail`, `quantity`, `size`,`tags`, UNIX_TIMESTAMP(`dateAdded`) as dateAdded, `customized`, `designerPick`, `style` from products where status = 1";

if(!$stmt = $dbcon->prepare($qry)){
die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
}

if(!$stmt->execute()){
die('Error : ('. $dbcon->errno .') '. $dbcon->error);
}

$stmt->store_result();
$stmt->bind_result( $a1, $a,$b, $c, $d, $i1, $i2, $i3, $e, $f, $g, $h, $i, $k, $l, $m, $n, $o, $p, $q, $r, $s);
while ($stmt->fetch()) {
if(is_null($a) && is_null($b)) continue;
$allproducts[] = [ 'productid' => $a1, 'name' => $a, 'price' => $b,  'bodypart' => $c, 'material' => $d, 'mainimg' => $i1, 'alt1img' => $i2, 'alt2img' => $i3, 'status' => $e, 'shortdesc' => $f, 'detaildesc' => $g, 'addinfo' => $h, 'featured' => $i, 'addedUsertype' => $k, 'addedbyUserEmail' => $l, 'quantity' => $m,'size' => $n, 'tags' => ucwords($o), 'dateAdded' => $p,  'customized' => $q, 'designerPick' => $r, 'style' => $s];
}
$stmt->close();

$tags=[];
$qry = "SELECT DISTINCT tags from products";
$stmt = $dbcon->prepare($qry);
if(!$stmt->execute()){
    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
}

$stmt->store_result();
$stmt->bind_result($a);
while ($stmt->fetch()) {
	if($a == "") continue;
   if(strpos($a, ",") !== false) {
		$a_parts = explode(",", $a);
		foreach($a_parts as $ap){
			$tags[]=trim(ucwords($ap));
		}
	}
	else {
    	$tags[] = trim(ucwords($a));
	}
}
$stmt->close();

$jsondata = array(
	"siteUrl" => SITE_URL,
	"items" => $itemsArr,
	"styles" => array('jhumka', 'chandelier', 'dangler', 'stud', 'hoop'),
	"isAgent" => isAgent(),
	"materials" => $categoriesArr,
	"products" =>$allproducts,
	"tags" => $tags
);

// var_dump($allproducts);
?>
<script type="text/javascript">
var model = <?php echo json_encode($jsondata) ?>;
</script>

