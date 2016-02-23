<!--//

//	Copyright (c) Roman Chirikov, 2008-2009

var newcontnum, options_state = 0;

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
function addcontact(i, id)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", "contacts.php?act=ajaxaddcontact&cid="+id, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				document.getElementById("trcont"+i).style.display = "none";
				newcontnum -= 1;
				if(newcontnum == 0) document.getElementById("newconttable").style.display = "none";
				wait(0, "waitcont1");
			}
		}
    	oXmlHttp.send(null);
    	wait(1, "waitcont1");
    }
}
function ignorecontact(i, id)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", "contacts.php?act=ajaxignorecontact&cid="+id, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				document.getElementById("trcont"+i).style.display = "none";
				newcontnum -= 1;
				if(newcontnum == 0) document.getElementById("newconttable").style.display = "none";
				wait(0, "waitcont1");
			}
		}
    	oXmlHttp.send(null);
    	wait(1, "waitcont1");
    }
}
function editoption()
{
	var ooptions = document.getElementById("options");
	var oodiv = document.getElementById("options_div");
	if(options_state == 0)
	{
		options_state = 1;
		
		oodiv.style.display = "none";
		
		ooptions.value = oodiv.innerHTML.replace("<BR>", "");
		while(ooptions.value.search("<BR>") != -1)
		{
			ooptions.value = ooptions.value.replace("<BR>", "");
		}
		ooptions.style.display = "block";
		
		document.getElementById("options_href").innerHTML = "Сохранить";
		document.getElementById("options_href_cancel").style.display = "block";
	}
	else
	{
		location = "settings.php?act=options&text="+encodeURIComponent(ooptions.value);
	}
}
function canceloption()
{
	options_state = 0;
	document.getElementById("options").style.display = "none";
	document.getElementById("options_div").style.display = "block";
	document.getElementById("options_href").innerHTML = "Редактировать информацию";
	document.getElementById("options_href_cancel").style.display = "none";
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