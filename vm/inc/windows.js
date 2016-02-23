<!--//

//	Copyright (c) Roman Chirikov, 2008-2009

function darken(onoff)
{
	if(onoff)
	{
		var odiv = document.createElement("DIV");
	
		odiv.id = "bg";
		odiv.style.width = "100%";
		odiv.style.height = "100%";
		odiv.style.position = "absolute";
		odiv.style.top = "0px";
		odiv.style.backgroundColor = "#000000";
		
		if(typeof document.body.style.opacity == 'string') odiv.style.opacity = 0.5;
		else if(document.body.filters) odiv.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=50)";
		
		document.body.appendChild(odiv);
		
		if(document.getElementById("wpcumuluswidgetcontent")) document.getElementById("wpcumuluswidgetcontent").style.visibility = "hidden";
	}
	else
	{
		document.body.removeChild(document.getElementById("bg"));
		if(document.getElementById("wpcumuluswidgetcontent")) document.getElementById("wpcumuluswidgetcontent").style.visibility = "visible";
	}
}

function close_win()
{
	darken(0);
	document.body.removeChild(document.getElementById("win"));
}

function open_win(inner)
{
	darken(1);
	
	var win = document.createElement("DIV");
	
	win.style.background = "#ffffff";
	win.style.border = "3px solid #ccc";
	win.style.padding = "20px";
	win.id = "win";
	
	win.innerHTML = '<div style="float: right; margin: -15px"><a href="javascript: close_win();"><img src="images/close.gif" alt="x" title="Закрыть" id="win_close"></a><img class="wait" id="win_wait" alt="" src="images/wait.gif"></div>' + inner + '<div style="color: #ff0000; text-align: center; width: 100%; display: none; padding-top: 10px;" id="win_error"></div>';
	
	document.body.appendChild(win);
	
	win.style.position = "absolute";
	win.style.left = window.document.body.clientWidth/2 - win.clientWidth/2+"px";
	win.style.top = window.document.body.clientHeight/2 - win.clientHeight/2+"px";
}

function win_error(error)
{
	var e = document.getElementById("win_error");
	e.innerHTML = error;
	e.style.display = "block";
}

function win_wait(onoff)
{
	if(onoff == 1)
	{
		document.getElementById("win_wait").style.display = "inline";
		document.getElementById("win_close").style.display = "none";
	}
	else
	{
		document.getElementById("win_close").style.display = "inline";
		document.getElementById("win_wait").style.display = "none";
	}
}

function w_file(action)
{
	open_win('<form action="'+action+'" method="post" enctype="multipart/form-data">Путь к файлу: <input type="file" name="photo"> <input type="submit" value="Загрузить" onclick="javascript: document.getElementById(\'wait\').style.display = \'\';"><div id="wait" style="display: none">Подождите...</div></form>');
}
	
//-->