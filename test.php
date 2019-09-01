<?php

require("config2.php");

$acc=requst("acc");

$sql = 'SELECT * FROM user_tb where account=:acc';
$rs = $db->prepare($sql);
$rs->bindValue(':acc', $acc, PDO::PARAM_STR);
$rs->execute();
while ($row = $rs->fetch(PDO::FETCH_NUM)) {
    echo $row[1] . "<br>";
}

function requst($key) {
    $tmp = filter_input(INPUT_POST, $key);
    if (!$tmp) $tmp = filter_input(INPUT_GET, $key);
    return $tmp;
}

?>