<?php
/*$acc = "";
if(isset($_REQUEST["acc"]))
    $acc=$_REQUEST["acc"];*/
$pw = "";
if(isset($_REQUEST["pw"]))
    $pw=$_REQUEST["pw"];

$acc = filter_input(INPUT_POST, "acc");
if(!$acc) $acc = filter_input(INPUT_GET, "acc");

$db = new PDO('mysql:host=localhost;dbname=ntuh.yl_mdms;charset=utf8', 'server', 'Gd94YaEioIe27MCM');

$sql='SELECT * FROM user_tb where account=:acc';
$rs=$db->prepare($sql);
$rs->bindValue(':acc',$acc,PDO::PARAM_STR);
$rs->execute();
while ($row=$rs->fetch(PDO::FETCH_NUM)){
    echo $row[1]."<br>";
}
?>