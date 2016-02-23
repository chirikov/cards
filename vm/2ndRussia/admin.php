<?php
include_once("../inc/my_connect.php");
//include_once("inc/control.php");

if(
$_GET['act'] != "newwork" && 
$_GET['act'] != "newedwork" && 
$_GET['act'] != "newjob" && 
$_GET['act'] != "newedjob"
) $_GET['act'] = "start";

$body = "";

if($_GET['act'] == "start")
{
	$body .= "
	<a href='admin.php?act=newwork'>Новое место</a><br>
	<a href='admin.php?act=newjob'>Новая должность</a><br>
	";
}
if($_GET['act'] == "newwork")
{
	$body .= "
	Новая структура<br><br>
	<table><form action='admin.php?act=newedwork' method='post'>
	<tr><td>Название:</td><td><input type='text' name='wname' size=100></td></tr>
	<tr><td>Описание:</td><td><textarea name='description' rows=7 cols=30></textarea></td></tr>
	<tr><td>Тип:</td><td><input type='radio' checked name='typer' value='exist'>
	<select name='type'>";
	$qt = mysql_query("select id, name from gos_structures_types where 1");
	while($t = mysql_fetch_assoc($qt))
	{
		$body .= "<option value='".$t['id']."'>".$t['name'];
	}
	$body .= "</select></td></tr>
	<tr><td></td><td><input type='radio' name='typer' value='new'><input type='text' name='tname' size=50></td></tr>
	<tr><td>Город:</td><td>
	<select name='city'>";
	$qc = mysql_query("select id, name from cities where 1");
	while($c = mysql_fetch_assoc($qc))
	{
		$body .= "<option value='".$c['id']."'>".$c['name'];
	}
	$body .= "</select></td></tr>
	<tr><td>Какому органу непосредственно подчиняется:</td><td>
	<select name='submission'>
	<option value='0'>никакому";
	$qc = mysql_query("select id, name from gos_structures where 1");
	while($c = mysql_fetch_assoc($qc))
	{
		$body .= "<option value='".$c['id']."'>".$c['name'];
	}
	$body .= "</select></td></tr>
	<tr><td></td><td><input type='submit' value='Создать'></form></td></tr></table>";
}
if($_GET['act'] == "newedwork")
{
	if($_POST['typer'] == "new")
	{
		$qt = mysql_query("insert into gos_structures_types (name) values ('".$_POST['tname']."')");
		$typeid = mysql_insert_id($mysql);
	}
	else $typeid = $_POST['type'];
	
	$q = mysql_query("insert into gos_structures (type, name, description, city, submission) values ('".$typeid."', '".$_POST['wname']."', '".$_POST['description']."', '".$_POST['city']."', '".$_POST['submission']."')");
	header("Location: admin.php?act=newwork");
	exit;
}
if($_GET['act'] == "newjob")
{
	$body .= '
	<html>
	<body>
	<script language="javascript" type="text/javascript">
	<!--//
	var browser = "ie";
	switch(navigator.appName)
	{
		case "Opera": browser = "opera"; break;
		case "Netscape": browser = "netscape"; break;
	}
	
	function getHTTPRequestObject() {
		var xmlHttpRequest;
		/*@cc_on
		@if (@_jscript_version >= 5)
		try {
			xmlHttpRequest = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (exception1) {
			try {
				xmlHttpRequest = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (exception2) {
				xmlHttpRequest = false;
			}
		}
		@else
		xmlhttpRequest = false;
		@end @*/
		
		if (!xmlHttpRequest && typeof XMLHttpRequest != "undefined") {
			try {
				xmlHttpRequest = new XMLHttpRequest();
			}
			catch (exception) {
				xmlHttpRequest = false;
			}
		}
		return xmlHttpRequest;
	}
	function workplaced()
	{
		var place = document.getElementById("workplace").value;
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			oXmlHttp.open("GET", "job.php?act=ajaxgetjobs&object="+place, true);
			oXmlHttp.onreadystatechange = function()
			{
				if(oXmlHttp.readyState == 4 || oXmlHttp.readyState == "complete")
				{
					var opts = document.getElementById("submission");
					for(i=opts.options.length; i>0; i--)
					{
						opts.options.remove(i);
					}
					if(oXmlHttp.responseText != "")
					{
						var vals = oXmlHttp.responseText.split("@");
						for(i=0; i<vals.length; i++)
						{
							var val = vals[i].split("|");
							var elem = document.createElement("option");
							elem.value = val[0];
							elem.text = val[1];
							if(browser == "ie") opts.add(elem, 1);
							else opts.appendChild(elem);
						}
					}
				}
			}
			oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
	    	oXmlHttp.send(null);
		}
	}
	//-->
	</script>
	';
	$body .= "
	Новая должность<br><br>
	<table><form action='admin.php?act=newedjob' method='post'>
	<tr><td>Название:</td><td><input type='text' name='jname' size=100></td></tr>
	<tr><td>Орган:</td><td>
	<select name='object' id='workplace' onchange='javascript: workplaced();'>
	<option>Выберите";
	$qc = mysql_query("select id, name from gos_structures where 1");
	while($c = mysql_fetch_assoc($qc))
	{
		$body .= "<option value='".$c['id']."'>".$c['name'];
	}
	$body .= "</select></td></tr>
	<tr><td>Зарплата:</td><td><input type='text' name='payment' value=0 size=100></td></tr>
	<tr><td>Выборная:</td><td><input type='checkbox' name='voteing' value='1'></td></tr>
	<tr><td>Какому лицу непосредственно подчиняется:</td><td>
	<select name='submission' id='submission'>
	<option value='0'>никакому
	</select></td></tr>
	<tr><td></td><td><input type='submit' value='Создать'></form></td></tr></table>
	</body></html>";
}
if($_GET['act'] == "newedjob")
{
	if($_POST['voteing'] == "") $_POST['voteing'] = 0;
	$q = mysql_query("insert into jobs (name, object, payment, voteing, submission) values ('".$_POST['jname']."', '".$_POST['object']."', '".$_POST['payment']."', '".$_POST['voteing']."', '".$_POST['submission']."')");
	header("Location: admin.php?act=newjob");
	exit;
}

print $body;

?>