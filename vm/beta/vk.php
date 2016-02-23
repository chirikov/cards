<?php
function getsid()
{
	/*
	$ch1 = curl_init("http://login.userapi.com/auth?login=auto&site=1");
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1, CURLOPT_HEADER, 1);
	curl_setopt($ch1, CURLOPT_COOKIE, "remixpassword=".$_COOKIE['remixpassword']);
	$head = curl_exec($ch1);
	curl_close($ch1);
	
	//$sid = substr($head, strpos($head, "Location: http://2ndworld.ru/index.html#;sid=")+45, 32);
	$sid = substr($head, strpos($head, "Location: http://in2017.com/index.html#;sid=")+44, 32);

	if(substr($sid, 0, 2) == "-1")
	{
	*/
		$ch1 = curl_init("http://login.userapi.com/auth?login=force&site=1&email=info@2ndworld.ru&pass=221397");
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch1, CURLOPT_HEADER, 1);
		$head = curl_exec($ch1);
		curl_close($ch1);
		
		$sid = substr($head, strpos($head, "Location: http://in2017.com/index.html#;sid=")+44, 32);
		$cook = substr($head, strpos($head, "Set-Cookie: remixpassword=")+26, 56);
		setcookie("remixpassword", $cook, time()+24*3600); //, "/", "login.userapi.com"
	//}
	return $sid;
}
function escape($str)
{
	$rus = array(chr(13).chr(10), " ", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ы", "Ь", "Э", "Ю", "Я", "а", "б", "в", "г", "д", "е", "ё", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", "ь", "э", "ю", "я");
	$code = array("%0D%0A", "+", "%D0%90", "%D0%91", "%D0%92", "%D0%93", "%D0%94", "%D0%95", "%D0%81", "%D0%96", "%D0%97", "%D0%98", "%D0%99", "%D0%9A", "%D0%9B", "%D0%9C", "%D0%9D", "%D0%9E", "%D0%9F", "%D0%A0", "%D0%A1", "%D0%A2", "%D0%A3", "%D0%A4", "%D0%A5", "%D0%A6", "%D0%A7", "%D0%A8", "%D0%A9", "%D0%AA", "%D0%AB", "%D0%AC", "%D0%AD", "%D0%AE", "%D0%AF", "%D0%B0", "%D0%B1", "%D0%B2", "%D0%B3", "%D0%B4", "%D0%B5", "%D1%91", "%D0%B6", "%D0%B7", "%D0%B8", "%D0%B9", "%D0%BA", "%D0%BB", "%D0%BC", "%D0%BD", "%D0%BE", "%D0%BF", "%D1%80", "%D1%81", "%D1%82", "%D1%83", "%D1%84", "%D1%85", "%D1%86", "%D1%87", "%D1%88", "%D1%89", "%D1%8A", "%D1%8B", "%D1%8C", "%D1%8D", "%D1%8E", "%D1%8F");
	return str_replace($rus, $code, $str);
}
/*
include_once("inc/my_connect.php");
include_once("inc/control.php");

if(loginned() !== true)
{
	header("Location: index.php");
	exit;
}
$q = mysql_query("update users set lasttime = ". time() ." where id = ".$_COOKIE['mir_id']);
*/
if(
$_GET['act'] != "getvkfriends" &&
$_GET['act'] != "list" &&
$_GET['act'] != "sendvkmes"
) $_GET['act'] = "getvkfriends";

if($_GET['act'] == "list")
{
	$q = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($q);
	$q2 = mysql_query("select vkid from info where id = ".$_COOKIE['mir_id']);
	$vkid = mysql_result($q2, 0);
	if($vkid == 0) $vkid = '';
	$body = "<script language='javascript' type='text/javascript' src='inc/vkajax.js'></script>
	<form name='fvkid' method='post' action='javascript: getvkfriends(\"".$row['name']." ".$row['surname']."\");'>
	Введите Ваш ID ВКонтакте: <input type='text' name='vkid' maxlength='8' value='".$vkid."'> <input type='submit' value='Получить список' name='go'><br>
	<input type='checkbox' name='vkonline' value='online'> только тех, кто в сети<br>
	<input type='checkbox' name='vkidsave' value='1'> сохранить мой ID</form>
	<table style='display: none;' id='tableresult'><tr><td id='tdresult'></td></tr></table>";
}
elseif($_GET['act'] == "getvkfriends")
{
	//$sid = getsid();
	
	print "
	<html>
<head>
</head>
<body>
	<script language='javascript' type='text/javascript'>
	<!--//
	//if(window.parent.document.getElementById('frnotvis').document.readyState == 'interactive') alert(window.parent.document.getElementById('frnotvis').src);
	//alert(window.parent.document.getElementById('frnotvis').document.readyState);
	//setTimeout('alert(window.parent.document.getElementById(\"frnotvis\").document.location);', 3000);
	//-->
	</script>
	<a href='#' onclick='javascript: alert(window.parent.document.getElementById(\"frnotvis\").src);'>123123</a>
	</body></html>";

	//print $sid;
	exit;
	
	if($_GET['vkonline'] == "true") $vkact = "friends_online";
	else $vkact = "friends";
	
	if($_GET['vkidsave'] == "true") $q1 = mysql_query("update info set vkid = '".$_GET['vkid']."' where id = ".$_COOKIE['mir_id']);
	
	$ch2 = curl_init("http://userapi.com/data?act=".$vkact."&from=1&to=1000&id=".$_GET['vkid']."&sid=".$sid);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	$json = curl_exec($ch2);
	curl_close($ch2);
	if($json == '{"ok": -3}') print "eprivacy";
	elseif($json == '[]') print "enofriends";
	else
	{
	$friends = json_decode($json, true);
	$fio = iconv('UTF-8', 'windows-1251', trim($_GET['fio']));
	$body = "
	<script language='javascript' type='text/javascript'>
	<!--//
	function allcheck(trigger)
	{	
		for(i=0; i<fvkfriends.elements.length; i++)
		{
			if(fvkfriends.elements(i).type == \"checkbox\") fvkfriends.elements(i).checked = trigger;
		}
	}
	//-->
	</script>
	<form name='fvkfriends' method='post' action='javascript: sendvkmes();'>
	<input type='hidden' name='num' value='".count($friends)."'>
	Текст: <textarea id='asd' name='text' rows=10 cols=40 readonly>".$fio." приглашает Вас во Второй Мир.

2ndworld.ru</textarea><br>
	<table><tr><td><input type='checkbox' name='friendall' onclick='javascript: allcheck(this.checked);'></td><td><input type='submit' value='Отправить приглашения' name='go'></td><td></td><td></td></tr>";
	$i = 1;
	foreach($friends as $friend)
	{
		if($friend[3] === 1) $online = "online";
		else $online = "";
		$body .= "<tr><td><input type='checkbox' id='friend".$i."' value='".$friend[0]."'></td><td><img src='".$friend[2]."'></td><td><a target='_blank' href='http://vkontakte.ru/id".$friend[0]."'>".$friend[1]."</a></td><td>".$online."</td></tr>";
		$i++;
	}
	$body .= "</table></form>";
	print $body;
	}
	exit;
}
elseif($_GET['act'] == "sendvkmes")
{
	$sid = getsid();
	$text = iconv('UTF-8', 'windows-1251', trim($_GET['text']));
	for($i = 1; $i<=$_GET['num']; $i++)
	{
		if($_GET['friend'.$i] > 0)
		{
			$url = "http://userapi.com/data?act=add_message&id=".$_GET['friend'.$i]."&ts=".time()."&message=".escape($text)."&sid=".$sid;
			if($_GET['fccode'] > 0) $url .= "&fcsid=".$_GET['fcsid']."&fccode=".$_GET['fccode'];
			$ch1 = curl_init($url);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			$json = curl_exec($ch1);
			curl_close($ch1);
			$jd = json_decode($json, true);
			if($jd['ok'] == 1)
			{
				print "ok";
			}
			elseif($jd['ok'] == -2) print "eflood";
		}
	}
	exit;
}

//include_once("inc/head.php");
print $body;
//include_once("inc/foot.php");
?>