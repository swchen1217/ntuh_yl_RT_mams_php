<?php
require("config.php");

$new_qrcode_DID="";

$sql = ' SELECT `DID` FROM `device_tb` WHERE 1 ORDER BY `DID` DESC';
$rs=mysqli_query($con,$sql);
list($last_DID)=mysqli_fetch_row($rs);
$new_qrcode_DID=$last_DID;

$img_url='https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=MDMS.D001&qzone=2';
$img = imagecreatefromstring(file_get_contents($img_url));
putenv('GDFONTPATH=' . realpath('.'));
$font = 'm.ttf';//字体文件
$black = imagecolorallocate($img,255, 225, 255);//字体颜色 RGB
$fontSize = 20;   //字体大小
$circleSize = 0; //旋转角度
$left = 50;      //左边距
$top = 210;       //顶边距
imagefttext($img, $fontSize, $circleSize, $left, $top, $black, $font, '要加的文字内容');
//ob_clean();
//header('Content-Type:image/png');
//imagepng($img,'public/img/666.png');
//imagedestroy($img);

//echo '<img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=MDMS.D001&qzone=2" alt="" title="" />';
?>