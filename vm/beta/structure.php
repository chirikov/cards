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

$russia = new Structure_element("state1", "Вторая Россия");
$russia->jobs["j1"] = new Job("j1", "Президент", "80000", 1, 1);
$ministries = new Structure_group("ministries", "Министерства");
$mvd = new Structure_element("mvd", "Министерство внутренних дел", "j1", "ministries");
*/


/*
$structure = array(
"name" => "Вторая Россия",
"structures" => array(
	"president" => array(
		"name" => "Президент",
		"id" => "vr_president",
		"payment" => 80000,
		"structures" => array(
			"group" => array(
				"name" => "Министерства",
				"items" => array(
					"mvd" => array(
						"name" => "Министерство внутренних дел",
						"id" => "vr_mvd",
						"structures" => array(
							"minister" => array(
								"name" => "Министр внутренних дел",
								"id" => "minister",
								"payment" => 10000
							),
							"group" => array(
								"name" => "Федеральные службы",
								"items" => array(
									"fms" => array(
										"name" => "Федеральная миграционная служба",
										"id" => "fms"
									)
								)
							),
							"admindep" => array(
								"name" => "Административный департамент",
								"id" => "admindep"
							)
						)
					),
					"mid" => array(
						"name" => "Министерство иностранных дел",
						"id" => "vr_mid",
						"structures" => array(
							"minister" => array(
								"name" => "Министр иностранных дел",
								"id" => "minister",
								"payment" => 10000
							),
							"group" => array(
								"name" => "Федеральные агентства",
								"items" => array(
									"fasng" => array(
										"name" => "Федеральное агентство по делам Содружества Независимых Государств, соотечественников, проживающих за рубежом, и по международному гуманитарному сотрудничеству",
										"id" => "fasng"
									)
								)
							)
						)
					)
				)
			),
			"primeminister" => array(
				"name" => "Премьер-министр",
				"id" => "vr_prime",
				"payment" => 40000,
				"structures" => array(
					"group" => array(
						"name" => "Министерства",
						"items" => array(
							"mk" => array(
								"name" => "Министерство культуры",
								"id" => "vr_mk",
								"structures" => array(
									"minister" => array(
										"name" => "Министр культуры",
										"id" => "minister",
										"payment" => 10000
									),
									"group" => array(
										"name" => "Федеральные агентства",
										"items" => array(
											"faa" => array(
												"name" => "Федеральная архивное агенство",
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