<!--//

var browser = "ie";
switch(navigator.appName)
{
	case "Opera": browser = "opera"; break;
	case "Netscape": browser = "netscape"; break;
}

function main(be_visible)
{
	if(be_visible) document.getElementById("result1").style.display = "block";
	else document.getElementById("result1").style.display = "none";
	document.getElementById("result2").style.display = "none";
	document.getElementById("result3").style.display = "none";
	document.getElementById('activate').style.display = 'none';
	document.getElementById('register').style.display = 'none';
	document.getElementById('main').style.display = 'block';
}
function register(be_visible)
{
	if(be_visible) document.getElementById("result2").style.display = "block";
	else document.getElementById("result2").style.display = "none";
	document.getElementById("result1").style.display = "none";
	document.getElementById("result3").style.display = "none";
	document.getElementById('main').style.display = 'none';
	document.getElementById('activate').style.display = 'none';
	document.getElementById('register').style.display = 'block';
}
function activate(be_visible)
{
	if(be_visible) document.getElementById("result3").style.display = "block";
	else document.getElementById("result3").style.display = "none";
	document.getElementById("result1").style.display = "none";
	document.getElementById("result2").style.display = "none";
	document.getElementById('main').style.display = 'none';
	document.getElementById('register').style.display = 'none';
	document.getElementById('activate').style.display = 'block';
}
function addoption(index, val)
{
	var elem = document.createElement('option');
	elem.id = 'opt'+val;
	elem.value = val;
	elem.text = val;
	if(browser == "ie") document.getElementById('selday').add(elem, index);
	else document.getElementById('selday').appendChild(elem);
}
function selchg()
{
	var f1 = document.getElementById('f1');
	if(f1.month.value == 2)
	{
		document.getElementById('selday').remove(30);
		document.getElementById('selday').remove(29);
		if(document.getElementById('selyear').value % 4 != 0) document.getElementById('selday').remove(28);
		else
		{
			if(document.getElementById('selday').options.length == 28) addoption(28, 29);
		}
	}
	else
	{
		if(f1.month.value == 4 || f1.month.value == 6 || f1.month.value == 9 || f1.month.value == 11)
		{
			if(document.getElementById('selday').options.length == 28) addoption(28, 29);
			if(document.getElementById('selday').options.length == 29) addoption(29, 30);
			document.getElementById('selday').remove(30);
		}
		else
		{
			if(document.getElementById('selday').options.length == 28) addoption(28, 29);
			if(document.getElementById('selday').options.length == 29) addoption(29, 30);
			if(document.getElementById('selday').options.length == 30) addoption(30, 31);
		}
	}
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
function getRequestBody(oForm) { 
	var aParams = new Array();
	for(var i = 0; i < oForm.elements.length; i++) {
		var sParam = encodeURIComponent(oForm.elements[i].name);
		sParam += "=";
		sParam += encodeURIComponent(oForm.elements[i].value);
		aParams.push(sParam);
	}
	return aParams.join("&");
}
function regdone()
{
	var oXmlHttp = getHTTPRequestObject();
	var sBody = getRequestBody(document.getElementById("f1"));
	if(oXmlHttp)
	{
		oXmlHttp.open("POST", "registration.php?act=ajaxregdone", true);
		oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				regswitch(result);
				wait(0, "wait2");
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
       	oXmlHttp.send(sBody);
       	wait(1, "wait2");
	}
}
function regswitch(result)
{
	var f2 = document.getElementById("f2");
	var f1 = document.getElementById("f1");
	var flogin = document.getElementById("flogin");
				switch(result)
				{
					case "ename":
						response(1, 2, "Вы не ввели имя");
					break;
					case "esurname":
						response(1, 2, "Вы не ввели фамилию");
					break;
					case "eemail":
						response(1, 2, "Вы не ввели E-mail");
					break;
					case "eemailsend":
						response(1, 2, "Возникла ошибка при отправке E-mail. Проверьте введённый адрес или повторите попытку позже.");
					break;
					case "epass":
						response(1, 2, "Вы не ввели пароль");
					break;
					case "epassdifferent":
						response(1, 2, "Пароли не совпадают");
					break;
					case "eemailexistsunact":
						response(1, 3, "Такой E-mail уже зарегистрирован, но не активирован. Введите ниже Ваш код активации.");
						f2.email.value = f1.email.value;
						activate(3);
					break;
					case "eemailexists":
						response(1, 1, "Такой E-mail уже зарегистрирован. Введите ниже Ваш пароль для входа.");
						flogin.email.value = f1.email.value;
						main(1);
					break;
					case "ok":
						response(0, 3, "Вы зарегистрированы. На Ваш E-mail отправлено письмо с кодом активации. Введите его ниже или пройдите по ссылке в письме для завершения регистрации.");
						f2.email.value = f1.email.value;
						activate(3);
					break;
					default:
						response(1, 2, "Ошибка регистрации");
				}
}
function actdone()
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		var f2 = document.getElementById("f2");
		oXmlHttp.open("GET", "registration.php?act=ajaxactdone&email="+f2.email.value+"&actcode="+f2.actcode.value, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				actswitch(result);
				wait(0, "wait3");
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		oXmlHttp.send(null);
		wait(1, "wait3");
	}
}
function actswitch(result)
{
	var f2 = document.getElementById("f2");
	var flogin = document.getElementById("flogin");
				switch(result)
				{
					case "eemail":
						response(1, 3, "Неверный E-mail");
					break;
					case "ecode":
						response(1, 3, "Неверный код");
					break;
					case "eemailacted":
						response(1, 1, "E-mail уже активирован");
						flogin.email.value = f2.email.value;
						main(1);
					break;
					case "ok":
						location = "profile.php";
					break;
				}
}
function sendactcode()
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		var f2 = document.getElementById("f2");
		var flogin = document.getElementById("flogin");
		oXmlHttp.open("GET", "registration.php?act=ajaxsendcode&email="+f2.email.value, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				switch(result)
				{
					case "eemailacted":
						response(1, 1, "E-mail уже активирован");
						flogin.email.value = f2.email.value;
						main(1);
					break;
					case "eemail":
						response(1, 3, "Неверный E-mail");
					break;
					case "eemailsend":
						response(1, 3, "Возникла ошибка при отправке E-mail");
					break;
					case "ok":
						response(0, 3, "Письмо отправлено");
					break;
				}
				wait(0, "wait3");
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		oXmlHttp.send(null);
		wait(1, "wait3");
	}
}
function logindone()
{
	var oXmlHttp = getHTTPRequestObject();
	var sBody = getRequestBody(document.getElementById("flogin"));
	if(oXmlHttp)
	{
		var f2 = document.getElementById("f2");
		var flogin = document.getElementById("flogin");
		oXmlHttp.open("POST", "login.php?act=ajaxlogindone", true);
		oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				switch(result)
				{
					case "eemail":
						response(1, 1, "Неверный E-mail");
					break;
					case "eemailunactive":
						response(1, 1, "E-mail не активирован");
						f2.email.value = flogin.email.value;
						activate(1);
					break;
					case "epass":
						response(1, 1, "Неверный пароль");
					break;
					case "ok":
						location = "profile.php";
					break;
				}
				wait(0, "wait1");
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
       	oXmlHttp.send(sBody);
       	wait(1, "wait1");
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
function response(iserror, number, text)
{
	if(iserror != 1) 
	{
		document.getElementById("result"+number).style.borderColor = "Green";
	}
	document.getElementById("result"+number).innerHTML = text;
	document.getElementById("result"+number).style.display = "block";
}
//-->