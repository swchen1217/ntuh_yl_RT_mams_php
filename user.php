<?php
require("config.php");
?>
<?php
$mode = "";
if(isset($_REQUEST["mode"]))
	$mode=$_REQUEST["mode"];
$acc = "";
if(isset($_REQUEST["acc"]))
	$acc=$_REQUEST["acc"];
$pw = "";
if(isset($_REQUEST["pw"]))
	$pw=$_REQUEST["pw"];
$new_pw = "";
if(isset($_REQUEST["new_pw"]))
	$new_pw=$_REQUEST["new_pw"];
if($mode=="connection_test"){
    echo "connection_ok"
}
if($mode=="login_check"){
    
}
?>