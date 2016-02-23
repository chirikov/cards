<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");

if(
$_GET['act'] != "setgosnom" && 
$_GET['act'] != "options" && 
$_GET['act'] != "ajaxsavepage"
) exit;

$body = "";

if($_GET['act'] == "setgosnom")
{
	if($_GET['nocar'] == 1)
	{
		$q = mysql_query("update info set gosnom = '-1'  where id = ".$_COOKIE['mir_id']);
	}
	else $q = mysql_query("update info set gosnom = '".$_POST['let1']." ".$_POST['num']." ".$_POST['let2'].$_POST['let3']." (".$_POST['region'].")'  where id = ".$_COOKIE['mir_id']);
	header("Location: profile.php");
	exit;
}
elseif($_GET['act'] == "ajaxsavepage")
{
	$query = "update info set layout = '".str_replace("NaN", "0", substr($_GET['layout'], 0, -1))."' where id = ".$_COOKIE['mir_id'];
	mysql_query($query);
	exit;
}
elseif($_GET['act'] == "options")
{
	/*
	if(!isset($_GET['i']))
	{
		header("Location: profile.php");
		exit;
	}
	$_GET['field'] = iconv('UTF-8', 'windows-1251', trim($_GET['field']));
	if($_GET['field'] != "")
	{
		if($_GET['i'] == 0)
		{
			$q = mysql_query("select options from info where id = ".$_COOKIE['mir_id']);
			$suf = "Ÿ";
			if(mysql_result($q, 0) == "") $suf = "";
			mysql_query("update info set options = concat(options, '".$suf.nl2br(htmlspecialchars($_GET['field']))."') where id = ".$_COOKIE['mir_id']);
		}
		else
		{
			$q = mysql_query("select options from info where id = ".$_COOKIE['mir_id']);
			$options = mysql_result($q, 0);
			if($options != "")
			{
				$parts = explode("Ÿ", $options);
				$parts[$_GET['i']-1] = nl2br(htmlspecialchars($_GET['field']));
				$options = implode("Ÿ", $parts);
				mysql_query("update info set options = '".$options."' where id = ".$_COOKIE['mir_id']);
			}
		}
	}
	elseif($_GET['i'] != 0)
	{
		$q = mysql_query("select options from info where id = ".$_COOKIE['mir_id']);
		$options = mysql_result($q, 0);
		if($options != "")
		{
			$parts = explode("Ÿ", $options);
			unset($parts[$_GET['i']-1]);
			$options = implode("Ÿ", $parts);
			mysql_query("update info set options = '".$options."' where id = ".$_COOKIE['mir_id']);
		}
	}
	*/
	//$q = mysql_query("select options from info where id = ".$_COOKIE['mir_id']);
	//if(mysql_result($q, 0) == "") $suf = "";
	$text = iconv('UTF-8', 'windows-1251', $_GET['text']);
	$text = strip_tags($text, "<p><b><i><font>");
	$text = ereg_replace("<.* on.*>.*<\/.*>", "", $text);
	mysql_query("update info set options = '".nl2br($text)."' where id = ".$_COOKIE['mir_id']);
	header("Location: profile.php");
	exit;
}


include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>