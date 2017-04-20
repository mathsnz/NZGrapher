<?php
$scalefactor=$_POST['scalefactor'];
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
$aqua = imagecolorallocate($im, 0, 200, 200);
$blue = imagecolorallocate($im, 0, 0, 255);
$grey = imagecolorallocate($im, 125, 125, 125);
$red75 = imagecolorallocatealpha ($im,255, 200, 200, 0);
$reg = './Roboto-Regular.ttf';
$con = './RobotoCondensed-Regular.ttf';
$bold = './Roboto-Bold.ttf';

//make image white
imagefill ($im, 0, 0, $white);

imagesetthickness($im, $scalefactor);

//draw area of graph
imagerectangle ($im, 50*$scalefactor, 50*$scalefactor, $width-50*$scalefactor, $height-50*$scalefactor, $black);

//graph title
$bbox = imagettfbbox(14*$scalefactor, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 14*$scalefactor, 0, $width/2-$w/2, 30*$scalefactor, $black, $bold, $title);

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

//xaxis label
$bbox = imagettfbbox(12*$scalefactor, 0, $bold, $xlabel);
$w = $bbox[4]-$bbox[0];
imagettftext($im,12*$scalefactor,0,$width/2-$w/2,$height-5*$scalefactor,$black,$bold,$xlabel);

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

include_once ('holtwinters.php');

list($a,$b,$s)=hwinit($ypoints,$seasons);

	$i=0;
	$error=0;
	foreach($ypoints as $ypoint){
		$a[$i]=$alpha*($ypoint-$s[$i-$seasons])+(1-$alpha)*($a[$i-1]+$b[$i-1]);
		$b[$i]=$beta*($a[$i]-$a[$i-1])+(1-$beta)*$b[$i-1];
		$s[$i]=$gamma*($ypoint-$a[$i])+(1-$gamma)*$s[$i-$seasons];
		$error+=pow($ypoint-($a[$i-1]+$b[$i-1]+$s[$i-$seasons]),2);
		$i++;
	}

	$error=sqrt($error/$i);

function purebell($std_deviation) {
	$rand1 = (float)mt_rand()/(float)mt_getrandmax();
	$rand2 = (float)mt_rand()/(float)mt_getrandmax();
	$gaussian_number = sqrt(-2 * log($rand1)) * cos(2 * M_PI * $rand2);
	$random_number = ($gaussian_number * $std_deviation);
	return $random_number;
}


	$trend=array();
	$forecasts=array();
	$forecastsmin=array();
	$forecastsmax=array();
	$trend[-1]=$a[$i-1];
	$trendmin[-1]=$a[$i-1];
	$trendmax[-1]=$a[$i-1];
	$c=0;
	$x=max($xpoints);
	while($c<$seasons*2){
		$season=$i+$c-$seasons;
		$x=$x+1/$seasons;
		while($season>$i-1){$season=$season-$seasons;}
		$t=$trend[$c-1]+$b[$i-1];
		$trend[$c]=$t;
		$forecasts[$c]=array($x,$t+$s[$season]);
		$c++;
	}


	$bsforecasts=array();
	$c=0;
	while($c<$seasons*2){
		$bsforecasts[$c]=array();
		$c++;
	}

	$z=0;
	while($z<1000){
		$bstrend=array();
		$bstrend[-1]=$a[$i-1];
		$c=0;
		while($c<$seasons*2){
			$season=$i+$c-$seasons;
			while($season>$i-1){$season=$season-$seasons;}
			$t=$bstrend[$c-1]+$b[$i-1]+purebell($error);
			$bstrend[$c]=$t;
			array_push($bsforecasts[$c],$t+$s[$season]);
			$c++;
		}
		$z++;
	}


	$x=max($xpoints);
	$c=0;
	while($c<$seasons*2){
		$x=$x+1/$seasons;
		sort($bsforecasts[$c]);
		$forecastsmin[$c]=$bsforecasts[$c][25];
		$forecastsmax[$c]=$bsforecasts[$c][975];
		$c++;
	}

if ($multiplicative=="yes"){
	foreach($forecasts as $c => $forcast){
		$forecasts[$c][1]=exp($forecasts[$c][1]);
		$forecastsmin[$c]=exp($forecastsmin[$c]);
		$forecastsmax[$c]=exp($forecastsmax[$c]);
	}
	foreach($ypoints as $index => $ypoint){
		$ypoints[$index]=exp($ypoint);
	}
	$i=0;
	while($i<count($xpoints)){
		$fitted[$i]=exp($a[$i-1]+$s[$i-$seasons]+$b[$i-1]);
		$i++;
	}
} else {
	$i=0;
	while($i<count($xpoints)){
		$fitted[$i]=$a[$i-1]+$s[$i-$seasons]+$b[$i-1];
		$i++;
	}
}

if($_POST['regression']=="yes"){
	$c=0;
	$x=max($xpoints);
	echo "
		<html>
		<head>
			<style>
				body {
					font-family: \"Trebuchet MS\", Helvetica, sans-serif;
				}
				table {
					border-collapse:collapse;
				}
				td, th {
					border:1px solid #000;
					padding-left:4px;
					padding-right:4px;
					width:80px;
				}
				*.minmax {
					color:#bbb;
				}
				#content {
					position:absolute;
					top:0px;
					left:0px;
					width:". number_format($_POST['width']-20,0,'','') ."px;
					height:". number_format($_POST['height']-20,0,'','') ."px;
					overflow-y:scroll;
					padding:10px;
				}
			</style>
		</head>
		<body>
		<div id=content>
		<table><tr><th>Time<th>Min<th>Prediction<th>Max";
	while($c<$seasons*2){
		$x=$x+1/$seasons;
		$min=format_number_significant_figures($forecastsmin[$c],5);
		$pred=format_number_significant_figures($forecasts[$c][1],5);
		$max=format_number_significant_figures($forecastsmax[$c],5);
		$year=substr($x,0,4);
		$month=round(substr($x,4)*$seasons)+1;
		if($seasons==4){
			$month="Q$month";
		} else if ($seasons==12){
			$month=sprintf('%02d', $month);
			$month="M$month";
		} else if ($seasons==24){
			if($month==25){
				$month=1;
				$year++;
			}
			$month=sprintf('%02d', $month);
			$month="H$month";
		} else if ($seasons==7){
			$month="D$month";
		} else if ($seasons==5){
			$month="W$month";
		}
		echo "<tr><td align=center>$year$month<td align=center class=minmax>$min<td align=center>$pred<td align=center class=minmax>$max";
		$c++;
	}
	echo "</table></div></body></html>";
	die();
}


//x axis ticks
$minx=min($xpoints);
$maxx=max($xpoints)+2;
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
imagettftext($im, $size, 0, 60*$scalefactor-$w/2, $height-30*$scalefactor, $black, $con, $minxtick);
imageline ($im, 60*$scalefactor, $height-50*$scalefactor, 60*$scalefactor, $height-45*$scalefactor, $black);
//max x mark
$bbox = imagettfbbox($size, 0, $con, $maxxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($im, $size, 0, $width-60*$scalefactor-$w/2, $height-30*$scalefactor, $black, $con, $maxxtick);
imageline ($im, $width-60*$scalefactor, $height-50*$scalefactor, $width-60*$scalefactor, $height-45*$scalefactor, $black);

//other ticks
$i=1;
$distanceright=60*$scalefactor;
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
$miny=min(min($ypoints),min($forecastsmin));
$maxy=max(max($ypoints),max($forecastsmax));
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
while($w<25*$scalefactor && $size<11*$scalefactor){
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
	$bbox = imagettfbbox($size, 0, $con, $ytick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, 43*$scalefactor-$w, $distancedown+5*$scalefactor, $black, $con, $ytick);
	imageline ($im, 45*$scalefactor, $distancedown, 50*$scalefactor, $distancedown, $black);
	$i++;
}



$i=0;
$lastxpixel=0;
$lastypixel=0;
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$yvalue=$ypoints[$i];
	$xpixel=60*$scalefactor+($width-120*$scalefactor)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60*$scalefactor-($height-120*$scalefactor)*($yvalue-$minytick)/($maxytick-$minytick);
	if($i>0){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$black);
	}
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	$i++;
	if($label=="yes") {imagettftext($im, 7*$scalefactor, 0, $xpixel+3*$scalefactor, $ypixel+4*$scalefactor, $blue, $reg, $i);}
}

$i=0;
$lastxpixel=0;
$lastypixel=0;

imagesetthickness($im,2*$scalefactor);
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$yvalue=$fitted[$i];
	$xpixel=60*$scalefactor+($width-120*$scalefactor)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60*$scalefactor-($height-120*$scalefactor)*($yvalue-$minytick)/($maxytick-$minytick);
	if($i>0){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$aqua);
	}
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	$i++;
}
imagesetthickness($im,1*$scalefactor);

$i=0;
$lastxpixel=0;
$lastypixel=0;
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$yvalue=$ypoints[$i];
	$xpixel=60*$scalefactor+($width-120*$scalefactor)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60*$scalefactor-($height-120*$scalefactor)*($yvalue-$minytick)/($maxytick-$minytick);
	if($i>0){
		imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$black);
	}
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	$i++;
	if($label=="yes") {imagettftext($im, 7*$scalefactor, 0, $xpixel+3*$scalefactor, $ypixel+4*$scalefactor, $blue, $reg, $i);}
}

$i=0;
$dlastxpixel=$lastxpixel;
$dlastypixel=$lastypixel;

$errorband=array($dlastxpixel,$dlastypixel);
$i=0;
while($i<count($forecastsmin)){
	$xvalue=$forecasts[$i][0];
	$yvalue=$forecastsmin[$i];
	$xpixel=60*$scalefactor+($width-120*$scalefactor)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60*$scalefactor-($height-120*$scalefactor)*($yvalue-$minytick)/($maxytick-$minytick);
	array_push($errorband,$xpixel,$ypixel);
	$i++;
}

$lastxpixel=$dlastxpixel;
$lastypixel=$dlastypixel;
while($i>0){
	$i--;
	$xvalue=$forecasts[$i][0];
	$yvalue=$forecastsmax[$i];
	$xpixel=60*$scalefactor+($width-120*$scalefactor)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60*$scalefactor-($height-120*$scalefactor)*($yvalue-$minytick)/($maxytick-$minytick);
	array_push($errorband,$xpixel,$ypixel);
}

imagefilledpolygon($im,$errorband,count($errorband)/2,$red75);

while($i<count($forecasts)){
	$xvalue=$forecasts[$i][0];
	$yvalue=$forecasts[$i][1];
	$xpixel=60*$scalefactor+($width-120*$scalefactor)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60*$scalefactor-($height-120*$scalefactor)*($yvalue-$minytick)/($maxytick-$minytick);
	imagesetthickness($im,2*$scalefactor);
	imageline($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$red);
	imagesetthickness($im,1*$scalefactor);
	$lastxpixel=$xpixel;
	$lastypixel=$ypixel;
	$i++;
}


imageline($im,60*$scalefactor,60*$scalefactor,80*$scalefactor,60*$scalefactor,$black);imagettftext($im, 10*$scalefactor, 0, 85*$scalefactor, 65*$scalefactor, $black, $reg, "Raw Data");
imagesetthickness($im,2*$scalefactor);
imageline($im,60*$scalefactor,75*$scalefactor,80*$scalefactor,75*$scalefactor,$aqua);imagettftext($im, 10*$scalefactor, 0, 85*$scalefactor, 80*$scalefactor, $black, $reg, "Historic Predictions");
imagefilledpolygon($im,array(60*$scalefactor,85*$scalefactor,80*$scalefactor,85*$scalefactor,80*$scalefactor,95*$scalefactor,60*$scalefactor,95*$scalefactor),4,$red75);imageline($im,60*$scalefactor,90*$scalefactor,80*$scalefactor,90*$scalefactor,$red);imagettftext($im, 10*$scalefactor, 0, 85*$scalefactor, 95*$scalefactor, $black, $reg, "Predictions");
imagesetthickness($im,1*$scalefactor);

include('labelgraph.php');


$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/TimeSeriesForecasts-'.$randomString.'.png');


$extrastyle="";
if($scalefactor==5){
	$extrastyle='width:100%;height:100%;';
}

echo "<img style='position:absolute;top:0px;left:0px;$extrastyle' src='imagetemp/TimeSeriesForecasts-".$randomString.".png' />";
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
