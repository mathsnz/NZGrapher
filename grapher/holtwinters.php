<?php

if ($multiplicative=="yes"){
	foreach($ypoints as $index => $value){
		$ypoints[$index]=log($value);
	}
}

require_once( './PolynomialRegression/PolynomialRegression/PolynomialRegression.php' ); 
// Precision digits in BC math. 
bcscale( 10 ); 

function hwinit($ypoints,$seasons){
	//create a centered moving mean for the first two years of the data.
	$mm=array();
	$i=0;
	$ioffset=floor($seasons/2);
	while($i<$seasons){
		$a1=0;
		$a=0;
		while($a<$seasons){
			$a1+=$ypoints[$a+$i];
			$a++;
		}
		$a2=0;
		$a=0;
		while($a<$seasons){
			$a2+=$ypoints[$a+$i+1];
			$a++;
		}
		$mm[$i+$ioffset+1]=($a1+$a2)/($seasons*2);
		$i++;
	}
	
	if(count($mm)>1){
		// fit a regression line to it
		// Start a regression class of order 2--linear regression. 
		$PolynomialRegression = new PolynomialRegression( 2 ); 

		// Add all the data to the regression analysis. 
		foreach ($mm as $x => $y){
			$PolynomialRegression->addData( $x, $y );
		}
		
		// Get coefficients for the polynomial. 
		$coefficients = $PolynomialRegression->getCoefficients(); 
		
		$ap=$coefficients[ 0 ]; // x-intercept is starting value
		$bp=$coefficients[ 1 ]; // gradient is the initial trend value

		//get inital seasonal effects.
		$i=0;
		$s=array();
		while ($i<$seasons){
			$s[$i-$seasons]=$ypoints[$i]-($ap+$bp*$i);
			$i++;
		}	
		
		$a=array();
		$b=array();
		$a[-1]=$ap;
		$b[-1]=$bp;
		return array($a,$b,$s);
	} else {
		// fit a regression line to the first three points
		// Start a regression class of order 2--linear regression. 
		$PolynomialRegression = new PolynomialRegression( 2 ); 

		// Add all the data to the regression analysis. 
		$i=0;
		while($i<3){
			$x=$i+1;
			$y=$ypoints[$i];
			$PolynomialRegression->addData( $x, $y );
			$i++;
		}
		
		// Get coefficients for the polynomial. 
		$coefficients = $PolynomialRegression->getCoefficients(); 
		
		$ap=$coefficients[ 0 ]; // x-intercept is starting value
		$bp=$coefficients[ 1 ]; // gradient is the initial trend value

		$a=array();
		$b=array();
		$s=array();
		$a[-1]=$ap;
		$b[-1]=$bp;
		$s[-1]=0;
		return array($a,$b,$s);
	}
}


function hwoptim($ypoints,$seasons,$alphamin,$alphamax,$betamin,$betamax,$gammamin,$gammamax){
	$split=9;
	list($a,$b,$s)=hwinit($ypoints,$seasons);
	$variables=array();
	$errors=array();
	$c=0;
	$alpha=$alphamin;
	while($alpha<$alphamax){
		$beta=$betamin;
		while($beta<$betamax){
			$gamma=$gammamin;
			while($gamma<$gammamax){
				
				$i=0;
				$error=0;
				foreach($ypoints as $ypoint){
					$a[$i]=$alpha*($ypoint-$s[$i-$seasons])+(1-$alpha)*($a[$i-1]+$b[$i-1]);
					$b[$i]=$beta*($a[$i]-$a[$i-1])+(1-$beta)*$b[$i-1];
					$s[$i]=$gamma*($ypoint-$a[$i])+(1-$gamma)*$s[$i-$seasons];
					$error+=pow($ypoint-($a[$i-1]+$b[$i-1]+$s[$i-$seasons]),2);
					$i++;
				}
				array_push($errors,$error);
				array_push($variables,array($alpha,$beta,$gamma));
				
				$c++;
				$gamma=$gamma+($gammamax-$gammamin)/$split;
			}
			$beta=$beta+($betamax-$betamin)/$split;
		}
		$alpha=$alpha+($alphamax-$alphamin)/$split;
	}
	array_multisort($errors,$variables);
	
	$alphas=array();
	$betas=array();
	$gammas=array();
	$variables=array_slice($variables,0,20);
	foreach($variables as $variable){
		list($alpha,$beta,$gamma)=$variable;
		array_push($alphas,$alpha);
		array_push($betas,$beta);
		array_push($gammas,$gamma);
	}
	$ralphamin=max(min($alphas)-($alphamax-$alphamin)/$split,0);
	$ralphamax=min(max($alphas)+($alphamax-$alphamin)/$split,1);
	$rbetamin=max(min($betas)-($betamax-$betamin)/$split,0);
	$rbetamax=min(max($betas)+($betamax-$betamin)/$split,1);
	$rgammamin=max(min($gammas)-($gammamax-$gammamin)/$split,0);
	$rgammamax=min(max($gammas)+($gammamax-$gammamin)/$split,1);
	
	//and this
	return array($ralphamin,$ralphamax,$rbetamin,$rbetamax,$rgammamin,$rgammamax,$variables[0][0],$variables[0][1],$variables[0][2]);
}

list ($alphamin,$alphamax,$betamin,$betamax,$gammamin,$gammamax,$alpha,$beta,$gamma)=hwoptim($ypoints,$seasons,0,1,0,1,0,1);
list ($alphamin,$alphamax,$betamin,$betamax,$gammamin,$gammamax,$alpha,$beta,$gamma)=hwoptim($ypoints,$seasons,max($alphamin,0),min($alphamax,1),max($betamin,0),min($betamax,1),max($gammamin,0),min($gammamax,1));
list ($alphamin,$alphamax,$betamin,$betamax,$gammamin,$gammamax,$alpha,$beta,$gamma)=hwoptim($ypoints,$seasons,max($alphamin,0),min($alphamax,1),max($betamin,0),min($betamax,1),max($gammamin,0),min($gammamax,1));
//list ($alphamin,$alphamax,$betamin,$betamax,$gammamin,$gammamax,$alpha,$beta,$gamma)=hwoptim($ypoints,$seasons,max($alphamin,0),min($alphamax,1),max($betamin,0),min($betamax,1),max($gammamin,0),min($gammamax,1));

?>