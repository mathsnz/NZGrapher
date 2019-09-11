function newrerandmedian(){
	return rerand('median');
}

function newrerandmean(){
	return rerand('mean');
}

function rerand(mm){
	$('#xvar').show();
	$('#yvar').show();
	$('#thicklinesshow').show();
	$('#labelshow').show();
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
	$('#stripgraphshow').show();
	$('#var1label').html("numerical 1:<br><small>required</small>");
	$('#var2label').html("category 1:<br><small>required</small>");

	if(mm=='mean'){
		$('#boxplot').prop('checked', false);
		$('#meandot').prop('checked', true);
	}

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
			allygroups = Object.keys(allydifferentgroups);
			allygroups.sort(sortorder).reverse();
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

	var oypixel=height*0.5-60*scalefactor;
	var maxheight=height*0.25-60*scalefactor;
	var left=60*scalefactor;
	var right=width-60*scalefactor;

	xmin = Math.min.apply(null, pointsforminmax);
	xmax = Math.max.apply(null, pointsforminmax);
	if($.isNumeric($('#boxplotmin').val())){
		xmin=$('#boxplotmin').val();
	}
	if($.isNumeric($('#boxplotmax').val())){
		xmax=$('#boxplotmax').val();
	}
	
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
	ctx.fillText($('#xaxis').val(),width/2,height*0.5-10*scalefactor);

	//y-axis title
	if($('#yaxis').val() != "Y Axis Title"){
		var x, y;
		x=20*scalefactor;
		y=height/4;
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
		if(mm=='median'){
			medians[i] = median(depoints[index]);
		} else {
			medians[i] = calculatemean(depoints[index]);
		}
		i++;
	}

	diff = parseFloat(Number(medians[0]-medians[1]).toPrecision(10));

	if(diff<0){
		diff=-diff;
		reverse=-1;
	} else {
		reverse=1;
	}
	
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
		console.log(diff);
		maxxtick += Math.ceil((diff-bottommaxxtick)/xstep+1)*xstep;
		minxtick -= Math.ceil((diff-bottommaxxtick)/xstep+1)*xstep;
	}

	horaxis(ctx,left,right,add(oypixel,10*scalefactor),minxtick,maxxtick,xstep);

	var alpha = 1-$('#trans').val()/100;

	colors = makeblankcolors(xpoints.length,alpha);

	for (var index in allydifferentgroups){
		plotdotplot(ctx,allydifferentgroups[index],xpoints,minxtick,maxxtick,oypixel,left,right,maxheight,colors,2,1);
		ctx.fillStyle = '#000000';
		fontsize = 15*scalefactor;
		ctx.font = "bold "+fontsize+"px Roboto";
		ctx.textAlign="right";
		ctx.fillText(index,right+10,oypixel-maxheight/2);
		oypixel = oypixel-maxheight;
	}

	if(mm=='median'){
		if(reverse==1){
			title="Difference Between Medians (" + cnames[0] + " - " + cnames[1] + ")";
		} else {
			title="Difference Between Medians (" + cnames[1] + " - " + cnames[0] + ")";
		}
	} else {
		if(reverse==1){
			title="Difference Between Means (" + cnames[0] + " - " + cnames[1] + ")";
		} else {
			title="Difference Between Means (" + cnames[1] + " - " + cnames[0] + ")";
		}
	}

	//rerandomisation x-axis title
	ctx.fillStyle = '#000000';
	fontsize = 15*scalefactor;
	ctx.font = "bold "+fontsize+"px Roboto";
	ctx.textAlign="center";
	ctx.fillText(title,width/2,height-10*scalefactor);

	// create the rerandomisation

	rerandomiseddifs=[];
	num=points.length;
	b=0;
	while(b<1000){
		group1=[];
		group2=[];
		ypointsforthis = ypoints.slice();
		shuffle(ypointsforthis);
		for (index in points){
			point=points[index];
			xval=xpoints[point];
			group=ypointsforthis[point];
			if(cnames[0]==group){
				group1.push(xval);
			} else {
				group2.push(xval);
			}
		}
		if(mm=='median'){
			med1=median(group1);
			med2=median(group2);
		} else {
			med1=calculatemean(group1);
			med2=calculatemean(group2);

		}
		dif=(med1-med2)*reverse;
		dif = parseFloat(Number(dif).toPrecision(10));
		rerandomiseddifs.push(dif);
		b++;
	}

	colors = makererandcolors(alpha,rerandomiseddifs,diff);

	$('#boxplot').prop('checked', false);
	$('#meandot').prop('checked', false);

	bspoints=[];
	i=0;
	while(i<1000){
		bspoints.push(i);
		i++;
	}
	
	rerandomiseddifsforsort = rerandomiseddifs.slice();
	rerandomiseddifsforsort.sort(function(a, b){return a-b});
	
	// set up axis for bootstrap
	steps=(maxxtick-minxtick)/xstep;
	
	offset=minxtick+xstep*Math.floor(steps/2);
	offset=-offset;
	offset=Math.floor(offset/xstep);
	offset=xstep*offset;
	minxtick=minxtick+offset;
	maxxtick=maxxtick+offset;

	oypixel = height - 90*scalefactor;
	horaxis(ctx,left,right,add(oypixel,30*scalefactor),minxtick,maxxtick,xstep);

	maxheight=height*0.5-100*scalefactor;

	if($('#labels').is(":checked")){var waslabels="yes";} else {var waslabels = "no";}
	$('#labels')[0].checked=false;
	plotdotplot(ctx,bspoints,rerandomiseddifs,minxtick,maxxtick,oypixel,left,right,maxheight,colors,1,0);
	if(waslabels=="yes"){$('#labels')[0].checked=true;}
	
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
	for (index in rerandomiseddifs){
		value = rerandomiseddifs[index];
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

	labelgraph(ctx,width,height);

	var dataURL = canvas.toDataURL();
	return dataURL;
}

function shuffle(array) {
  array.sort(() => Math.random() - 0.5);
}

function makererandcolors(alpha,rerandomiseddifs,odifference){
	var colors = [];
	i=0;
	for (index in rerandomiseddifs){
		value = rerandomiseddifs[index];
		if(value<odifference){
			color = 'rgba(80,80,80,'+alpha*0.4+')';
		} else {
			color = 'rgba(80,80,80,'+alpha+')';
		}
		colors.push(color);
	}
	return colors;
}
