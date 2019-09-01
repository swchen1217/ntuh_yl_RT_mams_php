<?php
$acc = "";
if(isset($_REQUEST["acc"]))
    $acc=$_REQUEST["acc"];
$pw = "";
if(isset($_REQUEST["pw"]))
    $pw=$_REQUEST["pw"];

$db = new PDO('mysql:host=localhost;dbname=ntuh.yl_mdms;charset=utf8', 'server', 'Gd94YaEioIe27MCM');
$sql='SELECT * FROM user_tb where account="'.$acc.'"';
$rs=$db->prepare($sql);
$rs->execute(array(':acc'=>$acc));
while ($row=$rs->fetch(PDO::FETCH_NUM)){
    echo $row[1]."<br>";
}
?>