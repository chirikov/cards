<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");

if(
$_GET['act'] != "ajaxlogindone" && 
$_GET['act'] != "logout"
) exit;

if($_GET['act'] == "ajaxlogindone")
{
	$email = trim($_POST['email']);
	$pass = trim($_POST['pass']);
	$q1 = mysql_query("select id, pass, actcode from users where email = '".$email."'");
	if(mysql_num_rows($q1) < 1) $result = "erНеверный E-mail";
	else
	{
		$id = mysql_result($q1, 0, 'id');
		$passdb = mysql_result($q1, 0, 'pass');
		$actcode = mysql_result($q1, 0, 'actcode');
		if($actcode != 0)
		{
			$result = "eemailunactive";
		}
		elseif($passdb != md5($pass))
		{
			$result = "epass";
		}
		else
		{
			setcookie("mir_id", $id, time()+60*60*24*2);
			setcookie("mir_logged", $passdb, time()+60*60*24*2);
			$result = "ok";
		}
	}
	print $result;
}
elseif($_GET['act'] == "logout")
{
	$q = mysql_query("update users set lasttime = ".(time()-5*60)." where id = ".$_COOKIE['mir_id']);
	setcookie("mir_id", "", time()-3600);
	setcookie("mir_logged", "", time()-3600);
	header("Location: index.php");
	exit;
}
?>