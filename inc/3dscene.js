<!--//

// Copyright 2009 Roman Chirikov

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

function clearSelection()
{
	var sel;
	if(document.selection && document.selection.empty) document.selection.empty();
	else if(window.getSelection)
	{
		sel = window.getSelection();
		if(sel && sel.removeAllRanges) sel.removeAllRanges();
	}
}

function getOpacity(elem)
{
	if(typeof document.body.style.opacity == 'string') return elem.style.opacity;
	else if(document.body.filters)
	{
		var fil = elem.style.filter;
		var st = fil.search("=");
		
		return fil.substr(st+1, fil.length-st-2)/100;
	}
}

function setOpacity(elem, val)
{
	if(typeof document.body.style.opacity == 'string') elem.style.opacity = val;
	else if(document.body.filters)
	{
		elem.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity="+val*100+")";
	}
}

var Scene = {
	id: "3dscene",
	This: null,
	elements: new Array(),
	startx: 0,
	starty: 0,
	top: 0,
	left: 0,
	width: 0,
	height: 0,
	dx: 0,
	dy: 0,
	speedx: 0,
	speedy: 0,
	radius: 200,
	autoradius: false,
	mnx: 1,
	speedtimer: 0,
	
	tf2xyz: function(teta, fi)
	{
		var x = Scene.radius * Math.sin(teta) * Math.cos(fi);
		var y = -Scene.radius * Math.sin(fi);
		var z = Scene.radius * Math.cos(teta) * Math.cos(fi);
		
		return {"x": x, "y": y, "z": z};
	},
	
	xyz2tf: function(x, y, z)
	{
		var fi = -Math.asin(y/Math.sqrt(x*x+y*y+z*z));
		var teta = Math.atan(x/z);
		if(z < 0) teta = Math.PI + teta;
		
		return {"teta": teta, "fi": fi};
	},
	
	getForce: function(teta1, fi1, teta2, fi2)
	{
		var d1 = Scene.tf2xyz(teta1, fi1);
		var d2 = Scene.tf2xyz(teta2, fi2);
		
		var d3 = {"x": d2["x"]-d1["x"], "y": d2["y"]-d1["y"], "z": d2["z"]-d1["z"]};
		var d3l = Math.sqrt(d3["x"]*d3["x"] + d3["y"]*d3["y"] + d3["z"]*d3["z"]);
		d3["x"] = d3["x"]/d3l*10000;
		d3["y"] = d3["y"]/d3l*10000;
		d3["z"] = d3["z"]/d3l*10000;
		
		return d3;
	},
	
	install: function()
	{
		if(document.getElementById(Scene.id))
		{
			if(Scene.autoradius) Scene.mnx = (document.body.offsetWidth-250)/2/Scene.radius;
			Scene.speedtimer = 0;
			
			Scene.This = document.getElementById(Scene.id);
			Scene.top = getOffsetTop(Scene.This);
			Scene.left = getOffsetLeft(Scene.This);
			Scene.width = parseInt(Scene.This.offsetWidth);
			Scene.height = parseInt(Scene.This.offsetHeight);
			Scene.hc = Scene.left + Scene.width/2;
			Scene.vc = Scene.top + Scene.height/2;
			
			var els = Scene.This.children;
			if(els.length > 0) Scene.elements[0] = {"this": els[0], "teta": 0, "fi": Math.PI/2, "x": 0, "y": 0, "z": 0, "tx": 0, "ty": 0, "tz": 0, "kzw": 0, "kzh": 0};
			for(i=1; i<els.length; i++)
			{
				Scene.elements[i] = {"this": els[i], "teta": 0.3*i, "fi": 0, "x": 0, "y": 0, "z": 0, "tx": 0, "ty": 0, "tz": 0, "kzw": 0, "kzh": 0};
			}
			
			var sdvig = 1;
			while(sdvig > 0.001)
			{
				sdvig = 0;
				for(i=1; i<els.length; i++)
				{
					var sdvig2 = 1;
					while(sdvig2 > 0.001)
					{
						sdvig2 = 0;
						var summforce = {"x": 0, "y": 0, "z": 0};
						for(j=0; j<els.length; j++)
						{
							if(j != i)
							{
								var forcej = Scene.getForce(Scene.elements[j]["teta"], Scene.elements[j]["fi"], Scene.elements[i]["teta"], Scene.elements[i]["fi"]);
								summforce["x"] += forcej["x"];
								summforce["y"] += forcej["y"];
								summforce["z"] += forcej["z"];
							}
						}
						var ntf = Scene.xyz2tf(summforce["x"], summforce["y"], summforce["z"]);
						sdvig += Scene.elements[i]["teta"] - ntf["teta"] + Scene.elements[i]["fi"] - ntf["fi"];
						sdvig = Math.abs(sdvig);
						if(els.length <= 8)
						{
							sdvig2 += Scene.elements[i]["teta"] - ntf["teta"] + Scene.elements[i]["fi"] - ntf["fi"];
							sdvig2 = Math.abs(sdvig2);
						}
						Scene.elements[i]["teta"] = ntf["teta"];
						Scene.elements[i]["fi"] = ntf["fi"];
					}
				}
			}
			Scene.setElements();
		}
	},
	
	setElements: function()
	{
		for(var i=0; i<Scene.elements.length; i++)
		{
			var r = Scene.tf2xyz(Scene.elements[i]["teta"], Scene.elements[i]["fi"]);
			
			Scene.elements[i]["x"] = r['x'];
			Scene.elements[i]["y"] = r['y'];
			Scene.elements[i]["z"] = r['z'];
			
			var kz = (r['z']/Scene.radius + 1)/2; // 1...0
			setOpacity(Scene.elements[i]["this"], kz);
			Scene.elements[i]["this"].style.position = "absolute";
			
			Scene.elements[i]["this"].style.zIndex = parseInt(r['z']+Scene.radius);
			
			Scene.elements[i]["this"].style.left = Scene.left + parseInt(Scene.This.offsetWidth)/2 - Scene.elements[i]["this"].offsetWidth/2 + Scene.mnx*r['x'] + "px";
			Scene.elements[i]["this"].style.top = Scene.top + parseInt(Scene.This.offsetHeight)/2 - Scene.elements[i]["this"].offsetHeight/2 + r['y'] + "px";
		}
	},
	
	unsetElements: function()
	{
		for(var i=0; i<Scene.elements.length; i++)
		{
			Scene.elements[i]["this"].style.position = "relative";
			Scene.elements[i]["this"].style.zIndex = Scene.radius;
			Scene.elements[i]["this"].style.left = 0 + "px";
			Scene.elements[i]["this"].style.top = 0 + "px";
			setOpacity(Scene.elements[i]["this"], 1);
		}
	},
	
	dragstart: function(event)
	{
		if(Scene.autoradius) Scene.mnx = (document.body.offsetWidth-250)/2/Scene.radius;
		
		Scene.inertionstop();
		
		Scene.startx = event.clientX - Scene.left;
		Scene.starty = event.clientY - Scene.top;
		Scene.lastdx = 0;
		Scene.lastdy = 0;
		Scene.speedx = 0;
		Scene.speedy = 0;
		
		addEvent(document, "mousemove", Scene.drag);
		addEvent(document, "mouseup", Scene.dragstop);
	},
	
	drag: function(event)
	{
		Scene.dx = event.clientX - Scene.left - Scene.startx;
		Scene.dy = event.clientY - Scene.top - Scene.starty;
		
		Scene.speedx = Scene.dx/0.01;
		Scene.speedy = Scene.dy/0.01;
		
		Scene.turn(-Math.PI*Scene.dx/parseInt(Scene.This.offsetWidth), Math.PI*Scene.dy/parseInt(Scene.This.offsetHeight));
		
		Scene.startx = event.clientX - Scene.left;
		Scene.starty = event.clientY - Scene.top;
	},
	
	turn: function(dteta, dfi)
	{
		var cosdfi = Math.cos(dfi);
		var sindfi = Math.sin(dfi);
		var cosdteta = Math.cos(dteta);
		var sindteta = Math.sin(dteta);
		
		for(i=0; i<Scene.elements.length; i++)
		{
			var el = Scene.elements[i];
			///////x
			el["ty"] =  el["y"] * cosdfi + el["z"] * sindfi;
			el["tz"] = -el["y"] * sindfi + el["z"] * cosdfi;
			///////y
			el["tx"] = el["x"] * cosdteta - el["tz"] * sindteta;
			el["tz"] = el["x"] * sindteta + el["tz"] * cosdteta;
			
			el["this"].style.left = Scene.left + parseInt(Scene.This.offsetWidth)/2 - el["this"].offsetWidth/2 + Scene.mnx*el["tx"] + "px";
			el["this"].style.top = Scene.top + parseInt(Scene.This.offsetHeight)/2 - el["this"].offsetHeight/2 + el["ty"] + "px";
			
			var kz = (Scene.elements[i]["tz"]/Scene.radius + 1.3)/2.3; // 1...0.13
			setOpacity(Scene.elements[i]["this"], kz);
			
			Scene.elements[i]["this"].style.zIndex = parseInt(Scene.elements[i]["tz"]+Scene.radius);
			
			Scene.elements[i]["x"] = el["tx"];
			Scene.elements[i]["y"] = el["ty"];
			Scene.elements[i]["z"] = el["tz"];
		}
		clearSelection();
	},
	
	dragstop: function()
	{
		removeEvent(document, "mousemove", Scene.drag);
		removeEvent(document, "mouseup", Scene.dragstop);
		
		if(Math.abs(Scene.dx) > 2 || Math.abs(Scene.dy) > 2) Scene.speedtimer = setInterval(Scene.inertion, 10);
	},
	
	inertion: function()
	{
		Scene.turn(-Math.PI*Scene.speedx*0.01/parseInt(Scene.This.offsetWidth), Math.PI*Scene.speedy*0.01/parseInt(Scene.This.offsetHeight));
		
		Scene.speedx -= Scene.speedx*0.05;
		Scene.speedy -= Scene.speedy*0.05;
		if(Math.abs(Scene.speedx) < 2 && Math.abs(Scene.speedy) < 2) Scene.inertionstop();
	},
	
	inertionstop: function()
	{
		if(Scene.speedtimer != 0) clearInterval(Scene.speedtimer);
	}
}

addEvent(window, 'load', Scene.install);

 //-->