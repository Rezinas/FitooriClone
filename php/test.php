<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// $elements =  array(
//   "custom_product"  => [ array (
//      "id"=> 119,
//       "carouselImg"=> "sample_1464173208.png",
//       "imgheight"=> 71,
//       "imgwidth"=> 20,
//       "bodypart"=> 3,
//       "centerx"=> 17,
//       "centery"=> 12,
//       "toppoints"=> 0,
//       "topX"=> "",
//       "topY"=> "",
//       "bottompoints"=> 1,
//       "botX"=> "8",
//       "botY"=> "68",
//       "color"=> "",
//       "texture"=> "",
//       "style"=> "hook,dangler,chandelier,jhumka",
//       "admintags"=> ",",
//       "material"=> 3,
//       "price"=> "0",
//       "name"=> "Oxidized Metal - Gold",
//       "quantity"=> 5,
//       "priority"=> 0,
//       "selectedImage"=> "sample_1464173208.png",
//       "topPos"=> 12,
//       "leftPos"=> 17
//     ),
//     array(
//       "id"=> 87,
//       "carouselImg"=> "DSC03618_1455793450.png",
//       "imgheight"=> 90,
//       "imgwidth"=> 77,
//       "bodypart"=> 3,
//       "centerx"=> 0,
//       "centery"=> 0,
//       "toppoints"=> 1,
//       "topX"=> "32",
//       "topY"=> "0",
//       "bottompoints"=> 5,
//       "botX"=> "1,17,33,48,64",
//       "botY"=> "85,85,85,85,85",
//       "color"=> "",
//       "texture"=> "",
//       "style"=> "jhumka",
//       "admintags"=> "",
//       "material"=> 3,
//       "price"=> "50",
//       "name"=> "Oxidized Metal",
//       "quantity"=> 10,
//       "priority"=> 0,
//       "selectedImage"=> "DSC03618_1455793450.png",
//       "topPos"=> 80,
//       "leftPos"=> -7
//     ),
//     array(
//       "id"=> 159,
//       "carouselImg"=> "DSC03878_1463336482.png",
//       "imgheight"=> 26,
//       "imgwidth"=> 10,
//       "bodypart"=> 3,
//       "centerx"=> 0,
//       "centery"=> 0,
//       "toppoints"=> 1,
//       "topX"=> "0",
//       "topY"=> "-4",
//       "bottompoints"=> 0,
//       "botX"=> "",
//       "botY"=> "",
//       "color"=> "",
//       "texture"=> "",
//       "style"=> "chandelier,hoop,jhumka,dangler",
//       "admintags"=> "",
//       "material"=> 2,
//       "price"=> "2",
//       "name"=> "Acrylic",
//       "quantity"=> 10,
//       "priority"=> 0,
//       "selectedImage"=> "DSC03878_1463336482.png",
//       "topPos"=> 165,
//       "leftPos"=> -6
//     ),
//     array(
//       "id"=> 159,
//       "carouselImg"=> "DSC03878_1463336482.png",
//       "imgheight"=> 26,
//       "imgwidth"=> 10,
//       "bodypart"=> 3,
//       "centerx"=> 0,
//       "centery"=> 0,
//       "toppoints"=> 1,
//       "topX"=> "0",
//       "topY"=> "-4",
//       "bottompoints"=> 0,
//       "botX"=> "",
//       "botY"=> "",
//       "color"=> "",
//       "texture"=> "",
//       "style"=> "chandelier,hoop,jhumka,dangler",
//       "admintags"=> "",
//       "material"=> 2,
//       "price"=> "2",
//       "name"=> "Acrylic",
//       "quantity"=> 10,
//       "priority"=> 0,
//       "selectedImage"=> "DSC03878_1463336482.png",
//       "topPos"=> 165,
//       "leftPos"=> 10
//     ),
//     array(
//       "id"=> 159,
//       "carouselImg"=> "DSC03878_1463336482.png",
//       "imgheight"=> 26,
//       "imgwidth"=> 10,
//       "bodypart"=> 3,
//       "centerx"=> 0,
//       "centery"=> 0,
//       "toppoints"=> 1,
//       "topX"=> "0",
//       "topY"=> "-4",
//       "bottompoints"=> 0,
//       "botX"=> "",
//       "botY"=> "",
//       "color"=> "",
//       "texture"=> "",
//       "style"=> "chandelier,hoop,jhumka,dangler",
//       "admintags"=> "",
//       "material"=> 2,
//       "price"=> "2",
//       "name"=> "Acrylic",
//       "quantity"=> 10,
//       "priority"=> 0,
//       "selectedImage"=> "DSC03878_1463336482.png",
//       "topPos"=> 165,
//       "leftPos"=> 26
//     ),
//     array(
//       "id"=> 159,
//       "carouselImg"=> "DSC03878_1463336482.png",
//       "imgheight"=> 26,
//       "imgwidth"=> 10,
//       "bodypart"=> 3,
//       "centerx"=> 0,
//       "centery"=> 0,
//       "toppoints"=> 1,
//       "topX"=> "0",
//       "topY"=> "-4",
//       "bottompoints"=> 0,
//       "botX"=> "",
//       "botY"=> "",
//       "color"=> "",
//       "texture"=> "",
//       "style"=> "chandelier,hoop,jhumka,dangler",
//       "admintags"=> "",
//       "material"=> 2,
//       "price"=> "2",
//       "name"=> "Acrylic",
//       "quantity"=> 10,
//       "priority"=> 0,
//       "selectedImage"=> "DSC03878_1463336482.png",
//       "topPos"=> 165,
//       "leftPos"=> 41
//     ),
//     array(
//       "id"=> 159,
//       "carouselImg"=> "DSC03878_1463336482.png",
//       "imgheight"=> 26,
//       "imgwidth"=> 10,
//       "bodypart"=> 3,
//       "centerx"=> 0,
//       "centery"=> 0,
//       "toppoints"=> 1,
//       "topX"=> "0",
//       "topY"=> "-4",
//       "bottompoints"=> 0,
//       "botX"=> "",
//       "botY"=> "",
//       "color"=> "",
//       "texture"=> "",
//       "style"=> "chandelier,hoop,jhumka,dangler",
//       "admintags"=> "",
//       "material"=> 2,
//       "price"=> "2",
//       "name"=> "Acrylic",
//       "quantity"=> 10,
//       "priority"=> 0,
//       "selectedImage"=> "DSC03878_1463336482.png",
//       "topPos"=> 165,
//       "leftPos"=> 57
//     )

//    ]);



$elements =  array(
  "custom_product"  => [ array (
      "id"=> 114,
      "carouselImg"=> "DSC03449_1456829862.png",
      "imgheight"=> 62,
      "imgwidth"=> 28,
      "bodypart"=> 3,
      "centerx"=> 15,
      "centery"=> 11,
      "toppoints"=> 0,
      "topX"=> "",
      "topY"=> "",
      "bottompoints"=> 1,
      "botX"=> "10",
      "botY"=> "60",
      "color"=> "",
      "texture"=> "",
      "style"=> "hook,dangler,chandelier,jhumka",
      "admintags"=> "",
      "material"=> 3,
      "price"=> "0",
      "name"=> "",
      "quantity"=> 98,
      "priority"=> 0,
      "selectedImage"=> "DSC03449_1456829862.png",
      "topPos"=> 11,
      "leftPos"=> 15
    ),
    array(
      "id"=> 22,
      "carouselImg"=> "DSC03572_1455201629.png",
      "imgheight"=> 104,
      "imgwidth"=> 58,
      "bodypart"=> 3,
      "centerx"=> 0,
      "centery"=> 0,
      "toppoints"=> 1,
      "topX"=> "26",
      "topY"=> "-1",
      "bottompoints"=> 0,
      "botX"=> "",
      "botY"=> "",
      "color"=> "",
      "texture"=> "",
      "style"=> "dangler",
      "admintags"=> "contemporary",
      "material"=> 2,
      "price"=> "50",
      "name"=> "Acrylic",
      "quantity"=> 100,
      "priority"=> 0,
      "selectedImage"=> "DSC03572_1455201629.png",
      "topPos"=> 71,
      "leftPos"=> -1
        )
   ]);















  //   $elements =  array(
  // "custom_product"  => [ array (
  //     "id" => 112,
  //     "carouselImg" => "DSC03166_1464173399.png",
  //     "imgheight" => 76,
  //     "imgwidth" => 19,
  //     "bodypart" => 3,
  //     "centerx" => 17,
  //     "centery" => 10,
  //     "toppoints" => 0,
  //     "topX" => "",
  //     "topY" => "",
  //     "bottompoints" => 1,
  //     "botX" => "6",
  //     "botY" => "73",
  //     "color" => "",
  //     "texture" => "",
  //     "style" => "hook,dangler,chandelier,jhumka",
  //     "admintags" => "",
  //     "material" => 3,
  //     "price" => "0",
  //     "name" => "Oxidized Metal - Silver",
  //     "quantity" => 10,
  //     "priority" => 0,
  //     "selectedImage" => "DSC03166_1464173399.png",
  //     "topPos" => 10,
  //     "leftPos" => 17
  //     ),
  //     array (
  //        "id" => 81,
  //     "carouselImg" => "DSC03605_1455792736.png",
  //     "imgheight" => 65,
  //     "imgwidth" => 60,
  //     "bodypart" => 3,
  //     "centerx" => 0,
  //     "centery" => 0,
  //     "toppoints" => 1,
  //     "topX" => "25",
  //     "topY" => "-3",
  //     "bottompoints" => 0,
  //     "botX" => "",
  //     "botY" => "",
  //     "color" => "Red",
  //     "texture" => "Plain",
  //     "style" => "jhumka",
  //     "admintags" => "",
  //     "material" => 2,
  //     "price" => "80",
  //     "name" => "Metal/Acrylic",
  //     "quantity" => 10,
  //     "priority" => 0,
  //     "selectedImage" => "DSC03607_1455792736.png",
  //     "topPos" => 83,
  //     "leftPos" => -2
  //     )
  //     ]);



// $elements =  array(
//   "custom_product"  => [ array (
//             "id" => 5,
//             "carouselImg" => "Trial_3_1455042538.png",
//             "imgheight" => 80,
//             "imgwidth" => 76,
//             "bodypart" => 3,
//             "centerx" => -6,
//             "centery" => 0,
//             "toppoints" => 0,
//             "topX" => "",
//             "topY" => "",
//             "bottompoints" => 3,
//             "botX" => "4,36,63",
//             "botY" => "50,80,49",
//             "color" => "",
//             "texture" => "",
//             "style" => 1,
//             "admintags" => "contemporary,danglers,studs",
//             "material" => 1,
//             "price" => "40",
//             "selectedImage" => "Trial_3_1455042538.png",
//             "topPos" => 0,
//             "leftPos" => -6
//       ),
//       array (
//           "id" => 10,
//       "carouselImg" => "4DSC03491_1455043797.png",
//       "imgheight" => 29,
//       "imgwidth" => 24,
//       "bodypart" => 3,
//       "centerx" => 0,
//       "centery" => 0,
//       "toppoints" => 1,
//       "topX" => "7",
//       "topY" => "-4",
//       "bottompoints" => 0,
//       "botX" => "",
//       "botY" => "",
//       "color" => "",
//       "texture" => "",
//       "style" => 1,
//       "admintags" => "danglers,chandelier",
//       "material" => 1,
//       "price" => "10",
//       "images" => [

//       ],
//       "selectedImage" => "4DSC03491_1455043797.png",
//       "topPos" => 50,
//       "leftPos" => -9
//       ),
//       array (
//              "id" => 10,
//       "carouselImg" => "4DSC03491_1455043797.png",
//       "imgheight" => 29,
//       "imgwidth" => 24,
//       "bodypart" => 3,
//       "centerx" => 0,
//       "centery" => 0,
//       "toppoints" => 1,
//       "topX" => "7",
//       "topY" => "-4",
//       "bottompoints" => 0,
//       "botX" => "",
//       "botY" => "",
//       "color" => "",
//       "texture" => "",
//       "style" => 1,
//       "admintags" => "danglers,chandelier",
//       "material" => 1,
//       "price" => "10",
//       "images" => [

//       ],
//       "selectedImage" => "4DSC03491_1455043797.png",
//       "topPos" => 80,
//       "leftPos" => 23
//       ),
//       array (
//               "id" => 10,
//       "carouselImg" => "4DSC03491_1455043797.png",
//       "imgheight" => 29,
//       "imgwidth" => 24,
//       "bodypart" => 3,
//       "centerx" => 0,
//       "centery" => 0,
//       "toppoints" => 1,
//       "topX" => "7",
//       "topY" => "-4",
//       "bottompoints" => 0,
//       "botX" => "",
//       "botY" => "",
//       "color" => "",
//       "texture" => "",
//       "style" => 1,
//       "admintags" => "danglers,chandelier",
//       "material" => 1,
//       "price" => "10",
//       "images" => [

//       ],
//       "selectedImage" => "4DSC03491_1455043797.png",
//       "topPos" => 49,
//       "leftPos" => 50
//       )
//       ]);


// $elements =  array(
//       "custom_product"  => [ array (
//               "id" => 3,
//   "carouselImg" => "1_1455040774.png",
//   "imgheight" => 42,
//   "imgwidth" => 42,
//   "bodypart" => 3,
//   "centerx" => 10,
//   "centery" => -9,
//   "toppoints" => 0,
//   "topX" => "",
//   "topY" => "",
//   "bottompoints" => 1,
//   "botX" => "18",
//   "botY" => "41",
//   "color" => "",
//   "texture" => "",
//   "style" => 1,
//   "admintags" => "chandelier,jhumka,studs,danglers",
//   "material" => 1,
//   "price" => "30",
//   "name" => "",
//   "selectedImage" => "1_1455040774.png",
//   "topPos" => -9,
//   "leftPos" => 10
//       )
//       ]);

// $elements =  array(
//       "custom_product"  => [ array (
//                "id" => 112,
//       "carouselImg" => "DSC03166_1456744307.png",
//       "imgheight" => 80,
//       "imgwidth" => 20,
//       "bodypart" => 3,
//       "centerx" => 17,
//       "centery" => 8,
//       "toppoints" => 0,
//       "topX" => "",
//       "topY" => "",
//       "bottompoints" => 1,
//       "botX" => "7",
//       "botY" => "77",
//       "color" => "",
//       "texture" => "",
//       "style" => 1,
//       "admintags" => "hook,danglers,chandelier,jhumka",
//       "material" => 3,
//       "price" => "0",
//       "name" => "",
//       "selectedImage" => "DSC03166_1456744307.png",
//       "topPos" => 8,
//       "leftPos" => 17
//       ),
//       array(
//         "id" => 108,
//       "carouselImg" => "DSC03706_1456144289.png",
//       "imgheight" => 43,
//       "imgwidth" => 28,
//       "bodypart" => 3,
//       "centerx" => 0,
//       "centery" => 0,
//       "toppoints" => 1,
//       "topX" => "10",
//       "topY" => "-4",
//       "bottompoints" => 1,
//       "botX" => "10",
//       "botY" => "40",
//       "color" => "",
//       "texture" => "",
//       "style" => 1,
//       "admintags" => "danglers,chandelier,hoops",
//       "material" => 2,
//       "price" => "10",
//       "name" => "",
//       "selectedImage" => "DSC03706_1456144289.png",
//       "topPos" => 85,
//       "leftPos" => 14
//         )
//       ]);



function imagetrim(&$im, $bg, $pad=null){
    // Calculate padding for each side.
    if (isset($pad)){
        $pp = explode(' ', $pad);
        if (isset($pp[3])){
            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[3]);
        }else if (isset($pp[2])){
            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[1]);
        }else if (isset($pp[1])){
            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[0], (int) $pp[1]);
        }else{
            $p = array_fill(0, 4, (int) $pp[0]);
        }
    }else{
        $p = array_fill(0, 4, 0);
    }

    // Get the image width and height.
    $imw = imagesx($im);
    $imh = imagesy($im);

    // Set the X variables.
    $xmin = $imw;
    $xmax = 0;

    // Start scanning for the edges.
    for ($iy=0; $iy<$imh; $iy++){
        $first = true;
        for ($ix=0; $ix<$imw; $ix++){
            $ndx = imagecolorat($im, $ix, $iy);
            if ($ndx != $bg){
                if ($xmin > $ix){ $xmin = $ix; }
                if ($xmax < $ix){ $xmax = $ix; }
                if (!isset($ymin)){ $ymin = $iy; }
                $ymax = $iy;
                if ($first){ $ix = $xmax; $first = false; }
            }
        }
    }

    // The new width and height of the image. (not including padding)
    $imw = 1+$xmax-$xmin; // Image width in pixels
    $imh = 1+$ymax-$ymin; // Image height in pixels

    // Make another image to place the trimmed version in.
    $im2 = imagecreatetruecolor($imw+$p[1]+$p[3], $imh+$p[0]+$p[2]);
     imagesavealpha($im2, true);

    // Make the background of the new image the same as the background of the old one.
    $bg2 = imagecolorallocatealpha($im2, 0,0,0,127);
    imagefill($im2, 0, 0, $bg2);

    // Copy it over to the new image.
    imagecopy($im2, $im, $p[3], $p[0], $xmin, $ymin, $imw, $imh);

    // To finish up, we replace the old image which is referenced.
    $im = $im2;
}

 function shadow_text($im, $size, $x, $y, $font, $text)
  {
    $fcolor = imagecolorallocate($im, 128, 128, 128);
    imagettftext($im, $size, 0, $x + 1, $y + 1, $fcolor, $font, $text);
  }

function createCustomPrdImage($elemArr)
 {
       //get total height of the design
    $totalheight =220; //initial offset
    $totalwidth =250; //initial offset
    foreach($elemArr as $elm) {
        $totalheight += $elm['imgheight'];
        $totalwidth += $elm['imgwidth'];
    };


    $img = imagecreatetruecolor($totalwidth, $totalheight);
    imagesavealpha($img, true);
    $color = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagefill($img, 0, 0, $color);


     //start constructing the image
    $offsetx = 15;
    $offsety = 15;
     foreach($elemArr as $key => $elm) {
      $imgpart = imagecreatefrompng("../productImages/".$elm['selectedImage']);
        $orig_w = $elm["imgwidth"];
        $orig_h = $elm["imgheight"];

        $dst_x = $elm['leftPos'];
        $dst_y = $elm['topPos'];
        if($dst_x < 0 && $key == 0) {
            $offsetx += abs($dst_x);
        }
        $dst_x += $offsetx;
        $dst_y += $offsety;

      imagealphablending($imgpart, false);
      imagecopyresampled($img, $imgpart, $dst_x, $dst_y, 0, 0, $orig_w, $orig_h, $orig_w, $orig_h);
      imagesavealpha( $img, true );
    }

    if(!imagepng($img, "../productImages/test0.png", 1)){
      return "ERROR";
    }

    imagetrim($img,$color, '33 25 33 25');
    $ow  = imagesx($img);
    $oh = imagesy($img);

 if(!imagepng($img, "../productImages/test1.png", 1)){
      return "ERROR";
    }

    $out_w = $ow*2;
    $out = imagecreatetruecolor($out_w, $oh+20);
    imagesavealpha($out, true);
    imagefill($out, 0, 0, $color);

    $curr_x = 0;
    $curr_y = 0;
    while($curr_x < $out_w){
    imagealphablending($out, true);
    imagecopy($out, $img, $curr_x, $curr_y, 0, 0, $ow, $oh);
    imagesavealpha( $out, true );

    $curr_x += $ow;
    $curr_y = 15;

    }

         $font = '../fonts/arial.ttf';
         $size = 8;

        $bbox = imagettfbbox($size, 0, $font, 'ky');
        $x =  $ow-10; $y = $bbox[5]+30;

        $text = 'FITOORI DESIGNS';
        shadow_text($out, $size, $x, $y, $font, $text);


 if(!imagepng($out, "../productImages/test2.png", 1)){
      return "ERROR";
    }


    // $ow_final  = imagesx($out);
    // $oh_final = imagesy($out);

    // $finalImg = imagecreatetruecolor(220, 250);
    // imagesavealpha($finalImg, true);
    // imagefill($finalImg, 0, 0, $color);

    // imagealphablending($finalImg, false);
    // imagecopyresampled($finalImg, $out, 0, 0, 0, 0, 220, 250, $ow_final, $oh_final);
    // imagesavealpha( $finalImg, true );


   // $fn = md5(microtime()."new")."_custom.png";
   $fn = "custom.png";

    $result;
    if(imagepng($out, "../productImages/".$fn, 9)){
    // if(imagepng($finalImg, "../productImages/".$fn, 9)){
      $result = $fn;
    }
    else {
      $result = "ERROR";
    }
    // imagedestroy($finalImg);
    imagedestroy($img);
    imagedestroy($out);
    return $result;
 }



createCustomPrdImage($elements["custom_product"]);
?>


<img style="border: 1px solid;" src="../productImages/test0.png" />
<img style="border: 1px solid;" src="../productImages/test1.png" />
<img style="border: 1px solid;" src="../productImages/test2.png" />
<img style="border: 1px solid;" src="../productImages/custom.png" />
<!-- <img style="border: 1px solid;" src="../productImages/DSC03507_1455707404.png" /> -->













