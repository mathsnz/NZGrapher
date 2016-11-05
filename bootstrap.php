<?php
//regresion?
$regression=$_POST['regression'];
//graph title
$title=stripslashes($_POST['title']);
//yaxis lavel
$ylabel=stripslashes($_POST['yaxis']);
//xaxis label
$xlabel=stripslashes($_POST['xaxis']);
//labels?
$label=$_POST['labels'];
//points
if(isset($_POST['xvals'])){$oxpoints=explode(',', $_POST['xvals']);} else {$oxpoints=array("");}
if(isset($_POST['yvals'])){$ypoints=explode(',', $_POST['yvals']);} else {$ypoints=array("");}
$psize=$_POST['size'];
$type=$_POST['type'];

//dimension
$width=$_POST['width'];
$height=$_POST['height'];

array_pop($oxpoints);

//clean points
$pointlabels=array();
$i=0;
$pointsremoved="id of Points Removed: ";
foreach($oxpoints as $xpoint){
	array_push($pointlabels,$i+1);
	if(!is_numeric($xpoint)){
		unset($oxpoints[$i]);
		unset($ypoints[$i]);
		unset($pointlabels[$i]);
		$pointsremoved.=($i+1).", ";
	}
	$i++;
}
$oxpoints = array_values($oxpoints);
$ypoints = array_values($ypoints);
$pointlabels = array_values($pointlabels);

if(count($oxpoints)<5){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable with at least 5 datapoints</center>";
	die();
}
if(is_numeric($oxpoints[0])==FALSE){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}

// Create image and define colours
if($width<1 || $height<1){echo "Invalid Image Dimensions";die();}
$im = imagecreatetruecolor($width, $height);
$height=$height/2;
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$grey = imagecolorallocatealpha($im, 100, 100, 100, 120);
$lightgrey = imagecolorallocatealpha($im, 100, 100, 100, 126);
$red = imagecolorallocate($im, 255, 0, 0);
$green = imagecolorallocate($im, 0, 255, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$reg = './Roboto-Regular.ttf';
$bold = './Roboto-Bold.ttf';

//make image white
imagefill ($im, 0, 0, $white);

//draw area of graph
imageline ($im, 50, $height-50, $width-50, $height-50, $black);

//graph title
$bbox = imagettfbbox(12, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 14, 0, $width/2-$w/2, 30, $black, $bold, $title);

//
//Axis Labels
//

$labelfont=4;

//xaxis lavel
$bbox = imagettfbbox(12, 0, $bold, $xlabel);
$w = $bbox[4]-$bbox[0];
imagettftext($im,12,0,$width/2-$w/2,$height-5,$black,$bold,$xlabel);

function format_number_significant_figures($number, $sf) {
  // How many decimal places do we round and format to?
  // @note May be negative.
  $dp = floor($sf - log10(abs($number)));
  // Round as a regular number.
  $number = round($number, $dp);
  // Leave the formatting to format_number(), but always format 0 to 0dp.
  return number_format($number, 0 == $number ? 0 : $dp,".","");
}

function FirstSF($number) {
    $multiplier = 1;
	if ($number==0){
		return 0;
	} else {
		while ($number < 0.1) {
			$number *= 10;
			$multiplier /= 10;
		}
		while ($number >= 1) {
			$number /= 10;
			$multiplier *= 10;
		}
		return round($number, 1) * 10;
	}
}

include 'meanmedsd.php';

$i=0;
$categories=array();
foreach($oxpoints as $xpoint) {
	if(count($ypoints)==1){
		$category=" ";
	} else {
		$category=$ypoints[$i];
	}
	$i++;
	if(array_key_exists($category,$categories)){
		array_push($categories["$category"], array($xpoint,$i));;
	} else {
		$categories["$category"]=array(array($xpoint,$i));
	}
}

$lqs=array();
$uqs=array();
foreach ($categories as $values){
	$points=array();
	foreach($values as $value){
		array_push($points,$value[0]);
	}
	$lq=array_firstQuartile($points,0.5);
	$uq=array_thirdQuartile($points,0.5);
	array_push($lqs,$lq);
	array_push($uqs,$uq);
	$i++;
}

//x axis ticks
$minx=min($oxpoints);
$maxx=max($oxpoints);
$range=$maxx-$minx;
$rangeround=format_number_significant_figures($range,1);
$steps=FirstSF($rangeround);
if($steps==0){$steps=1;}
if($steps<2) {
	$steps=$steps*10;
}
if($steps<3) {
	$steps=$steps*5;
}
if($steps<5) {
	$steps=$steps*2;
}
$step=$rangeround/$steps;
$minxtick=round($minx/$step)*$step;
if($minxtick>=$minx){
	$minxtick=$minxtick-$step;
}
$maxxtick=round($maxx/$step)*$step;
if($maxxtick<=$maxx){
	$maxxtick=$maxxtick+$step;
}
$steps=($maxxtick-$minxtick)/$step;

//set font size for x-axis
$size=0;
$w=0;
while($w<30 && $size<11){
	$bbox = imagettfbbox($size, 0, $reg, $minxtick);
	$w1 = $bbox[4]-$bbox[0];
	$bbox = imagettfbbox($size, 0, $reg, $maxxtick);
	$w2 = $bbox[4]-$bbox[0];
	$w=max($w1,$w2);
	$size++;
}
$size--;


//min x mark
$bbox = imagettfbbox($size, 0, $reg, $minxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, 60-$w/2, $height-30, $black, $reg, $minxtick);
imageline ($im, 60, $height-50, 60, $height-45, $black);
//max x mark
$bbox = imagettfbbox($size, 0, $reg, $maxxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, $width-60-$w/2, $height-30, $black, $reg, $maxxtick);
imageline ($im, $width-60, $height-50, $width-60, $height-45, $black);

//other ticks
$i=1;
$distanceright=60;
$xtick=$minxtick;
while($i<$steps){
	$distanceright=$distanceright+($width-120)/$steps;
	$xtick=$xtick+$step;
	$bbox = imagettfbbox($size, 0, $reg, $xtick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, $distanceright-$w/2, $height-30, $black, $reg, $xtick);
	imageline ($im, $distanceright, $height-50, $distanceright, $height-45, $black);
	$i++;
}

ksort($categories);
$offset=0;
$values=array();
$cnames=array();
foreach($categories as $key => $value){
$category=$key;
array_push($cnames,$category);



$xpoints=array();
$labels=array();
foreach($categories["$category"] as $xvals){
	array_push($xpoints,$xvals[0]);
	array_push($labels,$xvals[1]);
}


$i=0;

//TEMP IMAGE FOR THE SUBSET
$imheight=($height-120);
// define temp image
if($width-120<1 || $imheight<1){echo "Invalid Image Dimensions";die();}
$plot = imagecreatetruecolor($width-120, $imheight);
imagefill ($plot, 0, 0, $white);
$bbox = imagettfbbox(12, 0, $bold, $category);
$w = $bbox[4]-$bbox[0];
imagettftext($plot, 10, 0, $width-120-$w, $imheight*0.4, $black, $bold, $category);


$min=min($xpoints);
$mingraph=round((($width-120)*($min-$minxtick)/($maxxtick-$minxtick))/5,0)*5;
$LQ=array_firstQuartile($xpoints);
$LQgraph=round((($width-120)*($LQ-$minxtick)/($maxxtick-$minxtick))/5,0)*5;
$med=Quartile($xpoints,0.5);
$medgraph=round((($width-120)*($med-$minxtick)/($maxxtick-$minxtick))/5,0)*5;
$mean=format_number_significant_figures(array_sum($xpoints)/count($xpoints),5);
$meangraph=($width-120)*($mean-$minxtick)/($maxxtick-$minxtick);
$UQ=array_thirdQuartile($xpoints);
$UQgraph=round((($width-120)*($UQ-$minxtick)/($maxxtick-$minxtick))/5,0)*5;
$max=max($xpoints);
$maxgraph=round((($width-120)*($max-$minxtick)/($maxxtick-$minxtick))/5,0)*5;
$sd=format_number_significant_figures(sd($xpoints),5);
$num=count($xpoints);
$y=$imheight*0.9-1;
$h=$imheight*0.1;

$top=$imheight*0.5-39;
if($regression=="yes") {
	imagettftext($plot, 8, 0, 0, $top+8, $red, $reg, "min:  $min");
	imagettftext($plot, 8, 0, 0, $top+18, $red, $reg, "LQ:   $LQ");
	imagettftext($plot, 8, 0, 0, $top+28, $red, $reg, "med:  $med");
	imagettftext($plot, 8, 0, 0, $top+38, $red, $reg, "mean:  $mean");
	imagettftext($plot, 8, 0, 0, $top+48, $red, $reg, "UQ:  $UQ");
	imagettftext($plot, 8, 0, 0, $top+58, $red, $reg, "max:  $max");
	imagettftext($plot, 8, 0, 0, $top+68, $red, $reg, "sd:  $sd");
	imagettftext($plot, 8, 0, 0, $top+78, $red, $reg, "num:  $num");
}


$xpixels=array();
$xlabels=array();
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$xpixel=round((($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick))/$psize,0)*$psize;
	$i++;
	array_push($xpixels,intval($xpixel));
	array_push($xlabels,$labels[$i-1]);
}

array_multisort($xpixels,$xlabels);



$i=0;
$lastxpixel=-100000;
$count=array_count_values($xpixels);
$max=max($count);
$yheight=($imheight*0.9-10)/$max;
if($yheight>$psize){$yheight=$psize;}

foreach($xpixels as $xpixel) {
	if($lastxpixel==$xpixel) {
		$ypixel=$ypixel-$yheight;
	} else {
		$ypixel=$imheight*0.9-5;
	}
	$lastxpixel=$xpixel;
	imagesetthickness($plot,2);
	imagearc ($plot , $xpixel , $ypixel , $psize , $psize , 0, 359.9 , $grey);
	imagesetthickness($plot,1);
	if($label=="yes") {
		imagettftext($plot, 7, 0, $xpixel+3, $ypixel+3, $blue, $reg, $pointlabels[$xlabels[$i]-1]);
	}
	$i++;
}
	if($type=='Mean'){
		imagefilledellipse ($plot,$meangraph,$y,8,8,$red);
	}
	if($type=='Median'){
		imageline ($plot, $mingraph, $y-$h/2, $mingraph, $y+$h/2, $black);
		imageline ($plot, $LQgraph, $y-$h, $LQgraph, $y+$h, $black);
		imagesetthickness($plot,3);
		imageline ($plot, $medgraph, $y-$h, $medgraph, $y+$h, $red);
		imagesetthickness($plot,1);
		imageline ($plot, $UQgraph, $y-$h, $UQgraph, $y+$h, $black);
		imageline ($plot, $maxgraph, $y-$h/2, $maxgraph, $y+$h/2, $black);
		imageline ($plot, $mingraph, $y, $LQgraph, $y, $black);
		imageline ($plot, $LQgraph, $y+$h, $UQgraph, $y+$h, $black);
		imageline ($plot, $LQgraph, $y-$h, $UQgraph, $y-$h, $black);
		imageline ($plot, $UQgraph, $y, $maxgraph, $y, $black);
	}
	if($type=='IQR'){
		imageline ($plot, $mingraph, $y-$h/2, $mingraph, $y+$h/2, $black);
		imageline ($plot, $LQgraph, $y-$h, $LQgraph, $y+$h, $black);
		imageline ($plot, $medgraph, $y-$h, $medgraph, $y+$h, $black);
		imageline ($plot, $UQgraph, $y-$h, $UQgraph, $y+$h, $black);
		imageline ($plot, $maxgraph, $y-$h/2, $maxgraph, $y+$h/2, $black);
		imageline ($plot, $mingraph, $y, $LQgraph, $y, $black);
		imageline ($plot, $LQgraph, $y+$h, $UQgraph, $y+$h, $black);
		imageline ($plot, $LQgraph, $y-$h, $UQgraph, $y-$h, $black);
		imageline ($plot, $UQgraph, $y, $maxgraph, $y, $black);
		imagesetthickness($plot,3);
		imageline ($plot, $LQgraph, $y, $UQgraph, $y, $red);
		imagesetthickness($plot,1);

	}
	if($type=='Standard Deviation'){
		imagefilledellipse ($plot,$meangraph,$y,8,8,$red);
		$x1=($width-120)*($mean-$sd-$minxtick)/($maxxtick-$minxtick);
		$x2=($width-120)*($mean+$sd-$minxtick)/($maxxtick-$minxtick);
		imagesetthickness($plot,3);
		imageline ($plot,$x1,$y,$x2,$y,$red);
		imagesetthickness($plot,1);
	}

// copy the temp image back to the real image
imagecopy ($im, $plot, 60, 60+$offset, 0, 0, $width-120, $imheight);
// destroy temp images, clear memory
imagedestroy($plot);

$offset=$offset+$imheight;

}

$height=$height*2;


	if($type=='Mean'){
		$title="Bootstrap - Mean";
	}
	if($type=='Median'){
		$title="Bootstrap - Median";
	}
	if($type=='IQR'){
		$title="Bootstrap - IQR";
	}
	if($type=='Standard Deviation'){
		$title="Bootstrap - Standard Deviation";
	}


$bbox = imagettfbbox(12, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 12, 0, $width/2-$w/2, $height-10, $black, $bold, $title);

$bootstrapvals=array();
$num=count($oxpoints);
$b=0;
while($b<1000){
	$i=0;
	$bootstrapsample=array();
	while($i<$num){
		$select=mt_rand(0,$num-1);
		array_push($bootstrapsample,$oxpoints[$select]);
		$i++;
	}
	if($type=='Mean'){
		$value=array_sum($bootstrapsample)/count($bootstrapsample);
	}
	if($type=='Median'){
		$value=Quartile($bootstrapsample,0.5);
	}
	if($type=='IQR'){
		$value=array_thirdQuartile($bootstrapsample)-array_firstQuartile($bootstrapsample);
	}
	if($type=='Standard Deviation'){
		$value=format_number_significant_figures(sd($bootstrapsample),5);
	}
	array_push($bootstrapvals,$value);
	$b++;
}
	if($type=='Mean'){
		$offset=0;
	}
	if($type=='Median'){
		$offset=0;
	}
	if($type=='IQR'){
		$offset=-($minxtick+$maxxtick)/2+array_sum($bootstrapvals)/count($bootstrapvals);
	}
	if($type=='Standard Deviation'){
		$offset=-($minxtick+$maxxtick)/2+array_sum($bootstrapvals)/count($bootstrapvals);
	}

$offset=floor($offset/$step);
$offset=$step*$offset;
$minxtick=$minxtick+$offset;
$maxxtick=$maxxtick+$offset;

//draw area of graph
imageline ($im, 50, $height-50, $width-50, $height-50, $black);


//set font size for x-axis
$size=0;
$w=0;
while($w<30 && $size<11){
	$bbox = imagettfbbox($size, 0, $reg, $minxtick);
	$w1 = $bbox[4]-$bbox[0];
	$bbox = imagettfbbox($size, 0, $reg, $maxxtick);
	$w2 = $bbox[4]-$bbox[0];
	$w=max($w1,$w2);
	$size++;
}
$size--;


//min x mark
$bbox = imagettfbbox($size, 0, $reg, $minxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, 60-$w/2, $height-30, $black, $reg, $minxtick);
imageline ($im, 60, $height-50, 60, $height-45, $black);
//max x mark
$bbox = imagettfbbox($size, 0, $reg, $maxxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, $width-60-$w/2, $height-30, $black, $reg, $maxxtick);
imageline ($im, $width-60, $height-50, $width-60, $height-45, $black);

//other ticks
$i=1;
$distanceright=60;
$xtick=$minxtick;
while($i<$steps){
	$distanceright=$distanceright+($width-120)/$steps;
	$xtick=$xtick+$step;
	if(abs($xtick)<pow(10,-10)){$xtick=0;};
	$bbox = imagettfbbox($size, 0, $reg, $xtick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, $distanceright-$w/2, $height-30, $black, $reg, $xtick);
	imageline ($im, $distanceright, $height-50, $distanceright, $height-45, $black);
	$i++;
}

rsort($bootstrapvals);

$xpixels=array();
foreach($bootstrapvals as $xvalue){
	$xpixel=round((($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick))/($psize/2),0)*$psize/2+60;
	$i++;
	array_push($xpixels,intval($xpixel));
}

$i=0;
$lastxpixel=-100000;
$count=array_count_values($xpixels);
$max=max($count);
$yheight=($height*0.5-100)/$max;
if($yheight>$psize){$yheight=$psize;}
$color=$lightgrey;
foreach($xpixels as $xpixel) {
	if($lastxpixel==$xpixel) {
		$ypixel=$ypixel-$yheight;
	} else {
		$ypixel=$height-90;
	}
	$lastxpixel=$xpixel;
	if($i==25){
		$color=$grey;
	}
	if($i==975){
		$color=$lightgrey;
	}
	imagesetthickness($im,2);
	imagearc ($im , $xpixel , $ypixel , $psize , $psize , 0, 359.9 , $color);
	imagesetthickness($im,1);
	$i++;
}

//draw the line for the value;
$x=0;
if($type=='Mean'){
	$x=($width-120)*($mean-$minxtick)/($maxxtick-$minxtick)+60;
	$val=format_number_significant_figures($mean,4);
}
if($type=='Median'){
	$x=($width-120)*($med-$minxtick)/($maxxtick-$minxtick)+60;
	$val=format_number_significant_figures($med,4);
}
if($type=='IQR'){
	$x=($width-120)*(($uq-$lq)-$minxtick)/($maxxtick-$minxtick)+60;
	$val=format_number_significant_figures($uq-$lq,4);
}
if($type=='Standard Deviation'){
	$x=($width-120)*($sd-$minxtick)/($maxxtick-$minxtick)+60;
	$val=format_number_significant_figures($sd,4);
}

imagesetthickness ($im,2);
imageline($im,$x,$height*0.5,$x,$height-80,$red);


imagesetthickness ($im,2);
$min=$bootstrapvals[25];
$minxpixel=round((($width-120)*($min-$minxtick)/($maxxtick-$minxtick)),0)+60;
imageline($im,$minxpixel,$height-65,$minxpixel,$height-120,$blue);

$max=$bootstrapvals[975];
$maxxpixel=round((($width-120)*($max-$minxtick)/($maxxtick-$minxtick)),0)+60;
imageline($im,$maxxpixel,$height-75,$maxxpixel,$height-120,$blue);

imagesetthickness ($im,10);
imageline($im,$minxpixel,$height-115,$maxxpixel,$height-115,$blue);

$min=format_number_significant_figures($min,4);
$bbox = imagettfbbox(8, 0, $reg, $min);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 8, 0, $minxpixel-$w/2, $height-55, $blue, $reg, $min);

$max=format_number_significant_figures($max,4);
$bbox = imagettfbbox(8, 0, $reg, $max);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 8, 0, $maxxpixel-$w/2, $height-65, $blue, $reg, $max);

imagettftext($im, 8, 0, $x+3, $height*0.5+5, $red, $reg, $val);


include('labelgraph.php');

if(strlen($pointsremoved)>22){
	$bbox = imagettfbbox(8, 0, $reg, $pointsremoved);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, 8, 0, $width-$w, 12, $black, $reg, $pointsremoved);
}

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/Boot-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/Boot-".$randomString.".png' />";
?>

	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-19339458-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>

<?php
// Free up memory
imagedestroy($im);
$path = 'imagetemp/';
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
