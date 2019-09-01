<?php
$db = new PDO('mysql:host=localhost;dbname=ntuh.yl_mdms;charset=utf8', 'server', 'Gd94YaEioIe27MCM');
$rs=$db->query('SELECT * FROM user_tb');
while ($row=$rs->fetch(PDO::FETCH_NUM)){
    echo $row[0]."<br>";
}
?>