<?php
include_once("../inc/my_connect.php");
include_once("inc/control.php");

$profilehref = "";
$log = loginned();
if($log)
{
	$q = mysql_query("select surname, name from users where id = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($q);
	$profilehref = '<a href="../profile.php">'.$row['name'].' '.$row['surname'].'</a>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251"/>
<meta http-equiv="imagetoolbar" content="no"/>
<title>Второй Мир</title>
<meta name="Keywords" content="Вторая Россия, игра, второй мир, второй, мир, социальная сеть, сеть, функциональная сеть"/>
<meta name="Description" content="Второй Мир"/>
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
			<div class="logo">
				<a href="../index.php?force"><img class="img" src="../images/logo.gif" alt="Логотип"/><img class="text" src="../images/logo_text.gif" alt="Второй Мир"/></a>
				<div class="slogan"><span>Реклама</span></div>
			</div>
			<div class="head_right">
				<div class="search"><input type="text" disabled value="Поиск" alt=""/></div>
				<div class="menu">
					<?php print $profilehref; ?> <a href="../index.php?force">Главная</a>
				</div>
			</div>
		</td></tr></table>
		<div class="left_column">
			<div class="menu2">
				
			</div>
			<div class="population">
			</div>
		</div>
		<div class="right_column">
			<div class="start">
				<div class="start_text">
					<h1>Ваша реклама во Втором Мире</h1>
					<p>Проект <strong>Второй Мир</strong> готовит к запуску новый видеосервис в сети Интернет.
					Он позволит зарегистрированным пользователям просматривать видео, опубликовывать свои ролики, комментировать, голосовать за них и многое другое.
					В российском сегменте Интернета видеосервисы только начинают становиться популярными.</p>
					<p><strong>Как это будет выглядеть?</strong></p>
					<p>Мы предлагаем Вам разместить свой рекламный ролик на нашем сервисе.
					Перед тем, как просмотреть какой-либо видеоролик, пользователь увидит Вашу рекламу (он не сможет смотреть ролик без полного просмотра рекламы).
					Рекламный ролик не должен длиться более четырех секунд.</p>
					<img src="../images/video_web.jpg" alt="Вид проигрывателя и первый кадр рекламного ролика."><br><br>
					<p><strong>Сколько это стоит?</strong></p>
					<p>Вы платите только за показанную рекламу, то есть пропорционально количеству просмотров Вашего ролика.
					Один показ Вашей рекламы стоит <strong>5 рублей</strong>. Вы не платите за показ своей рекламы одному и тому же пользователю более трех раз за сутки (во избежание накрутки числа показов).
					Если у Вас нет своего ролика, мы Вам его изготовим (стоимость зависит от сложности изготовления).</p>
					<p><strong>Кто увидит?</strong></p>
					<p>Вы можете сами выбрать целевую аудиторию.
					На данный момент доступен таргетинг по городам, возрасту и полу (например, Вы сможете заказать показ рекламы только жительницам Уфы старше 35 лет).</p>
					<p><strong>Почему мы?</strong></p>
					<p>Сервис быстро наберет популярность, так как мы будем платить пользователям за просмотр и опубликование видеороликов.</p>
					<p>Вы можете скачать наше коммерческое предложение <a href="2ndworld_video_offer.doc">здесь</a>.</p>
					<p><strong>Как с нами связаться?</strong></p>
					<p style="padding-bottom: 0px">Тел.: 8-937-3000-120</p>
					<p>E-mail: <a href="mailto:offer@2ndworld.ru">offer@2ndworld.ru</a>.</p>
				</div>
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
			<a href="../help/about.php">О проекте</a>
			<a href="../help/index.php">Помощь</a>
		</div>
	</td></tr></table></div></div>
</div>
</body>
</html>