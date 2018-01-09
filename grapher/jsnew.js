function newscatter(){
	$('#reg').show();
	$('#regshow').show();
	$('#labelshow').show();
	$('#jittershow').show();
	$('#quadraticshow').show();
	$('#cubicshow').show();
	$('#expshow').show();
	$('#logshow').show();
	$('#powshow').show();
	$('#yxshow').show();
	$('#invertshow').show();
	$('#thicklinesshow').show();
	$('#xvar').show();
	$('#yvar').show();
	$('#zvar').show();
	$('#color').show();
	$('#colorname').show();
	$('#greyscaleshow').show();
	$('#sizediv').show();
	$('#pointsizename').html('Point Size:');
	$('#transdiv').show();

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
	ctx.font = "bold 15px Roboto";
	ctx.textAlign="center";
	ctx.fillText($('#xaxis').val(),width/2,height-10);
	
	//y-axis title
	x=20;
	y=height/2;
	ctx.save();
	ctx.fillStyle = '#000000';
	ctx.font = "bold 15px Roboto";
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
	var zpoints = $('#zvar').val().split(",");
	zpoints.pop();

	//check for numeric value
	var points=[];
	var allpoints=[];
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
			allpoints.push(index);
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

	if(pointsremoved.length!=0){
		ctx.fillStyle = '#000000';
		ctx.font = "13px Roboto";
		ctx.textAlign="right";
		ctx.fillText("ID(s) of Points Removed: "+pointsremoved.join(", "),width-50,50);
	}

	if(points.length==0){
		return 'Error: You must select a numeric variable for variable 1';
	}

	var oypixel=height-60;
	var maxheight=height-120;
	var left=90;
	var right=width-60;
	var gtop=90;
	var bottom=height-60;

	var xmin = Math.min.apply(null, pointsforminmax);
	var xmax = Math.max.apply(null, pointsforminmax);
	var ymin = Math.min.apply(null, pointsforminmaxy);
	var ymax = Math.max.apply(null, pointsforminmaxy);
	if($.isNumeric($('#scatplotminx').val())){
		xmin=$('#scatplotminx').val();
	}
	if($.isNumeric($('#scatplotmaxx').val())){
		xmax=$('#scatplotmaxx').val();
	}
	if($.isNumeric($('#scatplotminy').val())){
		ymin=$('#scatplotminy').val();
	}
	if($.isNumeric($('#scatplotmaxy').val())){
		ymax=$('#scatplotmaxy').val();
	}
	var minmaxstep = axisminmaxstep(xmin,xmax);
	var minxtick=minmaxstep[0];
	var maxxtick=minmaxstep[1];
	var xstep=minmaxstep[2];
	var minmaxstep = axisminmaxstep(ymin,ymax);
	var minytick=minmaxstep[0];
	var maxytick=minmaxstep[1];
	var ystep=minmaxstep[2];

	var alpha = 1-$('#trans').val()/100;
	var colors = makecolors(alpha,ctx);
	
	if(zpoints.length>0){
		zdifferentgroups = split(points,zpoints,4,3);
		if(typeof zdifferentgroups === 'object'){
			zgroups = Object.keys(zdifferentgroups);
			zgroups.sort();
			thisleft=60;
			eachwidth=(width-40)/zgroups.length;
			for (index in zgroups){
				group = zgroups[index];
				points = zdifferentgroups[group];

				thisright = add(thisleft,eachwidth);

				ctx.fillStyle = '#000000';
				ctx.font = "bold 15px Roboto";
				ctx.textAlign="center";
				ctx.fillText(group,add(thisleft,thisright-50)/2,oypixel-maxheight);
				
				plotscatter(ctx,points,xpoints,ypoints,minxtick,maxxtick,xstep,minytick,maxytick,ystep,gtop,bottom,add(thisleft,30),thisright-50,colors);

				thisleft = add(thisleft,eachwidth);
			}
		} else {
			return zdifferentgroups;
		}
	} else {
		plotscatter(ctx,points,xpoints,ypoints,minxtick,maxxtick,xstep,minytick,maxytick,ystep,gtop,bottom,left,right,colors);
	}
	
	labelgraph(ctx,width,height);
	
	if($('#invert').is(":checked")){
		invert(ctx)
	}

	var dataURL = canvas.toDataURL();
	return dataURL;
}

function plotscatter(ctx,indexes,xpoints,ypoints,minxtick,maxxtick,xstep,minytick,maxytick,ystep,gtop,bottom,left,right,colors){
	horaxis(ctx,left,right,add(bottom,10),minxtick,maxxtick,xstep);
	vertaxis (ctx,gtop,bottom,left-10,minytick,maxytick,ystep);
	ctx.lineWidth = 2;
	if($('#thicklines').is(":checked")){
		ctx.lineWidth = 5;
	}
	if($('#labels').is(":checked")){var labels="yes";} else {var labels = "no";}
	var rad = $('#size').val()/2;
	num = 0;
	xcurvefit="";
	ycurvefit="";
	for (var index in indexes){
		var index = indexes[index];
		var xpoint = xpoints[index];
		var ypoint = ypoints[index];
		xcurvefit+=xpoint+",";
		ycurvefit+=ypoint+",";
		var xpixel = convertvaltopixel(xpoint,minxtick,maxxtick,left,right);
		var ypixel = convertvaltopixel(ypoint,minytick,maxytick,bottom,gtop);
		if($('#jitter').is(":checked")){
			xpixel = add(xpixel,randint(-3,3));
			ypixel = add(ypixel,randint(-3,3));
		}
		ctx.beginPath();
		ctx.strokeStyle = colors[index];
		ctx.arc(xpixel,ypixel,rad,0,2*Math.PI);
		ctx.stroke();
		if(labels == "yes"){
			ctx.fillStyle = 'rgba(0,0,255,1)';
			ctx.font = "10px Roboto";
			ctx.textAlign="left";
			ctx.fillText(parseInt(add(index,1)),add(add(xpixel,rad),2),add(ypixel,4));
		}
		num++;
	}

	equationtop = gtop;
	ctx.textAlign="left";
	ctx.fillStyle = '#000';
	ctx.font = "13px Roboto";
	ctx.lineWidth = 1;
	if($('#thicklines').is(":checked")){
		ctx.lineWidth = 3;
	}
	
	$.ajaxSetup({async:false});
	
	if($('#regression').is(":checked")){
		ctx.fillStyle='#f00';
		ctx.strokeStyle='#f00';
		$.post('curvefitter.php',{xvals:xcurvefit,yvals:ycurvefit,type:'regression'}).done(function(data){
			data=JSON.parse(data);
			c = parseFloat(data.c).toPrecision(5);
			m = parseFloat(data.m).toPrecision(5);
		})
		x = minxtick;
		lasty=0;
		step = (maxxtick - minxtick)/100;
		while(x<maxxtick){
			y = add(m * x,c);
			xpixel = convertvaltopixel(x,minxtick,maxxtick,left,right);
			ypixel = convertvaltopixel(y,minytick,maxytick,bottom,gtop);
			if(x>minxtick && y>=minytick && y<=maxytick && lasty>=minytick && lasty<=maxytick){
				line(ctx,lastxpixel,lastypixel,xpixel,ypixel);
			}
			lastxpixel=xpixel;
			lastypixel=ypixel;
			lasty = y;
			x = add(x,step);
		}
		ctx.fillText($('#yaxis').val()+" = "+m+" * "+$('#xaxis').val()+" + "+c,left, equationtop);
		equationtop = add(equationtop,15);
	}
	
	if($('#quadratic').is(":checked")){
		ctx.fillStyle='#00f';
		ctx.strokeStyle='#00f';
		$.post('curvefitter.php',{xvals:xcurvefit,yvals:ycurvefit,type:'quadratic'}).done(function(data){
			data=JSON.parse(data);
			a = parseFloat(data.c).toPrecision(5);
			b = parseFloat(data.b).toPrecision(5);
			c = parseFloat(data.a).toPrecision(5);
		})
		x = minxtick;
		lasty=0;
		step = (maxxtick - minxtick)/100;
		while(x<maxxtick){
			y = add(add(a * Math.pow(x,2),b * x),c);
			xpixel = convertvaltopixel(x,minxtick,maxxtick,left,right);
			ypixel = convertvaltopixel(y,minytick,maxytick,bottom,gtop);
			if(x>minxtick && y>=minytick && y<=maxytick && lasty>=minytick && lasty<=maxytick){
				line(ctx,lastxpixel,lastypixel,xpixel,ypixel);
			}
			lastxpixel=xpixel;
			lastypixel=ypixel;
			lasty = y;
			x = add(x,step);
		}
		ctx.fillText($('#yaxis').val()+" = "+a+" * "+$('#xaxis').val()+"^2 + "+b+" * "+$('#xaxis').val()+" + "+c,left, equationtop);
		equationtop = add(equationtop,15);
	}
	
	if($('#cubic').is(":checked")){
		ctx.fillStyle='#0f0';
		ctx.strokeStyle='#0f0';
		$.post('curvefitter.php',{xvals:xcurvefit,yvals:ycurvefit,type:'cubic'}).done(function(data){
			data=JSON.parse(data);
			a = parseFloat(data.d).toPrecision(5);
			b = parseFloat(data.c).toPrecision(5);
			c = parseFloat(data.b).toPrecision(5);
			d = parseFloat(data.a).toPrecision(5);
		})
		x = minxtick;
		lasty=0;
		step = (maxxtick - minxtick)/100;
		while(x<maxxtick){
			y = add(add(add(a * Math.pow(x,3),b * Math.pow(x,2)),c * x),d);
			xpixel = convertvaltopixel(x,minxtick,maxxtick,left,right);
			ypixel = convertvaltopixel(y,minytick,maxytick,bottom,gtop);
			if(x>minxtick && y>=minytick && y<=maxytick && lasty>=minytick && lasty<=maxytick){
				line(ctx,lastxpixel,lastypixel,xpixel,ypixel);
			}
			lastxpixel=xpixel;
			lastypixel=ypixel;
			lasty = y;
			x = add(x,step);
		}
		ctx.fillText($('#yaxis').val()+" = "+a+" * "+$('#xaxis').val()+"^3 + "+b+" * "+$('#xaxis').val()+"^2 + "+c+" * "+$('#xaxis').val()+" + "+d,left, equationtop);
		equationtop = add(equationtop,15);
	}
	
	if($('#exp').is(":checked")){
		ctx.fillStyle='#952BFF';
		ctx.strokeStyle='#952BFF';
		$.post('curvefitter.php',{xvals:xcurvefit,yvals:ycurvefit,type:'exp'}).done(function(data){
			data=JSON.parse(data);
			a = parseFloat(data.a).toPrecision(5);
			b = parseFloat(data.b).toPrecision(5);
		})
		x = minxtick;
		lasty=0;
		step = (maxxtick - minxtick)/100;
		while(x<maxxtick){
			y = a * Math.exp(b*x);
			xpixel = convertvaltopixel(x,minxtick,maxxtick,left,right);
			ypixel = convertvaltopixel(y,minytick,maxytick,bottom,gtop);
			if(x>minxtick && y>=minytick && y<=maxytick && lasty>=minytick && lasty<=maxytick){
				line(ctx,lastxpixel,lastypixel,xpixel,ypixel);
			}
			lastxpixel=xpixel;
			lastypixel=ypixel;
			lasty = y;
			x = add(x,step);
		}
		ctx.fillText($('#yaxis').val()+" = "+a+" * exp("+b+" * "+$('#xaxis').val()+")",left, equationtop);
		equationtop = add(equationtop,15);
	}
	
	if($('#log').is(":checked")){
		ctx.fillStyle='#FF972E';
		ctx.strokeStyle='#FF972E';
		$.post('curvefitter.php',{xvals:xcurvefit,yvals:ycurvefit,type:'log'}).done(function(data){
			data=JSON.parse(data);
			a = parseFloat(data.a).toPrecision(5);
			b = parseFloat(data.b).toPrecision(5);
		})
		x = minxtick;
		lasty=0;
		step = (maxxtick - minxtick)/100;
		while(x<maxxtick){
			y = add(a * Math.log(x),b);
			xpixel = convertvaltopixel(x,minxtick,maxxtick,left,right);
			ypixel = convertvaltopixel(y,minytick,maxytick,bottom,gtop);
			if(x>minxtick && y>=minytick && y<=maxytick && lasty>=minytick && lasty<=maxytick){
				line(ctx,lastxpixel,lastypixel,xpixel,ypixel);
			}
			lastxpixel=xpixel;
			lastypixel=ypixel;
			lasty = y;
			x = add(x,step);
		}
		ctx.fillText($('#yaxis').val()+" = "+a+" * log("+$('#xaxis').val()+") + "+b,left, equationtop);
		equationtop = add(equationtop,15);
	}
	
	if($('#pow').is(":checked")){
		ctx.fillStyle='#3ED2D2';
		ctx.strokeStyle='#3ED2D2';
		$.post('curvefitter.php',{xvals:xcurvefit,yvals:ycurvefit,type:'pow'}).done(function(data){
			data=JSON.parse(data);
			a = parseFloat(data.a).toPrecision(5);
			b = parseFloat(data.b).toPrecision(5);
		})
		x = minxtick;
		lasty=0;
		step = (maxxtick - minxtick)/100;
		while(x<maxxtick){
			y = a * Math.pow(x,b);
			xpixel = convertvaltopixel(x,minxtick,maxxtick,left,right);
			ypixel = convertvaltopixel(y,minytick,maxytick,bottom,gtop);
			if(x>minxtick && y>=minytick && y<=maxytick && lasty>=minytick && lasty<=maxytick){
				line(ctx,lastxpixel,lastypixel,xpixel,ypixel);
			}
			lastxpixel=xpixel;
			lastypixel=ypixel;
			lasty = y;
			x = add(x,step);
		}
		ctx.fillText($('#yaxis').val()+" = "+a+" * "+$('#xaxis').val()+" ^ "+b,left, equationtop);
		equationtop = add(equationtop,15);
	}
	
	if($('#yx').is(":checked")){
		ctx.fillStyle = '#000';
		ctx.strokeStyle='#000';
		ctx.setLineDash([5, 5]);
		min = minxtick;
		if(min<minytick){min=minytick;}
		max = maxxtick;
		if(max>maxytick){max=maxytick;}
		if(min<max){
			var mnx = convertvaltopixel(min,minxtick,maxxtick,left,right);
			var mny = convertvaltopixel(min,minytick,maxytick,bottom,gtop);
			var mxx = convertvaltopixel(max,minxtick,maxxtick,left,right);
			var mxy = convertvaltopixel(max,minytick,maxytick,bottom,gtop);
			line(ctx,mnx,mny,mxx,mxy);
		}
		ctx.fillText("- - - y = x",left, equationtop);
		equationtop = add(equationtop,15);
		ctx.setLineDash([]);
	}
	
	if($('#regression, #cubic, #quadratic, #yx').is(":checked")){
		ctx.fillText("n = "+num,left, equationtop);
	}

}