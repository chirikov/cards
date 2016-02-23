<?php

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$seekat = $_GET["position"];
$file = "../movies/".$_GET["file"];

header("Content-Type: video/x-flv");
header('Content-Length: ' . filesize($file));

if($seekat != 0)
{
	print("FLV");
	print(pack('C', 1 ));
	print(pack('C', 1 ));
	print(pack('N', 9 ));
	print(pack('N', 9 ));
}
$fh = fopen($file, "rb") or exit("Could not open $file");
fseek($fh, $seekat);
while(!feof($fh))
{
	print (fread($fh, 16384)); 
}
fclose($fh);
?>