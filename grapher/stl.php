<?php

if ($multiplicative=="yes"){
	foreach($ypoints as $index => $value){
		$ypoints[$index]=log($value);
	}
}

require 'loess.php';

function stl($xpoints,$ypoints,$seasons){
	$T=array();
	$raw=array();
	$i=0;
	foreach($xpoints as $xpoint){
		$raw[strval($xpoint)]=$ypoints[$i];
		$T[strval($xpoint)]=0;
		$i++;
	}
	$n_l=nextodd($seasons);//next odd number after number in trend
	$n_s=7;
	$n_t=nextodd(1.5*$seasons/(1-1.5/$n_s));
	list($T,$S)=innerloop($xpoints,$raw,$T,$n_s,$n_l,$n_t);
	list($T,$S)=innerloop($xpoints,$raw,$T,$n_s,$n_l,$n_t);
	return array($T,$S);
}

function nextodd($n){
	$n=ceil($n);
	if(floor($n/2)==$n/2){$n++;}
	return $n;
}

function movingaverage($xs,$ys,$length){
	array_multisort($xs,$ys);
	$i=ceil(($length+1)/2-1);
	$max=count($xs)-$i;
	$cmms=array();
	while($i<$max){
		$a=$i-($length-1)/2;
		$b=$i+($length-1)/2;
		if($a!=floor($a)){
			//need to center
			$slice1=array_slice($ys,$a-0.5,$length);
			$slice2=array_slice($ys,$a+0.5,$length);
			$cmm1=array_sum($slice1)/count($slice1);
			$cmm2=array_sum($slice2)/count($slice2);
			$cmm=($cmm1+$cmm2)/2;
		} else {
			//already centered
			$slice=array_slice($ys,$i-$length/2,$length);
			$cmm=array_sum($slice)/count($slice);
		}
		$cmms[strval($xs[$i])]=$cmm;
		$i++;
	}
	return $cmms;
}

function innerloop($xpoints,$raw,$T,$n_s,$n_l,$n_t){
	$detrended=array();
	foreach($xpoints as $xpoint){
		$detrended[strval($xpoint)]=$raw[strval($xpoint)]-$T[strval($xpoint)];
	}
	if($n_l==1){
		$S=array();
		foreach($xpoints as $xpoint){
			$S[strval($xpoint)]=0;
		}
		$n_t=nextodd(count($raw)/3);
	} else {
		$cyclesubseries=array();
		foreach($xpoints as $xpoint){
			$year=floor($xpoint);
			$season=round($xpoint-$year,8);
			if(!array_key_exists(strval($season),$cyclesubseries)){$cyclesubseries[strval($season)]=array();}
			$cyclesubseries[strval($season)][strval($xpoint)]=$detrended[strval($xpoint)];
		}
		foreach ($cyclesubseries as $season => $values){
			$min=min(array_keys($values));
			$forpoints=array_merge(array_keys($values),array(min(array_keys($values))-1,max(array_keys($values))+1));
			$cyclesubseries[$season]=loess(array_keys($values),array_values($values),$n_s,$forpoints,75);
		}
		$C=array();
		foreach ($cyclesubseries as $season => $values){
			foreach($values as $x => $y){
				$C[strval($x)]=$y;
			}
		}
		ksort($C);
		$L=$C;
		$L=movingaverage(array_keys($L),array_values($L),count($cyclesubseries));
		$L=movingaverage(array_keys($L),array_values($L),count($cyclesubseries));
		//$L=movingaverage(array_keys($L),array_values($L),3);
		$L=loess(array_keys($L),array_values($L),$n_l,array_keys($L),88);
		
		$S=array();
		foreach($xpoints as $xpoint){
			if(array_key_exists(strval($xpoint),$C) && array_key_exists(strval($xpoint),$L)){
				$S[strval($xpoint)]=$C[strval($xpoint)]-$L[strval($xpoint)];
			} else {
				echo "Error with Data: array key ".strval($xpoint)." doesn't exist in C or L (stl.php)";
				die();
			}
		}
		
		$S2=array();
		foreach($xpoints as $xpoint){
			$year=floor($xpoint);
			$season=$xpoint-$year;
			if(!array_key_exists(strval($season),$S2)){$S2[strval($season)]=array();}
			$S2[strval($season)][strval($xpoint)]=$S[strval($xpoint)];
		}
		$S=array();
		foreach ($S2 as $season => $values){
			$average=array_sum($values)/count($values);
			foreach($values as $x => $y){
				$S[strval($x)]=$average;
			}
		}
		ksort($S);
	}
	
	$deseasonalised=array();
	foreach($xpoints as $xpoint){
		$deseasonalised[strval($xpoint)]=$raw[strval($xpoint)]-$S[strval($xpoint)];
	}
	$T=loess(array_keys($deseasonalised),array_values($deseasonalised),$n_t,array_keys($deseasonalised),123);
	return array($T,$S);
}


list($T,$S)=stl($xpoints,$ypoints,$seasons);

	$i=0;
	$fitted=array();
	$r=array();
	$trend=array();
	$s=array();
	
if ($multiplicative=="yes"){
	foreach($ypoints as $index => $ypoint){
		$ypoints[$index]=exp($ypoint);
		$xpoint=$xpoints[$i];
		$trend[$i]=exp($T[strval($xpoint)]);
		$fitted[$i]=exp($T[strval($xpoint)]+$S[strval($xpoint)]);
		$s[$i]=$fitted[$i]-$trend[$i];
		$r[$i]=exp($ypoint)-$fitted[$i];
		$i++;
	}
} else {
	foreach($ypoints as $ypoint){
		$xpoint=$xpoints[$i];
		$trend[$i]=$T[strval($xpoint)];
		$fitted[$i]=$T[strval($xpoint)]+$S[strval($xpoint)];
		$s[$i]=$S[strval($xpoint)];
		$r[$i]=$ypoint-$fitted[$i];
		$i++;
	}
}


?>