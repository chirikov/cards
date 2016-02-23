<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");
include_once("inc/constants.php");
include_once("inc/functions.php");

/*
function loadform()
{
	return "
	<table align='center'>
	<form action='javascript: loadedavatar();' method='post' id='favatar' name='favatar' enctype='multipart/form-data'>
	<input type='hidden' name='MAX_FILE_SIZE' value='2097152'>
	<tr><td><input type='file' name='photo'>(< 2Мб)</td></tr>
	<tr><td><input style='width: 100%;' type='submit' name='go' value='Загрузить'></td></tr>
	</form></table>";	
}
*/
function resize2($file, $nh, $nw, $koef, $quality=false, $qualitys=false)
{
	$file2 = ereg_replace(".jpg$", "s.jpg", $file);
	$sizes = getimagesize($file);
	$hr = $sizes[1]/$nh;
	$wr = $sizes[0]/$nw;
	if($wr >= $hr)
	{
		$w = $nw;
		$h = $sizes[1]/$wr;
	}
	else
	{
		$h = $nh;
		$w = $sizes[0]/$hr;
	}
	$im = imagecreatefromjpeg($file);
	$im2 = imagecreatetruecolor($w, $h);
	$im3 = imagecreatetruecolor($w/$koef, $h/$koef);
	imagecopyresampled($im2, $im, 0, 0, 0, 0, $w, $h, imagesx($im), imagesy($im));
	imagecopyresampled($im3, $im, 0, 0, 0, 0, $w/$koef, $h/$koef, imagesx($im), imagesy($im));
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
	if($file['size'] == 0) return "enofile";
	elseif($file['type'] != "image/jpeg" && $file['type'] != "image/pjpeg") return "etype";
	elseif($file['size'] > MAX_PHOTO_SIZE) return "esize";
	else return true;
}
function update_sequence($type, $folder, $album)
{
	if($type == "albums") $table = "albums";
	elseif($type == "folders") $table = "photo_folders";
	elseif($type == "photos") $table = "photos";
	
	$inj = "";
	if($folder != -1) $inj .= " and folder = ".$folder;
	if($album != -1) $inj .= " and album = ".$album;
	
	$q = mysql_query("select id from ".$table." where uid = ".$_COOKIE['mir_id'].$inj." order by sequence asc");
	$i = 1;
	while($row = mysql_fetch_assoc($q))
	{
		$q2 = mysql_query("update ".$table." set sequence = ".$i." where id = ".$row['id']);
		$i++;
	}
}
function delalbum($id)
{
	$q1 = mysql_query("select cover, folder from albums where id = ".$id." and uid = ".$_COOKIE['mir_id']);
	if(mysql_num_rows($q1) == 0) return false;
	else
	{
		$cover = mysql_result($q1, 0, 'cover');
		$folder = mysql_result($q1, 0, 'folder');
		
		$q2 = mysql_query("select code from photos where album = ".$id." and uid = ".$_COOKIE['mir_id']);
		while($row = mysql_fetch_assoc($q2))
		{
			unlink("photos/".$_COOKIE['mir_id']."/".$row['code'].".jpg");
			unlink("photos/".$_COOKIE['mir_id']."/".$row['code']."s.jpg");
			@unlink("photos/".$_COOKIE['mir_id']."/".$row['code']."o.jpg");
		}
		if($cover != "") unlink("photos/".$_COOKIE['mir_id']."/".$cover.".jpg");
		$q3 = mysql_query("delete from photos where album = ".$id." and uid = ".$_COOKIE['mir_id']);
		$q4 = mysql_query("delete from albums where id = ".$id." and uid = ".$_COOKIE['mir_id']);
		update_sequence("albums", $folder, -1);
		return true;
	}
}
function cover($aid)
{
	$q = mysql_query("select code from photos where album = ".$aid." order by sequence asc limit 3");
	
	if(mysql_num_rows($q) == 0)	return false;
	else
	{
		$w = PHOTO_W/PHOTOS_K;
		$h = PHOTO_H/PHOTOS_K;
		
		$koef = sqrt(pow($w, 2) + pow($h, 2))/$h + 0.1;
		$tx = $w-$w/$koef-5;
		$ty = $h-$h/$koef-5;
		$angle = 20;
		
		$col = imagecreatetruecolor($w, $h);
		$whitec = imagecolorallocate($col, 255, 255, 255);
		imagefill($col, 0, 0, $whitec);
		
		for($i=mysql_num_rows($q)-1; $i>=0; $i--)
		{
			$angle = (mysql_num_rows($q) - 1 - $i)*20;
			
			$im1 = imagecreatefromjpeg("photos/".$_COOKIE["mir_id"]."/".mysql_result($q, $i)."s.jpg");
			$white = imagecolorallocatealpha($im1, 255, 255, 255, 127);
			
			$im1x = imagesx($im1);
			$im1y = imagesy($im1);
			
			$im2 = imagecreatetruecolor($im1x/$koef, $im1y/$koef);
			imagecopyresampled($im2, $im1, 0, 0, 0, 0, $im1x/$koef, $im1y/$koef, $im1x, $im1y);
			$im2 = imagerotate($im2, $angle, $white);
			
			$im2x = imagesx($im2);
			$im2y = imagesy($im2);
			
			$dx = $tx-$im1y/$koef*sin(deg2rad($angle));
			$dy = $ty-$im1x/$koef*sin(deg2rad($angle))-$im1y/$koef*cos(deg2rad($angle))+$im1y/$koef;
			
			imagecopyresampled($col, $im2, $dx, $dy, 0, 0, $im2x, $im2y, $im2x, $im2y);
			imagedestroy($im1);
			imagedestroy($im2);
		}
		$ar = range('a', 'z');
		shuffle($ar);
		$code = substr(implode("", $ar), rand(0, 19), 7);
		while(file_exists("photos/".$_COOKIE["mir_id"]."/".$code.".jpg"))
		{
			$code = substr(implode("", $ar), rand(0, 19), 7);
		}
		
		$qoc = mysql_query("select cover from albums where id = ".$aid);
		if(mysql_result($qoc, 0) != "") unlink("photos/".$_COOKIE["mir_id"]."/".mysql_result($qoc, 0).".jpg");
		
		$qnc = mysql_query("update albums set cover = '".$code."' where id = ".$aid);
		imagejpeg($col, "photos/".$_COOKIE["mir_id"]."/".$code.".jpg", 90);
	}
}

if(
$_GET['act'] != "default" &&
$_GET['act'] != "loadavatar" &&
$_GET['act'] != "loadedavatar" &&
//$_GET['act'] != "albums" &&
$_GET['act'] != "editfoldername" &&
$_GET['act'] != "editalbumname" &&
$_GET['act'] != "editphotoname" &&
$_GET['act'] != "newalbum" &&
$_GET['act'] != "newfolder" &&
//$_GET['act'] != "newedalbum" &&
$_GET['act'] != "view" &&
$_GET['act'] != "delfolder" &&
$_GET['act'] != "delalbum" &&
$_GET['act'] != "delphoto" &&
//$_GET['act'] != "addphoto" &&
$_GET['act'] != "addphoto"
//$_GET['act'] != "photoaction" &&
//$_GET['act'] != "album"
) $_GET['act'] = "default";

$body = "";

if($_GET['act'] == "loadavatar")
{
	$body .= '
	<div class="start">
		<div class="start_form">
			<div class="top_corners"><i>&nbsp;</i></div>
			<h2>Загрузка фотографии</h2>';
	if($_GET['result'])
	{
		switch ($_GET['result'])
		{
			case "enofile":	$estr = "Указанный файл не существует."; break;
			case "etype":	$estr = "Неподдерживаемый тип файла. Укажите JPEG файл."; break;
			case "esize":	$estr = "Слишком большой файл. Укажите файл размером до 2-х мегабайт."; break;
		}
		$body .= '<center><table border="1" width="100%" style="border-width: 2px; border-color: red;" id="tableresult"><tr><td style="padding: 5px;" id="tdresult">'.$estr.'</td></tr></table><br><br></center>';
	}
			$body .= '
			<div class="start_form_pad"><form action="photo.php?act=loadedavatar" method="post" id="favatar" name="favatar" enctype="multipart/form-data">
			<input type="hidden" name="MAX_FILE_SIZE" value='.MAX_PHOTO_SIZE.'>
				<center><table>
					<tr>
						<td><input type="file" name="photo">(< 8 Мб)</td>
					</tr>
					<tr>
						<td colspan=2 align=center><input style="width: 100%;" type="submit" name="go" value="Загрузить" onclick="javascript: document.getElementById(\'wait\').style.display = \'\';"> <div id="wait" style="display: none;">Подождите...</div></td>
					</tr>
				</table></center></form>
			</div>
			<div class="bottom_corners"><i>&nbsp;</i></div>
		</div>
	</div>';
}
elseif($_GET['act'] == "loadedavatar")
{
	$check = check_image_file($_FILES['photo']);
	if($check !== true) $result = $check;
	else
	{
		$q1 = mysql_query("select avatar from users where id = ".$_COOKIE['mir_id']);
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
		resize2("photos/".$_COOKIE['mir_id']."/".$code.".jpg", AVATAR_H, AVATAR_W, AVATARS_K, AVATAR_Q, AVATARS_Q);
		$q = mysql_query("update users set avatar = '".$code."' where id = ".$_COOKIE['mir_id']);
		header("Location: profile.php");
		exit;
	}
	header("Location: photo.php?act=loadavatar&result=".$result);
	exit;
}
elseif($_GET['act'] == "default")
{
	$body .= '
	<script language="javascript" type="text/javascript" src="inc/windows.js"></script>
	<script language="javascript" type="text/javascript" src="inc/photo.js"></script>';
	
	if($_GET['uid'] < 1) $_GET['uid'] = $_COOKIE['mir_id'];
	if($_GET['uid'] == $_COOKIE['mir_id']) $me = true;
	else $me = false;
	
	$qsn = mysql_query("select surname, name from users where id = ".$_COOKIE['mir_id']);
	$sn = mysql_fetch_assoc($qsn);
	
	if($me) $body .= '<div class="path">Ваши фотографии</div>';
	else $body .= '<div class="path"><a href="profile.php?uid='.$_GET['uid'].'">'.$sn['name'].' '.$sn['surname'].'</a> » Фотографии</div>';
	
	if($me) $body .= '<div class="actions">
	<a href="javascript: upload();">Загрузить фотографии</a>
	<a href="javascript: newalbum();">Создать альбом</a>
	<a href="javascript: newfolder();">Создать папку</a>
	</div>';
	
	$qphotos = mysql_query("select * from photos where uid = ".$_COOKIE['mir_id']);
	$qfolders = mysql_query("select * from photo_folders where uid = ".$_COOKIE['mir_id']);
	$qalbums = mysql_query("select * from albums where uid = ".$_COOKIE['mir_id']);
	
	$num_folders = mysql_num_rows($qfolders);
	$num_albums = mysql_num_rows($qalbums);
	
	if(mysql_num_rows($qphotos) + $num_folders + $num_albums == 0)
	{
		$body .= '
		Добро пожаловать в раздел "Фотографии". Здесь вы можете загружать свои фотографии, создавать альбомы и объединять их в папки и т.д.
		';
	}
	else
	{
		$body .= '<div id="collection1" class="collection"><div class="left"><table>';
		$i=1;
		while($folder = mysql_fetch_assoc($qfolders))
		{
			if($i % 4 == 1) $body .= '<tr>';
			$body .= "<td onclick='javascript: tdclick(\"folder\", 1);'><img src='images/empty_folder.jpg' alt=''><div class='image_name'>".$folder['name']."</div></td>";
			if($i % 4 == 0) $body .= '</tr>';
			$i++;
		}
		while($album = mysql_fetch_assoc($qalbums))
		{
			if($album['folder'] == 0)
			{
				if($i % 4 == 1) $body .= '<tr>';
				if($album['cover'] != "") $img = "<img src='photos/".$_GET['uid']."/".$album['cover'].".jpg' alt=''>";
				else $img = "<img src='images/empty_album.jpg' alt=''>";
				$body .= "<td onclick='javascript: tdclick(\"album\", 1);'>".$img."<div class='image_name'>".$album['name']."</div></td>";
				if($i % 4 == 0) $body .= '</tr>';
				$i++;
			}
		}
		while($photo = mysql_fetch_assoc($qphotos))
		{
			if($photo['folder'] == 0 && $photo['album'] == 0)
			{
				if($i % 4 == 1) $body .= '<tr>';
				$body .= "<td onclick='javascript: tdclick(\"photo\", 1);'><img src='photos/".$_GET['uid']."/".$photo['code']."s.jpg' alt=''><div id='photoname".$photo['id']."' class='image_name'>".$photo['name']."</div></td>";
				if($i % 4 == 0) $body .= '</tr>';
				$i++;
			}
		}
		if($i % 4 != 0) $body .= '</tr>';
		$body .= '</table></div>
		<div class="right">Действия: <div id="menu1"></div></div>
		</div>
		<div id="collection2" class="collection" style="display: none">
			<div class="left"></div>
			<div class="right">Действия: <div id="menu2"></div></div>
		</div>
		<div id="collection3" class="collection" style="display: none">
			<div class="left"></div>
			<div class="right">Действия: <div id="menu3"></div></div>
		</div>';
	}
}
/*
elseif($_GET['act'] == "albums")
{
	if($_GET['uid'] < 1) $_GET['uid'] = $_COOKIE['mir_id'];
	$q0 = mysql_query("select name, surname from users where id = ".$_GET['uid']);
	$row0 = mysql_fetch_assoc($q0);
	if($_GET['uid'] != $_COOKIE['mir_id']) $body .= "<div class='path'><a href='profile.php?act=view&uid=".$_GET['uid']."'>".$row0['name']." ".$row0['surname']."</a> » <a href='#'>Альбомы</a></div>";
	$q1 = mysql_query("select id, name, addtime from albums where uid = ".$_GET['uid']." order by addtime desc");
	
	$qtimezone = mysql_query("select timezone from cities where id = (select city from users where id = ".$_COOKIE['mir_id'].")");
	$timezone = mysql_result($qtimezone, 0);
	
	$body .= "
	<script language='javascript' type='text/javascript'>
	<!--//
	function editalbname(id, name, event)
	{
		if(event.ctrlKey) {
			event.returnValue = false; 
			document.getElementById('albumname'+id).innerHTML = '<input type=\"input\" id=\"inpalbname'+id+'\" onkeypress=\"javascript: if(event.keyCode == 13) sendalbname('+id+', this.value);\"  size=32 maxlength=100>';
			document.getElementById('inpalbname'+id).value = name;
		}
	}
	function sendalbname(id, name)
	{
		location = 'photo.php?act=editalbumname&aid='+id+'&name='+encodeURIComponent(name);
	}
	//-->
	</script>
	<div class='albums'>
	<table>";
	if($_GET['uid'] == $_COOKIE['mir_id'])
	{
		$body .= "
		<tr>
		<td onmouseover='javascript: this.style.backgroundColor = \"#fffebf\";' onmouseout='javascript: this.style.backgroundColor = \"transparent\";'>
			<table style='width: ". (2*PHOTO_W/PHOTOS_K+6) ."px'>
			<tr><td style='height: ". (2*PHOTO_H/PHOTOS_K+6) ."px;' class='images'>
			<a href='photo.php?act=newalbum'><img src='images/greenplus.gif' alt='Новый альбом'></a>
			</td></tr>
			<tr><td class='underalbum'>
			<a href='photo.php?act=newalbum'><b>Создать альбом</b></a>
			</td></tr></table>
		</td>";
		$al = 2;
	}
	else $al = 1;
	while($row = mysql_fetch_assoc($q1))
	{
		if($al % 2 == 1) $body .= "<tr>";
		$q2 = mysql_query("select COUNT(*) from photos where album = ".$row['id']);
		$pn = mysql_result($q2, 0);
		if($pn > 0)
		{
			$q3 = mysql_query("select code from photos where album = ".$row['id']." order by addtime desc limit 4");
		}
		$body .= "<td onmouseover='javascript: this.style.backgroundColor = \"#fffebf\";' onmouseout='javascript: this.style.backgroundColor = \"transparent\";'>
		<table style='width: ". (2*PHOTO_W/PHOTOS_K+6) ."px'>
		<tr><td onclick='javascript: location = \"photo.php?act=album&aid=".$row['id']."\";' style='height: ". (2*PHOTO_H/PHOTOS_K) ."px;' class='images'>";
		if($pn == 0)
		{
			$body .= "(нет фотографий)";
		}
		else
		{
			$body .= "<table>";
			for($p=1; $p<=min(4, $pn); $p++)
			{
				if($p == 1 || $p == 3) $body .= "<tr><td class='oneimage'><img src='photos/".$_GET['uid']."/".$row['id']."/".mysql_result($q3, $p-1, 'code')."s.jpg'></td>";
				if($p == 2 || $p == 4) $body .= "<td class='oneimage'><img src='photos/".$_GET['uid']."/".$row['id']."/".mysql_result($q3, $p-1, 'code')."s.jpg'></td>";
			}
			$body .= "</table>";
		}
		$body .= "</td></tr><tr><td class='underalbum'>
			<div id='albumname".$row['id']."'><b><a href='photo.php?act=album&aid=".$row['id']."' ";
		if($_GET['uid'] == $_COOKIE['mir_id']) $body .= "onclick='javascript: editalbname(".$row['id'].", this.innerHTML, event);' title='Чтобы изменить название, нажмите на ссылку, удерживая нажатой клавишу Ctrl. Для сохранения нажмите Enter.' ";
		$body .= ">".$row['name']."</a></b></div><br><br>
			Добавлен: ".rusdate("@j@&nbsp;@month_rod@&nbsp;@Y@", $row['addtime'], $timezone, true)."<br>
			фотографий: ".$pn;
		if($_GET['uid'] == $_COOKIE['mir_id'])
		{
			$body .= "<br><br><a class='to_add' href='photo.php?act=addphoto&aid=".$row['id']."'>Пополнить</a>
			<a class='to_del' href='photo.php?act=delalbum&aid=".$row['id']."' onclick='javascript:
			var a = confirm(\"Удалить альбом?\");
			if(!a) return false;
			'>Удалить</a>";
		}
		$body .= "</td></tr></table>";
		$body .= "</td>";
		if($al % 2 == 0) $body .= "</tr>";
		$al++;
	}
	$body .= "</table></div>";
}
*/
elseif($_GET['act'] == "editfoldername")
{
	$name = iconv('UTF-8', 'windows-1251', trim($_GET['name']));
	if($name == "") $result = "ename";
	else
	{
		$q2 = mysql_query("update photo_folders set name = '".htmlspecialchars($name)."' where id = ".$_GET['id']." and uid = ".$_COOKIE['mir_id']);
		$result = "ok";
	}
	print $result;
	exit;
}
elseif($_GET['act'] == "editalbumname")
{
	$name = iconv('UTF-8', 'windows-1251', trim($_GET['name']));
	if($name == "") $result = "ename";
	else
	{
		$q2 = mysql_query("update albums set name = '".htmlspecialchars($name)."' where id = ".$_GET['id']." and uid = ".$_COOKIE['mir_id']);
		$result = "ok";
	}
	print $result;
	exit;
}
elseif($_GET['act'] == "editphotoname")
{
	$name = iconv('UTF-8', 'windows-1251', trim($_GET['name']));
	if($name == "") $result = "ename";
	else
	{
		$q2 = mysql_query("update photos set name = '".htmlspecialchars($name)."' where id = ".$_GET['id']." and uid = ".$_COOKIE['mir_id']);
		$result = "ok";
	}
	print $result;
	exit;
}
elseif($_GET['act'] == "newfolder")
{
	$name = iconv('UTF-8', 'windows-1251', trim($_GET['name']));
	if($name == "") $result = "ename";
	else
	{
		$qs = mysql_query("select MAX(sequence) from photo_folders where uid = ".$_COOKIE['mir_id']);
		$q = mysql_query("insert into photo_folders (uid, name, sequence) values (".$_COOKIE['mir_id'].", '".htmlspecialchars($name)."', ".(mysql_result($qs, 0)+1).")");
		$result = "ok";
	}
	print $result;
	exit;
}
elseif($_GET['act'] == "newalbum")
{
	$name = iconv('UTF-8', 'windows-1251', trim($_GET['name']));
	if($name == "") $result = "ename";
	else
	{
		$qs = mysql_query("select MAX(sequence) from albums where uid = ".$_COOKIE['mir_id']." and folder = 0");
		$q = mysql_query("insert into albums (uid, name, sequence) values (".$_COOKIE['mir_id'].", '".htmlspecialchars($name)."', ".(mysql_result($qs, 0)+1).")");
		$result = "ok";
	}
	print $result;
	exit;
	
	
	/*
	$body .= "
	<script language='javascript' type='text/javascript'>
	<!--//
	var num = 1;
	
	function addphotofield()
	{
		num++;
		var row = document.getElementById('photofields').insertRow(document.getElementById('photofields').rows.length - 1);
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(-1);
		cell2.style.padding = '10px';
		cell2.innerHTML = '<input type=\"file\" name=\"photo'+num+'\"><input style=\"margin-top: 5px; width: 200px\" type=\"text\" name=\"photoname'+num+'\">';
		document.getElementById('f1').photonum.value = num;
	}
	//-->
	</script>";
	$body .= '
	<div class="start">
		<div class="start_form">
			<div class="top_corners"><i>&nbsp;</i></div>
			<h2>Новый альбом</h2>';
	if($_GET['result'])
	{
		switch ($_GET['result'])
		{
			case "enoname":	$estr = "Вы не указали название альбома."; break;
		}
		$body .= '<center><table border="1" width="100%" style="border-width: 2px; border-color: red;" id="tableresult"><tr><td style="padding: 5px;" id="tdresult">'.$estr.'</td></tr></table><br><br></center>';
	}
			$body .= '
			<div class="start_form_pad"><form method="POST" action="photo.php?act=newedalbum" id="f1" name="f1" enctype="multipart/form-data">
			<input type="hidden" name="MAX_FILE_SIZE" value='.MAX_PHOTO_SIZE.'>
			<input type="hidden" name="photonum" value="1">
				<table style="margin: auto" id="photofields">
					<tr>
						<td style="padding: 10px"><label>Название альбома:</label></td>
						<td style="padding: 10px"><input type="Text" name="name" maxlength="100"/></td>
					</tr>
					<tr>
						<td style="padding: 10px"><label>Загрузить фотографию:</label></td>
						<td style="padding: 10px"><input type="file" name="photo1"><input style="margin-top: 5px; width: 200px" type="text" name="photoname1" value="Заголовок" onclick="javascript: if(this.value == \'Заголовок\') this.value = \'\';"></td>
					</tr>
					<tr>
						<td></td>
						<td style="padding: 10px"><a href="#" onclick="javascript: addphotofield();">добавить поле</a></td>
					</tr>
				</table></form>
				<div class="enter"><a href="#" onclick="javascript: document.getElementById(\'wait\').style.display = \'\'; f1.submit();">Создать</a></div> <div class="other_computer" id="wait" style="display: none;">Подождите...</div>
			</div>
			<div class="bottom_corners"><i>&nbsp;</i></div>
		</div>
	</div>
	';
	*/
}
/*
elseif($_GET['act'] == "newedalbum")
{
	$name = trim($_POST['name']);
	if(strlen($name) < 1)
	{
		$result = "enoname";
	}
	else
	{
		$q1 = mysql_query("insert into albums (uid, name, addtime) values ('".$_COOKIE['mir_id']."', '".htmlspecialchars($name)."', '".time()."')");
		$aid = mysql_insert_id($mysql);
		mkdir("photos/".$_COOKIE['mir_id']."/".$aid, 0766);
		for($i=1; $i<=$_POST['photonum']; $i++)
		{
			if(check_image_file($_FILES['photo'.$i]) === true)
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
	header("Location: photo.php?act=newalbum&result=".$result);
	exit;
}
elseif($_GET['act'] == "addphoto")
{
	if($_GET['aid'] < 1)
	{
		header("Location: photo.php?act=albums");
		exit;
	}
	$q1 = mysql_query("select id from albums where id = ".$_GET['aid']." && uid = ".$_COOKIE['mir_id']);
	if(mysql_num_rows($q1) < 1)
	{
		header("Location: profile.php");
		exit;
	}
	else
	{
		$body .= "
		<script language='javascript' type='text/javascript'>
		<!--//
		var num = 1;
		
		function addphotofield()
		{
			num++;
			var row = document.getElementById('photofields').insertRow(document.getElementById('photofields').rows.length - 1);
			var cell1 = row.insertCell(-1);
			var cell2 = row.insertCell(-1);
			cell2.style.padding = '10px';
			cell2.innerHTML = '<input type=\"file\" name=\"photo'+num+'\"><input style=\"margin-top: 5px; width: 200px\" type=\"text\" name=\"photoname'+num+'\">';
			document.getElementById('f1').photonum.value = num;
		}
		//-->
		</script>";
		$body .= '
		<div class="start">
		<div class="start_form">
			<div class="top_corners"><i>&nbsp;</i></div>
			<h2>Загрузка фотографий</h2>
			<div class="start_form_pad"><form method="POST" action="photo.php?act=addedphoto" id="f1" name="f1" enctype="multipart/form-data">
			<input type="hidden" name="aid" value="'.$_GET['aid'].'">
			<input type="hidden" name="MAX_FILE_SIZE" value='.MAX_PHOTO_SIZE.'>
			<input type="hidden" name="photonum" value="1">
				<table style="margin: auto" id="photofields">
					<tr>
						<td style="padding: 10px"><label>Загрузить фотографию:</label></td>
						<td style="padding: 10px"><input type="file" name="photo1"><input style="margin-top: 5px; width: 200px" type="text" name="photoname1" value="Заголовок" onclick="javascript: if(this.value == \'Заголовок\') this.value = \'\';"></td>
					</tr>
					<tr>
						<td></td>
						<td style="padding: 10px"><a href="#" onclick="javascript: addphotofield();">добавить поле</a></td>
					</tr>
				</table></form>
			<div class="enter"><a href="#" onclick="javascript: document.getElementById(\'wait\').style.display = \'\'; f1.submit();">Загрузить</a></div> <div class="other_computer" id="wait" style="display: none;">Подождите...</div>
			</div>
			<div class="bottom_corners"><i>&nbsp;</i></div>
		</div>
	</div>';
	}
}
*/
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
elseif($_GET['act'] == "album")
{
	if($_GET['aid'] < 1)
	{
		$q1 = mysql_query("select id from albums where uid = ".$_COOKIE['mir_id']." limit 1");
		$_GET['aid'] = mysql_result($q1, 0);
	}
	
	$q2 = mysql_query("select uid, name from albums where id = ".$_GET['aid']);
	$uid = mysql_result($q2, 0, 'uid');
	$q0 = mysql_query("select name, surname from users where id = ".$uid);
	$row0 = mysql_fetch_assoc($q0);
	
	if($_COOKIE['mir_id'] == $uid) $me = true;
	else $me = false;
	
	if($me) $body .= "<div class='path'><a href='photo.php?act=albums'>Альбомы</a> » ".mysql_result($q2, 0, 'name')."</div>";
	else $body .= "<div class='path'><a href='profile.php?uid=".$uid."'>".$row0['name']." ".$row0['surname']."</a> » <a href='photo.php?act=albums&uid=".$uid."'>Альбомы</a> » ".mysql_result($q2, 0, 'name')."</div>";
	
	$q3 = mysql_query("select id, name, code from photos where album = ".$_GET['aid']);
	if(mysql_num_rows($q3) < 1)
	{
		$body .= "В этом альбоме нет фотографий.";
	}
	else
	{
		$body .= "<div class='albums'><table align='center'>";
		$c = 1;
		while($row = mysql_fetch_assoc($q3))
		{
			if($c == 6) $c = 1;
			if($c == 1) $body .= "<tr>";
			$body .= "<td align='center' class='photos' onmouseover='javascript: this.style.backgroundColor = \"#fffebf\";' onmouseout='javascript: this.style.backgroundColor = \"transparent\";'>
			<a href='photo.php?act=view&pid=".$row['id']."'><img src='photos/".$uid."/".$_GET['aid']."/".$row['code']."s.jpg' title='".$row['name']."'></a></td>";
			if($c == 5) $body .= "</tr>";
			$c++;
		}
		$body .= "</table></div>";
	}
}
elseif($_GET['act'] == "view")
{
	if($_GET['pid'] < 1)
	{
		$q2 = mysql_query("select id from photos where album = (select id from albums where uid = ".$_COOKIE['mir_id']." limit 1) limit 1");
		$_GET['pid'] = mysql_result($q2);
	}
	$q4 = mysql_query("select album, name, code, addtime from photos where id = ".$_GET['pid']);
	$row = mysql_fetch_assoc($q4);
	$q3 = mysql_query("select uid, name from albums where id = ".$row['album']);
	$row3 = mysql_fetch_assoc($q3);
	$q0 = mysql_query("select name, surname from users where id = ".$row3['uid']);
	$row0 = mysql_fetch_assoc($q0);
	$q5 = mysql_query("select id, name, code from photos where album = ".$row['album']);
	$pnum = mysql_num_rows($q5);
	$body .= "
	<script language='javascript' type='text/javascript' src='inc/photo_view.js'></script>
	<script language='javascript' type='text/javascript'>
	<!--//
	pnum = ".$pnum.";
	";
	if($row3['uid'] == $_COOKIE['mir_id']) {$me = true; $body .= "me = true;";}
	else $me = false;
	
	$i=0;
	while($photo = mysql_fetch_assoc($q5))
	{
		$body .= "photos[".$i."] = 'photos/".$row3['uid']."/".$row['album']."/".$photo['code'].".jpg';";
		$i++;
	}
	mysql_data_seek($q5, 0); 
	
	$body .= "
	prefix = 'photos/".$row3['uid']."/".$row['album']."/';
	toset = 'photos/".$row3['uid']."/".$row['album']."/".$row['code'].".jpg';
	//-->
	</script>";
	if($me) $body .= "<div class='path'><a href='photo.php?act=albums&uid=".$row3['uid']."'>Альбомы</a> » <a href='photo.php?act=album&aid=".$row['album']."'>".$row3['name']."</a></div>";
	else $body .= "<div class='path'><a href='profile.php?act=view&uid=".$row3['uid']."'>".$row0['name']." ".$row0['surname']."</a> » <a href='photo.php?act=albums&uid=".$row3['uid']."'>Альбомы</a> » <a href='photo.php?act=album&aid=".$row['album']."'>".$row3['name']."</a></div>";
	$body .= "<table align='center'>
	<tr><td align='center' class='image_border' style='height: ".(PHOTO_H)."px; width: ".(PHOTO_W)."px'><img style='opacity: 1; filter:progid:DXImageTransform.Microsoft.Alpha(opacity=100)' id='scene' src='photos/".$row3['uid']."/".$row['album']."/".$row['code'].".jpg'></td></tr>
	<tr><td style='height: 25px'><div id='divphotoname'".($row['name']==''?" style='padding: 5px; display: none'":" style='padding: 5px'")."><span style='color: #999'>Заголовок:</span> <span id='spanphotoname'>".$row['name']."</span></div></td></tr>
	<tr><td align='center'><div style='position: relative; top: -210px; visibility: hidden; font-size: 16px' id='divwait'>Изображение загружается...</div></td></tr>
	<tr><td align='right'><input type='checkbox' id='ppreload' onclick='javascript: preload();'> Быстрый просмотр<br><br></td></tr>
	</td></tr>";
	if($row3['uid'] == $_COOKIE['mir_id'])
	{
		$body .= '
		<tr><td><br>
		<div class="start">
		<div class="start_form" style="width: 420px;">
		<div class="top_corners"><i>&nbsp;</i></div>
		<div class="start_form_pad">';
		$body .= "
		<a id='edithref' href='photoeditor.php?act=edit&pid=".$_GET['pid']."'>Редактор</a> <a id='maphref' href='map.php?act=mapphoto&pid=".$_GET['pid']."'>Отметить на карте</a>
					</div>
				<div class='bottom_corners'><i>&nbsp;</i></div>
			</div>
		</div>
		</td></tr>
		";
	}
	$body .= '<tr><td align="center">Другие фотографии из альбома:<br>
	<div class="pager"><div class="image_border"><table width="100%"><tr>
	<td><a onclick="javascript: return false;" id="imgl2" href="#" onmousedown="javascript: StartScroll(-1);" onmouseup="javascript: StopScroll();" class="arrow1">&nbsp;</a></td>
	<td>
	<div id="photoline" style="overflow:hidden; width: 450px;">
	<table border=0 cellspacing=0 cellpadding=0>
		<tr>';
			$i=1;
			while($photo = mysql_fetch_assoc($q5))
			{
				$body .= '<td><a href="#" onclick="javascript: imgclick(\''.$photo['code'].'\', '.$photo['id'].', '.$i.'); return false;"><img id="thumb'.$i.'"';
				if($photo['id'] != $_GET['pid']) $body .= ' style="opacity: 0.6; filter:progid:DXImageTransform.Microsoft.Alpha(opacity=60)"';
				else $body .= ' style="opacity: 1; filter:progid:DXImageTransform.Microsoft.Alpha(opacity=100)"';
				$body .= ' title="'.$photo['name'].'" src="photos/'.$row3['uid'].'/'.$row['album'].'/'.$photo['code'].'s.jpg"></a></td>';
				$i++;
			}
		$body .= '</tr>
	</table>
	</div>
	</td>
	<td><a onclick="javascript: return false;" id="imgr2" href="#" onmousedown="javascript: StartScroll(1);" onmouseup="javascript: StopScroll();" class="arrow2">&nbsp;</a></td></tr></table></div></div>
	</td></tr></table>
	';
}
elseif($_GET['act'] == "delalbum")
{
	if(delalbum($_GET['id'])) $result = "ok";
	else $result = "error";
	print $result;
	exit;
}
elseif($_GET['act'] == "delfolder")
{
	$q1 = mysql_query("select id from photo_folders where id = ".$_GET['id']." and uid = ".$_COOKIE['mir_id']);
	if(mysql_num_rows($q1) == 0) $result = "error";
	else
	{
		$q2 = mysql_query("select code from photos where album = 0 and folder = ".$_GET['id']." and uid = ".$_COOKIE['mir_id']);
		while($row = mysql_fetch_assoc($q2))
		{
			unlink("photos/".$_COOKIE['mir_id']."/".$row['code'].".jpg");
			unlink("photos/".$_COOKIE['mir_id']."/".$row['code']."s.jpg");
			@unlink("photos/".$_COOKIE['mir_id']."/".$row['code']."o.jpg");
		}
		$q3 = mysql_query("delete from photos where album = 0 and folder = ".$_GET['id']." and uid = ".$_COOKIE['mir_id']);
		
		$q4 = mysql_query("select id from albums where folder = ".$_GET['id']." and uid = ".$_COOKIE['mir_id']);
		while($row2 = mysql_fetch_assoc($q4))
		{
			delalbum($row2['id']);
		}
		
		$q5 = mysql_query("delete from photo_folders where id = ".$_GET['id']." and uid = ".$_COOKIE['mir_id']);
		update_sequence("folders", -1, -1);
		$result = "ok";
	}
	print $result;
	exit;
}
elseif($_GET['act'] == "delphoto")
{
	$q1 = mysql_query("select code, folder, album from photos where id = ".$_GET['id']." and uid = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($q1);
	if(mysql_num_rows($q1) == 0) $result = "error";
	else
	{
		unlink("photos/".$_COOKIE['mir_id']."/".$row['code'].".jpg");
		unlink("photos/".$_COOKIE['mir_id']."/".$row['code']."s.jpg");
		@unlink("photos/".$_COOKIE['mir_id']."/".$row['code']."o.jpg");
		
		$q5 = mysql_query("delete from photos where id = ".$_GET['id']." and uid = ".$_COOKIE['mir_id']);
		update_sequence("photos", $row['folder'], $row['album']);
		$result = "ok";
	}
	print $result;
	exit;
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>