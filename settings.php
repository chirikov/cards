<?php

function update_tabs($title, $val, $other=false, $nt=false, $oid="")
{
	global $print_return;
	
	if($val != "")
	{
		$style = "";
		if($title == "image" || $title == "photo")
		{
			if($oid == "") $style .= "background-color: #ffffff; ";
			elseif($_POST['backgroundtransp'] != "1") $style .= "background-color: ".$_POST['backgroundColor']."; ";
			else $style .= "background-color: transparent; ";
			$_POST['borderWidth'] = intval($_POST['borderWidth']);
			if($_POST['borderWidth'] > 10) $_POST['borderWidth'] = 10;
			if($oid == "") $style .= "border: 1px solid #d3d3d3; ";
			elseif($_POST['borderColor'] != "") $style .= "border: ".$_POST['borderWidth']."px solid ".$_POST['borderColor']."; ";
			$_POST['padding'] = abs(intval($_POST['padding']));
			if($_POST['padding'] > 20) $_POST['padding'] = 20;
			if($oid == "") $style .= "padding: 3px; ";
			else $style .= "padding: ".$_POST['padding']."px; ";
			$_POST['transparency'] = abs(intval($_POST['transparency']));
			$opacity = (100 - $_POST['transparency'])/100;
			$opacity<0.1?$opacity=0.1:true;
			if($opacity < 1) $style .= "opacity: ".$opacity."; filter: progid:DXImageTransform.Microsoft.Alpha(opacity=".($opacity*100).");";
		}
		elseif($title == "box")
		{
			$w = abs(intval($_POST['width']));
			if($w < 5) $w = 5;
			if($w > 870) $w = 870;
			$style .= "width: ".$w."px; ";
			$h = abs(intval($_POST['height']));
			if($h < 5) $h = 5;
			if($h > 600) $h = 600;
			$style .= "height: ".$h."px; ";
			$style .= "background-color: ".$_POST['backgroundColor']."; ";
			$_POST['borderWidth'] = intval($_POST['borderWidth']);
			if($_POST['borderWidth'] > 10) $_POST['borderWidth'] = 10;
			if($_POST['borderWidth'] > 0 && $_POST['borderColor'] != "") $style .= "border: ".$_POST['borderWidth']."px solid ".$_POST['borderColor']."; ";
			$_POST['transparency'] = abs(intval($_POST['transparency']));
			$opacity = (100 - $_POST['transparency'])/100;
			$opacity<0.1?$opacity=0.1:true;
			if($opacity < 1) $style .= "opacity: ".$opacity."; filter: progid:DXImageTransform.Microsoft.Alpha(opacity=".($opacity*100).");";
		}
		else
		{
			if($_POST['fontFamily'] != "Verdana") $style .= "font-family: ".$_POST['fontFamily']."; ";
			$_POST['fontSize'] = intval($_POST['fontSize']);
			if($_POST['fontSize'] < 6) $_POST['fontSize'] = 6;
			if($_POST['fontSize'] != 9) $style .= "font-size: ".$_POST['fontSize']."pt; ";
			if($_POST['color'] != "") $style .= "color: ".$_POST['color']."; ";
			if($_POST['bold'] == "1") $style .= "font-weight: bold; ";
			if($_POST['italic'] == "1") $style .= "font-style: italic; ";
			if($_POST['backgroundtransp'] != "1") $style .= "background-color: ".$_POST['backgroundColor']."; ";
			else $style .= "background-color: transparent; ";
			$_POST['borderWidth'] = intval($_POST['borderWidth']);
			if($_POST['borderWidth'] > 10) $_POST['borderWidth'] = 10;
			if($_POST['borderWidth'] > 0 && $_POST['borderColor'] != "") $style .= "border: ".$_POST['borderWidth']."px solid ".$_POST['borderColor']."; ";
			$_POST['padding'] = abs(intval($_POST['padding']));
			if($_POST['padding'] > 20) $_POST['padding'] = 20;
			if($_POST['padding'] > 0) $style .= "padding: ".$_POST['padding']."px; ";
			$_POST['transparency'] = abs(intval($_POST['transparency']));
			$opacity = (100 - $_POST['transparency'])/100;
			$opacity<0.1?$opacity=0.1:true;
			if($opacity < 1) $style .= "opacity: ".$opacity."; filter: progid:DXImageTransform.Microsoft.Alpha(opacity=".($opacity*100).");";
		}
		
		$q0 = mysql_query("select tabs from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
		$tabs = mysql_result($q0, 0);
		$tabs = json_decode($tabs, true);
		
		//$tabs[$_POST['tab']]['items']["image".$num] = array("id" => "image".$num, "value" => $code, "x" => 0, "y" => 0);
		if($other && $oid == $other)
		{
			$num = 0;
			while(in_array2($oid.$num, $tabs))
			{
				$num++;
			}
			$id = $oid.$num;
		}
		elseif($other) $id = $oid;
		else $id = $title;
		
		$foundtab = -1;
		for($i=0; $i<count($tabs); $i++)
		{
			if(isset($tabs[$i]['items'][$id])) {$foundtab = $i; break;}
		}
		if($foundtab == -1)
		{
			$tabs[$_POST['tab']]['items'][$id] = array("id" => $id, "title" => ($other=="other"?$title:""), "value" => (($other=="other"||$other=="image")?$val:""), "x" => 0, "y" => 0, "style" => $style, "nt" => $nt, "other" => ($other=="other"));
			$tabs[$_POST['tab']]['itemsnum'] = count($tabs[$_POST['tab']]['items']);
		}
		elseif($foundtab != $_POST['tab'])
		{
			print "EОбновлено на другой вкладке";
			$print_return = false;
			
			$tabs[$foundtab]['items'][$id]['value'] = (($other=="other"||$other=="image")?$val:"");
			if($other=="other") $tabs[$foundtab]['items'][$id]['title'] = $title;
			$tabs[$foundtab]['items'][$id]['style'] = $style;
			$tabs[$foundtab]['items'][$id]['nt'] = $nt;
		}
		else
		{
			$tabs[$_POST['tab']]['items'][$id]['value'] = (($other=="other"||$other=="image")?$val:"");
			if($other=="other") $tabs[$_POST['tab']]['items'][$id]['title'] = $title;
			$tabs[$_POST['tab']]['items'][$id]['style'] = $style;
			$tabs[$_POST['tab']]['items'][$id]['nt'] = $nt;
		}
		$tabs = json_encode($tabs);
		if(!$other) $q = mysql_query('update people set tabs = "'.mysql_escape_string($tabs).'", '.$id.' = "'.$val.'" where id = '.$_COOKIE['2ndw_userid'].' limit 1');
		else $q = mysql_query('update people set tabs = "'.mysql_escape_string($tabs).'" where id = '.$_COOKIE['2ndw_userid'].' limit 1');
		
		if($other || $title = "photo") return array("id" => $id, "style" => $style);
	}
	else {print "EВведите значение"; $print_return = false;}
}

header("Content-type: text/html; charset=utf-8");
include_once("inc/my_connect.php");
include_once("inc/control.php");
include_once("inc/functions.php");
/*
if($_GET['act'] == "setname")
{
	$val = $_POST['val'];
	$val = htmlspecialchars(mb_substr(trim($val), 0, 40));
	if(!is_valid("name", $val, "1,40")) print "EИмя введено неверно";
	else
	{
		$q = mysql_query("update people set name = '".$val."' where id = ".$_COOKIE['2ndw_userid']." limit 1");
		if($q) print stripslashes($val);
		else print "EОшибка базы данных. Попробуйте еще раз.";
	}
	exit;
}
*/
if($_GET['act'] == "setphoto")
{
	$q1 = mysql_query("select photo from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
	$code = mysql_result($q1, 0);
	$code0 = $code;
	$check = check_image_file($_FILES['photo']);
	if($check !== true && $_FILES['photo']['size'] > 0) $result = $check;
	else
	{
		if($check === true)
		{
			if($code != "")
			{
				unlink("photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg");
				unlink("photos/".$_COOKIE['2ndw_userid']."/".$code."s.jpg");
			}
			$ar = range('a', 'z');
			shuffle($ar);
			$code = substr(implode("", $ar), rand(0, 19), 7);
			while(file_exists("photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg"))
			{
				$code = substr(implode("", $ar), rand(0, 19), 7);
			}
			move_uploaded_file($_FILES['photo']['tmp_name'], "photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg");
			resize_photo("photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg", 300, 225, 100, 75, 80, 90);
			
			//$q = mysql_query("update people set photo = '".$code."' where id = ".$_COOKIE['2ndw_userid']." limit 1");
		}
		$id = update_tabs("photo", $code, false, true, $code0);
		echo '
		<script type="text/javascript">
		window.parent.document.getElementById("a_photo").innerHTML = "<img alt=\'\' style=\''.$id['style'].'\' src=\'photos/'.$_COOKIE['2ndw_userid'].'/'.$code.'.jpg\' class=\'border\'>";
		window.parent.Win.hide();
		</script>';		
		exit;
	}
	echo '<script type="text/javascript">
	window.parent.Win.response("'.substr($result, 1).'");
	window.parent.Win.wait(0);
	</script>';
	exit;
}
elseif($_GET['act'] == "setimage")
{
	$check = check_image_file($_FILES['photo']);
	if($check !== true && $_FILES['photo']['size'] > 0) $result = $check;
	else
	{
		$q1 = mysql_query("select tabs from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
		$tabs = mysql_result($q1, 0);
		$tabs = json_decode($tabs, true);
		$code = $tabs[$_POST['tab']]['items'][$_POST['id']]['value'];
		if($check === true)
		{
			unlink("photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg");
			
			$ar = range('a', 'z');
			shuffle($ar);
			$code = substr(implode("", $ar), rand(0, 19), 7);
			while(file_exists("photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg"))
			{
				$code = substr(implode("", $ar), rand(0, 19), 7);
			}
			move_uploaded_file($_FILES['photo']['tmp_name'], "photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg");
			resize_photo("photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg", 150, 112, 0, 0, 90);
		}
		$id = update_tabs("image", $code, "image", true, $_POST['id']);
		echo '
		<script type="text/javascript">
		window.parent.document.getElementById("a_'.$_POST['id'].'").innerHTML = "<img alt=\'\' style=\''.$id['style'].'\' src=\'photos/'.$_COOKIE['2ndw_userid'].'/'.$code.'.jpg\' class=\'border\'>";
		window.parent.Win.hide();
		</script>';
		exit;
	}
	echo '<script type="text/javascript">
	window.parent.Win.response("'.substr($result, 1).'");
	window.parent.Win.wait(0);
	</script>';
	exit;
}
elseif($_GET['act'] == "setbox")
{
	$id = update_tabs("box", 1, "box", true, $_POST['id']);
	exit;
}
elseif($_GET['act'] == "setbg")
{
	//$q1 = mysql_query("select bg from cards where id = ".$_COOKIE['2ndw_userid']." limit 1");
	//$bg = mysql_result($q1, 0);
	$q1 = mysql_query("select tabs from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
	$tabs = mysql_result($q1, 0);
	$tabs = json_decode($tabs, true);
	$bg = $tabs[$_POST['tab']]['bg'];
	if($_POST['type'] == "color")
	{
		if($_POST['color'] != "") $val = $_POST['color'];
		else {print "EВыберите цвет"; exit;}
		if($bg != "" && substr($bg, 0, 1) != "#")
		{
			unlink("photos/".$_COOKIE['2ndw_userid']."/".$bg.".jpg");
			unlink("photos/".$_COOKIE['2ndw_userid']."/".$bg."s.jpg");
		}
		if(substr($val, 0, 1) != "#") $val = "#".substr($val, 0, 6);
	}
	else
	{
		$check = check_image_file($_FILES['photo']);
		if($check !== true)
		{
			echo '<script type="text/javascript">
			window.parent.Win.response("'.substr($check, 1).'");
			window.parent.Win.wait(0);
			</script>';
			exit;
		}
		else
		{
			if($bg != "" && substr($bg, 0, 1) != "#")
			{
				unlink("photos/".$_COOKIE['2ndw_userid']."/".$bg.".jpg");
				unlink("photos/".$_COOKIE['2ndw_userid']."/".$bg."s.jpg");
			}
			
			$ar = range('a', 'z');
			shuffle($ar);
			$code = substr(implode("", $ar), rand(0, 19), 7);
			while(file_exists("photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg"))
			{
				$code = substr(implode("", $ar), rand(0, 19), 7);
			}
			$val = $code;
			move_uploaded_file($_FILES['photo']['tmp_name'], "photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg");
			resize_photo("photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg", 600, 870, 75, 100, 80, 90);
		}
	}
	$tabs[$_POST['tab']]['bg'] = $val;
	$tabs = json_encode($tabs);
	mysql_query('update people set tabs = "'.mysql_escape_string($tabs).'" where id = '.$_COOKIE['2ndw_userid'].' limit 1');
	//mysql_query("update cards set bg = '".$val."' where id = ".$_COOKIE['2ndw_userid']." limit 1");
	if($_POST['type'] == "color")
	{
		print $val;
	}
	else
	{
		print '<script type="text/javascript">
		window.parent.document.getElementById("card_body").style.background = "url(photos/'.$_COOKIE['2ndw_userid'].'/'.$val.'.jpg) 50% 50% no-repeat";
		window.parent.Win.hide();
		</script>';
	}
	exit;
}
elseif($_GET['act'] == "setaddtab")
{
	$val = stripslashes($_POST['val']);
	$val = htmlspecialchars(mb_substr(trim($val), 0, 20));
	if($val == "") print "EВведите название вкладки";
	else
	{
		$q0 = mysql_query("select tabs from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
		$tabs = mysql_result($q0, 0);
		$tabs = json_decode($tabs, true);
		$tabid = count($tabs);
		$tabs[] = array("title" => $val, "items" => "");
		$tabs = json_encode($tabs);
		$q = mysql_query('update people set tabs = "'.mysql_escape_string($tabs).'" where id = '.$_COOKIE['2ndw_userid'].' limit 1');
		print $val;
	}
	exit;
}
elseif($_GET['act'] == "setedittab")
{
	$val = stripslashes($_POST['val']);
	$val = htmlspecialchars(mb_substr(trim($val), 0, 20));
	if($val == "") print "EВведите название вкладки";
	else
	{
		$q0 = mysql_query("select tabs from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
		$tabs = mysql_result($q0, 0);
		$tabs = json_decode($tabs, true);
		$tabs[$_POST['tab']]['title'] = $val;
		$tabs = json_encode($tabs);
		$q = mysql_query('update people set tabs = "'.mysql_escape_string($tabs).'" where id = '.$_COOKIE['2ndw_userid'].' limit 1');
		print $val;
	}
	exit;
}
elseif($_GET['act'] == "setdeltab")
{
	$_POST['tab'] = intval($_POST['tab']);
	$_POST['totab'] = intval($_POST['totab']);
	if($_POST['tab'] == 0) exit;
	$q0 = mysql_query("select tabs from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
	$tabs = mysql_result($q0, 0);
	$tabs = json_decode($tabs, true);
	if($_POST['todo'] == "del")
	{
		$fdb = array("name", "surname", "photo", "sex", "birthdate", "mtel", "dtel", "rtel", "home", "work", "icq", "skype", "fstatus", "city");
		$query = "update people set";
		$i = 0;
		foreach($tabs[$_POST['tab']]['items'] as $item)
		{
			if(in_array($item['id'], $fdb)) {$query .= ($i>0?", ":" ").$item['id'].' = NULL'; $i++;}
		}
		$query .= " where id = ".$_COOKIE['2ndw_userid']." limit 1";
		if($i>0) mysql_query($query);
		print "0";
	}
	else
	{
		if(!empty($tabs[$_POST['totab']]['items'])) $tabs[$_POST['totab']]['items'] = array_merge($tabs[$_POST['totab']]['items'], $tabs[$_POST['tab']]['items']);
		else $tabs[$_POST['totab']]['items'] = $tabs[$_POST['tab']]['items'];
		if($_POST['totab'] > $_POST['tab']) print $_POST['totab']-1;
		else print $_POST['totab'];
	}
	array_splice($tabs, $_POST['tab'], 1);
	$tabs = json_encode($tabs);
	$q = mysql_query("update people set tabs = '".mysql_escape_string($tabs)."' where id = ".$_COOKIE['2ndw_userid']." limit 1");
	exit;
}
elseif($_GET['act'] == "setlayout")
{
	$q0 = mysql_query("select tabs from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
	$tabs = mysql_result($q0, 0);
	$tabs = json_decode($tabs, true);
	
	$layout = json_decode(stripslashes($_POST['layout']), true);
	foreach($layout as $el)
	{
		$tabs[$_GET['tab']]['items'][$el['id']]['x'] = intval($el['x']);
		$tabs[$_GET['tab']]['items'][$el['id']]['y'] = intval($el['y']);
	}
	$tabs = json_encode($tabs);
	$q = mysql_query('update people set tabs = "'.mysql_escape_string($tabs).'" where id = '.$_COOKIE['2ndw_userid'].' limit 1');
	exit;
}
elseif($_GET['act'] == "setadditem")
{
	$print_return = true;
	if(substr($_POST['id'], 0, 5) == "other")
	{
		$val = stripslashes($_POST['val']);
		$val = htmlspecialchars(trim($val), ENT_NOQUOTES);
		$other_title = stripslashes($_POST['other_title']);
		$other_title = htmlspecialchars(trim($other_title), ENT_NOQUOTES);
		if($other_title != "")
		{
			$title = $other_title;
			$id = update_tabs($other_title, $val, "other", ($_POST['notitle']=="1"?true:false), $_POST['id']);
			print json_encode(array("id" => $id['id'], "title" => $title, "value" => $val, "nt" => ($_POST['notitle']=="1"?true:false), "other" => true));
		}
		else {print "EВведите заголовок"; exit;}
	}
	elseif($_POST['id'] == "name")
	{
		$val = htmlspecialchars(mb_substr(trim($_POST[$_POST['title']]), 0, 40));
		if(!is_valid("en,ru", $val)) {print "EИмя введено неверно"; exit;}
		else
		{
			update_tabs($_POST['title'], $val, false, true);
			print json_encode(array("id" => $_POST['title'], "title" => "", "value" => $val, "nt" => true, "other" => false));
		}
	}
	elseif($_POST['id'] == "surname")
	{
		$val = htmlspecialchars(mb_substr(trim($_POST[$_POST['title']]), 0, 40));
		if(!is_valid("surname", $val)) {print "EФамилия введена неверно"; exit;}
		else
		{
			update_tabs($_POST['title'], $val, false, true);
			print json_encode(array("id" => $_POST['title'], "title" => "", "value" => $val, "nt" => true, "other" => false));
		}
	}
	elseif($_POST['id'] == "city")
	{
		update_tabs($_POST['title'], $_POST["val"]);
		$title = "Город";
		$val = format_address($_COOKIE['2ndw_userid'], "people.city", true);
		print json_encode(array("id" => $_POST['title'], "title" => $title, "value" => $val, "nt" => false, "other" => false));
	}
	elseif($_POST['id'] == "mtel")
	{
		$val = substr($_POST[$_POST['title']], 0, 20);
		if(is_valid("tel", $val))
		{
			update_tabs($_POST['title'], $val);
			$title = "Мобильный телефон";
			$val = $_POST[$_POST['title']];
			print json_encode(array("id" => $_POST['title'], "title" => $title, "value" => $val, "nt" => false, "other" => false));
		}
		else {print "EНомер введен неверно"; exit;}
	}
	elseif($_POST['id'] == "dtel")
	{
		$val = substr($_POST[$_POST['title']], 0, 20);
		if(is_valid("tel", $val))
		{
			update_tabs($_POST['title'], $val);
			$title = "Домашний телефон";
			$val = $_POST[$_POST['title']];
			print json_encode(array("id" => $_POST['title'], "title" => $title, "value" => $val, "nt" => false, "other" => false));
		}
		else {print "EНомер введен неверно"; exit;}
	}
	elseif($_POST['id'] == "rtel")
	{
		$val = substr($_POST[$_POST['title']], 0, 20);
		if(is_valid("tel", $val))
		{
			update_tabs($_POST['title'], $val);
			$title = "Рабочий телефон";
			$val = $_POST[$_POST['title']];
			print json_encode(array("id" => $_POST['title'], "title" => $title, "value" => $val, "nt" => false, "other" => false));
		}
		else {print "EНомер введен неверно"; exit;}
	}
	elseif($_POST['id'] == "icq")
	{
		$val = substr($_POST[$_POST['title']], 0, 9);
		if(is_valid("num", $val))
		{
			update_tabs($_POST['title'], $val);
			$title = "ICQ";
			$val = $_POST[$_POST['title']];
			print json_encode(array("id" => $_POST['title'], "title" => $title, "value" => $val, "nt" => false, "other" => false));
		}
		else {print "EНомер ICQ должен содержать только цифры"; exit;}
	}
	elseif($_POST['id'] == "skype")
	{
		$val = substr($_POST[$_POST['title']], 0, 40);
		if(is_valid("en,num,punct", $val))
		{
			update_tabs($_POST['title'], $val);
			$title = "Skype";
			$val = $_POST[$_POST['title']];
			print json_encode(array("id" => $_POST['title'], "title" => $title, "value" => $val, "nt" => false, "other" => false));
		}
		else {print "ESkypeID введен неверно"; exit;}
	}
	elseif($_POST['id'] == "fstatus")
	{
		update_tabs($_POST['title'], $_POST[$_POST['title']]);
		$title = "Семейное положение";
		$fstatuses = array("", "Не женат/Не замужем", "Встречаюсь", "Помолвлен(а)", "Женат/Замужем", "Ищу");
		$val = $fstatuses[$_POST[$_POST['title']]];
		print json_encode(array("id" => $_POST['title'], "title" => $title, "value" => $val, "nt" => false, "other" => false));
	}
	elseif($_POST['id'] == "birthdate")
	{
		$birthdate = $_POST['birthdate'];
		$birthdate = trim(mb_eregi_replace("[.-/]{1,}", " ", $birthdate));
		if($birthdate != "")
		{
			$parts = explode(" ", $birthdate);
			if(count($parts) == 3 || (count($parts) == 4 && mb_eregi("г", $parts[3])))
			{
				if(intval($parts[0]) == $parts[0] || "0".intval($parts[0]) == $parts[0]) $d = $parts[0];
				else {print "EФормат даты: день месяц год"; exit;}
				
				if(mb_eregi("янв", $parts[1])) $m = 1;
				elseif(mb_eregi("фев", $parts[1])) $m = 2;
				elseif(mb_eregi("мар", $parts[1])) $m = 3;
				elseif(mb_eregi("апр", $parts[1])) $m = 4;
				elseif(mb_eregi("ма(й|я)", $parts[1])) $m = 5;
				elseif(mb_eregi("июн", $parts[1])) $m = 6;
				elseif(mb_eregi("июл", $parts[1])) $m = 7;
				elseif(mb_eregi("авг", $parts[1])) $m = 8;
				elseif(mb_eregi("сен", $parts[1])) $m = 9;
				elseif(mb_eregi("окт", $parts[1])) $m = 10;
				elseif(mb_eregi("ноя", $parts[1])) $m = 11;
				elseif(mb_eregi("дек", $parts[1])) $m = 12;
				elseif(intval($parts[1]) == $parts[1] || "0".intval($parts[1]) == $parts[1]) $m = $parts[1];
				else {print "EФормат даты: день месяц год"; exit;}
				
				$parts[2] = mb_eregi_replace("[^0-9]{1,}", "", $parts[2]);
				
				if(strlen($parts[2]) == 2)
				{
					if($parts[2] >= 0 && $parts[2] <= 15) $y = "20".$parts[2];
					else $y = "19".$parts[2];
				}
				elseif(strlen($parts[2]) == 4) $y = $parts[2];
				else {print "EФормат даты: день месяц год"; exit;}
				
				$bd = mktime(0,0,1,$m,$d,$y);
				
				update_tabs($_POST['title'], $bd);
				$title = "Дата рождения";
				$val = rusdate("@j@ @month_rod@ @Y@", $bd);
				print json_encode(array("id" => $_POST['title'], "title" => $title, "value" => $val, "nt" => false, "other" => false));
			}
			else {print "EФормат даты: день месяц год"; exit;}
		}
		else {print "EВведите дату рождения"; exit;}
	}
	elseif($_POST['id'] == "home")
	{
		$city = $_POST['city'];
		$street = $_POST['street'];
		$house = $_POST['house']=="Дом"?"":$_POST['house'];
		$house = ereg_replace("#", "№", $house);
		$val = $city."#".$street."#".$house;
		if($city != "")
		{
			update_tabs($_POST['title'], $val);
			$title = "Адрес";
			$val = format_address($_COOKIE['2ndw_userid']);
			print json_encode(array("id" => $_POST['title'], "title" => $title, "value" => $val, "nt" => false, "other" => false));
		}
		else {print "EВыберите город"; exit;}
	}
	elseif($_POST['id'] == "work")
	{
		$city = $_POST['city'];
		$street = $_POST['street'];
		$house = $_POST['house']=="Дом"?"":$_POST['house'];
		$house = ereg_replace("#", "№", $house);
		$company = $_POST['company']=="Организация"?"":$_POST['company'];
		$company = ereg_replace("#", "№", $company);
		$company = htmlspecialchars($company, ENT_NOQUOTES);
		$job = $_POST['job']=="Должность"?"":$_POST['job'];
		$job = ereg_replace("#", "№", $job);
		$job = htmlspecialchars($job, ENT_NOQUOTES);
		
		$val = $city."#".$street."#".$house."#".$company."#".$job;
		if($company != "")
		{
			update_tabs($_POST['title'], $val);
			$title = "Работа";
			$val = $company.($job!=""?", ":"").$job."<br>".format_address($_COOKIE['2ndw_userid'], "people.work");
			print json_encode(array("id" => $_POST['title'], "title" => $title, "value" => $val, "nt" => false, "other" => false));
		}
		else {print "EВведите организацию"; exit;}
	}
	elseif(substr($_POST['id'], 0, 3) == "box")
	{
		$id = update_tabs("box", 1, "box", true, $_POST['id']);
		print json_encode(array("id" => $id['id'], "title" => "", "value" => "", "nt" => true, "other" => false));
	}
	elseif(substr($_POST['id'], 0, 5) == "image")
	{
		$check = check_image_file($_FILES['photo']);
		if($check !== true) $result = $check;
		else
		{
			$ar = range('a', 'z');
			shuffle($ar);
			$code = substr(implode("", $ar), rand(0, 19), 7);
			while(file_exists("photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg"))
			{
				$code = substr(implode("", $ar), rand(0, 19), 7);
			}
			move_uploaded_file($_FILES['photo']['tmp_name'], "photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg");
			resize_photo("photos/".$_COOKIE['2ndw_userid']."/".$code.".jpg", 150, 112, 0, 0, 80);
			$id = update_tabs($_POST['title'], $code, "image", true, "image");
			echo '
			<script type="text/javascript">
			var cb = window.parent.document.getElementById("card_body");
			cb.innerHTML += "<div class=\'draggable\' id=\'div_'.$id['id'].'\' onmousedown=\'StartDrag(event,this,body);\'><a href=\'javascript: editimage(\"'.$id['id'].'\");\' id=\'a_'.$id['id'].'\'><img class=\'border\' style=\''.$id['style'].'\' src=\'photos/'.$_COOKIE['2ndw_userid'].'/'.$code.'.jpg\'></a></div>";
			var ci = window.parent.document.getElementById("div_'.$id['id'].'");
			ci.style.top = window.parent.getOffsetTop(cb)-window.parent.getOffsetTop(ci)+"px";
			ci.style.left = window.parent.getOffsetLeft(cb)-window.parent.getOffsetLeft(ci)+"px";
			window.parent.pageunlock(1);
			window.parent.changepage();
			window.parent.Win.hide();
			</script>';
			exit;
		}
		echo '<script type="text/javascript">
		window.parent.Win.response("'.substr($result, 1).'");
		window.parent.Win.wait(0);
		</script>';
		exit;
	}
	exit;
}
elseif($_GET['act'] == "setdelitem")
{
	$title = $_POST['id'];
	
	$_POST['tab'] = intval($_POST['tab']);
	$_POST['totab'] = intval($_POST['totab']);
	
	$qn = mysql_query("select tabs from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
	$tabs = mysql_result($qn, 0);
	$tabs = json_decode($tabs, true);
	
	if(isset($tabs[$_POST['tab']]['items'][$title]))
	{
		if($_POST['todo'] == "del")
		{
			if(mb_substr($title, 0, 5) == "image") unlink("photos/".$_COOKIE['2ndw_userid']."/".$tabs[$_POST['tab']]['items'][$title]['value'].".jpg");
			elseif($title == "photo")
			{
				$qp = mysql_query("select photo from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
				unlink("photos/".$_COOKIE['2ndw_userid']."/".mysql_result($qp, 0).".jpg");
				unlink("photos/".$_COOKIE['2ndw_userid']."/".mysql_result($qp, 0)."s.jpg");
			}
			
			if($title != "photo" && $title != "name" && $title != "surname")
			{
				unset($tabs[$_POST['tab']]['items'][$title]);
				$tabs[$_POST['tab']]['itemsnum'] = count($tabs[$_POST['tab']]['items']);
			}
		
			$tabs = json_encode($tabs);
			
			if(mb_substr($title, 0, 5) != "other" && mb_substr($title, 0, 3) != "box" && mb_substr($title, 0, 5) != "image") $q = mysql_query('update people set '.$title.' = NULL, tabs = "'.mysql_escape_string($tabs).'" where id = '.$_COOKIE['2ndw_userid'].' limit 1');
			else $q = mysql_query('update people set tabs = "'.mysql_escape_string($tabs).'" where id = '.$_COOKIE['2ndw_userid'].' limit 1');
			print json_encode(array($title, "del"));
		}
		else
		{
			$tabs[$_POST['totab']]['items'][$title] = $tabs[$_POST['tab']]['items'][$title];
			$tabs[$_POST['totab']]['items'][$title]['x'] = 0;
			$tabs[$_POST['totab']]['items'][$title]['y'] = 0;
			unset($tabs[$_POST['tab']]['items'][$title]);
			$tabs[$_POST['tab']]['itemsnum'] = count($tabs[$_POST['tab']]['items']);
			$tabs[$_POST['totab']]['itemsnum'] = count($tabs[$_POST['totab']]['items']);
			
			$tabs = json_encode($tabs);
			
			$q = mysql_query('update people set tabs = "'.mysql_escape_string($tabs).'" where id = '.$_COOKIE['2ndw_userid'].' limit 1');
			print json_encode(array($title, "totab", $_POST['totab']));
		}
	}
}
else exit;

?>