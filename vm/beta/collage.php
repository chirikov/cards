<?php

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

?>