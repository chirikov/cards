<?php
include_once("../inc/my_connect.php");
include_once("inc/control.php");

$content = "";
$menu = "";
$profilehref = "";
$log = loginned();
if(!$log)
{
	$menu = '<a href="../index.php?act=register" class="regging"><ins>Регистрация</ins></a>';
}
elseif($log === "notingame")
{
	$content = '<div class="enter"><a href="settings.php?act=ingame">Играть!</a></div>';
}
else
{
	$q = mysql_query("select surname, name from users where id = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($q);
	$profilehref = '<a href="profile.php">'.$row['name'].' '.$row['surname'].'</a>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251"/>
<meta http-equiv="imagetoolbar" content="no"/>
<title>Вторая Россия</title>
<meta name="Keywords" content="Вторая Россия, игра, второй мир, второй, мир, социальная сеть, сеть, функциональная сеть"/>
<meta name="Description" content="Политическая игра Вторая Россия"/>
<link rel="shortcut icon" href="../images/favicon.ico">
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
				<a href="index.php?force"><img class="img" src="../images/2ndrussia_logo.gif" alt="Логотип"/><img class="text" src="../images/2ndrussia_logo_text.gif" alt="Вторая Россия"/></a>
				<div class="slogan"><span>Политическая игра</span></div>
			</div>
			<div class="head_right">
				<div class="search"><input type="text" disabled value="Поиск" alt=""/></div>
				<div class="menu">
					<?php print $profilehref; ?> <a href="../index.php?force">Второй Мир</a>
				</div>
			</div>
		</td></tr></table>
		<div class="left_column">
			<div class="menu2">
				<?php print $menu; ?>
			</div>
			<div class="population">
			</div>
		</div>
		<div class="right_column">
			<div class="start">
				<div class="start_text">
					<h1>Добро пожаловать во Вторую Россию!</h1>
					<p><strong>Вторая Россия</strong> - это бесплатная социально-политическая онлайн-игра, являющаяся моделью реальной жизни в государстве.</p>
					<p style="padding-bottom:0">Во Второй России вы сможете:</p>
					<ul>
						<li>участвовать в политической жизни страны;</li>
						<li>устраиваться на работу и получать зарплату;</li>
						<li>открывать собственный бизнес;</li>
						<li>играть на фондовых и валютных рынках;</li>
						<li>приобретать имущество;</li>
						<li>стать избранным президентом и многое другое...</li>
					</ul>
					Подробнее о Второй России можно узнать в <a href="help/index.php" class="guide">Путеводителе</a>.
				</div>
				<?php
				if($content != "")
				print '
				<div class="start_form">
					<div class="top_corners"><i>&nbsp;</i></div>
					<div class="start_form_pad">
						'.$content.'
					</div>
					<div class="bottom_corners"><i>&nbsp;</i></div>
				</div>';
				?>
			</div>
		</div>
		<div class="clear_line">&nbsp;</div>
	</div>
	<div class="footer"><div class="footer_block"><table class="footer_block"><tr><td>
		<div class="copyright">
			2008—2009 «Второй Мир»<br/>
			Все права защищены.
			<div class="design">Дизайн &mdash; <a target="_blank" href="http://ikari-design.ru">студия «Корона дизайна»</a></div>
		</div>
		<div class="menu3">
			<a href="help/about.php">О проекте</a>
			<a href="help/index.php">Помощь</a>
		</div>
	</td></tr></table></div></div>
</div>
</body>
</html>