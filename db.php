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
$id = "";
if(isset($_REQUEST["id"]))
	$id=$_REQUEST["id"];
$acc = "";
if(isset($_REQUEST["acc"]))
	$acc=$_REQUEST["acc"];
$pw = "";
if(isset($_REQUEST["pw"]))
	$pw=$_REQUEST["pw"];
$DID = "";
if(isset($_REQUEST["DID"]))
	$DID=$_REQUEST["DID"];
$user = "";
if(isset($_REQUEST["user"]))
	$user=$_REQUEST["user"];
$position = "";
if(isset($_REQUEST["position"]))
	$position=$_REQUEST["position"];
$status = "";
if(isset($_REQUEST["status"]))
	$status=$_REQUEST["status"];

$key=array("DID","category","model","number","user","position","status","LastModified");

if($mode=="sync_device_tb_download"){
	if(UserCheck($acc,$pw,1)){
		$sql = 'SELECT * FROM `device_tb` WHERE `LastModified` > "'.$LastModified.'"';
		$rs=mysqli_query($con,$sql);
		if(mysqli_num_rows($rs) == 0){
			echo "no_data";
		}else{
			$ToJson=array();
			while($row=mysqli_fetch_assoc($rs)){
				$ToJson[]=$row;
			}
			echo json_encode($ToJson);
		}
	}else{
		echo "user_error";
	}
	exit;
}
if($mode=="GetSystem_tb"){
	if($id!=""){
		if(UserCheck($acc,$pw,1)){
			$sql = 'SELECT value FROM `system_tb` WHERE id="'.$id.'"';
			$rs=mysqli_query($con,$sql);
			list($value_r)=mysqli_fetch_row($rs);
			echo $value_r;
		}else{
			echo "user_error";
		}
	}
	exit;
}
if($mode=="sync_position_item_tb_download"){
	if(UserCheck($acc,$pw,1)){
		$sql = 'SELECT * FROM `position_item_tb` WHERE 1';
		$rs=mysqli_query($con,$sql);
		$ToJson=array();
		while($row=mysqli_fetch_assoc($rs)){
			$ToJson[]=$row;
		}
		echo json_encode($ToJson);
	}else{
		echo "user_error";
	}
	exit;
}
if($mode=="update_device_tb_use"){
	if(UserCheck($acc,$pw,2)){
		if($DID!="" && $user!="" && $position!=""){
			$sql = 'UPDATE `device_tb` SET `user`="'.$user.'",`position`="'.$position.'",`status`="1",`LastModified`="'.date("Y-m-d H:i:s",time()).'" WHERE `DID`="'.$DID.'"';
			$rs=mysqli_query($con,$sql);
		}
	}else{
		echo "user_error";
	}
}
if($mode=="update_device_tb_storeroom"){
	if(UserCheck($acc,$pw,2)){
		if($DID!="" && $position!=""){
			$sql = 'UPDATE `device_tb` SET `user`="-",`position`="S-'.$position.'",`status`="2",`LastModified`="'.date("Y-m-d H:i:s",time()).'" WHERE `DID`="'.$DID.'"';
			$rs=mysqli_query($con,$sql);
		}
	}else{
		echo "user_error";
	}
}
function UserCheck($acc_in,$pw_in,$permission_in){
	require("config.php");
	if($acc_in!="" && $pw_in!=""){
		$sql = 'SELECT password,permission FROM `user_tb` WHERE `account`="'.$acc_in.'"';
		$rs=mysqli_query($con,$sql);
		if(mysqli_num_rows($rs) == 0){
			return false;
		}else{
			list($pw_r,$permission_r_first)=mysqli_fetch_row($rs);
			if($permission_r_first=="-1"){
				return false;
			}else{
				if(substr($pw_in,0,6)=="tmppw_"){
					$sql2 = 'SELECT tmppw,application_time FROM `user_tmppw_tb` WHERE `account`="'.$acc_in.'" order by application_time desc';
					$rs2=mysqli_query($con,$sql2);
					if(mysqli_num_rows($rs2) == 0)
						return false;
					else{
						list($tmppw_r,$application_time_r)=mysqli_fetch_row($rs2);
						if($pw_in==$tmppw_r){
							if((strtotime(date("Y-m-d H:i:s",time())) - strtotime($application_time_r))<=1800){
								$sql3 = 'SELECT name,permission FROM `user_tb` WHERE `account`="'.$acc_in.'"';
								$rs3=mysqli_query($con,$sql3);
								list($name_r,$permission_r)=mysqli_fetch_row($rs3);
								if((int)$permission_r>=$permission_in)
									echo true;
								else
									return false;
							}else
								return false;
						}else
							return false;
					}
				}else{
					if($pw_r==$pw_in){
						$sql4 = 'SELECT name,permission FROM `user_tb` WHERE `account`="'.$acc_in.'"';
						$rs4=mysqli_query($con,$sql4);
						list($name_r,$permission_r)=mysqli_fetch_row($rs4);
						if((int)$permission_r>=$permission_in)
							return true;
						else
							return false;
					}
					else
						return false;
				}
			}
		}
	}else
		return false;
}
?>