<?php
mb_internal_encoding("UTF-8");

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "password";
$main_email = "sokrat1988@mail.ru";

$mysql = @mysql_connect($dbhost, $dbuser, $dbpass);
@mysql_select_db("cards", $mysql);

return $mysql;
?>
