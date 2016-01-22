<?php
$itemsArr= explode("|", PRD_ITEM) ;
$colorsArr= explode("|", COLORS) ;
$texturesArr= explode("|", TEXTURES) ;


//SELECT * FROM `pieces`

 	 	//$qry =  "SELECT pieces.*, pieceimages.* FROM `pieces` join pieceimages on  pieces.id=pieceimages.pieceid";
		$qry = "SELECT * from pieces";

    	 	$stmt = $dbcon->prepare($qry);
		if(!$stmt->execute()){
		    die('Error : ('. $dbcon->errno .') '. $dbcon->error);
		}
		if (!($res = $stmt->get_result()))
			die('Error : ('. $dbcon->errno .') '. $dbcon->error);


		// Extract result set and loop rows
		while ($row = $res->fetch_assoc())
		{
			$qry2 = "SELECT * from pieceimages where pieceid = ".$row['id'];
			$stmt1 = $dbcon->prepare($qry2);
			if(!$stmt1->execute()){
				die('Error : ('. $dbcon->errno .') '. $dbcon->error);
			}
			if (!($res1 = $stmt1->get_result()))
				die('Error : ('. $dbcon->errno .') '. $dbcon->error);
			$images=[];
			while($row1 = $res1->fetch_assoc()){
				array_push($images, $row1);
			}
			$stmt1->close();
		  	$row["images"] = $images;
		    $elements[] = $row;
		}
		$stmt->close();

$jsondata = array(
	"items" => $itemsArr,
	"colorsArr" => $colorsArr,
	"textures" => $texturesArr,
	"elements" =>$elements
);

// var_dump($elements);
?>
<script type="text/javascript">
var model = <?php echo json_encode($jsondata) ?>;
</script>