function newresiduals(){

	$('#xvar').show();
	$('#yvar').show();
	$('#labelshow').show();
	$('#greyscaleshow').show();
	$('#sizediv').show();
	$('#removedpointsshow').show();
	$('#pointsizename').html('Point Size:');
	$('#transdiv').show();
	$('#residualsforcexshow').show();
	$('#regtypeshow').show();
	$('#weightedaverageshow').show();
	
	var canvas = document.getElementById('myCanvas');
	var ctx = canvas.getContext('2d');
	
	//set size
	var width = $('#width').val();
	var height = $('#height').val();

	ctx.canvas.width = width;
	ctx.canvas.height = height;

	ctx.fillStyle = "#ffffff";
	ctx.fillRect(0, 0, canvas.width, canvas.height);

	//graph title
	ctx.fillStyle = '#000000';
	fontsize=20*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#title').val(),width/2,30*scalefactor);
	
	if($('#residualsforcex').is(":checked")){
		xtitle="Explanatory";
		residualsforcex="yes";
	} else {
		xtitle="Fitted";
		residualsforcex="no";
	}
	
	$('#xaxis').val(xtitle);
	$('#yaxis').val("Residual");
	
	//x-axis title
	ctx.fillStyle = '#000000';
	ctx.font = "bold "+15*scalefactor+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText(xtitle,width/2,height-10*scalefactor);
	
	//y-axis title
	x=20*scalefactor;
	y=height/2;
	ctx.save();
	ctx.fillStyle = '#000000';
	ctx.font = "bold "+15*scalefactor+"px Roboto";
	ctx.translate(x, y);
	ctx.rotate(-Math.PI/2);
	ctx.textAlign = "center";
	ctx.fillText("Residual", 0, 0);
	ctx.restore();
	
	//get points
	var xpoints = $('#xvar').val().split(",");
	xpoints.pop();
	var ypoints = $('#yvar').val().split(",");
	ypoints.pop();

	//check for numeric value
	var points=[];
	var pointsremoved=[];
	var pointsforminmax=[];
	var pointsforminmaxy=[];
	countx=0;
	county=0;
	for (var index in xpoints){
		if($.isNumeric(xpoints[index])){countx++;}
		if($.isNumeric(ypoints[index])){county++;}
		if($.isNumeric(xpoints[index]) && $.isNumeric(ypoints[index])){
			points.push(index);
			pointsforminmax.push(xpoints[index]);
			pointsforminmaxy.push(ypoints[index]);
		} else {
			pointsremoved.push(add(index,1));
		}
	}

	if(countx==0){
		return 'Error: You must select a numeric variable for variable 1';
	}

	if(county==0){
		return 'Error: You must select a numeric variable for variable 2';
	}

	if(pointsremoved.length!=0 && $('#removedpoints').is(":checked")){
		ctx.fillStyle = '#000000';
		ctx.font = 13*scalefactor+"px Roboto";
		ctx.textAlign="right";
		ctx.fillText("ID(s) of Points Removed: "+pointsremoved.join(", "),width-40*scalefactor,40*scalefactor);
	}

	if(points.length==0){
		return 'Error: You must select a numeric variable for variable 1';
	}
	
	pointstofit = [];
	for (var index in points){
		var index = points[index];
		var xpoint = xpoints[index];
		var ypoint = ypoints[index];
		pointstofit.push([parseFloat(xpoint),parseFloat(ypoint)]);
	}
	
	regtype = $('#regtype').val();
	console.log(regtype);
	
	fitted = [];
	
	if(regtype=="Linear"){
		
		res = regression.linear(pointstofit,{
		  precision: 7,
		});
		console.log(res);
		
		c = res.equation[1].toPrecision(5);
		m = res.equation[0].toPrecision(5);
		
		for (var index in points){
			var index = points[index];
			var xpoint = xpoints[index];
			fitted[index] = add(m*xpoint,c).toPrecision(5);
		}
	} else if (regtype=="Quadratic"){
		
		res = regression.polynomial(pointstofit,{
		  order: 2,
		  precision: 7,
		});
		console.log(res);
		
		a = res.equation[0].toPrecision(5);
		b = res.equation[1].toPrecision(5);
		c = res.equation[2].toPrecision(5);
		
		for (var index in points){
			var index = points[index];
			var xpoint = xpoints[index];
			fitted[index] = add(add(a*xpoint*xpoint,b*xpoint),c).toPrecision(5);
		}
		
	} else if (regtype=="Cubic"){
		
		res = regression.polynomial(pointstofit,{
		  order: 3,
		  precision: 10,
		});
		console.log(res);
		
		a = res.equation[0];
		b = res.equation[1];
		c = res.equation[2];
		d = res.equation[3];
		
		for (var index in points){
			var index = points[index];
			var xpoint = xpoints[index];
			fitted[index] = add(add(add(a*xpoint*xpoint*xpoint,b*xpoint*xpoint),c*xpoint),d).toPrecision(5);
		}
		
	} else if (regtype=="y=a*exp(b*x)"){
		
		res = regression.exponential(pointstofit,{
		  precision: 7,
		});
		console.log(res);
		
		a = res.equation[0].toPrecision(5);
		b = res.equation[1].toPrecision(5);
		
		for (var index in points){
			var index = points[index];
			var xpoint = xpoints[index];
			fitted[index] = (a * Math.exp(b*xpoint)).toPrecision(5);
		}
		
	} else if (regtype=="y=a*ln(x)+b"){
		
		res = regression.logarithmic(pointstofit,{
		  precision: 7,
		});
		console.log(res);
		
		a = res.equation[1].toPrecision(5);
		b = res.equation[0].toPrecision(5);
		
		for (var index in points){
			var index = points[index];
			var xpoint = xpoints[index];
			fitted[index] = add(a * Math.log(xpoint),b).toPrecision(5);
		}
		
	} else if (regtype=="y=a*x^b"){
		
		res = regression.power(pointstofit,{
		  precision: 7,
		});
		console.log(res);
		
		a = res.equation[0].toPrecision(5);
		b = res.equation[1].toPrecision(5);
		
		for (var index in points){
			var index = points[index];
			var xpoint = xpoints[index];
			fitted[index] = (a * Math.pow(xpoint,b)).toPrecision(5);
		}
		
	}
	
	residuals=[];
	var pointsforminmax=[];
	var pointsforminmaxy=[];
		
	for (var index in points){
		var index = points[index];
		var ypoint = ypoints[index];
		var fit = fitted[index];
		residuals[index] = (ypoint-fit).toPrecision(5);
		pointsforminmaxy.push(ypoint-fit);
		if(residualsforcex=="yes"){
			fitted[index] = xpoints[index];
			fit = xpoints[index];
		}
		pointsforminmax.push(fit);
	}
	
	var alpha = 1-$('#trans').val()/100;
	var colors = makecolors(alpha,ctx);

	xmin = Math.min.apply(null, pointsforminmax);
	xmax = Math.max.apply(null, pointsforminmax);
	ymin = Math.min.apply(null, pointsforminmaxy);
	ymax = Math.max.apply(null, pointsforminmaxy);
	
	var minmaxstep = axisminmaxstep(xmin,xmax);
	var minxtick=minmaxstep[0];
	var maxxtick=minmaxstep[1];
	var xstep=minmaxstep[2];
	var minmaxstep = axisminmaxstep(ymin,ymax);
	var minytick=minmaxstep[0];
	var maxytick=minmaxstep[1];
	var ystep=minmaxstep[2];

	var left=90*scalefactor;
	var right=width-60*scalefactor;
	var gtop=90*scalefactor;
	var bottom=height-60*scalefactor;
	
	plotscatter(ctx,points,fitted,residuals,minxtick,maxxtick,xstep,minytick,maxytick,ystep,gtop,bottom,left,right,colors);
	
	labelgraph(ctx,width,height);
	
	if($('#invert').is(":checked")){
		invert(ctx)
	}

	var dataURL = canvas.toDataURL();
	return dataURL;
	
}