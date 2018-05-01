function newtimeseriesseasonaleffects(){
	$('#labelshow').show();
	$('#addmultshow').show();
	$('#xvar').show();
	$('#yvar').show();

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
	
	//x-axis title
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#xaxis').val(),width/4,height-10*scalefactor);
	
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#xaxis').val(),width/4*3,height-10*scalefactor);
	
	var x, y;
	x=12*scalefactor;
	y=height/2;
	ctx.save();
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.translate(x, y);
	ctx.rotate(-Math.PI/2);
	ctx.textAlign = "center";
	ctx.fillText($('#yaxis').val(), 0, 0);
	ctx.restore();
	
	x=12+width/2*scalefactor;
	y=height/2;
	ctx.save();
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.translate(x, y);
	ctx.rotate(-Math.PI/2);
	ctx.textAlign = "center";
	ctx.fillText("Seasonal Effect", 0, 0);
	ctx.restore();

	
	//get points
	var xpoints = $('#xvar').val().split(",");
	xpoints.pop();
	var ypoints = $('#yvar').val().split(",");
	ypoints.pop();
	
	seasons = checkforts(xpoints);

	if(seasons.substr(0,5)=="Error"){
		return seasons;
	}

	if(ypoints.length==0){
		return 'Error: You must select a numeric variable for variable 2';
	}
	
	tsxpoints = maketsxpoints(xpoints,seasons);
	
	// order the time series from smallest to largest
	//1) combine the arrays:
	var list = [];
	for (var j in tsxpoints)
		list.push({'tsxpoint': tsxpoints[j], 'ypoint': ypoints[j]});

	//2) sort:
	list.sort(function(a, b) {
		return ((a.tsxpoint < b.tsxpoint) ? -1 : ((a.tsxpoint == b.tsxpoint) ? 0 : 1));
	});

	if($.isNumeric(list[0].tsxpoint)){
		list.sort(function(a, b) {
			return (a.tsxpoint - b.tsxpoint);
		});
	}

	//3) separate them back out:
	for (var k = 0; k < list.length; k++) {
		tsxpoints[k] = list[k].tsxpoint;
		ypoints[k] = list[k].ypoint;
	}
	
	ctx.lineWidth = 1*scalefactor;
	ctx.strokeStyle = 'rgba(0,0,0,1)';
	ctx.rect(50*scalefactor,50*scalefactor,width/2-100*scalefactor,height-100*scalefactor);
	ctx.stroke();
	
	ctx.strokeStyle = 'rgba(0,0,0,1)';
	ctx.rect(width/2+50*scalefactor,50*scalefactor,width/2-100*scalefactor,height-100*scalefactor);
	ctx.stroke();
	
	left = 60*scalefactor;
	right = width/2-60*scalefactor;
	gtop = 60*scalefactor;
	gbottom = height-60*scalefactor;

	horaxis(ctx,left,right,add(gbottom,10*scalefactor),1,seasons,1);
	
	stlresponse=stl(tsxpoints,ypoints,seasons);
	trend = stlresponse[0];
	fitted = stlresponse[1];
	s = stlresponse[2];
	r = stlresponse[3];
	
	var pointsforminmax=[];
	var pointsfortminmax=[];
	for (var index in ypoints){
		pointsforminmax.push(ypoints[index]);
		pointsfortminmax.push(parseFloat(tsxpoints[index]));
	}
	
	var ymin = Math.min.apply(null, pointsforminmax);
	var ymax = Math.max.apply(null, pointsforminmax);
	
	var minmaxstep = axisminmaxstep(ymin,ymax);
	var minytick=minmaxstep[0];
	var maxytick=minmaxstep[1];
	var ystep=minmaxstep[2];
	
	vertaxis(ctx,gtop,gbottom,left-10*scalefactor,minytick,maxytick,ystep,right+10*scalefactor);
	
	if($('#addmult option:selected').text()=="Multiplicative"){var multiplicative="yes";} else {var multiplicative = "no";}
	
	seasonleft = width/2+60*scalefactor;
	seasonright = width-60*scalefactor;

	horaxis(ctx,seasonleft,seasonright,add(gbottom,10*scalefactor),1,seasons,1);
	
	if(multiplicative=="yes"){
		smult=[];
		pointsforminmax=[];
		for (var index in fitted){
			smult[index]=fitted[index]/trend[index];
			pointsforminmax.push(smult[index]);
		}
		var smin = Math.min.apply(null, pointsforminmax);
		var smax = Math.max.apply(null, pointsforminmax);
		var minmaxstep = axisminmaxstep(smin,smax);
		var minstick=minmaxstep[0];
		var maxstick=minmaxstep[1];
		var sstep=minmaxstep[2];
		vertaxis(ctx,gtop,gbottom,seasonleft-10*scalefactor,minstick,maxstick,sstep,seasonright+10*scalefactor);
		//0 Line
		ypixel = convertvaltopixel(1,maxstick,minstick,gtop,gbottom);
		ctx.beginPath();
		ctx.setLineDash([5, 5]);
		ctx.moveTo(seasonleft-10*scalefactor, ypixel);
		ctx.lineTo(seasonright+10*scalefactor, ypixel);
		ctx.stroke();
		ctx.setLineDash([]);
	} else {
		shiftforseasonal=Math.ceil((maxytick+minytick)/2/ystep)*ystep;
		vertaxis(ctx,gtop,gbottom,seasonleft-10*scalefactor,minytick-shiftforseasonal,maxytick-shiftforseasonal,ystep,seasonright+10*scalefactor);
		//0 Line
		ypixel = convertvaltopixel(0,maxytick-shiftforseasonal,minytick-shiftforseasonal,gtop,gbottom);
		ctx.beginPath();
		ctx.setLineDash([5, 5]);
		ctx.moveTo(seasonleft-10*scalefactor, ypixel);
		ctx.lineTo(seasonright+10*scalefactor, ypixel);
		ctx.stroke();
		ctx.setLineDash([]);
	} 
	
	if($('#labels').is(":checked")){var labels="yes";} else {var labels = "no";}
	ytrendpts=[];
	firstyear = Math.floor(Math.min.apply(null,pointsfortminmax));
	lastyear = Math.floor(Math.max.apply(null,pointsfortminmax));
	for (index in tsxpoints){
		point=parseFloat(tsxpoints[index]);
		year = Math.floor(point);
		n = (year-firstyear)/(lastyear-firstyear);
		color=ColorHSLaToRGBa(n*0.8,0.75,0.6,0.8);
		ctx.strokeStyle=color;
		ctx.fillStyle=color;
		season=Math.round((point-year)*seasons+1);
		xpixel=convertvaltopixel(season,1,seasons,left,right);
		ypixel=convertvaltopixel(ypoints[index],maxytick,minytick,gtop,gbottom);
		if(season != 1 && index!=0){
			line(ctx,xpixel,ypixel,lastxpixel,lastypixel);
		} 
		if(season==1 || season==seasons){
			fontsize = 10*scalefactor;
			ctx.font = fontsize+"px Roboto";
			ctx.textAlign="left";
			ctx.fillText(year,add(xpixel,3*scalefactor),add(ypixel,4*scalefactor));
		}
		if(multiplicative=="yes"){
			seasonypixel=convertvaltopixel(smult[index],maxstick,minstick,gtop,gbottom);
		} else {
			seasonypixel=convertvaltopixel(s[index],maxytick-shiftforseasonal,minytick-shiftforseasonal,gtop,gbottom);
		}
		seasonxpixel=convertvaltopixel(season,1,seasons,seasonleft,seasonright);
		ctx.strokeStyle='#000000';
		if(season!=1 && index!=0){
			if(parseFloat(index)<=parseFloat(seasons)){
				line(ctx,seasonxpixel,seasonypixel,lastseasonxpixel,lastseasonypixel);
			}
		}
		ctx.beginPath();
		ctx.arc(seasonxpixel,seasonypixel,2,0,2*Math.PI);
		ctx.stroke();
		lastseasonxpixel=seasonxpixel;
		lastseasonypixel=seasonypixel;

		if(labels == "yes"){
			ctx.fillStyle = 'rgba(0,0,255,1)';
			fontsize = 10*scalefactor;
			ctx.font = fontsize+"px Roboto";
			ctx.textAlign="left";
			ctx.fillText(parseInt(add(index,1)),add(add(xpixel,2),2),add(ypixel,4));
		}
		lastxpixel = xpixel;
		lastypixel = ypixel;
	}
	
	
	labelgraph(ctx,width,height);
	
	if($('#invert').is(":checked")){
		invert(ctx)
	}

	var dataURL = canvas.toDataURL();
	return dataURL;
}

function newtimeseriessforecasts(){
	$('#regshow').show();
	$('#labelshow').show();
	$('#addmultshow').show();
	$('#xvar').show();
	$('#yvar').show();
	$('#for').show();
	$('#invertshow').show();

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
	
	//x-axis title
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#xaxis').val(),width/2,height-10*scalefactor);
	
	var x, y;
	x=12*scalefactor;
	y=height/2;
	ctx.save();
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.translate(x, y);
	ctx.rotate(-Math.PI/2);
	ctx.textAlign = "center";
	ctx.fillText($('#yaxis').val(), 0, 0);
	ctx.restore();
	
	//get points
	var xpoints = $('#xvar').val().split(",");
	xpoints.pop();
	var ypoints = $('#yvar').val().split(",");
	ypoints.pop();
	
	seasons = checkforts(xpoints);

	if(seasons.substr(0,5)=="Error"){
		return seasons;
	}

	if(ypoints.length==0){
		return 'Error: You must select a numeric variable for variable 2';
	}
	
	tsxpoints = maketsxpoints(xpoints,seasons);
	
	if($('#addmult option:selected').text()=="Multiplicative"){var multiplicative="yes";} else {var multiplicative = "no";}
	
	pointsforminmax = [];
	for(index in ypoints){
		pointsforminmax.push(ypoints[index]);
		if(multiplicative=="yes"){
			ypoints[index]=Math.log(ypoints[index]);
		}
	}
	
	// order the time series from smallest to largest
	//1) combine the arrays:
	var list = [];
	for (var j in tsxpoints)
		list.push({'tsxpoint': tsxpoints[j], 'ypoint': ypoints[j]});

	//2) sort:
	list.sort(function(a, b) {
		return ((a.tsxpoint < b.tsxpoint) ? -1 : ((a.tsxpoint == b.tsxpoint) ? 0 : 1));
	});

	if($.isNumeric(list[0].tsxpoint)){
		list.sort(function(a, b) {
			return (a.tsxpoint - b.tsxpoint);
		});
	}
	
	pointsforxminmax = [];
	//3) separate them back out:
	for (var k = 0; k < list.length; k++) {
		tsxpoints[k] = list[k].tsxpoint;
		ypoints[k] = list[k].ypoint;
		pointsforxminmax.push(tsxpoints[k]);
	}
	
	// This is where the calculations need to happen.
	
	[alphamin,alphamax,betamin,betamax,gammamin,gammamax,alpha,beta,gamma]=hwoptim(ypoints,seasons,0,1,0,1,0,1);
	[alphamin,alphamax,betamin,betamax,gammamin,gammamax,alpha,beta,gamma]=hwoptim(ypoints,seasons,alphamin,alphamax,betamin,betamax,gammamin,gammamax);
	[alphamin,alphamax,betamin,betamax,gammamin,gammamax,alpha,beta,gamma]=hwoptim(ypoints,seasons,alphamin,alphamax,betamin,betamax,gammamin,gammamax);
	[a,b,s] = hwinit(ypoints,seasons);
	
	error=0;
	fitted=[];
	for(i in ypoints){
		ypoint = ypoints[i];
		a[i]=add(alpha*(ypoint-s[i-seasons]),(1-alpha)*add(a[i-1],b[i-1]));		
		b[i]=add(beta*(a[i]-a[i-1]),(1-beta)*b[i-1]);
		s[i]=add(gamma*(ypoint-a[i]),(1-gamma)*s[i-seasons]);
		fitted[i]=add(add(a[i-1],s[i-seasons]),b[i-1]);
		e = ypoint-add(add(a[i-1],b[i-1]),s[i-seasons]);
		error+=e*e;
		i++;
	}
	error=Math.sqrt(error/ypoints.length);
	
	trend=[];
	trendmin=[];
	trendmax=[];
	forecasts=[];
	forecastsmin=[];
	forecastsmax=[];
	trend[-1]=a[i-1];
	trendmin[-1]=a[i-1];
	trendmax[-1]=a[i-1];
	c=0;
	x=Math.max.apply(null,pointsforxminmax);
	while(c<seasons*2){
		season=add(i,c-seasons);
		x=add(x,1/seasons);
		while(season>i-1){season=season-seasons;}
		t=add(trend[c-1],b[i-1]);
		trend[c]=t;
		forecasts[c]=[x,add(t,s[season])];
		c++;
	}
	
	bsforecasts=[];
	c=0;
	while(c<seasons*2){
		bsforecasts[c]=[];
		c++;
	}

	z=0;
	while(z<1000){
		bstrend=[];
		bstrend[-1]=a[i-1];
		c=0;
		while(c<seasons*2){
			season=i+c-seasons;
			while(season>i-1){season=season-seasons;}
			t=add(add(bstrend[c-1],b[i-1]),purebell(error));
			bstrend[c]=t;
			bsforecasts[c].push(add(t,s[season]));
			c++;
		}
		z++;
	}
	
	x=Math.max.apply(null,pointsforxminmax);
	c=0;
	while(c<seasons*2){
		x=x+1/seasons;
		bsforecasts[c].sort(function(a, b){return a-b});
		forecastsmin[c]=bsforecasts[c][25];
		forecastsmax[c]=bsforecasts[c][975];
		c++;
	}
	
	if(multiplicative=="yes"){
		for(index in forecasts){
			forecasts[index][1]=Math.exp(forecasts[index][1]);
			forecastsmin[index]=Math.exp(forecastsmin[index]);
			forecastsmax[index]=Math.exp(forecastsmax[index]);
		}
		for(index in ypoints){
			fitted[index]=Math.exp(fitted[index]);
			ypoints[index]=Math.exp(ypoints[index]);
		}
	}
	
	for(index in forecasts){
		pointsforminmax.push(forecasts[index][1]);
		pointsforxminmax.push(forecasts[index][0]);
		pointsforminmax.push(forecastsmin[index]);
		pointsforminmax.push(forecastsmax[index]);
	}
	
	
	// end the calculations
	
	// start thinking about graphing or split into table display.
	
	var xmin = Math.min.apply(null, pointsforxminmax);
	var xmax = Math.max.apply(null, pointsforxminmax);
	var minmaxstep = axisminmaxstep(xmin,xmax);
	var minxtick=minmaxstep[0];
	var maxxtick=minmaxstep[1];
	var xstep=minmaxstep[2];
	
	var ymin = Math.min.apply(null, pointsforminmax);
	var ymax = Math.max.apply(null, pointsforminmax);
	var minmaxstep = axisminmaxstep(ymin,ymax);
	var minytick=minmaxstep[0];
	var maxytick=minmaxstep[1];
	var ystep=minmaxstep[2];
	
	ctx.lineWidth = 1*scalefactor;
	ctx.strokeStyle = 'rgba(0,0,0,1)';
	ctx.rect(50*scalefactor,50*scalefactor,width-100*scalefactor,height-100*scalefactor);
	ctx.stroke();
	
	left = 60*scalefactor;
	right = width-60*scalefactor;
	gtop = 60*scalefactor;
	gbottom = height-60*scalefactor;

	horaxis(ctx,left,right,add(gbottom,10*scalefactor),minxtick,maxxtick,xstep);

	vertaxis(ctx,gtop,gbottom,left-10*scalefactor,minytick,maxytick,ystep,right+10*scalefactor);	
	
	if($('#labels').is(":checked")){var labels="yes";} else {var labels = "no";}
	
	if($('#regression').is(":checked")){
		toreturn = "Error:";
		toreturn+="<style>"+
"				#forecastoutput {"+
"					position:absolute;"+
"					top:0px;"+
"					left:0px;"+
"					width:"+(width-20)+"px;"+
"					height:"+(height-20)+"px;"+
"					overflow-y:scroll;"+
"					padding:10px;"+
"				}"+
"				#forecastoutput table {"+
"					border-collapse:collapse;"+
"				}"+
"				#forecastoutput table td, #forecastoutput table th {"+
"					border:1px solid #000;"+
"					padding-left:4px;"+
"					padding-right:4px;"+
"					width:80px;"+
"				}"+
"				#forecastoutput *.minmax {"+
"					color:#bbb;"+
"				}"+
"			</style>"+
"			<div id=forecastoutput>"+
"			<table><tr><th>Time<th>Min<th>Prediction<th>Max";
		c=0;
		x = Math.max.apply(null,tsxpoints);
		while(c<seasons*2){
			x=forecasts[c][0];
			min = parseFloat(forecastsmin[c].toPrecision(5));
			pred = parseFloat(forecasts[c][1].toPrecision(5));
			max = parseFloat(forecastsmax[c].toPrecision(5));
			year = Math.floor(x);
			month=Math.round((x-year)*seasons)+1;
			if(month>seasons){
				month=1;
				year++;
			}

			if(seasons==1){
				split=""
			} else if(seasons==4){
				split="Q"
			} else if(seasons==12){
				split="M"
			} else if(seasons==7){
				split="D"
			} else if(seasons==5){
				split="W"
			} else if(seasons==24){
				split="H"
			} else {
				split="C"
			}
			if(seasons==1){
				month="";
			} else {
				i=0;
				pad="";
				while(i<seasons.length){
					pad+="0";
					i++;
				}
				month = (pad+month).slice(-seasons.length);
				month = split+month;
			}
			toreturn+= "<tr><td align=center>"+year+month+"<td align=center class=minmax>"+min+"<td align=center>"+pred+"<td align=center class=minmax>"+max;
			c++;
		}

		toreturn+="</table></div>";
		return toreturn;
	} else {
		
		// This is where the graphing needs to happen	
		ctx.lineWidth = 2*scalefactor;
		ctx.strokeStyle='rgb(0,200,200)';
		
		for (index in tsxpoints){
			point=parseFloat(tsxpoints[index]);
			xpixel=convertvaltopixel(point,minxtick,maxxtick,left,right);
			ypixel=convertvaltopixel(fitted[index],maxytick,minytick,gtop,gbottom);
			if(index != 0){
				line(ctx,xpixel,ypixel,lastxpixel,lastypixel);
			}
			if(labels == "yes"){
				ctx.fillStyle = 'rgba(0,0,255,1)';
				fontsize = 10*scalefactor;
				ctx.font = fontsize+"px Roboto";
				ctx.textAlign="left";
				ctx.fillText(parseInt(add(index,1)),add(add(xpixel,2),2),add(ypixel,4));
			}
			lastxpixel = xpixel;
			lastypixel = ypixel;	
		}
		
		lastxpixelfitted = lastxpixel;
		lastypixelfitted = lastypixel;
		
		ctx.lineWidth = 1*scalefactor;
		ctx.strokeStyle='#000';
		
		for (index in tsxpoints){
			point=parseFloat(tsxpoints[index]);
			xpixel=convertvaltopixel(point,minxtick,maxxtick,left,right);
			ypixel=convertvaltopixel(ypoints[index],maxytick,minytick,gtop,gbottom);
			if(index != 0){
				line(ctx,xpixel,ypixel,lastxpixel,lastypixel);
			}
			if(labels == "yes"){
				ctx.fillStyle = 'rgba(0,0,255,1)';
				fontsize = 10*scalefactor;
				ctx.font = fontsize+"px Roboto";
				ctx.textAlign="left";
				ctx.fillText(parseInt(add(index,1)),add(add(xpixel,2),2),add(ypixel,4));
			}
			lastxpixel = xpixel;
			lastypixel = ypixel;	
		}
		
		lastxpixel = lastxpixelfitted;
		lastypixel = lastypixelfitted;
		
		ctx.strokeStyle='#f00';
		
		for(index in forecasts){
			point=parseFloat(forecasts[index][0]);
			xpixel=convertvaltopixel(point,minxtick,maxxtick,left,right);
			ypixel=convertvaltopixel(forecasts[index][1],maxytick,minytick,gtop,gbottom);
			line(ctx,xpixel,ypixel,lastxpixel,lastypixel);
			lastxpixel = xpixel;
			lastypixel = ypixel;
		}
		
		ctx.fillStyle='rgba(255,0,0,0.2)';
		ctx.beginPath();
		
		for(index in forecasts){
			point=parseFloat(forecasts[index][0]);
			xpixel=convertvaltopixel(point,minxtick,maxxtick,left,right);
			ypixel=convertvaltopixel(forecastsmin[index],maxytick,minytick,gtop,gbottom);
			if(index == -1){
				ctx.moveTo(xpixel, ypixel);
			} else {
				ctx.lineTo(xpixel, ypixel);
			}
		}
		forecasts.reverse();
		forecastsmax.reverse();
		for(index in forecasts){
			point=parseFloat(forecasts[index][0]);
			xpixel=convertvaltopixel(point,minxtick,maxxtick,left,right);
			ypixel=convertvaltopixel(forecastsmax[index],maxytick,minytick,gtop,gbottom);
			ctx.lineTo(xpixel, ypixel);
		}
		ctx.fill();
		forecasts.reverse();
		forecastsmax.reverse();
		
		// This is the end of the graphing.
		
		ctx.fillStyle = 'rgba(0,0,0,1)';
		fontsize = 12*scalefactor;
		ctx.font = fontsize+"px Roboto";
		ctx.textAlign="left";
		
		ctx.strokeStyle='#000';
		ctx.lineWidth = 1*scalefactor;
		line(ctx,60*scalefactor,60*scalefactor,80*scalefactor,60*scalefactor);
		ctx.fillText("Raw Data",85*scalefactor,65*scalefactor);
		
		
		ctx.strokeStyle='rgb(0,200,200)';
		ctx.lineWidth = 2*scalefactor;
		line(ctx,60*scalefactor,75*scalefactor,80*scalefactor,75*scalefactor);
		ctx.fillText("Historic Predictions",85*scalefactor,80*scalefactor);
		
		ctx.fillStyle='rgba(255,0,0,0.2)';
		ctx.fillRect(60*scalefactor, 85*scalefactor, 20*scalefactor, 10*scalefactor);
		ctx.strokeStyle='#f00';
		line(ctx,60*scalefactor,90*scalefactor,80*scalefactor,90*scalefactor);
		ctx.fillStyle = 'rgba(0,0,0,1)';
		ctx.fillText("Predictions",85*scalefactor,95*scalefactor);
		
		ctx.lineWidth = 1*scalefactor;
		
		labelgraph(ctx,width,height);
		
		if($('#invert').is(":checked")){
			invert(ctx)
		}

		var dataURL = canvas.toDataURL();
		return dataURL;
	}
}

function hwinit(ypoints,seasons){
	//create a centered moving mean for the first two years of the data.
	mm=[];
	i=0;
	ioffset=Math.floor(seasons/2);
	while(i<seasons){
		a1=0;
		a=0;
		while(a<seasons){
			a1=add(a1,ypoints[a+i]);
			a++;
		}
		a2=0;
		a=0;
		while(a<seasons){
			a++;
			a2=add(a2,ypoints[a+i]);
		}
		mm[i+ioffset+1]=(a1+a2)/(seasons*2);
		i++;
	}
	
	$.ajaxSetup({async:false});
	
	if(mm.length>1){
		// fit a regression line to it
		
		xcurvefit = "";
		ycurvefit = "";
		for (index in mm){
			xcurvefit+=index+",";
			ycurvefit+=mm[index]+",";
		}
		
		$.post('curvefitter.php',{xvals:xcurvefit,yvals:ycurvefit,type:'regression'}).done(function(data){
			data=JSON.parse(data);
			c = parseFloat(data.c).toPrecision(5);
			m = parseFloat(data.m).toPrecision(5);
			r = parseFloat(data.r).toPrecision(5);
		})
		
		ap=c; // x-intercept is starting value
		bp=m; // gradient is the initial trend value

		//get inital seasonal effects.
		i=0;
		s=[];
		while (i<seasons){
			s[i-seasons]=ypoints[i]-(add(ap,bp*i));
			i++;
		}	
		
		a=[];
		b=[];
		a[-1]=ap;
		b[-1]=bp;
		return [a,b,s];
	} else {
		// fit a regression line to the first three points

		// Add all the data to the regression analysis. 
		i=0;
		xcurvefit = "";
		ycurvefit = "";
		while(i<3){
			xcurvefit+=index+",";
			ycurvefit+=mm[index]+",";
		}
		
		$.post('curvefitter.php',{xvals:xcurvefit,yvals:ycurvefit,type:'regression'}).done(function(data){
			data=JSON.parse(data);
			c = parseFloat(data.c).toPrecision(5);
			m = parseFloat(data.m).toPrecision(5);
			r = parseFloat(data.r).toPrecision(5);
		})
		
		ap=c; // x-intercept is starting value
		bp=m; // gradient is the initial trend value

		a=[];
		b=[];
		s=[];
		a[-1]=ap;
		b[-1]=bp;
		s[-1]=0;
		return [a,b,s];
	}
}

function hwoptim(ypoints,seasons,alphamin,alphamax,betamin,betamax,gammamin,gammamax){
	split=9;
	hw = hwinit(ypoints,seasons);
	a = hw[0];
	b = hw[1];
	s = hw[2];
	results=[];
	alpha=alphamin;
	while(alpha<alphamax){
		beta=betamin;
		while(beta<betamax){
			gamma=gammamin;
			while(gamma<gammamax){
				i=0;
				error=0;
				for(index in ypoints){
					ypoint = ypoints[index];
					a[i]=alpha*(ypoint-s[i-seasons])+(1-alpha)*(add(a[i-1],b[i-1]));
					b[i]=beta*(a[i]-a[i-1])+(1-beta)*b[i-1];
					s[i]=gamma*(ypoint-a[i])+(1-gamma)*s[i-seasons];
					e = ypoint-(add(add(a[i-1],b[i-1]),s[i-seasons]));
					error+=e*e;
					i++;
				}
				results.push([error,alpha,beta,gamma]);
				gamma=add(gamma,(gammamax-gammamin)/split);
			}
			beta=add(beta,(betamax-betamin)/split);
		}
		alpha=add(alpha,(alphamax-alphamin)/split);
	}
	
	
	results.sort(function(a, b) {
		return ((a[0] < b[0]) ? -1 : ((a[0] == b[0]) ? 0 : 1));
	});
	
	alphas=[];
	betas=[];
	gammas=[];
	i=0;
	while(i<20){
		result = results[i];
		alphas.push(result[1]);
		betas.push(result[2]);
		gammas.push(result[3]);
		i++;
	}
	
	ralphamin=Math.max(Math.min.apply(null,alphas)-(alphamax-alphamin)/split,0);
	ralphamax=Math.min(Math.max.apply(null,alphas)-(-(alphamax-alphamin)/split),1);
	rbetamin=Math.max(Math.min.apply(null,betas)-(betamax-betamin)/split,0);
	rbetamax=Math.min(Math.max.apply(null,betas)-(-(betamax-betamin)/split),1);
	rgammamin=Math.max(Math.min.apply(null,gammas)-(gammamax-gammamin)/split,0);
	rgammamax=Math.min(Math.max.apply(null,gammas)-(-(gammamax-gammamin)/split),1);
	
	//and this
	return [ralphamin,ralphamax,rbetamin,rbetamax,rgammamin,rgammamax,results[0][1],results[0][2],results[0][3]];
}

function purebell(std_deviation) {
	rand1 = Math.random();
	rand2 = Math.random();
	gaussian_number = Math.sqrt(-2 * Math.log(rand1)) * Math.cos(2 * 3.14159 * rand2);
	random_number = (gaussian_number * std_deviation);
	return random_number;
}

function addindex(){
	$("#rowbox").hide();
	$("#colbox").hide();
	$("#sambox").hide();
	i=0;
	$('#data tr').each(function(){
		if(i==0){
			val = 'Index';
		} else {
			val = i;
		}
		$("<td><div>" + val + "<br></div></td>").insertAfter($(this).children('th'));
		i++;
	})
	$('#data td div').attr('contenteditable','true');
	updatebox();
}
function converttimeshow(){
	$('#converttimediv').show();
	$("#sampling").show();
	$("#rowbox").hide();
	$("#colbox").hide();
	$("#sambox").hide();
	var col=2;
	var options=[];
	options.push('<option></option>');
	$('#data tr:first td').each( function(){
		options.push('<option value="'+(col)+'">' + $(this).text() + '</option>');
		col++;
	});
	//finally empty the select and append the items from the array
	$('#converttimecol').empty().append( options.join() );
}

function converttimego(){
	var col = $('#converttimecol').val();
	var convertto = $('#converttimeto').val();
	i=0;
	items=[];
	minmax=[];
	$('#data tr td:nth-child('+col+')').each( function(){
		if(i!=0){
			test = Date.parse($(this).text());
			if($.isNumeric(test)){
				items.push(test);
				minmax.push(test);
			} else{
				test = Date.parse('1 Jan '+$(this).text());
				if($.isNumeric(test)){
					minmax.push(test);
				}
				items.push(test);
			}
		}
		i++;
	});
	lowesttime = Math.min.apply(null,minmax);
	i=0;
	divideby = 1;
	if(convertto == 'Seconds'){divideby=1000;}
	if(convertto == 'Minutes'){divideby=60000;}
	if(convertto == 'Hours'){divideby=3600000;}
	if(convertto == 'Days'){divideby=86400000;}
	$('#data tr').each(function(){
		if(i==0){
			val = convertto;
		} else {
			val = (items[i-1]-lowesttime)/divideby;
		}
		$("<td><div>" + val + "<br></div></td>").insertAfter($(this).children(':eq('+(col-1)+')'));
		i++;
	})
	$('#data td div').attr('contenteditable','true');
	$ ("#sampling").hide();
	$ ("#converttimediv").hide();
}

function encodetimeshow(){
	customshowhide();
	$('#encodetimediv').show();
	$("#sampling").show();
	$("#rowbox").hide();
	$("#colbox").hide();
	$("#sambox").hide();
	var col=2;
	var options=[];
	options.push('<option></option>');
	$('#data tr:first td').each( function(){
		options.push('<option value="'+(col)+'">' + $(this).text() + '</option>');
		col++;
	});
	//finally empty the select and append the items from the array
	$('#encodetimecol').empty().append( options.join() );
}

function customshowhide(){
	if($('#encodetimetype').val()=='Custom'){
		$('.encodecustomshow').show();
	} else {
		$('.encodecustomshow').hide();
	}
}

function encodetimego(){
	var col = $('#encodetimecol').val();
	var sumavg = $('#encodetimesumavg').val();
	var type = $('#encodetimetype').val();
	i=0;
	items=[];
	$('#data tr td:nth-child('+col+')').each( function(){
		if(i!=0){
			items.push( Date.parse($(this).text()) );
		}
		i++;
	});
	lowesttime = Math.min.apply(null,items);
	highesttime = Math.max.apply(null,items);
	if(type=='Quarter'){
		length = 0;
		start = 0;
		seasons = 4;
		split="Q";
		pad="0";
	} else if(type=='Month'){
		length = 0;
		start = 0;
		seasons = 12;
		split="M";
		pad="00";
	} else if(type=='Day'){
		length = 0;
		start = 0;
		seasons = 7;
		split="D";
		pad="0";
	} else if(type=='Hour'){
		length = 0;
		start = 0;
		seasons = 24;
		split="H";
		pad="00";
	} else {
		length = $('#encodelength').val()*$('#encodemult').val();
		seasons = $('#encodeseasons').val();
		start = Date.parse($('#encodestart').val());
		split="C";
		pad="";
		i=0;
		while(i<seasons.toString().length){
			pad+="0";
			i++;
		}
	}
	data = [];
	data['0000']=['TS'];
	firstseason = converttots(lowesttime-start,seasons,length);
	firstseason = firstseason.split(split);
	currentyear=firstseason[0];
	firstyear=firstseason[0];
	firstseason=firstseason[1];
	lastseason = converttots(highesttime-start,seasons,length);
	lastseason = lastseason.split(split);
	lastyear=lastseason[0];
	lastseason=lastseason[1];
	while(currentyear<=lastyear){
		if(currentyear==firstyear){
			currentseason=firstseason;
		} else {
			currentseason=1;
		}
		while((currentseason<=seasons && currentyear<lastyear) || (currentseason<=lastseason && currentyear==lastyear)){
			timestamp = currentyear + split + (pad + currentseason).slice(-pad.length)
			data[timestamp]=[];
			data[timestamp][0]=[timestamp];
			c=1;
			$('#data tr:first td').each( function(){
				data[timestamp][c]=[];
				c++;
			});
			currentseason++;
		}
		currentyear++;
	}
	i=0;
	$('#data tr').each( function(){
		time = Date.parse($(this).children().eq(col-1).text());
		time = converttots(time-start,seasons,length);
		c = 1;
		$(this).children('td').each(function(){
			if(i==0){
				data['0000'][c]=$(this).text();
			} else {
				data[time][c].push($(this).text());
			}
			c++;
		});
		i++;
	});
	for (key in data){
		value = data[key];
		for (vkey in value){
			val = value[vkey];
			if(typeof val == "object"){
				if(val.length==0){
					data[key][vkey]="-";
				} else if(val.length==1){
					data[key][vkey]=val[0];
				} else {
					if($.isNumeric(data[key][vkey][0])){
						sum=0;
						for (var index in val){
							sum = add(sum,val[index]);
						}
						if(sumavg=="avg"){
							sum = parseFloat((sum/val.length).toPrecision(5));
						}
						data[key][vkey]=sum;
					} else {
						data[key][vkey]=mode(val);
					}
				}
				
			}
		}
	}
	var newtable="";
	i=0;
	for (index in data) {
		var cells = data[index];
		if(i==0){
			newtable += "<tr class=tabletop><th>id";
		} else {
			newtable += "<tr><th>" + i;
		}
		for (c = 0; c < cells.length; c++) {
			cell = cells[c];
			if(cell==''){cell="-";}
			newtable += "<td><div>" + cell + "<br></div></td>"
		}
		i++;
	}
	document.getElementById("data").innerHTML = newtable;
	$('#data td div').attr('contenteditable','true');
	$('#type').val('newabout');
	updatebox();
	$('#data td div').attr('contenteditable','true');
	$ ("#sampling").hide();
	$ ("#encodetimediv").hide();
}

//length is length of the "year", not the season

function converttots(time,seasons,length){
	date = new Date(time);
	if(seasons==12 && length==0){
		return date.getFullYear()+"M"+("00"+add(1,date.getMonth())).slice(-2);
	} else if(seasons==4 && length==0){
		return date.getFullYear()+"Q"+add(1,Math.floor(date.getMonth()/3));
	} else if(seasons==7 && length==0){
		week = getWeekNumber(date);
		return week+"D"+add(date.getDay(),1);
	} else if(seasons==24 && length==0){
		day = getDayNumber(date);
		return day+"H"+("00"+add(1,date.getHours())).slice(-2);
	} else {
		part1=Math.floor(time/length);
		part2=add(Math.floor((time/length-part1)*seasons),1);
		pad="";
		l=0;
		while(l<seasons.toString().length){
			pad+="0";
			l++;
		}
		part2 = (pad+part2).slice(-seasons.toString().length);
		return part1+"C"+part2;
	}
}

function getWeekNumber(d) {
    // Copy date so don't modify original
    d = new Date(d.getFullYear(), d.getMonth(), d.getDate());
    d.setDate(d.getDate() - d.getDay());
    var weekNo = Math.ceil(( ( (d) / 86400000) + 1)/7);
    // Return week number
    return weekNo;
}

function getDayNumber(d) {
    // Copy date so don't modify original
    d = new Date(d.getFullYear(), d.getMonth(), d.getDate());
    var dayNo = Math.ceil( ( d / 86400000) + 1);
    // Return array of year and week number
    return dayNo;
}

function mode(values){
	counts = [];
	for(var i=0;i< values.length;i++)
	{
	  var key = values[i];
	  counts[key] = (counts[key])? counts[key] + 1 : 1 ;

	}
	return Object.keys(counts).reduce(function(a, b){ return counts[a] > counts[b] ? a : b })
}
function sortorder(as, bs)
{
    var a, b, a1, b1, i= 0, n, L,
    rx=/(\.\d+)|(\d+(\.\d+)?)|([^\d.]+)|(\.\D+)|(\.$)/g;
    if(as=== bs) return 0;
    a= as.toLowerCase().match(rx);
    b= bs.toLowerCase().match(rx);
    L= a.length;
    while(i<L){
        if(!b[i]) return 1;
        a1= a[i],
        b1= b[i++];
        if(a1!== b1){
            n= a1-b1;
            if(!isNaN(n)) return n;
            return a1>b1? 1:-1;
        }
    }
    return b[i]? -1:0;
}
