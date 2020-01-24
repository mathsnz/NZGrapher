<?php
$url = urldecode($_GET['dataset']);
$url = str_replace(" ","%20",$url);
$file_headers = @get_headers($url);
print_r($file_headers);
// we want the the last errorcode, reverse array so we start at the end:
$file_headers = array_reverse($file_headers);
$code = "";
foreach($file_headers as $hline){
	// search for things like "HTTP/1.1 200 OK" , "HTTP/1.0 200 OK" , "HTTP/1.1 301 PERMANENTLY MOVED" , "HTTP/1.1 400 Not Found" , etc.
	// note that the exact syntax/version/output differs, so there is some string magic involved here
	if(preg_match('/^HTTP\/\S+\s+([1-9][0-9][0-9])\s+.*/', $hline, $matches) ){// "HTTP/*** ### ***"
		$code = $matches[1];
		break;
	}
}
$code = "200";
if($code!="200"){
	echo "Error\r\n";
	echo $url;
	print_r($file_headers);
} else {
	echo file_get_contents($url);
}
?>
