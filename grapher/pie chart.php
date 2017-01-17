<?php
//regresion?
$regression=$_POST['regression'];
//box plots?
$boxplot=$_POST['boxplot'];
//informal confidence interval?
$interval=$_POST['interval'];
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

//dimension
$width=$_POST['width'];
$height=$_POST['height'];

array_pop($xpoints);
array_pop($ypoints);
if(empty($xpoints)){
	echo "<br><br><br><br><br><center>You must select an x-variable for a pie chart<br>
	or<br>
	an x-variable and a y-variable for a series of pie charts.</center>";
	die();
}
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

// Create image and define colours
if($width<1 || $height<1){echo "Invalid Image Dimensions";die();}
$im = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$grey = imagecolorallocate($im, 125, 125, 125);
$lightgrey = imagecolorallocate($im, 230, 230, 230);
$red = imagecolorallocate($im, 255, 0, 0);
$green = imagecolorallocate($im, 0, 255, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$reg = './Roboto-Regular.ttf';
$bold = './Roboto-Bold.ttf';
$con = './RobotoCondensed-Regular.ttf';

//make image white
imagefill ($im, 0, 0, $white);

//graph title
$bbox = imagettfbbox(12, 0, $bold, $title);
$w = $bbox[4]-$bbox[0];
imagettftext($im, 14, 0, $width/2-$w/2, 30, $black, $bold, $title);

if(empty($ypoints)){
	$centerx=$width/2;
	$centery=$height/2;
	$diameter=min($height-150,$width-150);
	pie($im,$xpoints,$diameter,$centerx,$centery);
	if($regression=="yes"){
		$size=11;
		$num="num: ".count($xpoints);
		$bbox = imagettfbbox($size, 0, $bold, $num);
		$w = $bbox[4]-$bbox[0];
		imagettftext($im,$size,0,$centerx-$w/2,$centery+$diameter/2+30,$black,$bold,$num);
	}

} else {
	$catsy=array_unique($ypoints);
	$catsx=array_unique($xpoints);
	$numx=count($catsx);
	$vals=array_count_values($xpoints);
	$countx=count($xpoints);
	$full=array();
	foreach($catsy as $cat){
		$full[$cat]=array();
	}
	$i=0;
	foreach($xpoints as $xpoint){
		$ypoint=$ypoints[$i];
		array_push($full[$ypoint],$xpoint);
		$i++;
	}
	$xb=50;
	ksort($full);
	$numgraphs=count($catsy);
	if($numgraphs<=3){$numwidth=$numgraphs;}
	else {$numwidth=ceil(sqrt($numgraphs));}
	$numheight=ceil($numgraphs/$numwidth);
	$graphwidth=round(($width-50)/$numwidth);
	$graphheight=round(($height-80)/$numheight);
	$left=25;
	$top=50;
	foreach($full as $cat => $points){
		if($left>$width-30){
			$left=25;
			$top=$top+$graphheight;
		}
		$centerx=$graphwidth/2+$left;
		$centery=$graphheight/2+$top-20;
		$diameter=min($graphheight-50,$graphwidth-10);
		pie($im,$points,$diameter,$centerx,$centery);
		$size=11;
		if($regression=="yes"){
			$cat.=" (num: ".count($points).")";
		}
		$bbox = imagettfbbox($size, 0, $bold, $cat);
		$w = $bbox[4]-$bbox[0];
		imagettftext($im,$size,0,$centerx-$w/2,$centery+$diameter/2+30,$black,$bold,$cat);
		$left+=$graphwidth;
	}
}

function makelighter($v){
	$v=$v+(255-$v)*0.2;
	return $v;
}

function stringToColorCode($im,$str) {
  $code = dechex(crc32($str));
  $hex = substr($code, 0, 6);
  $r = makelighter(hexdec(substr($hex,0,2)));
  $g = makelighter(hexdec(substr($hex,2,2)));
  $b = makelighter(hexdec(substr($hex,4,2)));
  $col=imagecolorallocatealpha($im,$r,$g,$b,0);
  return $col;
}

function pie($im,$xpoints,$diameter,$centerx,$centery){
	$black=imagecolorallocate($im,0,0,0);
	$reg = './Roboto-Regular.ttf';
	$freq=array_count_values($xpoints);
	arsort($freq);
	$num=count($freq);
	$size=11;
	$total=array_sum($freq);
	$rot=-90;
	foreach($freq as $key => $value){
		$angle=$value/$total*360;
		$col=stringToColorCode($im,$key);
		$start=round($rot);
		$end=round($rot+$angle);
		echo "$start - $end - $key<br>";
		imagefilledarc($im,$centerx,$centery,$diameter,$diameter,$start,630,$col,0);
		$rot=$rot+$angle;
	}
	$rot=270;
	foreach($freq as $key => $value){
		$angle=$value/$total*360;
		$start=round($rot);
		$end=round($rot+$angle);
		$pix_x=round($diameter/2*cos(deg2rad($start))+$centerx);
		$pix_y=round($diameter/2*sin(deg2rad($start))+$centery);
		imageline($im, $centerx, $centery, $pix_x, $pix_y, $black);
		$rot=$rot+$angle;
	}
	$rot=270;
	foreach($freq as $key => $value){
		$angle=$value/$total*360;
		$start=round($rot);
		$end=round($rot+$angle);
		$half=($start+$end)/2;
		$pix_x=round($diameter*0.4*cos(deg2rad($half))+$centerx);
		$pix_y=round($diameter*0.4*sin(deg2rad($half))+$centery);
		$bbox = imagettfbbox($size, 0, $reg, $key);
		$w = $bbox[4]-$bbox[0];
		imagettftext($im, $size, 0, $pix_x-$w/2, $pix_y+5, $black, $reg, $key);
		$rot=$rot+$angle;
	}
	imageellipse($im,$centerx,$centery,$diameter,$diameter,$black);
}
include('labelgraph.php');

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/Pie-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/Pie-".$randomString.".png' />";

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
