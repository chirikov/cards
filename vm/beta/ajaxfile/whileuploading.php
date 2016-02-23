<?php
session_start();

// определяем временную директорию
$tmp_dir = isset($_SESSION['upload_tmp_dir']) ? $_SESSION['upload_tmp_dir'] : ini_get('upload_tmp_dir');

// общий размер загружаемого файла на сервер
$content_length = file_get_contents('filesize');

// очищаем кэш состояния файлов
clearstatcache();

$tmp_file = '';

if (isset($_SESSION['tmp_file']) && is_file($_SESSION['tmp_file']))
{
    $tmp_file = $_SESSION['tmp_file'];
}
else 
{
    $tmp_file = findTemporaryFile($tmp_dir, '/[p][h][p]*');
}

// определяем размер временного файла и формируем вывод
if (false===($output = filesize($tmp_file)))
{
	$humanFileSize = 'undefined';
	$output = 0;
}
else 
{
    $humanFileSize = humanFileSize($output);
}

$output = $humanFileSize.'|'.$output.'|'.$content_length;

header('Content-Length: '.strlen($output));
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

echo $output;

/** 
 * Ищет загружаемый файл во временной папке.
 * Возвращает имя временного файла в случае успеха.
 *
 * @param   string      $tmp_dir        имя временной директории, в которой ищется файл
 * @param   string      $pattern        шаблон, по которому ищутся файлы
 *
 * @return  boolean
 */
function findTemporaryFile($tmp_dir, $pattern)
{
    $found = false;
    
	if (is_dir($tmp_dir)) 
	{
        $phptempfiles = glob($tmp_dir.$pattern);
        
		if (count($phptempfiles)==1) 
		{
			$found = $phptempfiles[0];
		}
	}
    
	return $found;
}

/**
 * Форматирует и возвращает представление размера файла в более удобочитаемое.
 * 
 * @param   integer     $size       размер файла
 *
 * @return  string
 */
function humanFileSize($size)
{
    $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
    return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0 Bytes';
}
?>