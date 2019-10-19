<?php

echo uniqid('swchen1217').'<br>';
echo md5(uniqid('swchen1217'));

/*require("config.php");
require("request.php");

$acc=request("acc");

$sql = 'SELECT * FROM user_tb where account=:acc';
$rs = $db->prepare($sql);
$rs->bindValue(':acc', $acc, PDO::PARAM_STR);
$rs->execute();

while ($row = $rs->fetch(PDO::FETCH_NUM)) {
    echo $row[1] . "<br>";
}*/
?>