<?php
$qs = mysql_query("select id from messages where recepient = ".$_COOKIE['mir_id']." and seen = 0");
$snum = mysql_num_rows($qs);
$qslogan = mysql_query("select text from slogans limit ".rand(0, 3).", 1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251"/>
<meta http-equiv="imagetoolbar" content="no"/>
<title>������ ���</title>
<meta name="Keywords" content="������, ���, ����, ���������� ����, ������, ����������"/>
<meta name="Description" content="���������� ���� ������ ���"/>
<link rel="shortcut icon" href="images/favicon.ico"/>
<link rel="stylesheet" href="styles/layout.css" type="text/css"/>
<link rel="stylesheet" href="styles/typography.css" type="text/css"/>
<link rel="stylesheet" href="styles/my.css" type="text/css"/>
<!--[if IE]><link rel="stylesheet" href="styles/ie.css" type="text/css" /><![endif]-->
</head>
<body
<?php
if(basename($_SERVER['PHP_SELF']) == "map.php") print "onload='javascript: init();'";
elseif(basename($_SERVER['PHP_SELF']) == "profile.php" && $me) print "onload='javascript: setInterval(\"checkmes();\", 5000);'";
elseif(basename($_SERVER['PHP_SELF']) == "mail.php" && ($_GET['act'] == "inlist" || ($_GET['act'] == "talks" && $_GET['uid'] != ""))) print "onload='javascript: setInterval(\"checkmes();\", 5000);'";
?>
>

<div class="main">
	<div class="carcass">
		<table class="head"><tr><td class="head">
			<div class="logo">
				<a href="profile.php"><img class="img" src="images/logo.gif" alt="�������"/><img class="text" src="images/logo_text.gif" alt="������ ���"/></a>
				<div class="slogan"><span><?php print mysql_result($qslogan, 0); ?></span></div>
			</div>
			<div class="head_right">
				<div class="search"><input disabled="disabled" type="text" value="�����" alt=""/></div>
				<div class="menu">
					<a href="index.php?force">�������</a>
					<a href="2ndRussia/profile.php">������ ������</a>
					<a href="login.php?act=logout">�����</a>
				</div>
			</div>
		</td></tr></table>
		<div class="left_column">
			<div class="menu2">
				<a href="contacts.php?act=deault" class="contacts"><ins>��������</ins></a>
				<a href="mail.php?act=inlist" class="messages"><ins>��������� <span id='leftmesnum' style="padding: 0;"><?php if($snum > 0) print "[".$snum."]"; ?></span></ins></a>
				<a href="photo.php?act=albums" class="photos"><ins>����������</ins></a>
				<a href="map.php?act=default" class="politic"><ins>�����</ins></a>
			</div>
			<div class="course">
<?php if(basename($_SERVER['PHP_SELF']) == "profile.php") print '<div class="title">��������:</div>'.tagcloud($_GET['uid']); ?>
				<div class="other"></div>
			</div>
		</div>
		<div class="right_column">