<?php

include_once("inc/my_connect.php");
@mysql_select_db("cards", $mysql);

//$file = file("SOCRBASE.csv");

set_time_limit(0);

for($i = 0; $i < count($file); $i+=1)//count($file)
{
	$ss1 = explode(";", iconv('UTF-8', 'windows-1251', $file[$i]));
	$ss2 = explode(";", iconv('UTF-8', 'windows-1251', $file[++$i]));
	$ss3 = explode(";", iconv('UTF-8', 'windows-1251', $file[++$i]));
	$ss4 = explode(";", iconv('UTF-8', 'windows-1251', $file[++$i]));
	$ss5 = explode(";", iconv('UTF-8', 'windows-1251', $file[++$i]));
	$ss6 = explode(";", iconv('UTF-8', 'windows-1251', $file[++$i]));
	$ss7 = explode(";", iconv('UTF-8', 'windows-1251', $file[++$i]));
	
	//$q = mysql_query("insert into `kladrw` values (".$ss1[0].", '".$ss1[1]."', '".$ss1[2]."', ".$ss1[3].", ".$ss1[4].", 0".$ss1[5].", ".$ss1[6].", 0".$ss1[7].")");
	/*
	$q = mysql_query("insert into `kladr` values 
	(".$ss1[0].", '".$ss1[1]."', '".$ss1[2]."', ".$ss1[3].", ".$ss1[4].", ".$ss1[5].", 0".$ss1[6].", 0".$ss1[7]."),
	(".$ss2[0].", '".$ss2[1]."', '".$ss2[2]."', ".$ss2[3].", ".$ss2[4].", ".$ss2[5].", 0".$ss2[6].", 0".$ss2[7]."),
	(".$ss3[0].", '".$ss3[1]."', '".$ss3[2]."', ".$ss3[3].", ".$ss3[4].", ".$ss3[5].", 0".$ss3[6].", 0".$ss3[7]."),
	(".$ss4[0].", '".$ss4[1]."', '".$ss4[2]."', ".$ss4[3].", ".$ss4[4].", ".$ss4[5].", 0".$ss4[6].", 0".$ss4[7]."),
	(".$ss5[0].", '".$ss5[1]."', '".$ss5[2]."', ".$ss5[3].", ".$ss5[4].", ".$ss5[5].", 0".$ss5[6].", 0".$ss5[7]."),
	(".$ss6[0].", '".$ss6[1]."', '".$ss6[2]."', ".$ss6[3].", ".$ss6[4].", ".$ss6[5].", 0".$ss6[6].", 0".$ss6[7]."),
	(".$ss7[0].", '".$ss7[1]."', '".$ss7[2]."', ".$ss7[3].", ".$ss7[4].", ".$ss7[5].", 0".$ss7[6].", 0".$ss7[7].")
	");
	if(mysql_error() != "") print "insert DELAYED into `kladrw` values 
	(".$ss1[0].", '".$ss1[1]."', '".$ss1[2]."', ".$ss1[3].", ".$ss1[4].", ".$ss1[5].", ".($ss1[6]!=''?$ss1[6]:0).", ".($ss1[7]!=''?$ss1[7]:0)."),
	(".$ss2[0].", '".$ss2[1]."', '".$ss2[2]."', ".$ss2[3].", ".$ss2[4].", ".$ss2[5].", ".($ss2[6]!=''?$ss2[6]:0).", ".($ss2[7]!=''?$ss2[7]:0)."),
	(".$ss3[0].", '".$ss3[1]."', '".$ss3[2]."', ".$ss3[3].", ".$ss3[4].", ".$ss3[5].", ".($ss3[6]!=''?$ss2[6]:0).", ".($ss3[7]!=''?$ss3[7]:0)."),
	(".$ss4[0].", '".$ss4[1]."', '".$ss4[2]."', ".$ss4[3].", ".$ss4[4].", ".$ss4[5].", ".($ss4[6]!=''?$ss2[6]:0).", ".($ss4[7]!=''?$ss4[7]:0)."),
	(".$ss5[0].", '".$ss5[1]."', '".$ss5[2]."', ".$ss5[3].", ".$ss5[4].", ".$ss5[5].", ".($ss5[6]!=''?$ss5[6]:0).", ".($ss5[7]!=''?$ss5[7]:0)."),
	(".$ss6[0].", '".$ss6[1]."', '".$ss6[2]."', ".$ss6[3].", ".$ss6[4].", ".$ss6[5].", ".($ss6[6]!=''?$ss6[6]:0).", ".($ss6[7]!=''?$ss6[7]:0)."),
	(".$ss7[0].", '".$ss7[1]."', '".$ss7[2]."', ".$ss7[3].", ".$ss7[4].", ".$ss7[5].", ".($ss7[6]!=''?$ss7[6]:0).", ".($ss7[7]!=''?$ss7[7]:0).")
	";
	*/
	/*
	$q = mysql_query("insert into `street` values 
	('".$ss1[0]."', '".$ss1[1]."', '".$ss1[2]."', '".$ss1[3]."'),
	('".$ss2[0]."', '".$ss2[1]."', '".$ss2[2]."', '".$ss2[3]."'),
	('".$ss3[0]."', '".$ss3[1]."', '".$ss3[2]."', '".$ss3[3]."'),
	('".$ss4[0]."', '".$ss4[1]."', '".$ss4[2]."', '".$ss4[3]."'),
	('".$ss5[0]."', '".$ss5[1]."', '".$ss5[2]."', '".$ss5[3]."'),
	('".$ss6[0]."', '".$ss6[1]."', '".$ss6[2]."', '".$ss6[3]."'),
	('".$ss7[0]."', '".$ss7[1]."', '".$ss7[2]."', '".$ss7[3]."')
	");
	if(mysql_error() != "") print "insert into `kladr` values 
	(".$ss1[0].", '".$ss1[1]."', '".$ss1[2]."', ".$ss1[3]."),
	(".$ss2[0].", '".$ss2[1]."', '".$ss2[2]."', ".$ss2[3]."),
	(".$ss3[0].", '".$ss3[1]."', '".$ss3[2]."', ".$ss3[3]."),
	(".$ss4[0].", '".$ss4[1]."', '".$ss4[2]."', ".$ss4[3]."),
	(".$ss5[0].", '".$ss5[1]."', '".$ss5[2]."', ".$ss5[3]."),
	(".$ss6[0].", '".$ss6[1]."', '".$ss6[2]."', ".$ss6[3]."),
	(".$ss7[0].", '".$ss7[1]."', '".$ss7[2]."', ".$ss7[3].")
	".mysql_error();
	*/
	/*
	$q = mysql_query("insert into `socrbase` values 
	('".$ss1[1]."', '".$ss1[2]."'),
	('".$ss2[1]."', '".$ss2[2]."'),
	('".$ss3[1]."', '".$ss3[2]."'),
	('".$ss4[1]."', '".$ss4[2]."'),
	('".$ss5[1]."', '".$ss5[2]."'),
	('".$ss6[1]."', '".$ss6[2]."'),
	('".$ss7[1]."', '".$ss7[2]."')
	");
	if(mysql_error() != "") print "insert into `kladr` values 
	(".$ss1[0].", '".$ss1[1]."', '".$ss1[2]."', ".$ss1[3]."),
	(".$ss2[0].", '".$ss2[1]."', '".$ss2[2]."', ".$ss2[3]."),
	(".$ss3[0].", '".$ss3[1]."', '".$ss3[2]."', ".$ss3[3]."),
	(".$ss4[0].", '".$ss4[1]."', '".$ss4[2]."', ".$ss4[3]."),
	(".$ss5[0].", '".$ss5[1]."', '".$ss5[2]."', ".$ss5[3]."),
	(".$ss6[0].", '".$ss6[1]."', '".$ss6[2]."', ".$ss6[3]."),
	(".$ss7[0].", '".$ss7[1]."', '".$ss7[2]."', ".$ss7[3].")
	".mysql_error();
	*/
}

?>