function newpairsplot(){
	
	$("#invertshow").show();
	$("#graphmap").empty();
	
	var canvas = document.getElementById('myCanvas');
	var ctx = canvas.getContext('2d');
	
	//set size
	var width = $('#width').val();
	var height = $('#height').val();

	ctx.canvas.width = width;
	ctx.canvas.height = height;

	ctx.fillStyle = "#ffffff";
	ctx.fillRect(0, 0, canvas.width, canvas.height);
	
	data = $('#datain').val().split('@#$')
	data.pop();
	
	$(data).each(function(index,value){
		data[index]=value.split(',');
		data[index].pop();
	});
	
	//set font
	ctx.fillStyle = '#000000';
	fontsize=14*scalefactor;
	ctx.textAlign="center";
	ctx.font = fontsize+"px Roboto";
	
	ctx.strokeStyle = '#000000';
	ctx.lineWidth = 1*scalefactor;
	
	rows = data.length;
	hstep = (width-10*scalefactor)/rows;
	vstep = (height-30*scalefactor)/rows;
	t = 5*scalefactor;
	
	r=0;
	while(r<rows){
		c=0;
		left = 5*scalefactor;
		center = hstep/2;
		while(c<rows){
			bleft = left+5*scalefactor
			bright = left+hstep-5*scalefactor
			btop = t+5*scalefactor
			bbottom = t+vstep-5*scalefactor
			line(ctx,bleft,btop,bright,btop);
			line(ctx,bleft,btop,bleft,bbottom);
			line(ctx,bright,btop,bright,bbottom);
			line(ctx,bleft,bbottom,bright,bbottom);
			if(r==c){
				ctx.fillText($('#xvar option:eq('+(r+1)+')').text(),center,t+20*scalefactor);
				if($.isNumeric(data[r][0])){
					drawminihistogram(ctx,data[r],bleft,bright,btop,bbottom,r);
				} else {
					drawminibarchart(ctx,data[r],bleft,bright,btop,bbottom,r);
				}
			} else {
				if($.isNumeric(data[r][0])){
					if($.isNumeric(data[c][0])){
						drawminiscatter(ctx,data[c],data[r],bleft,bright,btop,bbottom,c,r);
					} else {
						drawminivboxes(ctx,data[c],data[r],bleft,bright,btop,bbottom,c,r);
					}
				} else {
					if($.isNumeric(data[c][0])){
						drawminihboxes(ctx,data[c],data[r],bleft,bright,btop,bbottom,c,r);
					} else {
						drawminiareagraphs(ctx,data[c],data[r],bleft,bright,btop,bbottom,c,r);
					}
				}
			}
			left += hstep;
			center += hstep;
			c++;
		}
		t += vstep;
		r++;
	}
	
	labelgraph(ctx,width,height);
	
	if($('#invert').is(":checked")){
		invert(ctx)
	}

	var dataURL = canvas.toDataURL();
	return dataURL;
}

function drawminihistogram(ctx,data,bleft,bright,btop,bbottom,r){
	min = Math.min.apply(null, data);
	max = Math.max.apply(null, data);
	range=max-min;
	point1=range/5+min;
	point2=range/5*2+min;
	point3=range/5*3+min;
	point4=range/5*4+min;
	
	bwidth = bright-bleft;
	bheight = bbottom-btop-30*scalefactor;
		
	sec1=0;
	sec2=0;
	sec3=0;
	sec4=0;
	sec5=0;
	
	$(data).each(function(index,value){
		if(value<point1){sec1++;}
		else if (value<point2){sec2++;}
		else if (value<point3){sec3++;}
		else if (value<point4){sec4++;}
		else {sec5++;}
	});
	
	max = Math.max(sec1,sec2,sec3,sec4,sec5);
	
	ctx.fillStyle = '#267BD0';
	ctx.rect(bleft, bbottom, bwidth/5, -bheight*sec1/max);ctx.fill();ctx.stroke();
	ctx.rect(bleft+bwidth/5*1, bbottom, bwidth/5, -bheight*sec2/max);ctx.fill();ctx.stroke();
	ctx.rect(bleft+bwidth/5*2, bbottom, bwidth/5, -bheight*sec3/max);ctx.fill();ctx.stroke();
	ctx.rect(bleft+bwidth/5*3, bbottom, bwidth/5, -bheight*sec4/max);ctx.fill();ctx.stroke();
	ctx.rect(bleft+bwidth/5*4, bbottom, bwidth/5, -bheight*sec5/max);ctx.fill();ctx.stroke();
	ctx.fillStyle = '#000';
	
	$('#graphmap').append("<area shape='rect' coords='"+bleft+","+btop+","+bright+","+bbottom+"' href=\"javascript:document.getElementById('xvar').selectedIndex="+r+"+1;document.getElementById('yvar').selectedIndex=0;document.getElementById('type').value='histogram';document.getElementById('xaxis').value=document.getElementById('xvar').options[document.getElementById('xvar').selectedIndex].text;document.getElementById('yaxis').value=document.getElementById('yvar').options[document.getElementById('yvar').selectedIndex].text;graphchange(document.getElementById('type'));updategraph();\" alt='"+bleft+","+btop+"'>");
	
}

function drawminibarchart(ctx,data,bleft,bright,btop,bbottom,r){
	var counts = {};
	$.each(data, function( index, value ) {
		if(counts[value]){
			counts[value]=add(counts[value],1);
		} else {
			counts[value]=1;
		}
	});
	var maxpoints = 0;
	num = 0;
	$.each( counts, function( index, value ){
	  if(value>maxpoints){
		  maxpoints=value;
	  }
	  num++;
	});
	bwidth = bright-bleft-5*scalefactor;
	bheight = bbottom-btop-30*scalefactor;
	bleft += 5;
	i=0;
	ctx.fillStyle = '#267BD0';
	$.each(counts, function(index,value){
		ctx.rect(bleft+bwidth/num*i, bbottom, bwidth/num-5, -bheight*value/maxpoints);ctx.fill();ctx.stroke();
		i++;
	});
	ctx.fillStyle = '#000';
	
	$('#graphmap').append("<area shape='rect' coords='"+bleft+","+btop+","+bright+","+bbottom+"' href=\"javascript:document.getElementById('xvar').selectedIndex="+r+"+1;document.getElementById('yvar').selectedIndex=0;document.getElementById('type').value='bar and area graph';document.getElementById('xaxis').value=document.getElementById('xvar').options[document.getElementById('xvar').selectedIndex].text;document.getElementById('yaxis').value=document.getElementById('yvar').options[document.getElementById('yvar').selectedIndex].text;graphchange(document.getElementById('type'));updategraph();\" alt='"+bleft+","+btop+"'>");
}

function drawminiscatter(ctx,xdata,ydata,bleft,bright,btop,bbottom,c,r){
	minx = Math.min.apply(null,xdata);
	maxx = Math.max.apply(null,xdata);
	miny = Math.min.apply(null,ydata);
	maxy = Math.max.apply(null,ydata);
	ctx.strokeStyle = 'rgba(0,0,0,0.5)';
	$.each(xdata,function(index,value){
		var xpoint = value;
		var ypoint = ydata[index];
		var xpixel = convertvaltopixel(xpoint,minx,maxx,bleft+10,bright-10);
		var ypixel = convertvaltopixel(ypoint,miny,maxy,bbottom-10,btop+10);
		ctx.beginPath();
		ctx.arc(xpixel,ypixel,2*scalefactor,0,2*Math.PI);
		ctx.stroke();
	})
	ctx.strokeStyle = '#000';
	$('#graphmap').append("<area shape='rect' coords='"+bleft+","+btop+","+bright+","+bbottom+"' href=\"javascript:document.getElementById('xvar').selectedIndex="+c+"+1;document.getElementById('yvar').selectedIndex="+r+"+1;document.getElementById('type').value='newscatter';document.getElementById('xaxis').value=document.getElementById('xvar').options[document.getElementById('xvar').selectedIndex].text;document.getElementById('yaxis').value=document.getElementById('yvar').options[document.getElementById('yvar').selectedIndex].text;graphchange(document.getElementById('type'));updategraph();\" alt='"+bleft+","+btop+"'>");
}

function drawminivboxes(ctx,xdata,ydata,bleft,bright,btop,bbottom,c,r){
	miny = Math.min.apply(null,ydata);
	maxy = Math.max.apply(null,ydata);
	thisdata={};
	count=0;
	$.each(xdata,function(index,value){
		var xpoint = value;
		var ypoint = ydata[index];
		if(thisdata[xpoint]){
			thisdata[xpoint].push(ypoint);
		} else {
			count++;
			thisdata[xpoint]=[];
			thisdata[xpoint].push(ypoint);
		}
	})
	i=0;
	w = (bright-bleft)/count;
	$.each(thisdata,function(index,thisvalues){
		var minval = Math.min.apply(null, thisvalues);
		var lq = lowerquartile(thisvalues);
		var med = median(thisvalues);
		var uq = upperquartile(thisvalues);
		var maxval = Math.max.apply(null, thisvalues);
		minval = convertvaltopixel(minval,miny,maxy,bbottom-10*scalefactor,btop+10*scalefactor);
		lq = convertvaltopixel(lq,miny,maxy,bbottom-10*scalefactor,btop+10*scalefactor);
		med = convertvaltopixel(med,miny,maxy,bbottom-10*scalefactor,btop+10*scalefactor);
		uq = convertvaltopixel(uq,miny,maxy,bbottom-10*scalefactor,btop+10*scalefactor);
		maxval = convertvaltopixel(maxval,miny,maxy,bbottom-10*scalefactor,btop+10*scalefactor);
		cen = bleft+i*w+w/2;
		line(ctx,cen,minval,cen,lq);
		line(ctx,cen-w/4,lq,cen+w/4,lq);
		line(ctx,cen-w/4,uq,cen-w/4,lq);
		line(ctx,cen-w/4,med,cen+w/4,med);
		line(ctx,cen+w/4,uq,cen+w/4,lq);
		line(ctx,cen-w/4,uq,cen+w/4,uq);
		line(ctx,cen,maxval,cen,uq);
		i++;
	})
	$('#graphmap').append("<area shape='rect' coords='"+bleft+","+btop+","+bright+","+bbottom+"' href=\"javascript:document.getElementById('xvar').selectedIndex="+r+"+1;document.getElementById('yvar').selectedIndex="+c+"+1;document.getElementById('type').value='newdotplot';document.getElementById('xaxis').value=document.getElementById('xvar').options[document.getElementById('xvar').selectedIndex].text;document.getElementById('yaxis').value=document.getElementById('yvar').options[document.getElementById('yvar').selectedIndex].text;graphchange(document.getElementById('type'));updategraph();\" alt='"+bleft+","+btop+"'>");
}

function drawminihboxes(ctx,xdata,ydata,bleft,bright,btop,bbottom,c,r){
	minx = Math.min.apply(null,xdata);
	maxx = Math.max.apply(null,xdata);
	thisdata={};
	count=0;
	$.each(ydata,function(index,value){
		var xpoint = value;
		var ypoint = xdata[index];
		if(thisdata[xpoint]){
			thisdata[xpoint].push(ypoint);
		} else {
			count++;
			thisdata[xpoint]=[];
			thisdata[xpoint].push(ypoint);
		}
	})
	i=0;
	w = (bbottom-btop)/count;
	$.each(thisdata,function(index,thisvalues){
		var minval = Math.min.apply(null, thisvalues);
		var lq = lowerquartile(thisvalues);
		var med = median(thisvalues);
		var uq = upperquartile(thisvalues);
		var maxval = Math.max.apply(null, thisvalues);
		minval = convertvaltopixel(minval,minx,maxx,bleft+10*scalefactor,bright-10*scalefactor);
		lq = convertvaltopixel(lq,minx,maxx,bleft+10*scalefactor,bright-10*scalefactor);
		med = convertvaltopixel(med,minx,maxx,bleft+10*scalefactor,bright-10*scalefactor);
		uq = convertvaltopixel(uq,minx,maxx,bleft+10*scalefactor,bright-10*scalefactor);
		maxval = convertvaltopixel(maxval,minx,maxx,bleft+10*scalefactor,bright-10*scalefactor);
		cen = btop+i*w+w/2;
		line(ctx,minval,cen,lq,cen);
		line(ctx,lq,cen-w/4,lq,cen+w/4);
		line(ctx,uq,cen-w/4,lq,cen-w/4);
		line(ctx,med,cen-w/4,med,cen+w/4);
		line(ctx,uq,cen+w/4,lq,cen+w/4);
		line(ctx,uq,cen-w/4,uq,cen+w/4);
		line(ctx,uq,cen,maxval,cen);
		i++;
	})
	$('#graphmap').append("<area shape='rect' coords='"+bleft+","+btop+","+bright+","+bbottom+"' href=\"javascript:document.getElementById('xvar').selectedIndex="+c+"+1;document.getElementById('yvar').selectedIndex="+r+"+1;document.getElementById('type').value='newdotplot';document.getElementById('xaxis').value=document.getElementById('xvar').options[document.getElementById('xvar').selectedIndex].text;document.getElementById('yaxis').value=document.getElementById('yvar').options[document.getElementById('yvar').selectedIndex].text;graphchange(document.getElementById('type'));updategraph();\" alt='"+bleft+","+btop+"'>");
}

function drawminiareagraphs(ctx,ydata,xdata,bleft,bright,btop,bbottom,c,r){
	bwidth = bright-bleft;
	bheight = bbottom-btop;
	thisdata={};
	ycats={};
	count=xdata.length;
	$.each(ydata,function(index,value){
		var xpoint = value;
		var ypoint = xdata[index];
		if(!thisdata[xpoint]){
			thisdata[xpoint]={};
		}
		if(!thisdata[xpoint][ypoint]){
			thisdata[xpoint][ypoint]=0;
		}
		if(!ycats[ypoint]){
			ycats[ypoint]=0;
		}
		thisdata[xpoint][ypoint]=add(thisdata[xpoint][ypoint],1);
	})
	l=0;
	ctx.fillStyle = 'rgba(0,0,0,0.12)';	
	$.each(thisdata,function(index,thisvalues){
		h=0;
		total = 0;
		$.each(thisvalues,function(index,value){
			total+=value;
		});
		$.each(ycats,function(index,value){
			if(thisvalues[index]){
				ctx.beginPath();
				ctx.rect(bleft+l, bbottom+h, bwidth*total/count, -bheight*thisvalues[index]/total);
				ctx.fill();
				ctx.stroke();
			}
			h = h-bheight*thisvalues[index]/total;
		});
		l=add(l,bwidth*total/count);
	});
	ctx.fillStyle = '#000';
	$('#graphmap').append("<area shape='rect' coords='"+bleft+","+btop+","+bright+","+bbottom+"' href=\"javascript:document.getElementById('xvar').selectedIndex="+c+"+1;document.getElementById('yvar').selectedIndex="+r+"+1;document.getElementById('type').value='bar and area graph';document.getElementById('xaxis').value=document.getElementById('xvar').options[document.getElementById('xvar').selectedIndex].text;document.getElementById('yaxis').value=document.getElementById('yvar').options[document.getElementById('yvar').selectedIndex].text;graphchange(document.getElementById('type'));updategraph();\" alt='"+bleft+","+btop+"'>");
}