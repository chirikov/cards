<?php

$ch = curl_init("http://www.world-gazetteer.com/wg.php?x=&men=stcl&lng=en&des=wg&srt=npan&col=abcdefghinoq&msz=1500");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
curl_close($ch);

print $data;

?>