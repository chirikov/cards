<?php
include_once("../inc/my_connect.php");
include_once("inc/control.php");
include_once("../inc/constants.php");
include_once("../inc/functions.php");

$body = "";

$help_page = "profile";

if($_GET['uid'] < 1) $_GET['uid'] = $_COOKIE['mir_id'];
$profile = profile($_GET['uid'], "id,surname,name,online,imgurl,birthdate");

$qgame = mysql_query("select city, job, money, balance from gameinfo where id = ".$_GET['uid']);
$gameinfo = mysql_fetch_assoc($qgame);

$qtimezone = mysql_query("select timezone from cities where id = (select city from users where id = ".$_COOKIE['mir_id'].")");
$timezone = mysql_result($qtimezone, 0);

if($_GET['uid'] == $_COOKIE['mir_id']) $me = true;
else $me = false;

$body .= '<div class="profile"><div class="main_info"><table class="main_info"><tr><td class="photo">';

$divphoto = '<div class="photo">';
if($me)
{
	if($profile['imgurl'] == "") $divphoto .= '<img alt="Нет фотографии" src="../images/noavatar.jpg">';
	else $divphoto .= '<img alt="'.$profile['name'].' '.$profile['surname'].'" src="../'.$profile['imgurl'].'">';
}
else
{
	if($profile['imgurl'] == "") $divphoto .= '<img alt="Нет фотографии" src="../images/noavatar.jpg">';
	else $divphoto .= '<img alt="'.$profile['name'].' '.$profile['surname'].'" src="../'.$profile['imgurl'].'">';
}
$divphoto .= "</div>";

$body .= $divphoto.'</div></div></td><td class="user_info">';

$userinfo = '<div class="user_info"><div class="name">'.$profile['name'].' '.$profile['surname'].'</div>';

if($profile['online'] == 1) $userinfo .= '<div class="status">в сети</div><div class="items">';
else $userinfo .= '<div class="items"><div class="item">Последний визит: <ins>'.rusdate("@ytt@ в @H@:@i@", $profile['online'], $timezone*3600, true)."</ins></div>";

if($gameinfo['city'] != 0)
{
	$qcity2 = mysql_query("select name from cities where id = ".$gameinfo['city']);
	$cityname = mysql_result($qcity2, 0);
	$userinfo .= '<div class="item">Город: <ins><a href="city.php?act=select">'.$cityname.'</a></ins></div>';
}
elseif($me) $userinfo .= '<div class="item">Город: <ins><a href="city.php?act=select">Выбрать город</a></ins></div>';
else $userinfo .= '<div class="item">Город: <ins>-</ins></div>';

if($gameinfo['city'] != 0)
{
	if($gameinfo['job'] != 0)
	{
		$qwork = mysql_query("select name from gos_structures where id = (select object from jobs where id = ".$gameinfo['job'].")");
		$work = mysql_result($qwork, 0);
		$userinfo .= '<div class="item">Место работы: <ins>'.$work.'</ins></div>';
		
		$qcity2 = mysql_query("select name from jobs where id = ".$gameinfo['job']);
		$jobname = mysql_result($qcity2, 0);
		$userinfo .= '<div class="item">Должность: <ins>'.$jobname.'</ins></div>';
	}
	elseif($me) $userinfo .= '<div class="item">Место работы: <ins><a href="job.php?act=search">Найти</a></ins></div>';
	else $userinfo .= '<div class="item">Место работы: <ins>-</ins></div>';
}
$userinfo .= '<div class="item">Деньги: <ins>'.$gameinfo['money'].'</ins></div>';
$userinfo .= '<div class="item">Баланс: <ins>'.($gameinfo['balance'] > 0 ? "+" : "").$gameinfo['balance'].'</ins></div>';

$userinfo .= '</div></div>';

$body .= $userinfo.'</td></tr></table></div>';

$body .= '</div>';

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>