<?php

function menu($filename, $hrefs, $titles)
{
	$s = "";
	$num = count($hrefs);
	for($i=0; $i<$num; $i++)
	{
		if($hrefs[$i] == $filename) $s .= '<b>'.$titles[$i].'</b>';
		else $s .= '<a href="index.php?page='.$hrefs[$i].'">'.$titles[$i].'</a>';
	}
	return $s;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251"/>
<meta http-equiv="imagetoolbar" content="no"/>
<title>������������ �� ������� ����</title>
<meta name="Keywords" content="������ ���, ������, ���, ���������� ����, ����, �������������� ����"/>
<meta name="Description" content="������ �������������� ���� ������ ���"/>
<link rel="shortcut icon" href="../images/favicon.ico">
<link rel="stylesheet" href="../styles/layout.css" type="text/css"/>
<link rel="stylesheet" href="../styles/typography.css" type="text/css"/>
<!--[if IE]><link rel="stylesheet" href="../styles/ie.css" type="text/css" /><![endif]-->
</head>
<body>
<div class="main">
	<div class="carcass">
		<table class="head"><tr><td class="head">
			<div class="logo_explorer">
				<a href="index.php"><img class="img" src="../images/explorer_logo.gif" alt="�������"/><img class="text" src="../images/explorer.gif" alt="������������"/></a>
				<div class="slogan"><span>�� ������� ����</span></div>
			</div>
			<div class="head_right">
				<div class="search"><input disabled type="text" value="�����" alt=""/></div>
				<div class="menu">
					<a href="../index.php">��������� �� �������</a>
				</div>
			</div>
		</td></tr></table>
		<div class="left_column">
			<div class="help_menu">
				<div class="title">������ �� ��������:</div>
				<div class="help_menu_pad">
					<?php print menu($_GET['page'], $hrefs, $titles); ?>
				</div>
			</div>
		</div>
		<div class="right_column">