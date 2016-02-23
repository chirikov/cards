<?php
include_once("../inc/my_connect.php");
include_once("inc/control.php");
include_once("../inc/constants.php");
include_once("../inc/functions.php");

$help_page = "city";

if(
$_GET['act'] != "select" && 
$_GET['act'] != "selected"
) $_GET['act'] = "select";

$body = "";

if($_GET['act'] == "select")
{
	$qcity = mysql_query("select id, name from cities where state = (select state from gameinfo where id = ".$_COOKIE['mir_id'].")");
	
	$body .= "
	<div class='path'><a href='profile.php'>Вторая Россия</a> » Выбор города</div>
	<form action='city.php?act=selected' method='post'>
	<select name='city'>";
	
	$qmycity = mysql_query("select city from gameinfo where id = ".$_COOKIE['mir_id']);
	$mycity = mysql_result($qmycity, 0);
	
	$body .= getlist($qcity, "select", $mycity);
	$body .= "</select> <input type='submit' value='Выбрать'></form>";
}
elseif($_GET['act'] == "selected")
{
	$q = mysql_query("update gameinfo set city = ".$_POST['city'].", job = 0 where id = ".$_COOKIE['mir_id']);
	header("Location: profile.php");
	exit;
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>