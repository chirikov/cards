<?php


$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=windows-1251\r\n";
$headers .= "From: tt@tt.ru\r\n";

if (mail("sokrat1988@mail.ru", "Тестовое сообщение", "Текст", $headers))
{
echo "Почтовый сервер работает правильно";
}

else
{
echo "Почтовый сервер не работает";
}


?>