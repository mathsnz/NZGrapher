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

//dimension
$width=$_POST['width'];
$height=$_POST['height'];
$psize=$_POST['size']; 

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

if(empty($oxpoints)){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}
if(is_numeric($oxpoints[0])==FALSE){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}
$numcategories=count(array_unique($ypoints))-1;
if($numcategories!=2){
	echo "<br><br><br><br><br><center>You must select a categorical y-variable with 2 categories.</center>";
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

//yaxis lavel
$bbox = imagettfbbox(12, 0, $bold, $ylabel);
$w = $bbox[4]-$bbox[0];
imagettftext($im,12,90,15,$height/2+$w/2,$black,$bold,$ylabel);

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
$i=0;
$means=array();
foreach ($categories as $values){
	$points=array();
	foreach($values as $value){
		array_push($points,$value[0]);
	}
	$mean=Quartile($points,0.5);
	array_push($means,$mean);
	$i++;
}
$differ=max($means)-min($means);

//x axis ticks
$minx=min($oxpoints);
$maxx=max($oxpoints);
$range=$maxx-$minx;
if($differ*2>$range){
	$minx=($minx+$maxx)/2-$differ;
	$maxx=($minx+$maxx)/2+$differ;
	$range=$maxx-$minx;
}
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
if($step==0){$step=1;}
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
$medians=array();
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
$imheight=($height-120)/$numcategories;
// define temp image 
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
array_push($medians,$mean);
$meangraph=round((($width-120)*($mean-$minxtick)/($maxxtick-$minxtick))/5,0)*5;
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
imagefilledellipse ($plot,$meangraph,$y,8,8,$red);

// copy the temp image back to the real image 
imagecopy ($im, $plot, 60, 60+$offset, 0, 0, $width-120, $imheight);
// destroy temp images, clear memory 
imagedestroy($plot); 

$offset=$offset+$imheight;

}

$height=$height*2;

$diff=$medians[0]-$medians[1];

if($diff<0){
	$arrow=5;
	$diff=-$diff;
	$reverse=-1;
} else {
	$arrow=5;
	$reverse=1;
}

if($reverse==1){
	$title="Difference Between Means (".$cnames[0]." - ".$cnames[1].")";
} else {
	$title="Difference Between Means (".$cnames[1]." - ".$cnames[0].")";
}
$bbox = imagettfbbox(12, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 12, 0, $width/2-$w/2, $height-10, $black, $bold, $title); 

$offset=$minxtick+$step*floor($steps/2);
$offset=-$offset;
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

$bootstrapdifs=array();
$num=count($oxpoints);
$b=0;
$oypoints=$ypoints;
$errorcount=0;
while($b<1000){
	$i=0;
	$bootstrap1=array();
	$bootstrap2=array();
	$ypoints=$oypoints;
	while($i<$num){
		$max=$num-$i-1;
		$category=mt_rand(0,$max);
		$cat=$ypoints[$category];
		unset($ypoints[$category]);
		sort($ypoints);
		if($cat==$cnames[0]){
			array_push($bootstrap1,$oxpoints[$i]);
		} else {
			array_push($bootstrap2,$oxpoints[$i]);
		}
		$i++;
	}
	if(count($bootstrap1)>0 && count($bootstrap2)>0){
		$mean1=format_number_significant_figures(array_sum($bootstrap1)/count($bootstrap1),5);
		$mean2=format_number_significant_figures(array_sum($bootstrap2)/count($bootstrap2),5);
		$dif=($mean1-$mean2)*$reverse;
	
		array_push($bootstrapdifs,$dif);
	
		$b++;
	} else {
		$errorcount++;
		if($errorcount>10){
			die("an error happend $errorcount times... something must be up with the data");
		}
	}
}


rsort($bootstrapdifs);

$xpixels=array();
$p=0;
foreach($bootstrapdifs as $xvalue){
	$xpixel=round((($width-120)*($xvalue-$minxtick)/($maxxtick-$minxtick))/($psize/2),0)*$psize/2+60;
	if($xvalue>=$diff){
		$p++;
	}
	$i++;
	array_push($xpixels,intval($xpixel));
}


//draw the line for the difference between means;
$x1=($width-120)*(-$minxtick)/($maxxtick-$minxtick)+60;
$x2=($width-120)*($diff-$minxtick)/($maxxtick-$minxtick)+60;

$i=0;
$lastxpixel=-100000;
$count=array_count_values($xpixels);
$max=max($count);
$yheight=($height*0.5-100)/$max;
if($yheight>$psize){$yheight=$psize;}
$color=$grey;
foreach($xpixels as $xpixel) {
	if($lastxpixel==$xpixel) {
		$ypixel=$ypixel-$yheight;
	} else {
		$ypixel=$height-90;
	}
	$lastxpixel=$xpixel;
	if($i>$p){
		$color=$lightgrey;
	}
	imagesetthickness($im,2); 
	imagearc ($im , $xpixel , $ypixel , $psize , $psize , 0, 359.9 , $color);
	imagesetthickness($im,1); 
	$i++;
}

if($diff<0){
	$arrow=-5;
} else {
	$arrow=5;
}

imagesetthickness ($im,3);
imageline($im,$x1,$height-90,$x2,$height-90,$blue);
imageline($im,$x2-$arrow,$height-95,$x2,$height-90,$blue);
imageline($im,$x2-$arrow,$height-85,$x2,$height-90,$blue);

imagesetthickness ($im,2);
$max=$bootstrapdifs[975];
$maxxpixel=round((($width-120)*($max-$minxtick)/($maxxtick-$minxtick)),0)+60;
imageline($im,$x2,$height-75,$x2,$height*0.5+10,$blue);

$bbox = imagettfbbox(8, 0, $reg, $diff);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 8, 0, $x2-$w-3, $height-75, $blue, $reg, $diff);

$bbox = imagettfbbox(8, 0, $reg, $diff);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 8, 0, $x2+3, $height*0.5+40, $blue, $reg, "p = $p/1000");
imagettftext($im, 8, 0, $x2+3, $height*0.5+50, $blue, $reg, "   = ".$p/1000);


include('labelgraph.php');

if(strlen($pointsremoved)>22){
	$bbox = imagettfbbox(8, 0, $reg, $pointsremoved);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, 8, 0, $width-$w, 12, $black, $reg, $pointsremoved);
}

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/BSRerandomMean-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/BSRerandomMean-".$randomString.".png' />";
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