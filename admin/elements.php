<?php
$categoriesArr= explode("|", CATEGORY) ;
$itemsArr= explode("|", PRD_ITEM) ;
$allpieces=[];

$qry = "SELECT id, carouselImg, price, toppoints, bottompoints, material, bodypart, style, admintags, quantity, name from pieces";
$stmt = $dbcon->prepare($qry);
if(!$stmt->execute()){
    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
}

$stmt->store_result();
$stmt->bind_result($a,$b, $c, $d, $e, $f, $g, $h, $i, $j, $k);
while ($stmt->fetch()) {
    $allpieces[] = ['id' => $a, 'carouselImg' => $b, 'price' => $c, 'toppoints' => $d,'bottompoints' => $e, 'material' => $f,'bodypart' => $g,'style' => $h,'admintags' => ucwords($i), 'quantity' => $j, 'name' =>$k];
}
$stmt->close();

$tags=[];
$qry = "SELECT DISTINCT admintags from pieces";
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
			$tags[]=ucwords($ap);
		}
	}
	else {
    	$tags[] = ucwords($a);
	}
}
$stmt->close();


$jsondata = array(
	"items" => $itemsArr,
	"isAgent" => isAgent(),
    "materials" => $categoriesArr,
	"styles" => array("hook", "hoop", "jhumka", "chandelier", "dangler", "stud"),
	"pieces" =>$allpieces,
	"tags" => $tags
);
?>
<script type="text/javascript">
var model = <?php echo json_encode($jsondata) ?>;
</script>
