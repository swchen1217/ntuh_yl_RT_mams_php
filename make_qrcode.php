<?php

//ini_set('display_errors','off');

require("config.php");

$new_qrcode_DID="";

 /*$sql = ' SELECT `DID` FROM `device_tb` WHERE 1 ORDER BY `DID` DESC';
$rs=mysqli_query($con,$sql);
list($last_DID)=mysqli_fetch_row($rs);
$new_qrcode_DID=$last_DID;*/

$img_url='./img/a01.png';
$img = imagecreatefromstring(file_get_contents($img_url));
//putenv('GDFONTPATH=' . realpath('.'));
$font = 'C:\Windows\Fonts\arial.ttf';
$black = imagecolorallocate($img,0, 0, 0);
$fontSize = 15;
$circleSize = 0;
$left = 63;
$top = 247;
imagettftext($img, $fontSize, $circleSize, $left, $top, $black, $font, 'MDMS.D0001');

imagepng($img,'img/output/a01.png');
imagedestroy($img);

//header('Location:./img/output/a01.png');

echo '<img src="./img/output/a01.png" />';
?>