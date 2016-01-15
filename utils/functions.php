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


/* global variables */


/*
 * image utility function used while uploading images.
*/

 function recreateImage($ext, $nw, $nh, $filename, $srcw, $srch, $destfile)
 {

	if($ext ==".jpg"){
		$srcfile = imagecreatefromjpeg($filename);
	}
	else if($ext == ".gif"){
		$srcfile = imagecreatefromgif($filename);
	}
	else if($ext == ".png"){
		$srcfile = imagecreatefrompng($filename);
	}

	if($srcfile ===false) {
		echo "imagecreate failed";
		return false;
	}

	$source_aspect_ratio = $srcw / $srch;

    $original_aspect_ratio = ORIGINAL_IMAGE_MAX_WIDTH / ORIGINAL_IMAGE_MAX_HEIGHT;
    if ($srcw <= ORIGINAL_IMAGE_MAX_WIDTH && $srch <= ORIGINAL_IMAGE_MAX_HEIGHT) {
        $original_image_width = $srcw;
        $original_image_height = $srch;
    } elseif ($original_aspect_ratio > $source_aspect_ratio) {
        $original_image_width = (int) (ORIGINAL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
        $original_image_height = ORIGINAL_IMAGE_MAX_HEIGHT;
    } else {
        $original_image_width = ORIGINAL_IMAGE_MAX_WIDTH;
        $original_image_height = (int) (ORIGINAL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
    }

    // $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH / THUMBNAIL_IMAGE_MAX_HEIGHT;
    // if ($srcw <= THUMBNAIL_IMAGE_MAX_WIDTH && $srch <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
    //     $thumbnail_image_width = $srcw;
    //     $thumbnail_image_height = $srch;
    // } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
    //     $thumbnail_image_width = (int) (THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
    //     $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
    // } else {
    //     $thumbnail_image_width = THUMBNAIL_IMAGE_MAX_WIDTH;
    //     $thumbnail_image_height = (int) (THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
    // }


    // $tooltip_aspect_ratio = TOOLTIP_IMAGE_MAX_WIDTH / TOOLTIP_IMAGE_MAX_HEIGHT;
    // if ($srcw <= TOOLTIP_IMAGE_MAX_WIDTH && $srch <= TOOLTIP_IMAGE_MAX_HEIGHT) {
    //     $tooltip_image_width = $srcw;
    //     $tooltip_image_height = $srch;
    // } elseif ($tooltip_aspect_ratio > $source_aspect_ratio) {
    //     $tooltip_image_width = (int) (TOOLTIP_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
    //     $tooltip_image_height = TOOLTIP_IMAGE_MAX_HEIGHT;
    // } else {
    //     $tooltip_image_width = TOOLTIP_IMAGE_MAX_WIDTH;
    //     $tooltip_image_height = (int) (TOOLTIP_IMAGE_MAX_WIDTH / $source_aspect_ratio);
    // }

   $panel_aspect_ratio = PANEL_IMAGE_MAX_WIDTH / PANEL_IMAGE_MAX_HEIGHT;
    if ($srcw <= PANEL_IMAGE_MAX_WIDTH && $srch <= PANEL_IMAGE_MAX_HEIGHT) {
        $panel_image_width = $srcw;
        $panel_image_height = $srch;
    } elseif ($panel_aspect_ratio > $source_aspect_ratio) {
        $panel_image_width = (int) (PANEL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
        $panel_image_height = PANEL_IMAGE_MAX_HEIGHT;
    } else {
        $panel_image_width = PANEL_IMAGE_MAX_WIDTH;
        $panel_image_height = (int) (PANEL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
    }


	$tmp_original=imagecreatetruecolor($original_image_width,$original_image_height);
	imagecopyresampled($tmp_original,$srcfile,0,0,0,0,$original_image_width,$original_image_height,$srcw,$srch);

	// $tmp_tooltip=imagecreatetruecolor($tooltip_image_width,$tooltip_image_height);
	// imagecopyresampled($tmp_tooltip,$srcfile,0,0,0,0,$tooltip_image_width,$tooltip_image_height,$srcw,$srch);

	// $tmp_thumb=imagecreatetruecolor($thumbnail_image_width,$thumbnail_image_height);
	// imagecopyresampled($tmp_thumb,$srcfile,0,0,0,0,$thumbnail_image_width,$thumbnail_image_height,$srcw,$srch);


    $tmp_panel=imagecreatetruecolor($panel_image_width,$panel_image_height);
    imagecopyresampled($tmp_panel,$srcfile,0,0,0,0,$panel_image_width,$panel_image_height,$srcw,$srch);





	if($ext ==".jpg"){
		imagejpeg($tmp_original, "pictures/".$destfile, 100);
		imagejpeg($tmp_tooltip, "pictures/tooltip_".$destfile, 100);
        imagejpeg($tmp_thumb, "pictures/thumb_".$destfile, 100);
		imagejpeg($tmp_panel, "pictures/panel_".$destfile, 100);
	}
	else if($ext == ".gif"){
		imagegif($tmp_original, "pictures/".$destfile);
		imagegif($tmp_tooltip, "pictures/tooltip_".$destfile);
        imagegif($tmp_thumb, "pictures/thumb_".$destfile);
		imagegif($tmp_panel, "pictures/panel_".$destfile);
	}
	else if($ext == ".png"){
		imagepng($tmp_original, "pictures/".$destfile, 9);
		imagepng($tmp_tooltip, "pictures/tooltip_".$destfile, 9);
        imagepng($tmp_thumb, "pictures/thumb_".$destfile, 9);
		imagepng($tmp_panel, "pictures/panel_".$destfile, 9);
	}


	imagedestroy($srcfile);
    imagedestroy($tmp_original);
    imagedestroy($tmp_tooltip);
    imagedestroy($tmp_thumb);
    imagedestroy($tmp_panel);

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


function prepare_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function uploadPrdImage($origFile, $filename, $filerror ){
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

?>