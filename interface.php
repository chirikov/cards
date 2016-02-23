<?php
header("Content-type: text/html; charset=utf-8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="imagetoolbar" content="no"/>
<title>Второй Мир</title>
<meta name="Keywords" content="второй, мир, сеть, социальная сеть, друзья, фотографии"/>
<meta name="Description" content="Социальная сеть Второй Мир"/>
<link rel="shortcut icon" href="images/favicon.ico"/>
<link rel="stylesheet" href="styles/layout.css" type="text/css"/>
<link rel="stylesheet" href="styles/cards.css" type="text/css"/>
</head>
<body>
<div class="main">
	<div class="carcass">
		<table class="head"><tr><td class="head">
			<div class="logo">
				<a href="profile.php"><img class="img" src="images/logo.gif" alt="Логотип"/><img class="text" src="images/logo_text.gif" alt="Второй Мир"/></a>
				<div class="slogan"><span>Всё дело в карточках</span></div>
			</div>
			<div class="head_right">
				<div class="menu">
					<a href="index.php?force">Главная</a>
					<a href="login.php?act=logout">Выход</a>
				</div>
			</div>
		</td></tr></table>
		<div class="right_column">
				<script type="text/javascript" src="inc/variants.js"></script>
				<script type="text/javascript">
				//var def_cities = "7700000000000|Москва;7800000000000|Санкт-Петербург;5400000100000|Новосибирск;6600000100000|Екатеринбург;5200000100000|Нижний Новгород;6300000100000|Самара;5500000100000|Омск;1600000100000|Казань;7400000100000|Челябинск;6100000100000|Ростов-на-Дону;0200100100000|Уфа";
				Variants.defs['city'] = "7700000000000|Москва;7800000000000|Санкт-Петербург;5400000100000|Новосибирск;6600000100000|Екатеринбург;5200000100000|Нижний Новгород;6300000100000|Самара;5500000100000|Омск;1600000100000|Казань;7400000100000|Челябинск;6100000100000|Ростов-на-Дону;0200100100000|Уфа";
				Variants.acts['city'] = "givecities";
				Variants.value_elements['city'] = "city";
				Variants.text_elements['city'] = "vars_city";
				</script>
				<input type="hidden" name="city" id="city">
				<input type="text" class="inpvars" id="vars_city"><br><div id="variants_city_container"></div><br>
				<input type="submit" onclick="alert(document.getElementById('city').value);">
		</div>
		<div class="clear_line">&nbsp;</div>
	</div>
	<div class="footer"><div class="footer_block"><table class="footer_block"><tr><td>
		<div class="copyright">
			2008—2009 «<a href="index.php?force">Второй Мир</a>»<br/>
			Все права защищены.
			<div class="design">Дизайн &mdash; <a target="_blank" href="http://ikari-design.ru">студия «Корона дизайна»</a></div>
		</div>
		<div class="menu3">
			<a href="adv/index.php">Реклама</a>
			<a href="help/about.php">О проекте</a>
			<a href="help/index.php">Помощь</a>
		</div>
	</td></tr></table></div></div>
</div>
</body>
</html>