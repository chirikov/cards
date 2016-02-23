<!--//

//	Copyright (c) Roman Chirikov, 2008-2009

var isMSIE = document.attachEvent != null; 
var isGecko = !document.attachEvent && document.addEventListener; 

var timer_seen, lastmesid, browser = "ie", page, subpage, mir_id = 0, myname, talkerid = 0, messages = new Array();
switch(navigator.appName)
{
	case "Opera": browser = "opera"; break;
	case "Netscape": browser = "netscape"; break;
}

function showform(i)
{
	if (document.getElementById("divans"+i).style.display == "none") 
	{
		document.getElementById("but1"+i).style.display = "none";
		var inner = "<form id='form"+i+"' method='get'><table><!--[if IE 6]><col style='width:80px'/><![endif]--><input type='hidden' id='recepient' name='recepient' value='"+messages[i]['uid']+"' style='display: none;'><tr><td><label>Сообщение:</label></td><td><textarea name='text' id='textarea"+i+"' rows='3' cols='30' onkeypress='javascript: ctrlenter(event); if(this.value.length >= 1000) this.value = this.value.substr(0, 999);'></textarea></td>";
		inner += "</tr></table></form><div class='buttons'><a href='#' onclick='javascript: sendmes("+i+"); return false;' class='answer'>Отправить</a><a href='#' onclick='javascript: showform("+i+"); return false;' class='delete'>Отменить</a></div><div class='cl'>&nbsp;</div>";
		
		document.getElementById("divans"+i).innerHTML = inner;
		document.getElementById("divans"+i).style.display = "";
	}
	else
	{
		document.getElementById("divans"+i).style.display = "none";
		document.getElementById("but1"+i).style.display = "";
	}
}
function arrowclick(i)
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
			if(messages[i]['seen'] == 0 && subpage == "in") messages[i]['timer_seen'] = setTimeout("seenmes("+i+");", 2000);
		}
		else
		{
			if(messages[i]['seen'] == 0) messages[i]['timer_seen'] = setTimeout("seenmes("+i+");", 2000);
		}
	}
	else
	{
		if(subpage == "in") window.clearTimeout(messages[i]['timer_seen']);
		trf.style.display = "none";
		trs.style.display = "";
		trs.cells[0].style.borderWidth = "1px";
		trs.cells[1].style.borderWidth = "1px";
		if(page == "mail") trs.cells[2].style.borderWidth = "1px";
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
function seenmes(i)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", "mail.php?act=seen&mid="+messages[i]['id'], true);
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
function delmes(i)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", "mail.php?act=delete&mid="+messages[i]['id'], true);
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
					arrowclick(i);
					
					var trs = document.getElementById("trs"+i);
					trs.style.display = "none";
					if(messages[i]['seen'] == 0) newmesnum(-1);
				}
				if(page == "mail") allmesnum(-1);
				wait(0, "wait");
			}
		}
    	oXmlHttp.send(null);
    	wait(1, "wait");
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
					showform(i);
					var otr1 = document.getElementById('tableposts').insertRow(0);
					var cell1 = otr1.insertCell(-1);
					cell1.className = "message1";
					cell1.colSpan = 3;
					
					var inner = "<div class='message1' style='border: 1px solid #cacaca; background: #fff'><div class='message_date'><b><a href='profile.php?uid="+mir_id+"'>"+myname+"</a></b>: "+document.getElementById("textarea"+i).value+"</div><div class='cl'>&nbsp;</div></div>";
					
					cell1.innerHTML = inner;
					
					allmesnum(1);
				}
				else
				{
					arrowclick(i);
					var trs = document.getElementById("trs"+i);
					if(page == "profile")
					{
						trs.style.display = "none";
						trs.cells[0].style.borderWidth = 0;
						trs.cells[1].style.borderWidth = 0;
					}
					if(messages[i]['seen'] == 0) newmesnum(-1);
				}
				wait(0, "wait");
			}
		}
		oXmlHttp.send("recepient="+document.getElementById("recepient").value+"&text="+encodeURIComponent(document.getElementById("textarea"+i).value));
		wait(1, "wait");
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
			//if(page == "mail") url = "mail.php?act=checkajax&type=mail&lastmesid="+lastmesid;
			oXmlHttp.open("GET", url, true);
			oXmlHttp.onreadystatechange = function()
			{
				if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
				{
					var resp = eval("("+oXmlHttp.responseText+")");
					for(j=0; j<resp["num"]; j++)
					{
						if(j == resp["num"]-1) lastmesid = resp["messages"][j]["id"];	
						var i = j;
						var tro = 0;
						if(page == "mail") tro = 1;
						if(page == "mail" && subpage == "talks") tro = 0;
						var otr1 = document.getElementById('tableposts').insertRow(tro);
						
						while(document.getElementById("trs"+i))
						{
							i++;
						}
						
						messages[i] = new Array(4);
						messages[i]['id'] = resp["messages"][j]["id"];
						messages[i]['uid'] = resp["messages"][j]["senderid"];
						messages[i]['seen'] = 0;
						
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
							//if(j == resp["num"]-1) lastmesid = resp["messages"][j]["id"];
							
							if(subpage == "talks")
							{
								cell1.className = "message1";
								cell1.colSpan = 3;
								
								var inner = "<div class='message1' style='border: 1px solid #cacaca; background: #ffffff'><div class='message_date'>"+resp["messages"][j]["time"]+"</div><div class='message_date'><b><a href='card.php?id="+resp["messages"][j]["senderid"]+"'>"+resp["messages"][j]["sendername"]+"</a></b>: "+resp["messages"][j]["text"]+"</div>";
								if(mir_id != resp["messages"][j]["senderid"]) inner += "<div id='but1"+i+"' class='buttons'><a href='#' onclick='javascript: showform("+i+"); return false;' class='answer'>Ответить</a><a href='#' onclick='javascript: var a = confirm(\"Удалить сообщение?\"); if(a) delmes("+i+"); return false;' class='delete'>Удалить</a></div><div id='divans"+i+"' style='display: none;' class='answer_text'></div>";
								inner += "<div class='cl'>&nbsp;</div></div>";
								
								cell1.innerHTML = inner;
								
								setTimeout("seenmes("+i+")", 2000);
							}
							else
							{
								var cell4 = otr1.insertCell(-1);
								
								var text = resp["messages"][j]["text"];
								var nlp = text.indexOf("<br />");
								if(nlp < 60 && nlp != -1) var preview = text.substr(0, nlp)+"...";
								else
								{
									var preview = text.substr(0, 60);
									if(text.length > 60) preview += "...";
								}
								
								cell1.innerHTML = resp["messages"][j]["time"];
								cell4.innerHTML = "<a href='#' class='more' onclick='javascript: arrowclick("+i+"); return false;'><img src='images/arrow2.gif' alt='V'/></a>"+preview;
								cell2.innerHTML = "<b><a href='card.php?id="+resp["messages"][j]["senderid"]+"'>"+resp["messages"][j]["sendername"]+"</a></b>";
								
								cell3.colSpan = 3;
								
								cell3.innerHTML = inner_template(i, resp["messages"][j]["senderid"], resp["messages"][j]["text"], resp["messages"][j]["sendername"], resp["messages"][j]["time"]);
								
								/*
								mid = resp[(4*j+1)];
								uid = resp[(4*j)];
								text = resp[(4*j-1)];
								name = resp[(4*j-2)];
								date = resp[(4*j-3)];
								*/
							}
							allmesnum(resp["num"]);
						}
						/*
						else
						{				
							if(j == resp[0]) lastmesid = resp[(4*j)];	
								
							cell1.innerHTML = "<b><a href='card.php?id="+resp[(4*j-1)]+"'>"+resp[(4*j-3)]+"</a></b>";
							cell2.innerHTML = "<a href='#' class='more' onclick='javascript: arrowclick("+i+");'><img src='images/arrow2.gif' alt='V'/></a>"+resp[(4*j-2)].substr(0, 70);
							if(resp[(4*j-2)].length > 70) cell2.innerHTML += "...";

							cell3.colSpan = 2;
							
							cell3.innerHTML = inner_template(i, resp[(4*j-1)], resp[(4*j-2)], resp[(4*j-3)], 0);
							
							/*
							mid = resp[(4*j)];
							uid = resp[(4*j-1)];
							text = resp[(4*j-2)];
							name = resp[(4*j-3)];
							*
						}
						*/
					}
					newmesnum(resp["num"]);
					wait(0, "wait");
				}
			}
			oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
			wait(1, "wait");
			oXmlHttp.send(null);
		}
	}
}
function inner_template(i, uid, text, name, date)
{
	var inner = "<div class='message1'>";
	if(date != 0) inner += "<div class='message_date'>"+date+"</div>";
	inner += "<a href='#' onclick='javascript: arrowclick("+i+"); return false;' class='more'><img src='images/arrow3.gif' alt='^'/></a><div class='message_text'><table><!--[if IE 6]><col style='width:80px'/><![endif]-->";
	inner += "<tr><td>От кого:</td><td><b><a href='card.php?id="+uid+"'>"+name+"</a></b></td></tr><tr><td>Сообщение:</td><td>"+text+"</td>";
	inner += "</tr></table></div><div id='but1"+i+"' class='buttons'><a href='#' onclick='javascript: showform("+i+"); return false;' class='answer'>Ответить</a><a href='#' onclick='javascript: var a = confirm(\"Удалить сообщение?\"); if(a) delmes("+i+"); return false;' class='delete'>Удалить</a>";
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
	document.getElementById("allmesnum").innerHTML -= -d;
}
function ctrlenter(event)
{
	switch(browser)
	{
		case "ie":
		if(event.keyCode == 10) sendmes(event.srcElement.id.substr(8));
		break;
		case "netscape":
		if(event.keyCode == 13 && event.ctrlKey || event.keyCode == 10) sendmes(event.target.id.substr(8));
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

function addEvent(el, evnt, func)
{
	if(el.addEventListener) el.addEventListener(evnt, func, true);
	else if(el.attachEvent) el.attachEvent('on'+evnt, func);
}

function mail_install()
{
	if(subpage == "in" || (subpage == "talks" && talkerid != 0)) setInterval(checkmes, 5000);
}

addEvent(window, 'load', mail_install);

//-->