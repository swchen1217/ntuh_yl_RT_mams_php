<?php

require("config.php");
include ('./phpqrcode/qrlib.php');

/*$data="";
QRcode::png('MDMS.D0001','./img/output/test.png','H',3,4);*/





$img_url='./img/a02.png';
$img = imagecreatefromstring(file_get_contents($img_url));
$font = 'C:\Windows\Fonts\arial.ttf';
$black = imagecolorallocate($img,0, 0, 0);
$fontSize = 8;
$circleSize = 0;
$left = 10;
$top = 85;
imagettftext($img, $fontSize, $circleSize, $left, $top, $black, $font, 'MDMS.D0001');

Header("Content-type: image/png");

imagepng($img);
//imagepng($img,'img/output/a02.png');
imagedestroy($img);

//header('Location:./img/output/a01.png');

//cho '<img src="./img/output/a02.png" />';10
?>