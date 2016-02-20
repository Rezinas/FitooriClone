<?php
	session_start();
	require_once($_SERVER['DOCUMENT_ROOT']."/plumms/utils/constants.php");
	require_once(SITE_ROOT."/utils/db_connection.php");

	//check for logged in user
	if(!isset($_SESSION['username'])) {
		$username="";
	}
	else{
		$username = ($_SESSION['username']);
	}


/* file upload related functions */
function GetImageExtension($imagetype)
    {
        if(empty($imagetype)) return false;
        switch($imagetype)
        {
            case 'image/bmp': return '.bmp';
            case 'image/gif': return '.gif';
            case 'image/jpeg': return '.jpg';
            case 'image/png': return '.png';
            default: return false;
        }
}


function cartesian($input) {
    // filter out empty values
    $input = array_filter($input);

    $result = array(array());

    foreach ($input as $key => $values) {
        $append = array();

        foreach($result as $product) {
            foreach($values as $item) {
                if($key == 0)
                $product['color'] = $item;
                if($key == 1)
                $product['design'] = $item;
                $append[] = $product;
            }
        }

        $result = $append;
    }

    return $result;
}


function isAgent() {
    if(isset($_SESSION['agentId'])) {
        return true;
    }
    else {
        //will have to check if user is logged in or guest here.
        return false;
    }
}
function getCurrentUserID() {
    if(isset($_SESSION['agentId'])) {
        return $_SESSION['agentId'];
    }
    else {
          //have to get user id or guest id here from session
    }
}



function getCurrentUserEmail() {
   if(isset($_SESSION['useremail'])) {
        return $_SESSION['useremail'];
    } else  return null;
}


function prepare_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


function uploadPrdImage($origFile, $filename, $filerror){

if($filename == "") return "ERROR";
  $path_parts = pathinfo($filename);
 $filename = $path_parts['filename'].'_'.time().'.'.$path_parts['extension'];

    $destFile = "../".PRDIMGDIR."/".$filename;
    if ( move_uploaded_file ($origFile, $destFile) ){
        return  $filename;
    }
    else      {
        $file_err = '';
        switch ($filerror)
         {  case 1:
                   $file_err .= 'The file is bigger than this PHP installation allows';
                   break;
            case 2:
                   $file_err .= 'The file is bigger than this form allows';
                   break;
            case 3:
                   $file_err .= 'Only part of the file was uploaded';
                   break;
            case 4:
                   $file_err .= 'No file was uploaded';
                   break;
         }
        return "ERROR =".$file_err;
       }
}

function showCustomDesign($config) {
  $htmlStr = "";
  return  $htmlStr;
}

?>