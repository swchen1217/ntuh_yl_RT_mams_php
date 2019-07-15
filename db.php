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
	exit;
}
if($mode=="GetSystem_tb"){
	if($id!=""){
		$sql = 'SELECT value FROM `system_tb` WHERE id="'.$id.'"';
		$rs=mysqli_query($con,$sql);
		list($value_r)=mysqli_fetch_row($rs);
		echo $value_r;
	}
	exit;
}
if($mode=="sync_position_item_tb_download"){
	$sql = 'SELECT * FROM `position_item_tb` WHERE 1';
	$rs=mysqli_query($con,$sql);
	$ToJson=array();
	while($row=mysqli_fetch_assoc($rs)){
		$ToJson[]=$row;
	}
	echo json_encode($ToJson);
	exit;
}
if($mode=="update_device_tb_use"){
	if($acc!="" && $pw!=""){
		
	}
}
?>