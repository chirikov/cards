<?php

include_once("inc/my_connect.php");
include_once("inc/control.php");

if(loginned() !== true)
{
	header("Location: login.php?act=login");
	exit;
}
$q = mysql_query("update users set lasttime = ". time() ." where id = ".$_COOKIE['mir_id']);

if(
$_GET['act'] != "selectjob" && 
$_GET['act'] != "selectedjob" && 
$_GET['act'] != "shop" && 
$_GET['act'] != "buy" && 
$_GET['act'] != "default"
) $_GET['act'] = "default";

$body = "";

if($_GET['act'] == "selectjob")
{
	$q1 = mysql_query("select work from info where id = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($q1);
	if($row['work'] != 0)
	{
		$body .= "Внимание! Вы уже работаете в одном из учреждений. Смена учреждения приведет к потере прежней работы.<br>";
	}
	$body .= "
	<form name='f1' method='post' action='object.php?act=selectedjob'>
	<input type='hidden' name='workplace' value=".$_POST['workplace'].">";
	$q2 = mysql_query("select id, job, num from vacancies where oid = ".$_POST['workplace']);
	$body .= "<select name='job'>";
	for($i=0; $i<mysql_num_rows($q2); $i++)
	{
		$jobnameid = mysql_result($q2, $i, 'job');
		$jobid = mysql_result($q2, $i, 'id');
		$jobn = mysql_result($q2, $i, 'num');
		$q3 = mysql_query("select name from jobs where id = ".$jobnameid);
		$body .= "<option value=".$jobid.">".mysql_result($q3, 0)." (".$jobn.")<br>";
	}
	$body .= "</select><input type='submit' value='Выбрать'></form>";
}
elseif($_GET['act'] == "selectedjob")
{
	$q2 = mysql_query("update info set work = ".$_POST['job']." where id = ".$_COOKIE['mir_id']);
	header("Location: profile.php?act=home");
	exit;
}
elseif($_GET['act'] == "default")
{
	if($_GET['oid']<1) $_GET['oid'] = 1;
	$q1 = mysql_query("select name, details from objects where id = ".$_GET['oid']);
	$row = mysql_fetch_assoc($q1);
	$body .= $row['name']."<br><br>".$row['details'];
	$q2 = mysql_query("select id from pricelist where oid = ".$_GET['oid']);
	if(mysql_num_rows($q2) > 0) $body .= "<br><br><a href='object.php?act=shop&oid=".$_GET['oid']."'>Список товаров</a>";
}
elseif($_GET['act'] == "shop")
{
	$q1 = mysql_query("select id, gid, price from pricelist where oid = ".$_GET['oid']);
	$body .= "<form name='f1' method='post' action='object.php?act=buy'>";
	while($row = mysql_fetch_assoc($q1))
	{
		$q2 = mysql_query("select name from goods where id = ".$row['gid']);
		$body .= "<input type='radio' name='gid' value='".$row['id']."'>".mysql_result($q2, 0).", ".$row['price']."<br>";
	}
	$body .= "<input type='submit' name='go' value='Купить'></form>";
}
elseif($_GET['act'] == "buy")
{
	$q1 = mysql_query("insert into property (uid, gid) values ('".$_COOKIE['mir_id']."', '".$_POST['gid']."')");
	$q3 = mysql_query("select money from info where id = ".$_COOKIE['mir_id']);
	$money = mysql_result($q3, 0);
	$q4 = mysql_query("select price from pricelist where id = ".$_POST['gid']);
	$cost = mysql_result($q4, 0);
	$nmoney = $money - $cost;
	$q2 = mysql_query("update info set money = ".$nmoney." where id = ".$_COOKIE['mir_id']);
	header("Location: profile.php?act=home");
	exit;
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>