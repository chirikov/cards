<?php

set_time_limit(0);

for($m=4; $m<=5; $m++)
{
	for($d=1; $d<=31; $d++)
	{
		if(strlen($m) < 2) $mm = "0".$m;
		else $mm = $m;
		
		$nn = $mm;
		
		if(strlen($d) < 2) $nn .= "0".$d;
		else $nn .= $d;
		
		@copy("http://www.forexite.com/free_forex_quotes/2008/".$mm."/".$nn."08.zip", "kotir/".$nn."08.zip");
		print "<br>".$nn;
	}
}

?>