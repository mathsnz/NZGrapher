<?php
$url = urldecode($_GET['dataset']);
$file_headers = @get_headers($url);
if($file_headers[0]!="HTTP/1.1 200 OK"){
	echo "Error\r\n";
	print_r($file_headers);
} else {
	echo file_get_contents(urldecode($_GET['dataset']));
}
?>
