<?php

$imgfile = "testimg3.png";
$column = array();

$im = imagecreatefrompng($imgfile);

$w = imagesx($im);
$h = imagesy($im);

$pixels = 0;

for($j=0; $j<$w; $j++)
{
	for($i=0; $i<$h; $i++)
	{
		$rgb = imagecolorat($im, $j, $i);
		
		if($column[$i] < $rgb-1 || $column[$i] > $rgb+1) $pixels++;
		print $rgb."@";
		$column[$i] = $rgb;
	}
}

//($w*$h - $pixels)
print "Size 1 (delimiters): ".(($pixels*24 + $w*8)/8)." bytes; ".$pixels."<br>";
print "Size 2 (null bits): ".(($pixels*24 + ($w*$h - $pixels))/8)." bytes;<br>";


/*
$fp = fopen("image1.txt", "w");

for($i=0; $i<256; $i++)
{
	fwrite($fp, chr($i));
}

fclose($fp);
*/

?>