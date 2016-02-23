<?php

$amount = 10000;
$tp = 0.0001;
$sl = 0.01;
//$slmid = 0.002;
$spread = 0.00011;
$info = false;

$all = 0;
$all2 = 0;
$money = 0;
$money2 = 0;
$status = false;
$status2 = false;
$direction = 0;
$direction2 = 0;
$bp = 0;
$bp2 = 0;

$price = 0;
$date = 0;
$wi = 0;

//$files = array("EURUSD0908.txt", "EURUSD1008.txt", "EURUSD1108.txt", "EURUSD1208.txt");
$files = array("GBPUSD0908.txt", "GBPUSD1008.txt", "GBPUSD1108.txt", "GBPUSD1208.txt");
$files2 = array("USDCHF0908.txt", "USDCHF1008.txt", "USDCHF1108.txt", "USDCHF1208.txt");

if($info) print "<table border=1><tr><td>Date</td><td>Time</td><td>Price</td><td>St 1</td><td>Dir 1</td><td>BP 1</td><td>Money 1</td><td>All 1</td><td>St 2</td><td>Dir 2</td><td>BP 2</td><td>Money 2</td><td>All 2</td></tr>";

//foreach($files as $file)
//{
for($fi=0; $fi<count($files); $fi++)
{
	$ss = file($files[$fi]);
	$ss2 = file($files2[$fi]);
	

//for($i=count($ss)-1; $i>=0; $i--)
for($i=0; $i<count($ss); $i++)
{
	$arr = explode(",", $ss[$i]);
	$price = $arr[3];
	
	$arr2 = explode(",", $ss2[$i]);
	$price2 = $arr2[3];
	
	
	//if($info) print "Status: ".$status."<br>money: ".$money."<br>all: ".$all."<br>price: ".$price."<br>";
	if($info) print "<tr><td>".$arr[1]."</td><td>".$arr[2]."</td><td>".$price."</td><td>".$status."</td><td>".$direction."</td><td>".$bp."</td><td>".$money."</td><td>".$all."</td><td>".$status2."</td><td>".$direction2."</td><td>".$bp2."</td><td>".$money2."</td><td>".$all2."</td></tr>";
	
	if($date != $arr[1])
	{
		$all += $money;
		$all2 += $money2;
		print "Day 1: ".$money.", Day 2: ".$money2.", all 1: ".$all.", all 2: ".$all2."<br>";
		$money = 0;
		$money2 = 0;
	}
	$date = $arr[1];
	
	if(!$status)
	{
		$status = true;
		
		if($direction == 1) $bp = $price + $spread;
		else $bp = $price - $spread;
		
		$wi = $i;
		//if($info) print "1 We've bought in direction ".$direction.", price: ".$price."<br><br>";
	}
	else
	{
		if(($price - $bp >= $tp && $direction == 1) || ($bp - $price >= $tp && $direction == 0))
		{
			$profit = abs(($price/$bp - 1)*$amount);
			$money += $profit;
			
			if($direction == 1) $bp = $price + $spread;
			else $bp = $price - $spread;
			
			/*
			if($direction != $direction2)
			{
				if($status2)
				{
					if($direction2 == 1) $money2 += ($price/$bp2 - 1)*$amount;
					else $money2 += (1 - $price/$bp2)*$amount;
					$status2 = false;
				}
				$direction2 = $direction;
			}
			*/
			//if($info) print "1 We've taken profit and bought in direction ".$direction.", waited ".($i-$wi)." minutes, price: ".$price."<br><br>";
			if($info) print "<tr><td colspan=20>1 We've taken profit ".$profit." and bought in direction ".$direction."</td></tr>";
			$wi = $i;
		}
		elseif(($bp+$sl <= $price && $direction == 0) || ($bp-$sl >= $price && $direction == 1))
		{
			
			$profit = abs(($price/$bp - 1)*$amount);
			$money -= $profit;
			
			$direction = 1 - $direction;
			$status = false;
			
			/*
			if($status2)
			{
				if($direction2 == 1) $money2 += ($price/$bp2 - 1)*$amount;
				else $money2 += (1 - $price/$bp2)*$amount;
				$status2 = false;
			}
			$direction2 = 1 - $direction2;
			*/
			
			//if($info) print "1 We've sold and reversed, lost ".abs(($price/$bp - 1)*$amount).", waited ".($i-$wi)." minutes, price: ".$price."<br><br>";
			if($info) print "<tr><td colspan=20>1 We've lost ".$profit.", dir 1 changed to ".$direction.", dir 2 changed to ".$direction2."</td></tr>";
		}
		/*
		elseif(($bp+$slmid <= $price && $direction == 0) || ($bp-$slmid >= $price && $direction == 1))
		{
			if($direction == $direction2)
			{
				if($status2)
				{
					if($direction2 == 1) $money2 += ($price/$bp2 - 1)*$amount;
					else $money2 += (1 - $price/$bp2)*$amount;
					$status2 = false;
				}
				$direction2 = 1 - $direction;
			}
		}
		*/
		
	}
	
	$price = $price2;
	
	if(!$status2)
	{
		$status2 = true;
		
		if($direction2 == 1) $bp2 = $price + $spread;
		else $bp2 = $price - $spread;
		
		$wi2 = $i;
		//if($info) print "2 We've bought in direction ".$direction2.", price: ".$price."<br><br>";
	}
	else
	{
		if(($price - $bp2 >= $tp && $direction2 == 1) || ($bp2 - $price >= $tp && $direction2 == 0))
		{
			$profit = abs(($price/$bp2 - 1)*$amount);
			$money2 += $profit;
			
			if($direction2 == 1) $bp2 = $price + $spread;
			else $bp2 = $price - $spread;
			/*
			if($direction != $direction2)
			{
				if($status)
				{
					if($direction == 1) $money += ($price/$bp - 1)*$amount;
					else $money += (1 - $price/$bp)*$amount;
					$status = false;
				}
				$direction = $direction2;
			}
			*/
			
			//if($info) print "2 We've taken profit and bought in direction ".$direction2.", waited ".($i-$wi2)." minutes, price: ".$price."<br><br>";
			if($info) print "<tr><td colspan=20>2 We've taken profit ".$profit." and bought in direction ".$direction2."</td></tr>";
			$wi2 = $i;
		}
		elseif(($bp2+$sl <= $price && $direction2 == 0) || ($bp2-$sl >= $price && $direction2 == 1))
		{
			$profit = abs(($price/$bp2 - 1)*$amount);
			$money2 -= $profit;
			
			$direction2 = 1 - $direction2;
			$status2 = false;
			
			/*
			if($status)
			{
				if($direction == 1) $money += ($price/$bp - 1)*$amount;
				else $money += (1 - $price/$bp)*$amount;
				$status = false;
			}
			$direction = 1 - $direction;
			*/
			
			//if($info) print "2 We've sold and reversed, lost ".abs(($price/$bp2 - 1)*$amount).", waited ".($i-$wi2)." minutes, price: ".$price."<br><br>";
			if($info) print "<tr><td colspan=20>2 We've lost ".$profit.", dir 2 changed to ".$direction2."</td></tr>";
		}
		/*
		elseif(($bp2+$slmid <= $price && $direction2 == 0) || ($bp2-$slmid >= $price && $direction2 == 1))
		{
			if($direction == $direction2)
			{
				if($status)
				{
					if($direction == 1) $money += ($price/$bp - 1)*$amount;
					else $money += (1 - $price/$bp)*$amount;
					$status = false;
				}
				$direction = 1 - $direction2;
			}
		}
		*/
	}
	
	
}
}

print "</table>";
?>