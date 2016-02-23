<?php

$dir = "kotir/";
//$opendir = opendir($dir);

//$files = array("050109.txt", "060109.txt", "070109.txt", "080109.txt", "090109.txt", "110109.txt", "120109.txt", "130109.txt", "140109.txt", "150109.txt", "160109.txt", "180109.txt", "190109.txt", "200109.txt", "210109.txt", "220109.txt", "230109.txt", "250109.txt", "260109.txt", "270109.txt", "280109.txt", "290109.txt", "300109.txt", "010209.txt", "020209.txt", "030209.txt", "040209.txt", "050209.txt", "060209.txt", "080209.txt", "090209.txt", "100209.txt", "110209.txt", "120209.txt", "130209.txt");

//$feu = fopen("EURUSD.txt", "w");
//$fgu = fopen("GBPUSD.txt", "w");
//$fuc = fopen("USDCHF.txt", "w");

//$w = 0;
//while(false !== ($file = readdir($opendir)))
//foreach($files as $file)
//{
	//if($file != "." && $file != "..")
	//{

$y = "08";
//$ii = 9;
for($m=9; $m<=12; $m++)
{
	if(strlen($m) < 2) $mm = "0".$m;
	else $mm = $m;
	
	$feu = fopen("EURUSD".$mm.$y.".txt", "w");
	$fgu = fopen("GBPUSD".$mm.$y.".txt", "w");
	$fuc = fopen("USDCHF".$mm.$y.".txt", "w");
	
	for($d=1; $d<=31; $d++)
	{
		
		if(strlen($d) < 2) $nn = "0".$d;
		else $nn = $d;
		$nn .= $mm;
		
		$file = $nn.$y.".txt";
		
		if(file_exists($dir.$file))
		{
			$strs = file($dir.$file);
			for($i=0; $i<count($strs); $i++)
			{
				if(substr($strs[$i], 0, 6) == "EURUSD") fwrite($feu, $strs[$i]);
				elseif(substr($strs[$i], 0, 6) == "GBPUSD") fwrite($fgu, $strs[$i]);
				elseif(substr($strs[$i], 0, 6) == "USDCHF") fwrite($fuc, $strs[$i]);
			}
		}
	}
	fclose($feu);
	fclose($fgu);
	fclose($fuc);
	//$ii++;
}

//fclose($feu);
//closedir($handle);

/*
set_time_limit(0);

for($m=1; $m<=12; $m++)
{
	for($d=1; $d<=31; $d++)
	{
		if(strlen($m) < 2) $nn = "0".$m;
		else $nn = $m;
		
		if(strlen($d) < 2) $nn .= "0".$d;
		else $nn .= $d;
		
		@copy("http://www.forexite.com/free_forex_quotes/2008/01/".$nn."08.zip", "kotir/".$nn."08.zip");
		print "<br>".$nn;
	}
}
*/
?>