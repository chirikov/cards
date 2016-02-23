<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");
if(loginned() == true && !isset($_GET['force']))
{
	header("Location: profile.php");
	exit;
}

$profilehref = "";
if(loginned() == true && isset($_GET['force']))
{
	$qname = mysql_query("select surname, name from users where id = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($qname);
	$profilehref = "<a href='profile.php'>".$row['name']." ".$row['surname']."</a>";
}
if($_GET['act'] != "register" && $_GET['act'] != "activate") $_GET['act'] = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251"/>
<meta http-equiv="imagetoolbar" content="no"/>
<title>Второй Мир</title>
<meta name="Keywords" content="Второй мир, второй, мир, социальная сеть, сеть, функциональная сеть"/>
<meta name="Description" content="Первая функциональная сеть Второй Мир"/>
<link rel="shortcut icon" href="images/favicon.ico"/>
<link rel="stylesheet" href="styles/layout.css" type="text/css"/>
<link rel="stylesheet" href="styles/typography.css" type="text/css"/>
<link rel="stylesheet" href="styles/my.css" type="text/css"/>
<!--[if IE]><link rel="stylesheet" href="styles/ie.css" type="text/css" /><![endif]-->
<script language="javascript" type="text/javascript" src="inc/indexajax.js"></script>
</head>
<body>
<div class="main">
	<div class="carcass">
		<table class="head"><tr><td class="head">
			<div class="logo">
				<a href="index.php?force"><img class="img" src="images/logo.gif" alt="Логотип"/><img class="text" src="images/logo_text.gif" alt="Второй Мир"/></a>
				<div class="slogan"><span>Живи...</span></div>
			</div>
			<div class="head_right">
				<div class="search"><input type="text" disabled="disabled" value="Поиск" alt=""/></div>
				<div class="menu">
					<?php print $profilehref; ?>
					<a href="2ndRussia/index.php">Вторая Россия</a>
					<a href="#" onclick="javascript: main();">Главная</a>
				</div>
			</div>
		</td></tr></table>
		<div class="left_column">
			<div class="menu2">
				<a href="#" onclick="javascript: register();" class="regging"><ins>Регистрация</ins></a>
				<a href="#" onclick="javascript: activate();" class="activation"><ins>Активация</ins></a>
			</div>
			<div class="population">
			</div>
		</div>
		<div class="right_column">
			<div id="main" class="start" <?php if($_GET['act'] == "register" || $_GET['act'] == "activate") print "style='display: none;'"; ?>>
				<div class="start_text">
					<h1>Добро пожаловать во Второй Мир!</h1>
					<p><strong>Второй Мир</strong> - это совершенно новый бесплатный проект, объединяющий в себе все лучшее для Вашего удобства. Сочетая современные технологии и идеи, Второй Мир
					создан, чтобы делать Вашу жизнь легче.<br/><br/>
					Кто-то назовет это "очередной социальной сетью".<br/>Мы называем Второй Мир <strong>первой функциональной сетью</strong>.</p>
					<p style="padding-bottom:0">Во Втором Мире вы сможете:</p>
					<ul>
						<li>всегда быть на связи с Вашими родными и друзьями;</li>
						<li>обмениваться сообщениями без перезагрузки страницы;</li>
						<li>отмечать себя и видеть остальных на карте всей планеты;</li>
						<li>выкладывать фотографии и заказывать их печать;</li>
						<li>смотреть и добавлять видеоролики и <strong>зарабатывать деньги</strong>;</li>
						<li>знакомиться, зная номер автомобиля;</li>
						<li>настраивать вид и функциональность своей страницы и многое другое...</li>
					</ul>
					Подробнее о Втором Мире можно узнать в <a href="help/index.php" class="guide">Путеводителе</a>.
				</div>
				<div class="start_form">
					<div class="top_corners"><i>&nbsp;</i></div>
					<h2>Вход во Второй Мир</h2>
					<div class="resultt" id="result1">&nbsp;</div>
					<div class="start_form_pad"><form method="post" id="flogin" name="flogin" action="javascript: logindone();" onkeypress="javascript: if(event.keyCode == 13) logindone();">
						<table class="form_table"><!--[if IE 6]><col style="width:70px"/><col style="width:157px"/><col style="width:100px"/><![endif]-->
							<tr>
								<td><label>E-mail:</label></td>
								<td><input type="text" name="email" maxlength="50"/></td>
								<td><a href="#" onclick="javascript: register();">Регистрация</a></td>
							</tr>
							<tr>
								<td><label>Пароль:</label></td>
								<td><input type="password" name="pass" maxlength="50"/></td>
								<td></td>
							</tr>
						</table>
						<div class="enter"><a href="#" onclick="javascript: logindone();">Войти</a></div>
						<div class="other_computer"><img class="wait" id="wait1" src="images/wait.gif" alt=""/></div></form>
					</div>
					<div class="bottom_corners"><i>&nbsp;</i></div>
				</div>
			</div>
			
			<div id="register" <?php if($_GET['act'] != "register") print "style='display: none;'"; ?> class="start">
				<div class="start_text">
					<h1>Регистрация</h1>
					<p><strong>Регистрация</strong> необходима для того, чтобы Вы могли воспользоваться всеми функциями проекта.<br/><br/>
					Заполните, пожалуйста, все поля.</p>
				</div>
				<div class="start_form" style="width: 330px">
					<div class="top_corners"><i>&nbsp;</i></div>
					<div class="resultt" id="result2">&nbsp;</div>
					<div class="start_form_pad"><form method="post" id="f1" name="f1" action="javascript: regdone();" onkeypress="javascript: if(event.keyCode == 13) regdone();">
						<table class="form_table"><!--[if IE 6]><col style="width:70px"/><col style="width:157px"/><col style="width:100px"/><![endif]-->
							<tr>
								<td><label>Имя:</label></td>
								<td><input type="text" name="name" maxlength="40"/></td>
							</tr>
							<tr>
								<td><label>Фамилия:</label></td>
								<td><input type="text" name="surname" maxlength="40"/></td>
							</tr>
							<tr>
								<td><label>Пол:</label></td>
								<td><select name="sex">
								<option value="m">Муж.</option>
								<option value="f">Жен.</option>
								</select></td>
							</tr>
							<tr>
								<td><label>Город:</label></td>
								<td>
								<select name="city">
<?php
$q1 = mysql_query("select id, name from cities where 1");
while($arr = mysql_fetch_assoc($q1))
{
	print '<option value="'.$arr['id'].'">'.$arr['name'].'</option>';
}
?>
								</select>
								</td>
								<td></td>
							</tr>
							<tr>
								<td><label>Дата&nbsp;рождения:</label></td>
								<td></td>
							</tr>
							<tr>
								<td><label>День:</label></td>
								<td>
								<select name="day" id="selday">
<?php
for($i=1; $i<=31; $i++)
{
	print '<option id="opt'.$i.'" value="'.$i.'">'.$i.'</option>';
}
?>
								</select>
								</td>
							</tr>
							<tr>
								<td><label>Месяц:</label></td>
								<td>
								<select name="month" onchange="javascript: selchg();">
<?php
$mons = array("@", "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
for($i=1; $i<=12; $i++)
{
	print '<option value="'.$i.'">'.$mons[$i].'</option>';
}
?>
								</select>
								</td>
							</tr>
							<tr>
								<td><label>Год:</label></td>
								<td>
								<select name="year" id="selyear" onchange="javascript: selchg();">
<?php
for($i=2002; $i>=1920; $i--)
{
	print '<option ';
	if($i == 1988) print 'selected="selected" ';
	print 'value="'.$i.'">'.$i.'</option>';
}
?>
								</select>
								</td>
							</tr>
							<tr>
								<td><label>E-mail:</label></td>
								<td><input type="text" name="email" value="<?php print $_GET['email']; ?>" maxlength="50"/></td>
							</tr>
							<tr>
								<td><label>Пароль:</label></td>
								<td><input type="password" name="pass" maxlength="50"/></td>
							</tr>
							<tr>
								<td><label>Подтвердите&nbsp;пароль:</label></td>
								<td><input type="password" name="pass2" maxlength="50"/></td>
							</tr>
						</table>
						<div class="enter"><a href="#" onclick="javascript: regdone();">Регистрация</a></div>
						<div class="other_computer"><img class="wait" id="wait2" src="images/wait.gif" alt=""/></div>
						</form>
					</div>
					<div class="bottom_corners"><i>&nbsp;</i></div>
				</div>
			</div>
			
			<div id="activate" <?php if($_GET['act'] != "activate") print "style='display: none;'"; ?> class="start">
				<div class="start_text">
					<h1>Активация</h1>
					<p><strong>Активация</strong> необходима для проверки существования указанного Вами E-mail.</p>
				</div>
				<div class="start_form">
					<div class="top_corners"><i>&nbsp;</i></div>
					<div class="resultt" id="result3">&nbsp;</div>
					<div class="start_form_pad"><form method="post" id="f2" name="f2" action="javascript: actdone();" onkeypress="javascript: if(event.keyCode == 13) actdone();">
						<table class="form_table"><!--[if IE 6]><col style="width:70px"/><col style="width:157px"/><col style="width:100px"/><![endif]-->
							<tr>
								<td><label>E-mail:</label></td>
								<td><input type="text" name="email" value="<?php print $_GET['email'] ?>" maxlength="50"/></td>
								<td></td>
							</tr>
							<tr>
								<td><label>Код&nbsp;активации:</label></td>
								<td><input type="text" name="actcode" maxlength="6"/></td>
								<td><a href="#" onclick="javascript: sendactcode();">Отправить код еще раз</a></td>
							</tr>
						</table>
						<div class="enter"><a href="#" onclick="javascript: actdone();">Активировать</a></div>
						<div class="other_computer"><img id="wait3" src="images/wait.gif" class="wait" alt=""/></div>
						</form>
					</div>
					<div class="bottom_corners"><i>&nbsp;</i></div>
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
			<a href="adv/index.php">Реклама</a>
			<a href="help/about.php">О проекте</a>
			<a href="help/index.php">Помощь</a>
		</div>
	</td></tr></table></div></div>
</div>
</body>
</html>