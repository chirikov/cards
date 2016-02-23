<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");
include_once("inc/constants.php");
include_once("inc/functions.php");

$help_page = "map";

if(
$_GET['act'] != "default" &&
$_GET['act'] != "setposition" &&
$_GET['act'] != "delposition" &&
$_GET['act'] != "mapphoto" &&
$_GET['act'] != "mappedphoto" &&
$_GET['act'] != "createposition"
) $_GET['act'] = "default";

$body = "";

if($_GET['act'] == "default")
{
	if($_GET['show'] == "") $_GET['show'] = "all";
	$qpos = mysql_query("select position from info where id = ".$_COOKIE['mir_id']);
	$qposs = mysql_query("select id, lon, lat, name from positions where user = ".$_COOKIE['mir_id']);
	$qdefpos = mysql_query("select lon, lat from cities where id = (select city from users where id = ".$_COOKIE['mir_id'].")");
	$rdefpos = mysql_fetch_assoc($qdefpos);
	if(mysql_result($qpos, 0) == 0 && mysql_num_rows($qposs) == 0)
	{
		$mypos = array("lon" => $rdefpos["lon"], "lat" => $rdefpos["lat"]);
		$position = -1;
	}
	elseif(mysql_result($qpos, 0) == 0 && mysql_num_rows($qposs) > 0)
	{
		$mypos = array("lon" => $rdefpos["lon"], "lat" => $rdefpos["lat"]);
		$position = 0;
	}
	else
	{
		$position = mysql_result($qpos, 0);
		$qll = mysql_query("select lon, lat, name from positions where id = ".$position);
		$mypos = mysql_fetch_assoc($qll);
	}
	$qman = mysql_query("select surname, name, avatar from users where id = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($qman);
	
	$body .= '
	<script src="http://api-maps.yandex.ru/0.8/?key=ANf2H0kBAAAAyFucOgIAeJ4X-AGiHj71X20QTgRzXK5T1bIAAAAAAAAAAAC9R72ECeXMiK-BR5-fCqUhOvM7eA==" type="text/javascript"></script>
	<script language="javascript" type="text/javascript" charset="Windows-1251">
	<!--//
	var newplacemark;
	var data;
	
	var templ_me = new YMaps.Template("<table border=0><tr><td><a href=\'profile.php\'><img src=\'photos/'.$_COOKIE['mir_id'].'/'.$row['avatar'].'s.jpg\'></a></td><td><a href=\'profile.php\'>'.$row['name'].' '.$row['surname'].'</a><br>$[posname]<br><div class=\'status\'>в сети</div></td></tr></table>");
	YMaps.Templates.add("me#template", templ_me);
	
	var templ_friend = new YMaps.Template("<table border=0><tr><td><a href=\'$[href]\'><img src=\'$[imgurl]\'></a></td><td><a href=\'$[href]\'>$[fio]</a><br>$[posname]<br>$[online]</td></tr></table>");
	YMaps.Templates.add("friend#template", templ_friend);
	
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
	function init()
	{
		var map = new YMaps.Map(document.getElementById("YMapsID"));
		map.setCenter(new YMaps.GeoPoint('.$mypos['lon'].', '.$mypos['lat'].'), 14, YMaps.MapType.MAP);
		map.enableScrollZoom();
		var toolbar = new YMaps.ToolBar();
		//var typecontrol = new YMaps.TypeControl();
		//var szoom = new YMaps.SmallZoom();
		map.addControl(toolbar);
		//map.addControl(typecontrol);
		//map.addControl(szoom);
		//var mymark = false;
		//data = {"map": map, "mymark": mymark, "frmarks": new Array(), "fio": "'.$row['name'].' '.$row['surname'].'"};
		';
		//if($position > 0) $body .= 'showmen(); delhrefdisplay();';
		$body .= '
	}
	function showmen()
	{
		';
		if($position > 0)
		{
			/* //show cities
			$qc = mysql_query("select * from cities where 1");
			while($rrr = mysql_fetch_assoc($qc))
			{
				$body .= 'showme(data["map"], "'.$rrr['lon'].'", "'.$rrr['lat'].'", "'.$rrr['name'].'");';
			}
			*/
			
			//$body .= 'showman(data["map"], "'.$mypos['lon'].'", "'.$mypos['lat'].'", 1, "Гостиный двор", "images/offer1.jpg", "");';
			/*
			$body .= '
			var templ_friend2 = new YMaps.Template("<table border=0><tr><td><a href=\'$[href]\'><img src=\'$[imgurl]\'></a></td><td valign=top><div style=\'padding: 5px\'><a style=\'font-weight: bold\'>$[fio]</a><br>$[posname]<br><br>$[online]</div></td></tr></table>");
			YMaps.Templates.add("friend2#template", templ_friend2);
			showman(data["map"], "'.$mypos['lon'].'", "'.$mypos['lat'].'", 1, "Elfo del Bosco", "images/offer1.jpg", "Итальянские межкомнатные двери", "ул. Айская, 46<br>тел.: 2288577<br><a href=\'1\'>www.elfodelbosco.ru</a>");';
			*/
			$body .= 'var mymark = showme(data["map"], "'.$mypos['lon'].'", "'.$mypos['lat'].'", "'.$mypos['name'].'"); data["mymark"] = mymark;';
			
			if($_GET['show'] == "friends") $contacts = contact_list(0, "id,surname,name,avatar,lasttime");
			elseif($_GET['show'] == "friends_online") $contacts = contact_list(0, "id,surname,name,avatar,lasttime", true);
			elseif($_GET['show'] == "all")
			{
				$qall = mysql_query("select id, surname, name, avatar, lasttime from users where id != ".$_COOKIE['mir_id']." and actcode = 0");
				$contacts = array();
				$i = 0;
				while($contacts_1 = mysql_fetch_assoc($qall))
				{
					$i++;
					$contacts[$i] = $contacts_1;
				}
			}
			elseif($_GET['show'] == "all_online")
			{
				$qall_on = mysql_query("select id, surname, name, avatar, lasttime from users where id != ".$_COOKIE['mir_id']." and lasttime > ".(time()-5*60)." and actcode = 0");
				$contacts = array();
				$i = 0;
				while($contacts_1 = mysql_fetch_assoc($qall_on))
				{
					$i++;
					$contacts[$i] = $contacts_1;
				}
			}
			else $contacts = array();
			
			$i = 0;
			foreach($contacts as $contact)
			{
				$qfrpos = mysql_query("select position from info where id = ".$contact['id']);
				if(mysql_result($qfrpos, 0) != 0)
				{
					if(time() - $contact['lasttime'] > 5*60) $online = "";
					else $online = "<div class='status'>в сети</div>";
					if($contact['avatar'] == "") $imgurl = "images/noavatars.jpg";
					else $imgurl = 'photos/'.$contact['id'].'/'.$contact['avatar'].'s.jpg';
					$qpos2 = mysql_query("select lon, lat, name from positions where id = ".mysql_result($qfrpos, 0));
					$pos = mysql_fetch_assoc($qpos2);
					$body .= ' mymark = showman(data["map"], "'.$pos['lon'].'", "'.$pos['lat'].'", '.$contact['id'].', "'.$contact['name'].' '.$contact['surname'].'", "'.$imgurl.'", "'.$pos['name'].'", "'.$online.'");
					data["frmarks"]['.$i.'] = mymark;
					';
				}
				$i++;
			}
			
		}
		$body .= '
	}
	function showme(map, lon, lat, posname)
	{
		var placemark = new YMaps.Placemark(new YMaps.GeoPoint(lon, lat),
		{
			draggable: 0,
		    balloonOptions:
		    {
		        hasCloseButton: true
		    }
		}
		);
		placemark.setBalloonContent(YMaps.Templates.get("me#template").build({posname: posname}));
		placemark.setIconContent("'.$row['name'].' '.$row['surname'].'");
		map.addOverlay(placemark);
		map.panTo(placemark.getGeoPoint());
		return placemark;
	}
	function showman(map, lon, lat, uid, fio, imgurl, posname, online)
	{
		var placemark = new YMaps.Placemark(new YMaps.GeoPoint(lon, lat),
		{
			draggable: 0,
		    balloonOptions:
		    {
		        hasCloseButton: true
		    }
		}
		);
		placemark.setBalloonContent(YMaps.Templates.get("friend#template").build({href: "profile.php?act=view&uid="+uid, imgurl: imgurl, fio: fio, posname: posname, online: online}));
		placemark.setIconContent(fio);
		map.addOverlay(placemark);
		return placemark;
	}
	function newposition()
	{
		var placemark = new YMaps.Placemark(data["map"].getCenter(),
		{
			draggable: 1,
		    balloonOptions:
		    {
		        hasCloseButton: true
		    }
		}
		);
		var inner_newposition = "<form name=\'fnewposition\' method=\'post\' action=\'javascript: createposition(newplacemark);\'>";
		inner_newposition += "Я <input type=\'text\' maxlength=\'50\' name=\'posname\' value=\'дома\'><input type=\'submit\' value=\'OK\'></form>";
		placemark.setBalloonContent("<div>"+inner_newposition+"</div>");
		placemark.setIconContent("Перетащите метку<br><a href=\'#\'>Готово</a> <a href=\'#\' onclick=\'javascript: newplacemark.getMap().removeOverlay(newplacemark); return false;\'>Отмена</a>");
		data["map"].addOverlay(placemark);
		return placemark;
	}
	function createposition(placemark)
	{
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			oXmlHttp.open("GET", "map.php?act=createposition&name="+encodeURIComponent(fnewposition.posname.value)+"&lon="+placemark.getGeoPoint().getLng()+"&lat="+placemark.getGeoPoint().getLat(), true);
			oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			oXmlHttp.onreadystatechange = function()
			{
				if(oXmlHttp.readyState == 1)
				{
					wait(1, "wait_map");
				}
				if(oXmlHttp.readyState == 4)
				{
					var result = oXmlHttp.responseText;
					switch(result)
					{
						case "ok":
						location = "map.php";
						break;
					}
					wait(0, "wait_map");
				}
			}
			oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
	       	oXmlHttp.send();
		}
	}
	function delposition()
	{
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			var places = document.getElementById("places");
			var args = places.value.split(";");
			oXmlHttp.open("GET", "map.php?act=delposition&id="+args[0], true);
			oXmlHttp.onreadystatechange = function()
			{
				if(oXmlHttp.readyState == 1)
				{
					wait(1, "wait_map");
				}
				if(oXmlHttp.readyState == 4)
				{
					var result = oXmlHttp.responseText;
					switch(result)
					{
						case "ok":
						for(i=0; i<places.options.length; i++)
						{
							if(places.options(i).selected == true) places.options.remove(i);
						}
						places.options("pos0").selected = true;
						setposition();
						break;
					}
					wait(0, "wait_map");
				}
			}
			oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
	       	oXmlHttp.send();
		}
	}
	function setposition()
	{
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			var args = document.getElementById("places").value.split(";");
			oXmlHttp.open("GET", "map.php?act=setposition&id="+args[0], true);
			oXmlHttp.onreadystatechange = function()
			{
				if(oXmlHttp.readyState == 1)
				{
					wait(1, "wait_map");
				}
				if(oXmlHttp.readyState == 4)
				{
					var result = oXmlHttp.responseText;
					switch(result)
					{
						case "ok":
						if(args[0] != "0")
						{
							if(data[\'mymark\'] == false)
							{
								location = "map.php";
							}
							else
							{
								data[\'mymark\'].setGeoPoint(new YMaps.GeoPoint(args[1], args[2]))
								data[\'mymark\'].setBalloonContent(YMaps.Templates.get("me#template").build({posname: args[3]}));
								data[\'map\'].panTo(data[\'mymark\'].getGeoPoint());
							}
						}
						else
						{
							data[\'map\'].removeOverlay(data[\'mymark\']);
							for(i=0; i<data[\'frmarks\'].length; i++)
							{
								data[\'map\'].removeOverlay(data[\'frmarks\'][i]);
							}
							data["mymark"] = false;
						}
						break;
					}
					wait(0, "wait_map");
				}
			}
			oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
	       	oXmlHttp.send();
		}
		delhrefdisplay();
	}
	function delhrefdisplay()
	{
		if(document.getElementById("places").value == 0) document.getElementById(\'delhref\').style.display = \'none\';
		else document.getElementById(\'delhref\').style.display = \'\';
	}
	function setcontent()
	{
		switch(document.getElementById("selwhom").value)
		{
			case "friends": location = "map.php?show=friends"; break;
			case "friends_online": location = "map.php?show=friends_online"; break;
			case "all": location = "map.php?show=all"; break;
			case "all_online": location = "map.php?show=all_online"; break;
			case "noone": location = "map.php?show=noone"; break;
		}
	}
	function wait(onoff, element_id)
	{
		if(onoff == 1)
		{
			document.getElementById(element_id).style.display = "";
		}
		else
		{
			document.getElementById(element_id).style.display = "none";
		}
	}
	//-->
	</script>
	<div id="YMapsID" style="height:512px; width:512px;"></div><br>
	<div class="start">
	<div class="start_form">
		<div class="top_corners"><i>&nbsp;</i></div>
		<div class="start_form_pad"><table class="form_table"><tr><td><a href="#" onclick="javascript: document.getElementById(\'qweqwe\').innerText = document.getElementById(\'YMapsID\').innerHTML;">@@@@@</a>';
	if($position == -1)
	{
		$body .= '<a href="#" onclick="javascript: newplacemark = newposition();">Создать место</a> <img id="wait_map" src="images/wait.gif" style="display: none"/>';
	}
	else
	{
		$body .= 'Где Вы: <select id="places" onchange="javascript: setposition(); document.getElementById(\'YMapsID\').focus();">';
		while($rposs = mysql_fetch_assoc($qposs))
		{
			$body .= '<option id="pos'.$rposs['id'].'" value="'.$rposs['id'].';'.$rposs['lon'].';'.$rposs['lat'].';'.$rposs['name'].';"';
			if($position == $rposs['id']) $body .= ' selected';
			$body .= '>'.$rposs['name'];
		}
		$body .= '<option id="pos0" value="0"';
		if($position == 0) $body .= ' selected';
		$body .= '>Не показывать на карте
		</select> <img id="wait_map" src="images/wait.gif" style="display: none"/><br>
		<a href="#" onclick="javascript: newplacemark = newposition();">Создать новое место</a> <a id="delhref" href="#" onclick="javascript: delposition();">Удалить это место</a><br><br>
		';
	}
	if($position > 0)
	{		
		$body .= 'Показывать: <select id="selwhom" name="whomshow" onchange="javascript: setcontent();">
		<option '; if($_GET['show'] == "friends") $body .= 'selected '; $body .= 'value="friends">Контакты
		<option '; if($_GET['show'] == "friends_online") $body .= 'selected '; $body .= 'value="friends_online">Контакты в сети
		<option '; if($_GET['show'] == "all") $body .= 'selected '; $body .= 'value="all">Всех
		<option '; if($_GET['show'] == "all_online") $body .= 'selected '; $body .= 'value="all_online">Всех в сети
		<option '; if($_GET['show'] == "noone") $body .= 'selected '; $body .= 'value="noone">Никого
		</select>';
	}
	$body .= '
	</td></tr></table></div>
		<div class="bottom_corners"><i>&nbsp;</i></div>
	</div>
	</div>
	<div id="qweqwe"></div>
	';
}
elseif($_GET['act'] == "createposition")
{
	$q1 = mysql_query("insert into positions (user, lon, lat, name) values(".$_COOKIE['mir_id'].", '".$_GET['lon']."', '".$_GET['lat']."', '".iconv('UTF-8', 'windows-1251', trim($_GET['name']))."')");
	$id = mysql_insert_id($mysql);
	if(mysql_query("update info set position = ".$id." where id = ".$_COOKIE['mir_id'])) print "ok";
	exit;
}
elseif($_GET['act'] == "setposition")
{
	if(mysql_query("update info set position = ".$_GET['id']." where id = ".$_COOKIE['mir_id'])) print "ok";
	exit;
}
elseif($_GET['act'] == "delposition")
{
	$q = mysql_query("delete from positions where id = ".$_GET['id']);
	if(mysql_query("update info set position = 0 where id = ".$_COOKIE['mir_id'])) print "ok";
	exit;
}
elseif($_GET['act'] == "mapphoto")
{
	$qalbum = mysql_query("select code, album from photos where id = ".$_GET['pid']);
	if(mysql_num_rows($qalbum) == 0)
	{
		header("Location: profile.php");
		exit;
	}
	$aid = mysql_result($qalbum, 0, 'album');
	$code = mysql_result($qalbum, 0, 'code');
	$qsecure = mysql_query("select name, lon, lat from albums where id = '".$aid."' and uid = ".$_COOKIE['mir_id']);
	if(mysql_num_rows($qsecure) == 0)
	{
		header("Location: profile.php");
		exit;
	}
	$defpos = $albumll = mysql_fetch_assoc($qsecure);
	if($defpos['lon'] == "" || $defpos['lat'] == "")
	{
		$qdefpos = mysql_query("select lon, lat from cities where id = (select city from users where id = ".$_COOKIE['mir_id'].")");
		$defpos = $cityll = mysql_fetch_assoc($qdefpos);
	}
	$qpos = mysql_query("select lon, lat, name from positions where id = (select position from info where id = ".$_COOKIE['mir_id'].")");
	if(mysql_num_rows($qpos) > 0) $mell = mysql_fetch_assoc($qpos);
	else $mell = false;
	$qman = mysql_query("select surname, name from users where id = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($qman);
	
	$body .= '
	<script src="http://api-maps.yandex.ru/0.8/?key=ANf2H0kBAAAAyFucOgIAeJ4X-AGiHj71X20QTgRzXK5T1bIAAAAAAAAAAAC9R72ECeXMiK-BR5-fCqUhOvM7eA==" type="text/javascript"></script>
	<script language="javascript" type="text/javascript" charset="utf-8">
	<!--//
	var photomark;
	
	function init()
	{
		var map = new YMaps.Map(document.getElementById("YMapsID"));
		map.setCenter(new YMaps.GeoPoint('.$defpos['lon'].', '.$defpos['lat'].'), 14, YMaps.MapType.MAP);
		map.enableScrollZoom();
		var toolbar = new YMaps.ToolBar();
		var typecontrol = new YMaps.TypeControl();
		var szoom = new YMaps.SmallZoom();
		map.addControl(toolbar);
		map.addControl(typecontrol);
		map.addControl(szoom);
		';
		if($mell) $body .= 'showme(map, "'.$mell['lon'].'", "'.$mell['lat'].'", "'.$mell['name'].'")';
		$body .= '
		var placemark = new YMaps.Placemark(map.getCenter(),
		{
			draggable: 1,
		    balloonOptions:
		    {
		        hasCloseButton: true
		    }
		}
		);
		placemark.setBalloonContent("<div><img src=\'photos/'.$_COOKIE['mir_id'].'/'.$aid.'/'.$code.'s.jpg\'></div>");
		placemark.setIconContent("Перетащите метку<br><a href=\'#\' onclick=\'javascript: mapphoto();\'>Готово</a> <a href=\'photo.php?act=view&pid='.$_GET['pid'].'\'>Отмена</a>");
		map.addOverlay(placemark);
		photomark = placemark;
	}
	
	function mapphoto()
	{
		location = "map.php?act=mappedphoto&pid='.$_GET['pid'].'&lon="+photomark.getGeoPoint().getLng()+"&lat="+photomark.getGeoPoint().getLat();
	}
	function showme(map, lon, lat, posname)
	{
		var placemark = new YMaps.Placemark(new YMaps.GeoPoint(lon, lat),
		{
			draggable: 0,
		    balloonOptions:
		    {
		        hasCloseButton: true
		    }
		}
		);
		placemark.setBalloonContent("<div>'.$row['name'].' '.$row['surname'].'</div>");
		placemark.setIconContent("'.$row['name'].' '.$row['surname'].'");
		map.addOverlay(placemark);
		//map.panTo(placemark.getGeoPoint());
		return placemark;
	}
	//-->
	</script>
	<div id="YMapsID" style="height:400px; width:600px;"></div><br>
	';
}
elseif($_GET['act'] == "mappedphoto")
{
	$qsecure = mysql_query("select id from albums where id = (select album from photos where id = ".$_GET['pid'].") and uid = ".$_COOKIE['mir_id']);
	if(mysql_num_rows($qsecure) == 0)
	{
		header("Location: profile.php");
		exit;
	}
	$q = mysql_query("update photos set lon = '".$_GET['lon']."', lat = '".$_GET['lat']."' where id = ".$_GET['pid']);
	header("Location: photo.php?act=view&pid=".$_GET['pid']);
	exit;
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>