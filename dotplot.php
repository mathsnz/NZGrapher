<?php
//regresion?
if(array_key_exists('regression', $_POST)){$regression=$_POST['regression'];} else {die('variable not set');}
//box plots?
if(array_key_exists('boxplot', $_POST)){$boxplot=$_POST['boxplot'];} else {die('variable not set');}
//informal confidence interval?
$interval=$_POST['interval'];
//informal confidence interval limits?
$intervallim=$_POST['intervallim'];
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

//dimension
$width=$_POST['width'];
$height=$_POST['height'];

array_pop($xpoints);
array_pop($ypoints);
array_pop($zpoints);
array_pop($colors);

$pointlabels=array();
$i=0;
$pointsremoved="id of Points Removed: ";
foreach($xpoints as $xpoint){
	array_push($pointlabels,$i+1);
	if(!is_numeric($xpoint)){
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
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}
if(is_numeric($xpoints[0])==FALSE){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}
$numcategories=count(array_unique($ypoints));
if($numcategories>11 && is_numeric($ypoints[0])==TRUE){
	$min=min($ypoints);
	$max=max($ypoints);
	$range=$max-$min;
	$i=0;
	$c1max=format_number_significant_figures($min+$range/4,2);
	$c2max=format_number_significant_figures($c1max+$range/4,2);
	$c3max=format_number_significant_figures($c2max+$range/4,2);
	foreach($ypoints as $zpoint){
		if ($zpoint<$c1max){
			$ypoints[$i]="a: < $c1max";
		} else if ($zpoint<$c2max){
			$ypoints[$i]="b: $c1max - $c2max";
		} else if ($zpoint<$c3max){
			$ypoints[$i]="c: $c2max - $c3max";
		} else {
			$ypoints[$i]="d: > $c3max";
		}
		$i++;
	}
	$numcategories=4;
}
if($numcategories>11){
	echo "<br><br><br><br><br><center>You must select a categorical y-variable with 10 or fewer categories or a numerical y-variable which will automatically be split into 4 categories.</center>";
	die();
}
if($numcategories==0){
	$numcategories=1;
	foreach($xpoints as $key => $value){
		$ypoints[$key]=" ";
	}
}

$numsubsets=count(array_unique($zpoints));
if($numsubsets>4 && is_numeric($zpoints[0])==TRUE){
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
	$numsubsets=4;
}
if($numsubsets>4){
	echo "<br><br><br><br><br><center>You must select a categorical subset variable with 4 or fewer categories or a numerical subset variable which will automatically be split into 4 categories.</center>";
	die();
}
if($numsubsets==0){
	$numsubsets=1;
}


// Create image and define colours
if($width<1 || $height<1){echo "Invalid Image Dimensions";die();}
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

//graph title
$bbox = imagettfbbox(12, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 14, 0, $width/2-$w/2, 30, $black, $bold, $title);



// sort out colors
include('colours.php');




//
//Axis Labels
//

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


//x axis ticks
$minx=min($xpoints);
$maxx=max($xpoints);
if($minx==$maxx){$minx=$minx-1;$maxx=$maxx+1;}
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
if($maxxtick==$minxtick){
	$maxxtick++;
	$minxtick--;
}
$steps=($maxxtick-$minxtick)/$step;
//Temp image for x-axis
$axisheight=30;
$axiswidth=($width-100)/$numsubsets;
// define temp image
$axis = imagecreatetruecolor($axiswidth, $axisheight);
imagefill ($axis, 0, 0, $white);

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

//axis line
imageline ($axis, 60, 0, $axiswidth, 0, $black);

//min x mark
$bbox = imagettfbbox($size, 0, $reg, $minxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($axis, $size, 0, 70-$w/2, 20, $black, $reg, $minxtick);
imageline ($axis, 70, 0, 70, 5, $black);
//max x mark
$bbox = imagettfbbox($size, 0, $reg, $maxxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($axis, $size, 0, $axiswidth-10-$w/2, 20, $black, $reg, $maxxtick);
imageline ($axis, $axiswidth-10, 0, $axiswidth-10, 5, $black);

//other ticks
$i=1;
$distanceright=70;
$xtick=$minxtick;
while($i<$steps){
	$distanceright=$distanceright+($axiswidth-80)/$steps;
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

$i=0;
$subsets=array();
foreach($xpoints as $xpoint) {
	if(count($zpoints)==0){
		$subset=" ";
	} else {
		if(array_key_exists($i,$zpoints)){
			$subset=$zpoints[$i];
		} else {
			$subset=" ";
		}
	}
	$i++;
	if(array_key_exists($subset,$subsets)){
		array_push($subsets["$subset"], array($xpoint,$ypoints[$i-1],$pointlabels[$i-1],$colors[$i-1]));;
	} else {
		$subsets["$subset"]=array(array($xpoint,$ypoints[$i-1],$pointlabels[$i-1],$colors[$i-1]));
	}
}

include 'meanmedsd.php';

$offsetsub=0;
$numsubsets=count($subsets);
ksort($subsets);

$catnames=array_unique($ypoints);
sort($catnames);



foreach($subsets as $key => $value){
$name=$key;

$xpoints=array();
$ypoints=array();
$labels=array();
foreach($subsets["$key"] as $xvals){
	array_push($xpoints,$xvals[0]);
	array_push($ypoints,$xvals[1]);
	array_push($labels,$xvals[2]);
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
		array_push($categories["$category"], array($xpoint,$labels[$i-1],$colors[$i-1]));;
	} else {
		$categories["$category"]=array(array($xpoint,$labels[$i-1],$colors[$i-1]));
	}
}


//TEMP IMAGE FOR THE SUBSET
$subheight=($height-100);
$subwidth=($width-100)/$numsubsets;
// define temp image
if($subheight<10 || $subwidth<10){echo "Invalid window size.";die();}
$sub = imagecreatetruecolor($subwidth, $subheight);
imagefill ($sub, 0, 0, $white);
$bbox = imagettfbbox(12, 0, $bold, $name);
$w = $bbox[4]-$bbox[0];
imagettftext($sub, 10, 0, $subwidth/2+30-$w/2, 10, $black, $bold, $name);



$imheight=($subheight-20)/$numcategories;

ksort($categories);
$offset=15;

foreach($categories as $key => $value){
$category=$key;

$xpoints=array();
$labels=array();
foreach($categories["$category"] as $xvals){
	array_push($xpoints,$xvals[0]);
	array_push($labels,$xvals[1]);
}


$i=0;

//TEMP IMAGE FOR THE CATEGORY
$imheight=($subheight-20)/$numcategories;
// define temp image
if($subwidth<1 || $imheight<1){echo "Invalid Image Dimensions";die();}
$plot = imagecreatetruecolor($subwidth, $imheight);
imagefill ($plot, 0, 0, $white);
$bbox = imagettfbbox(10, 0, $bold, $category);
$w = $bbox[4]-$bbox[0];
imagettftext($plot, 10, 0, $subwidth-$w-1, $imheight*0.4, $black, $bold, $category);


$min=min($xpoints);
$mingraph=round((($subwidth-80)*($min-$minxtick)/($maxxtick-$minxtick))/1,0)*1+70;
$LQ=array_firstQuartile($xpoints);
$LQgraph=round((($subwidth-80)*($LQ-$minxtick)/($maxxtick-$minxtick))/1,0)*1+70;
$med=Quartile($xpoints,0.5);
$medgraph=round((($subwidth-80)*($med-$minxtick)/($maxxtick-$minxtick))/1,0)*1+70;
$mean=format_number_significant_figures(array_sum($xpoints)/count($xpoints),5);
$meangraph=round((($subwidth-80)*($mean-$minxtick)/($maxxtick-$minxtick))/1,0)*1+70;
$UQ=array_thirdQuartile($xpoints);
$UQgraph=round((($subwidth-80)*($UQ-$minxtick)/($maxxtick-$minxtick))/1,0)*1+70;
$max=max($xpoints);
$maxgraph=round((($subwidth-80)*($max-$minxtick)/($maxxtick-$minxtick))/1,0)*1+70;
$sd=format_number_significant_figures(sd($xpoints),5);
$num=count($xpoints);
$y=$imheight*0.9-1;
$h=$imheight*0.1;
$intmin=$med-1.5*($UQ-$LQ)/sqrt($num);
$intmingraph=round((($subwidth-80)*($intmin-$minxtick)/($maxxtick-$minxtick))/1,0)*1+70;
$intmax=$med+1.5*($UQ-$LQ)/sqrt($num);
$intmaxgraph=round((($subwidth-80)*($intmax-$minxtick)/($maxxtick-$minxtick))/1,0)*1+70;

$xpointsformm=$xpoints;
sort($xpointsformm);
$newmin=$min;
$i=0;
while($i<count($xpointsformm) && $newmin<$LQ-($UQ-$LQ)){
	$newmin=$xpointsformm[$i];
	$i++;
}
$newmingraph=round((($subwidth-80)*($newmin-$minxtick)/($maxxtick-$minxtick))/1,0)*1+70;
$newmax=$max;
$i=count($xpointsformm)-1;
while($i>=0 && $newmax>$UQ+($UQ-$LQ)){
	$newmax=$xpointsformm[$i];
	$i--;
}
$newmaxgraph=round((($subwidth-80)*($newmax-$minxtick)/($maxxtick-$minxtick))/1,0)*1+70;
$i=0;

$top=$imheight*0.5-39;
if($regression=="yes") {
	imagettftext($plot, 8, 0, 10, $top+8, $red, $reg, "min:  $min");
	imagettftext($plot, 8, 0, 10, $top+18, $red, $reg, "LQ:   $LQ");
	imagettftext($plot, 8, 0, 10, $top+28, $red, $reg, "med:  $med");
	imagettftext($plot, 8, 0, 10, $top+38, $red, $reg, "mean:  $mean");
	imagettftext($plot, 8, 0, 10, $top+48, $red, $reg, "UQ:  $UQ");
	imagettftext($plot, 8, 0, 10, $top+58, $red, $reg, "max:  $max");
	imagettftext($plot, 8, 0, 10, $top+68, $red, $reg, "sd:  $sd");
	imagettftext($plot, 8, 0, 10, $top+78, $red, $reg, "num:  $num");
}


$xpixels=array();
$xlabels=array();
while($i<count($xpoints)){
	$xvalue=$xpoints[$i];
	$xpixel=round((($subwidth-80)*($xvalue-$minxtick)/($maxxtick-$minxtick))/$psize,0)*$psize+70;
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
		$ypixel=$imheight*0.9-10;
	}
	$lastxpixel=$xpixel;
	imagesetthickness($plot,2);
	imagearc ($plot , $xpixel , $ypixel , $psize , $psize , 0, 359.9 , $colors[array_search($xlabels[$i],$pointlabels)]);
	imagesetthickness($plot,1);
	if($label=="yes") {
		imagettftext($plot, 7, 0, $xpixel+3, $ypixel+3, $blue, $reg, $xlabels[$i]);
	}
	$i++;
}


if($boxplot=="yes") {
	imageline ($plot, $mingraph, $y-5, $mingraph, $y+5, $black);
	imageline ($plot, $LQgraph, $y-$h, $LQgraph, $y+$h, $black);
	imageline ($plot, $medgraph, $y-$h, $medgraph, $y+$h, $black);
	imageline ($plot, $UQgraph, $y-$h, $UQgraph, $y+$h, $black);
	imageline ($plot, $maxgraph, $y-5, $maxgraph, $y+5, $black);
	imageline ($plot, $mingraph, $y, $LQgraph, $y, $black);
	imageline ($plot, $LQgraph, $y+$h, $UQgraph, $y+$h, $black);
	imageline ($plot, $LQgraph, $y-$h, $UQgraph, $y-$h, $black);
	imageline ($plot, $UQgraph, $y, $maxgraph, $y, $black);
}
$highboxplot=$_POST['highboxplot'];
if($highboxplot=="yes") {
	$y=$imheight*0.1+1;
	imageline ($plot, $mingraph, $y-5, $mingraph, $y+5, $black);
	imageline ($plot, $LQgraph, $y-$h, $LQgraph, $y+$h, $black);
	imageline ($plot, $medgraph, $y-$h, $medgraph, $y+$h, $black);
	imageline ($plot, $UQgraph, $y-$h, $UQgraph, $y+$h, $black);
	imageline ($plot, $maxgraph, $y-5, $maxgraph, $y+5, $black);
	imageline ($plot, $mingraph, $y, $LQgraph, $y, $black);
	imageline ($plot, $LQgraph, $y+$h, $UQgraph, $y+$h, $black);
	imageline ($plot, $LQgraph, $y-$h, $UQgraph, $y-$h, $black);
	imageline ($plot, $UQgraph, $y, $maxgraph, $y, $black);
}

$boxnowhisker=$_POST['boxnowhisker'];
if($boxnowhisker=="yes") {
	imageline ($plot, $LQgraph, $y-$h, $LQgraph, $y+$h, $black);
	imageline ($plot, $medgraph, $y-$h, $medgraph, $y+$h, $black);
	imageline ($plot, $UQgraph, $y-$h, $UQgraph, $y+$h, $black);
	imageline ($plot, $LQgraph, $y+$h, $UQgraph, $y+$h, $black);
	imageline ($plot, $LQgraph, $y-$h, $UQgraph, $y-$h, $black);
}

$boxnooutlier=$_POST['boxnooutlier'];
if($boxnooutlier=="yes") {
	imageline ($plot, $newmingraph, $y-5, $newmingraph, $y+5, $black);
	imageline ($plot, $LQgraph, $y-$h, $LQgraph, $y+$h, $black);
	imageline ($plot, $medgraph, $y-$h, $medgraph, $y+$h, $black);
	imageline ($plot, $UQgraph, $y-$h, $UQgraph, $y+$h, $black);
	imageline ($plot, $newmaxgraph, $y-5, $newmaxgraph, $y+5, $black);
	imageline ($plot, $newmingraph, $y, $LQgraph, $y, $black);
	imageline ($plot, $LQgraph, $y+$h, $UQgraph, $y+$h, $black);
	imageline ($plot, $LQgraph, $y-$h, $UQgraph, $y-$h, $black);
	imageline ($plot, $UQgraph, $y, $newmaxgraph, $y, $black);
}

if($interval=="yes"){
	imagesetthickness ($plot,8);
	imageline ($plot, $intmingraph, $y, $intmaxgraph, $y, $blue);
	imagesetthickness ($plot,1);
}

if($intervallim=="yes"){
	$intmin=format_number_significant_figures($intmin,5);
	$bbox = imagettfbbox(8, 0, $reg, $intmin);
	$w = $bbox[4]-$bbox[0];
	imagettftext($plot, 8, 0, $intmingraph-$w, $y+14, $blue, $reg, $intmin);

	$intmax=format_number_significant_figures($intmax,5);
	$bbox = imagettfbbox(8, 0, $reg, $intmax);
	$w = $bbox[4]-$bbox[0];
	imagettftext($plot, 8, 0, $intmaxgraph, $y+14, $blue, $reg, $intmax);
}

// copy the temp image back to the subset image
imagecopy ($sub, $plot, 0, $offset, 0, 0, $subwidth, $imheight);
// destroy temp images, clear memory
imagedestroy($plot);

$offset=$offset+$imheight;

}

// copy the temp image back to the real image
imagecopy ($im, $sub, 50+$offsetsub, 50, 0, 0, $subwidth, $subheight);
// destroy temp images, clear memory
imagedestroy($sub);

$offsetsub=$offsetsub+$subwidth;
}
include('labelgraph.php');

if(strlen($pointsremoved)>22){
	$bbox = imagettfbbox(8, 0, $reg, $pointsremoved);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, 8, 0, $width-$w, 12, $black, $reg, $pointsremoved);
}

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
