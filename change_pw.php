<?php
require("config.php");

$in = "";
if(isset($_POST["in"]))
	$in=$_POST["in"];
echo $in;
?>