<?php

function vizitka($id, $w, $h)
{
	global $page_title;
	$_GET['tab'] = @intval($_GET['tab']);
	
	if($id == $_COOKIE['2ndw_userid']) $me = true;
	else $me = false;
			
	$qo = mysql_query("select * from people where id = ".$id." limit 1");
	$man = mysql_fetch_assoc($qo);
	
	$page_title = $man['name']." ".$man['surname'];
	$body = "";
	
	if($me) $body .= '
	<script type="text/javascript" src="inc/window.js"></script>
	<script type="text/javascript" src="inc/variants.js"></script>
	<script type="text/javascript" src="inc/jscolor.js"></script>
	<script type="text/javascript" src="inc/layout.js"></script>';
	$body .= '<script type="text/javascript" src="inc/hotsettings.js"></script>';
	$body .= '<div id="card" class="card" style="width: '.$w.'px; margin: 7px auto">
	<div class="title">
		<div id="card_top_left" class="left">';
	$tabs = json_decode($man['tabs'], true);
	$tabnum = count($tabs);
	if($me) $body .= '<span class="tab current" id="tab_current" onmouseover="this.children[1].style.visibility = \'visible\';" onmouseout="this.children[1].style.visibility = \'hidden\';"><span>'.$tabs[$_GET['tab']]['title'].'</span> <a style="visibility: hidden" href="javascript: edittab();">...</a></span>';
	else $body .= '<span class="tab current" id="tab_current" onmouseover="this.children[1].style.visibility = \'visible\';" onmouseout="this.children[1].style.visibility = \'hidden\';"><span>'.$tabs[$_GET['tab']]['title'].'</span> <a style="visibility: hidden" href="javascript: edittab();">...</a></span>';
	$body .= '<span class="tab" '.($tabnum>1?"":'style="display: none"').' id="card_menu_arrow"><a class="arrow" href="javascript: show_card_menu();" title="Вкладки">▼</a></span>';
	if($me) $body .= '<span class="tab"><a href="javascript: addtab();">+</a></span>';
	if($man['lasttime'] > time()-5*60) $body .= '<span class="status_g">в сети</span>';
	else $body .= '<span class="status_r">Последний визит: '.rusdate("@ytt@ в @H@:@i@", $man['lasttime'], 2*3600, true).'</span>';
	$body .= '</div>
		<div class="right">Карточка #'.$id.'</div>
		'.$man['name'].' '.$man['surname'].'
	</div>
	<div class="body" id="card_body" style="height: '.$h.'px; background: '.($tabs[$_GET['tab']]['bg']==""?$cards[$card['type']]['color']:(substr($tabs[$_GET['tab']]['bg'], 0, 1)=="#"?$tabs[$_GET['tab']]['bg']:'url(photos/'.$_COOKIE['2ndw_userid'].'/'.$tabs[$_GET['tab']]['bg'].'.jpg) 50% 50% no-repeat')).'">';//<div class="items">
	$layout = array();
	foreach($tabs[$_GET['tab']]['items'] as $item)
	{
		$print_title = true;
		if($item['id'] == "photo")
		{
			$print_title = false;
			$ins = "";
			if($me) $ins .= '<a href="javascript: editphoto(\''.$item['id'].'\');" id="a_'.$item['id'].'">';
			if($man[$item['id']] != "") $ins .= '<img src="photos/'.$_COOKIE['2ndw_userid'].'/'.$man[$item['id']].'.jpg" style="'.$item['style'].'" alt="'.$man['name'].' '.$man['surname'].'" class="border"/>';
			else $ins .= '<img src="images/noavatar.jpg" alt="Нет фотографии" class="border"/>';
			if($me) $ins .= '</a>';
			$item['style'] = "";
		}
		elseif(substr($item['id'], 0, 5) == "image")
		{
			$print_title = false;
			$ins = "";
			if($me) $ins .= '<a id="a_'.$item['id'].'" href="javascript: editimage(\''.$item['id'].'\');">';
			$ins .= '<img class="border" style="'.$item['style'].'" src="photos/'.$id.'/'.$item['value'].'.jpg">';
			if($me) $ins .= '</a>';
			$item['style'] = "";
		}
		elseif(substr($item['id'], 0, 3) == "box")
		{
			$print_title = false;
			$ins = "";
			if($me) $ins .= '<a id="a_'.$item['id'].'" href="javascript: editbox(\''.$item['id'].'\');">';
			$ins .= '<div style="'.$item['style'].'"></div>';
			if($me) $ins .= '</a>';
			$item['style'] = "";
		}
		elseif($item['id'] == "name" || $item['id'] == "surname" || $item['id'] == "mtel" || $item['id'] == "dtel" || $item['id'] == "rtel" || $item['id'] == "icq" || $item['id'] == "skype" || $item['id'] == "birthdate" || $item['id'] == "city" || $item['id'] == "home" || $item['id'] == "fstatus")
		{
			switch($item['id'])
			{
				case "mtel": $title = "Мобильный телефон"; break;
				case "dtel": $title = "Домашний телефон"; break;
				case "rtel": $title = "Рабочий телефон"; break;
				case "icq": $title = "ICQ"; break;
				case "skype": $title = "Skype"; break;
				case "birthdate": $title = "Дата рождения"; break;
				case "city": $title = "Город"; break;
				case "home": $title = "Адрес"; break;
				case "fstatus": $title = "Семейное положение"; break;
				case "name": $print_title = false; break;
				case "surname": $print_title = false; break;
			}
			$fstatuses = array("", "Не женат/Не замужем", "Встречаюсь", "Помолвлен(а)", "Женат/Замужем", "Ищу");
			switch($item['id'])
			{
				case "birthdate": $ins = rusdate("@j@ @month_rod@ @Y@", $man[$item['id']]); break;
				case "city": $ins = format_address($id, "people.city", true); break;
				case "home": $ins = format_address($id); break;
				case "fstatus": $ins = $fstatuses[$man[$item['id']]]; break;
				default: $ins = $man[$item['id']];
			}
			$tabs[$_GET['tab']]['items'][$item['id']]['value'] = $ins;
			$tabs[$_GET['tab']]['items'][$item['id']]['title'] = $title;
		}
		elseif($item['id'] == "work")
		{
			$title = "Работа";
			$parts = explode("#", $man[$item['id']]);
			$ins = $parts[3].($parts[4]!=""?", ":"").$parts[4]."<br>".format_address($id, "people.work");
			$tabs[$_GET['tab']]['items'][$item['id']]['title'] = $title;
		}
		else
		{
			if($item['nt']) $print_title = false;
			else $title = $item['title'];
			$ins = $item['value'];
		}
		$layout[$item['id'].'_x'] = $item['x'];
		$layout[$item['id'].'_y'] = $item['y'];
		
		$body .= '<div class="draggable" id="div_'.$item['id'].'" onmousedown="javascript: Layout.StartDrag(event, this);" style="';
		if($item['style'] != "") $body .= $item['style'].' ';
		if($layout) $body .= 'left: '.$layout[$item['id'].'_x'].'px; top: '.$layout[$item['id'].'_y'].'px;';
		$body .= '">'.($print_title?$title.': ':'').$ins.'</div>';	
	}
	if($me)
	{
		$body .= '
		<script type="text/javascript">
		tab = '.$_GET['tab'].';
		Layout.getid = function(id){return id.substr(4);}
		Layout.url = "settings.php?act=setlayout&tab="+tab;
		Layout.parent = document.getElementById(\'card_body\');
		Layout.layout = {};
		userid = '.$_COOKIE['2ndw_userid'].';
		tabs = eval('.json_encode($tabs).');';
		
		reset($layout);
		while (list($key, $val) = each($layout)) {
		    $body .= 'Layout.layout["'.$key.'"] = "'.$val.'px";';
		}
		$body .= '</script>';
	}
		$body .= '</div>
		<div class="title">
			<div id="card_bottom_left" class="left">';
	if($me) $body .= '<span class="tab current wa" id="card_set_title">Настройки</span><span class="tab" id="card_set_arrow"><a href="javascript: show_set_menu();" class="arrow" title="Настройки">▲</a></span>';
	$body .= '</div><div class="right">';
	if($me) $body .= '
				<a class="addopt" href="#" onclick="additem('.$_GET['tab'].');">Добавить/изменить сведения</a>
				<div class="change_interface">
					<a id="changepagehref" class="change" href="javascript: Layout.changepageclick();">Изменить расположение</a>
					<a id="cancelpagehref" style="display: none;" href="javascript: Layout.cancelpage();">Отмена</a>
					<a id="defaultpagehref" style="display: none;" href="javascript: Layout.defaultpage();">По умолчанию</a>
					<a style="display: none;" id="grid" href="javascript: Layout.show_grid();">Сетка</a>
				</div>';
		 $body .= '</div>
		</div>
	</div>';
		 
	return $body;
}

?>