<?php
function array_firstQuartile($values){
 $count = count($values);
 sort($values);
 $n=floor($count/2)/2-0.5;
 if($n<0) return Quartile($values,0.5);
 else if(ceil($n) == $n) return ($values[$n]);
 else return (($values[floor($n)]+$values[ceil($n)])/2);
}

function array_thirdQuartile($values){
 $count = count($values);
 rsort($values);
 $n=floor($count/2)/2-0.5;
 if($n<0) return Quartile($values,0.5);
 else if(ceil($n) == $n) return ($values[$n]);
 else return (($values[floor($n)]+$values[ceil($n)])/2);
}

function Quartile($Array, $Quartile) {
  rsort($Array); 
  $pos = (count($Array) - 1) * $Quartile;
 
  $base = floor($pos);
  $rest = $pos - $base;
  if(count($Array)==0){
	return "error";  
  } else if( isset($Array[$base+1]) ) {
    return $Array[$base] + $rest * ($Array[$base+1] - $Array[$base]);
  } else {
    return $Array[$base];
  }
}

// Function to calculate square of value - mean
function sd_square($x, $mean) { return pow($x - $mean,2); }

// Function to calculate standard deviation (uses sd_square)    
function sd($array) {
    // square root of sum of squares devided by N-1
	if(count($array)<2) return 0;
    else return sqrt(array_sum(array_map("sd_square", $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );
}
?>
