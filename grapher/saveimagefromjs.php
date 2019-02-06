<?php

//fix settings on some silly servers
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

//converting
$img = $_POST['imgBase64'];
if(array_key_exists('highres',$_POST)){
	$highres = $_POST['highres'];
} else {
	$highres = "no";
}

$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);

//saving
$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$fileName = "./imagetemp/$randomString.png";
file_put_contents($fileName, $fileData);

$extrastyle='';
if($highres=='yes'){
	$extrastyle='style="width:100%;height:100%;"';
}

//display
echo "<img $extrastyle src='./imagetemp/$randomString.png'usemap='#graphmap'>";

// Free up memory
$path = './imagetemp/';
if ($handle = opendir($path)) {
	while (false !== ($file = readdir($handle))) {
		if ((time()-filectime($path.$file)) > 1800) {
			if (is_file($path.$file)) {
				unlink($path.$file);
			}
		}
	}
}
?>
