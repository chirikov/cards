<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head>
<body>
<table width="100%" height="100%"><tr><td valign="middle" align="center">
<?php
$n = rand(1, 10000);
$sites = file("10000.txt");
$site = explode(",", trim($sites[$n]));
print '<a href="http://'.$site[1].'" target="_blank"><img alt="http://'.$site[1].'" src="1http://www.webmorda.kz/site2img/?u='.$site[1].'&s=s" border=0></a>';
?>
</td></tr></table>
</body>
</html>