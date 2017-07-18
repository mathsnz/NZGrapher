<?php


// Need a tick box to move the box and whisker graph up to the top of the plot so it doesn't overlap the dot plot.


//regresion?
$regression=$_POST['regression'];
//box plots?
$boxplot=$_POST['boxplot'];
//informal confidence interval?
$interval=$_POST['interval'];
//graph title
$title=stripslashes($_POST['title']);
//yaxis lavel
$ylabel=stripslashes($_POST['yaxis']);
//xaxis label
$xlabel=stripslashes($_POST['xaxis']);
//labels?
$label=$_POST['labels'];
//color
$colorlabel=stripslashes($_POST['colorlabel']);
//relative frequency
$rf=$_POST['relativefrequency'];
//points
if(isset($_POST['xvals'])){$xpoints=explode(',', $_POST['xvals']);} else {$xpoints=array("");}
if(isset($_POST['yvals'])){$ypoints=explode(',', $_POST['yvals']);} else {$ypoints=array("");}

//dimension
$width=$_POST['width'];
$height=$_POST['height'];

array_pop($xpoints);
array_pop($ypoints);
if(empty($xpoints)){
	echo "<br><br><br><br><br><center>You must select an x-variable for a bar graph<br>
	or<br>
	an x-variable and a y-variable for an area graph.</center>";
	die();
}
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

// Create image and define colours
if($width<1 || $height<1){echo "Invalid Image Dimensions";die();}
$im = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$grey = imagecolorallocate($im, 125, 125, 125);
$lightgrey = imagecolorallocate($im, 230, 230, 230);
$red = imagecolorallocate($im, 255, 0, 0);
$green = imagecolorallocate($im, 0, 255, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$reg = './Roboto-Regular.ttf';
$bold = './Roboto-Bold.ttf';
$con = './RobotoCondensed-Regular.ttf';

//make image white
imagefill ($im, 0, 0, $white);

//graph title
$bbox = imagettfbbox(12, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 14, 0, $width/2-$w/2, 30, $black, $bold, $title);

//
//Axis Labels
//

//xaxis label
$bbox = imagettfbbox(12, 0, $bold, $xlabel);
$w = $bbox[4]-$bbox[0];
imagettftext($im,12,0,$width/2-$w/2,$height-5,$black,$bold,$xlabel);

if(empty($ypoints)){
	imageline ($im, 50, $height-50, $width-50, $height-50, $black);
	imageline ($im, 50, 50, 50, $height-50, $black);
	$freq=array_count_values($xpoints);
	ksort($freq);
	$num=count($freq);

	//y axis ticks
	$miny=0.0001;
	$maxy=max($freq);
	if($rf==1){
		$sum = array_sum($freq);
		$maxy = $maxy / $sum;
	} else {
		$sum = 1;
	}
	$range=$maxy-$miny;
	$rangeround=format_number_significant_figures($range,1);
	$steps=FirstSF($rangeround);
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
	$minytick=round($miny/$step)*$step;
	if($minytick>=$miny){
		$minytick=$minytick-$step;
	}
	$maxytick=round($maxy/$step)*$step;
	if($maxytick<=$maxy){
		$maxytick=$maxytick+$step;
	}
	$steps=($maxytick-$minytick)/$step;

	//set font size for y-axis
	$size=0;
	$w=0;
	while($w<25 && $size<11){
		$bbox = imagettfbbox($size, 0, $reg, $minytick);
		$w1 = $bbox[4]-$bbox[0];
		$bbox = imagettfbbox($size, 0, $reg, $maxytick);
		$w2 = $bbox[4]-$bbox[0];
		$w=max($w1,$w2);
		$size++;
	}
	$size--;

	//min y mark
	$bbox = imagettfbbox($size, 0, $reg, $minytick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, 43-$w, $height-45, $black, $reg, $minytick);
	imageline ($im, 45, $height-50, 50, $height-50, $black);
	//max y mark
	$bbox = imagettfbbox($size, 0, $reg, $maxytick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, 43-$w, 65, $black, $reg, $maxytick);
	imageline ($im, 45, 60, 50, 60, $black);

	//other ticks
	$i=1;
	$distancedown=$height-50;
	$ytick=$minytick;
	while($i<$steps){
		$distancedown=$distancedown-($height-110)/$steps;
		$ytick=$ytick+$step;
		$bbox = imagettfbbox($size, 0, $reg, $ytick);
		$w = $bbox[4]-$bbox[0];
		imagettftext($im, $size, 0, 43-$w, $distancedown+5, $black, $reg, $ytick);
		imageline ($im, 45, $distancedown, 50, $distancedown, $black);
		$i++;
	}

	//yaxis label
	$bbox = imagettfbbox(12, 0, $bold, "Frequency");
	$w = $bbox[4]-$bbox[0];
	imagettftext($im,12,90,15,$height/2+$w/2,$black,$bold,"Frequency");

	$shift=($width-120)/$num;
	$left=60+$shift/2;

	foreach($freq as $key => $value){
		$value = $value / $sum;
		$bbox = imagettfbbox($size, 0, $reg, $key);
		$w = $bbox[4]-$bbox[0];
		imagettftext($im, $size, 0, $left-$w/2, $height-35, $black, $reg, $key);
		$top=$height-($height-110)*($value/$maxytick)-50;
		imagefilledrectangle($im,$left-$shift*0.4,$top,$left+$shift*0.4,$height-50,$lightgrey);
		imagerectangle($im,$left-$shift*0.4,$top,$left+$shift*0.4,$height-50,$black);
		$left=$left+$shift;
	}


} else {

	//yaxis label
	$bbox = imagettfbbox(12, 0, $bold, $ylabel);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im,12,90,15,$height/2+$w/2,$black,$bold,$ylabel);
	$catsy=array_unique($ypoints);
	$catsx=array_unique($xpoints);
	$numx=count($catsx);
	$vals=array_count_values($xpoints);
	$countx=count($xpoints);
	$full=array();
	foreach($catsx as $cat){
		$full[$cat]=array();
	}
	$i=0;
	foreach($xpoints as $xpoint){
		$ypoint=$ypoints[$i];
		array_push($full[$xpoint],$ypoint);
		$i++;
	}
	$xb=50;
	sort($catsx);
	foreach($catsx as $catx){
		$percentx=$vals[$catx]/$countx;
		$xa=$xb;
		$xb=$xa+($width-100)*$percentx;

		$bbox = imagettfbbox(10, 0, $reg, $catx);
		$w = $bbox[4]-$bbox[0];
		imagettftext($im,10,0,($xa+$xb)/2-$w/2,$height-35,$black,$reg,$catx);

		$numy=count($full[$catx]);
		$full[$catx]=array_count_values($full[$catx]);
		sort($catsy);
		$yb=50;
		foreach($catsy as $caty){
			$ya=$yb;
			if(array_key_exists($caty,$full[$catx])){
				$yb=$yb+($height-100)*$full[$catx][$caty]/$numy;
				imagefilledrectangle($im,$xa+1,$ya+1,$xb-1,$yb-1,$lightgrey);
				imagerectangle($im,$xa+1,$ya+1,$xb-1,$yb-1,$black);
				$bbox = imagettfbbox(10, 0, $con, $caty);
				$w = $bbox[4]-$bbox[0];
				if($w>($xb-$xa)){$w=$xb-$xa;}
				imagettftext($im,10,0,($xa+$xb)/2-$w/2,($ya+$yb)/2+5,$black,$con,$caty);
			}
		}
	}
}

include('labelgraph.php');

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/Dotplot-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/Dotplot-".$randomString.".png' />";

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
