<?php
header("Content-type: text/html; charset=utf-8");
include_once("inc/my_connect.php");
include_once("inc/control.php");
//include_once("inc/constants.php");
include_once("inc/functions.php");

define("MESSAGES_PER_PAGE", 20);

function messages_mail($inout="in", $page=1)
{
	//$qtimezone = mysql_query("select timezone from cities where id = (select city from users where id = ".$_COOKIE['2ndw_userid'].")");
	//$timezone = mysql_result($qtimezone, 0);
	
	$page = intval($page);
	if($page < 1) $page = 1;
	
	if($inout == "in")
	{
		$qn = mysql_query("select max(id) as maxid, COUNT(*) as num from messages where recepient = ".$_COOKIE['2ndw_userid']);
		$tabs = '<span class="tab current">Входящие</span><span class="tab"><a href="mail.php?act=outlist">Исходящие</a></span><span class="tab"><a href="mail.php?act=talks">Беседы</a></span>';
		$col2 = 'Отправитель:';
		$towhom = "От кого:";
		$refresh = '<input type="checkbox" class="refresh" id="auto" checked>Автообновление';
		$nomes = "входящих";
	}
	else
	{
		$qn = mysql_query("select max(id) as maxid, COUNT(*) as num from messages where sender = ".$_COOKIE['2ndw_userid']);
		$tabs = '<span class="tab"><a href="mail.php?act=inlist">Входящие</a></span><span class="tab current">Исходящие</span><span class="tab"><a href="mail.php?act=talks">Беседы</a></span>';
		$col2 = 'Получатель:';
		$towhom = "Кому:";
		$refresh = '';
		$nomes = "исходящих";
	}
	$mesnum = mysql_result($qn, 0, 'num');
	$lastmesnum = mysql_result($qn, 0, 'maxid');
		
	if(MESSAGES_PER_PAGE*($page-1) > $mesnum-1) $page = ceil($mesnum/MESSAGES_PER_PAGE);
	
	if($inout == "in") $q1 = mysql_query("select id, sender, text, seen, time from messages where recepient = ".$_COOKIE['2ndw_userid']." order by time desc limit ".MESSAGES_PER_PAGE*($page-1).", ".MESSAGES_PER_PAGE);
	else $q1 = mysql_query("select id, recepient, text, seen, time from messages where sender = ".$_COOKIE['2ndw_userid']." order by time desc limit ".MESSAGES_PER_PAGE*($page-1).", ".MESSAGES_PER_PAGE);
	
	$body = '
	<script language="javascript" type="text/javascript" src="inc/mail.js"></script>
	<div class="card" style="width: 890px; margin: 7px auto">
		<div class="title">
			<div class="left">'.$tabs.'</div>
			<div class="right" style="width: 164px"><div class="wait" id="wait"></div>'.$refresh.'</div>
			Ваши сообщения
		</div>
		<div class="body" style="height: auto">
			<div class="my_mail">

					<table class="posts" id="tableposts"><!--[if IE 6]><col style="width:120px"/><col style="width:170px"/><col style="width:540px"/><![endif]-->';
	if($mesnum == 0)
	{
		$body .= "<tbody><tr><td align='center' valign='middle'>У Вас нет ".$nomes." сообщений.</td></tr>";
	}
	else
	{
		$body .= '<thead><tr><td>Дата:</td><td>'.$col2.'</td><td>Текст:</td></tr></thead><tbody>';
		$i = 1;
		while($row = mysql_fetch_assoc($q1))
		{
			if($inout == "in")
			{
				$q2 = mysql_query("select name, surname, lasttime from people where id = ".$row['sender']." limit 1");
				$uid = $row['sender'];
			}
			else
			{
				$q2 = mysql_query("select name, surname, lasttime from people where id = ".$row['recepient']." limit 1");
				$uid = $row['recepient'];
			}
			$row2 = mysql_fetch_assoc($q2);
			if($row['seen'] == 0) $body .= "<tr id='trs".$i."' style='background-color: #fffebf;'>";
			else $body .= "<tr id='trs".$i."'>";
			if($row2['lasttime'] > time() - 5*60) $sender_online = "<b>";
			else $sender_online = "";
			
			$nlp = strpos($row['text'], "<br />");
			if($nlp < 60 && $nlp !== false) $preview = mb_substr($row['text'], 0, $nlp)."...";
			else
			{
				$preview = mb_substr($row['text'], 0, 60);
				if(mb_strlen($row['text']) > 60) $preview .= "...";
			}
			
			$body .= "<td>".rusdate("@ytt@ в @H@:@i@", $row['time'], $timezone*3600, true)."</td>";
			$body .= "<td><a href='card.php?id=".$uid."'>".$sender_online.$row2['name']." ".$row2['surname']."</b></a></td>";
			$body .= "<td>
			<a href='#' onclick='javascript: arrowclick(".$i."); return false;' class='more'><img src='images/arrow2.gif' alt='V'/></a>
			".$preview."
			</td></tr>
			<tr id='trf".$i."' style='display: none'>
				<td colspan='3' class='message1'>
					<div class='message1'>
						<div class='message_date'>".rusdate("@ytt@ в @H@:@i@", $row['time'], $timezone*3600, true)."</div>
						<a href='#' onclick='javascript: arrowclick(".$i."); return false;' class='more'><img src='images/arrow3.gif' alt='^'/></a>
						<div class='message_text'><table><!--[if IE 6]><col style='width:80px'/><![endif]-->
						<tr>
							<td>".$towhom."</td>
							<td><a href='card.php?id=".$uid."'>".$sender_online.$row2['name']." ".$row2['surname']."</b></a></td>
						</tr>
						<tr>
							<td>Сообщение:</td>
							<td>".wordwrap($row['text'], 60, "\n", 1)."</td>
						</tr>
						</table></div>";
						if($inout == "in")
						{
							$body .= "
							<div id='but1".$i."' class='buttons'>
								<a href='#' onclick='javascript: showform(".$i."); return false;' class='answer'>Ответить</a>
								<a href='#' onclick='javascript: 
								var a = confirm(\"Удалить сообщение?\");
								if(a) delmes(".$i.");
								return false;' class='delete'>Удалить</a>
							</div>
							<div id='divans".$i."' class='answer_text' style='display: none;'></div>";
						}
						$body .= "<div class='cl'>&nbsp;</div>
					</div>
				</td>
			</tr>
			<script language='javascript' type='text/javascript'>
			<!--//
			messages[".$i."] = new Array(4);
			messages[".$i."]['id'] = ".$row['id'].";
			messages[".$i."]['uid'] = ".$uid.";
			messages[".$i."]['seen'] = ".$row['seen'].";
			//-->
			</script>
			";
			$i++;
		}
	}
	$body .= '</tbody></table></div>
					</div>
			<div class="title">
				<div class="left" style="padding-left: 3px">Всего сообщений: <span id="allmesnum">'.$mesnum.'</span></div>
				<div class="right">'.card_pager($page, $mesnum, MESSAGES_PER_PAGE, "mail.php?act=".$inout."list").'</div>
			</div>
		</div>';
	$body .= '
	<script language="javascript" type="text/javascript">
	<!--//
	lastmesid = '.$lastmesnum.';
	page = "mail";
	subpage = "'.$inout.'";
	//-->
	</script>
	';
	
	return $body;
}

$help_page = "messages";

if(
$_GET['act'] != "inlist" && 
$_GET['act'] != "outlist" && 
$_GET['act'] != "talks" && 
$_GET['act'] != "sendajax" && 
$_GET['act'] != "checkajax" && 
$_GET['act'] != "seen" && 
$_GET['act'] != "delete"
) $_GET['act'] = "inlist";

$body = "";

if($_GET['act'] == "inlist")
{
	$page_title = "Входящие сообщения";
	$body .= messages_mail("in", $_GET['page']);
}
elseif($_GET['act'] == "outlist")
{
	$page_title = "Отправленные сообщения";
	$body .= messages_mail("out", $_GET['page']);
}
elseif($_GET['act'] == "talks")
{
	$_GET['page'] = intval($_GET['page']);
	$_GET['uid'] = intval($_GET['uid']);
	
	if($_GET['page'] < 1) $_GET['page'] = 1;
	$page = $_GET['page'];
	
	//$qtimezone = mysql_query("select timezone from cities where id = (select city from users where id = ".$_COOKIE['2ndw_userid'].")");
	//$timezone = mysql_result($qtimezone, 0);
	
	if($_GET['uid'] == 0)
	{
		$q = mysql_query("select distinct recepient from messages where sender = ".$_COOKIE['2ndw_userid']." order by time asc limit ".MESSAGES_PER_PAGE*($page-1).", ".MESSAGES_PER_PAGE);
		$talksnum = mysql_num_rows($q);
		
		$body .= '
		<script language="javascript" type="text/javascript" src="inc/mail.js"></script>
		<div class="card" style="width: 890px; margin: 7px auto">
			<div class="title">
				<div class="left"><span class="tab"><a href="mail.php?act=inlist">Входящие</a></span><span class="tab"><a href="mail.php?act=outlist">Исходящие</a></span><span class="tab current">Беседы</span></div>
				<div class="right"></div>
				Ваши сообщения
			</div>
			<div class="body" style="height: auto">
				<div class="my_mail">
						<table class="posts" id="tableposts"><!--[if IE 6]><col style="width:120px"/><col style="width:170px"/><col style="width:540px"/><![endif]-->';
		if($talksnum == 0)
		{
			$body .= "<tbody><tr><td align='center' valign='middle'>У Вас нет бесед.</td></tr>";
		}
		else
		{
			$body .= '<thead><tr><td>Дата:</td><td>Собеседник:</td><td>Последнее сообщение:</td></tr></thead><tbody>';
			
			$qmysn = mysql_query("select name, surname from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
			$mysn = mysql_fetch_assoc($qmysn);
			
			$i = 1;
			while($row = mysql_fetch_assoc($q))
			{
				$q2 = mysql_query("select name, surname, lasttime from people where id = ".$row['recepient']." limit 1");
				$row2 = mysql_fetch_assoc($q2);
				
				$qtext = mysql_query("select sender, text, time from messages where (recepient = ".$row['recepient']." && sender = ".$_COOKIE['2ndw_userid'].") || (recepient = ".$_COOKIE['2ndw_userid']." && sender = ".$row['recepient'].") order by time desc limit 1");
				$last = mysql_fetch_assoc($qtext);
				
				if($last['sender'] == $row['recepient']) $rsend = $row2;
				else $rsend = $mysn;
				
				$nlp = strpos($last['text'], "<br />");
				if($nlp < 50 && $nlp !== false) $preview = mb_substr($last['text'], 0, $nlp)."...";
				else
				{
					$preview = mb_substr($last['text'], 0, 50);
					if(mb_strlen($last['text']) > 50) $preview .= "...";
				}
				
				$body .= "<tr id='trs".$i."'>";
				if($row2['lasttime'] > time() - 5*60) $sender_online = "<b>";
				else $sender_online = "";
				$body .= "<td>".rusdate("@ytt@ в @H@:@i@", $last['time'], $timezone*3600, true)."</td>";
				$body .= "<td><a href='mail.php?act=talks&uid=".$row['recepient']."'>".$sender_online.$row2['name']." ".$row2['surname']."</b></a></td>";
				$body .= "<td><span style='color: #999'>".$rsend['name']." ".$rsend['surname'].":</span> ".$preview."</td></tr>";
				$i++;
			}
		}
		$body .= '</tbody></table></div>
					</div>
			<div class="title">
				<div class="left" style="padding-left: 3px">Всего собеседников: <span id="allmesnum">'.$talksnum.'</span></div>
				<div class="right">'.card_pager($page, $talksnum, MESSAGES_PER_PAGE, "mail.php?act=talks").'</div>
			</div>
		</div>';
	}
	else
	{
		$q = mysql_query("select id,sender,recepient,text,seen,time from messages where (recepient = ".$_GET['uid']." && sender = ".$_COOKIE['2ndw_userid'].") || (recepient = ".$_COOKIE['2ndw_userid']." && sender = ".$_GET['uid'].") order by time desc limit ".MESSAGES_PER_PAGE*($page-1).", ".MESSAGES_PER_PAGE);
		
		if(mysql_num_rows($q) < 1)
		{
			header("Location: mail.php?act=talks");
			exit;
		}
		
		$qnum = mysql_query("select COUNT(*) from messages where (recepient = ".$_GET['uid']." && sender = ".$_COOKIE['2ndw_userid'].") || (recepient = ".$_COOKIE['2ndw_userid']." && sender = ".$_GET['uid'].")");
		
		$qmysn = mysql_query("select name, surname from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
		$mysn = mysql_fetch_assoc($qmysn);
		
		$qhissn = mysql_query("select name, surname, lasttime from people where id = ".$_GET['uid']." limit 1");
		$hissn = mysql_fetch_assoc($qhissn);
		
		$body .= '
		<script language="javascript" type="text/javascript" src="inc/mail.js"></script>
		<div class="card" style="width: 870px">
			<div class="title">
				<div class="left"><span class="tab"><a href="mail.php?act=inlist">Входящие</a></span><span class="tab"><a href="mail.php?act=outlist">Исходящие</a></span><span class="tab current"><a href="mail.php?act=talks">Беседы</a></span></div>
				<div class="right" style="width: 164px"><div class="wait" id="wait"></div><input type="checkbox" class="refresh" id="auto" checked>Автообновление</div>
				'.$hissn['name']." ".$hissn['surname'].' (беседа)
			</div>
			<div class="body" style="height: auto">
				<div class="my_mail">
						<table class="posts" id="tableposts"><!--[if IE 6]><col style="width:120px"/><col style="width:170px"/><col style="width:540px"/><![endif]-->
						<tbody>';
		
		if($hissn['lasttime'] > time() - 5*60) $sender_online = "<b>";
		else $sender_online = "";
		
		$i = 1;
		while($row = mysql_fetch_assoc($q))
		{	
			if($row['sender'] == $_COOKIE['2ndw_userid']) {$sn = $mysn; $sender_online2 = "<b>";}
			else {$sn = $hissn; $sender_online2 = $sender_online;}
			
			$body .= "<tr id='trs".$i."'><td class='message1'>
					<div class='message1' style='background: #fff; border: 1px solid #cacaca'>
						<div class='message_date'>".rusdate("@ytt@ в @H@:@i@", $row['time'], $timezone*3600, true)."</div>
						<div class='message_date'><a href='card.php?id=".$row['sender']."'>".$sender_online2.$sn['name']." ".$sn['surname']."</b></a>:
						".wordwrap($row['text'], 60, "\n", 1)."</div>";
						if($row['sender'] != $_COOKIE['2ndw_userid']) $body .= "
							<div id='but1".$i."' class='buttons'>
								<a href='#' onclick='javascript: showform(".$i.");' class='answer'>Ответить</a>
								<a href='#' onclick='javascript: 
								var a = confirm(\"Удалить сообщение?\");
								if(a) delmes(".$i.");
								return false;' class='delete'>Удалить</a>
							</div>
							<div id='divans".$i."' class='answer_text' style='display: none;'></div>";
						
						$body .= "<div class='cl'>&nbsp;</div>
					</div>
				</td>
				</tr>
				<script language='javascript' type='text/javascript'>
				<!--//
				messages[".$i."] = new Array(4);
				messages[".$i."]['id'] = ".$row['id'].";
				messages[".$i."]['uid'] = ".$row['sender'].";
				messages[".$i."]['seen'] = ".$row['seen'].";
				//-->
				</script>";
			if($i == 1) $lastmesnum = $row['id'];
			$i++;
		}
		$body .= '</tbody></table></div>
					</div>
			<div class="title">
				<div class="left" style="padding-left: 3px">Сообщений: <span id="allmesnum">'.mysql_result($qnum, 0).'</span></div>
				<div class="right">'.card_pager($page, mysql_result($qnum, 0), MESSAGES_PER_PAGE, "mail.php?act=talks&uid=".$_GET['uid']).'</div>
			</div>
		</div>';
		$body .= '
		<script language="javascript" type="text/javascript">
		<!--//
		lastmesid = '.$lastmesnum.';
		page = "mail";
		subpage = "talks";
		mir_id = '.$_COOKIE['2ndw_userid'].';
		myname = "'.$mysn['name'].' '.$mysn['surname'].'";
		talkerid = '.$_GET['uid'].';
		//-->
		</script>
		';
	}
}
elseif($_GET['act'] == "sendajax")
{
	//$text = iconv('UTF-8', 'windows-1251', $_POST['text']);
	$text = nl2br(htmlspecialchars(mb_substr($_POST['text'], 0, 1000)));
	if(mb_strlen($text) > 0)
	{
		$q2 = mysql_query("insert into messages (sender, recepient, text, seen, time) values ('".$_COOKIE['2ndw_userid']."', '".$_POST['recepient']."', '".$text."', '0', '".time()."')");
	}
	exit;
}
elseif($_GET['act'] == "checkajax")
{
	$_GET['lastmesid'] = intval($_GET['lastmesid']);
	$q1 = mysql_query("select id, sender, text, time from messages where seen = 0 && recepient = ".$_COOKIE['2ndw_userid']." && id > ".$_GET['lastmesid']." order by time asc");
	$ns = mysql_num_rows($q1);
	$ret = array("num" => $ns, "messages" => array());
	//$qc = mysql_query("select timezone from cities where id = (select city from users where id = ".$_COOKIE['2ndw_userid'].")");
	//$timezone = mysql_result($qc, 0);
	while($row = mysql_fetch_assoc($q1))
	{
		$row['text'] = wordwrap($row['text'], 60, "\n", 1);
		$q9 = mysql_query("select name, surname from people where id = ".$row['sender']." limit 1");
		$row3 = mysql_fetch_assoc($q9);
		$ret["massages"][] = array("id" => $row['id'], "senderid" => $row['sender'], "sendername" => $row3['name']." ".$row3['surname'], "time" => rusdate("@ytt@ @H@:@i@", $row['time'], $timezone*3600, true), "text" => $row['text']);
	}
	print json_encode($ret);
	exit;
}
elseif($_GET['act'] == "delete")
{
	$_GET['mid'] = intval($_GET['mid']);
	if($_GET['mid'] < 0)
	{
		header("Location: card.php");
		exit;
	}
	$q2 = mysql_query("delete from messages where id = ".$_GET['mid']." && recepient = ".$_COOKIE['2ndw_userid']." limit 1");
	exit;
}
elseif($_GET['act'] == "seen")
{
	$_GET['mid'] = intval($_GET['mid']);
	if($_GET['mid'] < 0)
	{
		header("Location: card.phpe");
		exit;
	}
	$q2 = mysql_query("update messages set seen = 1 where id = ".$_GET['mid']." && recepient = ".$_COOKIE['2ndw_userid']." limit 1");
	exit;
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>