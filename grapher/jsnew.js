function newbargraphf(){
	$('#invertshow').show();
	$('#regshow').show();
	$('#sum').show();
	$('#xvar').show();
	$('#yvar').show();
	$('#zvar').show();
	$('#color').show();
	$('#colorname').show();
	$('#transdiv').show();
	$('#greyscaleshow').show();
	$('#gridlinesshow').show();
	$('#removedpointsshow').show();
	$('#percent100show').show();
	$('#relativefrequencyshow').show();
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
	var colorpoints = $('#color').val().split(",");
	colorpoints.pop();

	//check x points for number of groups
	if(xpoints.length==0){
		return 'Error: You must select a variable for "Category"';
	}

	//check for numeric value
	var points=[];
	var allpoints=[];
	var pointsremoved=[];
	for (var index in ypoints){
		if($.isNumeric(ypoints[index])){
			points.push(index);
			allpoints.push(index);
		} else {
			pointsremoved.push(add(index,1));
		}
	}

	if(points.length==0){
		return 'Error: You must select a numeric variable for "Frequency"';
	}
	
	xdifferentgroups = split(points,xpoints,10,'"Category"');
	if(typeof xdifferentgroups !== 'object'){
		return xdifferentgroups;
	}
	xgroups = Object.keys(xdifferentgroups).sort(sortorder);

	if(pointsremoved.length!=0 && $('#removedpoints').is(":checked")){
		ctx.fillStyle = '#000000';
		fontsize = 13*scalefactor;
		ctx.font = fontsize+"px Roboto";
		ctx.textAlign="right";
		ctx.fillText("ID(s) of Points Removed: "+pointsremoved.join(", "),width-48*scalefactor,48*scalefactor);
	}
	
	relativefrequency = false;
	if($('#relativefrequency').is(":checked")){
		relativefrequency = true;
		$("#percent100"). prop("checked", false);
	}
	
	percent100 = false;
	if($('#percent100').is(":checked")){
		percent100 = true;
	}

	var oypixel=60*scalefactor;
	var maxheight=height-60*scalefactor;
	var left=20*scalefactor;
	var right=width-60*scalefactor;

	ymin = 0;
	ymax = 0;
	total = 0;
	
	sumpoints = {};
	
	if(zpoints.length>0){
		zdifferentgroups = split(points,zpoints,4,'"Split"');
		if(typeof zdifferentgroups === 'object'){
			zgroups = Object.keys(zdifferentgroups);
			zgroups.sort(sortorder);
			for (z in zgroups){
				zgroup = zgroups[z];
				zgrouppoints = zdifferentgroups[zgroup];
				for(x in xgroups){
					xgroup = xgroups[x];
					for (i in xdifferentgroups[xgroup]){
						index = xdifferentgroups[xgroup][i];
						if($.inArray(index,zgrouppoints)>-1){
							cat = xgroup+'-~-'+zgroup;
							val = ypoints[index];
							if(sumpoints[cat] === undefined){
								sumpoints[cat]=0;
							}
							sumpoints[cat]-=(-val);
						}	
					}
				}
			}
		} else {
			return zdifferentgroups;
		}
	} else {
		for(g in xgroups){
			for (i in xdifferentgroups[xgroups[g]]){
				index = xdifferentgroups[xgroups[g]][i];
				cat = xgroups[g];
				val = ypoints[index];
				if(sumpoints[cat] === undefined){
					sumpoints[cat]=0;
				}
				sumpoints[cat]-=(-val);
			}
		}
	}
	
	for(index in sumpoints){
		total += sumpoints[index];
		if(sumpoints[index]>ymax){
			ymax = sumpoints[index];
		}
	}
	
	if(relativefrequency){
		ymax = ymax / total;
	}
	
	if(percent100){
		ymax = 100;
	}
	
	var minmaxstep = axisminmaxstep(ymin,ymax);
	var minytick=minmaxstep[0];
	var maxytick=minmaxstep[1];
	var ystep=minmaxstep[2];

	var alpha = 1-$('#trans').val()/100;
	var colors = makecolors(alpha,ctx);
	

	if(zpoints.length>0){
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
			
			var error = plotbargraph(ctx,left,right,thistop,minytick,maxytick,ystep,eachheight,points,xdifferentgroups,ypoints,colors,xgroups,colorpoints,relativefrequency,total,percent100,sumpoints,group);
			if(error != 'good'){return error;}
			
			thistop = add(thistop,eachheight);
		}
	} else {
		var error = plotbargraph(ctx,left,right,oypixel,minytick,maxytick,ystep,maxheight,points,xdifferentgroups,ypoints,colors,xgroups,colorpoints,relativefrequency,total,percent100,sumpoints,'~nogroup~');
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

function onlyUnique(value, index, self) { 
    return self.indexOf(value) === index;
}

function plotbargraph(ctx,left,right,gtop,minytick,maxytick,ystep,maxheight,points,groups,frequencys,colors,xgroups,colorpoints,relativefrequency,total,percent100,sumpoints,zgroup){
	
	uniquecolors = colorpoints.filter( onlyUnique ).sort(sortorder);
	if(uniquecolors.length==0){uniquecolors=[''];}
	
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
		ctx.fillText($('#yaxis').val(), 0, 0);
		ctx.restore();
	}
	
	append = '';
	if(percent100){append = '%';}
	
	vertaxis(ctx,gtop,gbottom,add(left,50*scalefactor),minytick,maxytick,ystep,right,append);
	
	line(ctx,add(left,50*scalefactor),gbottom,right,gbottom);
	
	stepsize = (right - add(left,50*scalefactor))/xgroups.length;
	
	thisleft = add(left,50*scalefactor);
	
	ctx.fillStyle = '#000000';
	fontsize = 13*scalefactor;
	ctx.font = fontsize+"px Roboto";
	ctx.textAlign="center";
	
	for (var index in xgroups){
		group = xgroups[index];
		thisright = add(thisleft,stepsize);
		line(ctx,thisright,gbottom,thisright,add(gbottom,10));
		thiscenter = add(thisleft,thisright)/2;
		ctx.fillStyle = '#000000';
		ctx.fillText(group,thiscenter,add(gbottom,15));
		gtotal = 0;
		boxbottom = gbottom;
		boxtop = boxbottom;
		colordesc = "";
		for(c in uniquecolors){
			thistotal = 0;
			colorname = uniquecolors[c];
			for(p in groups[group]){
				i = groups[group][p];
				if(colorname == colorpoints[i] || uniquecolors.length==1){
					if($.inArray(i,points)>-1){
						thistotal -= -frequencys[i];
						if(colorpoints.length>0){
							colordesc = '<b>'+$('#colorlabel').val()+'</b>: '+colorpoints[i]+'<br>';
						}
						color = colors[i];
					}
				}
			}
			displaytotal = thistotal;
			if(relativefrequency){
				displaytotal = displaytotal/total;
			}
			if(percent100){
				if(zgroup!='~nogroup~'){
					selectgroup = group + '-~-' + zgroup;
				} else {
					selectgroup = group;
				}
				displaytotal = displaytotal/sumpoints[selectgroup]*100;
			}
			if(thistotal>0){
				gtotal += displaytotal;
				boxtop = convertvaltopixel(gtotal,minytick,maxytick,gbottom,gtop);
				console.log({gtotal,minytick,maxytick,boxbottom,boxtop});
				boxleft = add(thisleft,stepsize*0.1);
				ctx.fillStyle = color;
				ctx.fillRect(boxleft,boxbottom,stepsize*0.8,boxtop-boxbottom);
				ctx.lineWidth = 1*scalefactor;
				ctx.strokeStyle = 'rgba(0,0,0,1)';
				ctx.rect(boxleft,boxbottom,stepsize*0.8,boxtop-boxbottom);
				ctx.stroke();
				ctx.fillStyle = '#000000';
				if(relativefrequency){
					displaytotal = displaytotal.toPrecision(5);
				}
				if(percent100){
					displaytotal = displaytotal.toFixed(1)+"%";
				}
				if(thistotal == displaytotal){
					title = '<b>'+$('#xaxis').val()+'</b>: '+xgroups[index]+'</b><br>'+colordesc+'<b>n</b>: '+ thistotal;
				} else {
					title = '<b>'+$('#xaxis').val()+'</b>: '+xgroups[index]+'</b><br>'+colordesc+'<b>n</b>: '+ thistotal + " (" + displaytotal + ")";
				}
				$('#graphmap').append("<area shape='rect' coords='"+(boxleft/scalefactor)+","+(boxtop/scalefactor)+","+(add(boxleft,stepsize*0.8)/scalefactor)+","+(boxbottom/scalefactor)+"' desc='"+title+"'>");
				if($('#regression').is(":checked")){
					ctx.fillText(displaytotal,thiscenter,(boxtop+boxbottom)/2+4.5*scalefactor);
				}
			}
			boxbottom = boxtop;
		}
		thisleft = thisright;
	}
	
	ctx.lineWidth = 2*scalefactor;
	if($('#thicklines').is(":checked")){
		ctx.lineWidth = 5*scalefactor;
	}
	return 'good';
}