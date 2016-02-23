function getRequestBody(oForm) { 
	var aParams = new Array();
	for(var i = 0; i < oForm.elements.length; i++) {
		var sParam = encodeURIComponent(oForm.elements[i].name);
		sParam += "=";
		sParam += encodeURIComponent(oForm.elements[i].value);
		aParams.push(sParam);
	}
	return aParams.join("&");
}

function newoption(i)
{
	location = "settings.php?act=newoption&i="+i+"&field="+encodeURIComponent(document.getElementById("optionfield").value);
}
function editoption2(i)
{
	var sdiv = document.getElementById("soption"+i);
	var text = document.getElementById("option"+i).innerHTML;
	
	sdiv.innerHTML = '<form action="javascript: newoption('+i+');"><textarea cols="50" rows="1" id="optionfield">'+text+'</textarea><input type="submit" value="Изменить" id="optionsubmit"></form>';
}


/*
$im1 = imagecreatefromjpeg($file1);
$white = imagecolorallocatealpha($im1, 255, 255, 255, 127);
$im1 = imagerotate($im1, rand(-45, 45), $white);
imagecopyresampled($col, $im1, 0, 0, 0, 0, imagesx($im1)/$koef, imagesy($im1)/$koef, imagesx($im1), imagesy($im1));

$im1 = imagecreatefromjpeg($file2);
$im1 = imagerotate($im1, rand(-45, 45), $white);
imagecopyresampled($col, $im1, 52, 0, 0, 0, imagesx($im1)/$koef, imagesy($im1)/$koef, imagesx($im1), imagesy($im1));

$im1 = imagecreatefromjpeg($file3);
$im1 = imagerotate($im1, rand(-45, 45), $white);
imagecopyresampled($col, $im1, 0, 39, 0, 0, imagesx($im1)/$koef, imagesy($im1)/$koef, imagesx($im1), imagesy($im1));

$im1 = imagecreatefromjpeg($file4);
$im1 = imagerotate($im1, rand(-45, 45), $white);
imagecopyresampled($col, $im1, 52, 39, 0, 0, imagesx($im1)/$koef, imagesy($im1)/$koef, imagesx($im1), imagesy($im1));

$im1 = imagecreatefromjpeg($file5);
$im1 = imagerotate($im1, rand(-45, 45), $white);
imagecopyresampled($col, $im1, 26, 19, 0, 0, imagesx($im1)/$koef, imagesy($im1)/$koef, imagesx($im1), imagesy($im1));
*/