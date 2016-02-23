<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");

function resize_photo($file, $nh, $nw, $nhs, $nws, $quality=false, $qualitys=false)
{
	$file2 = ereg_replace(".jpg$", "s.jpg", $file);
	$sizes = getimagesize($file);
	$hr = $sizes[1]/$nh;
	$wr = $sizes[0]/$nw;
	if($wr >= $hr)
	{
		$w = $nw;
		$h = $sizes[1]/$wr;
		$ws = $nws;
		$hs = $sizes[1]*$nws/$sizes[0];
	}
	else
	{
		$h = $nh;
		$w = $sizes[0]/$hr;
		$hs = $nhs;
		$ws = $sizes[0]*$nhs/$sizes[1];
	}
	$im = imagecreatefromjpeg($file);
	$im2 = imagecreatetruecolor($w, $h);
	$im3 = imagecreatetruecolor($ws, $hs);
	imagecopyresampled($im2, $im, 0, 0, 0, 0, $w, $h, $sizes[0], $sizes[1]);
	imagecopyresampled($im3, $im, 0, 0, 0, 0, $ws, $hs, $sizes[0], $sizes[1]);
	imagedestroy($im);
	if($quality === false) imagejpeg($im2, $file);
	else imagejpeg($im2, $file, $quality);
	if($qualitys === false) imagejpeg($im3, $file2);
	else imagejpeg($im3, $file2, $qualitys);
	imagedestroy($im2);
	imagedestroy($im3);
}

function check_image_file($file)
{
	if($file['size'] == 0) return "Enofile";
	elseif($file['type'] != "image/jpeg" && $file['type'] != "image/pjpeg") return "Etype";
	elseif($file['size'] > 8000000) return "Esize";
	else return true;
}

$body = "";

if($_GET['act'] == "setavatar")
{
	$check = check_image_file($_FILES['photo']);
	if($check !== true) $result = $check;
	else
	{
		$q1 = mysql_query("select photo from people where id = ".$_COOKIE['mir_id']." limit 1");
		$ava = mysql_result($q1, 0);
		if($ava != "")
		{
			unlink("photos/".$_COOKIE['mir_id']."/".mysql_result($q1, 0).".jpg");
			unlink("photos/".$_COOKIE['mir_id']."/".mysql_result($q1, 0)."s.jpg");
		}
		$ar = range('a', 'z');
		shuffle($ar);
		$code = substr(implode("", $ar), rand(0, 19), 7);
		while(file_exists("photos/".$_COOKIE['mir_id']."/".$code.".jpg"))
		{
			$code = substr(implode("", $ar), rand(0, 19), 7);
		}
		move_uploaded_file($_FILES['photo']['tmp_name'], "photos/".$_COOKIE['mir_id']."/".$code.".jpg");
		resize_photo("photos/".$_COOKIE['mir_id']."/".$code.".jpg", 300, 225, 100, 75, 80, 90);
		$q = mysql_query("update people set photo = '".$code."' where id = ".$_COOKIE['mir_id']." limit 1");
		print "ok";
		//header("Location: card.php");
		exit;
	}
	print $result;
	//header("Location: photo.php?act=loadavatar&result=".$result);
	exit;
}
elseif($_GET['act'] == "addedphoto")
{
	$aid = $_POST['aid'];
	$q1 = mysql_query("select id from albums where id = ".$aid." && uid = ".$_COOKIE['mir_id']);
	if($aid < 1)
	{
		header("Location: profile.php");
		exit;
	}
	elseif(mysql_num_rows($q1) < 1)
	{
		header("Location: profile.php");
		exit;
	}
	else
	{
		for($i=1; $i<=$_POST['photonum']; $i++)
		{
			if($_FILES['photo'.$i]['size'] != 0 && ($_FILES['photo'.$i]['type'] == "image/jpeg" || $_FILES['photo']['type'] != "image/pjpeg") && $_FILES['photo'.$i]['size'] <= MAX_PHOTO_SIZE)
			{
				$ar = range('a', 'z');
				shuffle($ar);
				$code = substr(implode("", $ar), rand(0, 19), 7);
				while(file_exists("photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg"))
				{
					$code = substr(implode("", $ar), rand(0, 19), 7);
				}
				move_uploaded_file($_FILES['photo'.$i]['tmp_name'], "photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg");
				resize2("photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg", PHOTO_H, PHOTO_W, PHOTOS_K, PHOTO_Q, PHOTOS_Q);
				$q = mysql_query("insert into photos (album, name, code, addtime) values('".$aid."', '".htmlspecialchars(trim($_POST['photoname'.$i]))."', '".$code."', '".time()."')");
			}
		}
		header("Location: photo.php?act=album&aid=".$aid);
		exit;
	}
	header("Location: photo.php?act=albums");
	exit;
}
else exit;

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>