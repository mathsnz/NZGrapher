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
$file_headers = get_headers($url, 1);

if ($file_headers === false) {
	die("Error: Unable to access URL: " . $url);
}

// get the type of file recieved
$content_type = '';
if (isset($file_headers['Content-Type'])) {
    $content_type = strtolower($file_headers['Content-Type']);
}

// check if the recieved content is the right type (not a website or unwanted filetype)
if (strpos($content_type, 'text/csv') === false && 
    strpos($content_type, 'text/plain') === false &&
    strpos($content_type, 'application/csv') === false) {
    die("Error: File is not a CSV. Content-Type: " . $content_type);
}

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
	$content = file_get_contents($url);
	if (!mb_check_encoding($content, 'UTF-8')) {
		$content = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');
	}
	echo $content;
}
?>
