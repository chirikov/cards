<?php 
// определяем размер загружаемого файла и запоминаем значение в файле
$content_length = isset($_SERVER['CONTENT_LENGTH']) ? $_SERVER['CONTENT_LENGTH'] : 0;

$fp = fopen('filesize', 'w');
fwrite($fp, $content_length);
fclose($fp);

// move_uploaded_file() не используем, т.к. нужен сам факт загрузки файла на сервер
?>