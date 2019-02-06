<?php
//regresion?
if(array_key_exists('regtype', $_POST)){$regtype=$_POST['regtype'];} else {die('variable not set');}
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
$psize=$_POST['size'];
$ptrans=$_POST['trans'];

array_pop($xpoints);
array_pop($ypoints);

if(empty($xpoints)){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}
if(empty($ypoints)){
	echo "<br><br><br><br><br><center>You must select a numerical y-variable</center>";
	die();
}

$pointlabels=array();
$i=0;
$pointsremoved="id of Points Removed: ";
foreach($xpoints as $xpoint){
	array_push($pointlabels,$i+1);
	if(!array_key_exists($i, $ypoints)){die('Not enough y points!');}
	$ypoint=$ypoints[$i];
	if(!is_numeric($xpoint) || !is_numeric($ypoint)){
		unset($xpoints[$i]);
		unset($ypoints[$i]);
		unset($pointlabels[$i]);
		$pointsremoved.=($i+1).", ";
	}
	$i++;
}
$xpoints = array_values($xpoints);
$ypoints = array_values($ypoints);
$pointlabels = array_values($pointlabels);

if(empty($xpoints)){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}
if(is_numeric($xpoints[0])==FALSE){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}
if(empty($ypoints)){
	echo "<br><br><br><br><br><center>You must select a numerical y-variable</center>";
	die();
}
if(is_numeric($ypoints[0])==FALSE){
	echo "<br><br><br><br><br><center>You must select a numerical y-variable</center>";
	die();
}


// Create image and define colours
if($width<1 || $height<1){echo "Invalid Image Dimensions";die();}
$im = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$red = imagecolorallocate($im, 255, 0, 0);
$green = imagecolorallocate($im, 0, 255, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$grey = imagecolorallocatealpha($im, 150, 150, 150, $ptrans);
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


//
//Axis Labels
//

//yaxis lavel
$bbox = imagettfbbox(12, 0, $bold, "Residual");
$w = $bbox[4]-$bbox[0];
imagettftext($im,12,90,15,$height/2+$w/2,$black,$bold,"Residual");

if($_POST['residualsforcex']=="no"){
	$label = "Fitted";
} else {
	$label = "Explanatory";
}

//xaxis lavel
$bbox = imagettfbbox(12, 0, $bold, $label);
$w = $bbox[4]-$bbox[0];
imagettftext($im,12,0,$width/2-$w/2,$height-5,$black,$bold,$label);

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


//regression
if($regtype=="Linear"){
	require_once( './PolynomialRegression/PolynomialRegression/PolynomialRegression.php' );

	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$PolynomialRegression = new PolynomialRegression( 2 );

	// Add all the data to the regression analysis.
	$i=0;
	foreach ($xpoints as $x){
		$PolynomialRegression->addData( $x, $ypoints[$i] );
		$i++;
	}
	$sumx=array_sum($xpoints);
	$sumy=array_sum($ypoints);

	// Get coefficients for the polynomial.
	$coefficients = $PolynomialRegression->getCoefficients();

	$c=$coefficients[ 0 ];
	$m=$coefficients[ 1 ];
} else if ($regtype=="Quadratic"){
	require_once( './PolynomialRegression/PolynomialRegression/PolynomialRegression.php' );

	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$PolynomialRegression = new PolynomialRegression( 3 );

	// Add all the data to the regression analysis.
	$i=0;
	foreach ($xpoints as $x){
		$PolynomialRegression->addData( $x, $ypoints[$i] );
		$i++;
	}

	// Get coefficients for the polynomial.
	$coefficients = $PolynomialRegression->getCoefficients();

	$a=$coefficients[ 0 ];
	$b=$coefficients[ 1 ];
	$c=$coefficients[ 2 ];
} else if ($regtype=="Cubic"){
	require_once( './PolynomialRegression/PolynomialRegression/PolynomialRegression.php' );

	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$PolynomialRegression = new PolynomialRegression( 4 );

	// Add all the data to the regression analysis.
	$i=0;
	foreach ($xpoints as $x){
		$PolynomialRegression->addData( $x, $ypoints[$i] );
		$i++;
	}

	// Get coefficients for the polynomial.
	$coefficients = $PolynomialRegression->getCoefficients();

	$a=$coefficients[ 0 ];
	$b=$coefficients[ 1 ];
	$c=$coefficients[ 2 ];
	$d=$coefficients[ 3 ];
} else if ($regtype=="y=a*exp(b*x)"){
	require_once( './PolynomialRegression/LinearizedRegression/ExpRegression.php' );

	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$regression = new ExpRegression();

	// Add all the data to the regression analysis.
	$i=0;
	foreach ($xpoints as $x){
		$regression->addData( $x, $ypoints[$i] );
		$i++;
	}

	// Get coefficients for the polynomial.
	$coefficients = $regression->getCoefficients();

	$a=$coefficients[ 0 ];
	$b=$coefficients[ 1 ];
} else if ($regtype=="y=a*ln(x)+b"){
	require_once( './PolynomialRegression/LinearizedRegression/LogRegression.php' );

	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$regression = new LogRegression();

	// Add all the data to the regression analysis.
	$i=0;
	foreach ($xpoints as $x){
		$regression->addData( $x, $ypoints[$i] );
		$i++;
	}

	// Get coefficients for the polynomial.
	$coefficients = $regression->getCoefficients();

	$a=$coefficients[ 1 ];
	$b=$coefficients[ 0 ];
} else if ($regtype=="y=a*x^b"){
	require_once( './PolynomialRegression/LinearizedRegression/PowRegression.php' );

	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$regression = new PowRegression();

	// Add all the data to the regression analysis.
	$i=0;
	foreach ($xpoints as $x){
		$regression->addData( $x, $ypoints[$i] );
		$i++;
	}

	// Get coefficients for the polynomial.
	$coefficients = $regression->getCoefficients();

	$a=$coefficients[ 0 ];
	$b=$coefficients[ 1 ];
}
//end regression
$i=0;
foreach($xpoints as $x){
	if($regtype=="Linear"){
		$fitted=$m*$x+$c;
	} else if ($regtype=="Quadratic"){
		$fitted=$a+$b*$x+$c*pow($x,2);
	} else if ($regtype=="Cubic"){
		$fitted=$a+$b*$x+$c*pow($x,2)+$d*pow($x,3);
	} else if ($regtype=="y=a*exp(b*x)"){
		$fitted=$a*exp($b*$x);
	} else if ($regtype=="y=a*ln(x)+b"){
		$fitted=$a*log($x)+$b;
	} else if ($regtype=="y=a*x^b"){
		$fitted=$a*pow($x,$b);
	}
	$residual=$ypoints[$i]-$fitted;
	if($_POST['residualsforcex']=="no"){
		$xpoints[$i]=$fitted;
	}
	$ypoints[$i]=$residual;
	$i++;
}

//x axis ticks
$minx=min($xpoints);
$maxx=max($xpoints);
$range=$maxx-$minx;
$rangeround=format_number_significant_figures($range,1);
if($rangeround==0){
	$rangeround=1;
}
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
if($range==0){$range=1;}
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
	if(abs($ytick)<pow(10,-10)){$ytick=0;};
	$bbox = imagettfbbox($size, 0, $reg, $ytick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, $size, 0, 43-$w, $distancedown+5, $black, $reg, $ytick);
	imageline ($im, 45, $distancedown, 50, $distancedown, $black);
	$i++;
}



$i=0;
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$yvalue=$ypoints[$i];
	$xpixel=60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
	$ypixel=$height-60-($height-120)*($yvalue-$minytick)/($maxytick-$minytick);
	imagesetthickness($im,2);
	imagearc ($im , $xpixel, $ypixel, $psize , $psize , 0, 359.9 , $grey);
	imagesetthickness($im,1);
	$i++;
	if($label=="yes") {imagettftext($im, 7, 0, $xpixel+3, $ypixel+4, $blue, $reg, $pointlabels[$i-1]);}
}

$zero=$height-60-($height-120)*(-$minytick)/($maxytick-$minytick);
imageline($im, 60, $zero, $width-60, $zero, $grey);

//weighted average curve
$xpoint=$minx;
$step=($maxx-$minx)/200;
$curve=array();

$range=$maxx-$minx;

while($xpoint<$maxx){
	$i=0;
	$total=0;
	$totalweight=0;
	while($i<count($xpoints)){
		$pointx=$xpoints[$i];
		$pointy=$ypoints[$i];
		$weight=pow((1-abs($xpoint-$pointx)/$range),10);
		$total=$total+$pointy*$weight;
		$totalweight+=$weight;
		$i++;
	}
	$xpoint+=$step;
	array_push($curve,array($xpoint,$total/$totalweight));
}



function quadbezier($img, $col, $x0, $y0, $x1, $y1, $x2, $y2, $n = 20) {
	$pts = array();

	for($i = 0; $i <= $n; $i++) {
		$t = $i / $n;
		$t1 = 1 - $t;
		$a = pow($t1, 2);
		$b = 2 * $t * $t1;
		$c = pow($t, 2);

		$x = round($a * $x0 + $b * $x1 + $c * $x2);
		$y = round($a * $y0 + $b * $y1 + $c * $y2);
		$pts[$i] = array($x, $y);
	}

	for($i = 0; $i < $n; $i++) {
		imageline($img, $pts[$i][0], $pts[$i][1], $pts[$i+1][0], $pts[$i+1][1], $col);
	}
}


$i=1;

while($i<count($curve)){
	if($i<count($curve)-1){
		$lastxvalue=$curve[$i-1][0];
		$lastyvalue=$curve[$i-1][1];
		$lastxpixel=60+($width-120)*($lastxvalue-$minxtick)/($maxxtick-$minxtick);
		$lastypixel=$height-60-($height-120)*($lastyvalue-$minytick)/($maxytick-$minytick);
		$xvalue=$curve[$i][0];
		$yvalue=$curve[$i][1];
		$xpixel=60+($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick);
		$ypixel=$height-60-($height-120)*($yvalue-$minytick)/($maxytick-$minytick);
		$nextxvalue=$curve[$i+1][0];
		$nextyvalue=$curve[$i+1][1];
		$nextxpixel=60+($width-120)*($nextxvalue-$minxtick)/($maxxtick-$minxtick);
		$nextypixel=$height-60-($height-120)*($nextyvalue-$minytick)/($maxytick-$minytick);
		quadbezier($im, $blue, $lastxpixel, $lastypixel, $xpixel, $ypixel, $nextxpixel, $nextypixel);
		//cubicbezier($im, $red, $lastlastxpixel, $lastlastypixel, $lastxpixel, $lastypixel, $xpixel, $ypixel, $nextxpixel, $nextypixel);
		//imageline ($im,$lastxpixel,$lastypixel,$xpixel,$ypixel,$red);
		//imageellipse ($im, $xpixel, $ypixel, 5, 5, $blue);
	}
	$i++;
	$i++;
}

include('labelgraph.php');

if(strlen($pointsremoved)>22){
	$bbox = imagettfbbox(8, 0, $reg, $pointsremoved);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, 8, 0, $width-$w, 12, $black, $reg, $pointsremoved);
}

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/Residuals-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/Residuals-".$randomString.".png' />";
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
