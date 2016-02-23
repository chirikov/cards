<?php

include_once("inc/my_connect.php");
include_once("inc/functions.php");

if(
$_GET['act'] != "ajaxregdone" && 
$_GET['act'] != "ajaxsendcode" && 
$_GET['act'] != "emailactdone" && 
$_GET['act'] != "ajaxactdone"
) exit;

if($_GET['act'] == "ajaxregdone")
{
	$surname = iconv('UTF-8', 'windows-1251', trim(substr($_POST['surname'], 0, 40)));
	$name = iconv('UTF-8', 'windows-1251', trim(substr($_POST['name'], 0, 40)));
	$email = iconv('UTF-8', 'windows-1251', trim(substr($_POST['email'], 0, 50)));
	$pass = iconv('UTF-8', 'windows-1251', trim(substr($_POST['pass'], 0, 50)));
	if(strlen($surname)<1 or strlen($name)<1 or strlen($email)<6 or strlen($pass)<6)
	{
		if(strlen($name)<1) $result = "ename";
		elseif(strlen($surname)<1) $result = "esurname";
		elseif(!is_valid("email", $email)) $result = "eemail";
		elseif(!is_valid("pass", $pass)) $result = "epass";
		//elseif($pass != $pass2) $result = "epassdifferent";
	}
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
			$birth = mktime(0,0,0, $_POST['month'], $_POST['day'], $_POST['year']);
			$actcode = rand(100000, 999999);
			$q = mysql_query("insert into users (surname, name, email, pass, state, city, birthdate, sex, avatar, actcode, lasttime, regtime) values ('".addslashes(htmlspecialchars($surname))."', '".addslashes(htmlspecialchars($name))."', '".addslashes(htmlspecialchars($email))."', '".md5($pass)."', '1', '".$city."', '".$birth."', '".$_POST['sex']."', '', '".$actcode."', '".time()."', '".time()."')");
			if($q) {
				$to = $name." ".$surname." <".$email.">";
				$from = "Второй Мир <info@2ndworld.ru>";
				$sub = "Регистрация в проекте Второй Мир";
				$headers = "From: ".$from."\nMIME-Version: 1.0\nContent-type: text/html; charset=win-1251\n";
				$body1 = "
				<html>
				<body>
				<h3>Спасибо за регистрацию во <a href='http://2ndworld.ru/index.php'>Втором Мире</a>.</h3><br>
				<br>
				Ваш код активации: ".$actcode."<br>
				Введите его в <a href='http://2ndworld.ru/index.php?act=activate&email=".$email."'>форме активации</a> на сайте или просто перейдите по следующей ссылке:<br><br>
				<a href='http://2ndworld.ru/registration.php?act=emailactdone&email=".$email."&actcode=".$actcode."'>http://2ndworld.ru/registration.php?act=emailactdone&email=".$email."&actcode=".$codedb."</a><br><br>
				<i>С уважением,<br>
				Второй Мир</i>
				</body>
				</html>
				";
				if(mail($to, $sub, $body1, $headers)) $result = "ok";
				else $result = "eemailsend";
			}
			else
			{
				$result = "ereg";
			}
		}
	}
	print $result;
}
elseif($_GET['act'] == "ajaxactdone")
{
	$email = trim(substr($_GET['email'], 0, 50));
	$q1 = mysql_query("select actcode from users where email = '".$email."'");
	if(mysql_num_rows($q1) < 1) $result = "eemail";
	else
	{
		$codedb = mysql_result($q1, 0);
		if($codedb != 0)
		{
			if($codedb != $_GET['actcode'])
			{
				$result = "ecode";
			}
			else
			{
				$q2 = mysql_query("update users set actcode = 0 where email = '".$email."'");
				$q3 = mysql_query("select id, pass from users where email = '".$email."'");
				$id = mysql_result($q3, 0, 'id');
				$pass = mysql_result($q3, 0, 'pass');
				mkdir("photos/".$id, 0766);
				$q4 = mysql_query("insert into info (id) values(".$id.")");
				setcookie("mir_id", $id, time()+60*60*24*2);
				setcookie("mir_logged", $pass, time()+60*60*24*2);
				$result = "ok";
			}
		}
		else
		{
			$result = "eemailacted";
		}
	}
	print $result;
}
elseif($_GET['act'] == "emailactdone")
{
	$email = trim(substr($_GET['email'], 0, 50));
	$q1 = mysql_query("select actcode from users where email = '".$email."'");
	if(mysql_num_rows($q1) < 1) $result = "eemail";
	else
	{
		$codedb = mysql_result($q1, 0);
		if($codedb != 0)
		{
			if($codedb != $_GET['actcode'])
			{
				$result = "ecode";
			}
			else
			{
				$q2 = mysql_query("update users set actcode = 0 where email = '".$email."'");
				$q3 = mysql_query("select id, pass from users where email = '".$email."'");
				$id = mysql_result($q3, 0, 'id');
				$pass = mysql_result($q3, 0, 'pass');
				mkdir("photos/".$id, 0766);
				$q4 = mysql_query("insert into info (id) values(".$id.")");
				setcookie("mir_id", $id, time()+60*60*24*2);
				setcookie("mir_logged", $pass, time()+60*60*24*2);
				$result = "ok";
				header("Location: profile.php");
				exit;
			}
		}
		else
		{
			$result = "eemailacted";
		}
	}
	header("Location: index.php?act=activate&result=".$result);
	exit;
}
elseif($_GET['act'] == "ajaxsendcode")
{
	$email = trim(substr($_GET['email'], 0, 50));
	$q1 = mysql_query("select actcode from users where email = '".$email."'");
	if(mysql_num_rows($q1) < 1) $result = "eemail";
	else
	{
		$codedb = mysql_result($q1, 0);
		if($codedb != 0)
		{
			$to = $name." ".$surname." <".$email.">";
				$from = "Второй Мир <info@2ndworld.ru>";
				$sub = "Код активации";
				$headers = "From: ".$from."\nMIME-Version: 1.0\nContent-type: text/html; charset=win-1251\n";
				$body1 = "
				<html>
				<body>
				Напоминаем Вам код активации Вашего E-mail: ".$codedb."
				<br>
				Введите его в <a href='http://2ndworld.ru/index.php?act=activate&email=".$email."'>форме активации</a> на сайте или просто перейдите по следующей ссылке:<br><br>
				<a href='http://2ndworld.ru/registration.php?act=emailactdone&email=".$email."&actcode=".$codedb."'>http://2ndworld.ru/registration.php?act=emailactdone&email=".$email."&actcode=".$codedb."</a><br><br>
				<i>С уважением,<br>
				Второй Мир</i>
				</body>
				</html>
				";
				if(mail($to, $sub, $body1, $headers)) $result = "ok";
				else $result = "eemailsend";
		}
		else
		{
			$result = "eemailacted";
		}
	}
	print $result;
}
?>