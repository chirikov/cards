<!--//

//	Copyright (c) Roman Chirikov, 2008-2009

var Win = new Object();

Win.title = "";
Win.inner = "";
Win.height = "300px";

Win.show = function()
{
	var odiv = document.createElement("DIV");
	odiv.id = "bg";
	odiv.style.width = "100%";
	odiv.style.height = document.body.offsetHeight+"px";
	odiv.style.position = "absolute";
	odiv.style.top = "0px";
	odiv.style.backgroundColor = "#000000";
	if(typeof document.body.style.opacity == 'string') odiv.style.opacity = 0.5;
	else if(document.body.filters) odiv.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=50)";
	window.document.body.appendChild(odiv);
	
	var win = document.createElement("DIV");
	win.id = "win";
	win.innerHTML = '<div class="card" id="window" style="width: 400px"><div class="title"><div class="left"></div><div class="right"><div class="win_close" id="win_close" onclick="Win.hide();"></div></div>'+this.title+'</div><div class="body" style="height: '+this.height+'; background: #F5F5F5"><table class="center"><tr><td>'+this.inner+'</td></tr><tr><td class="bottom"></td></tr></table></div><div class="title"><div class="left" id="win_foot"></div><div class="right"><div class="wait" id="win_wait"></div></div></div></div>';
	document.body.appendChild(win);
	win.style.position = "absolute";
	win.style.left = window.document.body.clientWidth/2 - 200+"px";
	win.style.top = window.document.body.clientHeight/2 - 150+"px";
}

Win.hide = function()
{
	document.body.removeChild(document.getElementById("win"));
	document.body.removeChild(document.getElementById("bg"));
}

Win.response = function(error)
{
	document.getElementById("win_foot").innerHTML = error;
}

Win.wait = function(onoff)
{
	if(onoff == 1)
	{
		document.getElementById("win_wait").style.display = "block";
		document.getElementById("win_close").style.display = "none";
		if(document.getElementById("win_submit")) document.getElementById("win_submit").disabled = true;
	}
	else
	{
		document.getElementById("win_close").style.display = "block";
		document.getElementById("win_wait").style.display = "none";
		if(document.getElementById("win_submit")) document.getElementById("win_submit").disabled = false;
	}
}

//-->