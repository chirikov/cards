<?php

set_time_limit(0);

/*
function getcolor($im, $x, $y)
{
	$rgb = imagecolorat($im, $x, $y);
	return ((($rgb >> 16) & 0xFF) + (($rgb >> 8) & 0xFF) + ($rgb & 0xFF))/3;
}
*/

function iscorner($matrix, $x, $y, $side, $type="out")
{
	$dl = 5;
	$dr = 5;
	if($type == "out")
	{
		if($side == "up")
		{
			if($matrix[$x][$y] == 0 && $matrix[$x][$y-1] == 255)
			{
				$uclear = 0;
				for($xi=$x-$dl; $xi<=$x+$dr; $xi++)
				{
					if($matrix[$xi][$y-1] == 0) $uclear++;
				}
				
				if($uclear == 0 && $matrix[$x-$dl][$y] != 0) return true;
				else return false;
			}
		}
		elseif($side == "down")
		{
			if($matrix[$x][$y] == 0 && $matrix[$x][$y+1] != 0)
			{
				$uclear = 0;
				for($xi=$x+$dr; $xi>=$x-$dl; $xi--)
				{
					if($matrix[$xi][$y+1] == 0) $uclear++;
				}
				
				if($uclear == 0 && $matrix[$x+$dr][$y] != 0) return true;
				else return false;
			}
		}
		elseif($side == "left")
		{
			if($matrix[$x][$y] == 0 && $matrix[$x-1][$y] != 0)
			{
				$uclear = 0;
				for($yi=$y+$dr; $yi>=$y-$dl; $yi--)
				{
					if($matrix[$x-1][$yi] == 0) $uclear++;
				}
				
				if($uclear == 0 && $matrix[$x][$y+$dr] != 0) return true;
				else return false;
			}
		}
		elseif($side == "right")
		{
			if($matrix[$x][$y] == 0 && $matrix[$x+1][$y] != 0)
			{
				$uclear = 0;
				for($yi=$y-$dl; $yi<=$y+$dr; $yi++)
				{
					if($matrix[$x+1][$yi] == 0) $uclear++;
				}
				
				if($uclear == 0 && $matrix[$x][$y-$dl] != 0) return true;
				else return false;
			}
		}
	}
	else
	{
		if($side == "up")
		{
			if($matrix[$x][$y] != 0 && $matrix[$x][$y-1] == 0)
			{
				$uclear = 0;
				for($xi=$x-$dl; $xi<=$x+$dr; $xi++)
				{
					if($matrix[$xi][$y-1] != 0) $uclear++;
				}
				
				if($uclear == 0 && $matrix[$x-$dl][$y] == 0) return true;
				else return false;
			}
		}
		elseif($side == "down")
		{
			if($matrix[$x][$y] != 0 && $matrix[$x][$y+1] == 0)
			{
				$uclear = 0;
				for($xi=$x-$dl; $xi<=$x+$dr; $xi++)
				{
					if($matrix[$xi][$y+1] != 0) $uclear++;
				}
				
				if($uclear == 0 && $matrix[$x+$dr][$y] == 0) return true;
				else return false;
			}
		}
		elseif($side == "left")
		{
			if($matrix[$x][$y] != 0 && $matrix[$x-1][$y] == 0)
			{
				$uclear = 0;
				for($yi=$y+$dr; $yi>=$y-$dl; $yi--)
				{
					if($matrix[$x-1][$yi] != 0) $uclear++;
				}
				
				if($uclear == 0 && $matrix[$x][$y+$dr] == 0) return true;
				else return false;
			}
		}
		elseif($side == "right")
		{
			if($matrix[$x][$y] != 0 && $matrix[$x+1][$y] == 0)
			{
				$uclear = 0;
				for($yi=$y-$dl; $yi<=$y+$dr; $yi++)
				{
					if($matrix[$x+1][$yi] != 0) $uclear++;
				}
				
				if($uclear == 0 && $matrix[$x][$y-$dl] == 0) return true;
				else return false;
			}
		}
	}
}

$matrix = array();
$cornersu = array();
$cornersd = array();
$cornersl = array();
$cornersr = array();
$cornersi = array();
$corners = array();

for($x=0; $x<600; $x++)
{
	for($y=0; $y<600; $y++)
	{
		$matrix[$x][$y] = 255;
	}
}

$file = "tiles.png";
$d = 8; // side of a square
$cor_near = 8; // nearity between corners

$im = imagecreatefrompng($file);

$imw = imagesx($im);
$imh = imagesy($im);

$red = imagecolorallocate($im, 255, 0, 0);
$green = imagecolorallocate($im, 0, 255, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$yellow = imagecolorallocate($im, 255, 255, 0);
$gray = imagecolorallocate($im, 200, 200, 200);

//$im = imagerotate($im, 14, $red);

for($y=0; $y<$imh; $y++)
{
	for($x=0; $x<$imw; $x++)
	{
		$rgb = imagecolorat($im, $x, $y);
		$matrix[$x][$y] = ((($rgb >> 16) & 0xFF) + (($rgb >> 8) & 0xFF) + ($rgb & 0xFF))/3;
	}
}

for($yi=0; $yi<floor($imh/$d); $yi++)
{
	for($xi=0; $xi<floor($imw/$d); $xi++)
	{
		$uc = 0;
		$dc = 0;
		$lc = 0;
		$rc = 0;
		
		for($ns=$xi*$d; $ns<($xi+1)*$d; $ns++)
		{
			$coloru = $matrix[$ns][$yi*$d];
			$colord = $matrix[$ns][$yi*$d+$d-1];
			
			imagesetpixel($im, $ns, $yi*$d, $gray);
			
			if($coloru == 85 || $colord == 85) continue 2;
			
			if($coloru == 0) $uc++;
			if($colord == 0) $dc++;
		}
		for($ns=$yi*$d; $ns<($yi+1)*$d; $ns++)
		{
			imagesetpixel($im, $xi*$d, $ns, $gray);
			
			$colorl = $matrix[$xi*$d][$ns];
			$colorr = $matrix[$xi*$d+$d-1][$ns];
			
			if($colorl == 85 || $colorr == 85) continue 2;
			
			if($colorl == 0) $lc++;
			if($colorr == 0) $rc++;
		}
		
		if(($uc == 0 && $dc == 0 && $lc == 0 && $rc == 0) || ($uc == $d && $dc == $d && $lc == $d && $rc == $d)) continue;
		else
		{
			if($uc < 3 && $dc != 0) $cornersu[] = array($xi*$d, $yi*$d); //imagerectangle($im, $xi*$d, $yi*$d, ($xi+1)*$d, ($yi+1)*$d, $red);}
			elseif($dc < 3 && $uc != 0) $cornersd[] = array($xi*$d, $yi*$d); //imagerectangle($im, $xi*$d, $yi*$d, ($xi+1)*$d, ($yi+1)*$d, $yellow);}
			if($lc < 3 && $rc != 0) $cornersl[] = array($xi*$d, $yi*$d); //imagerectangle($im, $xi*10, $yi*10, ($xi+1)*10, ($yi+1)*10, $green);
			elseif($rc < 3 && $lc != 0) $cornersr[] = array($xi*$d, $yi*$d); //imagerectangle($im, $xi*10, $yi*10, ($xi+1)*10, ($yi+1)*10, $blue);
			
			$cornersi[] = array($xi*$d, $yi*$d);
			
			//imagerectangle($im, $xi*$d, $yi*$d, ($xi+1)*$d, ($yi+1)*$d, $red);
		}
	}
}

$i = 0;
foreach($cornersu as $area)
{
	for($y=$area[1]; $y<$area[1]+$d; $y++)
	{
		for($x=$area[0]; $x<$area[0]+$d; $x++)
		{
			if(iscorner($matrix, $x, $y, "up", "out"))
			{
				//imagesetpixel($im, $x, $y, $red);
				$corners["up"][] = array("x" => $x, "y" => $y);
				continue 3;
			}
		}
	}
}
foreach($cornersd as $area)
{
	for($y=$area[1]+$d-1; $y>=$area[1]; $y--)
	{
		for($x=$area[0]+$d; $x>=$area[0]; $x--)
		{
			if(iscorner($matrix, $x, $y, "down", "out"))
			{
				//imagesetpixel($im, $x, $y, $yellow);
				$corners["down"][] = array("x" => $x, "y" => $y);
				continue 3;
			}
		}
	}
}
foreach($cornersl as $area)
{
	for($x=$area[0]; $x<$area[0]+$d; $x++)
	{
		for($y=$area[1]+$d; $y>=$area[1]; $y--)
		{
			if(iscorner($matrix, $x, $y, "left", "out"))
			{
				//imagesetpixel($im, $x, $y, $green);
				$corners["left"][] = array("x" => $x, "y" => $y);
				continue 3;
			}
		}
	}
}
foreach($cornersr as $area)
{
	for($x=$area[0]+$d-1; $x>=$area[0]; $x--)
	{
		for($y=$area[1]; $y<$area[1]+$d; $y++)
		{
			if(iscorner($matrix, $x, $y, "right", "out"))
			{
				//imagesetpixel($im, $x, $y, $blue);
				$corners["right"][] = array("x" => $x, "y" => $y);
				continue 3;
			}
		}
	}
}
foreach($cornersi as $area)
{
	// up
	for($y=$area[1]; $y<$area[1]+$d; $y++)
	{
		for($x=$area[0]; $x<$area[0]+$d; $x++)
		{
			if(iscorner($matrix, $x, $y, "up", "in"))
			{
				//imagesetpixel($im, $x, $y, $green);
				$corners["iup"][] = array("x" => $x, "y" => $y);
				continue 3;
			}
		}
	}
	// down
	for($y=$area[1]+$d-1; $y>=$area[1]; $y--)
	{
		for($x=$area[0]+$d; $x>=$area[0]; $x--)
		{
			if(iscorner($matrix, $x, $y, "down", "in"))
			{
				//imagesetpixel($im, $x, $y, $yellow);
				$corners["idown"][] = array("x" => $x, "y" => $y);
				continue 3;
			}
		}
	}
	// left
	for($x=$area[0]; $x<$area[0]+$d; $x++)
	{
		for($y=$area[1]+$d; $y>=$area[1]; $y--)
		{
			if(iscorner($matrix, $x, $y, "left", "in"))
			{
				//imagesetpixel($im, $x, $y, $green);
				$corners["ileft"][] = array("x" => $x, "y" => $y);
				continue 3;
			}
		}
	}
	// right
	for($x=$area[0]+$d-1; $x>=$area[0]; $x--)
	{
		for($y=$area[1]; $y<$area[1]+$d; $y++)
		{
			if(iscorner($matrix, $x, $y, "right", "in"))
			{
				//imagesetpixel($im, $x, $y, $blue);
				$corners["iright"][] = array("x" => $x, "y" => $y);
				continue 3;
			}
		}
	}
}

for($i=0; $i<count($corners["up"]); $i++)
{
	for($j=0; $j<count($corners["up"]); $j++)
	{
		if($i == $j) continue;
		
		if(abs($corners["up"][$i]["x"] - $corners["up"][$j]["x"]) <= $cor_near && abs($corners["up"][$i]["y"] - $corners["up"][$j]["y"]) <= $cor_near)
		{
			if($corners["up"][$i]["x"] != $corners["up"][$j]["x"])
			{
				if($corners["up"][$i]["x"] < $corners["up"][$j]["x"]) array_splice($corners["up"], $j, 1);
				else array_splice($corners["up"], $i, 1);
			}
			else
			{
				if($corners["up"][$i]["y"] < $corners["up"][$j]["y"]) array_splice($corners["up"], $j, 1);
				else array_splice($corners["up"], $i, 1);
			}
		}
	}
}
for($i=0; $i<count($corners["iup"]); $i++)
{
	for($j=0; $j<count($corners["iup"]); $j++)
	{
		if($i == $j) continue;
		
		if(abs($corners["iup"][$i]["x"] - $corners["iup"][$j]["x"]) <= $cor_near && abs($corners["iup"][$i]["y"] - $corners["iup"][$j]["y"]) <= $cor_near)
		{
			if($corners["iup"][$i]["x"] != $corners["iup"][$j]["x"])
			{
				if($corners["iup"][$i]["x"] < $corners["iup"][$j]["x"]) array_splice($corners["iup"], $j, 1);
				else array_splice($corners["up"], $i, 1);
			}
			else
			{
				if($corners["iup"][$i]["y"] < $corners["iup"][$j]["y"]) array_splice($corners["iup"], $j, 1);
				else array_splice($corners["iup"], $i, 1);
			}
		}
	}
}
for($i=0; $i<count($corners["down"]); $i++)
{
	for($j=0; $j<count($corners["down"]); $j++)
	{
		if($i == $j) continue;
		
		if(abs($corners["down"][$i]["x"] - $corners["down"][$j]["x"]) <= $cor_near && abs($corners["down"][$i]["y"] - $corners["down"][$j]["y"]) <= $cor_near)
		{
			if($corners["down"][$i]["x"] != $corners["down"][$j]["x"])
			{
				if($corners["down"][$i]["x"] > $corners["down"][$j]["x"]) array_splice($corners["down"], $j, 1);
				else array_splice($corners["down"], $i, 1);
			}
			else
			{
				if($corners["down"][$i]["y"] > $corners["down"][$j]["y"]) array_splice($corners["down"], $j, 1);
				else array_splice($corners["down"], $i, 1);
			}
		}
	}
}
for($i=0; $i<count($corners["idown"]); $i++)
{
	for($j=0; $j<count($corners["idown"]); $j++)
	{
		if($i == $j) continue;
		
		if(abs($corners["idown"][$i]["x"] - $corners["idown"][$j]["x"]) <= $cor_near && abs($corners["idown"][$i]["y"] - $corners["idown"][$j]["y"]) <= $cor_near)
		{
			if($corners["idown"][$i]["x"] != $corners["idown"][$j]["x"])
			{
				if($corners["idown"][$i]["x"] > $corners["idown"][$j]["x"]) array_splice($corners["idown"], $j, 1);
				else array_splice($corners["idown"], $i, 1);
			}
			else
			{
				if($corners["idown"][$i]["y"] > $corners["idown"][$j]["y"]) array_splice($corners["idown"], $j, 1);
				else array_splice($corners["idown"], $i, 1);
			}
		}
	}
}
for($i=0; $i<count($corners["left"]); $i++)
{
	for($j=0; $j<count($corners["left"]); $j++)
	{
		if($i == $j) continue;
		
		if(abs($corners["left"][$i]["x"] - $corners["left"][$j]["x"]) <= $cor_near && abs($corners["left"][$i]["y"] - $corners["left"][$j]["y"]) <= $cor_near)
		{
			if($corners["left"][$i]["y"] != $corners["left"][$j]["y"])
			{
				if($corners["left"][$i]["y"] > $corners["left"][$j]["y"]) array_splice($corners["left"], $j, 1);
				else array_splice($corners["left"], $i, 1);
			}
			else
			{
				if($corners["left"][$i]["x"] < $corners["left"][$j]["x"]) array_splice($corners["left"], $j, 1);
				else array_splice($corners["left"], $i, 1);
			}
		}
	}
}
for($i=0; $i<count($corners["ileft"]); $i++)
{
	for($j=0; $j<count($corners["ileft"]); $j++)
	{
		if($i == $j) continue;
		
		if(abs($corners["ileft"][$i]["x"] - $corners["ileft"][$j]["x"]) <= $cor_near && abs($corners["ileft"][$i]["y"] - $corners["ileft"][$j]["y"]) <= $cor_near)
		{
			if($corners["ileft"][$i]["y"] != $corners["ileft"][$j]["y"])
			{
				if($corners["ileft"][$i]["y"] > $corners["ileft"][$j]["y"]) array_splice($corners["ileft"], $j, 1);
				else array_splice($corners["ileft"], $i, 1);
			}
			else
			{
				if($corners["ileft"][$i]["x"] < $corners["ileft"][$j]["x"]) array_splice($corners["ileft"], $j, 1);
				else array_splice($corners["ileft"], $i, 1);
			}
		}
	}
}
for($i=0; $i<count($corners["right"]); $i++)
{
	for($j=0; $j<count($corners["right"]); $j++)
	{
		if($i == $j) continue;
		
		if(abs($corners["right"][$i]["x"] - $corners["right"][$j]["x"]) <= $cor_near && abs($corners["right"][$i]["y"] - $corners["right"][$j]["y"]) <= $cor_near)
		{
			if($corners["right"][$i]["y"] != $corners["right"][$j]["y"])
			{
				if($corners["right"][$i]["y"] < $corners["right"][$j]["y"]) array_splice($corners["right"], $j, 1);
				else array_splice($corners["right"], $i, 1);
			}
			else
			{
				if($corners["right"][$i]["x"] > $corners["right"][$j]["x"]) array_splice($corners["right"], $j, 1);
				else array_splice($corners["right"], $i, 1);
			}
		}
	}
}
for($i=0; $i<count($corners["iright"]); $i++)
{
	for($j=0; $j<count($corners["iright"]); $j++)
	{
		if($i == $j) continue;
		
		if(abs($corners["iright"][$i]["x"] - $corners["iright"][$j]["x"]) <= $cor_near && abs($corners["iright"][$i]["y"] - $corners["iright"][$j]["y"]) <= $cor_near)
		{
			if($corners["iright"][$i]["y"] != $corners["iright"][$j]["y"])
			{
				if($corners["iright"][$i]["y"] < $corners["iright"][$j]["y"]) array_splice($corners["iright"], $j, 1);
				else array_splice($corners["iright"], $i, 1);
			}
			else
			{
				if($corners["iright"][$i]["x"] > $corners["iright"][$j]["x"]) array_splice($corners["iright"], $j, 1);
				else array_splice($corners["iright"], $i, 1);
			}
		}
	}
}


foreach($corners as $type)
{
	foreach($type as $corner)
	{
		imagesetpixel($im, $corner["x"], $corner["y"], $red);
	}
}

function isgoodcorner($side, $n, $x, $y, $rx, $ry)
{
	if($n == 1)
	{
		switch($side)
		{
			case "up": if($rx > $x && $ry > $y) return true;
			case "right": if($rx < $x && $ry > $y) return true;
			case "down": if($rx < $x && $ry <= $y) return true;
			case "left": if($rx >= $x && $ry < $y) return true;
			
			case "iup": if($rx > $x && $ry >= $y) return true;
			case "iright": if($rx <= $x && $ry > $y) return true;
			case "idown": if($rx < $x && $ry <= $y) return true;
			case "ileft": if($rx >= $x && $ry < $y) return true;
		}
		return false;
	}
	else
	{
		switch($side)
		{
			case "up": if($rx > $x && $ry > $y) return true;
			case "right": if($rx <= $x && $ry > $y) return true;
			case "down": if($rx < $x && $ry <= $y) return true;
			case "left": if($rx > $x && $ry < $y) return true;
			
			case "iup": if($rx > $x && $ry > $y) return true;
			case "iright": if($rx <= $x && $ry > $y) return true;
			case "idown": if($rx < $x && $ry <= $y) return true;
			case "ileft": if($rx > $x && $ry >= $y) return true;
		}
		return false;
	}
}

function alongside($corner, $rcorner)
{
	global $im, $red, $matrix, $blue;
	$r = 4;
	$dl = 2;
	
	$rasst = sqrt(pow($rcorner["x"] - $corner["x"], 2) + pow($rcorner["y"] - $corner["y"], 2));
	if($rasst > 100) return false;
	//$znam = $rcorner["x"] - $corner["x"];
	//if($znam == 0) $znam = $rcorner["x"] - $corner["x"] - 0.001;
	$k1 = ($rcorner["y"] - $corner["y"] + 0.001)/($rcorner["x"] - $corner["x"] - 0.001);
	$k2 = -1/$k1;
	
	//imagesetpixel($im, $corner["x"], $corner["y"], $red);
	//imagesetpixel($im, $rcorner["x"], $rcorner["y"], $red);
	
	$centers = array();
	$tl = 1;
	while($tl < $dl)
	{
		$centers[$tl-1] = array("x" => $corner["x"] + $tl*($rcorner["x"] - $corner["x"])/($dl), "y" => $corner["y"] + $tl*($rcorner["y"] - $corner["y"])/($dl));
		$tl++;
	}
	
	//$center = array("x" => ($corner["x"] + $rcorner["x"])/2, "y" => ($corner["y"] + $rcorner["y"])/2);
	$bad = 0;
	foreach($centers as $center)
	{
		//imagesetpixel($im, $center["x"], $center["y"], $blue);
		$c2 = $center["y"] - $k2*$center["x"];
		
		$point = array();
		//if($corner["side"] == "up" || $corner["side"] == "down")
		if($k1 >= -1 && $k1 <= 1)
		{
			$point[0] = array("x" => ($center["y"] - $r - $c2)/$k2, "y" => $center["y"] - $r);
			$point[1] = array("x" => ($center["y"] + $r - $c2)/$k2, "y" => $center["y"] + $r);
		}
		else
		{
			$point[0] = array("x" => $center["x"] - $r, "y" => $k2*($center["x"] - $r) + $c2);
			$point[1] = array("x" => $center["x"] + $r, "y" => $k2*($center["x"] + $r) + $c2);
		}
		//imageline($im, $point[0]["x"], $point[0]["y"], $point[1]["x"], $point[1]["y"], $red);
		if($matrix[$point[0]["x"]][$point[0]["y"]] == $matrix[$point[1]["x"]][$point[1]["y"]]) {return false;}
		//else imageline($im, $point[0]["x"], $point[0]["y"], $point[1]["x"], $point[1]["y"], $blue);
	}
	
	return true;
}

function findcorner($corner)
{
	global $corners, $im, $green;
	
	$minrasst1 = 1000000;
	$i1 = 0;
	$i2 = 0;
	$minprom = 0;
	
	switch($corner["side"])
	{
		case "up": $ar1 = "right"; $ar2 = "idown"; break;
		case "right": $ar1 = "down"; $ar2 = "ileft"; break;
		case "down": $ar1 = "left"; $ar2 = "iup"; break;
		case "left": $ar1 = "up"; $ar2 = "iright"; break;
		
		case "iup": $ar1 = "ileft"; $ar2 = "down"; break;
		case "iright": $ar1 = "idown"; $ar2 = "left"; break;
		case "idown": $ar1 = "iright"; $ar2 = "up"; break;
		case "ileft": $ar1 = "iup"; $ar2 = "right"; break;
	}
	
	$i = 0;
	foreach($corners[$ar1] as $rcorner)
	{
		//imageline($im, $corner["x"], $corner["y"], $rcorner["x"], $rcorner["y"], $green);
		
		if(isgoodcorner($corner["side"], 1, $corner["x"], $corner["y"], $rcorner["x"], $rcorner["y"]) && alongside($corner, $rcorner))
		{
			//imageline($im, $corner["x"], $corner["y"], $rcorner["x"], $rcorner["y"], $green);
			
			$rasst = sqrt(pow($rcorner["x"] - $corner["x"], 2) + pow($rcorner["y"] - $corner["y"], 2));
			if($rasst < $minrasst1)
			{
				$minrasst1 = $rasst;
				$fcorner = array("side" => $ar1, "x" => $rcorner["x"], "y" => $rcorner["y"]);
				$i1 = $i;
			}
		}
		$i++;
	}
	
	//print_r($fcorner); print "<br>";
	
	$minprom = $minrasst1;
	//reset($corners);
	
	$i = 0;
	foreach($corners[$ar2] as $rcorner)
	{
		if(isgoodcorner($corner["side"], 2, $corner["x"], $corner["y"], $rcorner["x"], $rcorner["y"]) && alongside($corner, $rcorner))
		{
			//imageline($im, $corner["x"], $corner["y"], $rcorner["x"], $rcorner["y"], $green);
			
			$rasst = sqrt(pow($rcorner["x"] - $corner["x"], 2) + pow($rcorner["y"] - $corner["y"], 2));
			if($rasst < $minrasst1)
			{
				$minrasst1 = $rasst;
				$fcorner = array("side" => $ar2, "x" => $rcorner["x"], "y" => $rcorner["y"]);
				$i2 = $i;
			}
		}
		$i++;
	}
	
	//print_r($fcorner); print "<br>";
	
	if($minprom != $minrasst1) array_splice($corners[$ar2], $i2, 1);
	else array_splice($corners[$ar1], $i1, 1);
	
	return $fcorner;
}

function findsides($house, $corner)
{
	$next = findcorner($corner);
	/*
	if(!$next)
	{
		return true;
	}
	*/
	
	if($next["side"] == "up" && $next["x"] == $house[0]["x"] && $next["y"] == $house[0]["y"])
	{
		return true;
	}
	$house[] = $next;
	findsides(&$house, $next);
}


$houses = array();
$nh = 0;
foreach($corners["up"] as $corner)
{
//$corner = $corners["up"][3];
	$houses[$nh][0]["side"] = "up";
	$houses[$nh][0]["x"] = $corner["x"];
	$houses[$nh][0]["y"] = $corner["y"];
	
	//imagesetpixel($im, $corner["x"], $corner["y"], $green);
	
	$corner["side"] = "up";
	findsides(&$houses[$nh], $corner);
	$nh++;
}

foreach($houses as $house)
{
for($i=count($house)-1; $i>=0; $i--)
{
	//imagesetpixel($im, $house[$i]["x"], $house[$i]["y"], $red);
	
	if($i-1 < 0) $j = 0;
	else $j = $i-1;
	
	//fwrite($fp, ' '.(($house[$i]["x"]-128)).' 0 '.(($house[$i]["y"]-128))).',';
	
	
	imageline($im, $house[$i]["x"], $house[$i]["y"], $house[$j]["x"], $house[$j]["y"], $red);
}
}


//print_r($houses);

/*
$wrl = "ufa.wrl";
$fp = fopen($wrl, "w");
fwrite($fp, '
#VRML V2.0 utf8
Viewpoint {
position 0 1 5
orientation 1 1 2 0.05
}

Transform
{
	translation 0 0 0
	children
	[
		Shape
		{
			geometry Box{size 256 0.02 256}
			appearance Appearance
			{
				texture ImageTexture {url "wrl/tiles0.png"}
			}
			
		}
	]
}

');

foreach($houses as $house)
{
	fwrite($fp, '
Shape
{
	geometry IndexedFaceSet
	{
		coord Coordinate
		{
			point [
');
	for($i=count($house)-1; $i>=0; $i--)
	{
		//imagesetpixel($im, $house[$i]["x"], $house[$i]["y"], $red);
		
		//if($i-1 < 0) $j = 0;
		//else $j = $i-1;
		
		fwrite($fp, ' '.(($house[$i]["x"]-128)).' 0 '.(($house[$i]["y"]-128))).',';
		
		
		//imageline($im, $house[$i]["x"], $house[$i]["y"], $house[$j]["x"], $house[$j]["y"], $red);
	}
	for($i=count($house)-1; $i>=0; $i--)
	{
		//imagesetpixel($im, $house[$i]["x"], $house[$i]["y"], $red);
		
		//if($i-1 < 0) $j = 0;
		//else $j = $i-1;
		
		fwrite($fp, ' '.(($house[$i]["x"]-128)).' 20 '.(($house[$i]["y"]-128)));
		if($i != 0) fwrite($fp, ",");
		
		
		//imageline($im, $house[$i]["x"], $house[$i]["y"], $house[$j]["x"], $house[$j]["y"], $red);
	}
	fwrite($fp, '
	]
		}
		color Color
		{
			color [1 0 0, 1 0 0, 1 0 0, 1 0 0, 1 0 0, 1 0 0, 1 0 0, 1 0 0]
		}
		coordIndex [0 1 2 3 -1 4 5 6 7 -1 0 1 5 4 -1 0 3 7 4 -1 1 2 6 5 -1 ]
		solid FALSE
	}
}

');
}

fclose($fp);
*/

header("Content-type: image/png"); imagepng($im);

//print_r($corners);

?>