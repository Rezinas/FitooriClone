
   //update pieces table for quantity
        $pqry = "UPDATE pieces set quantity = quantity-2 where id=?";
        $upd_stmt = $dbcon->prepare($pqry);
        if(!$upd_stmt) {
          die('Update Error : ('. $dbcon->errno .') '. $dbcon->error);
        }

foreach($elements as $elem){
          $upd_stmt->bind_param('i', $elem['id']);
          if(!$upd_stmt->execute()){
              die('Update pieces Error : ('. $dbcon->errno .') '. $dbcon->error);
          }

          // if $elem.quantity -2 < 2 then send email from here
          if(($elem['quantity'] -2 ) <= 2) {
             $messageDetail = "The element low on quantity is ID: ".$elem['id']." Image:  <img src='http://fitoori.com/productImages/".$elem['selectedImage']."' />";
           // $result =  sendemail('rezinas@gmail.com', "Pieces low on Quantity",  $messageDetail);
          }

        }

        $upd_stmt->close();