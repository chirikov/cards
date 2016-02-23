<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");
include_once("inc/constants.php");
include_once("inc/functions.php");

function messages_mail($inout="in", $page=1)
{
	$qtimezone = mysql_query("select timezone from cities where id = (select city from users where id = ".$_COOKIE['mir_id'].")");
	$timezone = mysql_result($qtimezone, 0);
	
	if($page < 1) $page = 1;
	
	if($inout == "in")
	{
		$qn = mysql_query("select COUNT(*) from messages where recepient = ".$_COOKIE['mir_id']);
		$tabs = '<a href="mail.php?act=inlist"><i>Входящие</i></a><a href="mail.php?act=outlist" class="small"><i>Отправленные</i></a><a href="mail.php?act=talks" class="small"><i>Беседы</i></a>';
		$col2 = 'Отправитель:';
		$towhom = "От кого:";
		$refresh = '<div class="refresh"><img class="wait" id="waitmes1" alt="" src="images/wait.gif"/><input type="checkbox" id="auto" checked> Автообновление</div>';
		$nomes = "входящих";
	}
	else
	{
		$qn = mysql_query("select COUNT(*) from messages where sender = ".$_COOKIE['mir_id']);
		$tabs = '<a href="mail.php?act=inlist" class="small"><i>Входящие</i></a><a href="mail.php?act=outlist"><i>Отправленные</i></a><a href="mail.php?act=talks" class="small"><i>Беседы</i></a>';
		$col2 = 'Получатель:';
		$towhom = "Кому:";
		$refresh = '';
		$nomes = "исходящих";
	}
	$mesnum = mysql_result($qn, 0);
	if(MESSAGES_PER_PAGE*($page-1) > $mesnum-1) $page = ceil($mesnum/MESSAGES_PER_PAGE);
	
	if($inout == "in") $q1 = mysql_query("select id, sender, text, seen, time from messages where recepient = ".$_COOKIE['mir_id']." order by time desc limit ".MESSAGES_PER_PAGE*($page-1).", ".MESSAGES_PER_PAGE);
	else $q1 = mysql_query("select id, recepient, text, seen, time from messages where sender = ".$_COOKIE['mir_id']." order by time desc limit ".MESSAGES_PER_PAGE*($page-1).", ".MESSAGES_PER_PAGE);
	
	if($inout == "in") $body .= '';
	$body .= '
	<script language="javascript" type="text/javascript" src="inc/mail.js"></script>
	<div class="my_mail">
		<div class="tabs"><div class="summ">'.ucfirst($nomes).' сообщений: <b><span id="summ">'.$mesnum.'</span></b></div>'.$tabs.'</div>
		<div class="my_mail_messages">
			<table class="posts" id="tableposts"><!--[if IE 6]><col style="width:44px"/><col style="width:132px"/><col style="width:423px"/><![endif]-->
			<thead>
				<tr>
					<td>Дата:</td>
					<td>'.$col2.'</td>
					<td>'.$refresh.'Текст:</td>
				</tr>
			</thead><tbody>';
	$lastmesnum = 0;
	if($mesnum == 0)
	{
		$body .= "<tr><td colspan='3' align='center'>У Вас нет ".$nomes." сообщений.</td></tr>";
	}
	else
	{
		$i = 1;
		while($row = mysql_fetch_assoc($q1))
		{
			if($inout == "in")
			{
				$q2 = mysql_query("select name, surname, lasttime from users where id = ".$row['sender']);
				$uid = $row['sender'];
			}
			else
			{
				$q2 = mysql_query("select name, surname, lasttime from users where id = ".$row['recepient']);
				$uid = $row['recepient'];
			}
			$row2 = mysql_fetch_assoc($q2);
			if($row['seen'] == 0) $body .= "<tr id='trs".$i."' style='background-color: #fffebf;'>";
			else $body .= "<tr id='trs".$i."'>";
			if($row2['lasttime'] > time() - ONLINE_MINUTES*60) $sender_online = "<b>";
			else $sender_online = "";
			$body .= "<td>".rusdate("@ytt@ @H@:@i@", $row['time'], $timezone*3600, true)."</td>";
			$body .= "<td><a href='profile.php?uid=".$uid."'>".$sender_online.$row2['name']." ".$row2['surname']."</b></a></td>";
			$body .= "<td>
			<a href='#' onclick='javascript: arrowclick(".$i.", ".$row['id'].");' class='more'><img src='images/arrow2.gif' alt='V'/></a>
			".substr($row['text'], 0, 55); if(strlen($row['text']) > 55) $body .= "...";
			$body .= "
			</td></tr>
			<tr id='trf".$i."' style='display: none;'>
				<td colspan='3' class='message1'>
					<div class='message1'>
						<div class='message_date'>".rusdate("@ytt@ в @H@:@i@", $row['time'], $timezone*3600, true)."</div>
						<a href='#' onclick='javascript: arrowclick(".$i.", ".$row['id'].");' class='more'><img src='images/arrow3.gif' alt='^'/></a>
						<div class='message_text'><table><!--[if IE 6]><col style='width:85px'/><![endif]-->
						<tr>
							<td>".$towhom."</td>
							<td><a href='profile.php?uid=".$uid."'>".$sender_online.$row2['name']." ".$row2['surname']."</b></a></td>
						</tr>
						<tr>
							<td>Сообщение:</td>
							<td>".wordwrap($row['text'], 75, "\n", 1)."</td>
						</tr>
						</table></div>";
						if($inout == "in")
						{
							$body .= "
							<div id='but1".$i."' class='buttons'>
								<a href='#' onclick='javascript: showform(".$i.", ".$uid.");' class='answer'>Ответить</a>
								<a href='#' onclick='javascript: 
								var a = confirm(\"Удалить сообщение?\");
								if(a) delmes(".$i.", ".$row['id'].");
								return false;' class='delete'>Удалить</a>
							</div>
							<div id='divans".$i."' class='answer_text' style='display: none;'>";
							/*
								<table><!--[if IE 6]><col style='width:85px'/><![endif]-->
								<form id='form".$i."' method='get'>
								<input type='hidden' name='recepient' value='".$uid."' style='display: none;'>
								<tr>
									<td><label>Сообщение:</label></td>
									<td><textarea name='text' id='textarea".$i."' rows='3' cols='30' onkeypress='javascript: ctrlenter(event);'></textarea></td>
								</tr>
								</form>
								</table>
								<div class='buttons'>
									<a href='#' onclick='javascript: sendmes(".$i.");' class='answer'>Отправить</a>
									<a href='#' onclick='javascript: showform(".$i.");' class='delete'>Отменить</a>
								</div>
								<div class='cl'>&nbsp;</div>
							*/
							$body .="</div>";
						}
						$body .= "<div class='cl'>&nbsp;</div>
					</div>
				</td>
			</tr>";
			if($i == 1) $lastmesnum = $row['id'];
			$i++;
		}
	}
	$body .= "</tbody></table></div>";
	$body .= '
	<script language="javascript" type="text/javascript">
	<!--//
	lastmesid = '.$lastmesnum.';
	page = "mail";
	subpage = "'.$inout.'";
	//-->
	</script>
	';
	
	$body .= pager($page, $mesnum, MESSAGES_PER_PAGE, "mail.php?act=".$inout."list");
	$body .= "</div></div>";
	return $body;
}

$help_page = "messages";

if(
$_GET['act'] != "inlist" && 
$_GET['act'] != "outlist" && 
$_GET['act'] != "talks" && 
$_GET['act'] != "write" && 
$_GET['act'] != "sendajax" && 
$_GET['act'] != "checkajax" && 
$_GET['act'] != "send" && 
$_GET['act'] != "seen" && 
$_GET['act'] != "delete"
) $_GET['act'] = "inlist";

$body = "";

if($_GET['act'] == "inlist")
{
	$body .= messages_mail("in", $_GET['page']);
}
elseif($_GET['act'] == "outlist")
{
	$body .= messages_mail("out", $_GET['page']);
}
elseif($_GET['act'] == "talks")
{
	if($_GET['page'] < 1) $_GET['page'] = 1;
	$page = $_GET['page'];
	
	$qtimezone = mysql_query("select timezone from cities where id = (select city from users where id = ".$_COOKIE['mir_id'].")");
	$timezone = mysql_result($qtimezone, 0);
	
	if($_GET['uid'] == "")
	{
		$q = mysql_query("select distinct recepient from messages where sender = ".$_COOKIE['mir_id']." order by time asc limit ".MESSAGES_PER_PAGE*($page-1).", ".MESSAGES_PER_PAGE);
		$talksnum = mysql_num_rows($q);
		
		$body .= '
		<div class="my_mail">
		<div class="tabs"><div class="summ">Всего собеседников: <b>'.$talksnum.'</b></div><a href="mail.php?act=inlist" class="small"><i>Входящие</i></a><a href="mail.php?act=outlist" class="small"><i>Отправленные</i></a><a href="mail.php?act=talks"><i>Беседы</i></a></div>
		<div class="my_mail_messages">
			<table class="posts" id="tableposts"><!--[if IE 6]><col style="width:44px"/><col style="width:132px"/><col style="width:423px"/><![endif]-->
			<thead>
				<tr>
					<td>Дата:</td>
					<td>Собеседник:</td>
					<td>Последнее сообщение:</td>
				</tr>
			</thead><tbody>';
		if($talksnum == 0)
		{
			$body .= "<tr><td colspan='3' align='center'>У Вас нет бесед.</td></tr>";
		}
		else
		{
			$i = 1;
			while($row = mysql_fetch_assoc($q))
			{
				$q2 = mysql_query("select name, surname, lasttime from users where id = ".$row['recepient']);
				$uid = $row['recepient'];
				
				$qtext = mysql_query("select sender, text, time from messages where (recepient = ".$row['recepient']." && sender = ".$_COOKIE['mir_id'].") || (recepient = ".$_COOKIE['mir_id']." && sender = ".$row['recepient'].") order by time desc limit 1");
				$text = mysql_result($qtext, 0, 'text');
				$time = mysql_result($qtext, 0, 'time');
				$sender = mysql_result($qtext, 0, 'sender');
				
				$qsend = mysql_query("select name, surname from users where id = ".$sender);
				$rsend = mysql_fetch_assoc($qsend);
				
				$row2 = mysql_fetch_assoc($q2);
				$body .= "<tr id='trs".$i."'>";
				if($row2['lasttime'] > time() - ONLINE_MINUTES*60) $sender_online = "<b>";
				else $sender_online = "";
				$body .= "<td>".rusdate("@ytt@ @H@:@i@", $time, $timezone*3600, true)."</td>";
				$body .= "<td><a href='mail.php?act=talks&uid=".$uid."'>".$sender_online.$row2['name']." ".$row2['surname']."</b></a></td>";
				$body .= "<td><span style='color: #999'>".$rsend['name']." ".$rsend['surname'].":</span> ".substr($text, 0, 55); if(strlen($text) > 55) $body .= "...";
				$body .= "</td></tr>";
				$i++;
			}
		}
		$body .= "</tbody></table></div>";
		
		$body .= pager($page, $talksnum, MESSAGES_PER_PAGE, "mail.php?act=talks")."</div></div>";
	}
	else
	{
		$q = mysql_query("select id,sender,recepient,text,seen,time from messages where (recepient = ".$_GET['uid']." && sender = ".$_COOKIE['mir_id'].") || (recepient = ".$_COOKIE['mir_id']." && sender = ".$_GET['uid'].") order by time desc limit ".MESSAGES_PER_PAGE*($page-1).", ".MESSAGES_PER_PAGE);
		
		if(mysql_num_rows($q) < 1)
		{
			header("Location: mail.php?act=talks");
			exit;
		}
		
		$qnum = mysql_query("select COUNT(*) from messages where (recepient = ".$_GET['uid']." && sender = ".$_COOKIE['mir_id'].") || (recepient = ".$_COOKIE['mir_id']." && sender = ".$_GET['uid'].")");
		
		$body .= '
		<script language="javascript" type="text/javascript" src="inc/mail.js"></script>
		<div class="my_mail">
			<div class="tabs"><div class="summ">Сообщений: <b><span id="summ">'.mysql_result($qnum, 0).'</span></b></div><a href="mail.php?act=inlist" class="small"><i>Входящие</i></a><a href="mail.php?act=outlist" class="small"><i>Отправленные</i></a><a href="mail.php?act=talks"><i>Беседы</i></a></div>
			<div class="my_mail_messages">
				<table class="posts" id="tableposts">
				<thead>
					<tr style="height: 10px">
						<td style="height: 10px></td>
						<td style="height: 10px><div class="refresh"><img class="wait" id="waitmes1" alt="" src="images/wait.gif"/><input type="checkbox" id="auto" checked> Автообновление</div></td>
					</tr>
				</thead><tbody>';
		
		$qmysn = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
		$mysn = mysql_fetch_assoc($qmysn);
		
		$qhissn = mysql_query("select name, surname, lasttime from users where id = ".$_GET['uid']);
		$hissn = mysql_fetch_assoc($qhissn);
		if($hissn['lasttime'] > time() - ONLINE_MINUTES*60) $sender_online = "<b>";
		else $sender_online = "";
		
		$i = 1;
		while($row = mysql_fetch_assoc($q))
		{
			//if($row['seen'] == 0) $bg = "#fffebf";
			//else $bg = "#ffffff";
			
			if($row['sender'] == $_COOKIE['mir_id']) {$sn = $mysn; $sender_online2 = "<b>";}
			else {$sn = $hissn; $sender_online2 = $sender_online;}
			
			$body .= "<tr id='trs".$i."'><td colspan='3' class='message1'>
					<div class='message1' style='border: 1px solid #cacaca; background: #ffffff'>
						<div class='message_date'>".rusdate("@ytt@ в @H@:@i@", $row['time'], $timezone*3600, true)."</div>
						<div class='message_date'><a href='profile.php?uid=".$row['sender']."'>".$sender_online2.$sn['name']." ".$sn['surname']."</b></a>:
						".wordwrap($row['text'], 75, "\n", 1)."</div>";
						if($row['sender'] != $_COOKIE['mir_id']) $body .= "
							<div id='but1".$i."' class='buttons'>
								<a href='#' onclick='javascript: showform(".$i.", ".$_GET['uid'].");' class='answer'>Ответить</a>
								<a href='#' onclick='javascript: 
								var a = confirm(\"Удалить сообщение?\");
								if(a) delmes(".$i.", ".$row['id'].");
								return false;' class='delete'>Удалить</a>
							</div>
							<div id='divans".$i."' class='answer_text' style='display: none;'></div>";
						
						$body .= "<div class='cl'>&nbsp;</div>
					</div>
				</td>
				</tr>";
			if($i == 1) $lastmesnum = $row['id'];
			$i++;
		}
		$body .= "</tbody></table></div>";
		$body .= '
		<script language="javascript" type="text/javascript">
		<!--//
		lastmesid = '.$lastmesnum.';
		page = "mail";
		subpage = "talks";
		mir_id = '.$_COOKIE['mir_id'].';
		myname = "'.$mysn['name'].' '.$mysn['surname'].'";
		//-->
		</script>
		';
		$body .= pager($page, mysql_result($qnum, 0), MESSAGES_PER_PAGE, "mail.php?act=talks&uid=".$_GET['uid'])."</div></div>";
	}
}
elseif($_GET['act'] == "write")
{
	$q0 = mysql_query("select surname, name from users where id = ".$_COOKIE['mir_id']);
	$q = mysql_query("select surname, name from users where id = ".$_GET['recepient']);
	$row0 = mysql_fetch_assoc($q0);
	$row = mysql_fetch_assoc($q);
	$body .= '
	<div class="start">
		<div class="start_form">
			<div class="top_corners"><i>&nbsp;</i></div>
			<h2>Сообщение</h2>
			<div class="start_form_pad">';
	$body .= "<form action='mail.php?act=send' method='post' name='f1'><input type='hidden' name='recepient' value=".$_GET['recepient'].">";
				$body .= '<table class="form_table"><!--[if IE 6]><col style="width:70px"/><col style="width:157px"/><col style="width:100px"/><![endif]-->
					<tr>
						<td><label>Кому:</label></td>
						<td>'.$row['name'].' '.$row['surname'].'</td>
					</tr>
					<tr>
						<td><label>Текст:</label></td>
						<td><textarea rows=10 cols=30 name="text"></textarea></td>
					</tr>
				</table>
				<div class="enter" align=center><input type="submit" name="go" value="Отправить" style="width: 100px; height: 20px"></div></form>
			</div>
			<div class="bottom_corners"><i>&nbsp;</i></div>
		</div>
	</div>';
}
elseif($_GET['act'] == "sendajax")
{
	$text = iconv('UTF-8', 'windows-1251', $_POST['text']);
	if(strlen($text) > 0)
	{
		$q2 = mysql_query("insert into messages (sender, recepient, text, seen, time) values ('".$_COOKIE['mir_id']."', '".$_POST['recepient']."', '".nl2br(htmlspecialchars($text))."', '0', '".time()."')");
	}
	exit;
}
elseif($_GET['act'] == "checkajax")
{
	if($_GET['lastmesid'] == 0) $q1 = mysql_query("select id, sender, text, time from messages where seen = 0 && recepient = ".$_COOKIE['mir_id']." order by time asc");
	else $q1 = mysql_query("select id, sender, text, time from messages where seen = 0 && recepient = ".$_COOKIE['mir_id']." && id > ".$_GET['lastmesid']." order by time asc");
	$ns = mysql_num_rows($q1);
	print $ns;
	$qc = mysql_query("select timezone from cities where id = (select city from users where id = ".$_COOKIE['mir_id'].")");
	$timezone = mysql_result($qc, 0);
	while($row = mysql_fetch_assoc($q1))
	{
		$row['text'] = ereg_replace("џ", "", $row['text']);
		$row['text'] = wordwrap($row['text'], 75, "\n", 1);
		$q9 = mysql_query("select name, surname from users where id = ".$row['sender']);
		$row3 = mysql_fetch_assoc($q9);
		if($_GET['type'] == "mail") print "џ".rusdate("@ytt@ @H@:@i@", $row['time'], $timezone*3600, true)."џ".$row3['name']." ".$row3['surname']."џ".$row['text']."џ".$row['sender']."џ".$row['id'];
		else print "џ".$row3['name']." ".$row3['surname']."џ".$row['text']."џ".$row['sender']."џ".$row['id'];
	}
	exit;
}
elseif($_GET['act'] == "send")
{
	$text = $_POST['text'];
	if(strlen($text) > 0)
	{
		$q2 = mysql_query("insert into messages (sender, recepient, text, seen, time) values ('".$_COOKIE['mir_id']."', '".$_POST['recepient']."', '".nl2br(htmlspecialchars($text))."', '0', '".time()."')");
	}
	header("Location: profile.php");
	exit;
}
elseif($_GET['act'] == "delete")
{
	if($_GET['mid'] < 0)
	{
		header("Location: profile.php");
		exit;
	}
	$q2 = mysql_query("delete from messages where id = ".$_GET['mid']." && recepient = ".$_COOKIE['mir_id']);
	header("Location: mail.php?act=inlist");
	exit;
}
elseif($_GET['act'] == "seen")
{
	if($_GET['mid'] < 0)
	{
		header("Location: profile.php?act=home");
		exit;
	}
	$q1 = mysql_query("select id from messages where id = ".$_GET['mid']." && recepient = ".$_COOKIE['mir_id']);
	if(mysql_num_rows($q1) == 1)
	{
		$q2 = mysql_query("update messages set seen = 1 where id = ".$_GET['mid']);
	}
	else
	{
		header("Location: profile.php?act=home");
		exit;
	}
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>