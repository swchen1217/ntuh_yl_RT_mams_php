<?php
date_default_timezone_set('Asia/Taipei');

$mysql_host = "localhost";
$mysql_user = "server";
$mysql_pass = "Gd94YaEioIe27MCM";
$mysql_db = "ntuh.yl_mdms";

$db = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_db.';charset=utf8', $mysql_user, $mysql_pass);
?>