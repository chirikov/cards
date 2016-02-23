<?php
include_once("../inc/my_connect.php");
include_once("inc/control.php");
include_once("../inc/functions.php");

$help_page = "job";

if(
$_GET['act'] != "default" && 
$_GET['act'] != "found" && 
$_GET['act'] != "new" && 
$_GET['act'] != "invite" && 
$_GET['act'] != "invited" && 
$_GET['act'] != "ajaxgetjobs" &&  
$_GET['act'] != "search"
) $_GET['act'] = "default";

$body = "";

if($_GET['act'] == "default")
{
	$body .= "<div class='path'><a href='profile.php'>Вторая Россия</a> » Работа</div>";
	
	$q = mysql_query("select * from jobs where id = (select job from gameinfo where id = ".$_COOKIE['mir_id'].")");
	if(mysql_num_rows($q) < 1)
	{
		header("Location: job.php?act=search");
		exit;
	}
	$job = mysql_fetch_assoc($q);
	$qw = mysql_query("select name from gos_structures where id = ".$job['object']);
	
	$body .= "
	Место работы: ".mysql_result($qw, 0)."<br>
	Должность: ".$job['name']."<br>
	Зарплата: ".$job['payment']."<br><br>";
	if($job['submission'] != 0)
	{
		$body .= "Ваш начальник: ";
		$qs = mysql_query("select name from jobs where id = ".$job['submission']);
		$qssn = mysql_query("select name, surname from users where id = (select id from gameinfo where job = ".$job['submission'].")");
		if(mysql_num_rows($qssn) < 1) $body .= mysql_result($qs, 0).". Должность свободна. <a href='job.php?act=found&job=".$job['submission']."'>Занять</a>";
		else
		{
			$sn = mysql_fetch_assoc($qssn);
			$body .= $sn['name']." ".$sn['surname'].", ".mysql_result($qs, 0);
		}
		$body .= "<br><br>";
	}
	else
	{
		$qs = mysql_query("select id, name from gos_structures where id = (select submission from gos_structures where id = ".$job['object'].")");
		if(mysql_num_rows($qs) > 0)
		{
			$body .= "Орган, которому Вы подчиняетесь: ".mysql_result($qs, 0, 'name')."<br><br>";
		}
	}
	
	$qp = mysql_query("select id, name from jobs where submission = ".$job['id']);
	if(mysql_num_rows($qp) > 0) $body .= "Ваши подчиненные:<br>";
	while($p = mysql_fetch_assoc($qp))
	{
		$qpsn = mysql_query("select surname, name from users where id = (select id from gameinfo where job = ".$p['id'].")");
		if(mysql_num_rows($qpsn) < 1) $body .= $p['name'].". Должность свободна. <a href='job.php?act=invite&job=".$p['id']."'>Пригласить</a><br>";
		else
		{
			$sn = mysql_fetch_assoc($qpsn);
			$body .= $sn['name']." ".$sn['surname'].", ".$p['name']."<br>";
		}
	}
	$body .= "<br><br><a href='job.php?act=search'>Найти работу</a>";
}
elseif($_GET['act'] == "search")
{
	$q = mysql_query("select city, job from gameinfo where id = ".$_COOKIE['mir_id']);
	$gameinfo = mysql_fetch_assoc($q);
	$qcity = mysql_query("select name from cities where id = ".$gameinfo['city']);
	if(mysql_num_rows($qcity) < 1)
	{
		header("Location: city.php?act=select");
		exit;
	}
	$body .= "<div class='path'><a href='profile.php'>Вторая Россия</a> » ".mysql_result($qcity, 0)." » Поиск работы</div>";
	$body .= '
	<script language="javascript" type="text/javascript">
	<!--//
	var browser = "ie";
	switch(navigator.appName)
	{
		case "Opera": browser = "opera"; break;
		case "Netscape": browser = "netscape"; break;
	}
	
	function getHTTPRequestObject() {
		var xmlHttpRequest;
		/*@cc_on
		@if (@_jscript_version >= 5)
		try {
			xmlHttpRequest = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (exception1) {
			try {
				xmlHttpRequest = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (exception2) {
				xmlHttpRequest = false;
			}
		}
		@else
		xmlhttpRequest = false;
		@end @*/
		
		if (!xmlHttpRequest && typeof XMLHttpRequest != "undefined") {
			try {
				xmlHttpRequest = new XMLHttpRequest();
			}
			catch (exception) {
				xmlHttpRequest = false;
			}
		}
		return xmlHttpRequest;
	}
	function workplaced()
	{
		var place = document.getElementById("workplace").value;
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			oXmlHttp.open("GET", "job.php?act=ajaxgetjobs&object="+place, true);
			oXmlHttp.onreadystatechange = function()
			{
				if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
				{
					var opts = document.getElementById("job");
					for(i=opts.options.length; i>=0; i--)
					{
						opts.remove(i);
					}
					if(oXmlHttp.responseText != "")
					{
						var vals = oXmlHttp.responseText.split("@");
						for(i=0; i<vals.length; i++)
						{
							var val = vals[i].split("|");
							var elem = document.createElement("option");
							elem.value = val[0];
							elem.text = val[1];
							if(browser == "ie") opts.add(elem, -1);
							else opts.appendChild(elem);
						}
					}
				}
				wait(0, "wwait");
			}
			oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
	    	oXmlHttp.send(null);
	    	wait(1, "wwait");
		}
	}
	function wait(onoff, element_id)
	{
		if(onoff == 1)
		{
			document.getElementById(element_id).style.display = "inline";
		}
		else
		{
			document.getElementById(element_id).style.display = "none";
		}
	}
	//-->
	</script>
	';
	$body .= "<form action='job.php?act=found' method='get'>
	<input type='hidden' name='act' value='found'>
	Место работы: <select name='workplace' id='workplace' onchange='javascript: workplaced();'>";
	
	$qw = mysql_query("select id, name from gos_structures where city = ".$gameinfo['city']);
	$qmyw = mysql_query("select object from jobs where id = ".$gameinfo['job']);
	if(mysql_num_rows($qmyw) > 0) $myw = mysql_result($qmyw, 0);
	else $myw = 0;
	$body .= getlist($qw, "select", $myw);
	$body .= "</select> ";
	if($myw)
	{
		$body .= "<select id='job' name='job'>";
		$qmyw = mysql_query("select id, name from jobs where object = ".$myw);
		$body .= getlist($qmyw, "select", $gameinfo['job']);
		$body .= "</select>";
	}
	else
	{
		$body .= "<select id='job' name='job'>";
		$qmyw = mysql_query("select id, name from jobs where object = (select id from gos_structures where 1 limit 1)");
		$body .= getlist($qmyw, "select");
		$body .= "</select>";
	}
	$body .= "<input type='submit' value='Устроиться'></form><img id='wwait' class='wait' src='../images/wait.gif'>";
	//$body .= "<br><a href='job.php?act=new'>Создать место работы</a>";
}
elseif($_GET['act'] == "found")
{
	if($_GET['job'] > 0)
	{
		$q = mysql_query("update gameinfo set job = ".$_GET['job'].", city = (select city from gos_structures where id = (select object from jobs where id = ".$_GET['job'].")) where id = ".$_COOKIE['mir_id']);
	}
	header("Location: profile.php");
	exit;
}
elseif($_GET['act'] == "invite")
{
	$body .= "<div class='path'><a href='profile.php'>Вторая Россия</a> » Пригласить на работу</div>";
	$body .= "<form action='job.php?act=invited' method='post'>
	<input type='hidden' name='job' value='".$_GET['job']."'>
	<input type='radio' name='typer' value='contacts' checked> Выбрать из списка контактов: <select name='contact'>";
	$contacts = contact_list($_COOKIE['mir_id'], "id,surname,name");
	foreach($contacts as $contact)
	{
		$body .= "<option value='".$contact['id']."'>".$contact['name']." ".$contact['surname'];
	}
	$body .= "</select><br>
	<input type='radio' name='typer' value='email'> Пригласить по E-mail: <input type='text' name='email' maxlength='50'><br>
	<input type='submit' value='Пригласить'>
	</form>";
}
elseif($_GET['act'] == "invited")
{
	if($_POST['typer'] == "contacts")
	{
		$qi1 = mysql_query("select name, payment, object from jobs where id = ".$_POST['job']);
		$job = mysql_fetch_assoc($qi1);
		$qi2 = mysql_query("select name, city from gos_structures where id = ".$job['object']);
		$object = mysql_fetch_assoc($qi2);
		$qi3 = mysql_query("select name from cities where id = ".$object['city']);
		$text = "Приглашаю занять новую должность во Второй России:
		Город: ".mysql_result($qi3, 0)."
		Место работы: ".$object['name']."
		Должность: ".$job['name']."
		Зарплата: ".$job['payment']."
		
		<a href='2ndRussia/job.php?act=found&job=".$_POST['job']."'>Принять приглашение</a>";
		$q = mysql_query("insert into messages (sender, recepient, text, seen, time) values ('".$_COOKIE['mir_id']."', '".$_POST['contact']."', '".addslashes(nl2br($text))."', 0, '".time()."')");
	}
	elseif($_POST['typer'] == "email")
	{
		$qsn = mysql_query("select surname, name from users where id = ".$_COOKIE['mir_id']);
		$sn = mysql_fetch_assoc($qsn);
		
		$qi1 = mysql_query("select name, payment, object from jobs where id = ".$_POST['job']);
		$job = mysql_fetch_assoc($qi1);
		$qi2 = mysql_query("select name, city from gos_structures where id = ".$job['object']);
		$object = mysql_fetch_assoc($qi2);
		$qi3 = mysql_query("select name from cities where id = ".$object['city']);
		
		$to = $email;
		$from = "Второй Мир <info@2ndworld.ru>";
		$sub = $sn['name']." ".$sn['surname']." приглашает Вас занять должность во Второй России.";
		$headers = "From: ".$from."\nMIME-Version: 1.0\nContent-type: text/html; charset=win-1251\n";
		$body1 = "
		<html>
		<body>
		".$sn['name']." ".$sn['surname']." приглашает Вас занять должность во <a href='http://2ndworld.ru/2ndRussia/job.php?act=found&job=".$_POST['job']."'>Второй России</a>.<br><br>
		Город: ".mysql_result($qi3, 0)."<br>
		Место работы: ".$object['name']."<br>
		Должность: ".$job['name']."<br>
		Зарплата: ".$job['payment']."<br><br>
		<a href='http://2ndworld.ru/2ndRussia/job.php?act=found&job=".$_POST['job']."'>Принять приглашение</a><br><br>
		<i>С уважением,<br>
		<a href='http://2ndworld.ru'>Второй Мир</a></i>
		</body>
		</html>
		";
		mail($to, $sub, $body1, $headers);
	}
	header("Location: profile.php");
	exit;
}
elseif($_GET['act'] == "new")
{
	$q = mysql_query("select city from gameinfo where id = ".$_COOKIE['mir_id']);
	$gameinfo = mysql_fetch_assoc($q);
	$qcity = mysql_query("select name from cities where id = ".$gameinfo['city']);
	$body .= "<div class='path'><a href='profile.php'>Вторая Россия</a> » ".mysql_result($qcity, 0)." » Новое рабочее место</div>";
	$body .= '
	<div class="start">
		<div class="start_form">
			<div class="top_corners"><i>&nbsp;</i></div>
			<div class="resultt" id="result1">&nbsp;</div>
			<div class="start_form_pad"><form method="POST" id="fnew" name="fnew" action="javascript: newdone();">
				<table class="form_table">
					<tr>
						<td><label>Место работы:</label></td><td></td>
					</tr>
					<tr>
						<td><input type="radio" name="workplacer" value="exists"> Выбрать:</td><td><select name="workplace" id="workplace" onchange="javascript: workplace();">';
	$qw = mysql_query("select id, name from gos_structures where city = ".$gameinfo['city']);
	$qmyw = mysql_query("select object from jobs where id = ".$gameinfo['job']);
	$myw = mysql_result($qmyw, 0);
	while($w = mysql_fetch_assoc($qw))
	{
		if($myw == $w['id']) $body .= "<option value='".$w[id]."' selected>".$w['name'];
		else $body .= "<option value='".$w[id]."'>".$w['name'];
	}
			$body .= '</select></td>
					</tr>
					<tr>
						<td><input type="radio" name="workplace" value="new"> Создать новое</td>
						<td></td>
					</tr>
					<tr>
						<td>Название:</td>
						<td><input type="text" name="wname" maxlength="200"></td>
					</tr>
				</table>
				<div class="enter"><a href="#" onclick="javascript: newdone();">Создать</a></div>
				<div class="other_computer"><img class="wait" id="wait1" src="images/wait.gif"/></div></form>
			</div>
			<div class="bottom_corners"><i>&nbsp;</i></div>
		</div>
	</div>';
}
elseif($_GET['act'] == "ajaxgetjobs")
{
	$q = mysql_query("select id, name from jobs where object = ".$_GET['object']);
	print getlist($q, "ajax");
	exit;
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>