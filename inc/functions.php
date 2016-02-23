<?php

function rusdate($format="@j@ @month_rod@, @H@:@i@", $time=0, $delta=0, $ytt=false)
{
	if($time == 0) $time = time();
	$time += $delta;
	$months_rod = array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
	
	if(date("n", $time) == date("n", time()+$delta) && date("Y", $time) == date("Y", time()+$delta) && $ytt)
	{
		if(date("d", $time) == date("d", time()+$delta)) $y = "Сегодня";
		elseif(date("d", $time) == date("d", time()+$delta) - 1) $y = "Вчера";
		elseif(date("d", $time) == date("d", time()+$delta) + 1) $y = "Завтра";
		else
		{
			$y = date("j", $time)." ".$months_rod[date("n", $time)-1];
		}
	}
	else $y = date("j", $time)." ".$months_rod[date("n", $time)-1];
	
	$str = $format;
	$str = str_replace("@j@", date("j", $time), $str);
	$str = str_replace("@Y@", date("Y", $time), $str);
	$str = str_replace("@month_rod@", $months_rod[date("n", $time)-1], $str);
	$str = str_replace("@ytt@", $y, $str);
	$str = str_replace("@H@", date("H", $time), $str);
	$str = str_replace("@i@", date("i", $time), $str);
	
	return $str;
}

function in_array2($need, $arr)
{
	foreach($arr as $el)
	{
		if($el === $need) return $arr;
		elseif(is_array($el)) if($ret = in_array2($need, $el)) return $ret;
	}
	return false;
}

function is_valid($type="email", $val, $len="1,")
{
	$ens = "a-z";
	$enb = "A-Z";
	$en = $ens.$enb;
	$rus = "абвгдеёжзийклмнопрстуфхцчшщъыьэюя";
	$rub = "АБВГДЕЁЖЗКИЙЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";
	$ru = $rus.$rub;
	$num = "0-9";
	$punct = ".,_\-";
	$surname = $en.$ru."-";
	$tel = $num."+ \-\(\)";
	
	$atype = array("ens", "enb", "en", "rus", "rub", "ru", "num", "punct", "surname", "tel");
	
	if($type == "email")
	{
		if(!ereg("[a-zA-Z0-9.,_\-]{1,20}@[a-zA-Z0-9]{2,20}.[a-zA-Z]{2,4}", $val)) return false;
		else return true;
	}
	else
	{
		$types = explode(",", $type);
		$q = "";
		foreach($types as $t)
		{
			if(in_array($t, $atype)) $q .= $$t;
			else $q .= $t;
		}
		if(!mb_ereg("^[".$q."]{".$len."}$", $val)) return false;
		else return true;
	}
	/*
	switch($type)
	{
		case "email":
			if(!ereg("[a-zA-Z0-9]{1,20}@[a-zA-Z0-9]{2,20}.[a-zA-Z]{2,4}", $val)) return false;
			else return true;
		break;
		case "name":
			if(!mb_ereg("^[a-zA-ZАБВГДЕЁЖЗКИЙЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя-]{".$len."}$", $val)) return false;
			else return true;
		break;
		case "num":
			if(!ereg("^[0-9]{".$len."}$", $val)) return false;
			else return true;
		break;
		case "alpha":
			if(!mb_ereg("^[a-zA-ZАБВГДЕЁЖЗКИЙЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя]{".$len."}$", $val)) return false;
			else return true;
		break;
		case "enalpha":
			if(!ereg("^[a-zA-Z]{".$len."}$", $val)) return false;
			else return true;
		break;
		case "rusalpha":
			if(!mb_ereg("^[АБВГДЕЁЖЗКИЙЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя]{".$len."}$", $val)) return false;
			else return true;
		break;
		case "alnum":
			if(!mb_ereg("^[0-9a-zA-ZАБВГДЕЁЖЗКИЙЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя]{".$len."}$", $val)) return false;
			else return true;
		break;
	}
	*/
}
/*
function getlist($query, $type="select", $selected=0)
{
	$body = "";
	if($type == "select")
	{
		while($row = mysql_fetch_assoc($query))
		{
			$name = substr($row['name'], 0, 40);
			if(strlen($row['name']) > 40) $name .= "...";
			if($selected == $row['id']) $body .= "<option value='".$row['id']."' selected>".$name;
			else $body .= "<option value='".$row['id']."'>".$name;
		}
		return $body;
	}
	elseif($type == "ajax")
	{
		while($row = mysql_fetch_assoc($query))
		{
			$name = substr($row['name'], 0, 40);
			if(strlen($row['name']) > 40) $name .= "...";
			$body .= $row['id']."|".$name."@";
		}
		return substr($body, 0, -1);
	}
}
*/
function card_pager($page, $all, $perpage, $href)
{
	if($all > $perpage)
	{
		$body = "Страницы: ";
		
		$pagenum = ceil($all/$perpage);
		
		if($page > 1) $body .= '<span class="tab"><a class="arrow" href="'.$href.'&page='.($page-1).'">←</a></span>';
		
		if($page > 5)
		{
			$end = ($pagenum >= $page + 5)?$page+5:$pagenum;
			$start = $end-9;
			$body .= '<span class="tab wa"><a href="'.$href.'&page='.(($page-10>0)?$page-10:1).'">...</a></span>';
		}
		else
		{
			$end = ($pagenum>10)?10:$pagenum;
			$start = 1;
		}
		for($i=$start; $i<=$end; $i++)
		{
			if($i == $page) $body .= '<span class="tab current wa"><b>'.$i.'</b></span>';
			else $body .= '<span class="tab wa"><a href="'.$href.'&page='.$i.'">'.$i.'</a></span>';
		}
		if($pagenum > $end) $body .= '<span class="tab wa"><a href="'.$href.'&page='.(($page+10>$pagenum)?$pagenum:$page+10).'">...</a></span>';
		
		if($page < $pagenum) $body .= '<span class="tab"><a class="arrow" href="'.$href.'&page='.($page+1).'">→</a></span>';
		
		return $body;
	}
}

function resize_photo($file, $nh, $nw, $nhs, $nws, $quality=false, $qualitys=false)
{
	$file2 = ereg_replace(".jpg$", "s.jpg", $file);
	$sizes = getimagesize($file);
	$hr = $sizes[1]/$nh;
	$wr = $sizes[0]/$nw;
	if($wr >= $hr)
	{
		$w = $nw;
		$h = $sizes[1]/$wr;
		$ws = $nws;
		$hs = $sizes[1]*$nws/$sizes[0];
	}
	else
	{
		$h = $nh;
		$w = $sizes[0]/$hr;
		$hs = $nhs;
		$ws = $sizes[0]*$nhs/$sizes[1];
	}
	$im = imagecreatefromjpeg($file);
	$im2 = imagecreatetruecolor($w, $h);
	imagecopyresampled($im2, $im, 0, 0, 0, 0, $w, $h, $sizes[0], $sizes[1]);
	if($quality === false) imagejpeg($im2, $file);
	else imagejpeg($im2, $file, $quality);
	imagedestroy($im2);
	if($ws != 0 && $hs != 0)
	{
		$im3 = imagecreatetruecolor($ws, $hs);
		imagecopyresampled($im3, $im, 0, 0, 0, 0, $ws, $hs, $sizes[0], $sizes[1]);
		if($qualitys === false) imagejpeg($im3, $file2);
		else imagejpeg($im3, $file2, $qualitys);
		imagedestroy($im3);
	}
	imagedestroy($im);
}

function check_image_file($file)
{
	if($file['size'] == 0) return "EФайл не выбран";
	elseif($file['type'] != "image/jpeg" && $file['type'] != "image/pjpeg" || !getimagesize($file['tmp_name'])) return "EФотография должна быть в формате JPEG";
	elseif($file['size'] > 8000000) return "Eфотография не должна быть больше 8 Мб";
	else return true;
}

function format_address($id, $dbtf="people.home", $duplicatecity=false)
{
	$dbtf = explode(".", $dbtf);
	$man = array();
	if(!$duplicatecity) $adsel = "city, ";
	else {$adsel = ""; $man['city'] == "";}
	$q1 = mysql_query("select ".$adsel.$dbtf[1]." from ".$dbtf[0]." where id = ".$id." limit 1");
	$man = mysql_fetch_assoc($q1);
	$home = explode("#", $man[$dbtf[1]]);
	$body = "";
	if($home[0] != "" && ($man['city'] == $home[0] && ($duplicatecity || $home[1] == "") || $man['city'] != $home[0]))
	{
		$qc = mysql_query("select name, socr from kladr where code = ".$home[0]." limit 1");
		$city = mysql_fetch_assoc($qc);
		if(substr($home[0], 2) == "00000000000") $region = "";
		else
		{
			$q2 = mysql_query("select name, socr from kladr where code = '".substr($home[0], 0, 2)."00000000000' limit 1");
			$region = mysql_fetch_assoc($q2);
			if($region['socr'] == iconv("UTF-8", "windows-1251", "Респ") || $region['socr'] == iconv("UTF-8", "windows-1251", "г")) $region = $region['socr'].". ".$region['name'];
			elseif($region['socr'] == iconv("UTF-8", "windows-1251", "обл")) $region = $region['name']." ".$region['socr'].".";
			else $region = $region['name']." ".$region['socr'];
		}
		if($city['socr'] == iconv("UTF-8", "windows-1251", "г")) $body .= $city['name'].($region!=""?" (".$region.")":"");
		else
		{
			if(substr($home[0], 2, 3) == "000")
			{
				$q4 = mysql_query("select name from kladr where code = '".substr($home[0], 0, 8)."00000' limit 1");
				$raion = iconv("UTF-8", "windows-1251", "г. ").mysql_result($q4, 0);
			}
			else
			{
				$q4 = mysql_query("select name from kladr where code = '".substr($home[0], 0, 5)."00000000' limit 1");
				$raion = mysql_result($q4, 0).iconv("UTF-8", "windows-1251", " р-н");
			}
			$body .= $city['socr']." ".$city['name']." (".$raion.", ".$region.")";
		}
		if($home[1] != "") $body .= ", ";
	}
	if($home[1] != "")
	{
		$qc = mysql_query("select name, socr from streets where code = ".$home[1]." limit 1");
		$city = mysql_fetch_assoc($qc);
		$body .= $city['socr']." ".$city['name'];
		if($home[2] != "") $body .= " ".iconv("UTF-8", "windows-1251", $home[2]);
	}
	return iconv("windows-1251", "UTF-8", $body);
}

?>