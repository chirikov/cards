<!--//

//	Copyright (c) Roman Chirikov, 2008-2009

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

function addtab()
{
	Win.inner = '<form action="javascript: senddata(null);" id="win_form"><input type="hidden" name="act" value="addtab"><table class="login_table medtext"><tr><td>Название вкладки:</td><td><input class="text" type="text" name="val" maxlength="20"></td></tr><tr><td colspan="2"><input class="text" id="win_submit" type="submit" value="OK" style="width: 100%"></td></tr></table></form>';
	Win.title = "Новая вкладка";
	Win.show();
}

function edittab(tab2)
{
	if(typeof(tab2) == "undefined") tab2 = tab;
	Win.inner = '<form action="javascript: senddata(null);" id="win_form"><input type="hidden" name="act" value="edittab"><input type="hidden" name="tab" value="'+tab2+'"><table class="login_table medtext"><tr><td>Название вкладки:</td><td><input class="text" type="text" value="'+tabs[tab2]['title']+'" name="val" maxlength="20">';
	if(tab2 > 0) Win.inner += '<br><a href="#" onclick="Win.hide(); deltab('+tab2+');">Удалить вкладку</a>';
	Win.inner += '</td></tr><tr><td colspan="2"><input class="text" id="win_submit" type="submit" value="OK" style="width: 100%"></td></tr></table></form>';
	Win.title = "Редактировать вкладку";
	Win.show();
}

function deltab(tab)
{
	Win.inner = '<form action="javascript: senddata(null);" id="win_form"><input type="hidden" name="act" value="deltab"><input type="hidden" name="tab" value="'+tab+'">'+
	'<table class="markup_table smalltext"><tr><td colspan="2" style="text-align: left">Что сделать с содержимым вкладки "'+tabs[tab]['title']+'"?</td></tr>'+
	'<tr><td colspan="2" style="text-align: left"><input type="radio" checked name="todo" value="del"> удалить</td></tr>'+
	'<tr><td colspan="2" style="text-align: left"><input type="radio" name="todo" value="totab"> перенести на вкладку:</td></tr>'+
	'<tr><td colspan="2"><select name="totab">';
	for(i=0; i<tabs.length; i++)
	{
		if(i != tab) Win.inner += '<option value="'+i+'">'+tabs[i]['title'];
	}
	Win.inner += '</select></td></tr><tr><td colspan="2"><input class="text" id="win_submit" type="submit" value="OK" style="width: 100%"></td></tr></table></form>';
	Win.title = "Удалить вкладку";
	Win.show();
}

function delitem(id)
{
	Win2 = Win;
	Win.hide();
	Win2.inner = '<form action="javascript: senddata(null);" id="win_form"><input type="hidden" name="act" value="delitem"><input type="hidden" name="tab" value="'+tab+'"><input type="hidden" name="id" value="'+id+'">'+
	'<table class="markup_table smalltext"><tr><td colspan="2" style="text-align: left">Что сделать?</td></tr>'+
	'<tr><td colspan="2" style="text-align: left"><input type="radio" checked name="todo" value="del"> удалить</td></tr>'+
	'<tr><td colspan="2" style="text-align: left"><input type="radio" '+(tabs.length>1?'':'disabled')+' name="todo" value="totab"> перенести на вкладку:</td></tr>';
	if(tabs.length > 1)
	{
		Win2.inner += '<tr><td colspan="2"><select name="totab">';
		for(i=0; i<tabs.length; i++)
		{
			if(i != tab) Win2.inner += '<option value="'+i+'">'+tabs[i]['title'];
		}
		Win2.inner += '</select></td></tr>';
	}
	Win2.inner += '<tr><td colspan="2"><input class="text" id="win_submit" type="submit" value="OK" style="width: 100%"></td></tr></table></form>';
	Win2.title = tabs[tab]['items'][id]['title']+" - удаление/перемещение";
	Win2.show();
}

function editphoto()
{
	var src = document.getElementById("a_photo").children[0].src;
	Win.inner = '<img class="border" src="'+src+'"><form action="settings.php?act=setphoto" id="win_form" onsubmit="avatar_submit(this); return false;" method="post" enctype="multipart/form-data" target="hidframe">'+
	'<input type="hidden" name="act" value="delitem"><input type="hidden" name="todo" value="del"><input type="hidden" name="tab" value="'+tab+'"><input type="hidden" name="id" value="photo"><input type="hidden" name="MAX_FILE_SIZE" value="8000000">'+
	'<table class="login_table smalltext" width="300px">';
	if(src.search("noavatar") == -1) Win.inner += '<tr><td><a href="javascript: var form = document.getElementById(\'win_form\'); form.action = \'javascript: senddata(null);\'; form.encoding=\'\'; form.target = \'\'; form.submit();">Удалить Фотографию</a></td></tr>';
	Win.inner += '<tr><td><input class="text" type="file" name="photo" style="width: 100%"></td></tr>';
	if(src.search("noavatar") == -1) Win.inner += '<tr><td style="text-align: left"><a id="showstylehref" href="javascript: show_style();">Показать стиль</a></td></tr>';
	Win.inner += '<tr><td id="tdstyle" style="display: none; text-align: left; background: #e5e5e5">'+tdstyle_inner("photo")+'</td></tr>'+
	'<tr><td><input class="text" id="win_submit" type="submit" value="OK" style="width: 100%"></td></tr>'+
	'</table></form><iframe id="hidframe" name="hidframe" style="width:0; height:0; border:0"></iframe>';
	Win.title = "Ваша фотография";
	Win.show();
	
	myBgColor = new jscolor.color(document.getElementById('backgroundColor'));
	myBorderColor = new jscolor.color(document.getElementById('borderColor'));
	
	font_preset(document.getElementById("a_photo").children[0]);
}

function editimage(iid)
{
	var src = document.getElementById("a_"+iid).children[0].src;
	Win.inner = '<img class="border" src="'+src+'"><form action="settings.php?act=setimage" id="win_form" onsubmit="avatar_submit(this); return false;" method="post" enctype="multipart/form-data" target="hidframe">'+
	'<input type="hidden" name="act" value="delitem"><input type="hidden" name="todo" value="del"><input type="hidden" name="tab" value="'+tab+'"><input type="hidden" name="id" value="'+iid+'"><input type="hidden" name="MAX_FILE_SIZE" value="8000000">'+
	'<table class="login_table smalltext" width="300px">'+
	'<tr><td><a href="javascript: var form = document.getElementById(\'win_form\'); form.action = \'javascript: senddata(null);\'; form.encoding=\'\'; form.target = \'\'; form.submit();">Удалить изображение</a></td></tr>'+
	'<tr><td><input class="text" type="file" name="photo" style="width: 100%"></td></tr>'+
	'<tr><td style="text-align: left"><a id="showstylehref" href="javascript: show_style();">Показать стиль</a></td></tr>'+
	'<tr><td id="tdstyle" style="display: none; text-align: left; background: #e5e5e5">'+tdstyle_inner(iid)+'</td></tr>'+
	'<tr><td><input class="text" id="win_submit" type="submit" value="OK" style="width: 100%"></td></tr>'+
	'</table></form><iframe id="hidframe" name="hidframe" style="width:0; height:0; border:0"></iframe>';
	Win.title = "Изображение";
	Win.show();
	
	myBgColor = new jscolor.color(document.getElementById('backgroundColor'));
	myBorderColor = new jscolor.color(document.getElementById('borderColor'));
	
	font_preset(document.getElementById("a_"+iid).children[0]);
}

function editbox(iid)
{
	var el = document.getElementById("a_"+iid).children[0];
	var w = parseInt(el.style.width);
	var h = parseInt(el.style.height);
	var col = getColor(el.style.background);
	if(w > 100) w2 = 100;
	else w2 = w;
	if(h > 100) h2 = 100;
	else h2 = h;
	
	Win.inner = '<form action="javascript: senddata(null);" id="win_form" method="post">'+
	'<input type="hidden" name="act" value="additem"><input type="hidden" name="todo" value="del"><input type="hidden" name="tab" value="'+tab+'"><input type="hidden" name="id" value="'+iid+'">'+
	'<table class="login_table smalltext" width="300px">'+
	'<tr><td><div style="margin: 0 auto; width: '+w2+'px; height: '+h2+'px; background: '+col+'; border: '+el.style.border+'"></div><a href="javascript: var form = document.getElementById(\'win_form\'); form.act.value= \'delitem\'; form.submit();">Удалить прямоугольник</a></td></tr>'+
	'<tr><td>Ширина: <input class="text" id="width" type="text" name="width" value="'+w+'" maxlength="3" style="width: 30px"><br><br>Высота: <input class="text" id="height" type="text" name="height" value="'+h+'" maxlength="3" style="width: 30px"></td></tr>'+
	'<tr><td style="text-align: left"><a id="showstylehref" href="javascript: show_style();">Показать стиль</a></td></tr>'+
	'<tr><td id="tdstyle" style="display: none; text-align: left; background: #e5e5e5">'+tdstyle_inner(iid)+'</td></tr>'+
	'<tr><td><input class="text" id="win_submit" type="submit" value="OK" style="width: 100%"></td></tr>';
	Win.title = "Прямоугольник";
	Win.show();
	myBgColor = new jscolor.color(document.getElementById('backgroundColor'));
	myBorderColor = new jscolor.color(document.getElementById('borderColor'));
	
	font_preset(document.getElementById("a_"+iid).children[0]);
}

var tabs = {}, tab = 0, myColor, userid;
var default_cities = [{"value":"7700000000000","main":"Москва"},{"value":"7800000000000","main":"Санкт-Петербург"},{"value":"5400000100000","main":"Новосибирск"},{"value":"6600000100000","main":"Екатеринбург"},{"value":"5200000100000","main":"Нижний Новгород"},{"value":"6300000100000","main":"Самара"},{"value":"5500000100000","main":"Омск"},{"value":"1600000100000","main":"Казань"},{"value":"7400000100000","main":"Челябинск"},{"value":"6100000100000","main":"Ростов-на-Дону"},{"value":"0200100100000","main":"Уфа"}];

function get_other_defs()
{
	//var ids = new Array("name", "surname", "birthdate", "city", "fstatus", "mtel", "dtel", "rtel", "icq", "skype", "home", "work");
	var def = "", item = {}, res = new Array();
	
	for(item in tabs[tab]['items'])
	{
		if(typeof(tabs[tab]['items'][item]) != "undefined" && tabs[tab]['items'][item]['other'])
		{
			res[res.length] = {"value": tabs[tab]['items'][item]['id'], "main": escape(tabs[tab]['items'][item]['title'])};
		}
	}
	return res;
}

function show_style()
{
	var tdstyle = document.getElementById('tdstyle');
	if(tdstyle.style.display == 'none')
	{
		tdstyle.style.display = '';
		document.getElementById('showstylehref').innerHTML = "Спрятать стиль";
	}
	else
	{
		tdstyle.style.display = 'none';
		document.getElementById('showstylehref').innerHTML = "Показать стиль";
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

function getColor(col)
{
	if(!col) return "#ffffff";
	else if(col.substr(0, 1) == "#") return col;
	else if(col.substr(0, 1) == "r")
	{
		col = col.substr(4);
		if(col.search("r") != -1) col = col.substr(0, col.search("r")-2);
		else col = col.substr(0, col.length-1);
		col = col.split(", ");
		for(i=0; i<3; i++)
		{
			col[i] = parseInt(col[i]);
			col[i] = col[i].toString(16);
			if(col[i].length < 2) col[i] = "0"+col[i];
		}
		return "#"+col[0]+col[1]+col[2];
	}
}

function tdstyle_inner(id)
{
	var fonts = new Array("Verdana", "Arial", "Times New Roman", "Comic Sans MS");
	
	if(id.substr(0, 5) == "image" || id == "photo")
	{
		var in_style = '<table class="styletable"><tr><td>Цвет фона: <input class="colorPick" id="backgroundColor" type="text" name="backgroundColor" maxlength="7" value="#ffffff"><br><input type="checkbox" id="backgroundtransp" name="backgroundtransp" value="1"> прозрачный фон'+
		'</td></tr><tr><td>Толщина границы: <input class="text" id="borderWidth" type="text" name="borderWidth" value="1" maxlength="2" style="width: 20px"><br><br>'+
		'Цвет границы: <input class="colorPick" id="borderColor" type="text" name="borderColor" maxlength="7" value="#d3d3d3"><br><br>'+
		'Отступ: <input class="text" id="padding" type="text" name="padding" value="3" maxlength="2" style="width: 20px">'+
		'</td></tr><tr><td>Прозрачность: <input class="text" id="transparency" type="text" name="transparency" value="0%" maxlength="4" style="width: 40px"></td></tr></table>';
	}
	else if(id.substr(0, 3) == "box")
	{
		var in_style = '<table class="styletable"><tr><td>Цвет фона: <input class="colorPick" id="backgroundColor" type="text" name="backgroundColor" maxlength="7" value="#ffffff">'+
		'</td></tr><tr><td>Толщина границы: <input class="text" id="borderWidth" type="text" name="borderWidth" value="1" maxlength="2" style="width: 20px"><br><br>'+
		'Цвет границы: <input class="colorPick" id="borderColor" type="text" name="borderColor" maxlength="7" value="#d3d3d3">'+
		'</td></tr><tr><td>Прозрачность: <input class="text" id="transparency" type="text" name="transparency" value="0%" maxlength="4" style="width: 40px"></td></tr></table>';
	}
	else
	{
		var in_style = '<table class="styletable"><tr><td>Шрифт: <select id="fontFamily" name="fontFamily">';
		for(i=0; i<fonts.length; i++)
		{
			in_style += '<option value="'+fonts[i]+'">'+fonts[i];
		}
		in_style += '</select><br><br>'+
		'<input id="bold" type="checkbox" name="bold" value="1"> <b>Жирный</b> <input id="italic" type="checkbox" name="italic" value="1"> <i>Курсив</i><br><br>'+
		'Размер шрифта: <input class="text" id="fontSize" type="text" name="fontSize" value="9" maxlength="2" style="width: 20px"><br><br>'+
		'Цвет шрифта: <input class="colorPick" id="color" type="text" name="color" maxlength="7" value="#1f1f1f"></td>'+
		'<td>Толщина границы: <input class="text" id="borderWidth" type="text" name="borderWidth" value="0" maxlength="2" style="width: 20px"><br><br>'+
		'Цвет границы: <input class="colorPick" id="borderColor" type="text" name="borderColor" maxlength="7" value="#ffffff"><br><br>'+
		'Отступ: <input class="text" id="padding" type="text" name="padding" value="3" maxlength="2" style="width: 20px"></td></tr>'+
		'<tr><td>Цвет фона: <input class="colorPick" id="backgroundColor" type="text" name="backgroundColor" maxlength="7" value="#ffffff"><br><input type="checkbox" id="backgroundtransp" name="backgroundtransp" checked value="1"> прозрачный фон'+
		'</td><td>Прозрачность: <input class="text" id="transparency" type="text" name="transparency" value="0%" maxlength="4" style="width: 40px"></td></tr></table>';
	}
	
	return in_style;
}

function additem()
{
	var in_select = '<select class="text" name="title" onchange="additem_title_change(this);">'+
	'<option value="no">---'+
	'<option value="other">Другое'+
	'<option value="name">Имя'+
	'<option value="surname">Фамилия'+
	'<option value="birthdate">Дата рождения'+
	'<option value="city">Город'+
	'<option value="mtel">Мобильный телефон'+
	'<option value="home">Адрес'+
	'<option value="dtel">Домашний телефон'+
	'<option value="work">Работа'+
	'<option value="rtel">Рабочий телефон'+
	'<option value="icq">ICQ'+
	'<option value="skype">Skype'+
	'<option value="fstatus">Семейное положение'+
	'<option value="image">Изображение'+
	'<option value="box">Прямоугольник'+
	'<option value="other">Другое'+
	'</select>';
	
	Win.inner = '<form action="javascript: senddata(null);" id="win_form" onsubmit="avatar_submit(this); return false;">'+
	'<input type="hidden" name="act" value="additem"><input type="hidden" name="tab" value="'+tab+'"><input type="hidden" id="id" name="id" value="">'+
	'<table class="markup_table smalltext">'+
	'<tr><td id="tdtitle">'+in_select+'<input style="margin-top: 5px; display: none" type="text" class="inpvars" name="other_title" id="vars_other_title" maxlength="30" value="Заголовок"><div class="vars_container" id="variants_other_title_container"></div></td></tr>'+
	'<tr><td id="tdval" style="text-align: left"><input type="text" class="text" disabled></td></tr>'+
	'<tr><td style="text-align: left"><a id="showstylehref" href="javascript: show_style();">Показать стиль</a></td></tr>'+
	'<tr><td id="tdstyle" style="display: none">'+tdstyle_inner("other")+'</td></tr>'+
	'<tr><td><input class="text" id="win_submit" type="submit" value="OK" style="width: 100%"></td></tr>'+
	'</table></form>';
	Win.title = "Новые сведения";
	Win.show();
	myColor = new jscolor.color(document.getElementById('color'));
	myBgColor = new jscolor.color(document.getElementById('backgroundColor'));
	myBorderColor = new jscolor.color(document.getElementById('borderColor'));
	
	Variants.defs['city'] = default_cities;
	Variants.acts['city'] = "givecities";
	Variants.value_elements['city'] = "city";
	Variants.text_elements['city'] = "vars_city";
	
	Variants.defs['city2'] = default_cities;
	Variants.acts['city2'] = "givecities";
	Variants.value_elements['city2'] = "city";
	Variants.text_elements['city2'] = "vars_city2";
	Variants.varclickacts['city2'] = 'document.getElementById(\"street\").value = \"\"; document.getElementById(\"vars_street\").value = \"\"; document.getElementById(\"vars_street\").disabled = false;';
	
	Variants.defs['street'] = "";
	Variants.acts['street'] = "givestreets";
	Variants.value_elements['street'] = "street";
	Variants.text_elements['street'] = "vars_street";
	Variants.addactparams['street'] = "&city=@city.value@";
	
	Variants.defs['other_title'] = get_other_defs;
	Variants.acts['other_title'] = null;
	Variants.value_elements['other_title'] = "vars_other_title";
	Variants.text_elements['other_title'] = "vars_other_title";
	Variants.varclickacts['other_title'] = 'preset(\"variant_value\");';
	Variants.other_values['other_title'] = true;
}

function preset(id)
{
	var ci = document.getElementById(id);
	document.getElementById("textarea_val").value = unescape(tabs[tab]['items'][id]['value']);
	if(tabs[tab]['items'][id]['nt']) document.getElementById("notitle").defaultChecked = true;
	else document.getElementById("notitle").defaultChecked = false;
	
	document.getElementById('id').value = id;
	
	var delmove = document.getElementById("delmove")
	delmove.style.display = "";
	delmove.innerHTML = '<a href="javascript: delitem(\''+id+'\');">Удалить/перенести</a><br><br>';
	
	font_preset(id);
}

function font_preset(id)
{
	if(typeof(id) == "string") var ci = document.getElementById("div_"+id);
	else ci = id;
	
	if(document.getElementById("fontSize"))
	{
		var ff = ci.style.fontFamily;
		if(ff)
		{
			var opts = document.getElementById('fontFamily').options
			for(i=0; i<opts.length; i++)
			{
				if(opts[i].text == ff) opts[i].selected = true;
			}
		}
		
		var fs = parseInt(ci.style.fontSize);
		document.getElementById("fontSize").value = (isNaN(fs)?"9":fs);
		
		document.getElementById("color").value = getColor(ci.style.color);
		
		if(ci.style.fontWeight == "bold" || ci.style.fontWeight == 700) document.getElementById("bold").checked = true;
		else document.getElementById("bold").checked = false;
		if(ci.style.fontStyle == "italic") document.getElementById("italic").checked = true;
		else document.getElementById("italic").checked = false;
		
		myColor.importColor();
	}
	if(document.getElementById("backgroundtransp"))
	{
		if(!ci.style.backgroundColor || ci.style.backgroundColor == "transparent") document.getElementById("backgroundtransp").checked = true;
		else document.getElementById("backgroundtransp").checked = false;
	}
	if(document.getElementById("backgroundColor"))
	{
		document.getElementById("backgroundColor").value = getColor(ci.style.backgroundColor);
		myBgColor.importColor();
	}
	if(document.getElementById("borderColor"))
	{
		document.getElementById("borderColor").value = getColor(ci.style.borderColor);
		myBorderColor.importColor();
	}
	if(document.getElementById("borderWidth"))
	{
		var bw = parseInt(ci.style.borderWidth);
		document.getElementById("borderWidth").value = (isNaN(bw)?"0":bw);
	}
	if(document.getElementById("padding"))
	{
		var pad = parseInt(ci.style.padding);
		document.getElementById("padding").value = (isNaN(pad)?"0":pad);
	}
	if(document.getElementById("transparency"))
	{
		var op = getOpacity(ci);
		if(op == 0) op = 1;
		document.getElementById("transparency").value = parseInt((1-op)*100) + "%";
	}
}

function additem_title_change(_this)
{
	var tdval = document.getElementById("tdval");
	var defs = {"name": "", "surname": "", "birthdate": "24 февраля 1988", "mtel": "89101234567", "dtel": "83471234567", "rtel": "83471234567", "icq": "", "skype": ""};
	var maxlengths = {"name": 40, "surname": 40, "birthdate": 16, "mtel": 20, "dtel": 20, "rtel": 20, "icq": 9, "skype": 40};
	
	document.getElementById("vars_other_title").style.display = "none";
	document.getElementById('id').value = _this.value;
	document.getElementById("tdstyle").innerHTML = tdstyle_inner(_this.value);
	
	if(document.getElementById('color')) myColor = new jscolor.color(document.getElementById('color'));
	if(document.getElementById('backgroundColor')) myBgColor = new jscolor.color(document.getElementById('backgroundColor'));
	if(document.getElementById('borderColor')) myBorderColor = new jscolor.color(document.getElementById('borderColor'));
	
	if(_this.value == "no")
	{
		show_style();
		tdval.innerHTML = '<input type="text" class="text" disabled>';
	}
	else if(_this.value == "box")
	{
		tdval.innerHTML = 'Ширина: <input class="text" id="width" type="text" name="width" value="100" maxlength="3" style="width: 30px"><br><br>Высота: <input class="text" id="height" type="text" name="height" value="100" maxlength="3" style="width: 30px">';
	}
	else if(_this.value == "other")
	{
		document.getElementById("vars_other_title").style.display = "block";
		tdval.innerHTML = '<input type="checkbox" id="notitle" name="notitle" value="1"> Скрыть заголовок<br><br><div id="delmove" style="display: none"></div><textarea style="width: 300px" rows="5" name="val" id="textarea_val"></textarea>';
		Variants.install();
	}
	else if(_this.value == "fstatus")
	{
		if(document.getElementById("div_"+_this.value))
		{
			font_preset(_this.value);
			tdval.innerHTML = '';
			tdval.innerHTML += '<a href="javascript: document.getElementById(\'win_form\').act.value = \'delitem\'; senddata(null);">Убрать/переместить с визитки</a><br><br>';
		}
		else tdval.innerHTML = '';
		tdval.innerHTML += '<select name="fstatus"><option value="1">Не женат/Не замужем<option value="2">Встречаюсь<option value="3">Помолвлен(а)<option value="4">Женат/Замужем<option value="5">Ищу</select>';
	}
	else if(_this.value == "home")
	{
		if(document.getElementById("div_"+_this.value))
		{
			font_preset(_this.value);
			tdval.innerHTML = '';
			tdval.innerHTML += '<a href="javascript: document.getElementById(\'win_form\').act.value = \'delitem\'; senddata(null);">Убрать с визитки</a><br><br>';
		}
		else tdval.innerHTML = '';
		tdval.innerHTML += '<input type="hidden" name="city" id="city"><input type="text" value="Город" class="inpvars" id="vars_city2"><br><div id="variants_city2_container"></div><br>'+
		'<input type="hidden" name="street" id="street"><input type="text" disabled value="Улица" class="inpvars" id="vars_street"><br><div id="variants_street_container"></div><br>'+
		'<input type="text" class="text" maxlength="10" value="Дом" name="house" id="house" onclick="if(this.value == \'Дом\') this.value = \'\';">';
		Variants.install();
	}
	else if(_this.value == "work")
	{
		if(document.getElementById("div_"+_this.value))
		{
			font_preset(_this.value);
			tdval.innerHTML = '';
			tdval.innerHTML += '<a href="javascript: document.getElementById(\'win_form\').act.value = \'delitem\'; senddata(null);">Убрать с визитки</a><br><br>';
		}
		else tdval.innerHTML = '';
		tdval.innerHTML += '<table><tr><td><input type="hidden" name="city" id="city"><input type="text" value="Город" class="inpvars" id="vars_city2"><br><div id="variants_city2_container"></div></td></td><td><input type="text" class="text" maxlength="100" value="Организация" name="company" id="company" onclick="if(this.value == \'Организация\') this.value = \'\';"></td></tr>'+
		'<tr><td><input type="hidden" name="street" id="street"><input type="text" disabled value="Улица" class="inpvars" id="vars_street"><br><div id="variants_street_container"></div></td><td><input type="text" class="text" maxlength="100" value="Должность" name="job" id="job" onclick="if(this.value == \'Должность\') this.value = \'\';"></td></tr>'+
		'<tr><td><input type="text" class="text" maxlength="10" value="Дом" name="house" id="house" onclick="if(this.value == \'Дом\') this.value = \'\';"></td><td></td></tr></table>';
		Variants.install();
	}
	else if(_this.value == "image")
	{
		var form = document.getElementById("win_form");
		form.action = "settings.php?act=setadditem";
		form.method = "post";
		form.encoding = "multipart/form-data";
		form.target = "hidframe";
		tdval.innerHTML = '<input type="hidden" name="MAX_FILE_SIZE" value="8000000"><input class="text" type="file" name="photo" style="width: 100%"><iframe id="hidframe" name="hidframe" style="width:0; height:0; border:0"></iframe>';
	}
	else if(_this.value == "city")
	{
		if(document.getElementById("div_"+_this.value))
		{
			font_preset(_this.value);
			tdval.innerHTML = '';
			tdval.innerHTML += '<a href="javascript: document.getElementById(\'win_form\').act.value = \'delitem\'; senddata(null);">Убрать с визитки</a><br><br>';
		}
		else tdval.innerHTML = '';
		tdval.innerHTML += '<input type="hidden" name="val" id="city"><input type="text" value="Город" class="inpvars" id="vars_city"><br><div id="variants_city_container" class="vars_container"></div>';
		Variants.install();
	}
	else
	{
		var def = defs[_this.value];
		if(document.getElementById("div_"+_this.value))
		{
			def = tabs[tab]['items'][_this.value]['value'];
			font_preset(_this.value);
			tdval.innerHTML = '';
			if(_this.value != "name" && _this.value != "surname") tdval.innerHTML += '<a href="javascript: delitem(\''+_this.value+'\');">Убрать/перенести</a><br><br>';
		}
		else tdval.innerHTML = '';
		if(_this.value == "name" || _this.value == "surname") tdval.innerHTML += '<input type="hidden" name="notitle" value="1">';
		tdval.innerHTML += '<input type="text" class="text" maxlength="'+maxlengths[_this.value]+'" value="'+def+'" name="'+_this.value+'" id="val">';
	}
}

function avatar_submit(form)
{
	if(form.photo && form.photo.style.display != "none" && form.photo.value != "")
	{
		if(form.photo.value.search(".jpg$") == -1 && form.photo.value.search(".jpeg$") == -1) win_response("Фотография должна быть в формате JPEG");
		else
		{
			document.getElementById('win_submit').disabled = true;
			Win.wait(1);
			Win.response("Идет загрузка фотографии...");
			form.submit();
		}
	}
	else form.submit();
}

function getRequestBody(oForm) { 
	var aParams = new Array();
	for(var i = 0; i < oForm.elements.length; i++) {
		if(oForm.elements[i].type == "checkbox" && oForm.elements[i].checked == true || oForm.elements[i].type == "radio" && oForm.elements[i].checked == true || oForm.elements[i].type != "checkbox" && oForm.elements[i].type != "radio")
		{
			var sParam = encodeURIComponent(oForm.elements[i].name);
			sParam += "=";
			sParam += encodeURIComponent(oForm.elements[i].value);
			aParams.push(sParam);
		}
	}
	return aParams.join("&");
}

function senddata(elemid)
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		var form = document.getElementById("win_form");
		oXmlHttp.open("POST", "settings.php?act=set"+form.act.value, true);
		oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
			{
				form.action = "javascript: senddata(null);";
				form.method = "";
				form.encoding = "";
				form.target = "";
				
				var result = oXmlHttp.responseText;
				//alert(result);
				if(result.substr(0, 1) == "E")
				{
					Win.response(result.substr(1));
					Win.wait(0);
					document.getElementById("win_submit").disabled = false;
				}
				else
				{
					if(form.act.value == "addtab")
					{
						tab = tabs.length;
						tabs[tab] = {'title': result, 'itemsnum': 0, 'items': {}};
						document.getElementById("card_menu_arrow").style.display = "";
						document.getElementById("tab_current").children[0].innerHTML = result;
						document.getElementById("card_body").innerHTML = '';
						document.getElementById("card_body").style.background = '#ffffff';
					}
					else if(form.act.value == "edittab")
					{
						tabs[form.tab.value]['title'] = result;
						if(form.tab.value == tab) document.getElementById("tab_current").children[0].innerHTML = result;
					}
					else if(form.act.value == "deltab")
					{
						location = "card.php?id="+userid+"&tab="+result;
					}
					else if(form.act.value == "additem")
					{
						var parts = eval("("+result+")");
						var ist = false;
						var card_body = document.getElementById("card_body");
						
						//tabs[tab]['items'][parts['id']] = {"id": parts['id'], "title": parts['title'], "value": parts['value'], "nt": parts['nt'], "other": parts['other']};
						
						if(document.getElementById("div_"+parts['id']))
						{
							ist = true;
							
							var ci = document.getElementById("div_"+parts['id']);
							
							if(parts['id'].substr(0, 3) == "box")
							{
								ci2 = ci.children[0].children[0];
								var w = parseInt(form.width.value);
								if(w < 5) w = 5;
								else if(w > 870) w = 870;
								ci2.style.width = w+"px";
								var h = parseInt(form.height.value);
								if(h < 5) h = 5;
								else if(h > 600) h = 600;
								ci2.style.height = h+"px";
								
								ci2.style.background = form.backgroundColor.value;
								
								ci2.style.border = "solid";
								ci2.style.borderWidth = (parseInt(form.borderWidth.value)<=10?parseInt(form.borderWidth.value):"10")+"px";
								ci2.style.borderColor = form.borderColor.value;
								
								var tr = parseInt(form.transparency.value);
								tr = (100-tr)/100;
								if(tr < 0.1) tr = 0.1;
								setOpacity(ci2, tr);
							}
							else
							{
								ci.style.fontFamily = form.fontFamily.value;
								ci.style.fontSize = (parseInt(form.fontSize.value)>=6?parseInt(form.fontSize.value):"9")+"pt";
								ci.style.color = form.color.value;
								if(form.bold.checked == true) ci.style.fontWeight = "bold";
								else ci.style.fontWeight = "normal";
								if(form.italic.checked == true) ci.style.fontStyle = "italic";
								else ci.style.fontStyle = "normal";
								
								if(form.backgroundtransp.checked == false) ci.style.background = form.backgroundColor.value;
								else ci.style.background = "";
								
								var pad = parseInt(form.padding.value);
								if(pad > 20) pad = 20;
								ci.style.padding = pad+"px";
								
								var bw = parseInt(form.borderWidth.value);
								if(bw > 10) bw = 10;
								if(bw > 0) ci.style.border = bw+"px"+" solid "+form.borderColor.value;
								
								var tr = parseInt(form.transparency.value);
								tr = (100-tr)/100;
								if(tr < 0.1) tr = 0.1;
								setOpacity(ci, tr);
							}
							
							tabs[tab]['items'][parts['id']]['title'] =  parts['title'];
							tabs[tab]['items'][parts['id']]['value'] =  parts['value'];
							tabs[tab]['items'][parts['id']]['nt'] =  parts['nt'];
						}
						else
						{
							if(parts['id'].substr(0, 3) != "box")
							{
								var style = "font-family: "+form.fontFamily.value+";";
								style += " font-size: "+(parseInt(form.fontSize.value)>=6?parseInt(form.fontSize.value):"9")+"pt; color: "+form.color.value+";";
								if(form.bold.checked == true) style += " font-weight: bold;";
								if(form.italic.checked == true) style += " font-style: italic;";
								if(form.backgroundtransp.checked == false) style += " background: "+form.backgroundColor.value+";";
								var pad = parseInt(form.padding.value);
								if(pad > 20) pad = 20;
								style += " padding: "+pad+"px;";
							}
							else
							{
								var w = parseInt(form.width.value);
								if(w < 5) w = 5;
								else if(w > 862) w = 862;
								var style = "width: "+w+"px;";
								var h = parseInt(form.height.value);
								if(h < 5) h = 5;
								else if(h > 594) h = 594;
								style += " height: "+h+"px;";
								style += " background: "+form.backgroundColor.value+";";
							}
							var bw = parseInt(form.borderWidth.value);
							if(bw > 10) bw = 10;
							if(bw > 0) style += " border: "+bw+"px solid "+form.borderColor.value+";";
							
							var tr = parseInt(form.transparency.value);
							tr = (100-tr)/100;
							if(tr < 0.1) tr = 0.1;
							style += " opacity: "+tr+"; filter: progid:DXImageTransform.Microsoft.Alpha(opacity="+(tr*100)+");";
							
							tabs[tab]['items'][parts['id']] = {"id": parts['id'], "title": parts['title'], "value": parts['value'], "nt": parts['nt'], "other": parts['other']};
						}
						
						if(parts['id'].substr(0, 3) == "box")
						{
							if(!ist) card_body.innerHTML += '<div class="draggable" id="div_'+parts['id']+'" onmousedown="javascript: StartDrag(event, this, document.getElementById(\'card_body\'));"><a id="a_'+parts['id']+'" href="javascript: editbox(\''+parts['id']+'\');"><div style="'+style+'"></div></a></div>';
						}
						else
						{
							if(ist) ci.innerHTML = (!parts['nt']?parts['title']+': ':'')+parts['value'];
							else card_body.innerHTML += '<div class="draggable" id="div_'+parts['id']+'" onmousedown="javascript: StartDrag(event, this, document.getElementById(\'card_body\'));" style="'+style+'">'+((!parts['nt'])?(parts['title']+': '):'')+parts['value']+'</div>';
						}
						if(!ist)
						{
							var ci = document.getElementById("div_"+parts['id']);
							ci.style.top = getOffsetTop(card_body)-getOffsetTop(ci)+"px";
							ci.style.left = getOffsetLeft(card_body)-getOffsetLeft(ci)+"px";
							pageunlock(1);
							changepage();
						}
					}
					else if(form.act.value == "delitem")
					{
						var parts = eval("("+result+")");
						
						if(parts[1] == "del")
						{
							tabs[tab]['items'][parts[0]] = undefined;
						}
						else
						{
							tabs[parts[2]]['items'][parts[0]] = tabs[tab]['items'][parts[0]];
							tabs[tab]['items'][parts[0]] = undefined;
						}
						if(parts[0] != "photo") document.getElementById("card_body").removeChild(document.getElementById("div_"+parts[0]));
						else
						{
							var ava = document.getElementById("a_photo").children[0];
							ava.src = "images/noavatar.jpg";
							ava.style.border = "1px solid #d3d3d3";
							ava.style.background = "#fff";
							ava.style.padding = "3px";
							setOpacity(ava, 1);
						}
					}
					else if(form.act.value == "bg")
					{
						if(form.type.value == "color") document.getElementById("card_body").style.background = result;
					}
					Win.hide();
				}
			}
		}
		//alert(getRequestBody(form));
		oXmlHttp.send(getRequestBody(form));
		Win.wait(1);
		document.getElementById("win_submit").disabled = true;
	}
}

function set_bg()
{
	var onchg = "var form = document.getElementById('win_form'); if(this.value == 'color') {document.getElementById('tdvalc').style.display = ''; document.getElementById('ph').style.display = 'none'; form.action = 'javascript: senddata(null);'; form.method = form.encoding = form.target = '';}"+
	"else {document.getElementById('tdvalc').style.display = 'none'; document.getElementById('ph').style.display = ''; form.action = 'settings.php?act=setbg'; form.method = 'post'; form.encoding = 'multipart/form-data'; form.target = 'hidframe';}";
	
	var in_select = '<select name="type" onchange="'+onchg+'">'+
	'<option value="color">Цвет'+
	'<option value="image">Изображение'+
	'</select>';
	
	Win.inner = '<form action="javascript: senddata(null);" id="win_form" onsubmit="avatar_submit(this); return false;">'+
	'<input type="hidden" name="act" value="bg"><input type="hidden" name="tab" value="'+tab+'">'+
	'<table class="markup_table smalltext">'+
	'<tr><td id="tdtitle">'+in_select+'</td></tr>'+
	'<tr><td id="tdvalc" style="text-align: left">Цвет фона: <input class="colorPick" id="color" type="text" name="color" maxlength="7" value="#ffffff"></td></tr>'+
	'<tr><td id="tdvali" style="text-align: left"><input type="hidden" name="MAX_FILE_SIZE" value="8000000"><input id="ph" class="text" type="file" name="photo" style="width: 100%; display: none"></td></tr>'+
	'<tr><td><input class="text" id="win_submit" type="submit" value="OK" style="width: 100%"></td></tr></table></form><iframe id="hidframe" name="hidframe" style="width:0; height:0; border:0"></iframe>';
	Win.title = "Фон визитки";
	Win.show();
	
	var col = document.getElementById('card_body').style.background;
	document.getElementById("color").value = getColor(col);
	
	myColor = new jscolor.color(document.getElementById('color'));
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
function addEvent(el, evnt, func)
{
	if(el.addEventListener) el.addEventListener(evnt, func, true);
	else if(el.attachEvent) el.attachEvent('on'+evnt, func);
}

function show_card_menu()
{
	var card = document.getElementById("card");
	var cardbody = document.getElementById("card_body");
	
	if(document.getElementById("card_menu"))
	{
		card.removeChild(document.getElementById("card_menu"));
		document.getElementById("card_menu_arrow").children[0].innerHTML = "▼";
	}
	else
	{
		var cm = document.createElement("DIV");
		cm.id = "card_menu";
		cm.className = "card_menu cm_top";
		var inner = "";
		for(i = 0; i<tabs.length; i++)
		{
			if(i != tab) inner += '<div class="card_menu_item" id="card_menu_item'+i+'" onmouseover="this.children[1].style.visibility = \'visible\';" onmouseout="this.children[1].style.visibility = \'hidden\';"><a href="card.php?id='+userid+'&tab='+i+'">'+tabs[i]['title']+'</a><a href="javascript: edittab('+i+'); show_card_menu();" style="visibility: hidden; float: right">...</a></div>';
		}
		cm.innerHTML = inner;
		//addEvent(cm, "blur", show_card_menu);
		card.appendChild(cm);
		cm.style.position = "absolute";
		cm.style.top = getOffsetTop(cardbody)+"px";
		cm.style.left = getOffsetLeft(cardbody)+1+"px";
		if(navigator.appVersion.search("MSIE 8") != -1) cm.style.left = getOffsetLeft(cardbody)+"px";
		document.getElementById("card_menu_arrow").children[0].innerHTML = "▲";
		//cm.focus();
	}
}

function show_set_menu()
{
	var card = document.getElementById("card");
	var cardbody = document.getElementById("card_body");
	
	if(document.getElementById("card_set"))
	{
		card.removeChild(document.getElementById("card_set"));
		document.getElementById("card_set_arrow").children[0].innerHTML = "▲";
	}
	else
	{
		var cm = document.createElement("DIV");
		cm.id = "card_set";
		cm.className = "card_menu cm_bottom";
		cm.innerHTML = "<div class=\"card_menu_item\"><a href=\"#\">Доступ</a></div><div class=\"card_menu_item\"><a href=\"javascript: show_set_menu(); set_bg();\">Фон</a></div>";
		//addEvent(cm, "blur", show_set_menu);
		card.appendChild(cm);
		if(navigator.appName == "Opera") cm.style.width = "95px";
		cm.style.top = getOffsetTop(cardbody)+cardbody.offsetHeight-101+"px";
		cm.style.left = getOffsetLeft(cardbody)+1+"px";
		if(navigator.appVersion.search("MSIE 8") != -1) cm.style.left = getOffsetLeft(cardbody)+"px";
		document.getElementById("card_set_arrow").children[0].innerHTML = "▼";
		//cm.focus();
	}
}

Win.height = "auto";

//-->