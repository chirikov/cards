<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");
if(loginned() == true && !isset($_GET['force']))
{
	header("Location: profile.php");
	exit;
}

$profilehref = "";
if(loginned() == true && isset($_GET['force']))
{
	$qname = mysql_query("select surname, name from users where id = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($qname);
	$profilehref = "<a href='profile.php'>".$row['name']." ".$row['surname']."</a>";
}
if($_GET['act'] != "register" && $_GET['act'] != "activate") $_GET['act'] = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251"/>
<meta http-equiv="imagetoolbar" content="no"/>
<title>������ ���</title>
<meta name="Keywords" content="������ ���, ������, ���, ���������� ����, ����, �������������� ����"/>
<meta name="Description" content="������ �������������� ���� ������ ���"/>
<link rel="shortcut icon" href="images/favicon.ico"/>
<link rel="stylesheet" href="styles/layout.css" type="text/css"/>
<link rel="stylesheet" href="styles/typography.css" type="text/css"/>
<link rel="stylesheet" href="styles/my.css" type="text/css"/>
<!--[if IE]><link rel="stylesheet" href="styles/ie.css" type="text/css" /><![endif]-->
<script language="javascript" type="text/javascript" src="inc/indexajax.js"></script>
</head>
<body>
<div class="main">
	<div class="carcass">
		<table class="head"><tr><td class="head">
			<div class="logo">
				<a href="index.php?force"><img class="img" src="images/logo.gif" alt="�������"/><img class="text" src="images/logo_text.gif" alt="������ ���"/></a>
				<div class="slogan"><span>����...</span></div>
			</div>
			<div class="head_right">
				<div class="search"><input type="text" disabled="disabled" value="�����" alt=""/></div>
				<div class="menu">
					<?php print $profilehref; ?>
					<a href="2ndRussia/index.php">������ ������</a>
					<a href="#" onclick="javascript: main();">�������</a>
				</div>
			</div>
		</td></tr></table>
		<div class="left_column">
			<div class="menu2">
				<a href="#" onclick="javascript: register();" class="regging"><ins>�����������</ins></a>
				<a href="#" onclick="javascript: activate();" class="activation"><ins>���������</ins></a>
			</div>
			<div class="population">
			</div>
		</div>
		<div class="right_column">
			<div id="main" class="start" <?php if($_GET['act'] == "register" || $_GET['act'] == "activate") print "style='display: none;'"; ?>>
				<div class="start_text">
					<h1>����� ���������� �� ������ ���!</h1>
					<p><strong>������ ���</strong> - ��� ���������� ����� ���������� ������, ������������ � ���� ��� ������ ��� ������ ��������. ������� ����������� ���������� � ����, ������ ���
					������, ����� ������ ���� ����� �����.<br/><br/>
					���-�� ������� ��� "��������� ���������� �����".<br/>�� �������� ������ ��� <strong>������ �������������� �����</strong>.</p>
					<p style="padding-bottom:0">�� ������ ���� �� �������:</p>
					<ul>
						<li>������ ���� �� ����� � ������ ������� � ��������;</li>
						<li>������������ ����������� ��� ������������ ��������;</li>
						<li>�������� ���� � ������ ��������� �� ����� ���� �������;</li>
						<li>����������� ���������� � ���������� �� ������;</li>
						<li>�������� � ��������� ����������� � <strong>������������ ������</strong>;</li>
						<li>�����������, ���� ����� ����������;</li>
						<li>����������� ��� � ���������������� ����� �������� � ������ ������...</li>
					</ul>
					��������� � ������ ���� ����� ������ � <a href="help/index.php" class="guide">������������</a>.
				</div>
				<div class="start_form">
					<div class="top_corners"><i>&nbsp;</i></div>
					<h2>���� �� ������ ���</h2>
					<div class="resultt" id="result1">&nbsp;</div>
					<div class="start_form_pad"><form method="post" id="flogin" name="flogin" action="javascript: logindone();" onkeypress="javascript: if(event.keyCode == 13) logindone();">
						<table class="form_table"><!--[if IE 6]><col style="width:70px"/><col style="width:157px"/><col style="width:100px"/><![endif]-->
							<tr>
								<td><label>E-mail:</label></td>
								<td><input type="text" name="email" maxlength="50"/></td>
								<td><a href="#" onclick="javascript: register();">�����������</a></td>
							</tr>
							<tr>
								<td><label>������:</label></td>
								<td><input type="password" name="pass" maxlength="50"/></td>
								<td></td>
							</tr>
						</table>
						<div class="enter"><a href="#" onclick="javascript: logindone();">�����</a></div>
						<div class="other_computer"><img class="wait" id="wait1" src="images/wait.gif" alt=""/></div></form>
					</div>
					<div class="bottom_corners"><i>&nbsp;</i></div>
				</div>
			</div>
			
			<div id="register" <?php if($_GET['act'] != "register") print "style='display: none;'"; ?> class="start">
				<div class="start_text">
					<h1>�����������</h1>
					<p><strong>�����������</strong> ���������� ��� ����, ����� �� ����� ��������������� ����� ��������� �������.<br/><br/>
					���������, ����������, ��� ����.</p>
				</div>
				<div class="start_form" style="width: 330px">
					<div class="top_corners"><i>&nbsp;</i></div>
					<div class="resultt" id="result2">&nbsp;</div>
					<div class="start_form_pad"><form method="post" id="f1" name="f1" action="javascript: regdone();" onkeypress="javascript: if(event.keyCode == 13) regdone();">
						<table class="form_table"><!--[if IE 6]><col style="width:70px"/><col style="width:157px"/><col style="width:100px"/><![endif]-->
							<tr>
								<td><label>���:</label></td>
								<td><input type="text" name="name" maxlength="40"/></td>
							</tr>
							<tr>
								<td><label>�������:</label></td>
								<td><input type="text" name="surname" maxlength="40"/></td>
							</tr>
							<tr>
								<td><label>���:</label></td>
								<td><select name="sex">
								<option value="m">���.</option>
								<option value="f">���.</option>
								</select></td>
							</tr>
							<tr>
								<td><label>�����:</label></td>
								<td>
								<select name="city">
<?php
$q1 = mysql_query("select id, name from cities where 1");
while($arr = mysql_fetch_assoc($q1))
{
	print '<option value="'.$arr['id'].'">'.$arr['name'].'</option>';
}
?>
								</select>
								</td>
								<td></td>
							</tr>
							<tr>
								<td><label>����&nbsp;��������:</label></td>
								<td></td>
							</tr>
							<tr>
								<td><label>����:</label></td>
								<td>
								<select name="day" id="selday">
<?php
for($i=1; $i<=31; $i++)
{
	print '<option id="opt'.$i.'" value="'.$i.'">'.$i.'</option>';
}
?>
								</select>
								</td>
							</tr>
							<tr>
								<td><label>�����:</label></td>
								<td>
								<select name="month" onchange="javascript: selchg();">
<?php
$mons = array("@", "������", "�������", "����", "������", "���", "����", "����", "������", "��������", "�������", "������", "�������");
for($i=1; $i<=12; $i++)
{
	print '<option value="'.$i.'">'.$mons[$i].'</option>';
}
?>
								</select>
								</td>
							</tr>
							<tr>
								<td><label>���:</label></td>
								<td>
								<select name="year" id="selyear" onchange="javascript: selchg();">
<?php
for($i=2002; $i>=1920; $i--)
{
	print '<option ';
	if($i == 1988) print 'selected="selected" ';
	print 'value="'.$i.'">'.$i.'</option>';
}
?>
								</select>
								</td>
							</tr>
							<tr>
								<td><label>E-mail:</label></td>
								<td><input type="text" name="email" value="<?php print $_GET['email']; ?>" maxlength="50"/></td>
							</tr>
							<tr>
								<td><label>������:</label></td>
								<td><input type="password" name="pass" maxlength="50"/></td>
							</tr>
							<tr>
								<td><label>�����������&nbsp;������:</label></td>
								<td><input type="password" name="pass2" maxlength="50"/></td>
							</tr>
						</table>
						<div class="enter"><a href="#" onclick="javascript: regdone();">�����������</a></div>
						<div class="other_computer"><img class="wait" id="wait2" src="images/wait.gif" alt=""/></div>
						</form>
					</div>
					<div class="bottom_corners"><i>&nbsp;</i></div>
				</div>
			</div>
			
			<div id="activate" <?php if($_GET['act'] != "activate") print "style='display: none;'"; ?> class="start">
				<div class="start_text">
					<h1>���������</h1>
					<p><strong>���������</strong> ���������� ��� �������� ������������� ���������� ���� E-mail.</p>
				</div>
				<div class="start_form">
					<div class="top_corners"><i>&nbsp;</i></div>
					<div class="resultt" id="result3">&nbsp;</div>
					<div class="start_form_pad"><form method="post" id="f2" name="f2" action="javascript: actdone();" onkeypress="javascript: if(event.keyCode == 13) actdone();">
						<table class="form_table"><!--[if IE 6]><col style="width:70px"/><col style="width:157px"/><col style="width:100px"/><![endif]-->
							<tr>
								<td><label>E-mail:</label></td>
								<td><input type="text" name="email" value="<?php print $_GET['email'] ?>" maxlength="50"/></td>
								<td></td>
							</tr>
							<tr>
								<td><label>���&nbsp;���������:</label></td>
								<td><input type="text" name="actcode" maxlength="6"/></td>
								<td><a href="#" onclick="javascript: sendactcode();">��������� ��� ��� ���</a></td>
							</tr>
						</table>
						<div class="enter"><a href="#" onclick="javascript: actdone();">������������</a></div>
						<div class="other_computer"><img id="wait3" src="images/wait.gif" class="wait" alt=""/></div>
						</form>
					</div>
					<div class="bottom_corners"><i>&nbsp;</i></div>
				</div>
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
			<a href="adv/index.php">�������</a>
			<a href="help/about.php">� �������</a>
			<a href="help/index.php">������</a>
		</div>
	</td></tr></table></div></div>
</div>
</body>
</html>