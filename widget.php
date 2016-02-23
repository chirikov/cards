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
<script type="text/javascript">

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
var timer;
function update_population()
{
	clearInterval(timer);
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", "http://cards.2ndworld.ru/redir.php", true); //http://www.world-gazetteer.com/wg.php?x=&men=stcl&lng=en&des=wg&srt=npan&col=abcdefghinoq&msz=1500
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				var res1 = result.search('<div id="info"><h2>world population clock</h2><p>current population based on the World Gazetteer');
				var pop = result.substr(res1+111, 28);
				res1 = '';
				result = '';
				
				document.getElementById("world_population").innerHTML = pop;
				pop = '';
				document.getElementById("wait_world_population").style.display = "none";
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		oXmlHttp.send(null);
		document.getElementById("wait_world_population").style.display = "block";
	}
	timer = setInterval("update_population();", 3000);
}
/*
<iframe NAME="VIDEO" allowtransparency="true" [...] src="http://www.1tv.ru/owa/win/ORT6_ITV.video_object?p_ch=1tv" width=320 height=310 marginwidth=0 marginheight=0 scrolling=no frameborder=0></iframe>
Первый Канал
*/
</script>
</head>
<body onload="update_population();">
<div class="main">
	<div class="carcass">
		<table class="head"><tr><td class="head">
			<div class="logo">
				<a href="index.php"><img class="img" src="images/logo.gif" alt="Логотип"/><img class="text" src="images/logo_text.gif" alt="Второй Мир"/></a>
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
		
			<div id="card4" class="card" style="width: 400px; height: 300px; background: #C6FCBD; position: relative; float: left">
				<div class="title" style="cursor: move">
					Виджет - Население планеты
					<div class="card_number">Карточка #12</div>
				</div>
				<div class="body">
				<table class="center"><tr><td>
				<div class="bigtitle">Население планеты</div>
<?php
/*
$ch = curl_init("http://www.world-gazetteer.com/wg.php?x=&men=stcl&lng=en&des=wg&srt=npan&col=abcdefghinoq&msz=1500");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
curl_close($ch);
*/
?>
<center><font style="font-size: 20px" id="world_population"></font></center>
				</td></tr>
				<tr><td class="bottom"></td></tr>
				</table>
				</div>
				<div class="cfooter">
					<div class="left" id="foot4">World Gazetteer (www.world-gazetteer.com)</div>
					<div class="wait" id="wait_world_population"></div>
				</div>
			</div>
		
		</div>
		<div class="clear_line">&nbsp;</div>
	</div>
	<div class="footer"><div class="footer_block"><table class="footer_block"><tr><td>
		<div class="copyright">
			2008—2009 «<a href="index.php?force">Второй Мир</a>»<br/>
			Все права защищены.
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