<?php

//prevent the checking of SSL certificates, as this breaks things on some servers
stream_context_set_default( [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);

$url = urldecode($_GET['dataset']);
if(substr_count($url,":")>1){
	die("You cannot use a port number in the url or recursive queries. If this is a valid link download the csv and then upload to NZGrapher. ".substr_count($url,":"));
}
$url = str_replace(" ","%20",$url);
$file_headers = get_headers($url);

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

if($code!="200"){
	echo "Error\r\n";
	echo $url;
	print_r($file_headers);
} else {
	header('Content-Type: charset=utf-8');
	echo utf8_encode(file_get_contents($url));
}
?>
