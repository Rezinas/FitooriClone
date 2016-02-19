<?php
$categoriesArr= explode("|", CATEGORY) ;
$itemsArr= explode("|", PRD_ITEM) ;


$allproducts=[];

$qry = "SELECT  `productid`,`name`, `price`, `bodypart`, `material`, `mainimg`, `alt1img`, `alt2img`, `promotedimg`,`featuredimg`, `status`, `shortdesc`, `detaildesc`, `addinfo`, `featured`, `promoted`, `addedUsertype`, `addedbyUserEmail`, `quantity`  from products";
if(!$stmt = $dbcon->prepare($qry)){
die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
}

if(!$stmt->execute()){
die('Error : ('. $dbcon->errno .') '. $dbcon->error);
}

$stmt->store_result();
$stmt->bind_result( $a1, $a,$b, $c, $d, $i1, $i2, $i3, $i4, $i5, $e, $f, $g, $h, $i, $j, $k, $l, $m);
while ($stmt->fetch()) {
if(is_null($a) && is_null($b)) continue;
$allproducts[] = [ 'productid' => $a1, 'name' => $a, 'price' => $b,  'bodypart' => $c, 'material' => $d, 'mainimg' => $i1, 'alt1img' => $i2, 'alt2img' => $i3, 'promotedimg' => $i4, 'featuredimg' => $i5, 'status' => $e, 'shortdesc' => $f, 'detaildesc' => $g, 'addinfo' => $h, 'featured' => $i, 'promoted' => $j, 'addedUsertype' => $k, 'addedbyUserEmail' => $l, 'quantity' => $m];
}
$stmt->close();

$jsondata = array(
	"items" => $itemsArr,
	"isAgent" => isAgent(),
	"materials" => $categoriesArr,
	"products" =>$allproducts
);

// var_dump($elements);
?>
<script type="text/javascript">
var model = <?php echo json_encode($jsondata) ?>;
</script>

