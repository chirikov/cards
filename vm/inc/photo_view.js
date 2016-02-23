<!--//

var timer_scroll = null, me = false, pnum, opinterval = new Array, deltaop = new Array, recycles = new Array, toset, pic;
var minop = 0.6, maxop = 1, minops = 0, maxops = 1, prefix;
var photos = new Array;

function StartScroll(dir)
{
	scrollphotoline(dir);
	if (timer_scroll != null) clearInterval(timer_scroll);
	timer_scroll = setInterval('scrollphotoline(\"'+dir+'\");', 10);
}
function StopScroll()
{
	if (timer_scroll != null)
	{
		clearInterval(timer_scroll);
		timer_scroll = null;
	}
}
function scrollphotoline(dir)
{
	var photoline = document.getElementById('photoline');
	photoline.scrollLeft += dir*5;
	if (photoline.scrollLeft == 0 || photoline.scrollLeft == photoline.scrollWidth) clearInterval(timer_scroll);
}
function imgclick(code, id, i)
{
	if(toset != prefix+code+'.jpg')
	{
		toset = prefix+code+'.jpg';
		
		pic = new Image();
		pic.alt = "0";
		pic.onload = function() {pic.alt = "1";}
		pic.title = document.getElementById('thumb'+i).title;
		pic.src = toset;
		
		if(me) 
		{
			document.getElementById('edithref').href = 'photoeditor.php?act=edit&pid='+id;
			document.getElementById('maphref').href = 'map.php?act=mapphoto&pid='+id;
		}
		//if(typeof document.body.style.opacity == 'string')
		//{
		goopacity(document.getElementById('thumb'+i), maxop, false);
		goopacity(document.getElementById('scene'), minops, 1);
		for(j=1; j<=pnum; j++)
		{
			if(i != j) goopacity(document.getElementById('thumb'+j), minop, false);
		}
		//}
		//else document.getElementById('scene').src = toset;
	}
}
function goopacity(elem, val, recycle)
{
	var opacity = getopacity(elem);
	deltaop[elem.id] = (val - opacity)/40;
	recycles[elem.id] = recycle;
	if(deltaop[elem.id] != 0) opinterval[elem.id] = setInterval('opacy(\"'+elem.id+'\");', 10);
}
function opacy(elemid)
{
	var elem = document.getElementById(elemid);
	var opacity = getopacity(elem);
	opacity -= -deltaop[elemid];
	setopacity(elem, opacity);
	if(recycles[elemid] == 1)
	{
		if(opacity <= minops)
		{
			setopacity(elem, minops);
			clearInterval(opinterval[elemid]);
			document.getElementById("divwait").style.visibility = "visible";
			setscene();
		}
	}
	else if(recycles[elemid] == 2)
	{	
		if(opacity >= maxops)
		{
			setopacity(elem, maxops);
			clearInterval(opinterval[elemid]);
		}
	}
	else
	{
		if(opacity <= minop)
		{
			setopacity(elem, minop);
			clearInterval(opinterval[elemid]);
		}
		if(opacity >= maxop)
		{
			setopacity(elem, maxop);
			clearInterval(opinterval[elemid]);
		}
	}
}
function getopacity(elem)
{
	if(typeof document.body.style.opacity == 'string') return elem.style.opacity;
	else if(document.body.filters)
	{
		var fil = elem.style.filter;
		var st = fil.search("=");
		
		return fil.substr(st+1, fil.length-st-2)/100;
	}
}
function setopacity(elem, val)
{
	if(typeof document.body.style.opacity == 'string') elem.style.opacity = val;
	else if(document.body.filters)
	{
		elem.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity="+val*100+")";
	}
}
function setscene()
{
	if(pic.alt != "1")
	{
		setTimeout("setscene();", 500);
	}
	else
	{
		var elem = document.getElementById('scene');
		elem.src = toset;
		document.getElementById("divwait").style.visibility = "hidden";
		
		var pname = pic.title;
		if(pname != "")
		{
			document.getElementById("divphotoname").style.display = "";
			document.getElementById("spanphotoname").innerHTML = pname;
		}
		else document.getElementById("divphotoname").style.display = "none";
		
		goopacity(elem, maxops, 2);
	}
}
function preload()
{
	var chk = document.getElementById('ppreload');
	if(chk.checked)
	{
		for(i=0; i<photos.length; i++)
		{
			var pic2 = new Image();
			pic2.src = photos[i];
		}
	}
}

//-->