var currentrerandteachstep = 'presample';
var currentrerandteachypoints = [];
var currentrerandteachopoints = [];
var currentrerandteachygroups = [];
var currentrerandteachsample = {};
var currentrerandteachdiffs = [];
var currentrerandteachsamplepoints = [];
var currentrerandspeed = 'stopped';
var lastxpixel = 0;
var lastypixel = 0;
var lastkey = 0;

function newrerandteachstep(){
	
	if(typeof timer !== "undefined"){
		clearTimeout(timer);
	}

	var canvas = document.getElementById('myCanvas');
    var ctx = canvas.getContext('2d');
	
	if(currentrerandspeed == 'stopped' && currentrerandteachstep!='presample'){
		var dataURL = canvas.toDataURL();
		return dataURL;
	}

	//set size
	var width = $('#width').val();
	var height = $('#height').val();

	ctx.canvas.width = width;
	ctx.canvas.height = height;

	ctx.fillStyle = "#ffffff";
	ctx.fillRect(0, 0, canvas.width, canvas.height);

	//get points
	var xpoints = $('#xvar').val().split(",");
	xpoints.pop();
	var ypoints = $('#yvar').val().split(",");
	ypoints.pop();

	//check for numeric value
	var points=[];
	var allpoints=[];
	var pointsremoved=[];
	var pointsforminmax=[];
	for (var index in xpoints){
		if($.isNumeric(xpoints[index])){
			points.push(index);
			allpoints.push(index);
			pointsforminmax.push(xpoints[index]);
		} else {
			pointsremoved.push(add(index,1));
		}
	}

	if(points.length==0){
		return 'Error: You must select a numeric variable for "numerical 1"';
	}

	if(ypoints.length>0){
		allydifferentgroups = split(allpoints,ypoints,2,2);
		if(typeof allydifferentgroups === 'object'){
			for (index in allydifferentgroups){
				group = index;
				depoints=allydifferentgroups[index];
				for (index in depoints){
					point=depoints[index];
					ypoints[point]=group;
				}

			}
		} else {
			return allydifferentgroups;
		}
	} else {
		return 'Error: you must select a variable with only 2 values for "category 1"';
	}

	if(pointsremoved.length!=0 && $('#removedpoints').is(":checked")){
		ctx.fillStyle = '#000000';
		fontsize = 13*scalefactor;
		ctx.font = fontsize+"px Roboto";
		ctx.textAlign="right";
		ctx.fillText("ID(s) of Points Removed: "+pointsremoved.join(", "),width-48*scalefactor,48*scalefactor);
	}

	var oypixel=height*0.3-60*scalefactor;
	var maxheight=height*0.3-160*scalefactor;
	var left=60*scalefactor;
	var right=width-60*scalefactor;

	//Original Data Title
	ctx.fillStyle = '#000000';
	fontsize = 20*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="left";
	ctx.fillText('Original Data',30*scalefactor,30*scalefactor);

	//This Randomisation Title
	ctx.fillStyle = '#000000';
	fontsize = 20*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="left";
	ctx.fillText('This Randomisation',30*scalefactor,height*0.3+30*scalefactor);

	//Re-randomisation distribution
	ctx.fillStyle = '#000000';
	fontsize = 20*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="left";
	ctx.fillText('Re-randomisation Distribution',30*scalefactor,height*0.6+30*scalefactor);

	xmin = Math.min.apply(null, pointsforminmax);
	xmax = Math.max.apply(null, pointsforminmax);
	if($.isNumeric($('#boxplotmin').val())){
		xmin=$('#boxplotmin').val();
	}
	if($.isNumeric($('#boxplotmax').val())){
		xmax=$('#boxplotmax').val();
	}

	//x-axis title
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#xaxis').val(),width/2,height*0.3-10*scalefactor);

	//y-axis title
	if($('#yaxis').val() != "Y Axis Title"){
		var x, y;
		x=20*scalefactor;
		y=height*0.15;
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

	//x-axis title
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#xaxis').val(),width/2,height*0.6-10*scalefactor);

	//y-axis title
	if($('#yaxis').val() != "Y Axis Title"){
		var x, y;
		x=20*scalefactor;
		y=height*0.45;
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

	var depoints=[];

	for (var index in allydifferentgroups){
		depoints[index]=[];
		thesepoints = allydifferentgroups[index];
		for (var p in thesepoints){
			zp = xpoints[thesepoints[p]];
			depoints[index].push(zp);
		}
	}

	medians=[];
	cnames=[];

	var i=0;
	for (var index in depoints){
		cnames[i] = index;
		medians[i] = median(depoints[index]);
		i++;
	}

	diff = parseFloat(Number(medians[0]-medians[1]).toPrecision(10));

	if(diff<0){
		diff=-diff;
		reverse=-1;
	} else {
		reverse=1;
	}
	odiff = diff;
	
	var minmaxstep = axisminmaxstep(xmin,xmax);
	var minxtick=minmaxstep[0];
	var maxxtick=minmaxstep[1];
	var xstep=minmaxstep[2];
	
	// set up axis for bootstrap
	steps=(maxxtick-minxtick)/xstep;
	
	offset=minxtick+xstep*Math.floor(steps/2);
	offset=-offset;
	offset=Math.floor(offset/xstep);
	offset=xstep*offset;
	bottomminxtick=minxtick+offset;
	bottommaxxtick=maxxtick+offset;
	
	if(bottommaxxtick<diff){
		maxxtick += Math.ceil((diff-bottommaxxtick)/xstep+1)*xstep;
		minxtick -= Math.ceil((diff-bottommaxxtick)/xstep+1)*xstep;
	}

	horaxis(ctx,left,right,add(oypixel,10*scalefactor),minxtick,maxxtick,xstep);
	horaxis(ctx,left,right,add(oypixel+height*0.3,10*scalefactor),minxtick,maxxtick,xstep);

	var alpha = 1-$('#trans').val()/100;

	colors = makecolors(alpha,ctx);

	for (var index in allydifferentgroups){
		plotdotplot(ctx,allydifferentgroups[index],xpoints,minxtick,maxxtick,oypixel,left,right,maxheight,colors,2,1);
		ctx.fillStyle = '#000000';
		fontsize = 15*scalefactor;
		ctx.font = "bold "+fontsize+"px Roboto";
		ctx.textAlign="right";
		ctx.fillText(index,right+10,oypixel-maxheight/2);
		oypixel = oypixel-maxheight;
	}
	
	ctx.lineWidth = 2*scalefactor;
	ctx.strokeStyle = 'rgb(0,0,255)';
	y = height*0.15+5*scalefactor;
	if(reverse==1){
		med1=medians[1];
		med2=medians[0];
	} else {
		med2=medians[1];
		med1=medians[0];
	}
	leftxpixel = convertvaltopixel(med1,minxtick,maxxtick,left,right);
	rightxpixel = convertvaltopixel(med2,minxtick,maxxtick,left,right);
	line(ctx,leftxpixel,y,rightxpixel,y);
	line(ctx,rightxpixel-5*scalefactor,y-5*scalefactor,rightxpixel,y);
	line(ctx,rightxpixel-5*scalefactor,add(y,5*scalefactor),rightxpixel,y);
	
	// Create this randomisation
	if(currentrerandteachstep=='presample'){
		currentrerandteachsamplepoints = allpoints.slice();
		currentrerandteachsample = {};
		currentrerandteachygroups = [];
		currentrerandteachypoints = ypoints.slice();
		shuffle(currentrerandteachypoints);
		currentrerandteachopoints = currentrerandteachypoints.slice();
		for (var index in allydifferentgroups){
			currentrerandteachsample[index]=[];
			currentrerandteachygroups.push(index);
		}
		currentrerandteachygroups.sort();
		if(currentrerandspeed != 'stopped'){
			currentrerandteachstep='sample';
		}
		lastkey = -1;
	}
	
	if(currentrerandteachstep=='sample' && (currentrerandspeed=='restfast' || currentrerandspeed=='restmedium' || currentrerandspeed=='restslow')){
		currentrerandteachsample = split(allpoints,currentrerandteachopoints,2,2);
		currentrerandteachstep = 'plotdifference';
	}
	
	if(currentrerandteachstep=='calcdifference' || currentrerandteachstep=='plotdifference'){
		$('#boxplot').prop('checked', true);
		// plot arrow on middle graph
		ctx.lineWidth = 2*scalefactor;
		ctx.strokeStyle = 'rgb(255,0,0)';
		y = height*0.45+5*scalefactor;
		group1=[];
		group2=[];
		for (index in points){
			point=points[index];
			xval=xpoints[point];
			group=currentrerandteachopoints[point];
			if(cnames[0]==group){
				group1.push(xval);
			} else {
				group2.push(xval);
			}
		}
		if(reverse==1){
			med1=median(group2);
			med2=median(group1);
		} else {
			med1=median(group1);
			med2=median(group2);
		}
		leftxpixel = convertvaltopixel(med1,minxtick,maxxtick,left,right);
		rightxpixel = convertvaltopixel(med2,minxtick,maxxtick,left,right);
		if(leftxpixel<rightxpixel){
			line(ctx,leftxpixel,y,rightxpixel,y);
			line(ctx,rightxpixel-5*scalefactor,y-5*scalefactor,rightxpixel,y);
			line(ctx,rightxpixel-5*scalefactor,add(y,5*scalefactor),rightxpixel,y);
		} else {
			line(ctx,leftxpixel,y,rightxpixel,y);
			line(ctx,rightxpixel+5*scalefactor,y-5*scalefactor,rightxpixel,y);
			line(ctx,rightxpixel+5*scalefactor,add(y,5*scalefactor),rightxpixel,y);
		}
	}
	
	
	if(currentrerandteachstep=='plotdifference'){	
		diff = med2-med1;
		currentrerandteachdiffs.push(diff);
		$('#rerandteachremaining').html($('#rerandteachremaining').html()-1);
	}
	
	// Add point to this sample
	if(currentrerandteachstep=='sample'){
		$('#boxplot').prop('checked', false);
		thispoint = currentrerandteachsamplepoints.shift();
		thisgroup = currentrerandteachypoints.shift();
		currentrerandteachsample[thisgroup].push(thispoint);
	}
	
	// graph this randomisation
	lastpoint = -1;
	var oypixel=height*0.6-60*scalefactor;
	for (var index in currentrerandteachsample){
		plotdotplot(ctx,currentrerandteachsample[index],xpoints,minxtick,maxxtick,oypixel,left,right,maxheight,colors,2,1);
		if(highestkey>lastpoint){
			lastpoint = highestkey;
			ypixel = lastypixel;
			if(ypixel<120){
				console.log(ypixel)
				return;
			}
		}
		ctx.fillStyle = '#000000';
		fontsize = 15*scalefactor;
		ctx.font = "bold "+fontsize+"px Roboto";
		ctx.textAlign="right";
		ctx.fillText(index,right+10,oypixel-maxheight/2);
		oypixel = oypixel-maxheight;
	}
	
	// draw dropdown line
	if(currentrerandteachstep=='sample'){
		xpixel = convertvaltopixel(xpoints[thispoint],minxtick,maxxtick,left,right);
		ctx.lineWidth = 2*scalefactor;
		ctx.strokeStyle = 'rgb(255,0,0)';
		ytop = height*0.3-85*scalefactor-maxheight/2+currentrerandteachygroups.indexOf(ypoints[thispoint])*maxheight;
		ybottom = ypixel-5*scalefactor;
		line(ctx,xpixel,ytop,xpixel,ybottom);
		line(ctx,xpixel-5*scalefactor,ybottom-5*scalefactor,xpixel,ybottom);
		line(ctx,xpixel+5*scalefactor,ybottom-5*scalefactor,xpixel,ybottom);
		if(currentrerandteachsamplepoints.length==0){
			currentrerandteachstep = 'calcdifference';
		}
	}

	if(reverse==1){
		title="Difference Between Medians (" + cnames[0] + " - " + cnames[1] + ")";
	} else {
		title="Difference Between Medians (" + cnames[1] + " - " + cnames[0] + ")";
	}

	//rerandomisation x-axis title
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText(title,width/2,height-10*scalefactor);

	
	// set up axis for bootstrap
	steps=(maxxtick-minxtick)/xstep;
	
	offset=minxtick+xstep*Math.floor(steps/2);
	offset=-offset;
	offset=Math.floor(offset/xstep);
	offset=xstep*offset;
	minxtick=minxtick+offset;
	maxxtick=maxxtick+offset;

	oypixel = height - 75*scalefactor;
	horaxis(ctx,left,right,add(oypixel,15*scalefactor),minxtick,maxxtick,xstep);

	maxheight=height*0.4-120*scalefactor;
	
	bspoints=[];
	i=0;
	while(i<currentrerandteachdiffs.length){
		bspoints.push(i);
		i++;
	}
	if(currentrerandteachstep=='finished'){
		diff = odiff;
		colors = makererandcolors(alpha,currentrerandteachdiffs,diff);	
	} else {
		colors = makeblankcolors(currentrerandteachdiffs.length,alpha);
	}
	$('#boxplot').prop('checked', false);
	$('#meandot').prop('checked', false);
	plotdotplot(ctx,bspoints,currentrerandteachdiffs,minxtick,maxxtick,oypixel,left,right,maxheight,colors,1,0);
	ypixel = lastypixel;
	
	if(currentrerandteachstep=='plotdifference'){
		// plot arrow on bottom graph
		ctx.lineWidth = 2*scalefactor;
		ctx.strokeStyle = 'rgb(255,0,0)';
		y = ypixel;
		offset=minxtick+xstep*Math.floor(steps/2);
		offset=-offset;
		offset=Math.floor(offset/xstep);
		offset=xstep*offset;
		diffpix=convertvaltopixel(diff,minxtick+offset,maxxtick+offset,left,right);
		zeropix=convertvaltopixel(0,minxtick+offset,maxxtick+offset,left,right);
		if(zeropix<diffpix){
			line(ctx,zeropix,y,diffpix,y);
			line(ctx,diffpix-5*scalefactor,y-5*scalefactor,diffpix,y);
			line(ctx,diffpix-5*scalefactor,add(y,5*scalefactor),diffpix,y);
		} else {
			line(ctx,zeropix,y,diffpix,y);
			line(ctx,diffpix+5*scalefactor,y-5*scalefactor,diffpix,y);
			line(ctx,diffpix+5*scalefactor,add(y,5*scalefactor),diffpix,y);
		}
	}
	
	if(currentrerandteachstep=='finished'){
		y=oypixel-3*scalefactor;
		ctx.lineWidth = 2*scalefactor;
		ctx.strokeStyle = 'rgb(0,0,255)';
		ctx.fillStyle = '#0000ff';
		fontsize = 11*scalefactor;
		ctx.font = "bold "+fontsize+"px Roboto";
		ctx.textAlign = "center";
		diffpix=convertvaltopixel(diff,minxtick,maxxtick,left,right);
		zeropix=convertvaltopixel(0,minxtick,maxxtick,left,right);
		line(ctx,zeropix,y,diffpix,y);
		line(ctx,diffpix-5*scalefactor,y-5*scalefactor,diffpix,y);
		line(ctx,diffpix-5*scalefactor,add(y,5*scalefactor),diffpix,y);
		ctx.fillText(diff,diffpix,add(y,15*scalefactor));
		
		p=0;
		for (index in currentrerandteachdiffs){
			value = currentrerandteachdiffs[index];
			if(value<diff){
			} else {
				p++;
			}
		}
		
		line(ctx,diffpix,y+5*scalefactor,diffpix,y-maxheight);
		ctx.textAlign = "left";
		ctx.fillText("p",diffpix+5*scalefactor,y-maxheight+10*scalefactor);
		ctx.fillText("= "+p+"/1000",diffpix+15*scalefactor,y-maxheight+10*scalefactor);
		ctx.fillText("= "+(p/1000),diffpix+15*scalefactor,y-maxheight+20*scalefactor);
		ctx.fillText("= "+(p/10)+"%",diffpix+15*scalefactor,y-maxheight+30*scalefactor);
	}
	
	if($('#rerandteachremaining').html()==0){
		currentrerandteachstep='finished';
		currentrerandteachsample = {};
	}
	
	if(currentrerandteachstep=='plotdifference'){
		currentrerandteachstep='presample';
	}
	
	if(currentrerandteachstep=='calcdifference'){
		currentrerandteachstep='plotdifference';
	}
	
	if(currentrerandspeed=='oneslow'){
		if(currentrerandteachstep=='presample'){
			animate = false;
			currentrerandspeed = 'stopped';
		} else {
			timer = setTimeout(updategraph,1000);
		}
	}
	
	if(currentrerandspeed=='onefast'){
		if(currentrerandteachstep=='presample'){
			animate = false;
			currentrerandspeed = 'stopped';
		} else {
			timer = setTimeout(updategraph,100);
		}
	}
	
	if(currentrerandspeed=='restslow'){
		timer = setTimeout(updategraph,200);
	}
	
	if(currentrerandspeed=='restmedium'){
		timer = setTimeout(updategraph,50);
	}
	
	if(currentrerandspeed=='restfast'){
		timer = setTimeout(updategraph,0);
	}

	labelgraph(ctx,width,height);

	var dataURL = canvas.toDataURL();
	return dataURL;
}

function newrerandteach(){
	$('#xvar').show();
	$('#yvar').show();
	$('#thicklinesshow').show();
	$('#transdiv').show();
	$('#sizediv').show();
	$('#greyscaleshow').show();
	$('#stackdotsshow').show();
	$('#pointsizename').html('Point Size:');
	$('#boxplot').prop('checked', true);
	$('#meandot').prop('checked', false);
	$('#highboxplot').prop('checked', false);
	$('#boxnowhisker').prop('checked', false);
	$('#boxnooutlier').prop('checked', false);
	$('#interval').prop('checked', false);
	$('#intervallim').prop('checked', false);
	$('#regression').prop('checked', false);
	$('#gridlinesshow').show();
	$('#removedpointsshow').show();
	$('#var1label').html("numerical 1:<br><small>required</small>");
	$('#var2label').html("category 1:<br><small>required</small>");
	$('#color').val($('#yvar').val());
	return newrerandteachstep();
}