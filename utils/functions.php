<?php
	session_start();
	require_once($_SERVER['DOCUMENT_ROOT']."/utils/constants.php");
    require_once(SITE_ROOT."/utils/db_connection.php");
	require_once(SITE_ROOT."/phpmailer/PHPMailerAutoload.php");

	//check for logged in user
	if(!isset($_SESSION['username'])) {
		$username="";
	}
	else{
		$username = ($_SESSION['username']);
	}

function money_format($format, $number)
{
    $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
              '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
    if (setlocale(LC_MONETARY, 0) == 'C') {
        setlocale(LC_MONETARY, '');
    }
    $locale = localeconv();
    preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
    foreach ($matches as $fmatch) {
        $value = floatval($number);
        $flags = array(
            'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
                           $match[1] : ' ',
            'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
            'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
                           $match[0] : '+',
            'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
            'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
        );
        $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
        $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
        $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
        $conversion = $fmatch[5];

        $positive = true;
        if ($value < 0) {
            $positive = false;
            $value  *= -1;
        }
        $letter = $positive ? 'p' : 'n';

        $prefix = $suffix = $cprefix = $csuffix = $signal = '';

        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
        switch (true) {
            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                $prefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                $suffix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                $cprefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                $csuffix = $signal;
                break;
            case $flags['usesignal'] == '(':
            case $locale["{$letter}_sign_posn"] == 0:
                $prefix = '(';
                $suffix = ')';
                break;
        }
        if (!$flags['nosimbol']) {
            $currency = $cprefix .
                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                        $csuffix;
        } else {
            $currency = '';
        }
        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';

        $value = number_format($value, $right, $locale['mon_decimal_point'],
                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
        $value = @explode($locale['mon_decimal_point'], $value);

        $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
        if ($left > 0 && $left > $n) {
            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
        }
        $value = implode($locale['mon_decimal_point'], $value);
        if ($locale["{$letter}_cs_precedes"]) {
            $value = $prefix . $currency . $space . $value . $suffix;
        } else {
            $value = $prefix . $value . $space . $currency . $suffix;
        }
        if ($width > 0) {
            $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                     STR_PAD_RIGHT : STR_PAD_LEFT);
        }

        $format = str_replace($fmatch[0], $value, $format);
    }
    return $format;
}



/** sending emails **/

function sendemail($toemail, $subject, $message) {
    $mail = new PHPMailer;

$mail->IsSMTP();

// $mail->SMTPDebug = 2;
//Ask for HTML-friendly debug output
// $mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "bh-42.webhostbox.net";

//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;

//Whether to use SMTP authentication
$mail->SMTPAuth = true;



$mail->Username = "team@fitoori.com";
$mail->Password = "admin123";

    $mail->setFrom('team@fitoori.com', 'Fitoori Team');
    $mail->addReplyTo('team@fitoori.com', 'Fitoori Team');

    $mail->addAddress($toemail);               // Name is optional
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $subject;
    $mail->Body    = $message;

    $result ='';
    if(!$mail->send()) {

        $result .=  $mail->ErrorInfo;
    } else {
        $result .= 'EMAIL SENT';
    }
    return $result;

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
        return false;
    }
}
function isGuest() {
    if(isset($_SESSION['userid'])) {
        return false;
    }
    else {
        return true;
    }
}
function getCurrentUserID() {
    if(isset($_SESSION['agentId'])) {
        return $_SESSION['agentId'];
    }
    else if(isset($_SESSION['userid'])) {
          //have to get user id or guest id here from session
          return $_SESSION['userid'];
    }
    else {
      return null;
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