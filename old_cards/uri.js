######hot

	/*
	if(act == 'name')
	{
		Win.inner = '<form action="javascript: senddata(\''+_this.id+'\');" id="win_form"><input type="hidden" name="act" value="'+act+'"><table class="login_table medtext"><tr><td>Ваше имя:</td><td><input class="text" type="text" name="val" maxlength="40"></td></tr><tr><td colspan="2"><input class="text" id="win_submit" type="submit" value="OK" style="width: 100%"></td></tr></table></form>';
		Win.title = "Ваше имя";
		Win.show();
		document.getElementById("win_form").val.value = unescape(inner);
	}
	else if(act == 'surname')
	{
		Win.inner = '<form action="javascript: senddata(\''+_this.id+'\');" id="win_form"><input type="hidden" name="act" value="'+act+'"><table class="login_table medtext"><tr><td>Ваша фамилия:</td><td><input class="text" type="text" name="val" maxlength="40"></td></tr><tr><td colspan="2"><input class="text" id="win_submit" type="submit" value="OK" style="width: 100%"></td></tr></table></form>';
		Win.title = "Ваша фамилия";
		Win.show();
		document.getElementById("win_form").val.value = unescape(inner);
	}
	else 
	*/
	
				//var im = window.parent.document.createElement("DIV");
			//im.src = "photos/'.$_COOKIE['2ndw_userid'].'/'.$code.'.jpg";
			//im.className = "border";
			//im.outerHTML = "<div class=\'draggable\' id=\'drag_'.$_POST['title'].$num.'\' onmousedown=\'StartDrag(event,this,body);\'></div>";
			//window.parent.document.getElementById("card_body").appendChild(im);
	
	
	
	/*
	else if(_this.value == "name")
	{
		def = "";
		if(document.getElementById('card_item_ins_'+_this.value))
		{
			def = document.getElementById('card_item_ins_'+_this.value).innerHTML;
			var ci = document.getElementById('card_item_'+_this.value);
			document.getElementById("fontsize").value = parseInt(ci.style.fontSize);
			document.getElementById("color").value = ci.style.color;
			myColor.importColor();
		}
		tdval.innerHTML = '<input type="hidden" name="notitle" value="1"><input type="text" class="text" maxlength="40" value="'+def+'" name="name" id="val">';
	}
	else if(_this.value == "surname")
	{
		def = "";
		if(document.getElementById('card_item_ins_'+_this.value))
		{
			def = document.getElementById('card_item_ins_'+_this.value).innerHTML;
			var ci = document.getElementById('card_item_'+_this.value);
			document.getElementById("fontsize").value = parseInt(ci.style.fontSize);
			document.getElementById("color").value = ci.style.color;
			myColor.importColor();
		}
		tdval.innerHTML = '<input type="hidden" name="notitle" value="1"><input type="text" class="text" maxlength="40" value="'+def+'" name="surname" id="val">';
	}
	else if(_this.value == "birthdate")
	{
		def = "24 февраля 1988";
		if(document.getElementById('card_item_ins_'+_this.value))
		{
			def = document.getElementById('card_item_ins_'+_this.value).innerHTML;
			var ci = document.getElementById('card_item_'+_this.value);
			document.getElementById("fontsize").value = parseInt(ci.style.fontSize);
			document.getElementById("color").value = ci.style.color;
			myColor.importColor();
		}
		tdval.innerHTML = '<input type="text" class="text" maxlength="16" value="'+def+'" name="birthdate" id="val">';
	}
	else if(_this.value == "mtel")
	{
		def = "89101234567";
		if(document.getElementById('card_item_ins_'+_this.value))
		{
			def = document.getElementById('card_item_ins_'+_this.value).innerHTML;
			var ci = document.getElementById('card_item_'+_this.value);
			document.getElementById("fontsize").value = parseInt(ci.style.fontSize);
			document.getElementById("color").value = ci.style.color;
			myColor.importColor();
		}
		tdval.innerHTML = '<input type="text" class="text" maxlength="20" value="'+def+'" name="mtel" id="val">';
	}
	else if(_this.value == "dtel")
	{
		def = "83471234567";
		if(document.getElementById('card_item_ins_'+_this.value))
		{
			def = document.getElementById('card_item_ins_'+_this.value).innerHTML;
			var ci = document.getElementById('card_item_'+_this.value);
			document.getElementById("fontsize").value = parseInt(ci.style.fontSize);
			document.getElementById("color").value = ci.style.color;
			myColor.importColor();
		}
		tdval.innerHTML = '<input type="text" class="text" maxlength="20" value="'+def+'" name="dtel" id="val">';
	}
	else if(_this.value == "rtel")
	{
		def = "83471234567";
		if(document.getElementById('card_item_ins_'+_this.value))
		{
			def = document.getElementById('card_item_ins_'+_this.value).innerHTML;
			var ci = document.getElementById('card_item_'+_this.value);
			document.getElementById("fontsize").value = parseInt(ci.style.fontSize);
			document.getElementById("color").value = ci.style.color;
			myColor.importColor();
		}
		tdval.innerHTML = '<input type="text" class="text" maxlength="20" value="'+def+'" name="rtel" id="val">';
	}
	else if(_this.value == "icq")
	{
		if(document.getElementById('card_item_ins_'+_this.value))
		{
			def = document.getElementById('card_item_ins_'+_this.value).innerHTML;
			var ci = document.getElementById('card_item_'+_this.value);
			document.getElementById("fontsize").value = parseInt(ci.style.fontSize);
			document.getElementById("color").value = ci.style.color;
			myColor.importColor();
		}
		tdval.innerHTML = '<input type="text" class="text" maxlength="9" value="'+def+'" name="icq" id="val">';
	}
	else if(_this.value == "skype")
	{
		if(document.getElementById('card_item_ins_'+_this.value))
		{
			def = document.getElementById('card_item_ins_'+_this.value).innerHTML;
			var ci = document.getElementById('card_item_'+_this.value);
			document.getElementById("fontsize").value = parseInt(ci.style.fontSize);
			document.getElementById("color").value = ci.style.color;
			myColor.importColor();
		}
		tdval.innerHTML = '<input type="text" class="text" maxlength="40" value="'+def+'" name="skype" id="val">';
	}
	*/



	URI : function(uri) { // See RFC3986

		this.scheme = null
		this.authority = null
		this.path = ''
		this.query = null
		this.fragment = null

		this.parse = function(uri) {
			var m = uri.match(/^(([A-Za-z][0-9A-Za-z+.-]*)(:))?((\/\/)([^\/?#]*))?([^?#]*)((\?)([^#]*))?((#)(.*))?/)
			this.scheme = m[3] ? m[2] : null
			this.authority = m[5] ? m[6] : null
			this.path = m[7]
			this.query = m[9] ? m[10] : null
			this.fragment = m[12] ? m[13] : null
			return this
		}

		this.toString = function() {
			var result = ''
			if(this.scheme != null) result = result + this.scheme + ':'
			if(this.authority != null) result = result + '//' + this.authority
			if(this.path != null) result = result + this.path
			if(this.query != null) result = result + '?' + this.query
			if(this.fragment != null) result = result + '#' + this.fragment
			return result
		}

		this.toAbsolute = function(base) {
			var base = new jscolor.URI(base)
			var r = this
			var t = new jscolor.URI

			if(base.scheme == null) return false

			if(r.scheme != null && r.scheme.toLowerCase() == base.scheme.toLowerCase()) {
				r.scheme = null
			}

			if(r.scheme != null) {
				t.scheme = r.scheme
				t.authority = r.authority
				t.path = removeDotSegments(r.path)
				t.query = r.query
			} else {
				if(r.authority != null) {
					t.authority = r.authority
					t.path = removeDotSegments(r.path)
					t.query = r.query
				} else {
					if(r.path == '') {
						t.path = base.path
						if(r.query != null) {
							t.query = r.query
						} else {
							t.query = base.query
						}
					} else {
						if(r.path.substr(0,1) == '/') {
							t.path = removeDotSegments(r.path)
						} else {
							if(base.authority != null && base.path == '') {
								t.path = '/'+r.path
							} else {
								t.path = base.path.replace(/[^\/]+$/,'')+r.path
							}
							t.path = removeDotSegments(t.path)
						}
						t.query = r.query
					}
					t.authority = base.authority
				}
				t.scheme = base.scheme
			}
			t.fragment = r.fragment

			return t
		}

		function removeDotSegments(path) {
			var out = ''
			while(path) {
				if(path.substr(0,3)=='../' || path.substr(0,2)=='./') {
					path = path.replace(/^\.+/,'').substr(1)
				} else if(path.substr(0,3)=='/./' || path=='/.') {
					path = '/'+path.substr(3)
				} else if(path.substr(0,4)=='/../' || path=='/..') {
					path = '/'+path.substr(4)
					out = out.replace(/\/?[^\/]*$/, '')
				} else if(path=='.' || path=='..') {
					path = ''
				} else {
					var rm = path.match(/^\/?[^\/]*/)[0]
					path = path.substr(rm.length)
					out = out + rm
				}
			}
			return out
		}

		if(uri) {
			this.parse(uri)
		}

	},
	
		/* 3dscene.js
	zoom: function(el, kzw, kzh)
	{
		var els = el.children;
		for(i=0; i<els.length; i++)
		{
			if(!els[i].style.fontSize) els[i].style.fontSize = "12px";
			var iw = parseInt(els[i].offsetWidth);
			var ih = parseInt(els[i].offsetHeight);
			
			els[i].style.width = Math.round(iw*kzw) + "px";
			els[i].style.height = Math.round(ih*kzh) + "px";
			els[i].style.fontSize = Math.round(parseInt(els[i].style.fontSize)*kzh)+"px";
			
			if(els[i].children.length > 0) Scene.zoom(els[i], kzw, kzh);
		}
	},
	
	turn:
	//var iw = parseInt(Scene.elements[i]["this"].style.width)/Scene.elements[i]["kzw"];
	//var ih = parseInt(Scene.elements[i]["this"].style.height)/Scene.elements[i]["kzh"];
	
	setElements:
	//var iw = parseInt(Scene.elements[i]["this"].offsetWidth);
	//var ih = parseInt(Scene.elements[i]["this"].offsetHeight);
	
	//Scene.elements[i]["this"].style.width = Math.round(iw*kz) + "px";
	//Scene.elements[i]["this"].style.height = Math.round(ih*kz) + "px";
	//Scene.elements[i]["kzw"] = parseInt(Scene.elements[i]["this"].style.width)/iw;
	//Scene.elements[i]["kzh"] = parseInt(Scene.elements[i]["this"].style.height)/ih;
	
	//Scene.zoom(Scene.elements[i]["this"], Scene.elements[i]["kzw"], Scene.elements[i]["kzh"]);
	*/