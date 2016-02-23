<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");
include_once("inc/constants.php");
include_once("beta/collage.php");

if(
$_GET['act'] != "massmail" && 
$_GET['act'] != "massmailed" &&
$_GET['act'] != "photo_structure"
) exit;

$body = "";

if($_COOKIE['mir_id'] == 1)
{
	if($_GET['act'] == "massmail")
	{
		$body .= "
		<form action='adminfunc.php?act=massmailed' method='post'>
		<textarea name='text'></textarea>
		<input type='submit'>
		</form>
		";
	}
	elseif($_GET['act'] == "massmailed")
	{
		$q2 = mysql_query("insert into messages (sender, recepient, text, seen, time) values ('0', '0', '".addslashes(htmlspecialchars($_POST['text']))."', '0', '".time()."')");
	}
	elseif($_GET['act'] == "photo_structure")
	{
		$qu = mysql_query("select id from users where 1");
		while($user = mysql_fetch_assoc($qu))
		{
			$qa = mysql_query("select id from albums where uid = ".$user['id']);
			while($album = mysql_fetch_assoc($qa))
			{
				$qp = mysql_query("update photos set uid = ".$user['id']." where album = ".$album['id']);
				$qs = mysql_query("select id from photos where album = ".$album['id']);
				$i = 1;
				while($row = mysql_fetch_assoc($qs))
				{
					$qsu = mysql_query("update photos set sequence = ".$i." where id = ".$row['id']);
					$i++;
				}
				$qp = mysql_query("select code from photos where album = ".$album['id']);
				while($row = mysql_fetch_assoc($qp))
				{
					$code = $row['code'];
					if(file_exists("photos/".$user['id']."/".$code.".jpg"))
					{
						$ar = range('a', 'z');
						shuffle($ar);
						$code = substr(implode("", $ar), rand(0, 19), 7);
						while(file_exists("photos/".$user['id']."/".$code.".jpg"))
						{
							$code = substr(implode("", $ar), rand(0, 19), 7);
						}
						$qnc = mysql_query("update photos set code = ".$code." where id = ".$row['id']);
					}
					copy("photos/".$user['id']."/".$album['id']."/".$row['code'].".jpg", "photos/".$user['id']."/".$code.".jpg");
					copy("photos/".$user['id']."/".$album['id']."/".$row['code']."s.jpg", "photos/".$user['id']."/".$code."s.jpg");
					unlink("photos/".$user['id']."/".$album['id']."/".$row['code'].".jpg");
					unlink("photos/".$user['id']."/".$album['id']."/".$row['code']."s.jpg");
				}
				rmdir("photos/".$user['id']."/".$album['id']);
				cover($album['id']);
			}
		}
	}
}
include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>