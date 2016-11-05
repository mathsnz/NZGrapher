<?php
//regresion?
$regression=$_POST['regression'];
//graph title
$title=$_POST['title'];
//yaxis lavel
$ylabel=$_POST['yaxis']; 
//xaxis label
$xlabel=$_POST['xaxis'];
//labels?
$label=$_POST['labels'];
//points
$xpoints=explode(',', $_POST['xvals']);
//points
$ypoints=explode(',', $_POST['yvals']);

//dimension
$width=$_POST['width'];
$height=$_POST['height'];

array_pop($xpoints);
array_pop($ypoints);
if(empty($xpoints)){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}
if(is_numeric($xpoints[0])==FALSE){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}
$numcategories=1;

// Create image and define colours
$im = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$grey = imagecolorallocate($im, 125, 125, 125);
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

$i=0;
$categories=array();
foreach($xpoints as $xpoint) {
	if(count($ypoints)==0){
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



function Quartile($Array, $Quartile) {
  rsort($Array); 
  $pos = (count($Array) - 1) * $Quartile;
 
  $base = floor($pos);
  $rest = $pos - $base;
 
  if( isset($Array[$base+1]) ) {
    return $Array[$base] + $rest * ($Array[$base+1] - $Array[$base]);
  } else {
    return $Array[$base];
  }
}


ksort($categories);
$offset=0;
foreach($categories as $key => $value){
$category=$key;



$xpoints=array();
$labels=array();
foreach($categories["$category"] as $xvals){
	array_push($xpoints,$xvals[0]);
	array_push($labels,$xvals[1]);
}


$i=0;

//TEMP IMAGE FOR THE SUBSET
$imheight=($height-120)/$numcategories;
// define temp image 
$plot = imagecreatetruecolor($width-60, $imheight); 
imagefill ($plot, 0, 0, $white); 
$bbox = imagettfbbox(10, 0, $bold, $category);
$w = $bbox[4]-$bbox[0];
imagettftext($plot, 10, 0, $width-60-$w, $imheight*0.4, $black, $bold, $category); 

$y=$imheight*0.9-1;
$h=$imheight*0.1;


$xpixels=array();
$xlabels=array();
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$xpixel=round((($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick))/5,0)*5;
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
if($yheight>5){$yheight=5;}

foreach($xpixels as $xpixel) {
	if($lastxpixel==$xpixel) {
		$ypixel=$ypixel-$yheight;
	} else {
		$ypixel=$imheight*0.9-5;
	}
	$lastxpixel=$xpixel;
	imageellipse ($plot, $xpixel, $ypixel, 5, 5, $grey);
	if($label=="yes") {
		imagettftext($plot, 7, 0, $xpixel+3, $ypixel+3, $blue, $reg, $xlabels[$i]); 
	}
	$i++;
}

// copy the temp image back to the real image 
imagecopy ($im, $plot, 60, 60+$offset, 0, 0, $width-120, $imheight);
// destroy temp images, clear memory 
imagedestroy($plot); 

$offset=$offset+$imheight;

}

include('labelgraph.php');




$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/TimeSeriesForecasts-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/TimeSeriesForecasts-".$randomString.".png' />";
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