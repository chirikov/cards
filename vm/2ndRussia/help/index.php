<?php
include_once("../inc/my_connect.php");

$hrefs = array("registration", "activation", "profile", "contacts", "messages");
$titles = array("�����������", "���������", "�������", "��������", "���������");
	
if(array_search($_GET['page'], $hrefs) === false) $_GET['page'] = "start";

$body .= "";

if($_GET['page'] == "start")
{
	if($_GET['step'] < 0 || $_GET['step'] > 10) $_GET['step'] = 0;
	if($_GET['step'] == 0)
	{
		$body .= '
		<div class="help_text">
			<h1>������ �� ������� ����</h1>
			<p><b>������������</b> ������� ��� ������������� � ��������, ������ ������������ ����� ������������� �����.
			���������� �� ���� �������� ��� �� �����.</p>
			<p>���� ��� ���������� ���������� ����������, �� ������ ����� �� � &laquo;������ �� ��������&raquo;
			��� ��������������� �������.</p>
			<div class="steps">
				<table style="margin:0 auto"><tr><td>
					<div class="step_right"><a href="index.php?step=1">��� 1</a></div>
				</td></tr></table>
			</div>
		</div>
		';
	}
	elseif($_GET['step'] == 1)
	{
		$body = '
		<div class="help_text">
			<h1>��� 1: �����������</h1>
			<p><b>������������</b> ������� ��� ������������� � ��������, ������ ������������ ����� ������������� �����.
			���������� �� ���� �������� ��� �� �����.</p>
			<p>���� ��� ���������� ���������� ����������, �� ������ ����� �� � &laquo;������ �� ��������&raquo;
			��� ��������������� �������.</p>
			<div class="steps">
				<table style="width:100%"><tr><td>
					<div class="step_left"><a href="index.php?step=0">� ������</a></div>
					<div class="step_right"><a href="index.php?step=2">��� 2</a></div>
				</td></tr></table>
			</div>
		</div>
		';
	}
}
elseif($_GET['page'] == "registration")
{
	$body .= '
	
	';
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>