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

var DraggingItem = new Object(), clw, clh;

function StartDrag (event, _this) 
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