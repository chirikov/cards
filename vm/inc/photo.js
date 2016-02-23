<!--//

//	Copyright (c) Roman Chirikov, 2008-2009

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

function newfolder()
{
	open_win('<form action="javascript: ajaxnewfolder(\'folder\');">�������� �����: <input type="text" id="sname" name="name" maxlength="50"> <input type="submit" value="�������"></form>');
}

function newalbum()
{
	open_win('<form action="javascript: ajaxnewfolder(\'album\');">�������� �������: <input type="text" id="sname" name="name" maxlength="50"> <input type="submit" value="�������"></form>');
}

function ajaxnewfolder(type)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		var sname = document.getElementById("sname").value;
		oXmlHttp.open("GET", "photo.php?act=new"+type+"&name="+sname, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				win_wait(0);
				var resp = oXmlHttp.responseText;
				if(resp == "ok")
				{
					close_win();
					addfolder(type, sname);
				}
				else if(resp == "ename") win_error("������� ��������");
			}
		}
		oXmlHttp.send(null);
		win_wait(1);
	}
}

function addfolder(type, sname)
{
	alert(sname);
}



//-->