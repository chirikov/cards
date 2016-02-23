<!--//

//	Copyright (c) Roman Chirikov, 2008-2009

var action = 0;

function photoactionchange(el)
{
	switch(el.value)
	{
	case 'rotate':
		document.getElementById('rotatevalue').style.display = '';
		document.getElementById('brightnessvalue').style.display = 'none';
		document.getElementById('contrastvalue').style.display = 'none';
	break;
	case 'brightness':
		document.getElementById('rotatevalue').style.display = 'none';
		document.getElementById('brightnessvalue').style.display = '';
		document.getElementById('contrastvalue').style.display = 'none';
	break;
	case 'contrast':
		document.getElementById('rotatevalue').style.display = 'none';
		document.getElementById('brightnessvalue').style.display = 'none';
		document.getElementById('contrastvalue').style.display = '';
	break;
	default:
		document.getElementById('rotatevalue').style.display = 'none';
		document.getElementById('brightnessvalue').style.display = 'none';
		document.getElementById('contrastvalue').style.display = 'none';
	}
}
function scene_mousedown()
{
	switch(action)
	{
		case "selectarea":
		var ot = document.createElement("DIV");
		ot.id = "area";
		ot.className = "pe_selection";
		ot.style.left = event.offsetX;
		ot.style.top = event.offsetY - document.getElementById("dscene").offsetHeight;
		//ot.style.height = 0;
		document.getElementById("dscene").appendChild(ot);
		event.cancelBubble = true; 
		event.returnValue = false;
		break;
	}
}
function scene_mouseup()
{
	switch(action)
	{
		case "selectarea":
		action = 0;
		break;
	}
}
function scene_mousemove()
{
	switch(action)
	{
		case "selectarea":
		if(event.button == 1)
		{
			event.cancelBubble = true; 
			event.returnValue = false;
			
			var nw, nh;
			
			nw = event.offsetX - parseInt(document.getElementById("area").style.left);
			nh = event.offsetY;
			
			if(nh < 1) nh = 1;
			
			if(nw >= 0) document.getElementById("area").style.width = nw;
			if(nh >= 0) document.getElementById("area").style.height = nh;
			
			document.getElementById("qwe").innerHTML = nh;
			
			//if(nw < 0)
			//{
				//document.getElementById("area").style.left = event.offsetX;
			//}
			
			//document.getElementById("qwe").innerHTML = nw;
			
			//document.getElementById("area").style.height = Math.abs(event.offsetY - document.getElementById("dscene").offsetHeight - parseInt(document.getElementById("area").style.top));
		}
		break;
	}
}

//-->