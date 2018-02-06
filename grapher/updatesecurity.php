<?php

$files = glob("./*/password.php");

foreach ($files as $file){
	$dir = dirname($file);
	$password = file_get_contents($file);
	file_put_contents($file,"<?php \$correctpass='$password'; ?>");
	file_put_contents($dir."/index.php",file_get_contents('./updatesecuritynewindex.php'));
}

echo "Done";

?>