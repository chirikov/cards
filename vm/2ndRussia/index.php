<?php
include_once("../inc/my_connect.php");
include_once("inc/control.php");

$content = "";
$menu = "";
$profilehref = "";
$log = loginned();
if(!$log)
{
	$menu = '<a href="../index.php?act=register" class="regging"><ins>�����������</ins></a>';
}
elseif($log === "notingame")
{
	$content = '<div class="enter"><a href="settings.php?act=ingame">������!</a></div>';
}
else
{
	$q = mysql_query("select surname, name from users where id = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($q);
	$profilehref = '<a href="profile.php">'.$row['name'].' '.$row['surname'].'</a>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251"/>
<meta http-equiv="imagetoolbar" content="no"/>
<title>������ ������</title>
<meta name="Keywords" content="������ ������, ����, ������ ���, ������, ���, ���������� ����, ����, �������������� ����"/>
<meta name="Description" content="������������ ���� ������ ������"/>
<link rel="shortcut icon" href="../images/favicon.ico">
<link rel="stylesheet" href="../styles/layout.css" type="text/css"/>
<link rel="stylesheet" href="../styles/typography.css" type="text/css"/>
<link rel="stylesheet" href="../styles/my.css" type="text/css"/>
<!--[if IE]><link rel="stylesheet" href="../styles/ie.css" type="text/css" /><![endif]-->
</head>
<body>
<div class="main">
	<div class="carcass">
		<table class="head"><tr><td class="head">
			<div class="logo_2ndrussia">
				<a href="index.php?force"><img class="img" src="../images/2ndrussia_logo.gif" alt="�������"/><img class="text" src="../images/2ndrussia_logo_text.gif" alt="������ ������"/></a>
				<div class="slogan"><span>������������ ����</span></div>
			</div>
			<div class="head_right">
				<div class="search"><input type="text" disabled value="�����" alt=""/></div>
				<div class="menu">
					<?php print $profilehref; ?> <a href="../index.php?force">������ ���</a>
				</div>
			</div>
		</td></tr></table>
		<div class="left_column">
			<div class="menu2">
				<?php print $menu; ?>
			</div>
			<div class="population">
			</div>
		</div>
		<div class="right_column">
			<div class="start">
				<div class="start_text">
					<h1>����� ���������� �� ������ ������!</h1>
					<p><strong>������ ������</strong> - ��� ���������� ���������-������������ ������-����, ���������� ������� �������� ����� � �����������.</p>
					<p style="padding-bottom:0">�� ������ ������ �� �������:</p>
					<ul>
						<li>����������� � ������������ ����� ������;</li>
						<li>������������ �� ������ � �������� ��������;</li>
						<li>��������� ����������� ������;</li>
						<li>������ �� �������� � �������� ������;</li>
						<li>����������� ���������;</li>
						<li>����� ��������� ����������� � ������ ������...</li>
					</ul>
					��������� � ������ ������ ����� ������ � <a href="help/index.php" class="guide">������������</a>.
				</div>
				<?php
				if($content != "")
				print '
				<div class="start_form">
					<div class="top_corners"><i>&nbsp;</i></div>
					<div class="start_form_pad">
						'.$content.'
					</div>
					<div class="bottom_corners"><i>&nbsp;</i></div>
				</div>';
				?>
			</div>
		</div>
		<div class="clear_line">&nbsp;</div>
	</div>
	<div class="footer"><div class="footer_block"><table class="footer_block"><tr><td>
		<div class="copyright">
			2008�2009 ������� ���<br/>
			��� ����� ��������.
			<div class="design">������ &mdash; <a target="_blank" href="http://ikari-design.ru">������ ������� �������</a></div>
		</div>
		<div class="menu3">
			<a href="help/about.php">� �������</a>
			<a href="help/index.php">������</a>
		</div>
	</td></tr></table></div></div>
</div>
</body>
</html>