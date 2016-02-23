<!--//

//	Copyright (c) Roman Chirikov, 2008-2009

var isMSIE = document.attachEvent != null; 
var isGecko = !document.attachEvent && document.addEventListener; 

var timer_seen, lastmesid, browser = "ie", page, subpage = "in", mir_id = 0, myname;
switch(navigator.appName)
{
	case "Opera": browser = "opera"; break;
	case "Netscape": browser = "netscape"; break;
}

function showform(i, uid)
{
	if (document.getElementById("divans"+i).style.display == "none") 
	{
		document.getElementById("but1"+i).style.display = "none";
		
		var inner = "<table><!--[if IE 6]><col style='width:85px'/><![endif]--><form id='form"+i+"' method='get'><input type='hidden' id='recepient' name='recepient' value='"+uid+"' style='display: none;'><tr><td><label>Сообщение:</label></td><td><textarea name='text' id='textarea"+i+"' rows='3' cols='30' onkeypress='javascript: ctrlenter(event);'></textarea></td>";
		inner += "</tr></form></table><div class='buttons'><a href='#' onclick='javascript: sendmes("+i+");' class='answer'>Отправить</a><a href='#' onclick='javascript: showform("+i+", 0);' class='delete'>Отменить</a></div><div class='cl'>&nbsp;</div>";
		
		document.getElementById("divans"+i).innerHTML = inner;
		document.getElementById("divans"+i).style.display = "";
	}
	else
	{
		document.getElementById("divans"+i).style.display = "none";
		document.getElementById("but1"+i).style.display = "";
	}
}
function arrowclick(i, id)
{
	var trf = document.getElementById("trf"+i);
	var trs = document.getElementById("trs"+i);
	if(trf.style.display == "none")
	{
		trf.style.display = "";
		trs.style.display = "none";
		trs.cells[0].style.borderWidth = 0;
		trs.cells[1].style.borderWidth = 0;
		if(page == "mail")
		{
			trs.cells[2].style.borderWidth = 0;
			if(trs.style.backgroundColor == "#fffebf" && subpage == "in") timer_seen = setTimeout("seenmes("+i+", "+id+");", 2000);
		}
		else
		{
			if(trs.style.backgroundColor == "#fffebf") timer_seen = setTimeout("seenmes("+i+", "+id+");", 2000);
		}
	}
	else
	{
		if(subpage == "in") window.clearTimeout(timer_seen);
		trf.style.display = "none";
		trs.style.display = "";
		trs.cells[0].style.borderWidth = 1;
		trs.cells[1].style.borderWidth = 1;
		if(page == "mail") trs.cells[2].style.borderWidth = 1;
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
function seenmes(i, id)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", "mail.php?act=seen&mid="+id, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				document.getElementById("trs"+i).style.backgroundColor = "#ffffff";
				newmesnum(-1);
			}
		}
		oXmlHttp.send(null);
	}
}
function delmes(i, id)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", "mail.php?act=delete&mid="+id, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				if(subpage == "talks")
				{
					var trs = document.getElementById("trs"+i).style.display = "none";
				}
				else
				{
					arrowclick(i, 1);
					
					var trs = document.getElementById("trs"+i);
					trs.style.display = "none";
					if(trs.style.backgroundColor == "#fffebf") newmesnum(-1);
				}
				if(page == "mail") allmesnum(-1);
				wait(0, "waitmes1");
			}
		}
    	oXmlHttp.send(null);
    	wait(1, "waitmes1");
    }
}
function sendmes(i)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("POST", "mail.php?act=sendajax", true);
		oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				if(subpage == "talks")
				{
					showform(i, 0);
					var otr1 = document.getElementById('tableposts').insertRow(1);
					var cell1 = otr1.insertCell(-1);
					cell1.className = "message1";
					cell1.colSpan = 3;
					
					var inner = "<div class='message1' style='border: 1px solid #cacaca; background: #ffffff'><div class='message_date'><b><a href='profile.php?uid="+mir_id+"'>"+myname+"</a></b>: "+document.getElementById("textarea"+i).value+"</div><div class='cl'>&nbsp;</div></div>";
					
					cell1.innerHTML = inner;
					
					allmesnum(1);
				}
				else
				{
					arrowclick(i, 1);
					var trs = document.getElementById("trs"+i);
					if(page == "profile")
					{
						trs.style.display = "none";
						trs.cells[0].style.borderWidth = 0;
						trs.cells[1].style.borderWidth = 0;
					}
					if(trs.style.backgroundColor == "#fffebf") newmesnum(-1);
				}
				wait(0, "waitmes1");
			}
		}
		oXmlHttp.send("recepient="+document.getElementById("recepient").value+"&text="+encodeURIComponent(document.getElementById("textarea"+i).value));
		wait(1, "waitmes1");
	}
}
function checkmes()
{
	if(document.getElementById("auto").checked == true)
	{
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			var url = "mail.php?act=checkajax&lastmesid="+lastmesid;
			if(page == "mail") url = "mail.php?act=checkajax&type=mail&lastmesid="+lastmesid;
			oXmlHttp.open("GET", url, true);
			oXmlHttp.onreadystatechange = function()
			{
				if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
				{
					var resp = oXmlHttp.responseText.split("џ");
					for(j=1; j<=resp[0]; j++)
					{
						if(j == resp[0]) lastmesid = resp[(4*j)];
						var i = j;
						var tro = 0;
						if(page == "mail") tro = 1;
						var otr1 = document.getElementById('tableposts').insertRow(tro);
						
						while(document.getElementById("trs"+i))
						{
							i++;
						}
						otr1.id = "trs"+i;
						
						var cell1 = otr1.insertCell(-1);
						
						if(subpage != "talks")
						{
							var otr2 = document.getElementById('tableposts').insertRow(tro+1);
							
							otr1.style.backgroundColor = "#fffebf";
							otr2.style.display = "none";
							otr2.id = "trf"+i;
							
							var cell2 = otr1.insertCell(-1);
							var cell3 = otr2.insertCell(-1);
							cell3.className = "message1";
						}
						
						if(page == "mail")
						{
							if(j == resp[0]) lastmesid = resp[(4*j+1)];
							
							if(subpage == "talks")
							{
								cell1.className = "message1";
								cell1.colSpan = 3;
								
								var inner = "<div class='message1' style='border: 1px solid #cacaca; background: #ffffff'><div class='message_date'>"+resp[(4*j-3)]+"</div><div class='message_date'><b><a href='profile.php?uid="+resp[(4*j)]+"'>"+resp[(4*j-2)]+"</a></b>: "+resp[(4*j-1)]+"</div>";
								if(mir_id != resp[(4*j)]) inner += "<div id='but1"+i+"' class='buttons'><a href='#' onclick='javascript: showform("+i+", "+resp[(4*j)]+");' class='answer'>Ответить</a><a href='#' onclick='javascript: var a = confirm(\"Удалить сообщение?\"); if(a) delmes("+i+", "+resp[(4*j+1)]+"); return false;' class='delete'>Удалить</a></div><div id='divans"+i+"' style='display: none;' class='answer_text'></div>";
								inner += "<div class='cl'>&nbsp;</div></div>";
								
								cell1.innerHTML = inner;
								
								setTimeout("seenmes("+i+", "+resp[(4*j+1)]+")", 2000);
							}
							else
							{
								var cell4 = otr1.insertCell(-1);
								
								cell1.innerHTML = resp[(4*j-3)];
								cell4.innerHTML = "<a href='#' class='more' onclick='javascript: arrowclick("+i+", "+resp[(4*j+1)]+");'><img src='images/arrow2.gif' alt='V'/></a>"+resp[(4*j-1)].substr(0, 55);
								if(resp[(4*j-1)].length > 55) cell4.innerHTML += "...";
								cell2.innerHTML = "<b><a href='profile.php?uid="+resp[(4*j)]+"'>"+resp[(4*j-2)]+"</a></b>";
								
								cell3.colSpan = 3;
								
								cell3.innerHTML = inner_template(i, resp[(4*j+1)], resp[(4*j)], resp[(4*j-1)], resp[(4*j-2)], resp[(4*j-3)]);
							}
							allmesnum(resp[0]);
						}
						else
						{				
							if(j == resp[0]) lastmesid = resp[(4*j)];	
								
							cell1.innerHTML = "<b><a href='profile.php?uid="+resp[(4*j-1)]+"'>"+resp[(4*j-3)]+"</a></b>";
							cell2.innerHTML = "<a href='#' class='more' onclick='javascript: arrowclick("+i+", "+resp[(4*j)]+");'><img src='images/arrow2.gif' alt='V'/></a>"+resp[(4*j-2)].substr(0, 70);
							if(resp[(4*j-2)].length > 70) cell2.innerHTML += "...";

							cell3.colSpan = 2;
							
							cell3.innerHTML = inner_template(i, resp[(4*j)], resp[(4*j-1)], resp[(4*j-2)], resp[(4*j-3)], 0);
						}
					}
					newmesnum(resp[0]);
					wait(0, "waitmes1");
				}
			}
			oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
			wait(1, "waitmes1");
			oXmlHttp.send(null);
		}
	}
}
function inner_template(i, mid, uid, text, name, date)
{
	var inner = "<div class='message1'>";
	if(date != 0) inner += "<div class='message_date'>"+date+"</div>";
	inner += "<a href='#' onclick='javascript: arrowclick("+i+", "+mid+");' class='more'><img src='images/arrow3.gif' alt='^'/></a><div class='message_text'><table><!--[if IE 6]><col style='width:85px'/><![endif]-->";
	inner += "<tr><td>От кого:</td><td><b><a href='profile.php?uid="+uid+"'>"+name+"</a></b></td></tr><tr><td>Сообщение:</td><td>"+text+"</td>";
	inner += "</tr></table></div><div id='but1"+i+"' class='buttons'><a href='#' onclick='javascript: showform("+i+", "+uid+");' class='answer'>Ответить</a><a href='#' onclick='javascript: var a = confirm(\"Удалить сообщение?\"); if(a) delmes("+i+", "+mid+"); return false;' class='delete'>Удалить</a>";
	inner += "</div><div id='divans"+i+"' style='display: none;' class='answer_text'></div><div class='cl'>&nbsp;</div></div>";
	
	return inner;
}
function newmesnum(d)
{
	var lmn = document.getElementById("leftmesnum");
	var curmesnum = lmn.innerHTML.substr(1, lmn.innerHTML.search("]")-1); 
	curmesnum -= -d;
	if(curmesnum > 0) lmn.innerHTML = "["+curmesnum+"]";
	else lmn.innerHTML = "";
}
function allmesnum(d)
{
	var amn = document.getElementById("summ").innerHTML -= -d;
}
function ctrlenter(event)
{
	switch(browser)
	{
		case "ie":
		if(event.keyCode == 10) sendmes(event.srcElement.id.substr(8));
		break;
		case "netscape":
		if(event.keyCode == 13 && event.ctrlKey) sendmes(event.target.id.substr(8));
		break;
		case "opera":
		if(event.keyCode == 13 && event.ctrlKey) sendmes(event.target.id.substr(8));
		break;
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