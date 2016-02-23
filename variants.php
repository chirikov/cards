<?php
header("Content-type: text/html; charset=utf-8");
include_once("inc/my_connect.php");
include_once("inc/control.php");

if($_GET['act'] == "givecities")
{
	$text = iconv('UTF-8', 'windows-1251', $_POST['text']);
	if(strlen($text) < 3) $limit = 10;
	else $limit = 9999;
	$q = mysql_query("select name, socr, code from kladr where lower(name) regexp '^".strtolower($text)."' and code like '%00' and (code not like '_____000000__' or socr = '".iconv("UTF-8", "windows-1251", "г")."') limit ".$limit);
	$result = array();
	while($city = mysql_fetch_assoc($q))
	{
		$raion = "";
		$q2 = mysql_query("select name, socr from kladr where code = '".substr($city['code'], 0, 2)."00000000000' limit 1");
		$region = mysql_fetch_assoc($q2);
		if($region['socr'] == iconv("UTF-8", "windows-1251", "Респ") || $region['socr'] == iconv("UTF-8", "windows-1251", "г")) $region = $region['socr'].". ".$region['name'];
		else $region = $region['name']." ".$region['socr'];
		if($city['socr'] != iconv("UTF-8", "windows-1251", "г"))
		{
			if(substr($city['code'], 2, 3) == "000")
			{
				$q4 = mysql_query("select name from kladr where code = '".substr($city['code'], 0, 8)."00000' limit 1");
				$raion = iconv("UTF-8", "windows-1251", "г. ").mysql_result($q4, 0);
			}
			else
			{
				$q4 = mysql_query("select name from kladr where code = '".substr($city['code'], 0, 5)."00000000' limit 1");
				$raion = mysql_result($q4, 0).iconv("UTF-8", "windows-1251", " р-н");
			}
		}
		$q3 = mysql_query("select socrname from socrbase where scname = '".$city['socr']."' limit 1");
		$city_type = mysql_result($q3, 0);
		
		$result[] = array("value" => $city['code'], "main" => iconv('windows-1251', 'UTF-8', eregi_replace("^".$text, "<b>\\0</b>", $city['name'])), "sub" => iconv('windows-1251', 'UTF-8', $city_type."<br>".$region."<br>".$raion), "realtext" => iconv('windows-1251', 'UTF-8', $city['name']));
		//print iconv('windows-1251', 'UTF-8', ":".$city['code']."|".eregi_replace("^".$text, "<b>\\0</b>", $city['name'])."|".$city_type."<br>".$region."<br>".$raion."|".$city['name']);
	}
	print json_encode($result);
	exit;
}
elseif($_GET['act'] == "givestreets")
{
	$text = iconv('UTF-8', 'windows-1251', $_POST['text']);
	if(strlen($text) < 3) $limit = 10;
	else $limit = 9999;
	$q = mysql_query("select name, socr, code from streets where lower(name) regexp '".strtolower($text)."' and code like '".substr($_GET['city'], 0, 11)."%00' limit ".$limit);
	$result = array();
	while($street = mysql_fetch_assoc($q))
	{
		$q3 = mysql_query("select socrname from socrbase where scname = '".$street['socr']."' limit 1");
		$street_type = mysql_result($q3, 0);
		//print iconv('windows-1251', 'UTF-8', ":".$street['code']."|".eregi_replace($text, "<b>\\0</b>", $street['name'])."|".$street_type."|".$street['name']);
		$result[] = array("value" => $street['code'], "main" => iconv('windows-1251', 'UTF-8', eregi_replace($text, "<b>\\0</b>", $street['name'])), "sub" => iconv('windows-1251', 'UTF-8', $street_type), "realtext" => iconv('windows-1251', 'UTF-8', $street['name']));
	}
	print json_encode($result);
	exit;
}
else exit;

?>