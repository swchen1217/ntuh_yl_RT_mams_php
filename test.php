<?php
$acc = filter_input(INPUT_POST, "acc");
if (!$acc) $acc = filter_input(INPUT_GET, "acc");

require("config2.php");

$sql = 'SELECT * FROM user_tb where account=:acc';
$rs = $db->prepare($sql);
$rs->bindValue(':acc', $acc, PDO::PARAM_STR);
$rs->execute();
while ($row = $rs->fetch(PDO::FETCH_NUM)) {
    echo $row[1] . "<br>";
}
?>