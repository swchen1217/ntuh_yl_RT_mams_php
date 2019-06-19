<?php
require("config.php");

$new_qrcode_DID="";

$sql = ' SELECT `DID` FROM `device_tb` WHERE 1 ORDER BY `DID` DESC';
$rs=mysqli_query($con,$sql);
list($last_DID)=mysqli_fetch_row($rs);
$new_qrcode_DID=$last_DID;

echo '<img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=MDMS.D001&qzone=2" alt="" title="" />';
    
echo $new_qrcode_DID;
?>