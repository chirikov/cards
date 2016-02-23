<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "password";
$main_email = "sokrat1988@mail.ru";

$mysql = @mysql_connect($dbhost, $dbuser, $dbpass);
@mysql_select_db("gos", $mysql);

return $mysql;
?>
