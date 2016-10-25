<?php
$materialArr= explode("|", CATEGORY) ;
$colorsArr= explode("|", COLORS) ;
$sourcesArr= explode("|", SOURCES) ;
$allcomponents=[];

$qry = "SELECT compid, compimg, stock, costpercomp, material, color, source, cutoff, warning FROM components";
$stmt = $dbcon->prepare($qry);
if(!$stmt->execute()){
    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
}

$stmt->store_result();
$stmt->bind_result($a,$b, $c, $d, $e, $f, $g, $h, $i);
while ($stmt->fetch()) {
    $allcomponents[] = ['compid' => $a, 'compimg' => $b, 'stock' => $c, 'costpercomp' => $d, 'material' => $e, 'color' => $f,'source' => $g,'cutoff' => $h,'warning' => $i];
}
$stmt->close();

$jsondata = array(
	"isAgent" => isAgent(),
    "materials" => $materialArr,
	"colors" => $colorsArr,
	"components" => $allcomponents,
	"sources" => $sourcesArr
);
?>
<script type="text/javascript">
var model = <?php echo json_encode($jsondata) ?>;
</script>
