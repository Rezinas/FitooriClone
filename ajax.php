<?php
require_once($_SERVER['DOCUMENT_ROOT']."/plumms/utils/functions.php");

if(isset($_GET["addcustom"])) {
    if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
      $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
      // var_dump($_POST["custom_product"]);

      if(!empty($_POST["custom_product"])){
        $prd_qry  = "insert into products () VALUES ()";

        $ins_stmt = $dbcon->prepare($prd_qry);
        if(!$ins_stmt) {
         die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        if($ins_stmt->execute()){
              $prodid=$ins_stmt->insert_id;
          }else{
            die('Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
        }
        $ins_stmt->close();

        $elements = $_POST["custom_product"];
        $currUserEmail =getCurrentUserEmail();
        $currUsertype="";

        $qry = "SELECT usertype  from user WHERE email=?";
        $result=mysqli_query($dbcon,$qry);
       if ($result && mysqli_num_rows($result) > 0)
        {
            while ($row=mysqli_fetch_row($result))
            {
              $currUsertype = $row[0];
            }
              mysqli_free_result($result);
        }


        $elem_qry = "INSERT into customdesign (`productid`, `elementid`,`leftPos`, `topPos`, `selectedImage`, `addedBy`, `addedByType` ) VALUES (?,?,?,?,?,?,?)";
        $ins_stmt1 = $dbcon->prepare($elem_qry);

        if(!$ins_stmt1) {
         die('Prepare Error : ('. $dbcon->errno .') '. $dbcon->error);
        }

        foreach($elements as $elem){
        //  var_dump($elem);
          $ins_stmt1->bind_param('iiiisss', $prodid, $elem['id'], $elem['leftPos'], $elem['topPos'], $elem['selectedImage'],$currUserEmail, $currUsertype);

          if(!$ins_stmt1->execute()){
              die('Image Insert Error : ('. $dbcon->errno .') '. $dbcon->error);
          }
        }
        $ins_stmt1->close();
        echo "SUCCESS";
      }
      else echo "ERROR";
      exit();
  }
}

?>