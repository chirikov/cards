<?php

$to = "Роман Чириков <sokrat1988@mail.ru>";
$from = "Второй Мир <info@2ndworld.ru>";
$sub = "Регистрация в проекте Второй Мир";
$headers = "From: ".$from."\nMIME-Version: 1.0\nContent-type: text/html; charset=win-1251\n";

$body1 = "
<html>
<body>
<h3>Спасибо за регистрацию во <a href='http://localhost/vm/index.php'>Втором Мире</a>.</h3><br>
<br>
В этом письме содержится Ваш код активации.<br>
Введите его в <a href='http://localhost/vm/index.php?act=activate&email=sokrat1988@mail.ru'>форме активации</a> на сайте или просто перейдите по следующей ссылке:<br><br>
<a href='http://localhost/vm/registration.php?act=emailactdone&email=sokrat1988@mail.ru&actcode=123123'>http://localhost/vm/registration.php?act=emailactdone&email=sokrat1988@mail.ru&actcode=123123</a><br><br>
<i>С уважением,<br>
Второй Мир</i>
</body>
</html>
";

//$result = mail($to, $sub, $body1, $headers);
//if($result) print ":)";
?>