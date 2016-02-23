<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");
include_once("inc/constants.php");
include_once("inc/functions.php");
include_once("inc/profile_functions.php");

$help_page = "profile";

if($_GET['uid'] < 1) $_GET['uid'] = $_COOKIE['mir_id'];
$profile = profile($_GET['uid']);
$qtimezone = mysql_query("select timezone from cities where id = (select city from users where id = ".$_COOKIE['mir_id'].")");
$timezone = mysql_result($qtimezone, 0);
$newcontnum = 0;
if($_GET['uid'] == $_COOKIE['mir_id']) $me = true;
else $me = false;

$body .= '<script language="javascript" type="text/javascript" src="inc/mail.js"></script>
		<script language="javascript" type="text/javascript" src="inc/layout.js"></script>
		<script language="javascript" type="text/javascript" src="inc/windows.js"></script>
		<script language="javascript" type="text/javascript" src="inc/profile.js"></script>';
$body .= '
<script language="javascript" type="text/javascript">
<!--//
';

$qlayout = mysql_query("select layout from info where id = ".$_GET['uid']);
$slayout = mysql_result($qlayout, 0);
if($slayout != "")
{
	$layout = array();
	$elems = explode("@", $slayout);
	foreach($elems as $elem)
	{
		$vals = explode(";", $elem);
		$layout[$vals[0]."_x"] = $vals[1];
		$layout[$vals[0]."_y"] = $vals[2];
		
		$body .= "layout['".$vals[0]."_x'] = ".$vals[1]."; layout['".$vals[0]."_y'] = ".$vals[2].";";
	}
}

$body .= '
//-->
</script>';

$body .= '<div class="profile"><div class="main_info"><table class="main_info"><tr><td class="photo">';

$divphoto = '<div class="photo">';
if($me)
{
	if($profile['imgurl'] == "") $divphoto .= '<a href="photo.php?act=loadavatar" title="Загрузить фотографию"><img alt="Загрузить фотографию" src="images/noavatar.jpg"/></a>';
	else $divphoto .= '<a href="javascript: w_file(\'photo.php?act=loadedavatar\');" title="Сменить фотографию"><img alt="'.$profile['name'].' '.$profile['surname'].'" src="'.$profile['imgurl'].'"/></a>';
}
else
{
	if($profile['imgurl'] == "") $divphoto .= '<img alt="Нет фотографии" src="images/noavatar.jpg"/>';
	else $divphoto .= '<img alt="'.$profile['name'].' '.$profile['surname'].'" src="'.$profile['imgurl'].'"/>';
}
$divphoto .= "</div>";

$body .= dragdiv("av", $divphoto, $layout).'</td><td class="user_info">';

$userinfo = '<div class="user_info"><div class="name">'.$profile['name'].' '.$profile['surname'].'</div>';
if($profile['online'] == 1) $userinfo .= '<div class="status">в сети</div><div class="items">';
else $userinfo .= '<div class="items"><div class="item">Последний визит: <ins>'.rusdate("@ytt@ в @H@:@i@", $profile['online'], $timezone*3600, true)."</ins></div>";
$userinfo .= '<div class="item">Дата рождения: <ins>'.rusdate("@j@ @month_rod@ @Y@ г.", $profile['birthdate']).'</ins></div>';
if($profile['streetname'] == 0) $userinfo .= '<div class="item">Город: <ins>'.$profile['cityname'].'</ins></div>';
else $userinfo .= '<div class="item">Адрес: <ins>г. '.$profile['cityname'].', '.$profile['streetname'].' '.$profile['house'].'</ins></div>';
if($profile['gosnom'] != "0" && $profile['gosnom'] != "-1") $userinfo .= '<div class="item">Госномер: <ins>'.$profile['gosnom'].'</ins></div>';
elseif($profile['gosnom'] != "-1" && $me) $userinfo .= gosnomform();

$userinfo .= user_options($_GET['uid']);
if($me) $userinfo .= '<div class="addopt"><a href="#" class="add" id="options_href" onclick="javascript: editoption();">Редактировать информацию</a><a href="#" id="options_href_cancel" style="display: none" onclick="javascript: canceloption();">Отмена</a></div>';
$userinfo .= '</div></div>';

$body .= dragdiv("ui", $userinfo, $layout).'</td></tr></table></div>';

if($me)
{
	$body .= dragdiv("nm", messages_profile(), $layout);
	$body .= newcontacts();
	$body .= '
	<div class="change_interface">
		<a id="changepagehref" class="change" href="#" onclick="javascript: changepage();">Изменить вид страницы</a>
		<a id="cancelpagehref" style="display: none;" href="#" onclick="javascript: cancelpage();">Отмена</a>
		<a id="defaultpagehref" style="display: none;" href="#" onclick="javascript: defaultpage();">По умолчанию</a>
	</div>
	';
}
else
{
	$body .= "<a href='mail.php?act=write&recepient=".$_GET['uid']."'>Написать сообщение</a><br><br>
	<a href='contacts.php?act=default&uid=".$_GET['uid']."'>Контакты пользователя</a><br><br>";
	$q8 = mysql_query("select id from contacts where (id = '".$_COOKIE['mir_id']."' and cid = '".$_GET['uid']."') or (id = '".$_GET['uid']."' and cid = '".$_COOKIE['mir_id']."' and side = 2)");
	if(mysql_num_rows($q8)<1)
	{
		$body .= " <a href='contacts.php?act=add&cid=".$_GET['uid']."'>Добавить в контакты</a><br>";
	}
	$body .= albums($_GET['uid']);
}

$body .= '</div>';

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
//print strip_tags($body);
?>