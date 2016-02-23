<!--//

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

var Variants = {
	lastvarnums: {},
	lastvarlengths: {},
	defs: {},
	acts: {},
	value_elements: {},
	text_elements: {},
	real_texts: {},
	init_values: {},
	other_values: {},
	varclickacts: {},
	addactparams: {},
	browser: "ie",
	active: 0,
	
	addEvent: function(el, evnt, func)
	{
		if(el.addEventListener) el.addEventListener(evnt, func, false);
		else if(el.attachEvent) el.attachEvent('on'+evnt, func);
	},
	
	getId: function(event2)
	{
		if(event2.target) return event2.target.id.substr(5);
		else if(window.event.srcElement) return window.event.srcElement.id.substr(5);
	},
	
	install: function()
	{
		var i = 0;
		var divs = document.getElementsByTagName('input');
		for (var c = 0; c < divs.length; c++)
		{
			if(divs[c].id.substr(0, 5) == "vars_")
			{
				var cid = divs[c].id.substr(5);
				Variants.addEvent(divs[c], "click", Variants.default_variants);
				Variants.addEvent(divs[c], "keyup", Variants.variants);
				Variants.addEvent(divs[c], "blur", Variants.close);
				Variants.lastvarnums[cid] = 1;
				Variants.lastvarlengths[cid] = 0;
				Variants.init_values[cid] = divs[c].value;
			}
		}
		switch(navigator.appName)
		{
			case "Opera": Variants.browser = "opera"; break;
			case "Netscape": Variants.browser = "netscape"; break;
		}
	},
	
	default_variants: function(event2)
	{
		var def, cid = Variants.getId(event2);
		var textel = document.getElementById(Variants.text_elements[cid]);
		if(textel.value == Variants.init_values[cid]) textel.value = "";
		if(textel.value == "")
		{
			if(typeof(Variants.defs[cid]) == "function") def = Variants.defs[cid]();
			else
			{
				if(!Variants.defs[cid]) def = "";
				else def = Variants.defs[cid];
			}
			if(def && def != "") Variants.create(cid, def);
		}
	},
	
	create: function(id, result)
	{
		result = eval(result);
		Variants.lastvarnums[id] = result.length;
		if(result.length >0)
		{
			var variants = document.createElement("DIV");
			variants.id = "variants_"+id;
			variants.className = "variants";
			variants.style.width = document.getElementById(Variants.text_elements[id]).clientWidth+1+"px";
			
			var inner = "";
			if(result.length > 7) inner = '<div class="var_scroll">'; 
			for(var i=0; i<result.length; i++)
			{
				// 0 - value  1 - main  2 - sub  3 - realtext
				if(!result[i]["sub"]) result[i]["sub"] = "";
				if(!result[i]["realtext"]) result[i]["realtext"] = result[i]["main"];
				var adclick = "";
				if(Variants.varclickacts[id]!=null)
				{
					adclick = Variants.varclickacts[id];
					adclick = adclick.replace("variant_value", result[i]["value"]);
				}
				inner += '<div id=\"variant_'+id+i+'\" class=\"variant\" onclick=\'document.getElementById(\"'+Variants.value_elements[id]+'\").value = \"'+result[i]["value"]+'\"; Variants.real_texts[\"'+id+'\"] = unescape(\"'+result[i]["realtext"]+'\"); document.getElementById(\"'+Variants.text_elements[id]+'\").value = unescape(\"'+result[i]["realtext"]+'\"); '+adclick+' Variants.hide(\"'+id+'\", true);\'><span class=\"main\">'+unescape(result[i]["main"])+'</span><br><span class=\"sub\">'+result[i]["sub"]+'</span></div>';
			}
			if(result.length > 7) inner += '</div>';
			inner += '<div id="variant_close" class="variant_close" onclick="Variants.hide(\''+id+'\', true);"></div>';
			variants.innerHTML = inner;
			
			document.getElementById("variants_"+id+"_container").appendChild(variants);
		}
	},
	
	variants: function(event2)
	{
		var id = Variants.getId(event2);
		var textel = document.getElementById(Variants.text_elements[id]);
		var valel = document.getElementById(Variants.value_elements[id]);
		
		if(((textel.value.length > Variants.lastvarlengths[id] && (Variants.lastvarnums[id] > 0 || Variants.lastvarlengths[id] == 0)) || (textel.value.length < Variants.lastvarlengths[id] && textel.value.length > 0)) && Variants.active == 0 && Variants.acts[id] != null)
		{
			if(textel.value.length < Variants.lastvarlengths[id] && Variants.lastvarnums[id] > 0)
			{
				valel.value = "";
				valel.title = "";
			}
			var oXmlHttp = getHTTPRequestObject();
			if(oXmlHttp)
			{
				var addact = "";
				if(Variants.addactparams[id])
				{
					addact = Variants.addactparams[id].replace("@city.value@", document.getElementById("city").value);
				}
				oXmlHttp.open("POST", "variants.php?act="+Variants.acts[id]+addact, true);
				oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				oXmlHttp.onreadystatechange = function()
				{
					if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
					{
						Variants.hide(id, false);
						var result = oXmlHttp.responseText;
						Variants.create(id, result);
						Variants.wait(0, id);
						Variants.active = 0;
						selected2 = -1;
					}
				}
				oXmlHttp.send("text="+encodeURIComponent(textel.value));
				Variants.wait(1, id);
				Variants.active = 1;
			}
		}
		else Variants.hide(id, false);
		if(textel.value.length == 0)
		{
			Variants.real_texts[id] = "";
			Variants.hide(id, true);
		}
		Variants.lastvarlengths[id] = textel.value.length;
	},
	
	hide: function(id, close)
	{
		if(document.getElementById("variants_"+id)) document.getElementById("variants_"+id+"_container").removeChild(document.getElementById("variants_"+id));
		if(close) Variants.close(id);
	},
	
	close: function(id)
	{
		if(typeof(id) == "object") id = Variants.getId(event);
		if(document.getElementById("variants_"+id) == null)
		{
			var valel = document.getElementById(Variants.value_elements[id]);
			var textel = document.getElementById(Variants.text_elements[id]);
			if(!Variants.real_texts[id]) Variants.real_texts[id] = '';
			if(!Variants.other_values[id])
			{
				if(Variants.real_texts[id] == "") valel.value = '';
				textel.value = Variants.real_texts[id];
			}
			else
			{
				valel.value = textel.value;
			}
			Variants.lastvarlengths[id] = textel.value.length;
			Variants.lastvarnums[id] = 1;
		}
	},
	
	wait: function(onoff, id)
	{
		if(onoff == 1)
		{
			Variants.hide(id, false);
			var variants_wait = document.createElement("DIV");
			variants_wait.id = "variants_wait_"+id;
			variants_wait.className = "variants_wait";
			
			document.getElementById("variants_"+id+"_container").appendChild(variants_wait);
		}
		else
		{
			if(document.getElementById("variants_wait_"+id)) document.getElementById("variants_"+id+"_container").removeChild(document.getElementById("variants_wait_"+id));
		}
	}
}
/*
function select_variant(i)
{
	if(i == "+1") i = selected2 + 1;
	if(i == "-1") i = selected2 - 1;
	if(document.getElementById("variant"+i))
	{
		if(selected2 != -1) document.getElementById("variant"+selected2).style.background = "#fff";
		document.getElementById("variant"+i).style.background = "#f5f5f5";
		selected2 = i;
	}
}
function move_selection()
{
	//alert(window.event.charCode);
	if(document.getElementById("variants"))
	{
		if(window.event.keyCode == 40)
		{
			select_variant("+1"); return false;
		}
		else if(window.event.keyCode == 38)
		{
			select_variant("-1"); return false;
		}
	}
}
*/

Variants.addEvent(window, 'load', Variants.install);

//-->