<!--//
/* 	 
	Copyright (c) Roman Chirikov, Andrew Shitov (Art. Lebedev Studio)
	Original drag mechanics was written by Mike Hall (http://www.brainjar.com/dhtml/drag/) in 2001. 
*/

var isMSIE = document.attachEvent != null; 
var isGecko = !document.attachEvent && document.addEventListener; 
var browser = "ie";
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

var Layout = {
	changing: false,
	layout: null,
	grid: false,
	gridx: 10,
	gridy: 10,
	tagname: "div",
	classname: "draggable",
	getid: function(id) {return id;},
	url: "",
	DraggingItem: {},
	parent: window.document.body,
	draggables: new Array(),
	
	install: function()
	{
		Layout.min_x = getOffsetLeft(Layout.parent);
		Layout.min_y = getOffsetTop(Layout.parent);
		Layout.max_x = Layout.min_x + Layout.parent.clientWidth;
		Layout.max_y = Layout.min_y + Layout.parent.clientHeight;
		
		var ml = false;
		if(Layout.layout == null)
		{
			ml = true;
			Layout.layout = {};
		}
		
		var draggables0 = document.getElementsByTagName(Layout.tagname);
		for(var c = 0; c < draggables0.length; c++)
		{
			var current = draggables0[c];
			if(current.className == Layout.classname)
			{
				var cid = Layout.getid(current.id);
				Layout.draggables[Layout.draggables.length] = current;
				if(ml)
				{
					Layout.layout[cid+"_x"] = 0;
					Layout.layout[cid+"_y"] = 0;
				}
			}
		}
	},
	
	changepageclick: function()
	{
		if(Layout.changing == false) Layout.pageunlock(true);
		else Layout.savepage();
	},
	
	savepage: function()
	{
		var desktopLayout = "[";
		
		for(var c = 0; c < Layout.draggables.length; c++)
		{
			var current = Layout.draggables[c];
			var cid = Layout.getid(current.id);
			desktopLayout += '{"id":"'+cid+'", "x":"'+parseInt(current.style.left)+'", "y":"'+parseInt(current.style.top)+'"},';
			Layout.layout[cid+"_x"] = current.style.left;
			Layout.layout[cid+"_y"] = current.style.top;
		}
		desktopLayout = desktopLayout.substr(0, desktopLayout.length-1)+"]";
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			oXmlHttp.open("POST", Layout.url, true);
			oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			oXmlHttp.onreadystatechange = function()
			{
				if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
				{
					document.getElementById("changepagehref").style.textDecorationNone = false;
					document.getElementById("changepagehref").style.cursor = "hand";
					Layout.pageunlock(false);
				}
			}
			oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
	    	oXmlHttp.send("layout="+desktopLayout);
	    	document.getElementById("changepagehref").style.textDecorationNone = true;
			document.getElementById("changepagehref").style.cursor = "arrow";
			document.getElementById("changepagehref").innerHTML = "Подождите...";
	    }
	},

	show_grid: function()
	{
		Layout.grid = 1 - Layout.grid;
		if(Layout.grid) document.getElementById("grid").innerHTML = 'Без сетки';
		else document.getElementById("grid").innerHTML = 'Сетка';
	},

	cancelpage: function()
	{
		for(var c = 0; c < Layout.draggables.length; c++)
		{
			var current = Layout.draggables[c];
			var cid = Layout.getid(current.id);
			current.style.left = Layout.layout[cid+"_x"];
			current.style.top = Layout.layout[cid+"_y"];
		}
		Layout.pageunlock(false)
	},

	pageunlock: function(onoff)
	{
		if(onoff)
		{
			document.getElementById("cancelpagehref").style.display = "inline";
			document.getElementById("defaultpagehref").style.display = "inline";
			document.getElementById("grid").style.display = "inline";
			document.getElementById("changepagehref").innerHTML = "Сохранить";
			Layout.changing = true;
			for(var c = 0; c < Layout.draggables.length; c++)
			{
				var current = Layout.draggables[c];
				current.className = Layout.classname+" on";
				addEvent(current, "click", returnfalse);
			}
		}
		else
		{
			document.getElementById("cancelpagehref").style.display = "none";
			document.getElementById("defaultpagehref").style.display = "none";
			document.getElementById("grid").style.display = "none";
			document.getElementById("changepagehref").innerHTML = "Изменить расположение";
			Layout.changing = false;
			for(var c = 0; c < Layout.draggables.length; c++)
			{
				var current = Layout.draggables[c];
				current.className = Layout.classname;
				removeEvent(current, "click", returnfalse);
			}
		}
	},
	
	defaultpage: function()
	{
		var desktopLayout = ''; 
		for(var c = 0; c < Layout.draggables.length; c++)
		{
			var current = Layout.draggables[c];
			current.style.left = 0;
			current.style.top = 0;
		}
	},
	
	StartDrag: function(event, _this) 
	{
		if(Layout.changing)
		{
			Layout.DraggingItem.This = _this; 
			Layout.DraggingItem.Width = Layout.DraggingItem.This.offsetWidth;
			Layout.DraggingItem.Height = Layout.DraggingItem.This.offsetHeight
			//DraggingItem.AfterAction = _afteraction; 
			//DraggingItem.This.style.zIndex = 100;
			
			var position = new Object();
			if(isMSIE)
			{
				position.x = event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
				position.y = event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
			}
			else if(isGecko)
			{
				position.x = event.clientX + window.scrollX;
				position.y = event.clientY + window.scrollY;
			}
			
			Layout.DraggingItem.cursorStartX = position.x; 
			Layout.DraggingItem.cursorStartY = position.y; 
			
			Layout.DraggingItem.StartLeft = parseInt(Layout.DraggingItem.This.style.left); 
			Layout.DraggingItem.StartTop = parseInt(Layout.DraggingItem.This.style.top); 
			
			if(isNaN(Layout.DraggingItem.StartLeft)) Layout.DraggingItem.StartLeft = 0; 
			if(isNaN(Layout.DraggingItem.StartTop)) Layout.DraggingItem.StartTop = 0;
			
			addEvent(document, "mousemove", Layout.ProceedDrag);
			addEvent(document, "mouseup", Layout.StopDrag);
			
			if(isMSIE)
			{
				window.event.cancelBubble = true; 
				window.event.returnValue = false;
				Layout.relx = event.x;
				Layout.rely = event.y;
			}
			else if(isGecko)
			{
				event.preventDefault();
				Layout.relx = event.layerX;
				Layout.rely = event.layerY;
			}
		}
	},

	ProceedDrag: function(event)
	{
		var position = new Object();
		
		if(isMSIE)
		{
			position.x = event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
			position.y = event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
		}
		else if(isGecko)
		{
			position.x = event.clientX + window.scrollX;
			position.y = event.clientY + window.scrollY;
		}
	 
		var nextX = Layout.DraggingItem.StartLeft + position.x - Layout.DraggingItem.cursorStartX;
		if(Layout.grid) nextX = Math.round(nextX/Layout.gridx)*Layout.gridx;
		if(position.x - Layout.relx > Layout.min_x && position.x - Layout.relx + Layout.DraggingItem.Width < Layout.max_x) Layout.DraggingItem.This.style.left = nextX + "px";
		
		var nextY = Layout.DraggingItem.StartTop + position.y - Layout.DraggingItem.cursorStartY;
		if(Layout.grid) nextY = Math.round((nextY)/Layout.gridy)*Layout.gridy;
		if(position.y - Layout.rely > Layout.min_y && position.y - Layout.rely + Layout.DraggingItem.Height < Layout.max_y) Layout.DraggingItem.This.style.top = nextY + "px";
		
		if(isMSIE)
		{
			window.event.cancelBubble = true;
			window.event.returnValue = false;
		}
		else if(isGecko) event.preventDefault();
	},
	
	StopDrag: function(event) 
	{
		removeEvent(document, "mousemove", Layout.ProceedDrag);
		removeEvent(document, "mouseup", Layout.StopDrag);
	 	
		//if (DraggingItem.AfterAction) DraggingItem.AfterAction (DraggingItem.This); 
		//DraggingItem.This.style.zIndex = 2;
		return false;
	}
}

addEvent(window, "load", Layout.install);
//-->