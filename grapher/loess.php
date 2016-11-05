<?php

function loess($xpoints,$ypoints,$nPts,$xvals,$row="na"){
	$nPts=min(count($xpoints),$nPts);
	$xmin=min($xpoints);
	$xmax=max($xpoints);
	$points=array();
	$yreturn=array();
	$index=0;
	foreach ($xvals as $currentx){
		$distances=array();
		$i=0;
		foreach($xpoints as $xpoint){
			$distances[$i]=abs($xpoint-$currentx);
			$i++;
		}
		array_multisort($distances, $xpoints, $ypoints);
		$distances=array_slice($distances, 0, $nPts);
		$maxdis=max($distances);
		if($nPts<=3){$maxdis+=0.001;}
		$weights=array();
		// work out the weights
		$i=0;
		foreach($distances as $distance){
			$u=abs($distance)/$maxdis;
			$weights[$i]=pow((1-pow($u,3)),3);
			$i++;
		}
		//do the sums of squares
		$SumWts = 0;
		$SumWtX = 0;
		$SumWtX2 = 0;
		$SumWtY = 0;
		$SumWtXY = 0;
		$i=0;
		while($i<$nPts){
			$SumWts = $SumWts + $weights[$i];
			$SumWtX = $SumWtX + $xpoints[$i] * $weights[$i];
			$SumWtX2 = $SumWtX2 + pow($xpoints[$i],2)	* $weights[$i];
			$SumWtY = $SumWtY + $ypoints[$i] * $weights[$i];
			$SumWtXY = $SumWtXY + $xpoints[$i] * $ypoints[$i] * $weights[$i];
			$i++;
		}
		$Denom = $SumWts * $SumWtX2 - pow($SumWtX,2);
		if($Denom==0){die("invalid denominator (loess.php) $row");}
		//calculate the regression coefficients, and finally the loess value
		$WLRSlope = ($SumWts * $SumWtXY - $SumWtX * $SumWtY) / $Denom;
		$WLRIntercept = ($SumWtX2 * $SumWtY - $SumWtX * $SumWtXY) / $Denom;
		$yreturn[strval($currentx)] = $WLRSlope * $currentx + $WLRIntercept;
		$index++;
	}
	return $yreturn;
}

?>