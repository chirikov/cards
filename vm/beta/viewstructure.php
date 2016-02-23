<?php
include_once("structure.php");

function view_structure($structure, $prefix="")
{
	if(isset($structure['name'])) print $prefix." ".$structure['name']."<br>";
	if(isset($structure['structures']))
	{
		$i = 1;
		foreach($structure['structures'] as $obj)
		{
			view_structure($obj, $prefix.$i.".");
			$i++;
		}
	}
	elseif(isset($structure['items']))
	{
		$i = 1;
		foreach($structure['items'] as $obj)
		{
			view_structure($obj, $prefix.$i.".");
			$i++;
		}
	}
}

view_structure($structure);

?>