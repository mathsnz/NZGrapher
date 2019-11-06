function newbargraphf(){
	$('#invertshow').show();
	$('#regshow').show();
	$('#sum').show();
	$('#xvar').show();
	$('#yvar').show();
	$('#zvar').show();
	$('#color').show();
	$('#greyscaleshow').show();
	$('#gridlinesshow').show();
	$('#removedpointsshow').show();
	$('#var1label').html("Category:<br><small>required</small>");
	$('#var2label').html("Frequency:<br><small>required</small>");
	$('#var3label').html("Split:<br><small>optional</small>");

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
	var zpoints = $('#zvar').val().split(",");
	zpoints.pop();

	//check x points for number of groups
	if(xpoints.length==0){
		return 'Error: You must select a variable for "Category"';
	}

	//check for numeric value
	var points=[];
	var allpoints=[];
	var pointsremoved=[];
	var pointsforminmax=[];
	for (var index in ypoints){
		if($.isNumeric(ypoints[index])){
			points.push(index);
			allpoints.push(index);
			pointsforminmax.push(ypoints[index]);
		} else {
			pointsremoved.push(add(index,1));
		}
	}

	if(points.length==0){
		return 'Error: You must select a numeric variable for "Frequency"';
	}
	
	xdifferentgroups = split(points,xpoints,4,'"Category"');
	if(typeof xdifferentgroups !== 'object'){
		return xdifferentgroups;
	}

	if(pointsremoved.length!=0 && $('#removedpoints').is(":checked")){
		ctx.fillStyle = '#000000';
		fontsize = 13*scalefactor;
		ctx.font = fontsize+"px Roboto";
		ctx.textAlign="right";
		ctx.fillText("ID(s) of Points Removed: "+pointsremoved.join(", "),width-48*scalefactor,48*scalefactor);
	}

	var oypixel=60*scalefactor;
	var maxheight=height-60*scalefactor;
	var left=20*scalefactor;
	var right=width-60*scalefactor;

	ymin = 0;
	ymax = Math.max.apply(null, pointsforminmax);
	var minmaxstep = axisminmaxstep(ymin,ymax);
	var minytick=minmaxstep[0];
	var maxytick=minmaxstep[1];
	var ystep=minmaxstep[2];

	var alpha = 1-$('#trans').val()/100;
	var colors = makecolors(alpha,ctx);
	

	if(zpoints.length>0){
		zdifferentgroups = split(points,zpoints,4,'"Split"');
		if(typeof zdifferentgroups === 'object'){
			zgroups = Object.keys(zdifferentgroups);
			zgroups.sort(sortorder);
			thistop=60*scalefactor;
			eachheight=maxheight/zgroups.length;
			for (index in zgroups){
				group = zgroups[index];
				points = zdifferentgroups[group];

				thisbottom = add(thistop,eachheight);

				ctx.fillStyle = '#000000';
				fontsize = 15*scalefactor;
				ctx.font = "bold "+fontsize+"px Roboto";
				ctx.textAlign="left";
				ctx.fillText(group,left,thistop-15*scalefactor);
				
				var error = plotbargraph(ctx,left,right,thistop,minytick,maxytick,ystep,eachheight,points,xpoints,ypoints,colors);
				if(error != 'good'){return error;}
				
				thistop = add(thistop,eachheight);

			}
		} else {
			return zdifferentgroups;
		}
	} else {
		var error = plotbargraph(ctx,left,right,oypixel,minytick,maxytick,ystep,maxheight,points,xpoints,ypoints,colors);
		if(error != 'good'){return error;}
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
	ctx.fillText($('#xaxis').val(),width/2,height-10*scalefactor);

	labelgraph(ctx,width,height);
	if($('#invert').is(":checked")){
		invert(ctx)
	}

	var dataURL = canvas.toDataURL();
	return dataURL;
}

function plotbargraph(ctx,left,right,gtop,minytick,maxytick,ystep,maxheight,points,groups,frequencys,colors){
	
	maxheight = maxheight - 60*scalefactor;
	
	gbottom = add(gtop,maxheight);
	
	//y-axis title
	if($('#yaxis').val() != "Y Axis Title"){
		var x, y;
		x=20*scalefactor;
		y=gtop + maxheight/2;
		ctx.save();
		ctx.fillStyle = '#000000';
		fontsize = 15*scalefactor;
		ctx.font = "bold "+fontsize+"px Roboto";
		ctx.translate(x, y);
		ctx.rotate(-Math.PI/2);
		ctx.textAlign = "center";
		ctx.fillText('Frequency', 0, 0);
		ctx.restore();
	}
	
	vertaxis(ctx,gtop,gbottom,add(left,50*scalefactor),minytick,maxytick,ystep,right);
	
	line(ctx,add(left,50*scalefactor),gbottom,right,gbottom);
	
	stepsize = (right - add(left,50*scalefactor))/groups.length;
	
	thisleft = add(left,50*scalefactor);
	
	ctx.fillStyle = '#000000';
	fontsize = 13*scalefactor;
	ctx.font = fontsize+"px Roboto";
	ctx.textAlign="center";
	
	for (var index in groups){
		thisright = add(thisleft,stepsize);
		line(ctx,thisright,gbottom,thisright,add(gbottom,10));
		thiscenter = add(thisleft,thisright)/2;
		ctx.fillStyle = '#000000';
		ctx.fillText(groups[index],thiscenter,add(gbottom,15));
		if($.inArray(index,points)>-1){
			boxtop = convertvaltopixel(frequencys[index],minytick,maxytick,gbottom,gtop);
			boxleft = add(thisleft,stepsize*0.1);
			ctx.fillStyle = colors[index];
			ctx.fillRect(boxleft,gbottom,stepsize*0.8,boxtop-gbottom);
			ctx.lineWidth = 1*scalefactor;
			ctx.strokeStyle = 'rgba(0,0,0,1)';
			ctx.rect(boxleft,gbottom,stepsize*0.8,boxtop-gbottom);
			ctx.stroke();
			ctx.fillStyle = '#000000';
			if($('#regression').is(":checked")){
				ctx.fillText(frequencys[index],thiscenter,boxtop-5*scalefactor);
			}
			title = '<b>'+groups[index]+'</b><br>n: '+frequencys[index];
			$('#graphmap').append("<area shape='rect' coords='"+(boxleft/scalefactor)+","+(boxtop/scalefactor)+","+(add(boxleft,stepsize*0.8)/scalefactor)+","+(gbottom/scalefactor)+"' desc='"+title+"'>");
		}
		thisleft = thisright;
	}
	
	ctx.lineWidth = 2*scalefactor;
	if($('#thicklines').is(":checked")){
		ctx.lineWidth = 5*scalefactor;
	}
	return 'good';
}