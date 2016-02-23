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
<title>Вторая Россия</title>
<meta name="Keywords" content="второй, мир, сеть, социальная сеть, друзья, фотографии"/>
<meta name="Description" content="Социальная сеть Второй Мир"/>
<link rel="shortcut icon" href="../images/favicon.ico"/>
<link rel="stylesheet" href="../styles/layout.css" type="text/css"/>
<link rel="stylesheet" href="../styles/typography.css" type="text/css"/>
<link rel="stylesheet" href="../styles/my.css" type="text/css"/>
<!--[if IE]><link rel="stylesheet" href="../styles/ie.css" type="text/css" /><![endif]-->
</head>
<body>

<div class="main">
	<div class="carcass">
		<table class="head"><tr><td class="head">
			<div class="logo_2ndrussia">
				<a href="profile.php"><img class="img" src="../images/2ndrussia_logo.gif" alt="Логотип"/><img class="text" src="../images/2ndrussia_logo_text.gif" alt="Вторая Россия"/></a>
				<div class="slogan"><span>Политическая игра [beta]</span></div>
			</div>
			<div class="head_right">
				<div class="search"><input disabled="disabled" type="text" value="Поиск" alt=""/></div>
				<div class="menu">
					<a href="../profile.php">Второй Мир</a>
				</div>
			</div>
		</td></tr></table>
		<div class="left_column">
			<div class="menu2">
				<a href="job.php?act=deault" class="activation"><ins>Работа</ins></a>
			</div>
			<div class="course">
				<div class="title"></div>
				<div class="other"></div>
			</div>
		</div>
		<div class="right_column">