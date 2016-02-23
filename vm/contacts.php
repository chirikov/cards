<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");
include_once("inc/constants.php");
include_once("inc/functions.php");

$help_page = "contacts";

if(
$_GET['act'] != "default" && 
$_GET['act'] != "add" && 
$_GET['act'] != "ajaxaddcontact" && 
$_GET['act'] != "ajaxignorecontact" && 
$_GET['act'] != "delete"
) $_GET['act'] = "default";

$body = "";

if($_GET['act'] == "default")
{
	if($_GET['uid'] == "") $_GET['uid'] = $_COOKIE['mir_id'];
	if($_GET['uid'] == $_COOKIE['mir_id']) $me = true;
	else $me = false;
	
	$q0 = mysql_query("select surname, name from users where id = ".$_GET['uid']);
	$ns = mysql_fetch_assoc($q0);
	
	if($_GET['page'] < 1) $_GET['page'] = 1;
	
	$row = contact_list($_GET['uid'], "id");
	$cnum = count($row);
	
	$row = contact_list($_GET['uid'], "id, name, surname, avatar, lasttime", false, 0, 2, $_GET['page'], CONTACTS_PER_PAGE);
	
	$body .= '
	<script language="javascript" type="text/javascript" src="inc/windows.js"></script>
	<script language="javascript" type="text/javascript">
	<!--//
	/*
	function writeto()
	{
		darken(1);
	
	var odiv2 = document.createElement("DIV");
	odiv2.innerHTML = "<textarea name=\'text\' id=\'textarea\' rows=\'3\' cols=\'30\'></textarea>";
	odiv2.style.left = "100px";
	document.getElementById("bg").appendChild(odiv2);
	}
	*/
	//-->
	</script>
	<div class="contacts">';
	if($me) $body .= '<div class="contacts_search"><input disabled type="text" value="по контактам"/></div>';
	else $body .= '<div class="path"><a href="profile.php?uid='.$_GET['uid'].'" class="top">'.$ns['name'].' '.$ns['surname'].'</a> » Список контактов</div>';
	$body .= '<div class="contacts_numbers2"><ins><a href="search.php?act=default">Поиск</a> Всего контактов: <b>'.$cnum.'</b></ins></div>
	<div class="contacts_list">';
	if($cnum < 1)
	{
		$body .= "<center><div style='padding: 10px'>Нет контактов.";
		if($me) $body .= " <a href='search.php?act=default'>Найти контакты</a>";
		$body .= "</div></center>";
	}
	else
	{
		$qtimezone = mysql_query("select timezone from cities where id = (select city from users where id = ".$_COOKIE['mir_id'].")");
		$timezone = mysql_result($qtimezone, 0);
		$i = 1;
		foreach($row as $contact)
		{
			$q8 = mysql_query("select id from contacts where (id = '".$_COOKIE['mir_id']."' and cid = '".$contact['id']."') or (id = '".$contact['id']."' and cid = '".$_COOKIE['mir_id']."' and side = 2)");
			$body .= '<div class="item"><table class="item"><tr>
						<td class="contact_photo">
							<a href="profile.php?uid='.$contact['id'].'">';
			if($contact['avatar'] != "") $body .= "<img src='photos/".$contact['id']."/".$contact['avatar']."s.jpg'/>";
			else $body .= "<img src='images/noavatars.jpg'/>";
			$body .= '</a>
						</td>
						<td>
							<div class="name"><a href="profile.php?uid='.$contact['id'].'">'.$contact['name'].' '.$contact['surname'].'</a></div>';
			if($contact['lasttime'] > time()-ONLINE_MINUTES*60) $body .= "<div class='status'>в сети</div>";
			else $body .= "<div class='last_visit'>Последний визит: ".rusdate("@ytt@ в @H@:@i@", $contact['lasttime'], $timezone*3600, true)."</div>";
			$body .= '</td><td class="buttons"><div class="buttons">';
			// mail.php?act=write&recepient='.$contact['id'].'
			if($_COOKIE['mir_id'] != $contact['id']) $body .= '<a href="#" class="to_write" onclick="javascript: //writeto();">Написать</a>';
			if($me) $body .= '<a href="contacts.php?act=delete&cid='.$contact['id'].'" class="to_delete" onclick="javascript:
			var a = confirm(\'Удалить контакт?\');
			if(!a) return false;">&nbsp;</a>';
			elseif(mysql_num_rows($q8)<1 && $_COOKIE['mir_id'] != $contact['id']) $body .= '<a href="contacts.php?act=add&cid='.$contact['id'].'" class="to_add">Добавить</a>';
			$body .= "</div></td>
					</tr></table></div>";
			$i++;
		}
	}
	$body .= "</div>";
	$body .= pager($_GET['page'], $cnum, CONTACTS_PER_PAGE, 'contacts.php?act=default')."</div>";
}
elseif($_GET['act'] == "add")
{
	$q3 = mysql_query("update contacts set side = 2, seen = 1 where cid = '".$_COOKIE['mir_id']."' and id = '".$_GET['cid']."'");
	if(mysql_affected_rows($mysql) == 0)
	{
		$q1 = mysql_query("select id from contacts where id = '".$_COOKIE['mir_id']."' and cid = '".$_GET['cid']."'");
		if(mysql_num_rows($q1)<1)
		{
			$q2 = mysql_query("insert into contacts (id, cid) values ('".$_COOKIE['mir_id']."', '".$_GET['cid']."')");
		}
	}
	header("Location: contacts.php?act=default");
	exit;
}
elseif($_GET['act'] == "delete")
{
	mysql_query("delete from contacts where id = '".$_COOKIE['mir_id']."' and cid = '".$_GET['cid']."'");
	if(mysql_affected_rows($mysql) == 0)
	{
		mysql_query("update contacts set side = 1 where cid = '".$_COOKIE['mir_id']."' and id = '".$_GET['cid']."'");
	}
	header("Location: contacts.php?act=default");
	exit;
}
elseif($_GET['act'] == "ajaxaddcontact")
{
	$q1 = mysql_query("update contacts set side = 2, seen = 1 where id = '".$_GET['cid']."' and cid = '".$_COOKIE['mir_id']."'");
	exit;
}
elseif($_GET['act'] == "ajaxignorecontact")
{
	$q1 = mysql_query("update contacts set seen = 1 where id = '".$_GET['cid']."' and cid = '".$_COOKIE['mir_id']."'");
	exit;
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>