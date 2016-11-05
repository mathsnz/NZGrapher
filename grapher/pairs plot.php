<?php
$map="<map name=graphmap>";
//data
$titles=$_POST["titles"];
$datain=$_POST["datain"];;
$datain=explode("@#$",$datain);
$titles=explode(",",$titles);
array_pop($datain);
array_pop($titles);
$data=array();
$i=0;
foreach($datain as $key => $datapoints){
	$data[$titles[$i]]=explode(",",$datapoints);
	array_pop($data[$titles[$i]]);
	$i++;
}

//functions for median and quartiles
function array_firstQuartile($values){
 $count = count($values);
 sort($values);
 $n=floor($count/2)/2-0.5;
 if($n<0){$n=0;}
 if(ceil($n) == $n) return ($values[$n]);
 else return (($values[floor($n)]+$values[ceil($n)])/2);
}

function array_thirdQuartile($values){
 $count = count($values);
 rsort($values);
 $n=floor($count/2)/2-0.5;
 if($n<0){$n=0;}
 if(ceil($n) == $n) return ($values[$n]);
 else return (($values[floor($n)]+$values[ceil($n)])/2);
}

function array_median($Array) {
  rsort($Array);
  $pos = (count($Array) - 1) * 0.5;

  $base = floor($pos);
  $rest = $pos - $base;

  if( isset($Array[$base+1]) ) {
    return $Array[$base] + $rest * ($Array[$base+1] - $Array[$base]);
  } else {
    return $Array[$base];
  }
}

//dimension
$width=$_POST['width'];
$height=$_POST['height'];


// Create image and define colours
if($width<1 || $height<1){echo "Invalid Image Dimensions";die();}
$im = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$fill = imagecolorallocatealpha($im, 0, 100, 200, 100);
$lightgrey = imagecolorallocate($im, 230, 230, 230);
$grey = imagecolorallocatealpha($im, 0, 0, 0, 90);
$red = imagecolorallocate($im, 255, 0, 0);
$green = imagecolorallocate($im, 0, 255, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$reg = './Roboto-Regular.ttf';
$bold = './Roboto-Bold.ttf';

$height-=10;

//make image white
imagefill ($im, 0, 0, $white);

$vars=count($data);
if($vars==0) {die('no variables');}
$plotwidth=($width-10)/$vars-10;
$plotheight=($height-10)/$vars-10;
$left=10;
$top=10;

$i=0;

foreach ($data as $key => $datapoints){
	//title for each variable
	$title=$key;
	$bbox = imagettfbbox(10, 0, $reg, $title);
	$w = $bbox[4]-$bbox[0];
	imagettftext($im, 10, 0, $left+$plotwidth/2-$w/2, $top+15, $black, $reg, $title);
	imagerectangle($im,$left,$top,$left+$plotwidth,$top+$plotheight,$black);

	$x1=$left;
	$y1=$top;
	$x2=$x1+$plotwidth;
	$y2=$y1+$plotheight;

	//histograms for numerical data
	if(is_numeric($datapoints[0])){
		$map.="<area shape='rect' coords='$x1,$y1,$x2,$y2' href=\"javascript:window.parent.document.getElementById('xvar').selectedIndex=$i+1;window.parent.document.getElementById('yvar').selectedIndex=0;window.parent.document.getElementById('type').value='histogram';window.parent.document.getElementById('xaxis').value=window.parent.document.getElementById('xvar').options[window.parent.document.getElementById('xvar').selectedIndex].text;window.parent.document.getElementById('yaxis').value=window.parent.document.getElementById('yvar').options[window.parent.document.getElementById('yvar').selectedIndex].text;parent.graphchange(window.parent.document.getElementById('type'));parent.updategraph();\" alt='$x1 - $y1'>";
		$min=min($datapoints);
		$max=max($datapoints);
		$range=$max-$min;
		$point1=$range/5+$min;
		$point2=$range/5*2+$min;
		$point3=$range/5*3+$min;
		$point4=$range/5*4+$min;

		$sec1=0;
		$sec2=0;
		$sec3=0;
		$sec4=0;
		$sec5=0;

		foreach($datapoints as $point){
			if($point<$point1){$sec1++;}
			else if ($point<$point2){$sec2++;}
			else if ($point<$point3){$sec3++;}
			else if ($point<$point4){$sec4++;}
			else {$sec5++;}
		}
		$max=max($sec1,$sec2,$sec3,$sec4,$sec5);
		imagefilledrectangle($im,$left,$top+$plotheight*(1-$sec1/$max*0.5),$left+$plotwidth/5,$top+$plotheight,$fill);
		imagefilledrectangle($im,$left+$plotwidth/5,$top+$plotheight*(1-$sec2/$max*0.5),$left+$plotwidth/5*2,$top+$plotheight,$fill);
		imagefilledrectangle($im,$left+$plotwidth/5*2,$top+$plotheight*(1-$sec3/$max*0.5),$left+$plotwidth/5*3,$top+$plotheight,$fill);
		imagefilledrectangle($im,$left+$plotwidth/5*3,$top+$plotheight*(1-$sec4/$max*0.5),$left+$plotwidth/5*4,$top+$plotheight,$fill);
		imagefilledrectangle($im,$left+$plotwidth/5*4,$top+$plotheight*(1-$sec5/$max*0.5),$left+$plotwidth,$top+$plotheight,$fill);
		imagerectangle($im,$left,$top+$plotheight*(1-$sec1/$max*0.5),$left+$plotwidth/5,$top+$plotheight,$black);
		imagerectangle($im,$left+$plotwidth/5,$top+$plotheight*(1-$sec2/$max*0.5),$left+$plotwidth/5*2,$top+$plotheight,$black);
		imagerectangle($im,$left+$plotwidth/5*2,$top+$plotheight*(1-$sec3/$max*0.5),$left+$plotwidth/5*3,$top+$plotheight,$black);
		imagerectangle($im,$left+$plotwidth/5*3,$top+$plotheight*(1-$sec4/$max*0.5),$left+$plotwidth/5*4,$top+$plotheight,$black);
		imagerectangle($im,$left+$plotwidth/5*4,$top+$plotheight*(1-$sec5/$max*0.5),$left+$plotwidth,$top+$plotheight,$black);
	} else {
		//bar graph for non-numerical data;
		$map.="<area shape='rect' coords='$x1,$y1,$x2,$y2' href=\"javascript:window.parent.document.getElementById('xvar').selectedIndex=$i+1;window.parent.document.getElementById('yvar').selectedIndex=0;window.parent.document.getElementById('type').value='bar and area graph';window.parent.document.getElementById('xaxis').value=window.parent.document.getElementById('xvar').options[window.parent.document.getElementById('xvar').selectedIndex].text;window.parent.document.getElementById('yaxis').value=window.parent.document.getElementById('yvar').options[window.parent.document.getElementById('yvar').selectedIndex].text;parent.graphchange(window.parent.document.getElementById('type'));parent.updategraph();\" alt='$x1 - $y1'>";
		$vals=array_count_values($datapoints);
		$num=count($vals);
		if($num==0){$num=1;}
		$max=max($vals);
		$w=($plotwidth-6)/$num;
		$x=3;
		foreach($vals as $key => $d){
			imagefilledrectangle($im,$left+$x+3,$top+$plotheight*(1-$d/$max*0.5),$left+$x-3+$w,$top+$plotheight,$fill);
			imagerectangle($im,$left+$x+3,$top+$plotheight*(1-$d/$max*0.5),$left+$x-3+$w,$top+$plotheight,$black);
			$x=$x+$w;
		}
	}

	$i++;

	//new starting positions
	$left=$left+$plotwidth+10;
	$top=$top+$plotheight+10;
}

$x=0;

foreach($data as $xpoints){
	$y=0;
	if(is_numeric($xpoints[0])){
		$minx=min($xpoints);
		$maxx=max($xpoints);
		if($minx==$maxx){$minx=$minx-1;$maxx=$maxx+1;}
		$rangex=$maxx-$minx;
		if($rangex==0){$rangex=1;}
		foreach($data as $ypoints){
			$x1=$x*($plotwidth+10)+10;
			$y1=$y*($plotheight+10)+10;
			$x2=$x1+$plotwidth;
			$y2=$y1+$plotheight;
			if(is_numeric($ypoints[0])){
				if($x!=$y){
					$map.="<area shape='rect' coords='$x1,$y1,$x2,$y2' href=\"javascript:window.parent.document.getElementById('xvar').selectedIndex=$x+1;window.parent.document.getElementById('yvar').selectedIndex=$y+1;window.parent.document.getElementById('type').value='scatter';window.parent.document.getElementById('xaxis').value=window.parent.document.getElementById('xvar').options[window.parent.document.getElementById('xvar').selectedIndex].text;window.parent.document.getElementById('yaxis').value=window.parent.document.getElementById('yvar').options[window.parent.document.getElementById('yvar').selectedIndex].text;parent.graphchange(window.parent.document.getElementById('type'));parent.updategraph();\" alt='$x1 - $y1'>";
					imagerectangle($im,$x1,$y1,$x2,$y2,$black);
					$miny=min($ypoints);
					$maxy=max($ypoints);
					$rangey=$maxy-$miny;
					if($rangey==0){$rangey=1;}
					$i=0;
					foreach($xpoints as $xpoint){
						$ypoint=$ypoints[$i];
						$xpixel=$x1+5+($plotwidth-10)*($xpoint-$minx)/$rangex;
						$ypixel=$y2-5-($plotheight-10)*($ypoint-$miny)/$rangey;
						$i++;
						imageellipse($im,$xpixel,$ypixel,5,5,$grey);
						imageellipse($im,$xpixel,$ypixel,3,3,$grey);
					}
				}
			} else {
				// numeric / non-numeric
				if($x!=$y){
					$map.="<area shape='rect' coords='$x1,$y1,$x2,$y2' href=\"javascript:window.parent.document.getElementById('xvar').selectedIndex=$x+1;window.parent.document.getElementById('yvar').selectedIndex=$y+1;window.parent.document.getElementById('type').value='newdotplot';window.parent.document.getElementById('xaxis').value=window.parent.document.getElementById('xvar').options[window.parent.document.getElementById('xvar').selectedIndex].text;window.parent.document.getElementById('yaxis').value=window.parent.document.getElementById('yvar').options[window.parent.document.getElementById('yvar').selectedIndex].text;parent.graphchange(window.parent.document.getElementById('type'));parent.updategraph();\" alt='$x1 - $y1'>";
					imagerectangle($im,$x1,$y1,$x2,$y2,$black);
					$catsy=array_unique($ypoints);
					$numy=count($catsy);
					$subsets=array();
					foreach($catsy as $caty){
						$subsets[$caty]=array();
					}
					$i=0;
					foreach($xpoints as $xpoint){
						$ypoint=$ypoints[$i];
						$i++;
						array_push($subsets[$ypoint],$xpoint);
					}
					$ydelta=$plotheight/$numy;
					$boxheight=$ydelta*0.3;
					$ya=$ydelta/2+$y1;
					foreach($catsy as $caty){
						if(array_key_exists($caty,$subsets)){
							$min=min($subsets[$caty]);
							$lq=array_firstQuartile($subsets[$caty]);
							$med=array_median($subsets[$caty]);
							$uq=array_thirdQuartile($subsets[$caty]);
							$max=max($subsets[$caty]);
							$denom=($maxx-$minx)/($plotwidth-10);
							if($denom==0){$denom=0.01;}
							$minpix=$x1+($min-$minx)/$denom+5;
							$lqpix=$x1+($lq-$minx)/$denom+5;
							$medpix=$x1+($med-$minx)/$denom+5;
							$uqpix=$x1+($uq-$minx)/$denom+5;
							$maxpix=$x1+($max-$minx)/$denom+5;
							imageline($im,$minpix,$ya+$boxheight*0.5,$minpix,$ya-$boxheight*0.5,$black);
							imageline($im,$lqpix,$ya+$boxheight,$lqpix,$ya-$boxheight,$black);
							imageline($im,$medpix,$ya+$boxheight,$medpix,$ya-$boxheight,$black);
							imageline($im,$uqpix,$ya+$boxheight,$uqpix,$ya-$boxheight,$black);
							imageline($im,$maxpix,$ya+$boxheight*0.5,$maxpix,$ya-$boxheight*0.5,$black);
							imageline($im,$minpix,$ya,$lqpix,$ya,$black);
							imageline($im,$lqpix,$ya+$boxheight,$uqpix,$ya+$boxheight,$black);
							imageline($im,$lqpix,$ya-$boxheight,$uqpix,$ya-$boxheight,$black);
							imageline($im,$uqpix,$ya,$maxpix,$ya,$black);
						}
						$ya=$ya+$ydelta;
					}
				}
			}
			$y++;
		}
	} else {
		$catsx=array_unique($xpoints);
		$numx=count($catsx);
		$vals=array_count_values($xpoints);
		$countx=count($xpoints);
		foreach($data as $ypoints){
			$x1=$x*($plotwidth+10)+10;
			$y1=$y*($plotheight+10)+10;
			$x2=$x1+$plotwidth;
			$y2=$y1+$plotheight;
			if(is_numeric($ypoints[0])){
				//numeric / non-numeric
				$miny=min($ypoints);
				$maxy=max($ypoints);
				if($miny==$maxy){$maxy=$miny+1;$miny=$miny-1;};
				if($x!=$y){
					$map.="<area shape='rect' coords='$x1,$y1,$x2,$y2' href=\"javascript:window.parent.document.getElementById('xvar').selectedIndex=$y+1;window.parent.document.getElementById('yvar').selectedIndex=$x+1;window.parent.document.getElementById('type').value='newdotplot';window.parent.document.getElementById('xaxis').value=window.parent.document.getElementById('xvar').options[window.parent.document.getElementById('xvar').selectedIndex].text;window.parent.document.getElementById('yaxis').value=window.parent.document.getElementById('yvar').options[window.parent.document.getElementById('yvar').selectedIndex].text;parent.graphchange(window.parent.document.getElementById('type'));parent.updategraph();\" alt='$x1 - $y1'>";
					imagerectangle($im,$x1,$y1,$x2,$y2,$black);
					$subsets=array();
					foreach($catsx as $catx){
						$subsets[$catx]=array();
					}
					$i=0;
					foreach($xpoints as $xpoint){
						$ypoint=$ypoints[$i];
						$i++;
						array_push($subsets[$xpoint],$ypoint);
					}
					$xdelta=$plotwidth/$numx;
					$boxwidth=$xdelta*0.3;
					$xa=$xdelta/2+$x1;
					foreach($catsx as $catx){
						if(array_key_exists($catx,$subsets)){
							$min=min($subsets[$catx]);
							$lq=array_firstQuartile($subsets[$catx]);
							$med=array_median($subsets[$catx]);
							$uq=array_thirdQuartile($subsets[$catx]);
							$max=max($subsets[$catx]);
							$denom=($maxy-$miny)/($plotheight-10);
							if($denom==0){$denom=0.01;}
							$minpix=$y2-($min-$miny)/$denom-5;
							$lqpix=$y2-($lq-$miny)/$denom-5;
							$medpix=$y2-($med-$miny)/$denom-5;
							$uqpix=$y2-($uq-$miny)/$denom-5;
							$maxpix=$y2-($max-$miny)/$denom-5;
							imageline($im,$xa-$boxwidth*0.5,$minpix,$xa+$boxwidth*0.5,$minpix,$black);
							imageline($im,$xa-$boxwidth,$lqpix,$xa+$boxwidth,$lqpix,$black);
							imageline($im,$xa-$boxwidth,$medpix,$xa+$boxwidth,$medpix,$black);
							imageline($im,$xa-$boxwidth,$uqpix,$xa+$boxwidth,$uqpix,$black);
							imageline($im,$xa-$boxwidth*0.5,$maxpix,$xa+$boxwidth*0.5,$maxpix,$black);
							imageline($im,$xa,$minpix,$xa,$lqpix,$black);
							imageline($im,$xa-$boxwidth,$lqpix,$xa-$boxwidth,$uqpix,$black);
							imageline($im,$xa+$boxwidth,$lqpix,$xa+$boxwidth,$uqpix,$black);
							imageline($im,$xa,$uqpix,$xa,$maxpix,$black);
						}
						$xa=$xa+$xdelta;
					}
				}
			} else {
				//non-numeric / non-numeric
				$catsy=array_unique($ypoints);
				if($x!=$y){
					$map.="<area shape='rect' coords='$x1,$y1,$x2,$y2' href=\"javascript:window.parent.document.getElementById('xvar').selectedIndex=$x+1;window.parent.document.getElementById('yvar').selectedIndex=$y+1;window.parent.document.getElementById('type').value='bar and area graph';window.parent.document.getElementById('xaxis').value=window.parent.document.getElementById('xvar').options[window.parent.document.getElementById('xvar').selectedIndex].text;window.parent.document.getElementById('yaxis').value=window.parent.document.getElementById('yvar').options[window.parent.document.getElementById('yvar').selectedIndex].text;parent.graphchange(window.parent.document.getElementById('type'));parent.updategraph();\" alt='$x1 - $y1'>";
					$full=array();
					foreach($catsx as $cat){
						$full[$cat]=array();
					}
					$i=0;
					foreach($xpoints as $xpoint){
						$ypoint=$ypoints[$i];
						array_push($full[$xpoint],$ypoint);
						$i++;
					}
					$xb=-1;
					sort($catsx);
					foreach($catsx as $catx){
						$percentx=$vals[$catx]/$countx;
						$xa=$xb;
						$xb=$xb+($plotwidth+2)*$percentx;
						$numy=count($full[$catx]);
						$full[$catx]=array_count_values($full[$catx]);
						sort($catsy);
						$yb=-1;
						foreach($catsy as $caty){
							$ya=$yb;
							if(array_key_exists($caty,$full[$catx])){
								$yb=$yb+($plotheight+2)*$full[$catx][$caty]/$numy;
								imagefilledrectangle($im,$x1+$xa+1,$y1+$ya+1,$x1+$xb-1,$y1+$yb-1,$lightgrey);
								imagerectangle($im,$x1+$xa+1,$y1+$ya+1,$x1+$xb-1,$y1+$yb-1,$black);
							}
						}
					}
				}
			}
			$y++;
		}
	}
	$x++;
}

$height+=10;
include('labelgraph.php');

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
// output png and save
imagepng($im,'imagetemp/pairs-'.$randomString.'.png');

echo "<img style='position:absolute;top:0px;left:0px;' src='imagetemp/pairs-".$randomString.".png' / usemap='#graphmap'>";
echo $map."</map>";
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
