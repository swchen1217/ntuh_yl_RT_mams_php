<?php
require("config.php");

mb_internal_encoding('UTF-8');

$mode = "";
if(isset($_REQUEST["mode"]))
	$mode=$_REQUEST["mode"];
$LastModified = "";
if(isset($_REQUEST["LastModified"]))
	$LastModified=$_REQUEST["LastModified"];


if($mode=="sync_device_tb_download"){
	$sql = 'SELECT * FROM `device_tb` WHERE `LastModified` > "'.$LastModified.'"';
	$rs=mysqli_query($con,$sql);
	if(mysqli_num_rows($rs) == 0){
		echo "no_data";
	}else{
		/*while(list($r_DID,$r_category,$r_model,$r_number,$r_user,$r_position,$r_status,$r_LastModified)=mysqli_fetch_row($rs)){
			echo $r_DID.",".$r_category.",".$r_model.",".$r_number.",".$r_user.",".$r_position.",".$r_status.",".$r_LastModified."<br>";
		}*/
		$ToJson=array();
		while($row=mysqli_fetch_assoc($rs)){
			$ToJson[]=$row;
		}
		echo json_encode($ToJson);
	}
}
if($mode=="sync_device_tb_upload"){
	
}
?>