<?php
function loginned()
{
	if(isset($_COOKIE['2ndw_userid']))
	{
		$q = mysql_query("select pass, lasttime from people where id = ".$_COOKIE['2ndw_userid']." limit 1");
		if(mysql_result($q, 0, 'pass') != $_COOKIE['2ndw_pass']) return false;
		else
		{
			$time = time();
			if($time - mysql_result($q, 0, 'lasttime') > 3*60)
			{
				mysql_query("update people set lasttime = ".$time." where id = ".$_COOKIE['2ndw_userid']." limit 1");
				setcookie("2ndw_userid", $_COOKIE['2ndw_userid'], $time+60*60*24*2);
				setcookie("2ndw_pass", $_COOKIE['2ndw_pass'], $time+60*60*24*2);
			}
			return true;
		}
	}
	else return false;
}

if(basename($_SERVER['PHP_SELF']) != "index.php")
{
	if(loginned() !== true)
	{
		header("Location: index.php");
		exit;
	}
}
else
{
	if(loginned() === true && $_GET['act'] != "logout")
	{
		//header("Location: card.php");
		//exit;
	}
}
?>