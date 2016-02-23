<!--//
/* 	 
	Copyright (c) Roman Chirikov, Andrew Shitov (Art. Lebedev Studio)
	Original drag mechanics was written by Mike Hall (http://www.brainjar.com/dhtml/drag/) in 2001. 
*/

var isMSIE = document.attachEvent != null; 
var isGecko = !document.attachEvent && document.addEventListener; 

var relx, rely, pagechanging = false, layout = new Array, browser = "ie";
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
function changepage()
{
	if(pagechanging == false)
	{
		pageunlock(true)
	}
	else
	{
		var draggables = document.getElementsByTagName ('div'); 
		var desktopLayout = ''; 
		
		for (var c = 0; c != draggables.length; c++) 
		{
			var current = draggables[c]; 
			if (current.className == 'draggable') 
			{ 
				desktopLayout += current.id + ';' + parseInt(current.style.left) + ';' + parseInt(current.style.top) + '@';
				layout[current.id+"_x"] = current.style.left;
				layout[current.id+"_y"] = current.style.top;
			}
		}
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			oXmlHttp.open("GET", "settings.php?act=ajaxsavepage&layout="+desktopLayout, true);
			oXmlHttp.onreadystatechange = function()
			{
				if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
				{
					document.getElementById("changepagehref").style.textDecorationNone = false;
					document.getElementById("changepagehref").style.cursor = "hand";
					pageunlock(false);
				}
			}
			oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
	    	oXmlHttp.send(null);
	    	document.getElementById("changepagehref").style.textDecorationNone = true;
			document.getElementById("changepagehref").style.cursor = "arrow";
			document.getElementById("changepagehref").innerHTML = "Подождите...";
	    }
	}
}
function cancelpage()
{
	var draggables = document.getElementsByTagName('div'); 
	
	for(var c = 0; c != draggables.length; c++) 
	{
		var current = draggables[c]; 
		if(current.className == 'draggable') 
		{
			current.style.left = layout[current.id+"_x"];
			current.style.top = layout[current.id+"_y"];
		}
	}
	pageunlock(false)
}
function pageunlock(onoff)
{
	if(onoff)
	{
		document.getElementById("cancelpagehref").style.display = "inline";
		document.getElementById("defaultpagehref").style.display = "inline";
		document.getElementById("changepagehref").innerHTML = "Сохранить";
		pagechanging = true;
		var draggables = document.getElementsByTagName('div');
		for (var c = 0; c != draggables.length; c++)
		{
			var current = draggables[c];
			if (current.className == 'draggable')
			{
				current.style.cursor = "move";
				current.style.border = "1px dashed #aaa";
				if(isMSIE) document.attachEvent("onclick", returnfalse, event);
				if(isGecko) document.addEventListener("click", returnfalse, true);
			}
		}
	}
	else
	{
		document.getElementById("cancelpagehref").style.display = "none";
		document.getElementById("defaultpagehref").style.display = "none";
		document.getElementById("changepagehref").innerHTML = "Изменить вид страницы";
		pagechanging = false;
		var draggables = document.getElementsByTagName('div');
		for (var c = 0; c != draggables.length; c++)
		{
			var current = draggables[c];
			if (current.className == 'draggable')
			{
				current.style.cursor = "default";
				current.style.border = "0px solid transparent";
				if(isMSIE) document.detachEvent("onclick", returnfalse);
				if(isGecko) document.removeEventListener("click", returnfalse, true);
			}
		}
	}
}
function defaultpage()
{
	var draggables = document.getElementsByTagName ('div'); 
	var desktopLayout = ''; 
	
	for (var c = 0; c != draggables.length; c++) 
	{
		var current = draggables[c]; 
		if (current.className == 'draggable') 
		{ 
			current.style.left = 0;
			current.style.top = 0;
		}
	}
}
function returnfalse(event)
{
	if(browser == "ie") {event.cancelBubble = true; event.returnValue = false;}
	else if(browser == "opera" || browser == "netscape") {event.preventDefault(); return false;}
}
var DraggingItem = new Object(), clw, clh;

function StartDrag (event, _this) 
{ 
	if(pagechanging)
	{
		DraggingItem.This = _this; 
		//DraggingItem.AfterAction = _afteraction; 
		DraggingItem.This.style.zIndex = 10;
		
		var position = new Object();
		if (isMSIE)
		{
			position.x = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft; 
			position.y = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop; 
		}
		if (isGecko)
		{
			position.x = event.clientX + window.scrollX; 
			position.y = event.clientY + window.scrollY; 
		}
		
		DraggingItem.cursorStartX = position.x; 
		DraggingItem.cursorStartY = position.y; 
		
		DraggingItem.StartLeft = parseInt (DraggingItem.This.style.left); 
		DraggingItem.StartTop = parseInt (DraggingItem.This.style.top); 
		
		if (isNaN (DraggingItem.StartLeft)) DraggingItem.StartLeft = 0; 
		if (isNaN (DraggingItem.StartTop)) DraggingItem.StartTop = 0; 

		if(isMSIE)
		{
			document.attachEvent ("onmousemove", ProceedDrag);
			document.attachEvent ("onmouseup", StopDrag);
			window.event.cancelBubble = true; 
			window.event.returnValue = false;
			relx = event.x;
			rely = event.y;
		}
		if(isGecko)
		{
			document.addEventListener ("mousemove", ProceedDrag, true);
			document.addEventListener ("mouseup", StopDrag, true);
			event.preventDefault();
			relx = event.layerX;
			rely = event.layerY;
		}
		clw = window.document.body.clientWidth;
		clh = window.document.body.clientHeight;
	}
}
function ProceedDrag (event) 
{ 
	var position = new Object(); 
 
	if(isMSIE) { 
		position.x = event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft; 
		position.y = event.clientY + document.documentElement.scrollTop + document.body.scrollTop; 
	} 
	if(isGecko) 
	{ 
		position.x = event.clientX + window.scrollX; 
		position.y = event.clientY + window.scrollY; 
	}	 
 
	var nextX = DraggingItem.StartLeft + position.x - DraggingItem.cursorStartX;
	if (position.x - relx > 0 && position.x - relx + DraggingItem.This.offsetWidth < clw) DraggingItem.This.style.left = nextX + "px";
	
	var nextY = DraggingItem.StartTop + position.y - DraggingItem.cursorStartY;
	if (position.y - rely > 0 && position.y - rely + DraggingItem.This.offsetHeight < clh) DraggingItem.This.style.top = nextY + "px";
	
	if(isMSIE)
	{
		window.event.cancelBubble = true;
		window.event.returnValue = false;
	}
	if(isGecko) event.preventDefault();
}
function StopDrag (event) 
{
	if (isMSIE) 
	{ 
		document.detachEvent ("onmousemove", ProceedDrag); 
		document.detachEvent ("onmouseup", StopDrag); 
	} 
	if (isGecko) 
	{ 
		document.removeEventListener ("mousemove", ProceedDrag, true); 
		document.removeEventListener ("mouseup", StopDrag, true); 
	} 
 
	//if (DraggingItem.AfterAction) DraggingItem.AfterAction (DraggingItem.This); 
	DraggingItem.This.style.zIndex = 2;
	return false;
}
//-->