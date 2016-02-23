<?php
/*
class Structure_element
{
	var $id;
	var $name;
	var $jobs;
	var $submission;
	var $group;
	var $groups;
	
	function Structure_element($id=-1, $name="", $submission=0, $group=0)
	{
		$this->id = $id;
		$this->name = $name;
		$this->submission = $submission;
		$this->group = $group;
	}
}
class Structure_group
{
	var $id;
	var $name;
	
	function Structure_group($id, $name)
	{
		$this->id = $id;
		$this->name = $name;
	}
}
class Job
{
	var $id;
	var $name;
	var $payment;
	var $vacant;
	var $user;
	var $submission;
	
	function Job($id=-1, $name="", $payment=0, $vacant=1, $user=0, $submission=0)
	{
		$this->id = $id;
		$this->name = $name;
		$this->payment = $id;
		$this->vacant = $vacant;
		$this->user = $user;
		$this->submission = $submission;
	}
	function occupy($uid, $io)
	{
		print $mysql;
	}
	function free()
	{
		print $mysql;
	}
}

$russia = new Structure_element("state1", "������ ������");
$russia->jobs["j1"] = new Job("j1", "���������", "80000", 1, 1);
$ministries = new Structure_group("ministries", "������������");
$mvd = new Structure_element("mvd", "������������ ���������� ���", "j1", "ministries");
*/


/*
$structure = array(
"name" => "������ ������",
"structures" => array(
	"president" => array(
		"name" => "���������",
		"id" => "vr_president",
		"payment" => 80000,
		"structures" => array(
			"group" => array(
				"name" => "������������",
				"items" => array(
					"mvd" => array(
						"name" => "������������ ���������� ���",
						"id" => "vr_mvd",
						"structures" => array(
							"minister" => array(
								"name" => "������� ���������� ���",
								"id" => "minister",
								"payment" => 10000
							),
							"group" => array(
								"name" => "����������� ������",
								"items" => array(
									"fms" => array(
										"name" => "����������� ������������ ������",
										"id" => "fms"
									)
								)
							),
							"admindep" => array(
								"name" => "���������������� �����������",
								"id" => "admindep"
							)
						)
					),
					"mid" => array(
						"name" => "������������ ����������� ���",
						"id" => "vr_mid",
						"structures" => array(
							"minister" => array(
								"name" => "������� ����������� ���",
								"id" => "minister",
								"payment" => 10000
							),
							"group" => array(
								"name" => "����������� ���������",
								"items" => array(
									"fasng" => array(
										"name" => "����������� ��������� �� ����� ����������� ����������� ����������, �����������������, ����������� �� �������, � �� �������������� ������������� ��������������",
										"id" => "fasng"
									)
								)
							)
						)
					)
				)
			),
			"primeminister" => array(
				"name" => "�������-�������",
				"id" => "vr_prime",
				"payment" => 40000,
				"structures" => array(
					"group" => array(
						"name" => "������������",
						"items" => array(
							"mk" => array(
								"name" => "������������ ��������",
								"id" => "vr_mk",
								"structures" => array(
									"minister" => array(
										"name" => "������� ��������",
										"id" => "minister",
										"payment" => 10000
									),
									"group" => array(
										"name" => "����������� ���������",
										"items" => array(
											"faa" => array(
												"name" => "����������� �������� ��������",
												"id" => "faa"
											)
										)
									)
								)
							)
						)
					)
				)
			)
		)
	)
)
);
*/

?>