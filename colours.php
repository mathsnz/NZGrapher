<?php
if(count($colors)<=1){
	$colormethod=1;
	$col = imagecolorallocatealpha($im, 150, 150, 150, $ptrans);
	foreach ($xpoints as $key => $value){
		$colors[$key]=$col;
	}
} else if (is_numeric($colors[0])==TRUE){
	$colormethod="number";
	$min=min($colors);
	$max=max($colors);
	$end=0.8;
	$sat=0.75;
	$lum=0.6;
	if($min==$max){$min=$min-1;$max=$max+1;} 
	foreach ($colors as $key => $value){
		$n = ($value-$min)/($max-$min);
		$col = ColorHSLToRGB($n*$end,$sat,$lum);
		$col = imagecolorallocatealpha($im, $col['r'], $col['g'], $col['b'], $ptrans);
		$colors[$key]=$col;
	}
	$left=50;
	imagettftext($im, 8, 0, $left, 48, $black, $reg, "Coloured by $colorlabel: $min");
	$bbox = imagettfbbox(8, 0, $reg, "Coloured by $colorlabel: $min");
	$left = $left+$bbox[4]-$bbox[0];
	$left = $left + 5 + $psize;
	$col = ColorHSLToRGB(0*$end,$sat,$lum);
	$col = imagecolorallocatealpha($im, $col['r'], $col['g'], $col['b'], $ptrans);
	imagesetthickness($im,2); 
	imagearc ($im , $left , 44 , $psize , $psize , 0, 359.9 , $col);
	imagesetthickness($im,1); 
	$colz=0;
	while($colz<=1){
		$col = ColorHSLToRGB($colz*$end,$sat,$lum);
		$col = imagecolorallocatealpha($im, $col['r'], $col['g'], $col['b'], $ptrans);
		imagesetthickness($im,2); 
		imagearc ($im , $left , 44 , $psize , $psize , 0, 359.9 , $col);
		imagesetthickness($im,1); 
		$left = $left + $psize+2;
		$colz=$colz+0.1;
	}
	imagettftext($im, 8, 0, $left, 48, $black, $reg, $max);
	$bbox = imagettfbbox(8, 0, $reg, $max);
	$left = $left+$bbox[4]-$bbox[0];
	
} else {
	$color="cat";
	$cats=array_unique($colors);
	sort($cats);
	$min=0;
	$max=count($cats)-1;
	if($max==$min){$max++;}
	$col = imagecolorallocatealpha($im, 0, 0, 0, $ptrans);
	$end=$max/($max+1)*0.8;
	$sat=0.75;
	$lum=0.6;
	if($min==$max){$min=$min-1;$max=$max+1;}
	foreach ($colors as $key => $value){
		$value=array_search ($value,$cats);
		if($max!=$min){
			$n = ($value-$min)/($max-$min);
		} else {
			$n = 1;
		}
		$col = ColorHSLToRGB($n*$end,$sat,$lum);
		$col = imagecolorallocatealpha($im, $col['r'], $col['g'], $col['b'], $ptrans);
		$colors[$key]=$col;
	}
	$left=50;
	imagettftext($im, 8, 0, $left, 48, $black, $reg, "Coloured by $colorlabel:");
	$bbox = imagettfbbox(8, 0, $reg, "Coloured by $colorlabel:");
	$left = $left+$bbox[4]-$bbox[0];
	$left = $left + 5 + $psize;
	foreach ($cats as $key => $value){
		$n = ($key-$min)/($max-$min);
		$col = ColorHSLToRGB($n*$end,$sat,$lum);
		$col = imagecolorallocatealpha($im, $col['r'], $col['g'], $col['b'], $ptrans);
		imagesetthickness($im,2); 
		imagearc ($im , $left , 44 , $psize , $psize , 0, 359.9 , $col);
		imagesetthickness($im,1); 
		$left = $left + $psize;
		imagettftext($im, 8, 0, $left, 48, $black, $reg, $value);
		$bbox = imagettfbbox(8, 0, $reg, $value);
		$left = $left+$bbox[4]-$bbox[0];
		$left = $left + 5 + $psize;	
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