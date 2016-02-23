<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");
include_once("inc/constants.php");

function thumbnail($file, $koef, $quality=false)
{
	$file2 = ereg_replace(".jpg$", "s.jpg", $file);
	$sizes = getimagesize($file);
	$h = $sizes[1]/$koef;
	$w = $sizes[0]/$koef;
	$im = imagecreatefromjpeg($file);
	$im2 = imagecreatetruecolor($w, $h);
	imagecopyresampled($im2, $im, 0, 0, 0, 0, $w, $h, imagesx($im), imagesy($im));
	imagedestroy($im);
	if($quality === false) imagejpeg($im2, $file2);
	else imagejpeg($im2, $file2, $quality);
	imagedestroy($im2);
}

if(
$_GET['act'] != "edit" &&
$_GET['act'] != "save"
) exit;

$body = "";

if($_GET['act'] == "edit")
{
	if($_GET['pid'] == "")
	{
		header("Location: photo.php");
		exit;
	}
	$q1 = mysql_query("select id from albums where uid = ".$_COOKIE['mir_id']." and id = (select album from photos where id = ".$_GET['pid'].")");
	if(mysql_num_rows($q1) < 1)
	{
		header("Location: photo.php");
		exit;
	}
	$aid = mysql_result($q1, 0);
	$q2 = mysql_query("select code from photos where id = ".$_GET['pid']);
	$code = mysql_result($q2, 0);
	$body .= "
	<script language='javascript' type='text/javascript' src='inc/photoeditor.js'></script>
	<table align='center'>
	<tr><td>
	<div id='dscene' onmousedown='javascript: scene_mousedown();' onmouseup='javascript: scene_mouseup();' onmousemove='javascript: scene_mousemove();' >
	<img class='image_border' id='scene' src='photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg'>
	</div>
	<div id='qwe'></div></td></tr>";
	$body .= '
	<tr><td><br>
	<div class="start">
	<div class="start_form" style="width: 420px;">
	<div class="top_corners"><i>&nbsp;</i></div>
	<div class="start_form_pad"><form name="photoactionform" id="photoactionform" action="photoeditor.php?act=save" method="post">
	<input type="hidden" name="pid" value="'.$_GET['pid'].'">';
	$body .= "
	Редактировать: <select name='photoaction' onchange='javascript: photoactionchange(this);'>
	<option value='rotate'>повернуть
	<option value='blacknwhite'>ч/б режим
	<option value='negative'>негатив
	<option value='sepia'>сепия
	<option value='blur'>размыть
	<option value='brightness'>яркость
	<option value='contrast'>контраст
	<option value='delete'>удалить
	</select> 
	<select name='rotatevalue' id='rotatevalue'>
	<option value='90' selected>90&deg; против ч.с.
	<option value='180'>180&deg;
	<option value='-90'>90&deg; по ч.с.
	</select>
	<select name='brightnessvalue' id='brightnessvalue' style='display: none'>
	<option value='255'>больше";
	for($i=90; $i>=-90; $i-=10)
	{
		$val = $i;
		if($val > 0) $val = "+".$val;
		if($i != 0) $body .= "<option value='".round($i*255/100)."'>".$val;
		else $body .= "<option selected value='".$i."'>".$i;
	}
	$body .= "<option value='-255'>меньше
	</select>
	<select name='contrastvalue' id='contrastvalue' style='display: none'>
	<option value='-100'>больше";
	for($i=-90; $i<=90; $i+=10)
	{
		$val = -$i;
		if($val > 0) $val = "+".$val;
		if($i != 0) $body .= "<option value='".$i."'>".$val;
		else $body .= "<option selected value='".$i."'>".$i;
	}
	$body .= "<option value='100'>меньше
	</select><br>
	<input type='radio' checked name='target' value='copy'>Применить к копии<br>
	<input type='radio' name='target' value='self'>Применить к фотографии<br>
	<input type='submit' name='go' value='OK' onclick='javascript: 
	if(photoactionform.photoaction.value != \"negative\" && photoactionform.target.value == \"self\")
	{
		var a = confirm(\"Данное действие нельзя будет отменить. Продолжить?\");
		if(!a) return false;
	}
	'></form><br>
	<a href='#' onclick='javascript: action = \"selectarea\";'>Выделить область</a>
				</div>
			<div class='bottom_corners'><i>&nbsp;</i></div>
		</div>
	</div>
	</td></tr>
	</table>
	";
}
elseif($_GET['act'] == "save")
{
	if($_POST['pid'] == "")
	{
		header("Location: photo.php");
		exit;
	}
	$q1 = mysql_query("select id from albums where uid = ".$_COOKIE['mir_id']." and id = (select album from photos where id = ".$_POST['pid'].")");
	if(mysql_num_rows($q1) < 1)
	{
		header("Location: photo.php");
		exit;
	}
	$aid = mysql_result($q1, 0);
	$q2 = mysql_query("select code from photos where id = ".$_POST['pid']);
	$code = mysql_result($q2, 0);
	$path = "photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg";
	
	$ok = true;
	if($_POST['target'] == "copy")
	{
		$ar = range('a', 'z');
		shuffle($ar);
		$code2 = substr(implode("", $ar), rand(0, 19), 7);
		while(file_exists("photos/".$_COOKIE['mir_id']."/".$aid."/".$code2.".jpg"))
		{
			$code2 = substr(implode("", $ar), rand(0, 19), 7);
		}
		$path2 = "photos/".$_COOKIE['mir_id']."/".$aid."/".$code2.".jpg";
		if(copy($path, $path2))
		{
			$qin = mysql_query("insert into photos (album, code, addtime) values('".$aid."', '".$code2."', '".time()."')");
			$_POST['pid'] = mysql_insert_id($mysql);
			$code = $code2;
			$path = $path2;
			$ok = true;
		}
		else $ok = false;
	}
	
	if($ok)
	{
	if($_POST['photoaction'] == "blacknwhite")
	{
		$im = imagecreatefromjpeg($path);
		imagefilter($im, IMG_FILTER_GRAYSCALE);
		imagejpeg($im, $path, PHOTO_Q);
		thumbnail($path, PHOTOS_K, PHOTOS_Q);
	}
	elseif($_POST['photoaction'] == "negative")
	{
		$im = imagecreatefromjpeg($path);
		imagefilter($im, IMG_FILTER_NEGATE);
		imagejpeg($im, $path, PHOTO_Q);
		thumbnail($path, PHOTOS_K, PHOTOS_Q);
	}
	elseif($_POST['photoaction'] == "sepia")
	{
		$im = imagecreatefromjpeg($path);
		imagefilter($im, IMG_FILTER_GRAYSCALE);
		imagefilter($im, IMG_FILTER_COLORIZE, 90, 60, 40);
		imagejpeg($im, $path, PHOTO_Q);
		thumbnail($path, PHOTOS_K, PHOTOS_Q);
	}
	elseif($_POST['photoaction'] == "blur")
	{
		$im = imagecreatefromjpeg($path);
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		imagejpeg($im, $path, PHOTO_Q);
		thumbnail($path, PHOTOS_K, PHOTOS_Q);
	}
	elseif($_POST['photoaction'] == "rotate")
	{
		$im = imagecreatefromjpeg($path);
		$gray = imagecolorallocate($im, 200, 200, 200);
		$im = imagerotate($im, $_POST['rotatevalue'], $gray);
		if($_POST['rotatevalue'] != 180)
		{
			$ch = imagesy($im);
			$cw = imagesx($im);
			$hr = $ch/PHOTO_H;
			$wr = $cw/PHOTO_W;
			if($wr >= $hr)
			{
				$w = PHOTO_W;
				$h = $ch/$wr;
			}
			else
			{
				$h = PHOTO_H;
				$w = $cw/$hr;
			}
			$im2 = imagecreatetruecolor($w, $h);
			imagecopyresampled($im2, $im, 0, 0, 0, 0, $w, $h, $cw, $ch);
			$im = $im2;
		}
		imagejpeg($im, $path, PHOTO_Q);
		thumbnail($path, PHOTOS_K, PHOTOS_Q);
	}
	elseif($_POST['photoaction'] == "brightness")
	{
		$im = imagecreatefromjpeg($path);
		imagefilter($im, IMG_FILTER_BRIGHTNESS, $_POST['brightnessvalue']);
		imagejpeg($im, $path, PHOTO_Q);
		thumbnail($path, PHOTOS_K, PHOTOS_Q);
	}
	elseif($_POST['photoaction'] == "contrast")
	{
		$im = imagecreatefromjpeg($path);
		imagefilter($im, IMG_FILTER_CONTRAST, $_POST['contrastvalue']);
		imagejpeg($im, $path, PHOTO_Q);
		thumbnail($path, PHOTOS_K, PHOTOS_Q);
	}
	elseif($_POST['photoaction'] == "delete")
	{
		unlink("photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg");
		unlink("photos/".$_COOKIE['mir_id']."/".$aid."/".$code."s.jpg");
		$q3 = mysql_query("delete from photos where id = ".$_POST['pid']);
		header("Location: photo.php?act=album&aid=".$aid);
		exit;
	}
	header("Location: photoeditor.php?act=edit&pid=".$_POST['pid']);
	//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	//header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	//header("Cache-Control: no-store, no-cache, must-revalidate");
	//header("Cache-Control: post-check=0, pre-check=0", false);
	//header("Pragma: no-cache");
	exit;
	}
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>