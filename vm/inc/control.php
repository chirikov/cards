<?php
function loginned()
{
	if(isset($_COOKIE['mir_id']))
	{
		$q = mysql_query("select pass from users where id = ".$_COOKIE['mir_id']);
		if(mysql_result($q, 0) != $_COOKIE['mir_logged']) return false;
		else
		{
			setcookie("mir_id", $_COOKIE['mir_id'], time()+60*60*24*2);
			setcookie("mir_logged", $_COOKIE['mir_logged'], time()+60*60*24*2);
			mysql_query("update users set lasttime = ". time() ." where id = ".$_COOKIE['mir_id']);
			return true;
		}
	}
	else return false;
}

if(basename($_SERVER['PHP_SELF']) != "index.php" && basename($_SERVER['PHP_SELF']) != "login.php")
{
	if(loginned() !== true)
	{
		//$_COOKIE['mir_id'] = 1;
		header("Location: index.php");
		exit;
	}
}
?>