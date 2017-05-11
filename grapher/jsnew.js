function newtimeseries(){
	$('#labelshow').show();
	$('#longtermtrendshow').show();
	$('#addmultshow').show();
	$('#startfinishshow').show();
	$('#seasonalshow').show();
	$('#xvar').show();
	$('#yvar').show();
	$('#zvar').show();
	$('#differentaxisshow').show();
	$('#gridlinesshow').show();

	var canvas = document.getElementById('myCanvas');
    var ctx = canvas.getContext('2d');

	//set size
	var width = $('#width').val();
	var height = $('#height').val();

	ctx.canvas.width = width;
	ctx.canvas.height = height;

	ctx.fillStyle = "#ffffff";
	ctx.fillRect(0, 0, canvas.width, canvas.height);

	if($('#longtermtrend').is(":checked")){var longtermtrend="yes";} else {var longtermtrend = "no";}
	if($('#seasonal').is(":checked")){var seasonal="yes";} else {var seasonal = "no";}

	//graph title
	ctx.fillStyle = '#000000';
	fontsize = 20*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#title').val(),width/2,30*scalefactor);

	if(seasonal=='yes'){
		width=width*0.7;
	}

	//get points
	var xpoints = $('#xvar').val().split(",");
	xpoints.pop();
	var ypoints = $('#yvar').val().split(",");
	ypoints.pop();
	var zpoints = $('#zvar').val().split(",");
	zpoints.pop();

	seasons = checkforts(xpoints);

	if(seasons.substr(0,5)=="Error"){
		return seasons;
	}

	if(ypoints.length==0){
		return 'Error: You must select a numeric variable for variable 2';
	}

	tsxpoints = maketsxpoints(xpoints,seasons);

	// order the time series from smallest to largest
	//check if zpoints
	if(zpoints.length==0){
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
	} else {
		//1) combine the arrays:
		var list = [];
		for (var j in tsxpoints)
			list.push({'tsxpoint': tsxpoints[j], 'ypoint': ypoints[j], 'zpoint': zpoints[j]});

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
			zpoints[k] = list[k].zpoint;
		}
	}

	ctx.lineWidth = 1*scalefactor;
	ctx.strokeStyle = 'rgba(0,0,0,1)';
	ctx.rect(50*scalefactor,50*scalefactor,width-100*scalefactor,height-100*scalefactor);
	ctx.stroke();

	//x-axis title
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#xaxis').val(),width/2,height-10*scalefactor);

	//y-axis title
	if($('#yaxis').val() != "Y Axis Title"){
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
	}

	var xmin = Math.min.apply(null, tsxpoints);
	var xmax = Math.max.apply(null, tsxpoints);

	var minmaxstep = axisminmaxstep(xmin,xmax);
	var minxtick=minmaxstep[0];
	var maxxtick=minmaxstep[1];
	var xstep=minmaxstep[2];
	if(xstep<1){xstep=1;}

	left = 60*scalefactor;
	right = width-60*scalefactor;
	gtop = 60*scalefactor;
	gbottom = height-60*scalefactor;

	horaxis(ctx,left,right,add(gbottom,10*scalefactor),minxtick,maxxtick,xstep);

	var pointsforminmax=[];
	for (var index in ypoints){
		pointsforminmax.push(ypoints[index]);
	}

	if($('#differentaxis').is(":checked")){var differentaxis="yes";} else {var differentaxis = "no";}
	if($('#startfinish').is(":checked")){var startfinish="yes";} else {var startfinish = "no";}

	if(longtermtrend=='yes'){
		stlresponse=stl(tsxpoints,ypoints,seasons);
		trend = stlresponse[0];
		fitted = stlresponse[1];
		s = stlresponse[2];
		r = stlresponse[3];
	}

	if(zpoints.length>0){
		if(differentaxis=="yes"){
			pointsforzminmax=[];
			for (var index in zpoints){
				pointsforzminmax.push(zpoints[index]);
			}
			var zmin = Math.min.apply(null, pointsforzminmax);
			var zmax = Math.max.apply(null, pointsforzminmax);

			var minmaxstep = axisminmaxstep(zmin,zmax);
			var minztick=minmaxstep[0];
			var maxztick=minmaxstep[1];
			var zstep=minmaxstep[2];

			rvertaxis(ctx,gtop,gbottom,right+10*scalefactor,minztick,maxztick,zstep,left);

			zshiftforseasonal=Math.ceil((maxztick+minztick)/2/zstep)*zstep;
			rvertaxis(ctx,gtop,gbottom,right+width/0.7*0.3+10*scalefactor,minztick-zshiftforseasonal,maxztick-zshiftforseasonal,zstep,seasonleft);
		} else {
			for (var index in zpoints){
				pointsforminmax.push(zpoints[index]);
			}
		}
	}

	var ymin = Math.min.apply(null, pointsforminmax);
	var ymax = Math.max.apply(null, pointsforminmax);

	var minmaxstep = axisminmaxstep(ymin,ymax);
	var minytick=minmaxstep[0];
	var maxytick=minmaxstep[1];
	var ystep=minmaxstep[2];

	vertaxis(ctx,gtop,gbottom,left-10*scalefactor,minytick,maxytick,ystep,right+10*scalefactor);
	if(seasonal=="yes"){
		shiftforseasonal=Math.ceil((maxytick+minytick)/2/ystep)*ystep;
		vertaxis(ctx,gtop,gbottom,right+80*scalefactor,minytick-shiftforseasonal,maxytick-shiftforseasonal,ystep,seasonright+10*scalefactor);
		ctx.lineWidth = 1*scalefactor;
		ctx.strokeStyle = 'rgba(0,0,0,1)';
		seasonleft=right+90*scalefactor;
		seasonright=width/0.7*0.3+right;
		ctx.rect(seasonleft-10*scalefactor,gtop-10*scalefactor,seasonright-seasonleft+20*scalefactor,gbottom-gtop+20*scalefactor);
		ctx.stroke();
		horaxis(ctx,seasonleft,seasonright,add(gbottom,10*scalefactor),1,seasons,1);
		//x-axis title
		ctx.fillStyle = '#000000';
		fontsize = 15*scalefactor;
		ctx.font = "bold "+fontsize+"px Roboto";
		ctx.textAlign="center";
		ctx.fillText("Season",(seasonleft+seasonright)/2,height-10*scalefactor);
		//y-axis title
		var x, y;
		x=seasonleft-40*scalefactor;
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
	}

	if($('#addmult option:selected').text()=="Multiplicative"){var multiplicative="yes";} else {var multiplicative = "no";}
	if($('#labels').is(":checked")){var labels="yes";} else {var labels = "no";}
	ytrendpts=[];
	for (index in tsxpoints){
		if(zpoints.length>0){
			ctx.strokeStyle = 'rgba(48,145,255,1)';
		}
		xpixel=convertvaltopixel(tsxpoints[index],minxtick,maxxtick,left,right);
		ypixel=convertvaltopixel(ypoints[index],maxytick,minytick,gtop,gbottom);
		if(index != 0){
			line(ctx,xpixel,ypixel,lastxpixel,lastypixel);
		}
		if(longtermtrend=='yes'){
			trendpixel=convertvaltopixel(trend[index],maxytick,minytick,gtop,gbottom);
			ytrendpts.push(xpixel,trendpixel);
			if(startfinish=="yes" && (index==0 || index==tsxpoints.length-1)){
				ctx.textAlign="left";
				if(index==0){
					ctx.textAlign="right";
				}
				ctx.fillStyle = 'rgba(255,255,255,1)';
				ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel-2,trendpixel-2);
				ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel+2,trendpixel-2);
				ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel-2,trendpixel+2);
				ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel+2,trendpixel+2);
				if(zpoints.length>0){
					ctx.fillStyle = 'rgba(48,145,255,1)';
				} else {
					ctx.fillStyle = 'rgba(0,0,0,1)';
				}
				fontsize = 12*scalefactor;
				ctx.font = "bold "+fontsize+"px Roboto";
				ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel,trendpixel);
			}
			lasttrendpixel=trendpixel;
			if(seasonal=='yes'){
				seasonypixel=convertvaltopixel(s[index],maxytick-shiftforseasonal,minytick-shiftforseasonal,gtop,gbottom);
				point=parseFloat(tsxpoints[index]);
				season=Math.round((point-Math.floor(point))*seasons+1);
				seasonxpixel=convertvaltopixel(season,1,seasons,seasonleft,seasonright);
				if(season!=1 && index!=0){
					if(multiplicative=="yes"){
						if(zpoints.length>0){
							ctx.strokeStyle = 'rgba(48,145,255,0.3)';
						} else {
							ctx.strokeStyle = 'rgba(0,0,0,0.3)';
						}
						line(ctx,seasonxpixel,seasonypixel,lastseasonxpixel,lastseasonypixel);
						if(zpoints.length>0){
							ctx.strokeStyle = 'rgba(48,145,255,1)';
						} else {
							ctx.strokeStyle = 'rgba(0,0,0,1)';
						}
					} else if(parseFloat(index)<=parseFloat(seasons)){
						line(ctx,seasonxpixel,seasonypixel,lastseasonxpixel,lastseasonypixel);
					}
				}
				ctx.beginPath();
				ctx.arc(seasonxpixel,seasonypixel,2,0,2*Math.PI);
				ctx.stroke();
				lastseasonxpixel=seasonxpixel;
				lastseasonypixel=seasonypixel;
			}
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
	if(longtermtrend=="yes"){
		ctx.lineWidth = 3*scalefactor;
		drawSpline(ctx,ytrendpts,0.5);
	}

	if(zpoints.length>0){
		if(longtermtrend=='yes'){
			stlresponse=stl(tsxpoints,zpoints,seasons);
			trend = stlresponse[0];
			fitted = stlresponse[1];
			s = stlresponse[2];
			r = stlresponse[3];
		}
		ctx.strokeStyle = 'rgba(191,108,36,1)';
		ztrendpts=[]
		for (index in tsxpoints){
			xpixel=convertvaltopixel(tsxpoints[index],minxtick,maxxtick,left,right);
			if(differentaxis=="yes"){
				ypixel=convertvaltopixel(zpoints[index],maxztick,minztick,gtop,gbottom);
			} else {
				ypixel=convertvaltopixel(zpoints[index],maxytick,minytick,gtop,gbottom);
			}
			if(index != 0){
				line(ctx,xpixel,ypixel,lastxpixel,lastypixel);
			}
			if(longtermtrend=='yes'){
				if(differentaxis=="yes"){
					trendpixel=convertvaltopixel(trend[index],maxztick,minztick,gtop,gbottom);
				} else {
					trendpixel=convertvaltopixel(trend[index],maxytick,minytick,gtop,gbottom);
				}
				ztrendpts.push(xpixel,trendpixel);
				ctx.lineWidth = 1*scalefactor;
				if(startfinish=="yes" && (index==0 || index==tsxpoints.length-1)){
					ctx.textAlign="left";
					if(index==0){
						ctx.textAlign="right";
					}
					ctx.fillStyle = 'rgba(255,255,255,1)';
					ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel-2,trendpixel-2);
					ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel+2,trendpixel-2);
					ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel-2,trendpixel+2);
					ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel+2,trendpixel+2);
					ctx.fillStyle = 'rgba(191,108,36,1)';
					fontsize = 12*scalefactor;
					ctx.font = "bold "+fontsize+"px Roboto";
					ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel,trendpixel);
				}
				lasttrendpixel=trendpixel;
				if(differentaxis=="yes"){
					seasonypixel=convertvaltopixel(s[index],maxztick-zshiftforseasonal,minztick-zshiftforseasonal,gtop,gbottom);
				} else {
					seasonypixel=convertvaltopixel(s[index],maxytick-shiftforseasonal,minytick-shiftforseasonal,gtop,gbottom);
				}
				if(seasonal=='yes'){
					point=parseFloat(tsxpoints[index]);
					season=Math.round((point-Math.floor(point))*seasons+1);
					seasonxpixel=convertvaltopixel(season,1,seasons,seasonleft,seasonright);
					if(season!=1 && index!=0){
						if(multiplicative=="yes"){
							ctx.strokeStyle = 'rgba(191,108,36,0.3)';
							line(ctx,seasonxpixel,seasonypixel,lastseasonxpixel,lastseasonypixel);
							ctx.strokeStyle = 'rgba(191,108,36,1)';
						} else if(parseFloat(index)<=parseFloat(seasons)){
							line(ctx,seasonxpixel,seasonypixel,lastseasonxpixel,lastseasonypixel);
						}
					}
					ctx.beginPath();
					ctx.arc(seasonxpixel,seasonypixel,2,0,2*Math.PI);
					ctx.stroke();
					lastseasonxpixel=seasonxpixel;
					lastseasonypixel=seasonypixel;
				}
			}
			if(labels == "yes"){
				ctx.fillStyle = 'rgba(0,0,255,1)';
				fontsize = 10*scalefactor;
				ctx.font = fontsize+"px Roboto";
				ctx.textAlign="left";
				ctx.fillText(parseInt(add(index,1)),add(xpixel,4),add(ypixel,4));
			}
			lastxpixel = xpixel;
			lastypixel = ypixel;
		}
		if(longtermtrend=="yes"){
			ctx.lineWidth = 3*scalefactor;
			drawSpline(ctx,ztrendpts,0.5);
		}
		if(differentaxis=="yes"){
			lefta=" (left axis)";
			righta=" (right axis)";
		} else {
			lefta="";
			righta="";
		}
		ctx.lineWidth = 2*scalefactor;
		ctx.textAlign="left";
		fontsize = 13*scalefactor;
		ctx.font = fontsize+"px Roboto";
		ctx.strokeStyle = 'rgba(48,145,255,1)';
		ctx.fillStyle = 'rgba(48,145,255,1)';
		line(ctx,left,gtop,add(left,10*scalefactor),gtop);
		ctx.fillText($("#yvar option:selected").text()+lefta,add(left,15*scalefactor),add(gtop,5*scalefactor));
		ctx.strokeStyle = 'rgba(191,108,36,1)';
		ctx.fillStyle = 'rgba(191,108,36,1)';
		line(ctx,left,add(gtop,15*scalefactor),add(left,10*scalefactor),add(gtop,15*scalefactor));
		ctx.fillText($("#zvar option:selected").text()+righta,add(left,15*scalefactor),add(gtop,20*scalefactor));
	}

	if(seasonal=='yes'){
		width=width/0.7;
	}
	labelgraph(ctx,width,height);

	var dataURL = canvas.toDataURL();
	return dataURL;
}

function newtimeseriesrecomp(){

	$('#labelshow').show();
	$('#addmultshow').show();
	$('#startfinishshow').show();
	$('#xvar').show();
	$('#yvar').show();
	$('#gridlinesshow').show();

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

	//3) separate them back out:
	for (var k = 0; k < list.length; k++) {
		tsxpoints[k] = list[k].tsxpoint;
		ypoints[k] = list[k].ypoint;
	}

	ctx.lineWidth = 1*scalefactor;
	ctx.strokeStyle = 'rgba(0,0,0,1)';
	ctx.rect(50*scalefactor,50*scalefactor,width-100*scalefactor,height-100*scalefactor);
	ctx.stroke();

	//x-axis title
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#xaxis').val(),width/2,height-10*scalefactor);

	//y-axis title
	if($('#yaxis').val() != "Y Axis Title"){
		var x, y;
		x=12*scalefactor;
		y=height/2;
		ctx.save();
		ctx.fillStyle = '#000000';
		ctx.font = "bold "+fontsize+"px Roboto";
		ctx.translate(x, y);
		ctx.rotate(-Math.PI/2);
		ctx.textAlign = "center";
		ctx.fillText($('#yaxis').val(), 0, 0);
		ctx.restore();
	}

	var xmin = Math.min.apply(null, tsxpoints);
	var xmax = Math.max.apply(null, tsxpoints);

	var minmaxstep = axisminmaxstep(xmin,xmax);
	var minxtick=minmaxstep[0];
	var maxxtick=minmaxstep[1];
	var xstep=minmaxstep[2];

	left = 60*scalefactor;
	right = width-60*scalefactor;
	gtop = 60*scalefactor;
	gbottom = height-60*scalefactor;

	horaxis(ctx,left,right,add(gbottom,10*scalefactor),minxtick,maxxtick,xstep);

	var pointsforminmax=[];
	for (var index in ypoints){
		pointsforminmax.push(ypoints[index]);
	}

	var abslowest = Math.min.apply(null, pointsforminmax);
	var abshighest = Math.max.apply(null, pointsforminmax);

	if($('#startfinish').is(":checked")){var startfinish="yes";} else {var startfinish = "no";}

	stlresponse=stl(tsxpoints,ypoints,seasons);
	trend = stlresponse[0];
	fitted = stlresponse[1];
	s = stlresponse[2];
	r = stlresponse[3];

	for (var index in trend){
		pointsforminmax.push(trend[index]);
	}

	for (var index in fitted){
		pointsforminmax.push(fitted[index]);
	}

	pointsforsminmax=[]
	for (var index in s){
		pointsforsminmax.push(s[index]);
	}

	pointsforrminmax=[]
	for (var index in r){
		pointsforrminmax.push(r[index]);
	}

	var ymin = Math.min.apply(null, pointsforminmax);
	var ymax = Math.max.apply(null, pointsforminmax);
	var smin = Math.min.apply(null, pointsforsminmax);
	var smax = Math.max.apply(null, pointsforsminmax);
	var rmin = Math.min.apply(null, pointsforrminmax);
	var rmax = Math.max.apply(null, pointsforrminmax);

	var minmaxstep = axisminmaxstep(ymin,ymax);
	var minytick=minmaxstep[0];
	var maxytick=minmaxstep[1];
	var ystep=minmaxstep[2];
	var minstick=Math.floor(smin/ystep)*ystep;
	var maxstick=Math.ceil(smax/ystep)*ystep;
	var minrtick=Math.floor(rmin/ystep)*ystep;
	var maxrtick=Math.ceil(rmax/ystep)*ystep;

	ysteps = (maxytick-minytick)/ystep;
	ssteps = Math.abs(minstick/ystep)+Math.abs(maxstick/ystep);
	rsteps = Math.abs(minrtick/ystep)+Math.abs(maxrtick/ystep);

	totalsteps = ysteps + ssteps + rsteps;

	gbottom = (height-160*scalefactor)*ysteps/totalsteps+gtop;

	vertaxis(ctx,gtop,gbottom,left-10*scalefactor,minytick,maxytick,ystep);

	ctx.lineWidth = 2*scalefactor;
	ctx.strokeStyle = 'rgba(0,200,0,1)';
	for (index in tsxpoints){
		xpixel=convertvaltopixel(tsxpoints[index],minxtick,maxxtick,left,right);
		ypixel=convertvaltopixel(fitted[index],maxytick,minytick,gtop,gbottom);
		if(index != 0){
			line(ctx,xpixel,ypixel,lastxpixel,lastypixel);
		}
		lastxpixel = xpixel;
		lastypixel = ypixel;
	}

	if($('#addmult option:selected').text()=="Multiplicative"){var multiplicative="yes";} else {var multiplicative = "no";}
	if($('#labels').is(":checked")){var labels="yes";} else {var labels = "no";}
	trendpts=[];
	for (index in tsxpoints){
		xpixel=convertvaltopixel(tsxpoints[index],minxtick,maxxtick,left,right);
		ypixel=convertvaltopixel(ypoints[index],maxytick,minytick,gtop,gbottom);
		trendpixel=convertvaltopixel(trend[index],maxytick,minytick,gtop,gbottom);
		trendpts.push(xpixel,trendpixel);
		if(startfinish=="yes" && (index==0 || index==tsxpoints.length-1)){
			ctx.textAlign="left";
			if(index==0){
				ctx.textAlign="right";
			}
			ctx.fillStyle = 'rgba(255,255,255,1)';
			ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel-2,trendpixel-2);
			ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel+2,trendpixel-2);
			ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel-2,trendpixel+2);
			ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel+2,trendpixel+2);
			ctx.fillStyle = 'rgba(0,0,255,1)';
			ctx.font = "bold 12px Roboto";
			ctx.fillText(parseFloat(trend[index].toPrecision(3)),xpixel,trendpixel);
		}
		if(index != 0){
			ctx.strokeStyle = 'rgba(0,0,0,1)';
			line(ctx,xpixel,ypixel,lastxpixel,lastypixel);
			ctx.lineWidth = 1*scalefactor;
		}
		if(labels == "yes"){
			ctx.fillStyle = 'rgba(0,0,255,1)';
			ctx.font = "10px Roboto";
			ctx.textAlign="left";
			ctx.fillText(parseInt(add(index,1)),add(add(xpixel,2),2),add(ypixel,4));
		}
		lasttrendpixel=trendpixel;
		lastfittedpixel=fitted;
		lastxpixel = xpixel;
		lastypixel = ypixel;
	}

	ctx.strokeStyle = 'rgba(0,0,255,1)';
	ctx.lineWidth = 2*scalefactor;
	drawSpline(ctx,trendpts,0.5)

	fontsize=12*scalefactor;

	ctx.strokeStyle = 'rgba(0,0,0,1)';
	line (ctx,left,gtop,left+20*scalefactor,gtop);
	ctx.fillStyle = 'rgba(0,0,0,1)';
	ctx.font = fontsize+"px Roboto";
	ctx.textAlign="left";
	ctx.fillText("Raw Data",left+25*scalefactor,gtop+5*scalefactor);

	gtop+=15*scalefactor;
	ctx.lineWidth = 3*scalefactor;
	ctx.strokeStyle = 'rgba(0,0,255,1)';
	line (ctx,left,gtop,left+20*scalefactor,gtop);
	ctx.fillStyle = 'rgba(0,0,0,1)';
	ctx.font = fontsize+"px Roboto";
	ctx.textAlign="left";
	ctx.fillText("Trend",left+25*scalefactor,gtop+5*scalefactor);

	gtop+=15*scalefactor;
	ctx.lineWidth = 2*scalefactor;
	ctx.strokeStyle = 'rgba(0,200,0,1)';
	line (ctx,left,gtop,left+20*scalefactor,gtop);
	ctx.fillStyle = 'rgba(0,0,0,1)';
	ctx.font = fontsize+"px Roboto";
	ctx.textAlign="left";
	ctx.fillText("Trend + Seasonal",left+25*scalefactor,gtop+5*scalefactor);

	ctx.lineWidth = 1*scalefactor;
	ctx.strokeStyle = 'rgba(0,0,0,1)';
	line(ctx,left-10*scalefactor,gbottom+10*scalefactor,right+10*scalefactor,gbottom+10*scalefactor);

	gtop = gbottom+20*scalefactor;
	gbottom = (height-160*scalefactor)*ssteps/totalsteps+gtop;

	rvertaxis(ctx,gtop,gbottom,right+10*scalefactor,minstick,maxstick,ystep);
	ctx.strokeStyle = 'rgba(0,0,0,0.3)';
	zero = convertvaltopixel(0,minstick,maxstick,gbottom,gtop);
	line(ctx,left,zero,right,zero)
	ctx.strokeStyle = 'rgba(255,100,0,1)';
	for (index in tsxpoints){
		xpixel=convertvaltopixel(tsxpoints[index],minxtick,maxxtick,left,right);
		ypixel=convertvaltopixel(s[index],maxstick,minstick,gtop,gbottom);
		if(index != 0){
			line(ctx,xpixel,ypixel,lastxpixel,lastypixel);
		}
		if(labels == "yes"){
			fontsize = 10*scalefactor;
			ctx.fillStyle = 'rgba(0,0,255,1)';
			ctx.font = fontsize+"px Roboto";
			ctx.textAlign="left";
			ctx.fillText(parseInt(add(index,1)),add(add(xpixel,2),2),add(ypixel,4));
		}
		lastxpixel = xpixel;
		lastypixel = ypixel;
	}
	line (ctx,left,gtop,left+20*scalefactor,gtop);
	ctx.fillStyle = 'rgba(0,0,0,1)';
	fontsize = 12*scalefactor;
	ctx.font = fontsize+"px Roboto";
	ctx.textAlign="left";
	ctx.fillText("Seasonal",left+25*scalefactor,gtop+5*scalefactor);

	ctx.strokeStyle = 'rgba(0,0,0,1)';
	line(ctx,left-10*scalefactor,gbottom+10*scalefactor,right+10*scalefactor,gbottom+10*scalefactor);

	gtop = gbottom+20*scalefactor;
	gbottom = (height-160*scalefactor)*rsteps/totalsteps+gtop;

	vertaxis(ctx,gtop,gbottom,left-10*scalefactor,minrtick,maxrtick,ystep);
	ctx.strokeStyle = 'rgba(0,0,0,0.3)';
	zero = convertvaltopixel(0,minrtick,maxrtick,gbottom,gtop);
	limit=(abshighest-abslowest)/10;
	lowlimit = convertvaltopixel(-limit,minrtick,maxrtick,gbottom,gtop);
	highlimit = convertvaltopixel(limit,minrtick,maxrtick,gbottom,gtop);
	line(ctx,left,zero,right,zero);
	if(limit<maxrtick){
		line(ctx,left,highlimit,right,highlimit);
	}
	if(limit>minrtick){
		line(ctx,left,lowlimit,right,lowlimit);
	}
	ctx.strokeStyle = 'rgba(255,0,0,1)';
	line (ctx,left,gtop,left+20*scalefactor,gtop);
	for (index in tsxpoints){
		xpixel=convertvaltopixel(tsxpoints[index],minxtick,maxxtick,left,right);
		ypixel=convertvaltopixel(r[index],maxrtick,minrtick,gtop,gbottom);
		if(index != 0){
			line(ctx,xpixel,ypixel,lastxpixel,lastypixel);
		}
		if(labels == "yes"){
			fontsize = 10*scalefactor;
			ctx.fillStyle = 'rgba(0,0,255,1)';
			ctx.font = fontsize+"px Roboto";
			ctx.textAlign="left";
			ctx.fillText(parseInt(add(index,1)),add(add(xpixel,2),2),add(ypixel,4));
		}
		lastxpixel = xpixel;
		lastypixel = ypixel;
	}
	fontsize = 12*scalefactor;
	ctx.fillStyle = 'rgba(0,0,0,1)';
	ctx.font = fontsize+"px Roboto";
	ctx.textAlign="left";
	ctx.fillText("Residual",left+25*scalefactor,gtop+5*scalefactor);

	labelgraph(ctx,width,height);

	var dataURL = canvas.toDataURL();
	return dataURL;
}


function stl(xpoints,ypoints,seasons){
	if($('#addmult option:selected').text()=="Multiplicative"){var multiplicative="yes";} else {var multiplicative = "no";}
	if (multiplicative=="yes"){
		for(index in ypoints){
			ypoints[index]=Math.log(ypoints[index]);
		}
	}
	T=[];
	S=[];
	raw=[];
	for (index in xpoints){
		xpoint=xpoints[index].toString();
		raw[xpoint]=ypoints[index];
		T[xpoint]=0;
		S[xpoint]=0;
	}
	n_l=nextodd(seasons);//next odd number after number in trend
	n_s=7;
	n_t=nextodd(1.5*seasons/(1-1.5/n_s));
	il=innerloop(xpoints,raw,T,n_s,n_l,n_t);
	T=il[0];
	S=il[1];
	il=innerloop(xpoints,raw,T,n_s,n_l,n_t);
	T=il[0];
	S=il[1];

	fitted=[];
	r=[];
	trend=[];
	s=[];
	if (multiplicative=="yes"){
		for(index in ypoints){
			ypoints[index]=Math.exp(ypoints[index]);
			ypoint=ypoints[index];
			xpoint=xpoints[index].toString();
			trend[index]=Math.exp(T[xpoint]);
			fitted[index]=Math.exp(T[xpoint]+S[xpoint]);
			s[index]=fitted[index]-trend[index];
			r[index]=ypoint-fitted[index];
		}
	} else {
		for(index in ypoints){
			ypoint=ypoints[index];
			xpoint=xpoints[index].toString();
			trend[index]=T[xpoint];
			fitted[index]=T[xpoint]+S[xpoint];
			s[index]=S[xpoint];
			r[index]=ypoint-fitted[index];
		}
	}
	return [trend,fitted,s,r];
}

function nextodd(n){
	n=Math.ceil(n);
	if(Math.floor(n/2)==n/2){n++;}
	return n;
}

function innerloop(xpoints,raw,T,n_s,n_l,n_t){

	detrended=[];
	for (index in xpoints){
		xpoint=xpoints[index].toString();
		detrended[xpoint]=raw[xpoint]-T[xpoint];
	}
	if(n_l==1){
		for(index in T){
			S[index]=0;
		}
		n_t=nextodd(xpoints.length/3);
	} else {
		cyclesubseries=[];
		for (index in xpoints){
			xpoint=xpoints[index];
			year=Math.floor(xpoint);
			season=(xpoint-year).toFixed(4);
			xpoint=xpoint.toString();
			if(cyclesubseries[season] === undefined){cyclesubseries[season]=[];}
			cyclesubseries[season][xpoint]=detrended[xpoint];
		}
		for (index in cyclesubseries){
			season=index;
			values=cyclesubseries[season];
			minkey=99999999;
			maxkey=0;
			forpoints=[];
			keys=[];
			vals=[]
			for (key in values){
				keys.push(key);
				vals.push(values[key])
				forpoints.push(key);
				if(key<minkey){minkey=key;}
				if(key>maxkey){maxkey=key;}
			}
			forpoints.push((parseFloat(maxkey)+1).toFixed(4));
			forpoints.push((minkey-1).toFixed(4));
			cyclesubseries[season]=loess(keys,vals,n_s,forpoints,75);
		}
		C=[];
		for (index in cyclesubseries){
			for (x in cyclesubseries[index]){
				C[parseFloat(x).toFixed(4)]=cyclesubseries[index][x];
			}

		}

		Ckeys=[];
		for (index in C){
			Ckeys.push(parseFloat(index).toFixed(4));
		}
		Ckeys.sort(function(a, b){return a-b});
		L=[];
		for (index in Ckeys){
			key=Ckeys[index];
			L[parseFloat(key).toFixed(4)]=C[key];
		}
		L=movingaverage(L,seasons);
		L=movingaverage(L,seasons);
		keys=[]
		vals=[];
		for (key in L){
			keys.push(key);
			vals.push(L[key]);
		}
		L=loess(keys,vals,n_l,keys,88);
		S=[];
		for (index in xpoints){
			xpoint=xpoints[index].toString();
			if(C[xpoint] === undefined || L[xpoint] === undefined){
				return "Error: with Data array key "+xpoint+" doesn't exist in C or L (stl)";
			} else {
				S[xpoint]=C[xpoint]-L[xpoint];
			}
		}
		S2=[];

		for (index in xpoints){
			xpoint=xpoints[index];
			year=Math.floor(xpoint);
			season=xpoint-year;
			if(S2[season] === undefined){S2[season]=[];}
			S2[season][xpoint]=S[xpoint.toString()];
		}

		S=[];

		for (index in S2){
			values = S2[index];
			total=0;
			i=0;
			for (index in values){
				total+=values[index];
				i++;
			}
			mean = total/i;
			for (index in values){
				value=values[index];
				S[index.toString()]=mean;
			}
		}

		Skeys=[];
		Svals=[];
		for (index in S){
			Skeys.push(parseFloat(index).toFixed(4));
			Svals[index]=S[index];
		}
		Skeys.sort(function(a, b){return a-b});

		S=[];
		for (index in Skeys){
			key=Skeys[index];
			S[parseFloat(key).toFixed(4)]=Svals[key];
		}
	}
	deseasonalised=[];
	deseasonalisedkey=[];
	deseasonalisedval=[];
	for (index in xpoints){
		xpoint=xpoints[index].toString();
		deseasonalisedkey.push(xpoint);
		deseasonalisedval.push(raw[xpoint]-S[xpoint]);
		deseasonalised[xpoint]=(raw[xpoint]-S[xpoint]);
	}

	T=loess(deseasonalisedkey,deseasonalisedval,n_t,deseasonalisedkey,123);
	return [T,S];
}

function movingaverage(array,length){
	i=Math.ceil((parseFloat(length)+1)/2-1);
	xs=[];
	ys=[];
	for (index in array){
		xs.push(index);
		ys.push(array[index]);
	}
	max=xs.length-i;
	cmms=[];
	while(i<max){
		a=i-(length-1)/2;
		b=i+(length-1)/2;
		if(a!=Math.floor(a)){
			//need to center
			cmm1=0;
			cmm2=0;
			z=0;
			while(z<length){
				cmm1+=ys[a+z-0.5];
				cmm1+=ys[a+z+0.5];
				z++;
			}
			cmm1/=length;
			cmm2/=length;
			cmm=(cmm1+cmm2)/2;
		} else {
			//already centered
			cmm=0;
			z=0;
			a=i-length/2;
			while(z<length){
				cmm+=ys[a+z+0.5];
				z++;
			}
			cmm/=length;
		}
		cmms[parseFloat(xs[i]).toFixed(4)]=cmm;
		i++;
	}
	return cmms;
}

function loess(xpoints,ypoints,nPts,xvals,row){
	row = row || "na";
	nPts=Math.min(xpoints.length,nPts);
	yreturn=[];
	for (index in xvals){
		currentx = parseFloat(xvals[index]);
		distances=[];
		i=0;
		for (index in xpoints){
			xpoint=parseFloat(xpoints[index]);
			distances[i]=Math.abs(xpoint-currentx);
			i++;
		}

		smallestndistances=[];
		for (index in distances){
			if(smallestndistances.length<nPts){
				smallestndistances.push(distances[index]);
			} else {
				smallestndistances.sort(function(a, b){return a-b});
				biggest=smallestndistances[smallestndistances.length-1];
				distance=distances[index];
				if(distance<biggest){
					smallestndistances.pop();
					smallestndistances.push(distance);
				}
			}
		}
		points=[];
		for (index in smallestndistances){
			distance=smallestndistances[index];
			point = distances.indexOf(distance);
			if (point > -1) {
				distances.splice(point, 1, "DELETED");
			}
			points.push(point);
		}

		distances=smallestndistances;
		distances.sort(function(a, b){return a-b});
		maxdis=distances[distances.length-1];
		if(nPts<=3){maxdis+=0.001;}
		weights=[];
		// work out the weights
		for (index in points){
			i=points[index];
			distance=distances[index];
			u=Math.abs(distance)/maxdis;
			weights[i]=Math.pow((1-Math.pow(u,3)),3);
		}

		SumWts = 0;
		SumWtX = 0;
		SumWtX2 = 0;
		SumWtY = 0;
		SumWtXY = 0;

		for(index in points){
			i=points[index];
			SumWts = SumWts + weights[i];
			SumWtX = SumWtX + parseFloat(xpoints[i]) * weights[i];
			SumWtX2 = SumWtX2 + Math.pow(parseFloat(xpoints[i]),2)	* weights[i];
			SumWtY = SumWtY + parseFloat(ypoints[i]) * weights[i];
			SumWtXY = SumWtXY + parseFloat(xpoints[i]) * parseFloat(ypoints[i]) * weights[i];
		}
		Denom = SumWts * SumWtX2 - Math.pow(SumWtX,2);
		if(Denom==0){console.log('oh dear - invalid denominator for LOESS row');return("Error: invalid denominator for LOESS row");}
		//calculate the regression coefficients, and finally the loess value
		WLRSlope = (SumWts * SumWtXY - SumWtX * SumWtY) / Denom;
		WLRIntercept = (SumWtX2 * SumWtY - SumWtX * SumWtXY) / Denom;
		yreturn[currentx.toFixed(4)] = WLRSlope * currentx + WLRIntercept;
	}
	return yreturn;
}

function getControlPoints(x0,y0,x1,y1,x2,y2,t){
    //  x0,y0,x1,y1 are the coordinates of the end (knot) pts of this segment
    //  x2,y2 is the next knot -- not connected here but needed to calculate p2
    //  p1 is the control point calculated here, from x1 back toward x0.
    //  p2 is the next control point, calculated here and returned to become the
    //  next segment's p1.
    //  t is the 'tension' which controls how far the control points spread.

    //  Scaling factors: distances from this knot to the previous and following knots.
    var d01=Math.sqrt(Math.pow(x1-x0,2)+Math.pow(y1-y0,2));
    var d12=Math.sqrt(Math.pow(x2-x1,2)+Math.pow(y2-y1,2));

    var fa=t*d01/(d01+d12);
    var fb=t-fa;

    var p1x=x1+fa*(x0-x2);
    var p1y=y1+fa*(y0-y2);

    var p2x=x1-fb*(x0-x2);
    var p2y=y1-fb*(y0-y2);

    return [p1x,p1y,p2x,p2y]
}

function drawSpline(ctx,pts,t){
    ctx.save();
    var cp=[];   // array of control points, as x0,y0,x1,y1,...
    var n=pts.length;

	// Draw an open curve, not connected at the ends
	for(var i=0;i<n-4;i+=2){
		cp=cp.concat(getControlPoints(pts[i],pts[i+1],pts[i+2],pts[i+3],pts[i+4],pts[i+5],t));
	}
	for(var i=2;i<pts.length-5;i+=2){
		ctx.beginPath();
		ctx.moveTo(pts[i],pts[i+1]);
		ctx.bezierCurveTo(cp[2*i-2],cp[2*i-1],cp[2*i],cp[2*i+1],pts[i+2],pts[i+3]);
		ctx.stroke();
		ctx.closePath();
	}
	//  For open curves the first and last arcs are simple quadratics.
	ctx.beginPath();
	ctx.moveTo(pts[0],pts[1]);
	ctx.quadraticCurveTo(cp[0],cp[1],pts[2],pts[3]);
	ctx.stroke();
	ctx.closePath();

	ctx.beginPath();
	ctx.moveTo(pts[n-2],pts[n-1]);
	ctx.quadraticCurveTo(cp[2*n-10],cp[2*n-9],pts[n-4],pts[n-3]);
	ctx.stroke();
	ctx.closePath();
    ctx.restore();
}
