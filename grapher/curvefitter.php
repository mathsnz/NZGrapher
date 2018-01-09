<?php
//points
$xpoints=explode(',', $_POST['xvals']);
$ypoints=explode(',', $_POST['yvals']);
array_pop($xpoints);
array_pop($ypoints);
$type = $_POST['type'];

if($type=="regression") {
	require_once( './PolynomialRegression/PolynomialRegression/PolynomialRegression.php' );

	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$PolynomialRegression = new PolynomialRegression( 2 );

	// Add all the data to the regression analysis.
	$i=0;
	$sumxx=0;$sumxy=0;$sumyy=0;
	foreach ($xpoints as $x){
		$PolynomialRegression->addData( $x, $ypoints[$i] );
		$sumxx+=$x*$x;
		$sumxy+=$x*$ypoints[$i];
		$sumyy+=$ypoints[$i]*$ypoints[$i];
		$i++;
	}
	$sumx=array_sum($xpoints);
	$sumy=array_sum($ypoints);

	// Get coefficients for the polynomial.
	$coefficients = $PolynomialRegression->getCoefficients();

	$c=$coefficients[ 0 ];
	$m=$coefficients[ 1 ];
	
	echo json_encode(array('c'=>$c,'m'=>$m));
}

if($type=="quadratic") {
	require_once( './PolynomialRegression/PolynomialRegression/PolynomialRegression.php' );
  if(count($xpoints)<3){
	die(json_encode(array('a'=>0,'b'=>0,'c'=>0)));
  }
	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$PolynomialRegression = new PolynomialRegression( 3 );

	// Add all the data to the regression analysis.
	$i=0;
	foreach ($xpoints as $x){
		$PolynomialRegression->addData( $x, $ypoints[$i] );
		$i++;
	}

	// Get coefficients for the polynomial.
	$coefficients = $PolynomialRegression->getCoefficients();

	$a=$coefficients[ 0 ];
	$b=$coefficients[ 1 ];
	$c=$coefficients[ 2 ];

	echo json_encode(array('a'=>$a,'b'=>$b,'c'=>$c));
}

if($type=="cubic") {
	require_once( './PolynomialRegression/PolynomialRegression/PolynomialRegression.php' );
  if(count($xpoints)<3){
    die('Not enough points for a cubic. Please deselected it.');
  }
	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$PolynomialRegression = new PolynomialRegression( 4 );

	// Add all the data to the regression analysis.
	$i=0;
	foreach ($xpoints as $x){
		$PolynomialRegression->addData( $x, $ypoints[$i] );
		$i++;
	}

	// Get coefficients for the polynomial.
	$coefficients = $PolynomialRegression->getCoefficients();

	$a=$coefficients[ 0 ];
	$b=$coefficients[ 1 ];
	$c=$coefficients[ 2 ];
	$d=$coefficients[ 3 ];
	
	echo json_encode(array('a'=>$a,'b'=>$b,'c'=>$c,'d'=>$d));
}

if($type=="exp") {
	require_once( './PolynomialRegression/LinearizedRegression/ExpRegression.php' );

	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$regression = new ExpRegression();

	// Add all the data to the regression analysis.
	$i=0;
	foreach ($xpoints as $x){
		$regression->addData( $x, $ypoints[$i] );
		$i++;
	}

	// Get coefficients for the polynomial.
	$coefficients = $regression->getCoefficients();

	$a=$coefficients[ 0 ];
	$b=$coefficients[ 1 ];
	
	echo json_encode(array('a'=>$a,'b'=>$b));
}

if($type=="log") {
	require_once( './PolynomialRegression/LinearizedRegression/LogRegression.php' );

	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$regression = new LogRegression();

	// Add all the data to the regression analysis.
	$i=0;
	foreach ($xpoints as $x){
		$regression->addData( $x, $ypoints[$i] );
		$i++;
	}

	// Get coefficients for the polynomial.
	$coefficients = $regression->getCoefficients();

	$a=$coefficients[ 1 ];
	$b=$coefficients[ 0 ];

	echo json_encode(array('a'=>$a,'b'=>$b));
}

if($type=="pow") {
	require_once( './PolynomialRegression/LinearizedRegression/PowRegression.php' );

	// Precision digits in BC math.
	bcscale( 10 );

	// Start a regression class of order 2--linear regression.
	$regression = new PowRegression();

	// Add all the data to the regression analysis.
	$i=0;
	foreach ($xpoints as $x){
		$regression->addData( $x, $ypoints[$i] );
		$i++;
	}

	// Get coefficients for the polynomial.
	$coefficients = $regression->getCoefficients();

	$a=$coefficients[ 0 ];
	$b=$coefficients[ 1 ];

	echo json_encode(array('a'=>$a,'b'=>$b));
}