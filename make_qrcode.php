<?php
require("config.php");

$new_qrcode_DID="";

 /*$sql = ' SELECT `DID` FROM `device_tb` WHERE 1 ORDER BY `DID` DESC';
$rs=mysqli_query($con,$sql);
list($last_DID)=mysqli_fetch_row($rs);
$new_qrcode_DID=$last_DID;*/

$img_url='./img/a01.png';
$img = imagecreatefromstring(file_get_contents($img_url));
putenv('GDFONTPATH=' . realpath('.'));
$font = 'Arial.ttf';
$black = imagecolorallocate($img,255, 225, 255);
$fontSize = 20;
$circleSize = 0;
$left = 50;
$top = 210;
imagettftext($img, $fontSize, $circleSize, $left, $top, $black, $font, 'MDMS.D0001');

header('Content-Type: image/png');

imagepng($img);
imagedestroy($img);

//ob_clean();
//header('Content-Type:image/png');
//imagepng($img,'public/img/666.png');
//imagedestroy($img);

//echo '<img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=MDMS.D001&qzone=2" alt="" title="" />';
?>