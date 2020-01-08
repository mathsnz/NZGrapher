function newpairedexperiment(){
	$('#arrowsshow').show();
	$('#regshow').show();
	$('#sum').show();
	$('#invertshow').show();
	$('#stackdotsshow').show();
	$('#labelshow').show();
	$('#highboxplotshow').show();
	$('#boxnowhiskershow').show();
	$('#boxnooutliershow').show();
	$('#boxplotshow').show();
	$('#intervalshow').show();
	$('#sizediv').show();
	$('#meandotshow').show();
	$('#pointsizename').html('Point Size:');
	$('#transdiv').show();
	$('#xvar').show();
	$('#yvar').show();
	$('#color').show();
	$('#colorname').show();
	$('#greyscaleshow').show();
	$('#gridlinesshow').show();
	$('#removedpointsshow').show();
	$('#stripgraphshow').show();
	$('#var1label').html("Numerical 1:<br><small>required</small>");
	$('#var2label').html("Numerical 2:<br><small>required</small>");
	$('#var3label').html("");

	var canvas = document.getElementById('myCanvas');
    var ctx = canvas.getContext('2d');

	//set size
	var width = $('#width').val();
	var height = $('#height').val();

	ctx.canvas.width = width;
	ctx.canvas.height = height;

	ctx.fillStyle = "#ffffff";
	ctx.fillRect(0, 0, canvas.width, canvas.height);

	//get points
	var xpoints = (dataforselector[$('#xvar option:selected').text()]).slice();
	var ypoints = (dataforselector[$('#yvar option:selected').text()]).slice();

	//check for numeric value
	var apoints=[];
	var pointsremoved=[];
	for (var index in xpoints){
		if($.isNumeric(xpoints[index])){
			apoints.push(index);
		} else {
			pointsremoved.push(add(index,1));
		}
	}

	if(apoints.length==0){
		return 'Error: You must select a numeric variable for "Numerical 1"';
	}
	
	var points=[];
	var allpoints=[];
	var pointstoplot=[];
	var pointsforminmax=[];
	for (var i in apoints){
		index = apoints[i];
		if($.isNumeric(ypoints[index])){
			points.push(index);
			allpoints.push(index);
			pointsforminmax.push(ypoints[index]-xpoints[index]);
			pointstoplot[index] = ypoints[index]-xpoints[index];
		} else {
			pointsremoved.push(add(index,1));
		}
	}

	if(points.length==0){
		return 'Error: You must select a numeric variable for "Numerical 2"';
	}

	if(pointsremoved.length!=0 && $('#removedpoints').is(":checked")){
		ctx.fillStyle = '#000000';
		fontsize = 13*scalefactor;
		ctx.font = fontsize+"px Roboto";
		ctx.textAlign="right";
		ctx.fillText("ID(s) of Points Removed: "+pointsremoved.join(", "),width-48*scalefactor,48*scalefactor);
	}
	
	var oypixel=height-60*scalefactor;
	var maxheight=height-120*scalefactor;
	var left=90*scalefactor;
	var right=width-60*scalefactor;

	var alpha = 1-$('#trans').val()/100;
	var colors = makecolors(alpha,ctx);
	
	if($('#arrows').is(":checked")){
		var finalxpoints=[];
		var finalypoints=[];
		var pointsforminmax=[];
		for (var i in apoints){
			index = apoints[i];
			pointsforminmax.push(xpoints[index]);
			pointsforminmax.push(ypoints[index]);
			finalxpoints.push(xpoints[index]);
			finalypoints.push(ypoints[index]);
		}

		xmin = Math.min.apply(null, pointsforminmax);
		xmax = Math.max.apply(null, pointsforminmax);
		if($.isNumeric($('#boxplotmin').val())){
			xmin=$('#boxplotmin').val();
		}
		if($.isNumeric($('#boxplotmax').val())){
			xmax=$('#boxplotmax').val();
		}
		var minmaxstep = axisminmaxstep(xmin,xmax);
		var minxtick=minmaxstep[0];
		var maxxtick=minmaxstep[1];
		var xstep=minmaxstep[2];
		
		ctx.strokeStyle = '#000000';
		horaxis(ctx,left,right,add(oypixel,10*scalefactor),minxtick,maxxtick,xstep);
		
		var topypix=oypixel-3*maxheight/4;
		var bottomypix=oypixel-maxheight/4;
		
		var rad = $('#size').val()/2*scalefactor;
		if($('#labels').is(":checked")){var labels="yes";} else {var labels = "no";}
			
		for (var i in points){
			index = points[i];
			topxpixel = convertvaltopixel(xpoints[index],minxtick,maxxtick,left,right);
			bottomxpixel = convertvaltopixel(ypoints[index],minxtick,maxxtick,left,right);
			ctx.strokeStyle = colors[index];
			ctx.fillStyle = colors[index];
			ctx.beginPath();
			ctx.arc(topxpixel,topypix,rad,0,2*Math.PI);
			ctx.stroke();
			ctx.beginPath();
			ctx.arc(bottomxpixel,bottomypix,rad,0,2*Math.PI);
			ctx.stroke();
			drawArrow(ctx,topxpixel,topypix+rad,bottomxpixel,bottomypix-rad,5*scalefactor)
			if(labels == "yes"){
				ctx.fillStyle = 'rgba(0,0,255,1)';
				fontsize = 10*scalefactor;
				ctx.font = fontsize+"px Roboto";
				ctx.textAlign="left";
				ctx.fillText(parseInt(add(index,1)),add(add(topxpixel,rad),2*scalefactor),add(topypix,4*scalefactor));
				ctx.fillText(parseInt(add(index,1)),add(add(bottomxpixel,rad),2*scalefactor),add(bottomypix,4*scalefactor));
			}
			$('#graphmap').append('<area shape="circle" coords="'+(topxpixel/scalefactor)+','+(topypix/scalefactor)+','+(rad/scalefactor)+'" alt="'+parseInt(add(index,1))+'" desc="Point ID: '+parseInt(add(index,1))+'<br>'+$('#xaxis').val()+': '+xpoints[index]+'<br>'+$('#yaxis').val()+': '+ypoints[index]+'">');
			$('#graphmap').append('<area shape="circle" coords="'+(bottomxpixel/scalefactor)+','+(bottomypix/scalefactor)+','+(rad/scalefactor)+'" alt="'+parseInt(add(index,1))+'" desc="Point ID: '+parseInt(add(index,1))+'<br>'+$('#xaxis').val()+': '+xpoints[index]+'<br>'+$('#yaxis').val()+': '+ypoints[index]+'">');
		}

		if($('#regression').is(":checked")){			
			ctx.fillStyle = '#000000';
			fontsize = 15*scalefactor;
			ctx.font = "bold "+fontsize+"px Roboto";
			ctx.textAlign="left";
			var ypix=oypixel-3*maxheight/4;
			ctx.fillText($('#xaxis').val(),left-60*scalefactor,ypix-60*scalefactor);
			var ypix=oypixel-maxheight/4;
			ctx.fillText($('#yaxis').val(),left-60*scalefactor,ypix-60*scalefactor);
			
			ctx.fillStyle = 'rgba(255,0,0,1)';
			fontsize = 11*scalefactor;
			ctx.font = fontsize+"px Roboto";
			ctx.textAlign="left";
			
			thisvalues = finalxpoints;
			var minval = Math.min.apply(null, thisvalues);
			var lq = lowerquartile(thisvalues);
			var med = median(thisvalues);
			var mean = calculatemean(thisvalues);
			var uq = upperquartile(thisvalues);
			var maxval = Math.max.apply(null, thisvalues);
			var minnooutliersval = minnooutliers(thisvalues,lq,uq);
			var maxnooutliersval = maxnooutliers(thisvalues,lq,uq);
			var sd = standarddeviation(thisvalues);
			var num = thisvalues.length;
			var ypix=oypixel-3*maxheight/4;
			ctx.fillText('min: '+minval,left-60*scalefactor,ypix-44*scalefactor);
			ctx.fillText('lq: '+lq,left-60*scalefactor,ypix-33*scalefactor);
			ctx.fillText('med: '+med,left-60*scalefactor,ypix-22*scalefactor);
			ctx.fillText('mean: '+mean,left-60*scalefactor,ypix-11*scalefactor);
			ctx.fillText('uq: '+uq,left-60*scalefactor,ypix);
			ctx.fillText('max: '+maxval,left-60*scalefactor,ypix+11*scalefactor);
			ctx.fillText('sd: '+sd,left-60*scalefactor,ypix+22*scalefactor);
			ctx.fillText('num: '+num,left-60*scalefactor,ypix+33*scalefactor);
			
			thisvalues = finalypoints;
			var minval = Math.min.apply(null, thisvalues);
			var lq = lowerquartile(thisvalues);
			var med = median(thisvalues);
			var mean = calculatemean(thisvalues);
			var uq = upperquartile(thisvalues);
			var maxval = Math.max.apply(null, thisvalues);
			var minnooutliersval = minnooutliers(thisvalues,lq,uq);
			var maxnooutliersval = maxnooutliers(thisvalues,lq,uq);
			var sd = standarddeviation(thisvalues);
			var num = thisvalues.length;
			var ypix=oypixel-maxheight/4;
			ctx.fillText('min: '+minval,left-60*scalefactor,ypix-44*scalefactor);
			ctx.fillText('lq: '+lq,left-60*scalefactor,ypix-33*scalefactor);
			ctx.fillText('med: '+med,left-60*scalefactor,ypix-22*scalefactor);
			ctx.fillText('mean: '+mean,left-60*scalefactor,ypix-11*scalefactor);
			ctx.fillText('uq: '+uq,left-60*scalefactor,ypix);
			ctx.fillText('max: '+maxval,left-60*scalefactor,ypix+11*scalefactor);
			ctx.fillText('sd: '+sd,left-60*scalefactor,ypix+22*scalefactor);
			ctx.fillText('num: '+num,left-60*scalefactor,ypix+33*scalefactor);
		} else {
			ctx.fillStyle = '#000000';
			fontsize = 15*scalefactor;
			ctx.font = "bold "+fontsize+"px Roboto";
			ctx.textAlign="left";
			var ypix=oypixel-3*maxheight/4;
			ctx.fillText($('#xaxis').val(),left-60*scalefactor,ypix+5*scalefactor);
			var ypix=oypixel-maxheight/4;
			ctx.fillText($('#yaxis').val(),left-60*scalefactor,ypix+5*scalefactor);
		}
		
	} else {

		xmin = Math.min.apply(null, pointsforminmax);
		xmax = Math.max.apply(null, pointsforminmax);
		if(xmin==xmax){
			xmin--;
			xmax++;
		}
		if($.isNumeric($('#boxplotmin').val())){
			xmin=$('#boxplotmin').val();
		}
		if($.isNumeric($('#boxplotmax').val())){
			xmax=$('#boxplotmax').val();
		}
		var minmaxstep = axisminmaxstep(xmin,xmax);
		var minxtick=minmaxstep[0];
		var maxxtick=minmaxstep[1];
		var xstep=minmaxstep[2];
		
		ctx.strokeStyle = '#000000';
		horaxis(ctx,left,right,add(oypixel,10*scalefactor),minxtick,maxxtick,xstep);
		plotdotplot(ctx,points,pointstoplot,minxtick,maxxtick,oypixel,left,right,maxheight,colors,2,'Difference');

		//x-axis title
		ctx.fillStyle = '#000000';
		fontsize = 15*scalefactor;
		ctx.font = "bold "+fontsize+"px Roboto";
		ctx.textAlign="center";
		ctx.fillText("Difference ("+$('#yaxis').val()+" - "+$('#xaxis').val()+")",width/2,height-10*scalefactor);
	
	}
	
	//graph title
	ctx.fillStyle = '#000000';
	fontsize = 20*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#title').val(),width/2,30*scalefactor);

	labelgraph(ctx,width,height);
	if($('#invert').is(":checked")){
		invert(ctx)
	}

	var dataURL = canvas.toDataURL();
	return dataURL;
}

function drawArrow(ctx, fromx, fromy, tox, toy, radius) {
	var x_center = tox;
	var y_center = toy;

	var angle;
	var x;
	var y;

	ctx.beginPath();

	angle = Math.atan2(toy - fromy, tox - fromx)
	x_center = -radius * Math.cos(angle) + x_center;
	y_center = -radius * Math.sin(angle) + y_center;
	
	x = radius * Math.cos(angle) + x_center;
	y = radius * Math.sin(angle) + y_center;

	ctx.moveTo(x, y);

	angle += (1.0/3.0) * (2 * Math.PI)
	x = radius * Math.cos(angle) + x_center;
	y = radius * Math.sin(angle) + y_center;

	ctx.lineTo(x, y);

	angle += (1.0/3.0) * (2 * Math.PI)
	x = radius *Math.cos(angle) + x_center;
	y = radius *Math.sin(angle) + y_center;

	ctx.lineTo(x, y);
	ctx.closePath();
	ctx.fill();
	
	angle = Math.atan2(toy - fromy, tox - fromx)
	x = -0.5*radius * Math.cos(angle) + x_center;
	y = -0.5*radius * Math.sin(angle) + y_center;
	
	line(ctx,fromx,fromy,x,y);
}

function newbootstrap(){
	$('#regshow').show();
	$('#sum').show();
	$('#invertshow').show();
	$('#stackdotsshow').show();
	$('#labelshow').show();
	$('#intervalshow').show();
	$('#sizediv').show();
	$('#pointsizename').html('Point Size:');
	$('#transdiv').show();
	$('#xvar').show();
	$('#greyscaleshow').show();
	$('#gridlinesshow').show();
	$('#removedpointsshow').show();
	$('#btypeshow').show();
	$('#var1label').html("Numerical 1:<br><small>required</small>");
	$('#var2label').html("");
	$('#var3label').html("");

	var canvas = document.getElementById('myCanvas');
    var ctx = canvas.getContext('2d');

	//set size
	var width = $('#width').val();
	var height = $('#height').val();

	ctx.canvas.width = width;
	ctx.canvas.height = height;

	ctx.fillStyle = "#ffffff";
	ctx.fillRect(0, 0, canvas.width, canvas.height);

	//get points
	var xpoints = (dataforselector[$('#xvar option:selected').text()]).slice();
	
	//check for numeric value
	var points=[];
	var pointsremoved=[];
	var pointsforminmax=[];
	for (var index in xpoints){
		if($.isNumeric(xpoints[index])){
			points.push(index);
			pointsforminmax.push(xpoints[index]);
		} else {
			pointsremoved.push(add(index,1));
		}
	}

	if(points.length==0){
		return 'Error: You must select a numeric variable for "Numerical 1"';
	}

	if(pointsremoved.length!=0 && $('#removedpoints').is(":checked")){
		ctx.fillStyle = '#000000';
		fontsize = 13*scalefactor;
		ctx.font = fontsize+"px Roboto";
		ctx.textAlign="right";
		ctx.fillText("ID(s) of Points Removed: "+pointsremoved.join(", "),width-48*scalefactor,48*scalefactor);
	}
	
	var oypixel=height/2.5-60*scalefactor;
	var maxheight=height/2.5-120*scalefactor;
	var left=90*scalefactor;
	var right=width-60*scalefactor;

	xmin = Math.min.apply(null, pointsforminmax);
	xmax = Math.max.apply(null, pointsforminmax);
	if($.isNumeric($('#boxplotmin').val())){
		xmin=$('#boxplotmin').val();
	}
	if($.isNumeric($('#boxplotmax').val())){
		xmax=$('#boxplotmax').val();
	}
	var minmaxstep = axisminmaxstep(xmin,xmax);
	var minxtick=minmaxstep[0];
	var maxxtick=minmaxstep[1];
	var xstep=minmaxstep[2];
	
	var alpha = 1-$('#trans').val()/100;
	var colors = makeblankcolors(xpoints.length,alpha);
	
	$('#boxplot').prop('checked',false);
	$('#highboxplot').prop('checked',false);
	$('#boxnowhisker').prop('checked',false);
	$('#boxnooutlier').prop('checked',false);
	$('#meandot').prop('checked',false);
	
	if($('#btype').val()=='Median' || $('#btype').val()=='IQR'){
		$('#boxplot').prop('checked',true);
	} else if($('#btype').val()=='Mean' || $('#btype').val()=='Standard Deviation'){
		$('#meandot').prop('checked',true);
	}
	
	ctx.strokeStyle = '#000000';
	horaxis(ctx,left,right,add(oypixel,10*scalefactor),minxtick,maxxtick,xstep);
	plotdotplot(ctx,points,xpoints,minxtick,maxxtick,oypixel,left,right,maxheight,colors,2,1);
	
	if($('#btype').val()=='Median'){
		ctx.strokeStyle = '#ff0000';
		ctx.lineWidth = 3*scalefactor;
		var med = median(pointsforminmax);
		var medgraph = convertvaltopixel(med,minxtick,maxxtick,left,right);
		line(ctx,medgraph,oypixel,medgraph,oypixel-maxheight*0.2)
		dropline = med;
	} else if ($('#btype').val()=='IQR'){
		ctx.strokeStyle = '#ff0000';
		ctx.lineWidth = 3*scalefactor;
		var lq = lowerquartile(pointsforminmax);
		var uq = upperquartile(pointsforminmax);
		var lqgraph = convertvaltopixel(lq,minxtick,maxxtick,left,right);
		var uqgraph = convertvaltopixel(uq,minxtick,maxxtick,left,right);
		line(ctx,lqgraph,oypixel-maxheight*0.1,uqgraph,oypixel-maxheight*0.1);
		dropline = uq - lq;
		shift=((dropline-(minxtick+maxxtick)/2)/xstep).toFixed(0)*xstep;
		minxtick = minxtick+shift;
		maxxtick = maxxtick+shift;
	} else if($('#btype').val()=='Mean'){
		var mean = calculatemean(pointsforminmax);
		dropline = mean;
	} else if ($('#btype').val()=='Standard Deviation'){
		ctx.strokeStyle = '#ff0000';
		ctx.lineWidth = 3*scalefactor;
		var mean = calculatemean(pointsforminmax);
		var sd = standarddeviation(pointsforminmax);	
		var bottomsdgraph = convertvaltopixel(add(mean,sd),minxtick,maxxtick,left,right);
		var topsdgraph = convertvaltopixel(add(mean,-sd),minxtick,maxxtick,left,right);
		line(ctx,bottomsdgraph,oypixel-5*scalefactor,topsdgraph,oypixel-5*scalefactor);
		console.log(bottomsdgraph);
		dropline = sd;
		shift=((dropline-(minxtick+maxxtick)/2)/xstep).toFixed(0)*xstep;
		minxtick = minxtick+shift;
		maxxtick = maxxtick+shift;
	}
	
	var oypixel=height-90*scalefactor;
	var maxheight=height*0.6-120*scalefactor;
	
	horaxis(ctx,left,right,add(oypixel,40*scalefactor),minxtick,maxxtick,xstep);
	
	bootstrapvals=[];
	num=points.length;
	b=0;
	while(b<1000){
		thisbootstrap=[];
		for (index in points){
			sel=randint(0,num-1);
			point=points[sel];
			xval=xpoints[point];
			thisbootstrap.push(xval);
		}
		if($('#btype').val()=='Median'){
			val = median(thisbootstrap);
		} else if ($('#btype').val()=='IQR'){
			lq = lowerquartile(thisbootstrap);
			uq = upperquartile(thisbootstrap);
			val = uq-lq;
		} else if($('#btype').val()=='Mean'){
			val = calculatemean(thisbootstrap);
		} else if ($('#btype').val()=='Standard Deviation'){
			val = standarddeviation(thisbootstrap);	
		}
		val = parseFloat(Number(val).toPrecision(10));
		bootstrapvals.push(val);
		b++;
	}

	colors = makebscolors(1000,alpha);

	$('#boxplot').prop('checked', false);
	$('#meandot').prop('checked', false);

	bspoints=[];
	i=0;
	while(i<1000){
		bspoints.push(i);
		i++;
	}

	$('#boxplot').prop('checked', false);
	$('#meandot').prop('checked', false);

	if($('#labels').is(":checked")){var waslabels="yes";} else {var waslabels = "no";}
	if($('#regression').is(":checked")){var wasreg="yes";} else {var wasreg = "no";}
	if($('#interval').is(":checked")){var wasint="yes";} else {var wasint = "no";}
	if($('#intervallim').is(":checked")){var wasintlim="yes";} else {var wasintlim = "no";}
	$('#labels')[0].checked=false;
	$('#regression')[0].checked=false;
	$('#interval')[0].checked=false;
	$('#intervallim')[0].checked=false;
	plotdotplot(ctx,bspoints,bootstrapvals,minxtick,maxxtick,oypixel,left,right,maxheight,colors,1,0);
	if(waslabels=="yes"){$('#labels')[0].checked=true;}
	if(wasreg=="yes"){$('#regression')[0].checked=true;}
	if(wasint=="yes"){$('#interval')[0].checked=true;}
	if(wasintlim=="yes"){$('#intervallim')[0].checked=true;}

	bootstrapvals.sort(function(a, b){return a-b});
	
	y=oypixel-3*scalefactor;
	ctx.lineWidth = 2*scalefactor;
	ctx.strokeStyle = 'rgb(0,0,255)';
	ctx.fillStyle = '#0000ff';
	fontsize = 11*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign = "center";
	droppix=convertvaltopixel(dropline,minxtick,maxxtick,left,right);
	ctx.strokeStyle = 'rgb(255,0,0)';
	ctx.fillStyle = '#ff0000';
	line(ctx,droppix,add(y,-maxheight),droppix,y);
	ctx.fillText(dropline,droppix,add(y,-maxheight-5*scalefactor));
	ctx.strokeStyle = 'rgb(0,0,255)';
	ctx.fillStyle = '#0000ff';
	intervalmin=bootstrapvals[25];
	intervalminpix=convertvaltopixel(intervalmin,minxtick,maxxtick,left,right);
	intervalmax=bootstrapvals[974];
	intervalmaxpix=convertvaltopixel(intervalmax,minxtick,maxxtick,left,right);
	ctx.textAlign = "right";
	line(ctx,intervalminpix,add(y,18*scalefactor),intervalminpix,y-20*scalefactor);
	ctx.fillText(intervalmin,intervalminpix,add(y,30*scalefactor));
	ctx.textAlign = "left";
	line(ctx,intervalmaxpix,add(y,18*scalefactor),intervalmaxpix,y-20*scalefactor);
	ctx.fillText(intervalmax,intervalmaxpix,add(y,30*scalefactor));
	y=y-15*scalefactor;
	ctx.lineWidth = 10*scalefactor;
	line(ctx,intervalminpix,y,intervalmaxpix,y);
	
	//graph title
	ctx.fillStyle = '#000000';
	fontsize = 20*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#title').val(),width/2,30*scalefactor);

	//x-axis title
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#xaxis').val(),width/2,height/2.5-10*scalefactor);

	//x-axis title
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText('Bootstrap - '+$('#btype').val(),width/2,height-10*scalefactor);

	labelgraph(ctx,width,height);
	if($('#invert').is(":checked")){
		invert(ctx)
	}

	var dataURL = canvas.toDataURL();
	return dataURL;
	
}