<?php
include_once("../inc/my_connect.php");
include_once("inc/control.php");

if(
$_GET['act'] != "ingame"
) exit;

if(loginned() !== true && $_GET['act'] != "ingame")
{
	header("Location: index.php");
	exit;
}

if($_GET['act'] == "ingame")
{
	$q = mysql_query("insert into gameinfo (id) values ('".$_COOKIE['mir_id']."')");
	header("Location: profile.php");
	exit;
}


?>