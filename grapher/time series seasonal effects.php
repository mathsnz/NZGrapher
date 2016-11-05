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
$green = imagecolorallocate($im, 0, 255, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$grey = imagecolorallocate($im, 125, 125, 125);
$red75 = imagecolorallocatealpha ($im,255, 0, 0, 100);
$reg = './Roboto-Regular.ttf';
$con = './RobotoCondensed-Regular.ttf';
$bold = './Roboto-Bold.ttf';

$width=$width*0.5;

//make image white
imagefill ($im, 0, 0, $white);

//draw area of graph
imagerectangle ($im, 50, 50, $width-50, $height-50, $black);
imagerectangle ($im, $width+50, 50, $width+$width-50, $height-50, $black);

//graph title
$bbox = imagettfbbox(12, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 14, 0, $width-$w/2, 30, $black, $bold, $title);



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
$bbox = imagettfbbox(12, 0, $bold, "Seasonal Effect");
$w = $bbox[4]-$bbox[0];
imagettftext($im,12,90,$width+15,$height/2+$w/2,$black,$bold,"Seasonal Effect");

//xaxis label
$bbox = imagettfbbox(12, 0, $bold, $xlabel);
$w = $bbox[4]-$bbox[0];
imagettftext($im,12,0,$width/2-$w/2,$height-15,$black,$bold,$xlabel);
imagettftext($im,12,0,$width+$width/2-$w/2,$height-15,$black,$bold,$xlabel);

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

//holt winters

//stl decomposition

include 'stl.php';

//x axis ticks
$minx=1;
$maxx=$seasons;
$range=$maxx-$minx;
$rangeround=format_number_significant_figures($range,1);
$steps=$seasons-1;
$step=1;
$minxtick=round($minx/$step)*$step;
$maxxtick=round($maxx/$step)*$step;

//min x mark
$bbox = imagettfbbox(12, 0, $con, $minxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 10, 0, 60-$w/2, $height-30, $black, $con, $minxtick);
imageline ($im, 60, $height-50, 60, $height-45, $black);
//max x mark
$bbox = imagettfbbox(12, 0, $con, $maxxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 10, 0, $width-60-$w/2, $height-30, $black, $con, $maxxtick);
imageline ($im, $width-60, $height-50, $width-60, $height-45, $black);

//other ticks
$i=1;
$distanceright=60;
$xtick=$minxtick;
while($i<$steps){
	$distanceright=$distanceright+($width-120)/$steps;
	$xtick=$xtick+$step;
	$bbox = imagettfbbox(12, 0, $con, $xtick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, 10, 0, $distanceright-$w/2, $height-30, $black, $con, $xtick); 
	imageline ($im, $distanceright, $height-50, $distanceright, $height-45, $black);
	$i++;
}

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
imagettftext($im, $size, 0, $width+60-$w/2, $height-30, $black, $con, $minxtick);
imageline ($im, $width+60, $height-50, $width+60, $height-45, $black);
//max x mark
$bbox = imagettfbbox($size, 0, $con, $maxxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, $width+$width-60-$w/2, $height-30, $black, $con, $maxxtick);
imageline ($im, $width+$width-60, $height-50, $width+$width-60, $height-45, $black);

//other ticks
$i=1;
$distanceright=$width+60;
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
	$bbox = imagettfbbox($size, 0, $con, $minytick);
	$w1 = $bbox[4]-$bbox[0];
	$bbox = imagettfbbox($size, 0, $con, $maxytick);
	$w2 = $bbox[4]-$bbox[0];
	$w=max($w1,$w2);
	$size++;
}
$size--;

//min y mark
$bbox = imagettfbbox($size, 0, $con, $minytick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, 43-$w, $height-55, $black, $con, $minytick);
imageline ($im, 45, $height-60, 50, $height-60, $black);
//max y mark
$bbox = imagettfbbox($size, 0, $con, $maxytick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, 43-$w, 65, $black, $con, $maxytick);
imageline ($im, 45, 60, 50, 60, $black);

//other ticks
$i=1;
$distancedown=$height-60;
$ytick=$minytick;
while($i<$steps){
	$distancedown=$distancedown-($height-120)/$steps;
	$ytick=$ytick+$step;
	if(abs($ytick)<=pow(10,-10)){$ytick=0;}
	$bbox = imagettfbbox($size, 0, $con, $ytick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, 43-$w, $distancedown+5, $black, $con, $ytick);
	imageline ($im, 45, $distancedown, 50, $distancedown, $black);
	$i++;
}


//s axis ticks
$minr=min($s);
$maxr=max($s);
$range=$maxr-$minr;
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
$minrtick=round($minr/$step)*$step;
if($minrtick>=$minr){
	$minrtick=$minrtick-$step;
}
$maxrtick=round($maxr/$step)*$step;
if($maxrtick<=$maxr){
	$maxrtick=$maxrtick+$step;
}
if ($minrtick==$maxrtick){
	$minrtick--;
	$maxrtick++;
}
$steps=($maxrtick-$minrtick)/$step;

//set font size for r-axis
$size=0;
$w=0;
while($w<25 && $size<11){
	$bbox = imagettfbbox($size, 0, $reg, $minrtick);
	$w1 = $bbox[4]-$bbox[0];
	$bbox = imagettfbbox($size, 0, $reg, $maxrtick);
	$w2 = $bbox[4]-$bbox[0];
	$w=max($w1,$w2);
	$size++;
}
$size--;


//min s mark
$bbox = imagettfbbox($size, 0, $con, $minrtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, $width+43-$w, $height-55, $black, $con, $minrtick);
imageline ($im, $width+45, $height-60, $width+50, $height-60, $black);
//max s mark
$bbox = imagettfbbox($size, 0, $con, $maxrtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, $width+43-$w, 65, $black, $con, $maxrtick);
imageline ($im, $width+45, 60, $width+50, 60, $black);

//other ticks
$i=1;
$distancedown=$height-60;
$rtick=$minrtick;
while($i<$steps){
	$distancedown=$distancedown-($height-120)/$steps;
	$rtick=$rtick+$step;
	if(abs($rtick)<=pow(10,-10)){$rtick=0;}
	$bbox = imagettfbbox($size, 0, $con, $rtick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, $width+43-$w, $distancedown+5, $black, $con, $rtick);
	imageline ($im, $width+45, $distancedown, $width+50, $distancedown, $black);
	$i++;
}


$i=1;
$lastxpixel=0;
$lastypixel=0;
$firstyear=substr(min($xpoints),0,4);
$lastyear=substr(max($xpoints),0,4);
$end=0.8;
$sat=0.75;
$lum=0.6;
while($i<count($xpoints)){
	$year=substr($xpoints[$i],0,4);
	$xvalue=round(($xpoints[$i]-$year+1/$seasons)*$seasons);
	$yvalue=$ypoints[$i];
	$xpixel=60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60-($height-120)*($yvalue-$minytick)/($maxytick-$minytick);
	$n = ($year-$firstyear)/($lastyear-$firstyear);
	$col = ColorHSLToRGB($n*$end,$sat,$lum);
	$color = imagecolorallocatealpha($im, $col['r'], $col['g'], $col['b'], 0.8);
	if($xvalue!=1 && $i>1){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$color);
	}
	if ($xvalue==1 || $xvalue==$seasons) {
		imagettftext($im, 7, 0, $xpixel+3, $ypixel+4, $color, $reg, $year);
	}
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	$i++;
}

$i=1;
$r=array();
while($i<=$seasons){
	$r[$i]=array();
	$i++;
}

$i=1;
$lastxpixel=0;
$lastypixel=0;
$color = imagecolorallocatealpha ($im,0, 0, 0,115);
while($i<count($xpoints)){
	$year=substr($xpoints[$i],0,4);
	$xvalue=round(($xpoints[$i]-$year+1/$seasons)*$seasons);
	$yvalue=$s[$i];
	array_push($r[$xvalue],$yvalue);
	$xpixel=$width+60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60-($height-120)*($yvalue-$minrtick)/($maxrtick-$minrtick);
	if($xvalue!=1 && $i>1){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$color);
	}
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	$i++;
}

$i=1;

while($i<=$seasons){
	$xvalue=$i;
	$yvalue=array_sum($r[$i])/count($r[$i]);
	$xpixel=$width+60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60-($height-120)*($yvalue-$minrtick)/($maxrtick-$minrtick);
	if($xvalue!=1 && $i>1){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$black);
	}
	imageellipse($im,$xpixel,$ypixel,5,5,$black);
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	
	$i++;
}


$zero=$height-60-($height-120)*(-$minrtick)/($maxrtick-$minrtick);
imageline($im,$width+60,$zero,$width*2-60,$zero,$color);


imageline($im,60,60,80,60,$color);imagettftext($im, 10, 0, 85, 65, $black, $reg, "Raw Data");

$width=$width*2;
include('labelgraph.php');

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/TimeSeriesSeasonal-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/TimeSeriesSeasonal-".$randomString.".png' />";
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

function ColorHSLToRGB($h, $s, $l){

        $r = $l;
        $g = $l;
        $b = $l;
        $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
        if ($v > 0){
              $m;
              $sv;
              $sextant;
              $fract;
              $vsf;
              $mid1;
              $mid2;

              $m = $l + $l - $v;
              $sv = ($v - $m ) / $v;
              $h *= 6.0;
              $sextant = floor($h);
              $fract = $h - $sextant;
              $vsf = $v * $sv * $fract;
              $mid1 = $m + $vsf;
              $mid2 = $v - $vsf;

              switch ($sextant)
              {
                    case 0:
                          $r = $v;
                          $g = $mid1;
                          $b = $m;
                          break;
                    case 1:
                          $r = $mid2;
                          $g = $v;
                          $b = $m;
                          break;
                    case 2:
                          $r = $m;
                          $g = $v;
                          $b = $mid1;
                          break;
                    case 3:
                          $r = $m;
                          $g = $mid2;
                          $b = $v;
                          break;
                    case 4:
                          $r = $mid1;
                          $g = $m;
                          $b = $v;
                          break;
                    case 5:
                          $r = $v;
                          $g = $m;
                          $b = $mid2;
                          break;
              }
        }
        return array('r' => $r * 255.0, 'g' => $g * 255.0, 'b' => $b * 255.0);
}
?>