<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");

if(
$_GET['act'] != "default" && 
$_GET['act'] != "search"
) $_GET['act'] = "default";

$body = "";

function searchform($name="", $surname="")
{
	$retv = '
	<form id="fsearch" action="search.php?act=search" method="POST" name="fsearch">
	<table class="form_table"><!--[if IE 6]><col style="width:70px"/><col style="width:157px"/><col style="width:100px"/><![endif]-->
		<tr>
			<td><label>Имя:</label></td>
			<td><input type="Text" name="name" maxlength="40" value="'.$name.'"/></td>
		</tr>
		<tr>
			<td><label>Фамилия:</label></td>
			<td><input type="Text" name="surname" maxlength="40" value="'.$surname.'"/></td>
		</tr>
	</table>
	<div class="enter"><a href="#" onclick="javascript: document.getElementById(\'fsearch\').submit();">Искать</a></div>
	</form>
	';
	return $retv;
}

if($_GET['act'] == "default")
{
	$body .= '
	<div class="start">
		<div class="start_form">
			<div class="top_corners"><i>&nbsp;</i></div>
			<h2>Поиск контактов</h2>
			<div class="start_form_pad">
				'.searchform().'
			</div>
			<div class="bottom_corners"><i>&nbsp;</i></div>
		</div>
	</div>
	';
}
elseif($_GET['act'] == "search")
{
	$name = trim($_POST['name']);
	$surname = trim($_POST['surname']);
	if($name == "") $name = ".";
	if($surname == "") $surname = ".";
	$body .= '
	<div class="start">
		<div class="start_form">
			<div class="top_corners"><i>&nbsp;</i></div>
			<h2>Поиск контактов</h2>
			<div class="start_form_pad">
				'.searchform($name, $surname).'
			</div>
			<div class="bottom_corners"><i>&nbsp;</i></div>
		</div><br>';
	$q1 = mysql_query("select id, surname, name from users where name regexp '.*".$name.".*' and surname regexp '.*".$surname.".*' and actcode = 0");
	if(mysql_num_rows($q1) == 0)
	{
		$body .= "Пользователей не найдено.";
	}
	else
	{
		while($row = mysql_fetch_assoc($q1))
		{
			$body .= "<a href='profile.php?uid=".$row['id']."'>".$row['name']." ".$row['surname']."</a><br>";
		}
	}
	$body .= '</div>';
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>