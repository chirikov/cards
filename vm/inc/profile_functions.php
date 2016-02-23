<?php

function messages_profile()
{
	$q8 = mysql_query("select id, sender, text from messages where recepient = ".$_COOKIE['mir_id']." && seen = 0 order by time desc");
	$mesnum = mysql_num_rows($q8);
	$body .= '
	<div class="new_messages window1">
		<div class="title"><div class="in"><div class="in">
			<div class="refresh"><img class="wait" id="waitmes1" src="images/wait.gif" alt=""/><input type="checkbox" id="auto" checked="checked"/> Автообновление</div>
			Новые сообщения: 
		</div></div></div>
		<div class="posts" id="divmes"><table id="tableposts" class="posts" bgcolor="white"><!--[if IE 6]><col style="width:85px"/><col style="width:503px"/><![endif]-->';
	$lastmesnum = 0;
	$i=1;
	while($row = mysql_fetch_assoc($q8))
	{
		$q9 = mysql_query("select id, surname, name, lasttime from users where id = ".$row['sender']);
		$row3 = mysql_fetch_assoc($q9);
		if($row3['lasttime'] > time() - ONLINE_MINUTES*60) $sender_online = "<b>";
		else $sender_online = "";
		$body .= "
		<tr id='trs".$i."' style='background-color: #fffebf'>
		<td><a href='profile.php?act=view&uid=".$row3['id']."'>".$sender_online.$row3['name']." ".$row3['surname']."</b></a></td>
		<td	onclick='javascript: arrowclick(".$i.", ".$row['id'].");'><a href='#' class='more'><img src='images/arrow2.gif' alt='V'/></a>
		".substr($row['text'], 0, 70);
		if(strlen($row['text']) > 70) $body .= "...";
		$body .= "</td></tr>
		<tr id='trf".$i."' style='display: none;'>
			<td colspan='2' class='message1'>
				<div class='message1'>
					<a href='#' onclick='javascript: arrowclick(".$i.", ".$row['id'].");' class='more'><img src='images/arrow3.gif' alt='^'/></a>
					<div class='message_text'><table><!--[if IE 6]><col style='width:85px'/><![endif]-->
					<tr>
						<td>От кого:</td>
						<td><b><a href='profile.php?act=view&uid=".$row3['id']."'>".$row3['name']." ".$row3['surname']."</a></b></td>
					</tr>
					<tr>
						<td>Сообщение:</td>
						<td>".wordwrap($row['text'], 75, "\n", 1)."</td>
					</tr>
					</table></div>
					<div id='but1".$i."' class='buttons'>
						<a href='#' onclick='javascript: showform(".$i.", ".$row['sender'].");' class='answer'>Ответить</a>
						<a href='#' onclick='javascript: 
						var a = confirm(\"Удалить сообщение?\");
						if(a) delmes(".$i.", ".$row['id'].");
						return false;' class='delete'>Удалить</a>
					</div>
					<div  id='divans".$i."' style='display: none;' class='answer_text'>
					</div>
					<div class='cl'>&nbsp;</div>
				</div>
			</td>
		</tr>
		";
		if($i == 1) $lastmesnum = $row['id'];
		$i++;
	}
	if($i == 1) $body .= "<tr style='display: none;'><td></td></tr>";
	$body .= '</table></div>
	</div>
	<script language="javascript" type="text/javascript">
	<!--//
	lastmesid = '.$lastmesnum.';
	page = "profile";
	//-->
	</script>
	';
	return $body;
}

function newcontacts()
{
	$body = "";
	$newcont = contact_list(0, "id, name, surname, avatar", false, -1, 0);
	$newcontnum = count($newcont);
	if($newcontnum > 0)
	{
		$body .= '<div class="new_messages window1" id="newconttable">
				<div class="title"><div class="in"><div class="in">
					<div class="refresh"><img id="waitcont1" class="wait" src="images/wait.gif" alt=""/></div>
					Вас добавили в контакты: 
				</div></div></div>
				<div class="posts"><table class="posts"><!--[if IE 6]><col style="width:95px"/><col style="width:503px"/><![endif]-->';
		$i = 1;
		foreach($newcont as $contact)
		{
			$body .= "<tr id='trcont".$i."' style='cursor: default'><td align=center><a href='profile.php?uid=".$contact['id']."'>";
			if($contact['avatar'] != "") $body .= "<img border='0' src='photos/".$contact['id']."/".$contact['avatar']."s.jpg'></a></td>";
			else $body .= "<img border='0' src='images/noavatars.jpg'></a></td>";
			$body .= "<td><a href='profile.php?uid=".$contact['id']."'>".$contact['name']." ".$contact['surname']."</a><br><br>
			<a href='#' onclick='javascript: addcontact(".$i.", ".$contact['id'].");'>Тоже добавить</a> <a href='#' onclick='javascript: ignorecontact(".$i.", ".$contact['id'].");'>Не добавлять</a></td></tr>";
			$i++;
		}
		$body .= '</table></div></div>
		<script language="javascript" type="text/javascript">
		<!--//
		newcontnum = '.$newcontnum.';
		//-->
		</script>';
	}
	return $body;
}

function albums($uid)
{
	$body = "";
	$q9 = mysql_query("select id, name from albums where uid = ".$uid." limit 5");
	$i = 0;
	$arr = array();
	while($row4 = mysql_fetch_assoc($q9))
	{
		$q91 = mysql_query("select code from photos where album = ".$row4['id']." limit 1");
		if(mysql_num_rows($q91) > 0)
		{
			$i++;
			$arr[$i] = $row4;
			$arr[$i]['code'] = mysql_result($q91, 0);
		}
	}
	$na = $i;
	if($na > 0)
	{
		$body .= '<div class="new_messages window1" id="photos">
					<div class="title"><div class="in"><div class="in">
						Альбомы пользователя: 
					</div></div></div>
					<div class="profile_albums"><table class="profile_albums"><tr>';
		$i = 1;
		for($i=1; $i<=$na; $i++)
		{
			$body .= "<td style='width: ". (PHOTO_W/PHOTOS_K+10) ."px'><a href='photo.php?act=album&aid=".$arr[$i]['id']."'><img src='photos/".$uid."/".$arr[$i]['id']."/".$arr[$i]['code']."s.jpg'></a><br>
			<a href='photo.php?act=album&aid=".$arr[$i]['id']."'>".$arr[$i]['name']."</a></td>";
		}
		$body .= "</tr></table></div><div class='view_all'><ins><a href='photo.php?act=albums&uid=".$uid."'>Все альбомы</a></ins></div></div>";
	}
	return $body;
}

function dragdiv($id, $content, $layout)
{
	$body = '<div class="draggable" id="'.$id.'" onmousedown="javascript: StartDrag(event, this);"';
	if($layout) $body .= ' style="left: '.$layout[$id.'_x'].'px; top: '.$layout[$id.'_y'].'px"';
	$body .= '>'.$content.'</div>';
	return $body;
}

function user_options($uid)
{
	$body = "";
	$q = mysql_query("select options from info where id = ".$uid);
	$options = mysql_result($q, 0);
	$body .= '<div id="options_div">'.$options.'</div><textarea rows="20" id="options" class="options_write" style="display: none" wrap="virtual"></textarea>';
	return $body;
}

function tagcloud($uid)
{
	$conts = contact_list($uid, "id,surname,name,lasttime");
	$tags = '<tags>';
	foreach($conts as $cont)
	{
		if($cont['lasttime'] > time()-ONLINE_MINUTES*60) $tags .= '<a href=\'profile.php?uid='.$cont['id'].'\' style=\'font-size: 16pt\'>'.$cont['name'].' '.$cont['surname'].'</a>';
		else $tags .= '<a href=\'profile.php?uid='.$cont['id'].'\' style=\'font-size: 10pt\'>'.$cont['name'].' '.$cont['surname'].'</a>';
		
	}
	$tags .= '</tags>';
	
	$body = '
	<script language="javascript" type="text/javascript" src="inc/tagcloud.js"></script>
	<div id="wpcumuluswidgetcontent" style="border: 1px solid #cacaca; width: 145px; height: 145px"><p style="padding: 5px">Для просмотра необходим Flash Player 9.<br><br><a href="http://get.adobe.com/flashplayer/" target="_blank">Установить</a></p></div>
	<script language="javascript" type="text/javascript">
	<!--//
		var widget_so = new SWFObject("swf/tagcloud.swf?r="+Math.floor(Math.random()*9999999), "tagcloudflash", "145", "145", "9", "#ffffff");
		widget_so.addParam("allowScriptAccess", "always");
		widget_so.addVariable("tcolor", "0x808080");
		widget_so.addVariable("tcolor2", "0x000000");
		widget_so.addVariable("hicolor", "0x000000");
		widget_so.addVariable("tspeed", "100");
		widget_so.addVariable("distr", "true");
		widget_so.addVariable("mode", "tags");
		widget_so.addVariable("tagcloud", encodeURIComponent("'.$tags.'"));
		widget_so.write("wpcumuluswidgetcontent");
	//-->
	</script>
	';
	return $body;
}
?>