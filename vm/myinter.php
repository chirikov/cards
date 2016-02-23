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
<link rel="stylesheet" href="styles/test.css" type="text/css"/>
<!--[if IE]><link rel="stylesheet" href="styles/ie.css" type="text/css" /><![endif]-->
<script language="javascript" type="text/javascript">
<!--//

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

function form_submit(fname, url)
{
	var oForm = document.forms[fname];
	
	var aParams = new Array();
	for(var i=0; i<oForm.elements.length; i++)
	{
		var sParam = encodeURIComponent(oForm.elements[i].name) + "=" + encodeURIComponent(oForm.elements[i].value);
		aParams.push(sParam);
	}
	var request = aParams.join("&");
	
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", url+"&"+request, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4)
			{
				var result = oXmlHttp.responseText;
				if(result.substr(0, 2) == "er")
				{
					alert(result.substr(2))
				}
				document.getElementById(fname+"_wait").style.display = "none";
			}
		}
		alert("@");
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		oXmlHttp.send(null);
		document.getElementById(fname+"_wait").style.display = "inline";
	}
}

//-->
</script>
</head>
<body>
<div class="main">
	<div class="carcass">
	<div class="right_column">
	<div class="start">


<div class="form">
	<div class="top_corners"><i>&nbsp;</i></div>
	<h2>Название формы</h2>
	<div class="result" id="login_result" style="display: none">&nbsp;</div>
	<div class="form_pad">
		<form method="post" name="login" action="javascript: form_submit('login', 'login.php?act=ajaxlogindone');" onkeypress="javascript: if(event.keyCode == 13) this.submit();">
		<table class="form_table">
			<tr>
				<td><label>E-mail:</label></td>
				<td><input type="text" name="email" maxlength="50"/></td>
				<td><a href="#" onclick="javascript: register();">Регистрация</a></td>
			</tr>
			<tr>
				<td><label>Пароль:</label></td>
				<td><input type="password" name="pass" maxlength="50"/></td>
				<td><a href="#" onclick="javascript: register();">Забыли&nbsp;пароль?</a></td>
			</tr>
		</table>
		<div class="enter"><a href="#" onclick="javascript: document.forms['login'].submit();">Войти</a><img  id="wait1" src="images/wait.gif" alt=""/></div>
		<div class="other_computer"><input type="checkbox"> Чужой компьютер</div>
		</form>
		
	</div>
	<div class="bottom_corners"><i>&nbsp;</i></div>
</div>


</div></div></div></div>
</body>
</html>