<?php

include_once("inc/my_connect.php");
include_once("inc/control.php");

if(loginned() !== true)
{
	header("Location: index.php");
	exit;
}
$q = mysql_query("update users set lasttime = ". time() ." where id = ".$_COOKIE['mir_id']);

if($_GET['act'] != "" && $_GET['act'] != "") $_GET['act'] = "";

$body = "";

if($_GET['act'] == "")
{
	
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>