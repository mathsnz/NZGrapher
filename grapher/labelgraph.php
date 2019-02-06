<?php
$reg = './Roboto-Regular.ttf';
$bold = './Roboto-Bold.ttf';

$scalefactor=$_POST['scalefactor'];

//Left
imagettftext($im,10*$scalefactor,0,5*$scalefactor,$height-5*$scalefactor,$black,$reg,"Made with NZGrapher");

//Right
$bbox = imagettfbbox(10*$scalefactor, 0, $reg, "www.mathsnz.com");
$w = $bbox[4]-$bbox[0];
imagettftext($im,10*$scalefactor,0,$width-$w-5*$scalefactor,$height-5*$scalefactor,$black,$reg,"www.mathsnz.com");

?>
