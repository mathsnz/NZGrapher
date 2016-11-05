<?php
$reg = './Roboto-Regular.ttf';
$bold = './Roboto-Bold.ttf';

//Left
imagettftext($im,10,0,5,$height-5,$black,$reg,"Made with NZGrapher");

//Right
$bbox = imagettfbbox(10, 0, $reg, "www.mathsnz.com");
$w = $bbox[4]-$bbox[0];
imagettftext($im,10,0,$width-$w-5,$height-5,$black,$reg,"www.mathsnz.com");

?>