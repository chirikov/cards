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
function getvkfriends(fio, pvkid)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		var vkid;
		pvkid>0?vkid=pvkid:vkid=fvkid.vkid.value;
		oXmlHttp.open("GET", "vk.php?act=getvkfriends&vkid="+vkid+"&vkonline="+fvkid.vkonline.checked+"&vkidsave="+fvkid.vkonline.checked+"&fio="+encodeURIComponent(fio), true);
		oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 1)
			{
				wait(1);
			}
			if(oXmlHttp.readyState == 4)
			{
				var result = oXmlHttp.responseText;
				switch(result)
				{
					case "eprivacy":
						response(1, "Вы не разрешаете просматривать свой список друзей. Разрешите это делать всем пользователям в <a href='http://vkontakte.ru/settings.php?act=privacy'>настройках приватности</a>.");
					break;
					case "enofriends":
						response(1, "У Вас нет друзей ВКонтакте");
					break;
					default:
						response(0, result);
					break;
				}
				wait(0);
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
       	oXmlHttp.send();
	}
}
function sendvkmes(captcha)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		if(captcha) var url = fcid.old_url.value + "&fccode="+fcid.fccode.value + "&fcsid="+fcid.cid.value;
		else
		{
			var url = "vk.php?act=sendvkmes&num="+fvkfriends.num.value+"&text="+encodeURIComponent(fvkfriends.text.value);
			for(i=1; i<=fvkfriends.num.value; i++)
			{
				if(document.getElementById("friend"+i).checked == true) url += "&friend"+i+"="+document.getElementById("friend"+i).value;
			}
		}
		oXmlHttp.open("GET", url, true);
		oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 1)
			{
				wait(1);
			}
			if(oXmlHttp.readyState == 4)
			{
				var result = oXmlHttp.responseText;
				switch(result)
				{
					case "eflood":
						var cid = Math.round(Math.random()*100000000);
						alert(cid);
						response(1, "Введите, пожалуйста, код с картинки. <form name='fcid' method='post' action='javascript: sendvkmes(true);'><input type='hidden' name='old_url' value='"+url+"'><input type='hidden' name='cid' value='"+cid+"'><img src='http://userapi.com/data?act=captcha&csid="+cid+"'><input type='text' name='fccode'><input type='submit' value='OK' name='go'></form>");
					break;
					case "ok":
						location = "profile.php?act=home";
					break;
				}
				wait(0);
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		oXmlHttp.send();
	}
}
function wait(onoff)
{
	if(onoff == 1)
	{
		//document.getElementById("uajaxloader").style.display = "block";
	}
	else
	{
		//document.getElementById("uajaxloader").style.display = "none";
	}
}
function response(iserror, text)
{
	if(iserror == 1)
	{
		document.getElementById("tdresult").style.backgroundColor = "Red";
	}
	else 
	{
		document.getElementById("tdresult").style.backgroundColor = "Blue";
	}
	document.getElementById("tdresult").innerHTML = text;
	document.getElementById("tableresult").style.display = "block";
}
function allcheck(trigger)
{	
	for(i=0; i<fvkfriends.elements.length; i++)
	{
		if(fvkfriends.elements(i).type == "checkbox") fvkfriends.elements(i).checked = trigger;
	}
}
//-->