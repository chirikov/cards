<html>
<head>


<script language="JavaScript1.1">

var timer_scroll = null;
var scrollStep;

function StartScroll(dir)
{
	scrollStep=10;
	scrollDemoScenesBar(dir);
	if (timer_scroll != null) clearInterval(timer_scroll);
	timer_scroll = window.setInterval('scrollDemoScenesBar("'+dir+'");', 100);
}

function StopScroll()
{
	if (timer_scroll != null)
	{
		clearInterval(timer_scroll);
		timer_scroll = null;
	}
}

function scrollDemoScenesBar(dir)
{
	DemoScenesBar.scrollLeft += dir*scrollStep;
	if (DemoScenesBar.scrollLeft == 0 || DemoScenesBar.scrollLeft == DemoScenesBar.scrollWidth) clearInterval(timer_scroll);
}


</script>
</head>
<body>

<table>
<tr>
<td><img src="img/eng/big_butt_left_n.gif" onmousedown="StartScroll(-1);" onmouseup="StopScroll();"></td>
<td style="width:500">
<div id="DemoScenesBar" style="width:400;height:95;overflow:hidden;">

<table width=1000>
	<tr>
		<td width=151><img></td>
		<td width=151><img></td>
		<td width=151><img></td>
		<td width=151><img></td>
	</tr>
</table>
</div>
</td>
<td><img src="img/eng/big_butt_right_n.gif" onmousedown="StartScroll(1);" onmouseup="StopScroll();"></td>
</tr>
</table>

</body>
</html>




###################### old
$body .= "<tr><td>Другие фотографии из альбома:<br><div class='pager'><table width='100%'><tr>";
	$q5 = mysql_query("select id, code from photos where album = ".$row['album']);
	$pnum = mysql_num_rows($q5);
	$n = 0;
	if($pnum > 4) $body .= "<td><a id='imgl' href='#' onclick='javascript: clickleft();' class='arrow1'>&nbsp;</a></td>";
	else $body .= "<td><a id='imgl' href='#' onclick='javascript: clickleft();' class='arrow1' style='display: none;'>&nbsp;</a></td>";
	if($pnum > 1)
	{
		$body .= "<td align='center'><img onclick='javascript: imclick(1);' id='sm1'></td>";
		$body .= "<td align='center'><img onclick='javascript: imclick(2);' id='sm2'></td>";
		$n = 2;
		if($pnum > 2)
		{
			$body .= "<td align='center'><img onclick='javascript: imclick(3);' id='sm3'></td>";
			$n = 3;
			if($pnum > 3)
			{
				$body .= "<td align='center'><img onclick='javascript: imclick(4);' id='sm4'></td>";
				$n = 4;
			}
		}
	}
	if($pnum > 4) $body .= "<td><a id='imgr' href='#' onclick='javascript: clickright();' class='arrow2'>&nbsp;</a></td>";
	else $body .= "<td><a id='imgr' href='#' onclick='javascript: clickright();' class='arrow2' style='display: none;'>&nbsp;</a></td>";
	$body .= "</tr></table></div>";
	if($n > 0)
	{
		$body .= "
		<script language='javascript' type='text/javascript'>
		<!--//
		var timer_scroll = null;
		var ph = new Array();
		var phid = new Array();
		var stack = new Array();
		var last = ".$pnum.";
		
		function StartScroll(dir)
		{
			scrollStep=10;
			scrollphotoline(dir);
			if (timer_scroll != null) clearInterval(timer_scroll);
			timer_scroll = window.setInterval('scrollphotoline(\"'+dir+'\");', 100);
		}
		
		function StopScroll()
		{
			if (timer_scroll != null)
			{
				clearInterval(timer_scroll);
				timer_scroll = null;
			}
		}
		
		function scrollphotoline(dir)
		{
			photoline.scrollLeft += dir*10;
			if (photoline.scrollLeft == 0 || photoline.scrollLeft == photoline.scrollWidth) clearInterval(timer_scroll);
		}
		";
		for($i=1; $i<=$pnum; $i++)
		{
			$code = mysql_result($q5, $i-1, 'code');
			$body .= "ph[".$i."] = '".$code."';";
			$body .= "phid[".$i."] = '".mysql_result($q5, $i-1, 'id')."';";
			if($code == $row['code']) $pid = $i;
		}
		if($pid == 1)
		{
			$body .= "document.getElementById('sm1').src = 'photos/".$row3['uid']."/".$row['album']."/".$row['code']."s.jpg'; stack[1] = ".$pid.";";
			for($i = 2; $i<=$n; $i++)
			{
				$body .= "document.getElementById('sm".$i."').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $i-1, 'code')."s.jpg'; stack[".$i."] = ".$i.";";
			}
		}
		elseif($pid == $pnum)
		{
			$body .= "document.getElementById('sm".$n."').src = 'photos/".$row3['uid']."/".$row['album']."/".$row['code']."s.jpg'; stack[".$n."] = ".$pid.";";
			for($i = $n-1; $i>=1; $i--)
			{
				$body .= "document.getElementById('sm".$i."').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $pnum-$n+$i-1, 'code')."s.jpg'; stack[".$i."] = ".($pnum-$n+$i).";";
			}
		}
		else
		{
			if($pid == $pnum-1 && $n > 3)
			{
				$body .= "document.getElementById('sm3').src = 'photos/".$row3['uid']."/".$row['album']."/".$row['code']."s.jpg'; stack[3] = ".$pid.";";
				$body .= "document.getElementById('sm4').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $pid, 'code')."s.jpg'; stack[4] = ".($pid+1).";";
				for($i = 1; $i<=2; $i++)
				{
					$body .= "document.getElementById('sm".$i."').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $pid+$i-4, 'code')."s.jpg'; stack[".$i."] = ".($pid+$i-3).";";
				}
			}
			else
			{
				$body .= "document.getElementById('sm2').src = 'photos/".$row3['uid']."/".$row['album']."/".$row['code']."s.jpg'; stack[2] = ".$pid.";";
				$body .= "document.getElementById('sm1').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $pid-2, 'code')."s.jpg'; stack[1] = ".($pid-1).";";
				for($i = 3; $i<=$n; $i++)
				{
					$body .= "document.getElementById('sm".$i."').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $pid+$i-3, 'code')."s.jpg'; stack[".$i."] = ".($pid+$i-2).";";
				}
			}
		}
		if($pid == 1 || $pid == 2) $body .= "document.getElementById('imgl').style.display = 'none';";
		if($pid == $pnum || $pid == $pnum-1 || $pid == $pnum-2) $body .= "document.getElementById('imgr').style.display = 'none';";
		$body .= "

		function imclick(no)
		{
			document.getElementById('scene').src = 'photos/".$row3['uid']."/".$row['album']."/'+ph[stack[no]]+'.jpg';
			document.getElementById('delhref').href = 'photo.php?act=delphoto&pid='+phid[stack[no]];
		}
		
		function clickright()
		{
		document.getElementById('imgl').style.display = 'block';";
		for($i = 1; $i<=3; $i++)
		{
			$body .= "document.getElementById('sm".$i."').src = document.getElementById('sm".($i+1)."').src; stack[".$i."] = stack[".($i+1)."];";
		}
		$body .= "document.getElementById('sm4').src = 'photos/".$row3['uid']."/".$row['album']."/'+ph[stack[4]+1]+'s.jpg'; stack[4] = stack[4]+1;";
		$body .= "
		if(stack[4] == last) {document.getElementById('imgr').style.display = 'none';}
		}
		function clickleft()
		{
		document.getElementById('imgr').style.display = 'block';";
		for($i = 4; $i>=2; $i--)
		{
			$body .= "document.getElementById('sm".$i."').src = document.getElementById('sm".($i-1)."').src; stack[".$i."] = stack[".($i-1)."];";
		}
		$body .= "document.getElementById('sm1').src = 'photos/".$row3['uid']."/".$row['album']."/'+ph[stack[1]-1]+'s.jpg'; stack[1] = stack[1]-1;";
		$body .= "
		if(stack[1] == 1) {document.getElementById('imgl').style.display = 'none';}
		}
		//-->
		</script>";
	}
	$body .= "</td></tr>
	</table>";