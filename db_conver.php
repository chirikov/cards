<?php

include_once("inc/my_connect.php");
//@mysql_select_db("cards", $mysql);

set_time_limit(0);

//$qn = mysql_query("select count(*) from kladr where 1");
//$num = mysql_result($qn, 0);
/*
$qs = mysql_query("select name, socr, code from kladr where 1");
while($row = mysql_fetch_assoc($qs))
{
	$qu = mysql_query("update kladr set name = '".iconv('windows-1251', 'UTF-8', $row['name'])."', socr = '".iconv('windows-1251', 'UTF-8', $row['socr'])."' where code = '".$row['code']."' limit 1");
}
*/

$qs = mysql_query("select name, socr, code from kladr where 1");
for($i = 158000; $i < 188244; $i++)
{
	$qu = mysql_query("update kladr set name = '".iconv('windows-1251', 'UTF-8', mysql_result($qs, $i, 'name'))."', socr = '".iconv('windows-1251', 'UTF-8', mysql_result($qs, $i, 'socr'))."' where code = '".mysql_result($qs, $i, 'code')."' limit 1");
	if(!$qu) print mysql_error();
}

?>