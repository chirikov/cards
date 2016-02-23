<html>
<head>

<style>

.big_card{width: 800px; height: 600px}
.big_card .body{color: white}
.big_card .ub{border-top: 9px solid #c1c1c1}
.big_card .db{border-bottom: 9px solid #c1c1c1}
.big_card .lb{border-left: 9px solid #c1c1c1}
.big_card .rb{border-right: 9px solid #c1c1c1}

.c_7880e6 .big_card td{padding: 0; background: #7880e6}
.c_7880e6 .big_card .ul{background: url(images/corners-7880e6-b.gif) 0 0 no-repeat; width: 30px}
.c_7880e6 .big_card .ur{background: url(images/corners-7880e6-b.gif) 100% 0 no-repeat; height: 30px}
.c_7880e6 .big_card .dr{background: url(images/corners-7880e6-b.gif) 100% -30px no-repeat; width: 30px; height: 30px}
.c_7880e6 .big_card .dl{background: url(images/corners-7880e6-b.gif) 0 -30px no-repeat}

.c_bd232e .big_card td{padding: 0; background: #bd232e}
.c_bd232e .big_card .ul{background: url(images/corners-bd232e-b.gif) 0 0 no-repeat; width: 30px}
.c_bd232e .big_card .ur{background: url(images/corners-bd232e-b.gif) 100% 0 no-repeat; height: 30px}
.c_bd232e .big_card .dr{background: url(images/corners-bd232e-b.gif) 100% -30px no-repeat; width: 30px; height: 30px}
.c_bd232e .big_card .dl{background: url(images/corners-bd232e-b.gif) 0 -30px no-repeat}

.c_2a7f16 .big_card td{padding: 0; background: #2a7f16}
.c_2a7f16 .big_card .ul{background: url(images/corners-2a7f16-b.gif) 0 0 no-repeat; width: 30px}
.c_2a7f16 .big_card .ur{background: url(images/corners-2a7f16-b.gif) 100% 0 no-repeat; height: 30px}
.c_2a7f16 .big_card .dr{background: url(images/corners-2a7f16-b.gif) 100% -30px no-repeat; width: 30px; height: 30px}
.c_2a7f16 .big_card .dl{background: url(images/corners-2a7f16-b.gif) 0 -30px no-repeat}

.small_card{width: 200px; height: 150px}
.small_card .body{color: white}
.small_card .ub{border-top: 3px solid #c1c1c1}
.small_card .db{border-bottom: 3px solid #c1c1c1}
.small_card .lb{border-left: 3px solid #c1c1c1}
.small_card .rb{border-right: 3px solid #c1c1c1}

.c_7880e6 .small_card td{padding: 0; background: #7880e6}
.c_7880e6 .small_card .ul{background: url(images/corners-7880e6-s.gif) 0 0 no-repeat; width: 24px}
.c_7880e6 .small_card .ur{background: url(images/corners-7880e6-s.gif) 100% 0 no-repeat; height: 24px}
.c_7880e6 .small_card .dr{background: url(images/corners-7880e6-s.gif) 100% -24px no-repeat; width: 24px; height: 24px}
.c_7880e6 .small_card .dl{background: url(images/corners-7880e6-s.gif) 0 -24px no-repeat}

.c_bd232e .small_card td{padding: 0; background: #bd232e}
.c_bd232e .small_card .ul{background: url(images/corners-bd232e-s.gif) 0 0 no-repeat; width: 24px}
.c_bd232e .small_card .ur{background: url(images/corners-bd232e-s.gif) 100% 0 no-repeat; height: 24px}
.c_bd232e .small_card .dr{background: url(images/corners-bd232e-s.gif) 100% -24px no-repeat; width: 24px; height: 24px}
.c_bd232e .small_card .dl{background: url(images/corners-bd232e-s.gif) 0 -24px no-repeat}

.c_2a7f16 .small_card td{padding: 0; background: #2a7f16}
.c_2a7f16 .small_card .ul{background: url(images/corners-2a7f16-s.gif) 0 0 no-repeat; width: 24px}
.c_2a7f16 .small_card .ur{background: url(images/corners-2a7f16-s.gif) 100% 0 no-repeat; height: 24px}
.c_2a7f16 .small_card .dr{background: url(images/corners-2a7f16-s.gif) 100% -24px no-repeat; width: 24px; height: 24px}
.c_2a7f16 .small_card .dl{background: url(images/corners-2a7f16-s.gif) 0 -24px no-repeat}

</style>

</head>
<body>

<?php

function card_output($size, $color, $body)
{
	print '<div class="c_'.$color.'"><table class="';
	if($size == 1) print 'big_card';
	else print 'small_card';
	print '" cellspacing="0"><tr><td class="ul">&nbsp;</td><td class="ub">&nbsp;</td><td class="ur">&nbsp;</td></tr><tr><td class="lb">&nbsp;</td><td class="body">
	'.$body.'
	</td><td class="rb">&nbsp;</td></tr><tr><td class="dl">&nbsp;</td><td class="db">&nbsp;</td><td class="dr">&nbsp;</td></tr></table></div>
	';
}

card_output(1, "7880e6", "123");
print "<br>";
card_output(1, "bd232e", "123");
print "<br>";
card_output(1, "2a7f16", "123");
print "<br>";
card_output(0, "7880e6", "123");
print "<br>";
card_output(0, "bd232e", "123");
print "<br>";
card_output(0, "2a7f16", "123");
print "<br>";
?>

</body>
</html>