<?php

$to = "����� ������� <sokrat1988@mail.ru>";
$from = "������ ��� <info@2ndworld.ru>";
$sub = "����������� � ������� ������ ���";
$headers = "From: ".$from."\nMIME-Version: 1.0\nContent-type: text/html; charset=win-1251\n";

$body1 = "
<html>
<body>
<h3>������� �� ����������� �� <a href='http://localhost/vm/index.php'>������ ����</a>.</h3><br>
<br>
� ���� ������ ���������� ��� ��� ���������.<br>
������� ��� � <a href='http://localhost/vm/index.php?act=activate&email=sokrat1988@mail.ru'>����� ���������</a> �� ����� ��� ������ ��������� �� ��������� ������:<br><br>
<a href='http://localhost/vm/registration.php?act=emailactdone&email=sokrat1988@mail.ru&actcode=123123'>http://localhost/vm/registration.php?act=emailactdone&email=sokrat1988@mail.ru&actcode=123123</a><br><br>
<i>� ���������,<br>
������ ���</i>
</body>
</html>
";

//$result = mail($to, $sub, $body1, $headers);
//if($result) print ":)";
?>