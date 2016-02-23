<html>
<head>

<style>
div{border: 0px solid}
a{text-decoration: none}
a:hover{text-decoration: underline}

.top_menu{overflow: hidden; height: 110px; padding: 0 100px 0 100px}
.top_menu div{position: relative; width: 100%; height: 100px}
.top_menu .content{color: #fff; width: auto; height: 100px; top: -100px; padding: 0 10px 0 310px; background: #bbb url(images/logo_and_text.jpg) 0 0 no-repeat}
.top_menu .content .name{font-size: 18px; padding: 10px; height: auto; width: auto; float: left}
.top_menu .content .topright_menu{float: right; width: auto; padding: 5px 0}
.top_menu .content .topright_menu a{padding: 0 0 0 10px; color: #fff}


.top_menu .shadow{left: 2px; background: #bbb; opacity: 0.5; filter: progid:DXImageTransform.Microsoft.Alpha(opacity=50)}
.top_menu .shadow_in{background: inherit; top: 2px; left: 2px; opacity: 0.5; filter: progid:DXImageTransform.Microsoft.Alpha(opacity=50)}

.card{margin: 7px; overflow: hidden}
.card div{position: relative; width: 100%; height: 100%}
.card .content{width: auto; height: auto; padding: 10px; z-index: 2; color: #fff}
.card .shadow{left: 2px; opacity: 0.5; z-index: 1; filter: progid:DXImageTransform.Microsoft.Alpha(opacity=50)}
.card .shadow_in{background: inherit; top: 2px; left: 2px; opacity: 0.5; filter: progid:DXImageTransform.Microsoft.Alpha(opacity=50)}

html{height: 100%; background: #fff url(images/bg1.gif) 0 0 repeat-x; min-width: 910px}

body{min-height: 100%; background: url(images/bg2.gif) 50% 0 no-repeat; margin: 0; font-family: arial; font-size: 14px}

img{border: 0}
table{border-collapse: collapse}

</style>

</head>
<body>

<div class="top_menu">
<div class="shadow" style="background: #bbb; top: 2px">
	<div class="shadow_in"><div class="shadow_in">
	<div class="shadow_in"><div class="shadow_in">
	</div></div></div></div>
</div>
<div class="content">
	<div class="name">Роман Чириков</div>
	<div class="topright_menu">
		<a href="#">Моя визитка</a>
		<a href="#">Моя визитка</a>
	</div>
</div>
</div>

<?php

function topmenu($body, $color="7880e6")
{
	return '
	<div class="top_menu">
	<div style="background: #'.$color.'">
	<div class="shadow" style="background: #'.$color.'; top: 2px">
		<div class="shadow_in"><div class="shadow_in">
		<div class="shadow_in"><div class="shadow_in">
		</div></div></div></div>
	</div>
	</div>
	<div class="content body" style="top: -'.($h+10).'px">
		'.$body.'
		</div>
	</div>';
}

function card_output($body, $color="7880e6", $w=200, $h=150)
{
	return '
	<div class="card" style="width: '.($w+10).'px; height: '.($h+10).'px">
	<div class="body" style="background: #'.$color.'; width: '.$w.'px; height: '.$h.'px">
	<div class="shadow" style="background: #'.$color.'; top: 2px">
		<div class="shadow_in"><div class="shadow_in">
		<div class="shadow_in"><div class="shadow_in">
		</div></div></div></div>
	</div>
	</div>
	<div class="content body" style="top: -'.($h+10).'px">
		'.$body.'
		</div>
	</div>';
}

$text = "<font size='20px'>Текст</font><br><br>
Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста. 
Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста. Много маленького текста.";

print card_output($text, "7880e6", 800, 600);

//print topmenu($text, "7880e6", 400, 300);

/*
print "<table cellspacing=0 cellpadding=0>
<tr><td>".card_output($text, "7880e6", 400, 300)."</td><td>".card_output($text, "bd232e", 400, 300)."</td><td>".card_output($text, "34A71A", 400, 300)."</td></tr>
<tr><td>".card_output($text, "8a24ab", 400, 300)."</td><td>".card_output($text, "eaa218", 400, 300)."</td><td>".card_output($text, "da50a8", 400, 300)."</td></tr>
</table>";
*/

?>

</body>
</html>