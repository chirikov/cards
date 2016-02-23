<?php
/*
function owner($id)
{
	$q = mysql_query("select id from cards where type = 1 and id = (select start from net where end = ".$id.")"); // Предполагается, что НА карточку может ссылаться только 1 визитка, но не только визитки.
	return mysql_result($q, 0);
}
*/

function are_friends($id1, $id2)
{
	/* direct search through net
	if(mysql_num_rows(mysql_query("select start from net where ((start = ".$id1." and end = ".$id2.") or (end = ".$id1." and start = ".$id2.")) and seen = 1")) > 0) return true;
	else return false;
	*/
	
	// using field cards.connections (id,id,id,...)
	$q = mysql_query("select find_in_set('".$id1."', (select connections from cards where id = ".$id2."))");
	
	if(mysql_result($q, 0) > 0) return true;
	else return false;
}

function are_friends_friends($id1, $id2)
{
	/* 4 possible clauses of being friends-friends
	$q1 = mysql_query("select end from net where seen = 1 and start = ".$id1." and (select net.end in (select start from net where seen = 1 and end = ".$id2.")) = 1 and (select type from cards where id = net.end) = 1"); // 1->x; x->2
	if(mysql_num_rows($q1) > 0) return true;
	$q2 = mysql_query("select end from net where seen = 1 and start = ".$id1." and (select net.end in (select end from net where seen = 1 and start = ".$id2.")) = 1 and (select type from cards where id = net.end) = 1"); // 1->x; 2->x
	if(mysql_num_rows($q2) > 0) return true;
	$q3 = mysql_query("select start from net where seen = 1 and end = ".$id1." and (select net.start in (select end from net where seen = 1 and start = ".$id2.")) = 1 and (select type from cards where id = net.start) = 1"); // x->1; 2->x
	if(mysql_num_rows($q3) > 0) return true;
	$q4 = mysql_query("select start from net where seen = 1 and end = ".$id1." and (select net.start in (select start from net where seen = 1 and end = ".$id2.")) = 1 and (select type from cards where id = net.start) = 1"); // x->1; x->2
	if(mysql_num_rows($q4) > 0) return true;
	*/
	
	if(are_friends($id1, $id2)) return true;
	
	// using field cards.connections (id,id,id,...)
	//$q = mysql_query("select id from cards where type = 1 and find_in_set(cards.id, (select connections from cards where id = ".$id1.") > 0 and find_in_set(cards.id, (select connections from cards where id = ".$id2.") > 0");
	$q = mysql_query("select id from cards where type = 1 and find_in_set('".$id1."', connections) > 0 and find_in_set('".$id2."', connections) > 0 limit 1");
	
	if(mysql_num_rows($q) > 0) return mysql_result($q, 0);
	else return false;
}

function check_perm($id, $type)
{
	$q = mysql_query("select owner, ".$type."_perm from cards where id = ".$id." limit 1");
	$card = mysql_fetch_assoc($q);
	if($card['owner'] == $_COOKIE['2ndw_userid']) return true;
	$perm = explode("|", $card[$type.'_perm']);
	
	if(ereg("^([a-z0-9,]*,)*[fw]*".$_COOKIE['2ndw_userid']."(,[a-z0-9,]*)*$", $perm[2])) return false;
	elseif(ereg("^([a-z0-9,]*,)*[fw]*".$_COOKIE['2ndw_userid']."(,[a-z0-9,]*)*$", $perm[1])) return true;
	else
	{
		$minus = explode(",", $perm[2]);
		foreach($minus as $mins)
		{
			if(substr($mins, 0, 1) == "f")
			{
				if(are_friends(substr($mins, 1), $_COOKIE['2ndw_userid'])) return false;
			}
			elseif(substr($mins, 0, 1) == "g")
			{
				if(mysql_num_rows(mysql_query("select start from net where start = ".substr($mins, 1)." and end = ".$_COOKIE['2ndw_userid'])) > 0) return false;
			}
			elseif(substr($mins, 0, 1) == "w")
			{
				if(are_friends_friends(substr($mins, 1), $_COOKIE['2ndw_userid'])) return false;
			}
		}
		$plus = explode(",", $perm[1]);
		foreach($plus as $mins)
		{
			if(substr($mins, 0, 1) == "f")
			{
				if(are_friends(substr($mins, 1), $_COOKIE['2ndw_userid'])) return true;
			}
			elseif(substr($mins, 0, 1) == "g")
			{
				if(is_connected(substr($mins, 1), $_COOKIE['2ndw_userid']) > 0) return true;
			}
			elseif(substr($mins, 0, 1) == "w")
			{
				if(are_friends_friends(substr($mins, 1), $_COOKIE['2ndw_userid'])) return true;
			}
		}
		if($perm[0] == "a") return true;
		elseif($perm[0] == "f" && are_friends($_COOKIE['2ndw_userid'], $card['owner'])) return true;
		elseif($perm[0] == "w" && (are_friends_friends($_COOKIE['2ndw_userid'], $card['owner']))) return true;
		else return false;
	}
}

function is_connected($id, $to=0) // false, wait, true
{
	if($to = 0) $to = $_COOKIE['2ndw_userid'];
	
	$qs = mysql_query("select seen from net where (start = ".$id." and end = ".$to.") or (start = ".$to." and end = ".$id.") limit 1");
	if(mysql_num_rows($qs) > 0)
	{
		$seen = mysql_result($qs, 0);
		if($seen == 1) return true;
		else return "wait";
	}
	else return false;
}

function connect($id, $to=0)
{
	if($to = 0) $to = $_COOKIE['2ndw_userid'];
	
	$iscon = is_connected($id, $to);
	if($iscon === true) return "connected";
	if($iscon === "wait")
	{
		$q = mysql_query("update net set seen = 1 where start = ".$id." and end = ".$to." limit 1");
		if(mysql_affected_rows($mysql) == 1) return true;
		else return "wait";
	}
	elseif(check_perm($id, "add"))
	{
		$qa = mysql_query("select auth from cards where id = ".$id." limit 1");
		$auth = mysql_result($qa, 0);
		$seen = 1 - $auth;
		$q1 = mysql_query("insert in net values (".$id.", ".$to.", ".$seen.") limit 1");
		if($auth = 0)
		{
			$q2 = mysql_query("update cards set connections = concat(connections, make_set(2+(select length(connections) > 0), ',', '".$to."')) where id = ".$id." limit 1");
			$q3 = mysql_query("update cards set connections = concat(connections, make_set(2+(select length(connections) > 0), ',', '".$id."')) where id = ".$to." limit 1");
		}
		if($q1 && $auth == 0) return true;
		elseif($q1 && $auth == 1) return "wait";
		else return false;
	}
	else return false;
}

$cards = array(
	1 => array(
		"name" => "Визитка",
		"color" => "#F5F5F5",
		"table" => "people"
		),
	2 => array(
		"name" => "Коллекция",
		"color" => "#C6FCBD",
		"table" => "collections"
		),
	3 => array(
		"name" => "Фотография",
		"color" => "#D7E4FC",
		"table" => "photos"
		),
	4 => array(
		"name" => "Аудиозапись",
		"color" => "#FCF6D7",
		"table" => "audio"
		),
	5 => array(
		"name" => "Видеоролик",
		"color" => "#E1DFFA",
		"table" => "video"
		),
	6 => array(
		"name" => "Фотография",
		"color" => "#FEDADE",
		"table" => "photos"
		),
	7 => array(
		"name" => "Аудиозапись",
		"color" => "#C8FCFD",
		"table" => "audio"
		),
	8 => array(
		"name" => "Видеоролик",
		"color" => "#8FA2F3",
		"table" => "video"
		)
);

function card($id=1, $w=890, $h=600)
{
	global $cards, $page_title;
	
	$q = mysql_query("select type, owner from cards where id = ".$id." limit 1");
	$card = mysql_fetch_assoc($q);
	
	if($card['owner'] == $_COOKIE['2ndw_userid']) $me = true;
	else $me = false;
	
	$qo = mysql_query("select name, surname from people where id = ".$card['owner']." limit 1");
	$man = mysql_fetch_assoc($qo);
	if(check_perm($id, "view"))
	{
		if($card['type'] == 1)
		{
			$body = vizitka($id, $w, $h);
		}
		elseif($card['type'] == 2)
		{
			$q1 = mysql_query("select name from collections where id = ".$id." limit 1");
			$page_title = $man['name'].' '.$man['surname'].' - '.mysql_result($q1, 0);
			$body = '
			<div class="card" style="width: '.$w.'px">
				<div class="title">
					<div class="left">'.$cards[$card['type']]['name'].'</div>
					<div class="right">Карточка #'.$id.'</div>
					'.$man['name'].' '.$man['surname'].' - '.mysql_result($q1, 0).'
				</div>
				<div class="body" style="height: '.$h.'px; background: '.$cards[$card['type']]['color'].'">';
			$qc = mysql_query("select end from net where start = ".$id." and seen = 1 limit 6");
			if(mysql_num_rows($qc) == 0)
			{
				$body .= '<table class="center bigtext">
					<tr><td align="center">В коллекции нет карточек</td></tr>
					<tr><td class="bottom"></td></tr>
					</table>';
			}
			else
			{
				$body .= '<table>';
				$inrow = 3;
				for($i=0; $i<mysql_num_rows($qc); $i++)
				{
					if($i % $inrow == 0) $body .= "<tr>";
					$body .= '<td>'.card_thumb($id).'</td>';
					if(($i+1) % $inrow == 0) $body .= "</tr>";
				}
				if(($i+1) % $inrow != 0) $body .= "</tr>";
				$body .= '</table>';
			}
			$body .= '</div>
				<div class="title">
					<div class="left"></div>
					<div class="right"></div>
				</div>
			</div>';
		}
		elseif($card['type'] == 3)
		{
			$q1 = mysql_query("select * from photos where id = ".$id." limit 1");
			$photo = mysql_fetch_assoc($q1);
			$body = '			
			<div class="card" style="width: '.$w.'px">
				<div class="title">
					<div class="left">'.$cards[$card['type']]['name'].'</div>
					<div class="right">Карточка #'.$id.'</div>
					'.$photo["name"].'
				</div>
				<div class="body" style="height: '.$h.'px; background: '.$cards[$card['type']]['color'].'">
					
				</div>
				<div class="title">
					<div class="left"></div>
					<div class="right"></div>
				</div>
			</div>';
		}
	}
	else
	{
		$body = '
		<div class="card" style="width: '.$w.'px">
			<div class="title">
				<div class="left">'.$cards[$card['type']]['name'].'</div>
				<div class="right">Карточка #'.$id.'</div>
			</div>
			<div class="body" style="height: '.$h.'px; background: '.$cards[$card['type']]['color'].'">
				<table class="center bigtext">
				<tr><td align="center">Просмотр карточки ограничен</td></tr>
				<tr><td class="bottom"></td></tr>
				</table>
			</div>
			<div class="title">
				<div class="left"></div>
				<div class="right"></div>
			</div>
		</div>';
	}
	return $body;
}

function card_thumb($id, $w=200, $h=140)
{
	global $cards;
	
	$q = mysql_query("select type, owner from cards where id = ".$id." limit 1");
	$card = mysql_fetch_assoc($q);
	
	$qo = mysql_query("select name, surname, photo from people where id = ".$card['owner']." limit 1");
	$man = mysql_fetch_assoc($qo);
	if(check_perm($id, "view"))
	{
		if($card['type'] == 1)
		{
			$body = '
			<div class="card" style="width: '.$w.'px">
			<div class="title">
				<div class="left"></div>
				<div class="right">#'.$id.'</div>
			</div>
			<div class="body" style="height: '.$h.'px; background: '.$cards[$card['type']]['color'].'">
				<table class="center smalltext">
				<tr><td align="center">
				<a href="card.php?id='.$id.'">';
			if($man['photo'] == "") $body .= '<img src="images/noavatars.jpg" alt="'.$man['name'].' '.$man['surname'].'" class="border">';
			else $body .= '<img src="photos/'.$card['owner'].'/'.$man['photo'].'s.jpg" alt="'.$man['name'].' '.$man['surname'].'" class="border">';
			$body .= '<br>'.$man['name'].' '.$man['surname'].'</a>
				</td></tr>
				</table>
			</div>
			<div class="title">
				<div class="left"></div>
				<div class="right"></div>
			</div>
		</div>';
			return $body;
		}
		elseif($card['type'] == 2)
		{
			$q1 = mysql_query("select name from collections where id = ".$id." limit 1");
			$body = '
			<div class="card" style="width: '.$w.'px">
			<div class="title">
				<div class="left"></div>
				<div class="right">#'.$id.'</div>
			</div>
			<div class="body" style="height: '.$h.'px; background: '.$cards[$card['type']]['color'].'">
				<table class="center smalltext">
				<tr><td align="center">
				<a href="card.php?id='.$id.'">
				'.$man['name'].' '.$man['surname'].'<br>
				'.mysql_result($q1, 0).'
				</a>
				</td></tr>
				</table>
			</div>
			<div class="title">
				<div class="left"></div>
				<div class="right"></div>
			</div>
		</div>';
			return $body;
		}
	}
}

include_once("inc/my_connect.php");
include_once("inc/control.php");
include_once("inc/functions.php");
include_once("inc/cards.php");

$id = @intval($_GET['id']);
$id<1?$id=$_COOKIE['2ndw_userid']:1;

$body = card($id);
include("inc/head.php");
print $body;
//print card_thumb($id);
include("inc/foot.php");

?>