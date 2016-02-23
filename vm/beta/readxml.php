<?php

function my_search($what, $where, &$results, $limit=0, $path="")
{
	foreach($where as $el)
	{
		if(count($results) >= $limit && $limit != 0) return;
		if(is_array($el)) my_search($what, $el, &$results, $limit, $path."[".key($where)."]");
		elseif($el == $what) $results[] = $path."[".key($where)."]";
		next($where);
	}
}

function start_handler($xmlp, $name, $attr)
{
	//for($i=0; $i<)
	print $attr['NAME'].":<br>";
	print_r($attr);
	print "<br>";
}
function end_handler($xmlp, $name)
{
	print "<br>";
}

$xml = implode("", file("structure.xml"));

$xmlp = xml_parser_create_ns("utf-8", "---");
xml_set_element_handler($xmlp, start_handler, end_handler);
xml_parse_into_struct($xmlp, $xml, $structure);
xml_parser_free($xmlp);

print_r($structure);

my_search("МВД", $structure, $res, 0);

print_r($res);

//print_r ($structure[9]);

?>