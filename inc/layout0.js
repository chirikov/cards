<!--//
/* 	 
	Copyright (c) Roman Chirikov, Andrew Shitov (Art. Lebedev Studio)
	Original drag mechanics was written by Mike Hall (http://www.brainjar.com/dhtml/drag/) in 2001. 
*/

var isMSIE = document.attachEvent != null; 
var isGecko = !document.attachEvent && document.addEventListener; 

var min_x=0, min_y=0, max_x, max_y, relx, rely, pagechanging = false, layout = new Array, browser = "ie", grid = false, gridv = 10;
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
		var desktopLayout = "["; 
		
		for (var c = 0; c != draggables.length; c++) 
		{
			var current = draggables[c]; 
			if (current.className == 'draggable on') 
			{
				var cid = current.id.substr(4);
				desktopLayout += '{"id":"'+cid+'", "x":"'+parseInt(current.style.left)+'", "y":"'+parseInt(current.style.top)+'"},';
				layout[cid+"_x"] = current.style.left;
				layout[cid+"_y"] = current.style.top;
			}
		}
		desktopLayout = desktopLayout.substr(0, desktopLayout.length-1)+"]";
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			oXmlHttp.open("POST", "settings.php?act=setlayout&tab="+tab, true);
			oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
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
	    	oXmlHttp.send("layout="+desktopLayout);
	    	document.getElementById("changepagehref").style.textDecorationNone = true;
			document.getElementById("changepagehref").style.cursor = "arrow";
			document.getElementById("changepagehref").innerHTML = "Подождите...";
	    }
	}
}

function show_grid()
{
	grid = 1 - grid;
	_this = document.getElementById("grid");
	if(grid) _this.innerHTML = 'Без сетки';
	else _this.innerHTML = 'Сетка';
}

function cancelpage()
{
	var draggables = document.getElementsByTagName('div'); 
	
	for(var c = 0; c != draggables.length; c++) 
	{
		var current = draggables[c]; 
		if(current.className == 'draggable on') 
		{
			var cid = current.id.substr(4);
			current.style.left = layout[cid+"_x"];
			current.style.top = layout[cid+"_y"];
		}
	}
	pageunlock(false)
}

function addEvent(el, evnt, func)
{
	if(el.addEventListener) el.addEventListener(evnt, func, true);
	else if(el.attachEvent) el.attachEvent('on'+evnt, func);
}

function removeEvent(el, evnt, func)
{
	if(el.addEventListener) el.removeEventListener(evnt, func, true);
	else if(el.attachEvent) el.detachEvent('on'+evnt, func);
}

function pageunlock(onoff)
{
	if(onoff)
	{
		document.getElementById("cancelpagehref").style.display = "inline";
		document.getElementById("defaultpagehref").style.display = "inline";
		document.getElementById("grid").style.display = "inline";
		document.getElementById("changepagehref").innerHTML = "Сохранить";
		pagechanging = true;
		var draggables = document.getElementsByTagName('div');
		for(var c = 0; c < draggables.length; c++)
		{
			var current = draggables[c];
			if(current.className == 'draggable')
			{
				current.className = "draggable on";
				//addEvent(current, "click", returnfalse);
			}
		}
	}
	else
	{
		document.getElementById("cancelpagehref").style.display = "none";
		document.getElementById("defaultpagehref").style.display = "none";
		document.getElementById("grid").style.display = "none";
		document.getElementById("changepagehref").innerHTML = "Изменить расположение";
		pagechanging = false;
		var draggables = document.getElementsByTagName('div');
		for(var c = 0; c < draggables.length; c++)
		{
			var current = draggables[c];
			if(current.className == 'draggable on')
			{
				current.className = "draggable";
				//removeEvent(current, "click", returnfalse);
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
		if (current.className == 'draggable on') 
		{ 
			current.style.left = 0;
			current.style.top = 0;
		}
	}
}
function getOffsetTop(element) {
    var offset = 0;
    do {
        offset += element.offsetTop;
    } while (element = element.offsetParent);
    return offset;
}
function getOffsetLeft(element) {
    var offset = 0;
    do {
        offset += element.offsetLeft;
    } while (element = element.offsetParent);
    return offset;
}
function returnfalse(event)
{
	if(browser == "ie") {event.cancelBubble = true; event.returnValue = false;}
	else if(browser == "opera" || browser == "netscape") {event.preventDefault(); return false;}
}
var DraggingItem = new Object();

function StartDrag (event, _this, parent) 
{ 
	if(pagechanging)
	{
		DraggingItem.This = _this; 
		DraggingItem.Width = DraggingItem.This.offsetWidth;
		DraggingItem.Height = DraggingItem.This.offsetHeight
		//DraggingItem.AfterAction = _afteraction; 
		//DraggingItem.This.style.zIndex = 100;
		
		var position = new Object();
		if (isMSIE)
		{
			position.x = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft; 
			position.y = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop; 
		}
		else if (isGecko)
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
		
		addEvent(document, "mousemove", ProceedDrag);
		addEvent(document, "mouseup", StopDrag);

		if(isMSIE)
		{
			window.event.cancelBubble = true; 
			window.event.returnValue = false;
			relx = event.x;
			rely = event.y;
		}
		else if(isGecko)
		{
			event.preventDefault();
			relx = event.layerX;
			rely = event.layerY;
		}
		
		min_x = getOffsetLeft(parent);
		min_y = getOffsetTop(parent);
		max_x = min_x + parent.clientWidth;
		max_y = min_y + parent.clientHeight;
	}
}
function ProceedDrag (event) 
{ 
	var position = new Object(); 
 
	if(isMSIE) { 
		position.x = event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft; 
		position.y = event.clientY + document.documentElement.scrollTop + document.body.scrollTop; 
	} 
	else if(isGecko) 
	{ 
		position.x = event.clientX + window.scrollX; 
		position.y = event.clientY + window.scrollY; 
	}	 
 
	var nextX = DraggingItem.StartLeft + position.x - DraggingItem.cursorStartX;
	if(grid) nextX = Math.round(nextX/gridv)*gridv;
	if (position.x - relx > min_x && position.x - relx + DraggingItem.Width < max_x) DraggingItem.This.style.left = nextX + "px";
	
	var nextY = DraggingItem.StartTop + position.y - DraggingItem.cursorStartY;
	if(grid) nextY = Math.round((nextY)/gridv)*gridv;
	if (position.y - rely > min_y && position.y - rely + DraggingItem.Height < max_y) DraggingItem.This.style.top = nextY + "px";
	
	if(isMSIE)
	{
		window.event.cancelBubble = true;
		window.event.returnValue = false;
	}
	else if(isGecko) event.preventDefault();
}
function StopDrag (event) 
{
	removeEvent(document, "mousemove", ProceedDrag);
	removeEvent(document, "mouseup", StopDrag);
 
	//if (DraggingItem.AfterAction) DraggingItem.AfterAction (DraggingItem.This); 
	//DraggingItem.This.style.zIndex = 2;
	return false;
}
//-->