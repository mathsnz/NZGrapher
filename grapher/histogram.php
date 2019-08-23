<?php
//regresion?
$regression=$_POST['regression'];
//box plots?
$boxplot=$_POST['boxplot'];
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
//relative frequency
$rf=$_POST['relativefrequency'];
//points
if(isset($_POST['xvals'])){$xpoints=explode(',', $_POST['xvals']);} else {$xpoints=array("");}
if(isset($_POST['yvals'])){$ypoints=explode(',', $_POST['yvals']);} else {$ypoints=array("");}
$psize=$_POST['size'];
$ptrans=$_POST['trans'];

//dimension
$width=$_POST['width'];
$height=$_POST['height'];

array_pop($xpoints);
array_pop($ypoints);

$i=0;
$pointsremoved="id of Points Removed: ";
foreach($xpoints as $xpoint){
	if(!is_numeric($xpoint)){
		unset($xpoints[$i]);
		unset($ypoints[$i]);
		$pointsremoved.=($i+1).", ";
	}
	$i++;
}
$xpoints = array_values($xpoints);
$ypoints = array_values($ypoints);

if(empty($xpoints)){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}
if(is_numeric($xpoints[0])==FALSE){
	echo "<br><br><br><br><br><center>You must select a numerical x-variable</center>";
	die();
}

$numcategories=count(array_unique($ypoints));
if($numcategories>5 && is_numeric($ypoints[0])==TRUE){
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
if($numcategories>5){
	echo "<br><br><br><br><br><center>You must select a categorical y-variable with 4 or fewer categories or a numerical y-variable which will automatically be split into 4 categories.</center>";
	die();
}
if($numcategories==0){
	$numcategories=1;
	foreach($xpoints as $key => $value){
		$ypoints[$key]=" ";
	}
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
$lightgrey = imagecolorallocate($im, 230, 230, 230);
$reg = __DIR__.'/Roboto-Regular.ttf';
$bold = __DIR__.'/Roboto-Bold.ttf';

//make image white
imagefill ($im, 0, 0, $white);

//graph title
$bbox = imagettfbbox(14, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 14, 0, $width/2-$w/2, 30, $black, $bold, $title);


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

if($psize==3){
	if($steps<2) {
		$steps=$steps*50;
	}
	if($steps<3) {
		$steps=$steps*20;
	}
	if($steps<5) {
		$steps=$steps*10;
	}
} else if ($psize==5){
	if($steps<2) {
		$steps=$steps*20;
	}
	if($steps<3) {
		$steps=$steps*10;
	}
	if($steps<5) {
		$steps=$steps*5;
	}
} else if ($psize==7){
	if($steps<2) {
		$steps=$steps*10;
	}
	if($steps<3) {
		$steps=$steps*5;
	}
	if($steps<5) {
		$steps=$steps*2;
	}
} else if ($psize==9){
	if($steps<2) {
		$steps=$steps*5;
	}
	if($steps<3) {
		$steps=$steps*2;
	}
	if($steps<5) {
		$steps=$steps*1;
	}
} else if ($psize==11){
	if($steps<2) {
		$steps=$steps*2;
	}
	if($steps<3) {
		$steps=$steps*1;
	}
	if($steps<5) {
		$steps=ceil($steps*0.5);
	}
} else if ($psize==13){
	if($steps<2) {
		$steps=$steps*2;
	}
	if($steps<3) {
		$steps=$steps*1;
	}
	if($steps<5) {
		$steps=ceil($steps*0.5);
	}
} else if ($psize==15){
	$steps=2;
} else if ($psize==17){
	$steps=1;
} else if ($psize==19){
	$steps=1;
} else {
	if($steps<2) {
		$steps=$steps*10;
	}
	if($steps<3) {
		$steps=$steps*5;
	}
	if($steps<5) {
		$steps=$steps*2;
	}
}
$step=$rangeround/$steps;
$minxtick=round($minx/$step)*$step;
if($minxtick>$minx){
	$minxtick=$minxtick-$step;
}
$maxxtick=round($maxx/$step)*$step;
if($maxxtick<=$maxx){
	$maxxtick=$maxxtick+$step;
}
$steps=($maxxtick-$minxtick)/$step;
//Temp image for x-axis
$axisheight=30;
$axiswidth=$width-100;
// define temp image
$xaxis = imagecreatetruecolor($axiswidth+20, $axisheight);
imagefill ($xaxis, 0, 0, $white);

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

//xaxis line
imageline ($xaxis, 50, 0, $axiswidth, 0, $black);

//min x mark
$bbox = imagettfbbox($size, 0, $reg, $minxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($xaxis, $size, 0, 70-$w/2, 20, $black, $reg, $minxtick);
imageline ($xaxis, 70, 0, 70, 5, $black);
//max x mark
$bbox = imagettfbbox($size, 0, $reg, $maxxtick);
$w = $bbox[4]-$bbox[0];
imagettftext($xaxis, $size, 0, $axiswidth-10-$w/2, 20, $black, $reg, $maxxtick);
imageline ($xaxis, $axiswidth-10, 0, $axiswidth-10, 5, $black);

//other ticks
$i=1;
$distanceright=70;
$xtick=$minxtick;
while($i<$steps){
	$distanceright=$distanceright+($axiswidth-80)/$steps;
	$xtick=$xtick+$step;
	$bbox = imagettfbbox($size, 0, $reg, $xtick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($xaxis, $size, 0, $distanceright-$w/2, 20, $black, $reg, $xtick);
	imageline ($xaxis, $distanceright, 0, $distanceright, 5, $black);
	$i++;
}

$i=0;
$data=array();
$odata=array();
foreach($xpoints as $xpoint){
	$ypoint=$ypoints[$i];
	$xbucket=floor(strval($xpoint/$step))*$step;
	if(!array_key_exists($ypoint,$data)){$data[$ypoint]=array();}
	if(!array_key_exists($ypoint,$odata)){$odata[$ypoint]=array();}
	array_push($data[$ypoint],strval($xbucket));
	array_push($odata[$ypoint],strval($xpoint));
	$i++;
}
$maxfreq=array();
$xstep=$step;
foreach($data as $category => $values){
	$data[$category]=array_count_values($values);
	ksort($data[$category]);
	$sum=1;
	if($rf==1){
		$sum = array_sum($data[$category]);
	}
	array_push($maxfreq,max($data[$category])/$sum);
}
$maxfreq=max($maxfreq);
$numcategories=count($data);

//y axis ticks
$miny=0.0001;
$maxy=$maxfreq;
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

//Temp image for y-axis
$yaxisheight=($height-80)/$numcategories;
$yaxiswidth=50;
// define temp image
$yaxis = imagecreatetruecolor($yaxiswidth, $yaxisheight);
imagefill ($yaxis, 0, 0, $white);

//xaxis line
imageline ($yaxis, 49, 10, 49, $yaxisheight-26, $black);

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
imagettftext($yaxis, $size, 0, 43-$w, $yaxisheight-21, $black, $reg, $minytick);
imageline ($yaxis, 45, $yaxisheight-26, 50, $yaxisheight-26, $black);
//max y mark
$bbox = imagettfbbox($size, 0, $reg, $maxytick);
$w = $bbox[4]-$bbox[0];
imagettftext($yaxis, $size, 0, 43-$w, 15, $black, $reg, $maxytick);
imageline ($yaxis, 45, 10, 50, 10, $black);

//other ticks
$i=1;
$distancedown=$yaxisheight-26;
$ytick=$minytick;
while($i<$steps){
	$distancedown=$distancedown-($yaxisheight-36)/$steps;
	$ytick=$ytick+$step;
	$bbox = imagettfbbox($size, 0, $reg, $ytick);
	$w = $bbox[4]-$bbox[0];
	imagettftext($yaxis, $size, 0, 43-$w, $distancedown+5, $black, $reg, $ytick);
	imageline ($yaxis, 45, $distancedown, 50, $distancedown, $black);
	$i++;
}

//yaxis label
$bbox = imagettfbbox($size, 0, $bold, "Frequency");
$w = $bbox[4]-$bbox[0];
imagettftext($yaxis,$size,90,15,$yaxisheight/2+$w/2,$black,$bold,"Frequency");


include 'meanmedsd.php';

$offset=50;
ksort($data);

foreach($data as $category => $values){

$i=0;

//TEMP IMAGE FOR THE CATEGORY
$imheight=($height-80)/$numcategories;
$imwidth=($width-50);
// define temp image
if($imwidth<1 || $imheight<1){echo "Invalid Image Dimensions";die();}
$plot = imagecreatetruecolor($imwidth, $imheight);
imagefill ($plot, 0, 0, $white);
$bbox = imagettfbbox(10, 0, $bold, $category);
$w = $bbox[4]-$bbox[0];
imagettftext($plot, 10, 0, $imwidth-$w-1, $imheight*0.4, $black, $bold, $category);

imagecopy ($plot, $xaxis, 0, $imheight-26, 0, 0, $axiswidth+20, $axisheight);
imagecopy ($plot, $yaxis, 0, 0, 0, 0, $yaxiswidth, $yaxisheight);

$xpoints=$odata[strval($category)];
$med=Quartile($xpoints,0.5);
$mean=format_number_significant_figures(array_sum($xpoints)/count($xpoints),5);
$num=count($xpoints);
$sd=format_number_significant_figures(sd($xpoints),5);

foreach ($values as $xbucket => $freq){
	$x1=($axiswidth-80)*($xbucket-$minxtick)/($maxxtick-$minxtick)+70;
	$y1=$yaxisheight-26;
	$x2=($axiswidth-80)*($xbucket+$xstep-$minxtick)/($maxxtick-$minxtick)+70;
	$div=1;
	if($rf==1){$div=$num;}
	$y2=$yaxisheight-26-($freq/$maxytick)*($yaxisheight-36)/$div;
	imagefilledrectangle($plot,$x1,$y1,$x2,$y2,$lightgrey);
	imagerectangle($plot,$x1,$y1,$x2,$y2,$black);
}



$top=10;
if($regression=="yes") {
	imagettftext($plot, 8, 0, 60, $top, $red, $reg, "med:  $med");
	imagettftext($plot, 8, 0, 60, $top+10, $red, $reg, "mean:  $mean");
	imagettftext($plot, 8, 0, 60, $top+20, $red, $reg, "num:  $num");
	imagettftext($plot, 8, 0, 60, $top+30, $red, $reg, "sd:  $sd");
}

// copy the temp image back to the subset image
imagecopy ($im, $plot, 30, $offset, 0, 0, $imwidth, $imheight);
// destroy temp images, clear memory
imagedestroy($plot);

$offset=$offset+$imheight;

}
// destroy temp images, clear memory
imagedestroy($xaxis);

include('labelgraph.php');

if(strlen($pointsremoved)>22){
	$bbox = imagettfbbox(8, 0, $reg, $pointsremoved);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, 8, 0, $width-$w, 12, $black, $reg, $pointsremoved);
}

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/Histogram-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/Histogram-".$randomString.".png' />";
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
