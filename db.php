<?php
require("config.php");

mb_internal_encoding('UTF-8');

$mode = "";
if(isset($_REQUEST["mode"]))
	$mode=$_REQUEST["mode"];
$LastModified = "";
if(isset($_REQUEST["LastModified"]))
	$LastModified=$_REQUEST["LastModified"];
$josn_data = "";
if(isset($_REQUEST["josn_data"]))
	$josn_data=$_REQUEST["josn_data"];

$key=array("DID","category","model","number","user","position","status","LastModified");

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
	exit;
}
if($mode=="sync_device_tb_upload"){
	if($josn_data!=""){
		$data=json_decode($josn_data, true);
		for($i=0;$i<sizeof($data);$i++){
			$sql = 'SELECT * FROM `device_tb` WHERE `DID`="'.$data[$i]["DID"].'"';
			$rs=mysqli_query($con,$sql);
			if(mysqli_num_rows($rs)==0){
				$sql2 = 'INSERT INTO `device_tb` (`DID`,`category`,`model`,`number`,`user`,`position`,`status`,`LastModified`) VALUES ("'.$data[$i][$key[0]].'","'.$data[$i][$key[1]].'","'.$data[$i][$key[2]].'","'.$data[$i][$key[3]].'","'.$data[$i][$key[4]].'","'.$data[$i][$key[5]].'","'.$data[$i][$key[6]].'","'.$data[$i][$key[7]].'")';
				$rs2=mysqli_query($con,$sql2);
			}else{
				$sql2 = 'UPDATE `device_tb` SET `DID`="'.$data[$i][$key[0]].'",`category`="'.$data[$i][$key[1]].'",`model`="'.$data[$i][$key[2]].'",`number`="'.$data[$i][$key[3]].'",`user`="'.$data[$i][$key[4]].'",`position`="'.$data[$i][$key[5]].'",`status`="'.$data[$i][$key[6]].'",`LastModified`="'.$data[$i][$key[7]].'"	 WHERE `DID`="'.$data[$i]["DID"].'"';
				$rs2=mysqli_query($con,$sql2);
			}
		}
	}
	exit;
}
?>