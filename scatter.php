<?php
//regresion?
if(array_key_exists('regression', $_POST)){$regression=$_POST['regression'];} else {die('variable not set');}
$quadratic=$_POST['quadratic'];
$exp=$_POST['exp'];
$log=$_POST['log'];
$pow=$_POST['pow'];
$yx=$_POST['yx'];
$cubic=$_POST['cubic'];
//jitter
$jitter=$_POST['jitter'];
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
//points
if(isset($_POST['xvals'])){$xpoints=explode(',', $_POST['xvals']);} else {$xpoints=array("");}
if(isset($_POST['yvals'])){$ypoints=explode(',', $_POST['yvals']);} else {$ypoints=array("");}
if(isset($_POST['zvals'])){$zpoints=explode(',', $_POST['zvals']);} else {$zpoints=array("");}
if(isset($_POST['color'])){$colors=explode(',', $_POST['color']);} else {$colors=array("");}
$psize=$_POST['size'];
$ptrans=$_POST['trans'];

function format_number_significant_figures($number, $sf) {
  // How many decimal places do we round and format to?
  // @note May be negative.
  $dp = floor($sf - log10(abs($number)));
  // Round as a regular number.
  $number = round($number, $dp);
  // Leave the formatting to format_number(), but always format 0 to 0dp.
  return number_format($number, 0 == $number ? 0 : $dp,".","");
}

//dimension
$width=$_POST['width'];
$height=$_POST['height'];

array_pop($xpoints);
array_pop($ypoints);
array_pop($zpoints);
array_pop($colors);

if(empty($xpoints)){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable (currently empty)</center>";
	die();
}
if(empty($ypoints)){
	echo "<br><br><br><br><br><center>You must select a numerical y-variable (currently empty)";
	die();
}

$pointlabels=array();
$i=0;
$pointsremoved="id of Points Removed: ";
foreach($xpoints as $xpoint){
	array_push($pointlabels,$i+1);
	if(array_key_exists($i,$ypoints)){
		$ypoint=$ypoints[$i];
	} else {
		echo "error in the data";
		die();
	}
	if(!is_numeric($xpoint) || !is_numeric($ypoint)){
		unset($xpoints[$i]);
		unset($ypoints[$i]);
		unset($zpoints[$i]);
		unset($colors[$i]);
		unset($pointlabels[$i]);
		$pointsremoved.=($i+1).", ";
	}
	$i++;
}
$xpoints = array_values($xpoints);
$ypoints = array_values($ypoints);
$zpoints = array_values($zpoints);
$colors = array_values($colors);
$pointlabels = array_values($pointlabels);

if(empty($xpoints)){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable (currently non-numerical)</center>";
	die();
}
if(is_numeric($xpoints[0])==FALSE){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable (currently non-numerical)</center>";
	die();
}
if(empty($ypoints)){
	echo "<br><br><br><br><br><center>You must select a numerical y-variable (currently non-numerical)";
	die();
}
if(is_numeric($ypoints[0])==FALSE){
	echo "<br><br><br><br><br><center>You must select a numerical y-variable (currently non-numerical)</center>";
	die();
}

$numcategories=count(array_unique($zpoints));
if($numcategories>4 && is_numeric($zpoints[0])==TRUE){
	$min=min($zpoints);
	$max=max($zpoints);
	$range=$max-$min;
	$i=0;
	$c1max=format_number_significant_figures($min+$range/4,2);
	$c2max=format_number_significant_figures($c1max+$range/4,2);
	$c3max=format_number_significant_figures($c2max+$range/4,2);
	foreach($zpoints as $zpoint){
		if ($zpoint<$c1max){
			$zpoints[$i]="a: < $c1max";
		} else if ($zpoint<$c2max){
			$zpoints[$i]="b: $c1max - $c2max";
		} else if ($zpoint<$c3max){
			$zpoints[$i]="c: $c2max - $c3max";
		} else {
			$zpoints[$i]="d: > $c3max";
		}
		$i++;
	}
	$numcategories=4;
}
if($numcategories>4){
	echo "<br><br><br><br><br><center>You must select a categorical subset variable with 4 or fewer categories or a numerical subset variable which will automatically be split into 4 categories.</center>";
	die();
}
if($numcategories==0){
	$numcategories=1;
}

// Create image and define colours
if($width<1 || $height<1){echo "Invalid Image Dimensions";die();}
$im = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$red = imagecolorallocate($im, 255, 0, 0);
$green = imagecolorallocate($im, 0, 255, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$grey = imagecolorallocatealpha($im, 0, 0, 0, $ptrans);
$darkgreen = imagecolorallocate($im, 0, 200, 0);
$purple = imagecolorallocate($im, 128, 0, 255);
$orange = imagecolorallocate($im, 255, 128, 0);
$darkteal = imagecolorallocate($im, 0, 197, 197);
$reg = './Roboto-Regular.ttf';
$bold = './Roboto-Bold.ttf';

//make image white
imagefill ($im, 0, 0, $white);

//draw area of graph
imagerectangle ($im, 50, 50, $width-50, $height-50, $black);

//graph title
$bbox = imagettfbbox(14, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 14, 0, $width/2-$w/2, 30, $black, $bold, $title);



// sort out colors
include('colours.php');




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


//x axis ticks
$minx=min($xpoints);
$maxx=max($xpoints);
if($minx==$maxx){$minx=$minx-1;$maxx=$maxx+1;}
$range=$maxx-$minx;
$rangeround=format_number_significant_figures($range,1);
if($rangeround==0){$rangeround=1;}
$steps=FirstSF($rangeround);
if($steps<2) {
	$steps=$steps*5;
}
if($steps<3) {
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
//Temp image for x-axis
$axisheight=30;
$axiswidth=($width-100)/$numcategories;
// define temp image
$axis = imagecreatetruecolor($axiswidth, $axisheight);
imagefill ($axis, 0, 0, $white);

//set font size for x-axis
$size=0;
$w=0;
while($w<21 && $size<11){
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
imagettftext($axis, $size, 0, 10-$w/2, 20, $black, $reg, $minxtick);
imageline ($axis, 10, 0, 10, 5, $black);
//max x mark
$bbox = imagettfbbox($size, 0, $reg, $maxxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($axis, $size, 0, $axiswidth-10-$w/2, 20, $black, $reg, $maxxtick);
imageline ($axis, $axiswidth-10, 0, $axiswidth-10, 5, $black);

//other ticks
$i=1;
$distanceright=10;
$xtick=$minxtick;
while($i<$steps){
	$distanceright=$distanceright+($axiswidth-20)/$steps;
	$xtick=$xtick+$step;
	$bbox = imagettfbbox($size, 0, $reg, $xtick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($axis, $size, 0, $distanceright-$w/2, 20, $black, $reg, $xtick);
	imageline ($axis, $distanceright, 0, $distanceright, 5, $black);
	$i++;
}
$offset=0;
while ($offset<$width-100){
	// copy the temp image back to the real image
	imagecopy ($im, $axis, 50+$offset, $height-49, 0, 0, $axiswidth, $axisheight);
	$offset=$offset+$axiswidth;
}
$offset=0;
// destroy temp images, clear memory
imagedestroy($axis);

//y axis ticks
$miny=min($ypoints);
$maxy=max($ypoints);
if($miny==$maxy){$miny=$miny-1;$maxy=$maxy+1;}
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
$categories=array();
foreach($xpoints as $xpoint) {
	if(count($zpoints)==0){
		$category=" ";
	} else {
		$category=$zpoints[$i];
	}
	$i++;
	if(array_key_exists($category,$categories)){
		array_push($categories["$category"], array($xpoint,$ypoints[$i-1],$pointlabels[$i-1],$colors[$i-1]));;
	} else {
		$categories["$category"]=array(array($xpoint,$ypoints[$i-1],$pointlabels[$i-1],$colors[$i-1]));
	}
}

$numcategories=count($categories);
ksort($categories);
foreach($categories as $key => $value){
$category=$key;



$xpoints=array();
$ypoints=array();
$labels=array();
$colors=array();
foreach($categories["$category"] as $xvals){
	array_push($xpoints,$xvals[0]);
	array_push($ypoints,$xvals[1]);
	array_push($labels,$xvals[2]);
	array_push($colors,$xvals[3]);
}

//TEMP IMAGE FOR THE SUBSET
$imheight=($height-100);
$imwidth=($width-100)/$numcategories;
// define temp image
if($imwidth<1 || $imheight<1){echo "Invalid Image Dimensions";die();}
$plot = imagecreatetruecolor($imwidth, $imheight);
imagefill ($plot, 0, 0, $white);
$bbox = imagettfbbox(12, 0, $bold, $category);
$w = $bbox[4]-$bbox[0];
imagettftext($plot, 10, 0, $imwidth/2-$w/2, $imheight-10, $black, $bold, $category);
imageline($plot,0,0,0,$imheight,$black);
imageline($plot,0,0,$imwidth,0,$black);

$i=0;
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$yvalue=$ypoints[$i];
	if($jitter=='yes'){
		mt_srand(($xvalue+0.123456)*($yvalue+0.1234565)*$i);
		$jitx=mt_rand(-3,3);
		$jity=mt_rand(-3,3);
	} else {
		$jitx=0;
		$jity=0;
	}
	$xpixel=10+($imwidth-20)*($xvalue-$minxtick)/($maxxtick-$minxtick)+$jitx;
	$ypixel=($imheight-10)-($imheight-20)*($yvalue-$minytick)/($maxytick-$minytick)+$jity;
	imagesetthickness($plot,2);
	imagearc ($plot , $xpixel, $ypixel, $psize , $psize , 0, 359.9 , $colors[$i]);
	imagesetthickness($plot,1);
	if($label=="yes") {imagettftext($plot, 7, 0, $xpixel+3, $ypixel+4, $blue, $reg, $labels[$i]);}
	$i++;
}

$rtop=20;
if($regression=="yes") {
	require_once( './PolynomialRegression/PolynomialRegression/PolynomialRegression.php' );

	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$PolynomialRegression = new PolynomialRegression( 2 );

	// Add all the data to the regression analysis.
	$i=0;
	$sumxx=0;$sumxy=0;$sumyy=0;
	foreach ($xpoints as $x){
		$PolynomialRegression->addData( $x, $ypoints[$i] );
		$sumxx+=$x*$x;
		$sumxy+=$x*$ypoints[$i];
		$sumyy+=$ypoints[$i]*$ypoints[$i];
		$i++;
	}
	$sumx=array_sum($xpoints);
	$sumy=array_sum($ypoints);

	// Get coefficients for the polynomial.
	$coefficients = $PolynomialRegression->getCoefficients();

	$c=$coefficients[ 0 ];
	$m=$coefficients[ 1 ];
	if($i>1){
		$den=(($sumxx-((1/$i)*$sumx*$sumx)))*($sumyy-((1/$i)*$sumy*$sumy));
		if($den>0){
			$r=($sumxy-((1/$i)*$sumx*$sumy))/sqrt($den);
		}
	}

	// y=$m*x+$c
	$yleft=$m*$minxtick+$c;
	$ypixelleft=$imheight-10-($imheight-20)*($yleft-$minytick)/($maxytick-$minytick);
	$yright=$m*$maxxtick+$c;
	$ypixelright=$imheight-10-($imheight-20)*($yright-$minytick)/($maxytick-$minytick);
	$xpixelleft=10;
	$xpixelright=$imwidth-10;


	imageline ($plot, $xpixelleft, $ypixelleft, $xpixelright, $ypixelright, $red);

	$equation=$ylabel." = ".format_number_significant_figures($m,5)."*".$xlabel." + ".format_number_significant_figures($c,5);

	imagettftext($plot, 10, 0, 10, $rtop, $red, $reg, $equation);
	imagettftext($plot, 10, 0, 10, $rtop+15, $red, $reg, "r = ".round($r,5));
	$rtop+=30;
	$lastcol=$red;
}

if($quadratic=="yes") {
	require_once( './PolynomialRegression/PolynomialRegression/PolynomialRegression.php' );
  if(count($xpoints)<3){
    die('Not enough points for a quadratic. Please deselected it.');
  }
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

	$x=$minxtick;
	$step=($maxxtick-$minxtick)/500;
	$lastxpixel="";
	$lastypixel="";
	while($x<=$maxxtick){
		$y=$a+$b*$x+$c*pow($x,2);
		$xpixel=10+($imwidth-20)*($x-$minxtick)/($maxxtick-$minxtick);
		$ypixel=($imheight-10)-($imheight-20)*($y-$minytick)/($maxytick-$minytick);
		if($lastxpixel!="" && $lastypixel!=""){
			imageline($plot,$lastxpixel,$lastypixel,$xpixel,$ypixel,$blue);
		}
		$x+=$step;
		$lastxpixel=$xpixel;
		$lastypixel=$ypixel;
	}

	$equation=$ylabel." = ".format_number_significant_figures($a,5)." + ".format_number_significant_figures($b,5)."*".$xlabel." + ".format_number_significant_figures($c,5)."*".$xlabel."²"; //³

	imagettftext($plot, 10, 0, 10, $rtop, $blue, $reg, $equation);
	$rtop+=15;
	$lastcol=$blue;
}

if($cubic=="yes") {
	require_once( './PolynomialRegression/PolynomialRegression/PolynomialRegression.php' );
  if(count($xpoints)<3){
    die('Not enough points for a cubic. Please deselected it.');
  }
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

	$x=$minxtick;
	$step=($maxxtick-$minxtick)/500;
	$lastxpixel="";
	$lastypixel="";
	while($x<=$maxxtick){
		$y=$a+$b*$x+$c*pow($x,2)+$d*pow($x,3);
		$xpixel=10+($imwidth-20)*($x-$minxtick)/($maxxtick-$minxtick);
		$ypixel=($imheight-10)-($imheight-20)*($y-$minytick)/($maxytick-$minytick);
		if($lastxpixel!="" && $lastypixel!=""){
			imageline($plot,$lastxpixel,$lastypixel,$xpixel,$ypixel,$darkgreen);
		}
		$x+=$step;
		$lastxpixel=$xpixel;
		$lastypixel=$ypixel;
	}

	$equation=$ylabel." = ".format_number_significant_figures($a,5)." + ".format_number_significant_figures($b,5)."*".$xlabel." + ".format_number_significant_figures($c,5)."*".$xlabel."² + ".format_number_significant_figures($d,5)."*".$xlabel."³"; //

	imagettftext($plot, 10, 0, 10, $rtop, $darkgreen, $reg, $equation);
	$rtop+=15;
	$lastcol=$darkgreen;
}

if($exp=="yes") {
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

	$x=$minxtick;
	$step=($maxxtick-$minxtick)/500;
	$lastxpixel="";
	$lastypixel="";
	while($x<=$maxxtick){
		$y=$a*exp($b*$x);
		$xpixel=10+($imwidth-20)*($x-$minxtick)/($maxxtick-$minxtick);
		$ypixel=($imheight-10)-($imheight-20)*($y-$minytick)/($maxytick-$minytick);
		if($lastxpixel!="" && $lastypixel!=""){
			imageline($plot,$lastxpixel,$lastypixel,$xpixel,$ypixel,$purple);
		}
		$x+=$step;
		$lastxpixel=$xpixel;
		$lastypixel=$ypixel;
	}

	$equation=$ylabel." = ".format_number_significant_figures($a,5)." exp( ".format_number_significant_figures($b,5)."*".$xlabel.")"; //

	imagettftext($plot, 10, 0, 10, $rtop, $purple, $reg, $equation);
	$rtop+=15;
	$lastcol=$purple;
}
if($log=="yes") {
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

	$x=$minxtick;
	$step=($maxxtick-$minxtick)/500;
	$lastxpixel="";
	$lastypixel="";
	while($x<=$maxxtick){
		$y=$a*log($x)+$b;
		$xpixel=10+($imwidth-20)*($x-$minxtick)/($maxxtick-$minxtick);
		$ypixel=($imheight-10)-($imheight-20)*($y-$minytick)/($maxytick-$minytick);
		if($lastxpixel!="" && $lastypixel!=""){
			imageline($plot,$lastxpixel,$lastypixel,$xpixel,$ypixel,$orange);
		}
		$x+=$step;
		$lastxpixel=$xpixel;
		$lastypixel=$ypixel;
	}

	$equation=$ylabel." = ".format_number_significant_figures($a,5)." ln( ".$xlabel.") + ".format_number_significant_figures($b,5); //

	imagettftext($plot, 10, 0, 10, $rtop, $orange, $reg, $equation);
	$rtop+=15;
	$lastcol=$orange;
}

if($pow=="yes") {
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

	$x=$minxtick;
	$step=($maxxtick-$minxtick)/500;
	$lastxpixel="";
	$lastypixel="";
	while($x<=$maxxtick){
		$y=$a*pow($x,$b);
		$xpixel=10+($imwidth-20)*($x-$minxtick)/($maxxtick-$minxtick);
		$ypixel=($imheight-10)-($imheight-20)*($y-$minytick)/($maxytick-$minytick);
		if($lastxpixel!="" && $lastypixel!=""){
			imageline($plot,$lastxpixel,$lastypixel,$xpixel,$ypixel,$darkteal);
		}
		$x+=$step;
		$lastxpixel=$xpixel;
		$lastypixel=$ypixel;
	}

	$equation=$ylabel." = ".format_number_significant_figures($a,5)." * ".$xlabel." ^ ".format_number_significant_figures($b,5); //

	imagettftext($plot, 10, 0, 10, $rtop, $darkteal, $reg, $equation);
	$rtop+=15;
	$lastcol=$darkteal;
}
if($yx=="yes") {
	$b=0;

	$x=$minxtick;
	$step=($maxxtick-$minxtick)/500;
	$lastxpixel="";
	$lastypixel="";
	$i=0;
	while($x<=$maxxtick){
		$y=$x;
		$xpixel=10+($imwidth-20)*($x-$minxtick)/($maxxtick-$minxtick);
		$ypixel=($imheight-10)-($imheight-20)*($y-$minytick)/($maxytick-$minytick);
		if($lastxpixel!="" && $lastypixel!=""){
			if($i/5==round($i/5)){
				imageline($plot,$lastxpixel,$lastypixel,$xpixel,$ypixel,$black);
			}
		}
		$x+=$step;
		$lastxpixel=$xpixel;
		$lastypixel=$ypixel;
		$i++;
	}

	$equation="- - - y = x";

	imagettftext($plot, 10, 0, 10, $rtop, $black, $reg, $equation);
	$rtop+=15;
	$lastcol=$black;
}
if($regression=="yes" || $quadratic=="yes" || $cubic=="yes" || $exp=="yes" || $log=="yes" || $pow=="yes"){
	imagettftext($plot, 10, 0, 10, $rtop, $lastcol, $reg, "num = $i");
}

// copy the temp image back to the real image
imagecopy ($im, $plot, 50+$offset, 50, 0, 0, $imwidth, $imheight);
// destroy temp images, clear memory
imagedestroy($plot);

$offset=$offset+$imwidth;
}

include('labelgraph.php');

if(strlen($pointsremoved)>22){
	$bbox = imagettfbbox(8, 0, $reg, $pointsremoved);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, 8, 0, $width-$w, 12, $black, $reg, $pointsremoved);
}

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/Scatter-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/Scatter-".$randomString.".png' />";
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
