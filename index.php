<?php

header("Content-type: text/html; charset=utf-8");
include_once("inc/my_connect.php");
include_once("inc/control.php");

function widget_output($id, $wb=true)
{
	$q = mysql_query("select folder, name from widgets where id = ".$id." limit 1");
	$wid = mysql_fetch_assoc($q);
	$ret = "";
	if($wb) $ret .= '<div id="card'.$id.'" class="card" style="width: 236px; position: relative; float: left" onmousedown="javascript: Layout.StartDrag(event, this);">';
	$ret .= '<div class="title">
			<div class="left"></div>
			<div class="right"><div class="win_close" onclick="remove_card('.$id.');"></div></div>
			'.$wid['name'].'
		</div>
		<div class="body" style="height: 170px; background: #FEFEFE">
		<iframe scrolling="No" style="border: 0" frameborder="0" src="widgets/'.$wid['folder'].'/'.$wid['folder'].'.php" width="234" height="170"></iframe>
		</div>
		<div class="title">
			<div class="left" id="foot'.$id.'" style="padding-left: 3px"></div>
			<div class="right"><div class="wait" id="wait'.$id.'"></div></div>
		</div>';
	if($wb) $ret .= '</div>';
	return $ret;
}

if($_GET['act'] == "activate")
{
	$email = trim($_GET['email']);
	$code = trim($_GET['code']);
	$result = "";
	
	$q1 = mysql_query("select id, pass, actcode from people where email = '".$email."' limit 1");
	if(mysql_num_rows($q1) == 0) $result = "eemail";
	else
	{
		$dbcode = mysql_result($q1, 0, 'actcode');
		if($dbcode == 0) $result = "eactive";
		elseif($dbcode == $code)
		{
			$q2 = mysql_query("update people set actcode = 0 where email = '".$email."' limit 1");
			$id = mysql_result($q1, 0, 'id');
			$pass = mysql_result($q1, 0, 'pass');
			mkdir("photos/".$id, 0766);
			setcookie("2ndw_userid", $id, time()+60*60*24*2);
			setcookie("2ndw_pass", $pass, time()+60*60*24*2);
			if($_GET['type'] == "email") header("Location: card.php");
			else $result = "ok";
		}
		else $result = "ecode";
	}
	if($_GET['type'] == "ajax") print $result;
	elseif($result != "") header("Location: index.php");
	exit;
}
elseif($_GET['act'] == "sendcode")
{
	$email = trim($_GET['email']);
	$q1 = mysql_query("select name, surname, actcode from people where email = '".$email."' limit 1");
	$row = mysql_fetch_assoc($q1);
	if(mysql_num_rows($q1) == 0) $result = "eemail";
	else
	{
		if($row['actcode'] == 0) $result = "eactive";
		else
		{
			$to = $row['name']." ".$row['surname']." <".$email.">";
			$from = "Второй Мир <noreply@2ndworld.ru>";
			$sub = "Код активации";
			$headers = "From: ".$from."\nMIME-Version: 1.0\nContent-type: text/html; charset=utf-8\n";
			$body1 = "
			<html>
			<body>
			Напоминаем Вам код активации Вашего E-mail: ".$row['actcode']."
			<br>
			Введите его в <a href='http://2ndworld.ru/index.php'>форме активации</a> на сайте или просто перейдите по следующей ссылке:<br><br>
			<a href='http://2ndworld.ru/index.php?act=activate&type=email&email=".$email."&code=".$row['actcode']."'>http://2ndworld.ru/index.php?act=activate&type=email&email=".$email."&code=".$row['actcode']."</a><br><br>
			<i>С уважением,<br>
			Второй Мир</i>
			</body>
			</html>
			";
			if(mail($to, $sub, $body1, $headers)) $result = "ok";
			else $result = "eemailsend";
		}
	}
	print $result;
	exit;
}
elseif($_GET['act'] == "recovery")
{
	$email = trim($_GET['email']);
	
	$q = mysql_query("select name, surname, actcode from people where email = '".$email."' limit 1");
	if(mysql_num_rows($q) == 0) print "eemail";
	else
	{
		$row = mysql_fetch_assoc($q);
		if($row['actcode'] == 0)
		{
			$ar = array_merge(range('a', 'z'), range('1', '9'));
			shuffle($ar);
			$newpassword = substr(implode("", $ar), rand(0, 28), 7);
			
			$q = mysql_query("update people set pass = '".md5($newpassword)."' where email = '".$email."' limit 1");
			$to = $row['name']." ".$row['surname']." <".$email.">";
			$from = "Второй Мир <noreply@2ndworld.ru>";
			$sub = "Восстановление пароля";
			$headers = "From: ".$from."\nMIME-Version: 1.0\nContent-type: text/html; charset=utf-8\n";
			$body1 = "
			<html>
			<body>
			<h3>Восстановление пароля во <a href='http://2ndworld.ru/index.php'>Втором Мире</a>.</h3><br>
			<br>
			Ваш новый пароль: ".$newpassword."<br><br>
			Вы можете поменять его в настройках аккаунта.
			Используйте его при входе на <a href='http://2ndworld.ru/index.php'>сайт</a> или просто перейдите по следующей ссылке:<br><br>
			<a href='http://2ndworld.ru/index.php?act=login&email=".$email."&pass=".$newpassword."'>http://2ndworld.ru/index.php?act=login&email=".$email."&pass=".$newpassword."</a><br><br>
			<i>С уважением,<br>
			Второй Мир</i>
			</body>
			</html>
			";
			if(mail($to, $sub, $body1, $headers)) print "ok";
			else print "eemailsend";
		}
		else print "eunactive";
	}
	exit;
}
elseif($_GET['act'] == "login")
{
	$email = trim($_GET['email']);
	$pass = trim($_GET['pass']);
	
	$q1 = mysql_query("select id, pass, actcode from people where email = '".$email."' limit 1");
	if(mysql_num_rows($q1) == 0) $result = "eemail";
	else
	{
		$row = mysql_fetch_assoc($q1);
		
		if($row['actcode'] != 0) $result = "eunactive";
		elseif($row['pass'] != md5($pass)) $result = "epass";
		else
		{
			setcookie("2ndw_userid", $row['id'], time()+60*60*24*2);
			setcookie("2ndw_pass", $row['pass'], time()+60*60*24*2);
			$result = "ok";
		}
	}
	print $result;
	exit;
}
elseif($_GET['act'] == "logout")
{
	$q = mysql_query("update people set lasttime = ".(time()-5*60)." where id = ".$_COOKIE['2ndw_userid']);
	setcookie("2ndw_userid", "", time()-3600);
	setcookie("2ndw_pass", "", time()-3600);
	header("Location: index.php");
	exit;
}
elseif($_GET['act'] == "register")
{
	$surname = trim(substr($_POST['surname'], 0, 40));
	$name = trim(substr($_POST['name'], 0, 40));
	$email = trim(substr($_POST['email'], 0, 50));
	$pass = trim(substr($_POST['pass'], 0, 50));
	
	if(!is_valid("ru,en", $name)) $result = "ename";
	elseif(!is_valid("surname", $surname)) $result = "esurname";
	elseif(!is_valid("email", $email)) $result = "eemail";
	elseif(strlen($pass) < 6) $result = "epass";
	else
	{
		$q4 = mysql_query("select id, actcode from people where email = '".$email."' limit 1");
		$regd = mysql_num_rows($q4);
		if($regd > 0)
		{
			if(mysql_result($q4, 0, 'actcode') != 0) $result = "eemailexistsunact";
			else $result = "eemailexists";
		}
		else
		{
			$actcode = rand(100000, 999999);
			$qi = mysql_query("insert into cards (type) values (1)");
			$iid = mysql_insert_id($mysql);
			$qi = mysql_query("update cards set owner = id where id = LAST_INSERT_ID() limit 1");
			$deftabs = '[{"title":"Основное","itemsnum":3,"items":{"name":{"id":"name","x":20,"y":20,"style":"color: #c20000; font-size: 20px;","nt":true},"surname":{"id":"surname","x":60,"y":40,"style":"color: #c20000; font-size: 20px;","nt":true},"photo":{"id":"photo","x":730,"y":-50}}}]';
			$q = mysql_query("insert into people (id, surname, name, email, pass, tabs, actcode, regtime) values (".$iid.", '".addslashes(htmlspecialchars($surname))."', '".addslashes(htmlspecialchars($name))."', '".addslashes(htmlspecialchars($email))."', '".md5($pass)."', '".$deftabs."', ".$actcode.", ".time().")");
			if($q) {
				$to = $name." ".$surname." <".$email.">";
				$from = "Второй Мир <noreply@2ndworld.ru>";
				$sub = "Регистрация в проекте Второй Мир";
				$headers = "From: ".$from."\nMIME-Version: 1.0\nContent-type: text/html; charset=utf-8\n";
				$body1 = "
				<html>
				<body>
				<h3>Спасибо за регистрацию во <a href='http://2ndworld.ru/index.php'>Втором Мире</a>.</h3><br>
				<br>
				Ваш код активации: ".$actcode."<br>
				Введите его в <a href='http://2ndworld.ru/index.php'>форме активации</a> на сайте или просто перейдите по следующей ссылке:<br><br>
				<a href='http://2ndworld.ru/index.php?act=activate&type=email&email=".$email."&code=".$actcode."'>http://2ndworld.ru/index.php?act=activate&type=email&email=".$email."&code=".$actcode."</a><br><br>
				<i>С уважением,<br>
				Второй Мир</i>
				</body>
				</html>
				";
				if(mail($to, $sub, $body1, $headers)) $result = "ok";
				else $result = "eemailsend";
			}
			else $result = "ereg";
		}
	}
	print $result;
	exit;
}
elseif($_GET['act'] == "retrievecard")
{
	$qt = mysql_query("select type from cards where id = ".$_GET['id']." limit 1");
	$type = mysql_result($qt, 0);
	if($type == 6) print widget_output($_GET['id'], false);
	if(!isset($_COOKIE["2ndw_unregid"]))
	{
		$qn = mysql_query("select COUNT(*) from unreg where 1");
		$id = md5(mysql_result($qn, 0) + 1);
		$toshow = array(500,501,502,503,504,505,$_GET['id']);
		$toshow = json_encode($toshow);
		$qu = mysql_query('insert into unreg values("'.$id.'", "'.mysql_escape_string($toshow).'")');
		setcookie("2ndw_unregid", $id, time()+60*60*24*365);
	}
	else
	{
		$q1 = mysql_query("select tabs from unreg where id = '".$_COOKIE["2ndw_unregid"]."'");
		$tabs = mysql_result($q1, 0);
		$tabs = json_decode($tabs, true);
		$tabs[] = $_GET['id'];
		$tabs = json_encode($tabs);
		$qu = mysql_query('update unreg set tabs = "'.mysql_escape_string($tabs).'" where id = "'.$_COOKIE["2ndw_unregid"].'" limit 1');
	}
	exit;
}
elseif($_GET['act'] == "removecard")
{
	$loginned = loginned();
	if(!isset($_COOKIE["2ndw_unregid"]))
	{
		$qn = mysql_query("select COUNT(*) from unreg where 1");
		$id = md5(mysql_result($qn, 0) + 1);
		if($loginned === true) $toshow = array($_COOKIE["2ndw_userid"],500,501,502,503,504,505);
		else $toshow = array(500,501,502,503,504,505);
		$toshow2 = array();
		foreach($toshow as $c)
		{
			if($c != $_GET['id']) $toshow2[] = $c;
		}
		$toshow2 = json_encode($toshow2);
		$qu = mysql_query('insert into unreg values("'.$id.'", "'.mysql_escape_string($toshow2).'")');
		setcookie("2ndw_unregid", $id, time()+60*60*24*365);
		print "ok";
	}
	else
	{
		$q1 = mysql_query("select tabs from unreg where id = '".$_COOKIE["2ndw_unregid"]."'");
		$tabs = mysql_result($q1, 0);
		$tabs = json_decode($tabs, true);
		$ai = array_search($_GET['id'], $tabs);
		unset($tabs[$ai]);
		$tabs = json_encode($tabs);
		$qu = mysql_query('update unreg set tabs = "'.mysql_escape_string($tabs).'" where id = "'.$_COOKIE["2ndw_unregid"].'" limit 1');
		print "ok";
	}
	exit;
}
else
{
	$lrar =  array("login", "registration", "activation", "recovery");
	$defcards = array(500,501,502,503,504,505);
	if(!isset($_COOKIE["2ndw_unregid"])) $toshow = array_merge($lrar, $defcards);
	else
	{
		$q1 = mysql_query("select tabs from unreg where id = '".$_COOKIE["2ndw_unregid"]."'");
		$tabs = mysql_result($q1, 0);
		$toshow = json_decode($tabs, true);
		$toshow = array_merge($lrar, $toshow);
		setcookie("2ndw_unregid", $_COOKIE["2ndw_unregid"], time()+60*60*24*365);
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="imagetoolbar" content="no"/>
<title>Второй Мир</title>
<meta name="Keywords" content="второй, мир, сеть, карточки, социальная сеть, друзья, фотографии"/>
<meta name="Description" content="Второй Мир"/>
<link rel="shortcut icon" href="images/favicon.ico"/>
<link rel="stylesheet" href="styles/layout.css" type="text/css"/>
<link rel="stylesheet" href="styles/cards.css" type="text/css"/>
<script type="text/javascript" src="inc/layout.js"></script>
<script type="text/javascript" src="inc/index.js"></script>
<script type="text/javascript" src="inc/3dscene.js"></script>
</head>
<body>
<script type="text/javascript">
Layout.gridx = 250;
Layout.gridy = 216;
Layout.grid = true;
Layout.classname = "card";
Layout.parent = window.document.body;
var vid = "flat";
Scene.autoradius = true;
Scene.radius = 120;

function sceneswitch()
{
	 if(vid == 'flat')
	 {
	 	pagechanging = false;
	 	document.getElementById("sceneswitchhref").innerHTML = 'Обычный';
		vid = '3d';
		document.getElementById("3dscene0").id = "3dscene";
	 	Scene.install();
	 }
	 else
	 {
	 	pagechanging = true;
		vid = 'flat';
		document.getElementById("sceneswitchhref").innerHTML = '2.5D';
		document.getElementById("3dscene").id = "3dscene0";
		Scene.unsetElements();
	 }
}
</script>
<div class="main">
	<table class="head"><tr><td class="head">
		<div class="logo">
			<a href="index.php"><img class="img" src="images/logo.gif" alt="Логотип"/><img class="text" src="images/logo_text.gif" alt="Второй Мир"/></a>
			<div class="slogan"><span>Всё дело в карточках</span></div>
		</div>
		<div class="head_right">
			<div class="menu">
				<a href="javascript: addcards();">+ другие карточки</a>
				<div class="change_interface">
					<a id="changepagehref" class="change" href="javascript: Layout.changepageclick();">Изменить расположение</a>
					<a id="cancelpagehref" style="display: none;" href="javascript: Layout.cancelpage();">Отмена</a>
					<a id="defaultpagehref" style="display: none;" href="javascript: Layout.defaultpage();">По умолчанию</a>
					<a style="display: none;" id="grid" href="javascript: Layout.show_grid();">Сетка</a>
				</div>
				<a id="sceneswitchhref" href="javascript: sceneswitch();">2.5D</a>
			</div>
		</div>
	</td></tr></table>
	<table width="100%"><tr><td width="100%">
	<div id="3dscene0" style="width: 100%; min-height: 442px; float: left" onmousedown="if(vid == '3d') Scene.dragstart(event);">
	<?php
	$i = 5;
	foreach($toshow as $cardid)
	{
		if($cardid == "login")
		{
			?>
	<div id="card1" class="card" style="width: 236px; position: relative; float: left">
		<div class="title" style="cursor: move" onmousedown="javascript: Layout.StartDrag(event, document.getElementById('card1'));">
			<div class="left"></div>
			<div class="right"></div>
			Вход во Второй Мир
		</div>
		<div class="body" style="height: 170px; background: #AEBDFB">
			<table class="center"><tr><td>
				<table class="login_table">
				<form action="javascript: login();" id="form_login">
				<tr><td><input class="text" style="width: 130px" type="text" name="email" value="E-mail" maxlength="50" id="ent_email" onclick="javascript: if(this.value == 'E-mail') this.value = '';" onblur="javascript: if(this.value == '') this.value = 'E-mail';"></td><td><a href="javascript: show_activation();">Активация</a></td></tr>
				<tr><td>
				<input id="passtext" class="text" style="width: 130px" type="text" value="Пароль" onfocus="javascript: this.style.display = 'none'; var a = document.getElementById('passpass'); a.style.display = ''; a.focus();">
				<input id="passpass" class="text" style="width: 130px; display: none" type="password" name="pass" maxlength="50" onblur="javascript: if(this.value == '') {this.style.display = 'none'; var a = document.getElementById('passtext'); a.style.display = '';}"></td><td><a href="javascript: show_reset();">Забыл</a></td></tr>
				<tr><td><input class="text" type="submit" value="Войти" style="width: 100%"></td><td class="smalltext"><input type="checkbox" name="alien" id="alien"> <span onclick="document.getElementById('alien').checked = 1-document.getElementById('alien').checked;" style="cursor: default">Не дома</span></td></tr>
				</form>
				</table>
			</td></tr>
			</table>
		</div>
		<div class="title">
			<div class="left" id="foot1" style="padding-left: 3px"></div>
			<div class="right"><div class="wait" id="wait1"></div></div>
		</div>
	</div>
			<?php
		}
		elseif($cardid == "registration")
		{
			?>
	<div id="card2" class="card" style="width: 236px; position: relative; float: left">
		<div class="title" style="cursor: move" onmousedown="javascript: Layout.StartDrag(event, document.getElementById('card2'));">
			<div class="left"></div>
			<div class="right"></div>
			Регистрация
		</div>
		<div class="body" style="height: 170px; background: #FBEFB2">
		<script language="javascript" type="text/javascript">
var pass1 = '';
function reg_submit()
{
	var form_reg = document.getElementById('form_reg');
	var b = document.getElementById('foot2');
	if(form_reg.name.value.length < 1 || form_reg.name.value == "Имя")
	{
		b.innerHTML = 'Введите имя';
		return false;
	}
	if(form_reg.surname.value.length < 1 || form_reg.surname.value == "Фамилия")
	{
		b.innerHTML = 'Введите фамилию';
		return false;
	}
	if(form_reg.email.value.length < 6 || form_reg.email.value == "E-mail")
	{
		b.innerHTML = 'Введите E-mail';
		return false;
	}
	if((form_reg.pass.value.length < 6 || form_reg.pass.type == "text") && pass1 == '')
	{
		b.innerHTML = 'Слабый пароль';
		return false;
	}
	if(pass1 == '')
	{
		pass1 = form_reg.pass.value;
		form_reg.pass.value = '';
		b.innerHTML = 'Введите пароль еще раз';
		form_reg.pass.focus();
		return false;
	}
	if(pass1 != form_reg.pass.value)
	{
		b.innerHTML = 'Пароли не совпадают';
		pass1 = '';
		form_reg.pass.value = '';
		return false;
	}
}
		</script>
			<table class="center"><tr><td>
			<table class="login_table">
			<form action="javascript: register();" id="form_reg">
			<tr><td style="padding: 3px"><input class="text" type="text" name="name" maxlength="40" value="Имя" onclick="javascript: if(this.value == 'Имя') this.value = '';" onblur="javascript: if(this.value == '') this.value = 'Имя';"></td></tr>
			<tr><td style="padding: 3px"><input class="text" type="text" name="surname" maxlength="40" value="Фамилия" onclick="javascript: if(this.value == 'Фамилия') this.value = '';" onblur="javascript: if(this.value == '') this.value = 'Фамилия';"></td></tr>
			<tr><td style="padding: 3px"><input class="text" type="text" name="email" maxlength="50" value="E-mail" onclick="javascript: if(this.value == 'E-mail') this.value = '';" onblur="javascript: if(this.value == '') this.value = 'E-mail';"></td></tr>
			<tr><td style="padding: 3px"><input id="passtextreg" class="text" type="text" value="Пароль" onfocus="javascript: this.style.display = 'none'; var a = document.getElementById('passpassreg'); a.style.display = ''; a.focus();">
			<input id="passpassreg" class="text" style="display: none" type="password" name="pass" maxlength="50" onblur="javascript: if(this.value == '') {this.style.display = 'none'; var a = document.getElementById('passtextreg'); a.style.display = '';}"></td></tr>
			<tr><td style="padding: 3px"><input class="text" type="submit" value="Зарегистрироваться" style="width: 100%" onclick="javascript: return reg_submit();"></td></tr>
			</form>
			</table>
		</td></tr>
		</table>
		</div>
		<div class="title">
			<div class="left" id="foot2" style="padding-left: 3px">Надо всё заполнить</div>
			<div class="right"><div class="wait" id="wait2"></div></div>
		</div>
	</div>
			<?php
		}
		elseif($cardid == "activation")
		{
			?>
	<div id="card3" class="card" style="width: 236px; position: relative; float: left; display: none">
		<div class="title" style="cursor: move" onmousedown="javascript: Layout.StartDrag(event, document.getElementById('card3'));">
			<div class="left"></div>
			<div class="right"></div>
			Активация
		</div>
		<div class="body" style="height: 170px; background: #C6FCBD">
			<table class="center"><tr><td>
				<table class="login_table">
				<form action="javascript: activate();" id="form_act">
				<tr><td><input class="text" type="text" name="email" value="E-mail" maxlength="50" id="act_email" onclick="javascript: if(this.value == 'E-mail') this.value = '';" onblur="javascript: if(this.value == '') this.value = 'E-mail';"></td></tr>
				<tr><td style="text-align: left"><input class="text" type="text" name="code" value="Код" maxlength="6" style="width: 57px" onclick="javascript: if(this.value == 'Код') this.value = '';" onblur="javascript: if(this.value == '') this.value = 'Код';"> <a href="#" onclick="sendcode();">Прислать код</a></td></tr>
				<tr><td colspan="2"><input class="text" type="submit" value="Активировать" style="width: 100%"></td></tr>
				</form>
				</table>
			</td></tr>
			</table>
		</div>
		<div class="title">
			<div class="left" id="foot3" style="padding-left: 3px"></div>
			<div class="right"><div class="wait" id="wait3"></div></div>
		</div>
	</div>
			<?php
		}
		elseif($cardid == "recovery")
		{
			?>
	<div id="card4" class="card" style="width: 236px; position: relative; float: left; display: none">
		<div class="title" style="cursor: move" onmousedown="javascript: Layout.StartDrag(event, document.getElementById('card4'));">
			<div class="left"></div>
			<div class="right"></div>
			Восстановление пароля
		</div>
		<div class="body" style="height: 170px; background: #FEDADE">
			<table class="center"><tr><td>
				<table class="login_table">
				<form action="javascript: recovery();" id="form_rec">
				<tr><td><input class="text" type="text" name="email" value="E-mail" maxlength="50" id="reset_email" onclick="javascript: if(this.value == 'E-mail') this.value = '';" onblur="javascript: if(this.value == '') this.value = 'E-mail';"></td></tr>
				<tr><td><input class="text" type="submit" value="Отправить новый" style="width: 100%"></td></tr>
				</form>
				</table>
			</td></tr>
			</table>
		</div>
		<div class="title">
			<div class="left" id="foot4" style="padding-left: 3px"></div>
			<div class="right"><div class="wait" id="wait4"></div></div>
		</div>
	</div>
			<?php
		}
		else
		{
			$qt = mysql_query("select type from cards where id = ".$cardid." limit 1");
			$ct = mysql_result($qt, 0);
			if($ct == 6)
			{
				print widget_output($cardid);
			}
			elseif($ct == 1)
			{
				$q = mysql_query("select name, surname, photo from people where id = ".$_COOKIE["2ndw_userid"]." limit 1");
				$man = mysql_fetch_assoc($q);
				print '
<div id="card'.$cardid.'" class="card" style="width: 236px; position: relative; float: left">
	<div class="title" style="cursor: move" onmousedown="javascript: Layout.StartDrag(event, document.getElementById(\'card'.$cardid.'\'));">
		<div class="left"></div>
		<div class="right"><div class="win_close" onclick="remove_card('.$cardid.');"></div></div>
		'.$man['name'].' '.$man['surname'].'
	</div>
	<div class="body" style="height: 170px; background: #FEFEFE">
	<table class="center"><tr><td><a href="card.php">';
	if($man['photo'] == "") print '<img src="images/noavatars.jpg">';
	else print '<img src="photos/'.$_COOKIE["2ndw_userid"].'/'.$man['photo'].'s.jpg">';
	print '</a></td></tr></table>
	</div>
	<div class="title">
		<div class="left" id="foot'.$cardid.'" style="padding-left: 3px"></div>
		<div class="right"><div class="wait" id="wait'.$cardid.'"></div></div>
	</div>
</div>
';
			}
		}
	}
	?>
	</div></td><td>
	<div id="addcardspanel" style="width: 200px; float: left; height: 600px; display: none; border: 1px solid #d3d3d3; background: #fefefe; padding: 10px">
	<font style="font-size: 16px; font-weight: bold">Другие карточки:</font>
	<ul style="padding: 10px">
	<li><a href="javascript: addcard(505);">Яндекс.Карты</a>
	<li>123
	<li>123
	</ul>
	</div></td></tr></table>
	<div class="clear_line">&nbsp;</div>
	<div class="footer"><div class="footer_block"><table class="footer_block"><tr><td>
		<div class="copyright">
			2008—2009 «<a href="index.php">Второй Мир</a>»<br/>
			Все права защищены.
		</div>
		<div class="menu3">
			<a href="help/about.php">О проекте</a>
			<a href="help/index.php">Помощь</a>
		</div>
	</td></tr></table></div></div>
</div>
</body>
</html>