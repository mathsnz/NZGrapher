<?php


if(is_numeric(substr($xpoints[0],0,1))==FALSE || !(is_numeric($xpoints[0]) && strlen($xpoints[0])==4) && (substr($xpoints[0],4,1)!="Q" && substr($xpoints[0],4,1)!="M" && substr($xpoints[0],4,1)!="D" && substr($xpoints[0],4,1)!="W" && substr($xpoints[0],4,1)!="H")){
	echo "<br><br><br><br><br><center>You must select a time series x-variable<br>eg: 2001 or 2001M01 or 2001Q1 or 2001D1 or 2001W1 or 2001H01</center>";
	die();
}
if(empty($ypoints)){
	echo "<br><br><br><br><br><center>You must select a numerical y-variable</center>";
	die();
}
if(is_numeric($ypoints[0])==FALSE){
	echo "<br><br><br><br><br><center>You must select a numerical y-variable</center>";
	die();
}


function checkseasons($xpoints){
	if(strlen($xpoints[0])==4){
		$seasons=1;
	}
	if(substr($xpoints[0],4,1)=="Q"){
		$seasons=4;
	}
	if(substr($xpoints[0],4,1)=="M"){
		$seasons=12;
	}
	if(substr($xpoints[0],4,1)=="D"){
		$seasons=7;
	}
	if(substr($xpoints[0],4,1)=="W"){
		$seasons=5;
	}
	if(substr($xpoints[0],4,1)=="H"){
		$seasons=24;
	}
	return $seasons;
}

function tsxpoints($xpoints, $seasons){
	$tsxpoints=array();
	foreach($xpoints as $xpoint){
		$year=substr($xpoint,0,4);
		$season=substr($xpoint,5);
		if(empty($season)){$season=1;}
		$newxpoint=round($year+($season-1)/$seasons,8);
		array_push($tsxpoints,$newxpoint);
	}
	return $tsxpoints;
}