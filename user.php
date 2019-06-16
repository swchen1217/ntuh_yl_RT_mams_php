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
    echo "connection_ok";
}
if($mode=="login_check"){
    $sql = 'SELECT `password` FROM `user_tb` WHERE `account`="'.$acc.'"';
	$rs=mysqli_query($con,$sql);
    if(mysqli_num_rows($rs) == 0){
        echo "no_acc";
    }else{
        list($pw_r)=mysqli_fetch_row($rs);
        if($pw_r==$pw)
            echo "ok";
        else
            echo "pw_error";
    }
    exit;
}
if($mode=="get_user_name"){
    $sql = 'SELECT `name` FROM `user_tb` WHERE `account`="'.$acc.'"';
	$rs=mysqli_query($con,$sql);
    list($name)=mysqli_fetch_row($rs);
    echo $name;
    exit;
}
?>