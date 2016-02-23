<?php

function contact_list($uid=0, $fields="id,surname,name,lasttime", $online=false, $side=0, $seen=2, $page=1, $howmuch=-1)
{
	if($uid == 0) $uid = $_COOKIE['mir_id'];
	$result = array();
	$i = 0;
	if($side == 1 || $side == 0)
	{
		if($seen == 2) $q1 = mysql_query("select cid from contacts where id = ".$uid);
		else $q1 = mysql_query("select cid from contacts where id = ".$uid." and seen = ".$seen);
		while($row = mysql_fetch_assoc($q1))
		{
			if($online == false) $qu = mysql_query("select ".$fields." from users where id = ".$row['cid']);
			else $qu = mysql_query("select ".$fields." from users where id = ".$row['cid']." and lasttime > ".(time()-ONLINE_MINUTES*60));
			if(mysql_num_rows($qu) > 0)
			{
				$i++;
				$result[$i] = mysql_fetch_assoc($qu);
			}
		}
	}
	if($side == -1)
	{
		if($seen == 2) $q1 = mysql_query("select id from contacts where cid = ".$uid);
		else $q1 = mysql_query("select id from contacts where cid = ".$uid." and seen = ".$seen);
		while($row = mysql_fetch_assoc($q1))
		{
			if($online == false) $qu = mysql_query("select ".$fields." from users where id = ".$row['id']);
			else $qu = mysql_query("select ".$fields." from users where id = ".$row['id']." and lasttime > ".(time()-ONLINE_MINUTES*60));
			if(mysql_num_rows($qu) > 0)
			{
				$i++;
				$result[$i] = mysql_fetch_assoc($qu);
			}
		}
	}
	if($side == 2 || $side == 0)
	{
		if($seen == 2) $q1 = mysql_query("select id from contacts where cid = ".$uid." and side = 2");
		else $q1 = mysql_query("select id from contacts where cid = ".$uid." and side = 2 and seen = ".$seen);
		while($row = mysql_fetch_assoc($q1))
		{
			if($online == false) $qu = mysql_query("select ".$fields." from users where id = ".$row['id']);
			else $qu = mysql_query("select ".$fields." from users where id = ".$row['id']." and lasttime > ".(time()-ONLINE_MINUTES*60));
			if(mysql_num_rows($qu) > 0)
			{
				$i++;
				$result[$i] = mysql_fetch_assoc($qu);
			}
		}
	}
	if($howmuch == -1) return $result;
	else
	{
		$res2 = array_chunk($result, $howmuch);
		return $res2[$page-1];
	}
}

function profile($uid=0, $fields="id,surname,name,online,imgurl,birthdate,cityname,streetname,house,gosnom")
{
	if($uid == 0) $uid = $_COOKIE['mir_id'];
	$result = array();
	$fields = explode(",", $fields);
	$qusers = "";
	foreach($fields as $field)
	{
		if(strpos("@id@surname@name@email@state@city@birthdate@sex@avatar@actcode@lasttime@regtime@", "@".$field."@") !== false) $qusers .= $field.", ";
		elseif($field == "online") $qusers .= "lasttime, ";
		elseif($field == "cityname") $qusers .= "city, ";
		elseif($field == "imgurl") $qusers .= "avatar, ";
	}
	if($qusers != "")
	{
		$qusers = mysql_query("select ".substr($qusers, 0, -2)." from users where id = ".$uid);
		$rusers = mysql_fetch_assoc($qusers);
		foreach($fields as $field)
		{
			if(isset($rusers[$field])) $result[$field] = $rusers[$field];
			elseif($field == "online")
			{
				if($rusers['lasttime'] > time()-ONLINE_MINUTES*60) $result[$field] = "1";
				else $result[$field] = $rusers['lasttime'];
			}
			elseif($field == "cityname")
			{
				$qcity = mysql_query("select name from cities where id = ".$rusers['city']);
				$result[$field] = mysql_result($qcity, 0);
			}
			elseif($field == "imgurl")
			{
				if($rusers['avatar'] != "") $result[$field] = "photos/".$uid."/".$rusers['avatar'].".jpg";
				else $result[$field] = "";
			}
		}
	}
	$qinfo = "";
	foreach($fields as $field)
	{
		if(strpos("@id@street@house@vkid@position@gosnom@", "@".$field."@") !== false) $qinfo .= $field.", ";
		elseif($field == "streetname") $qinfo .= "street, ";
	}
	if($qinfo != "")
	{
		$qinfo = mysql_query("select ".substr($qinfo, 0, -2)." from info where id = ".$uid);
		$rinfo = mysql_fetch_assoc($qinfo);
		foreach($fields as $field)
		{
			if(isset($rinfo[$field])) $result[$field] = $rinfo[$field];
			elseif($field == "streetname")
			{
				if($rinfo['street'] != 0)
				{
					$qstreet = mysql_query("select type, name from streets where id = ".$rinfo['street']);
					$result[$field] = mysql_result($qstreet, 0, 'type')." ".mysql_result($qstreet, 0, 'name');
				}
				else $result[$field] = 0;
			}
		}
	}
	return $result;
}

function rusdate($format="@j@&nbsp;@month_rod@,&nbsp;@H@:@i@", $time=0, $delta=0, $ytt=false)
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
			$y = date("j", $time)."&nbsp;".$months_rod[date("n", $time)-1];
		}
	}
	else $y = date("j", $time)."&nbsp;".$months_rod[date("n", $time)-1];
	
	$str = $format;
	$str = str_replace("@j@", date("j", $time), $str);
	$str = str_replace("@Y@", date("Y", $time), $str);
	$str = str_replace("@month_rod@", $months_rod[date("n", $time)-1], $str);
	$str = str_replace("@ytt@", $y, $str);
	$str = str_replace("@H@", date("H", $time), $str);
	$str = str_replace("@i@", date("i", $time), $str);
		
	return $str;
}

function gosnomform()
{
	$retv .= '<div class="item">Госномер: <ins>
	<form action="settings.php?act=setgosnom" method="post" name="fgosnom">
	<select name="let1">
	<option value="A">A
	<option value="B">B
	<option value="C">C
	<option value="E">E
	<option value="H">H
	<option value="K">K
	<option value="M">M
	<option value="O">O
	<option value="P">P
	<option value="T">T
	<option value="X">X
	<option value="Y">Y
	</select><input type="text" name="num" maxlength="3" size="3">
	<select name="let2">
	<option value="A">A
	<option value="B">B
	<option value="C">C
	<option value="E">E
	<option value="H">H
	<option value="K">K
	<option value="M">M
	<option value="O">O
	<option value="P">P
	<option value="T">T
	<option value="X">X
	<option value="Y">Y
	</select>
	<select name="let3">
	<option value="A">A
	<option value="B">B
	<option value="C">C
	<option value="E">E
	<option value="H">H
	<option value="K">K
	<option value="M">M
	<option value="O">O
	<option value="P">P
	<option value="T">T
	<option value="X">X
	<option value="Y">Y
	</select> <input type="text" name="region" maxlength="3" size="3"><input type="submit" name="go" value="OK"></form> <a href="settings.php?act=setgosnom&nocar=1">Отказаться от ввода</a></ins></div>';
	return $retv;
}

function is_valid($type="email", $val)
{
	switch($type)
	{
		case "email":
		if(!ereg("[a-zA-Z0-9]{1,20}@[a-zA-Z0-9]{2,20}.[com,ru,net,org,biz,info,by,ua]", $val)) return false;
		else return true;
		break;
		case "pass":
		if(!ereg("[a-zA-Z0-9]{6,50}", $val)) return false;
		else return true;
		break;
	}
}

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

function pager($page, $all, $perpage, $href)
{
	if($all > $perpage)
	{
		if($all > $perpage) 
		{
			$body = "<div class='pager'><table><tr>";
			
			$pagenum = ceil($all/$perpage);
			
			if($page > 1) $style = "";
			else $style = "style='visibility: hidden;' ";
			
			if($page < $pagenum) $style2 = "";
			else $style2 = "style='visibility: hidden;' ";
			
			$body .= "<td><a ".$style."href='".$href."&page=".($page-1)."' class='arrow1'>&nbsp;</a></td>";
			
			if($page > 5)
			{
				$end = ($pagenum >= $page + 5)?$page+5:$pagenum;
				$start = $end-9;
				$body .= "<td><a href='".$href."&page=".(($page-10>0)?$page-10:1)."'>...</a></td>";
			}
			else
			{
				$end = ($pagenum>10)?10:$pagenum;
				$start = 1;
			}
			for($i=$start; $i<=$end; $i++)
			{
				if($i == $page) $body .= "<td><b>".$i."</b></td>";
				else $body .= "<td><a href='".$href."&page=".$i."'>".$i."</a></td>";
			}
			if($pagenum > $end) $body .= "<td><a href='".$href."&page=".(($page+10>$pagenum)?$pagenum:$page+10)."'>...</a></td>";
			
			$body .= "<td><a ".$style2."href='".$href."&page=".($page+1)."' class='arrow2'>&nbsp;</a></td>";
			$body .= "</tr></table></div>";
			
			return $body;
		}
	}
}

?>