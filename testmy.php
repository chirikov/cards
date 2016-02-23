<?php
/*
if(ereg("ма(й|я)", "мая")) print "yes";
else print "no";
*/

//$arr = array("asd");
//print implode("@", $arr);

//print mb_substr("абвгде", 0, 4);

$str = "\"1\" '2' `3` \'4\' \`5\`";
$a1 = array();

$name = array("id" => "name", "x" => 0, "y" => 0, "style" => "color: #c20000; font-size: 20px", "nt" => true);
$surname = array("id" => "surname", "x" => 0, "y" => 0, "style" => "color: #c20000; font-size: 20px", "nt" => true);
$photo = array("id" => "photo", "x" => 0, "y" => 0);

$a1[0] = array("title" => "Основное", "items" => $str);
//РћСЃРЅРѕРІРЅРѕРµ
$json = json_encode($a1);

//print $json;

function in_array2($need, $arr)
{
	foreach($arr as $el)
	{
		if($el === $need) return $arr;
		elseif(is_array($el)) if($ret = in_array2($need, $el)) return $ret;
	}
	return false;
}

$j = '[
{
"title":"\u041e\u0441\u043d\u043e\u0432\u043d\u043e\u0435",
"itemsnum":7,
"items":{
"name":{"id":"name","x":0,"y":0,"style":"color: #c20000; font-size: 20px;","nt":true},
"photo":{"id":"photo","x":0,"y":0},
"other0":{"id":"other0","title":"\u0417\u0430\u0433\u043e\u043b\u043e\u0432\u043e\u043a 1","value":"erqwerer","x":0,"y":0,"style":"color: #1F1F1F; background-color: transparent; border-width: 4px; border-color: #9C9C9C; padding: 3px; ","nt":false,"other":true},
"city":{"id":"city","title":"","value":"","x":0,"y":0,"style":"color: #1F1F1F; background-color: transparent; padding: 3px; ","nt":false,"other":false},
"box0":{"id":"box0","title":"","value":"","x":0,"y":0,"style":"width: 100px; height: 100px; background-color: #59FF26; border: solid; border-width: 1px; border-color: #D3D3D3; ","nt":true,"other":false},
"box1":{"id":"box1","title":"","value":"","x":0,"y":0,"style":"width: 100px; height: 100px; background-color: #483BFF; border: solid; border-width: 1px; border-color: #D3D3D3; opacity: 0.8; filter: progid:DXImageTransform.Microsoft.Alpha(opacity=80);","nt":true,"other":false}
}
}
]';

$ja = json_decode($j, true);

//print_r($ja);

//var_dump(in_array2("box2", $ja));

?>

<div id="asf">123</div>
<script>
alert(asf.innerHTML);
</script>