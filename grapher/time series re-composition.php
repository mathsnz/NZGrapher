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
//add/multi
if(isset($_POST['addmult'])){
	$addmult=$_POST['addmult'];
	if($addmult=="Multiplicative"){
		$multiplicative="yes";
	} else {
		$multiplicative="no";
	}
}

//dimension
$width=$_POST['width'];
$height=$_POST['height'];

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
$green = imagecolorallocate($im, 0, 200, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$grey = imagecolorallocatealpha($im, 0, 0, 0, 90);
$lightgrey = imagecolorallocatealpha($im, 0, 0, 0, 110);
$red75 = imagecolorallocatealpha ($im,255, 0, 0, 100);
$orange = imagecolorallocate($im, 255, 100, 0);
$reg = './Roboto-Regular.ttf';
$con = './RobotoCondensed-Regular.ttf';
$bold = './Roboto-Bold.ttf';

//make graph only in top half
$topraw=50;
$bottomraw=($height-100)*0.6+50;
$topseasonal=$bottomraw;
$bottomseasonal=($height-100)*0.2+$topseasonal;
$topresidual=$bottomseasonal;
$bottomresidual=$height-50;

//make image white
imagefill ($im, 0, 0, $white);



//draw area of graph
imagerectangle ($im, 50, $topraw, $width-50, $bottomraw, $black);
imagerectangle ($im, 50, $topseasonal, $width-50, $bottomseasonal, $black);
imagerectangle ($im, 50, $topresidual, $width-50, $bottomresidual, $black);

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

//stl decomposition

include 'stl.php';

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
if($minxtick>$minx){
	$minxtick=$minxtick-$step;
}
$maxxtick=round($maxx/$step)*$step;
if($maxxtick<$maxx){
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
$bbox = imagettfbbox($size, 0, $con, $minxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, 60-$w/2, $height-30, $black, $con, $minxtick);
imageline ($im, 60, $height-50, 60, $height-45, $black);
//max x mark
$bbox = imagettfbbox($size, 0, $con, $maxxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, $width-60-$w/2, $height-30, $black, $con, $maxxtick);
imageline ($im, $width-60, $height-50, $width-60, $height-45, $black);

//other ticks
$i=1;
$distanceright=60;
$xtick=$minxtick;
while($i<$steps){
	$distanceright=$distanceright+($width-120)/$steps;
	$xtick=$xtick+$step;
	$bbox = imagettfbbox($size, 0, $con, $xtick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, $distanceright-$w/2, $height-30, $black, $con, $xtick); 
	imageline ($im, $distanceright, $height-50, $distanceright, $height-45, $black);
	$i++;
}
//y axis ticks
$miny=min($ypoints);
$maxy=max($ypoints);
$minfitted=min($fitted);
$maxfitted=max($fitted);
$miny=min($miny,$minfitted);
$maxy=max($maxy,$maxfitted);
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
$bbox = imagettfbbox($size, 0, $con, $minytick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, 43-$w, $bottomraw-5, $black, $con, $minytick);
imageline ($im, 45, $bottomraw-10, 50, $bottomraw-10, $black);
//max y mark
$bbox = imagettfbbox($size, 0, $con, $maxytick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, 43-$w, $topraw+15, $black, $con, $maxytick);
imageline ($im, 45, $topraw+10, 50, $topraw+10, $black);

//other ticks
$i=1;
$distancedown=$bottomraw-10;
$ytick=$minytick;
while($i<$steps){
	$distancedown=$distancedown-($bottomraw-$topraw-20)/$steps;
	$ytick=$ytick+$step;
	$bbox = imagettfbbox($size, 0, $con, $ytick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, 43-$w, $distancedown+5, $black, $con, $ytick);
	imageline ($im, 45, $distancedown, 50, $distancedown, $black);
	$i++;
}

// seasonal s axis ticks
$mins=min($s);
$maxs=max($s);
if($mins==$maxs){$maxs++;$mins--;}
$ranges=$maxs-$mins;
$rangerounds=format_number_significant_figures($ranges,1);
$steps=FirstSF($rangerounds);
if($steps<2) {
	$steps=$steps*2;
}
$step=$rangerounds/$steps;
$minstick=round($mins/$step)*$step;
if($minstick>=$mins){
	$minstick=$minstick-$step;
}
$maxstick=round($maxs/$step)*$step;
if($maxstick<=$maxs){
	$maxstick=$maxstick+$step;
}
$steps=($maxstick-$minstick)/$step;

//min r mark
$bbox = imagettfbbox($size, 0, $con, $minstick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, $width-43, $bottomseasonal-5, $black, $con, $minstick);
imageline ($im, $width-50, $bottomseasonal-10, $width-45, $bottomseasonal-10, $black);
//max y mark
$bbox = imagettfbbox($size, 0, $con, $maxstick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, $width-43, $topseasonal+15, $black, $con, $maxstick);
imageline ($im, $width-50, $topseasonal+10, $width-45, $topseasonal+10, $black);

//other ticks
$i=1;
$distancedown=$bottomseasonal-10;
$stick=$minstick;
while($i<$steps){
	$distancedown=$distancedown-($bottomseasonal-$topseasonal-20)/$steps;
	$stick=$stick+$step;
	if(abs($stick)<pow(10,-10)){$stick=0;}
	$bbox = imagettfbbox($size, 0, $con, $stick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, $width-43, $distancedown+5, $black, $con, $stick);
	imageline ($im, $width-50, $distancedown, $width-45, $distancedown, $black);
	$i++;
}

// residuals r axis ticks
$minr=min($r);
$maxr=max($r);
if($minr==$maxr){$maxr++;$minr--;}
$ranger=$maxr-$minr;
$rangeroundr=format_number_significant_figures($ranger,1);
$steps=FirstSF($rangeroundr);
if($steps<2) {
	$steps=$steps*2;
}
$step=$rangeroundr/$steps;
$minrtick=round($minr/$step)*$step;
if($minrtick>=$minr){
	$minrtick=$minrtick-$step;
}
$maxrtick=round($maxr/$step)*$step;
if($maxrtick<=$maxr){
	$maxrtick=$maxrtick+$step;
}
$steps=($maxrtick-$minrtick)/$step;

//min r mark
$bbox = imagettfbbox($size, 0, $con, $minrtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, 43-$w, $bottomresidual-5, $black, $con, $minrtick);
imageline ($im, 45, $bottomresidual-10, 50, $bottomresidual-10, $black);
//max y mark
$bbox = imagettfbbox($size, 0, $con, $maxrtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, 43-$w, $topresidual+15, $black, $con, $maxrtick);
imageline ($im, 45, $topresidual+10, 50, $topresidual+10, $black);

//other ticks
$i=1;
$distancedown=$bottomresidual-10;
$rtick=$minrtick;
while($i<$steps){
	$distancedown=$distancedown-($bottomresidual-$topresidual-20)/$steps;
	$rtick=$rtick+$step;
	if(abs($rtick)<pow(10,-10)){$rtick=0;}
	$bbox = imagettfbbox($size, 0, $con, $rtick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, 43-$w, $distancedown+5, $black, $con, $rtick);
	imageline ($im, 45, $distancedown, 50, $distancedown, $black);
	$i++;
}

//plot the fitted data in green
$i=0;
$lastxpixel=0;
$lastypixel=0;
imagesetthickness($im,2);
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$yvalue=$fitted[$i];
	$xpixel=60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$bottomraw-10-($bottomraw-$topraw-20)*($yvalue-$minytick)/($maxytick-$minytick);
	if($i>0){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$green);
	}
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	$i++;
}
imagesetthickness($im,1);
//plot the raw data in black
$i=0;
$lastxpixel=0;
$lastypixel=0;
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$yvalue=$ypoints[$i];
	$xpixel=60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$bottomraw-10-($bottomraw-$topraw-20)*($yvalue-$minytick)/($maxytick-$minytick);
	if($i>0){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$black);
	}
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	$i++;
	if($label=="yes") {imagettftext($im, 7, 0, $xpixel+3, $ypixel+4, $blue, $reg, $i);}
}

//plot the trend in blue	
$i=0;
$aCoords=array();
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$yvalue=$trend[$i];
	$xpixel=60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$bottomraw-10-($bottomraw-$topraw-20)*($yvalue-$minytick)/($maxytick-$minytick);
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

//plot the seasonal
$zero=$bottomseasonal-10-($bottomseasonal-$topseasonal-20)*(-$minstick)/($maxstick-$minstick);
imageline($im,60,$zero,$width-60,$zero,$grey);

$i=0;
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$yvalue=$s[$i];
	$xpixel=60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$bottomseasonal-10-($bottomseasonal-$topseasonal-20)*($yvalue-$minstick)/($maxstick-$minstick);
	if($i>0){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$orange);
	}
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	$i++;
	if($label=="yes") {imagettftext($im, 7, 0, $xpixel+3, $ypixel+4, $blue, $reg, $i);}
}

//plot the residuals
$zero=$bottomresidual-10-($bottomresidual-$topresidual-20)*(-$minrtick)/($maxrtick-$minrtick);
imageline($im,60,$zero,$width-60,$zero,$grey);

$tenpc=($maxy-$miny)/10;
if($tenpc<$maxrtick){
	$tenpc=$bottomresidual-10-($bottomresidual-$topresidual-20)*($tenpc-$minrtick)/($maxrtick-$minrtick);
	imageline($im,60,$tenpc,$width-60,$tenpc,$lightgrey);
}

$tenpc=-($maxy-$miny)/10;
if($tenpc>$minrtick){
	$tenpc=$bottomresidual-10-($bottomresidual-$topresidual-20)*($tenpc-$minrtick)/($maxrtick-$minrtick);
	imageline($im,60,$tenpc,$width-60,$tenpc,$lightgrey);
}

$i=0;
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$yvalue=$r[$i];
	$xpixel=60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$bottomresidual-10-($bottomresidual-$topresidual-20)*($yvalue-$minrtick)/($maxrtick-$minrtick);
	if($i>0){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$red);
	}
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	$i++;
	if($label=="yes") {imagettftext($im, 7, 0, $xpixel+3, $ypixel+4, $blue, $reg, $i);}
}


imageline($im,60,60,80,60,$black);imagettftext($im, 10, 0, 85, 65, $black, $reg, "Raw Data");
imageline($im,60,75,80,75,$blue);imagettftext($im, 10, 0, 85, 80, $black, $reg, "Trend");
imagesetthickness($im,2);
imageline($im,60,90,80,90,$green);imagettftext($im, 10, 0, 85, 95, $black, $reg, "Trend + Seasonal");
imagesetthickness($im,1);
imageline($im,60,$topseasonal+10,80,$topseasonal+10,$orange);imagettftext($im, 10, 0, 85, $topseasonal+15, $black, $reg, "Seasonal");
imageline($im,60,$topresidual+10,80,$topresidual+10,$red);imagettftext($im, 10, 0, 85, $topresidual+15, $black, $reg, "Residual");

include('labelgraph.php');

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/TimeSeriesRecomp-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/TimeSeriesRecomp-".$randomString.".png' />";
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