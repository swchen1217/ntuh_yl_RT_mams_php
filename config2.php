<?php
date_default_timezone_set('Asia/Taipei');
$mysql_host = "localhost";
$mysql_user = "server";
$mysql_pass = "Gd94YaEioIe27MCM";
$mysql_db = "ntuh.yl_mdms";
$con = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);

mysqli_query($con, "set names utf8");
?>