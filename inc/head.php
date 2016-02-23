<?php
header("Content-type: text/html; charset=utf-8");
$qs = mysql_query("select id from messages where recepient = ".$_COOKIE['2ndw_userid']." and seen = 0");
$snum = mysql_num_rows($qs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="imagetoolbar" content="no"/>
<title><?php print $page_title ?></title>
<meta name="Keywords" content="второй, мир, сеть, карточки, социальная сеть, друзья, фотографии"/>
<meta name="Description" content="Второй Мир"/>
<link rel="shortcut icon" href="images/favicon.ico"/>
<link rel="stylesheet" href="styles/layout.css" type="text/css"/>
<link rel="stylesheet" href="styles/cards.css" type="text/css"/>
<?php if(basename($_SERVER['PHP_SELF']) == "mail.php") print '<link rel="stylesheet" href="styles/mail.css" type="text/css"/>' ?>
</head>
<body>
<div class="main">
	<table class="head"><tr><td class="head">
		<div class="logo">
			<a href="index.php"><img class="img" src="images/logo.gif" alt="Логотип"/><img class="text" src="images/logo_text.gif" alt="Второй Мир"/></a>
			<div class="slogan"><span>Всё дело в карточках</span></div>
		</div>
		<div class="head_right">
			<div class="menu">
				<a href="card.php">Моя визитка</a>
				<a href="mail.php?act=inlist">Сообщения <span id='leftmesnum'><?php if($snum > 0) print "[".$snum."]"; ?></span></a>
				<a href="index.php?act=logout">Выход</a>
			</div>
		</div>
	</td></tr></table>
	<div class="right_column">