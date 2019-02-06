<?php
//regresion?
$scalefactor=$_POST['scalefactor'];
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

imagesetthickness($im, $scalefactor);

//draw area of graph
imagerectangle ($im, 50*$scalefactor, 50*$scalefactor, $width-50*$scalefactor, $height-50*$scalefactor, $black);
imagerectangle ($im, $width+50*$scalefactor, 50*$scalefactor, $width+$width-50*$scalefactor, $height-50*$scalefactor, $black);

//graph title
$bbox = imagettfbbox(14*$scalefactor, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 14*$scalefactor, 0, $width-$w/2, 30*$scalefactor, $black, $bold, $title);



//x points
$seasons=checkseasons($xpoints);
$xpoints=tsxpoints($xpoints,$seasons);

//
//Axis Labels
//

//yaxis label
$bbox = imagettfbbox(12*$scalefactor, 0, $bold, $ylabel);
$w = $bbox[4]-$bbox[0];
imagettftext($im,12*$scalefactor,90*$scalefactor,15*$scalefactor,$height/2+$w/2,$black,$bold,$ylabel);
$bbox = imagettfbbox(12*$scalefactor, 0, $bold, "Seasonal Effect");
$w = $bbox[4]-$bbox[0];
imagettftext($im,12*$scalefactor,90*$scalefactor,$width+15*$scalefactor,$height/2+$w/2,$black,$bold,"Seasonal Effect");

//xaxis label
$bbox = imagettfbbox(12*$scalefactor, 0, $bold, $xlabel);
$w = $bbox[4]-$bbox[0];
imagettftext($im,12*$scalefactor,0,$width/2-$w/2,$height-15*$scalefactor,$black,$bold,$xlabel);
imagettftext($im,12*$scalefactor,0,$width+$width/2-$w/2,$height-15*$scalefactor,$black,$bold,$xlabel);

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
$bbox = imagettfbbox(10*$scalefactor, 0, $con, $minxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 10*$scalefactor, 0, 60*$scalefactor-$w/2, $height-30*$scalefactor, $black, $con, $minxtick);
imageline ($im, 60*$scalefactor, $height-50*$scalefactor, 60*$scalefactor, $height-45*$scalefactor, $black);
//max x mark
$bbox = imagettfbbox(12*$scalefactor, 0, $con, $maxxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 10*$scalefactor, 0, $width-60*$scalefactor-$w/2, $height-30*$scalefactor, $black, $con, $maxxtick);
imageline ($im, $width-60*$scalefactor, $height-50*$scalefactor, $width-60*$scalefactor, $height-45*$scalefactor, $black);

//other ticks
$i=1;
$distanceright=60*$scalefactor;
$xtick=$minxtick;
while($i<$steps){
	$distanceright=$distanceright+($width-120*$scalefactor)/$steps;
	$xtick=$xtick+$step;
	$bbox = imagettfbbox(10*$scalefactor, 0, $con, $xtick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, 10*$scalefactor, 0, $distanceright-$w/2, $height-30*$scalefactor, $black, $con, $xtick);
	imageline ($im, $distanceright, $height-50*$scalefactor, $distanceright, $height-45*$scalefactor, $black);
	$i++;
}

//set font size for x-axis
$size=0;
$w=0;
while($w<30*$scalefactor && $size<11*$scalefactor){
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
imagettftext($im, $size, 0, $width+60*$scalefactor-$w/2, $height-30*$scalefactor, $black, $con, $minxtick);
imageline ($im, $width+60*$scalefactor, $height-50*$scalefactor, $width+60*$scalefactor, $height-45*$scalefactor, $black);
//max x mark
$bbox = imagettfbbox($size, 0, $con, $maxxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, $width+$width-60*$scalefactor-$w/2, $height-30*$scalefactor, $black, $con, $maxxtick);
imageline ($im, $width+$width-60*$scalefactor, $height-50*$scalefactor, $width+$width-60*$scalefactor, $height-45*$scalefactor, $black);

//other ticks
$i=1;
$distanceright=$width+60*$scalefactor;
$xtick=$minxtick;
while($i<$steps){
	$distanceright=$distanceright+($width-120*$scalefactor)/$steps;
	$xtick=$xtick+$step;
	$bbox = imagettfbbox($size, 0, $con, $xtick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, $distanceright-$w/2, $height-30*$scalefactor, $black, $con, $xtick);
	imageline ($im, $distanceright, $height-50*$scalefactor, $distanceright, $height-45*$scalefactor, $black);
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
while($w<25*$scalefactor && $size<11*$scalefactor){
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
imagettftext($im, $size, 0, 43*$scalefactor-$w, $height-55*$scalefactor, $black, $con, $minytick);
imageline ($im, 45*$scalefactor, $height-60*$scalefactor, 50*$scalefactor, $height-60*$scalefactor, $black);
//max y mark
$bbox = imagettfbbox($size, 0, $con, $maxytick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, 43*$scalefactor-$w, 65*$scalefactor, $black, $con, $maxytick);
imageline ($im, 45*$scalefactor, 60*$scalefactor, 50*$scalefactor, 60*$scalefactor, $black);

//other ticks
$i=1;
$distancedown=$height-60*$scalefactor;
$ytick=$minytick;
while($i<$steps){
	$distancedown=$distancedown-($height-120*$scalefactor)/$steps;
	$ytick=$ytick+$step;
	if(abs($ytick)<=pow(10,-10)){$ytick=0;}
	$bbox = imagettfbbox($size, 0, $con, $ytick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, 43*$scalefactor-$w, $distancedown+5*$scalefactor, $black, $con, $ytick);
	imageline ($im, 45*$scalefactor, $distancedown, 50*$scalefactor, $distancedown, $black);
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
while($w<25*$scalefactor && $size<11*$scalefactor){
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
imagettftext($im, $size, 0, $width+43*$scalefactor-$w, $height-55*$scalefactor, $black, $con, $minrtick);
imageline ($im, $width+45*$scalefactor, $height-60*$scalefactor, $width+50*$scalefactor, $height-60*$scalefactor, $black);
//max s mark
$bbox = imagettfbbox($size, 0, $con, $maxrtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, $width+43*$scalefactor-$w, 65*$scalefactor, $black, $con, $maxrtick);
imageline ($im, $width+45*$scalefactor, 60*$scalefactor, $width+50*$scalefactor, 60*$scalefactor, $black);

//other ticks
$i=1;
$distancedown=$height-60*$scalefactor;
$rtick=$minrtick;
while($i<$steps){
	$distancedown=$distancedown-($height-120*$scalefactor)/$steps;
	$rtick=$rtick+$step;
	if(abs($rtick)<=pow(10,-10)){$rtick=0;}
	$bbox = imagettfbbox($size, 0, $con, $rtick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, $width+43*$scalefactor-$w, $distancedown+5*$scalefactor, $black, $con, $rtick);
	imageline ($im, $width+45*$scalefactor, $distancedown, $width+50*$scalefactor, $distancedown, $black);
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
	$xpixel=60*$scalefactor+($width-120*$scalefactor)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60*$scalefactor-($height-120*$scalefactor)*($yvalue-$minytick)/($maxytick-$minytick);
	$n = ($year-$firstyear)/($lastyear-$firstyear);
	$col = ColorHSLToRGB($n*$end,$sat,$lum);
	$color = imagecolorallocatealpha($im, $col['r'], $col['g'], $col['b'], 0.8);
	if($xvalue!=1 && $lastxpixel!=0){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$color);
	}
	if ($xvalue==1 || $xvalue==$seasons) {
		imagettftext($im, 7*$scalefactor, 0, $xpixel+3*$scalefactor, $ypixel+4*$scalefactor, $color, $reg, $year);
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
	$xpixel=$width+60*$scalefactor+($width-120*$scalefactor)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60*$scalefactor-($height-120*$scalefactor)*($yvalue-$minrtick)/($maxrtick-$minrtick);
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
	$xpixel=$width+60*$scalefactor+($width-120*$scalefactor)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60*$scalefactor-($height-120*$scalefactor)*($yvalue-$minrtick)/($maxrtick-$minrtick);
	if($xvalue!=1 && $i>1){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$black);
	}
	imageellipse($im,$xpixel,$ypixel,5,5,$black);
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;

	$i++;
}


$zero=$height-60*$scalefactor-($height-120*$scalefactor)*(-$minrtick)/($maxrtick-$minrtick);
imageline($im,$width+60*$scalefactor,$zero,$width*2-60*$scalefactor,$zero,$color);


imageline($im,60*$scalefactor,60*$scalefactor,80*$scalefactor,60*$scalefactor,$black);imagettftext($im, 10*$scalefactor, 0, 85*$scalefactor, 65*$scalefactor, $black, $reg, "Raw Data");

$width=$width*2;
include('labelgraph.php');

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/TimeSeriesSeasonal-'.$randomString.'.png');

$extrastyle="";
if($scalefactor==5){
	$extrastyle='width:100%;height:100%;';
}

echo "<img style='position:absolute;top:0px;left:0px;$extrastyle' src='imagetemp/TimeSeriesSeasonal-".$randomString.".png' />";
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
