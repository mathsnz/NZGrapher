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
if(isset($_POST['xvals'])){$xpoints=explode(',', $_POST['xvals']);} else {$xpoints=array("");}
if(isset($_POST['yvals'])){$ypoints=explode(',', $_POST['yvals']);} else {$ypoints=array("");}

//dimension
$width=$_POST['width'];
$height=$_POST['height'];

if(isset($_POST['addmult'])){
	$addmult=$_POST['addmult'];
	if($addmult=="Multiplicative"){
		$multiplicative="yes";
	} else {
		$multiplicative="no";
	}
}

array_pop($xpoints);
array_pop($ypoints);
if(empty($xpoints)){
	echo "<br><br><br><br><br><center>You must select a time series x-variable</center>";
	die();
}


include('time series setup.php');


// Create image and define colours
if($width<1 || $height<1){echo "Invalid Image Dimensions";die();}
$im = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$red = imagecolorallocate($im, 255, 0, 0);
$green = imagecolorallocate($im, 0, 255, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$grey = imagecolorallocatealpha($im, 0, 0, 0, 90);
$lightgrey = imagecolorallocatealpha($im, 0, 0, 0, 110);
$red75 = imagecolorallocatealpha ($im,255, 0, 0, 100);
$reg = './Roboto-Regular.ttf';
$bold = './Roboto-Bold.ttf';

//make image white
imagefill ($im, 0, 0, $white);

//draw area of graph
imagerectangle ($im, 50, 50, $width-50, $height-50, $black);

//graph title
$bbox = imagettfbbox(12, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 14, 0, $width/2-$w/2, 30, $black, $bold, $title);



//x points
$seasons=checkseasons($xpoints);
$xpoints=tsxpoints($xpoints,$seasons);

//
//Axis Labels
//

//yaxis label
$bbox = imagettfbbox(12, 0, $bold, $ylabel);
$w = $bbox[4]-$bbox[0];
imagettftext($im,12,90,15,$height/2+$w/2,$black,$bold,$ylabel);


//xaxis label
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

if($_POST['longtermtrend']=='yes'){
	include 'stl.php';
}


//x axis ticks
$minx=min($xpoints);
$maxx=max($xpoints);
$range=$maxx-$minx;
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
//y axis ticks
$miny=min($ypoints);
$maxy=max($ypoints);
$range=$maxy-$miny;
$rangeround=format_number_significant_figures($range,1);
if($rangeround==0){$rangeround=1;}
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

if($minytick==$maxytick){$minytick--;}

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
imagettftext($im, $size, 0, 43-$w, $height-55, $black, $reg, $minytick);
imageline ($im, 45, $height-60, 50, $height-60, $black);
//max y mark
$bbox = imagettfbbox($size, 0, $reg, $maxytick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, 43-$w, 65, $black, $reg, $maxytick);
imageline ($im, 45, 60, 50, 60, $black);

//other ticks
$i=1;
$distancedown=$height-60;
$ytick=$minytick;
while($i<$steps){
	$distancedown=$distancedown-($height-120)/$steps;
	$ytick=$ytick+$step;
	$bbox = imagettfbbox($size, 0, $reg, $ytick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, 43-$w, $distancedown+5, $black, $reg, $ytick);
	imageline ($im, 45, $distancedown, 50, $distancedown, $black);
	$i++;
}

$i=0;
$lastxpixel=0;
$lastypixel=0;
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$yvalue=$ypoints[$i];
	$xpixel=60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60-($height-120)*($yvalue-$minytick)/($maxytick-$minytick);
	if($i>0){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$black);
	}
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	$i++;
	if($label=="yes") {imagettftext($im, 7, 0, $xpixel+3, $ypixel+4, $blue, $reg, $i);}
}

if($_POST['longtermtrend']=='yes'){
	//plot the trend in blue
	$i=0;
	$aCoords=array();
	while($i<count($xpoints)){
		$xvalue=$xpoints[$i];
		$yvalue=$trend[$i];
		$xpixel=60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
		$ypixel=$height-60-($height-120)*($yvalue-$minytick)/($maxytick-$minytick);
		$aCoords[$xpixel]=-$ypixel;
		$i++;
	}


	include_once ('smootherplot.php');
	include_once ('smoothercubicsplines.php');

	$oCurve = new CubicSplines();

	if ($oCurve) {
	    $oCurve->setInitCoords($aCoords, 1);
	    $r2 = $oCurve->processCoords();
	    if ($r2)
	        $curveGraph = new Plot($r2);
	    else
	        continue;
	} else {
	    $curveGraph = $oPlot;
	}

	imagesetthickness($im,2);
	$curveGraph->drawLine($im, $blue, 0, 0);
	imagesetthickness($im,1);
}

imageline($im,60,60,80,60,$black);imagettftext($im, 10, 0, 85, 65, $black, $reg, "Raw Data");

include('labelgraph.php');

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/TimeSeries-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/TimeSeries-".$randomString.".png' />";
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
