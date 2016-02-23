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
function register()
{
	var oXmlHttp = getHTTPRequestObject();
	var f = document.getElementById("form_reg");
	var sBody = getRequestBody(f);
	if(oXmlHttp)
	{
		oXmlHttp.open("POST", "index.php?act=register", true);
		oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				switch(result)
				{
					case "ename":
						document.getElementById("foot2").innerHTML = "Вы не ввели имя";
					break;
					case "esurname":
						document.getElementById("foot2").innerHTML = "Вы не ввели фамилию";
					break;
					case "eemail":
						document.getElementById("foot2").innerHTML = "Вы не ввели E-mail";
					break;
					case "eemailsend":
						document.getElementById("foot2").innerHTML = "Возникла ошибка при отправке E-mail. Запросите код.";
						document.getElementById("form_act").email.value = f.email.value;
						document.getElementById('card3').style.display = '';
					break;
					case "epass":
						document.getElementById("foot2").innerHTML = "Слишком короткий пароль";
					break;
					case "eemailexistsunact":
						document.getElementById("foot2").innerHTML = "Такой E-mail уже зарегистрирован, но не активирован";
						document.getElementById("form_act").email.value = f.email.value;
						document.getElementById('card3').style.display = '';
					break;
					case "eemailexists":
						document.getElementById("foot2").innerHTML = "Такой E-mail уже зарегистрирован";
						document.getElementById("form_login").email.value = f.email.value;
					break;
					case "ereg":
						document.getElementById("foot2").innerHTML = "Произошла ошибка при регистрации";
					break;
					case "ok":
						document.getElementById("foot2").innerHTML = "Вы зарегистрированы. На Ваш E-mail отправлено письмо с кодом активации.";
						document.getElementById("form_act").email.value = f.email.value;
						document.getElementById('card3').style.display = '';
					break;
				}
				wait(0, "wait2");
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
       	oXmlHttp.send(sBody);
       	wait(1, "wait2");
	}
}
function activate()
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		var f = document.getElementById("form_act");
		oXmlHttp.open("GET", "index.php?act=activate&type=ajax&email="+f.email.value+"&code="+f.code.value, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				switch(result)
				{
					case "eemail":
						document.getElementById("foot3").innerHTML = "Неверный E-mail";
					break;
					case "ecode":
						document.getElementById("foot3").innerHTML = "Неверный код";
					break;
					case "eactive":
						document.getElementById("foot3").innerHTML = "E-mail уже активирован";
						document.getElementById("form_login").email.value = f.email.value;
						document.getElementById("form_login").pass.focus();
					break;
					case "ok":
						location = "card.php";
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
function sendcode()
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		var f = document.getElementById("form_act");
		oXmlHttp.open("GET", "index.php?act=sendcode&email="+f.email.value, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				switch(result)
				{
					case "eemail":
						document.getElementById("foot3").innerHTML = "Неверный E-mail";
					break;
					case "eemailsend":
						document.getElementById("foot3").innerHTML = "Возникла ошибка при отправке E-mail";
					break;
					case "eactive":
						document.getElementById("foot3").innerHTML = "E-mail уже активирован";
						document.getElementById("form_login").email.value = f.email.value;
						document.getElementById("form_login").pass.focus();
					break;
					case "ok":
						document.getElementById("foot3").innerHTML = "Код отправлен на e-mail";
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
function recovery()
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		var f = document.getElementById("form_rec");
		oXmlHttp.open("GET", "index.php?act=recovery&email="+f.email.value, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				switch(result)
				{
					case "eemail":
						document.getElementById("foot4").innerHTML = "Неверный E-mail";
					break;
					case "eemailsend":
						document.getElementById("foot4").innerHTML = "Возникла ошибка при отправке E-mail";
					break;
					case "eunactive":
						document.getElementById("foot4").innerHTML = "E-mail не активирован";
						document.getElementById("form_act").email.value = f.email.value;
						document.getElementById("form_act").code.focus();
						document.getElementById('card3').style.display = '';
					break;
					case "ok":
						document.getElementById("foot4").innerHTML = "Новый пароль отправлен на e-mail";
					break;
				}
				wait(0, "wait4");
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		oXmlHttp.send(null);
		wait(1, "wait4");
	}
}
function login()
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		var f = document.getElementById("form_login");
		oXmlHttp.open("GET", "index.php?act=login&email="+f.email.value+"&pass="+f.pass.value+"&alien="+f.alien.value, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				switch(result)
				{
					case "eemail":
						document.getElementById("foot1").innerHTML = "Неверный E-mail";
					break;
					case "eunactive":
						document.getElementById("foot1").innerHTML = "E-mail не активирован";
						document.getElementById("form_act").email.value = f.email.value;
						document.getElementById("form_act").code.focus();
						document.getElementById('card3').style.display = '';
					break;
					case "epass":
						document.getElementById("foot1").innerHTML = "Неверный пароль";
					break;
					case "ok":
						location = "card.php";
					break;
				}
				wait(0, "wait1");
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
       	oXmlHttp.send(null);
       	wait(1, "wait1");
	}
}
function show_activation()
{
	document.getElementById('card3').style.display = '';
	if(document.getElementById('ent_email').value != "") document.getElementById('act_email').value = document.getElementById('ent_email').value;
}
function show_reset()
{
	document.getElementById('card4').style.display = '';
	if(document.getElementById('ent_email').value != "") document.getElementById('reset_email').value = document.getElementById('ent_email').value;
}
function wait(onoff, element_id)
{
	if(onoff == 1)
	{
		document.getElementById(element_id).style.display = "block";
	}
	else
	{
		document.getElementById(element_id).style.display = "none";
	}
}
function remove_card(id)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", "index.php?act=removecard&id="+id, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				if(result == "ok") document.getElementById('card'+id).style.display = 'none';
				else document.getElementById('foot'+id).innerHTML = "Ошибка сервера";
				wait(0, "wait"+id);
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
       	oXmlHttp.send(null);
       	wait(1, "wait"+id);
	}
}
function addcards()
{
	document.getElementById('addcardspanel').style.display = '';
}
function addcard(id)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", "index.php?act=retrievecard&id="+id, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				var result = oXmlHttp.responseText;
				
				var odiv = document.createElement("div");
				odiv.id = "card"+id;
				odiv.className = "card";
				odiv.style.width = "236px";
				odiv.style.position = "relative";
				odiv.style.styleFloat = "left";
				odiv.innerHTML = result;
				
				document.getElementById("3dscene0").appendChild(odiv);
				//wait(0, "wait"+id);
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
       	oXmlHttp.send(null);
       	//wait(1, "wait"+id);
	}
}
//-->