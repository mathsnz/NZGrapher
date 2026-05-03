<?php

$files = glob("./*/password.php");

foreach ($files as $file){
	$dir = dirname($file);
	$password = file_get_contents($file);
	file_put_contents($dir."/index.php",file_get_contents('./updatesecuritynewindex.txt'));
	file_put_contents($dir."/delete.php",file_get_contents('./updatesecuritynewdelete.txt'));
	file_put_contents($dir."/upload.php",file_get_contents('./updatesecuritynewupload.txt'));
	if(substr($password,0,1)!="<"){
		file_put_contents($file,"<?php \$correctpass='$password'; ?>");
	}
}

$files = glob("./*/datasettings.php");
foreach ($files as $file){
	include($file);
	$data = unserialize(base64_decode($data));
	if(is_array($data)){
		file_put_contents($file,'<?php $data="'.base64_encode(json_encode($data)).'"; ?>');
	}
}

echo "Done";

?>