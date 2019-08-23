function newchangelog(){
	$.get('./change log.php').done(function(data){
		var width = $('#width').val()-22;
		var height = $('#height').val()-22;
		$('#jsgraph').html("<div style='width:"+width+"px;height:"+height+"px;overflow-y:scroll;padding:10px;text-align:left;'>"+data+"</div>");
	});
	return "DISPLLoading...";
}

function newpiechart(){
	$('#xvar').show();
	$('#yvar').show();
	$('#zvar').show();
	$('#var1label').html("category 1:<br><small>required</small>");
	$('#var2label').html("category 2:<br><small>optional</small>");
	$('#var3label').html("frequency:<br><small>optional</small>");
	if($('#color').val() != $('#xvar').val()){
		$('#color').val($('#xvar').val());
	};
	
	$('#colorlabel').val($('#xaxis').val());
	
	$('#regshow').show();
	$('#sum').show();
	$('#greyscaleshow').show();
	$('#donutshow').show();
	
	var canvas = document.getElementById('myCanvas');
	var ctx = canvas.getContext('2d');
	
	//set size
	var width = $('#width').val();
	var height = $('#height').val();

	ctx.canvas.width = width;
	ctx.canvas.height = height;

	var colors = makecolors(1,ctx);
	
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
	var zpoints = $('#zvar').val().split(",");
	zpoints.pop();
	
	if(xpoints.length==0){
		return 'Error: You must select a variable for "category 1"';
	}
	
	var points=[];
	var allpoints=[];
	var pointsremoved=[];
	var pointsforminmax=[];
	for (var index in xpoints){
		allpoints.push(index);
	}
	allydifferentgroups = [];
	
	if(ypoints.length>0){
		allydifferentgroups = split(allpoints,ypoints,10,2);
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
		allygroups=''
		allydifferentgroups={};
		allydifferentgroups['']=allpoints;
	}
	
	numgraphs=Object.keys(allydifferentgroups).length;
	if(numgraphs<=3){
		numwidth=numgraphs;
	} else {
		numwidth=Math.ceil(Math.sqrt(numgraphs));
	}
	
	numheight=Math.ceil(numgraphs/numwidth);
	
	graphwidth=(width-50*scalefactor)/numwidth;
	graphheight=(height-80*scalefactor)/numheight;
	
	left=25*scalefactor;
	datop=50*scalefactor;
	
	$.each(allydifferentgroups,function(group,keys){
		if($('#regression').is(":checked")){
			group += " (num: " + keys.length + ")";
		}
		
		if(left>width-30*scalefactor){
			left=25*scalefactor;
			datop=datop+graphheight;
		}
		
		centerx=graphwidth/2+left;
		centery=graphheight/2+datop-20*scalefactor;
		
		diameter=Math.min(graphheight-50*scalefactor,graphwidth-10*scalefactor);
		
		drawpie(ctx,keys,colors,diameter,centerx,centery,xpoints,zpoints,group);
				
		ctx.fillStyle = '#000000';
		fontsize=14*scalefactor;
		ctx.font = "bold "+fontsize+"px Roboto";
		ctx.textAlign="center";
		ctx.fillText(group,centerx,centery+diameter/2+30*scalefactor);
		
		left+=graphwidth;
			
	});
	
	labelgraph(ctx,width,height);
	
	if($('#invert').is(":checked")){
		invert(ctx)
	}

	var dataURL = canvas.toDataURL();
	return dataURL;
}

function drawpie(ctx,keys,colors,diameter,centerx,centery,xpoints,zpoints,group){
	if(zpoints.length>0){
		total = 0;
		$.each(keys,function(i,key){
			total = add(total,zpoints[key]);
		})
	} else {
		total = keys.length;
	}
	if(total==0){total=1;}
	angleperitem = 2*Math.PI / total;
	
	var counts = {};
	
	$.each(keys,function(i,key){
		value = xpoints[key];
		if(counts[value]){
			if(zpoints.length>0){
				counts[value]['count']=add(counts[value]['count'],zpoints[key]);
			} else {
				counts[value]['count']+=1;
			}
		} else {
			counts[value]=[];
			if(zpoints.length>0){
				counts[value]['count']=zpoints[key];
			} else {
				counts[value]['count']=1;
			}
			counts[value]['color']=colors[key];
			counts[value]['name']=value;
		}
	})
	
	angle = -Math.PI/2;
	ctx.strokeStyle = 'rgb(0,0,0)';
	ctx.lineWidth = 1*scalefactor;
	
	const ordered = {};
	Object.keys(counts).sort().forEach(function(key) {
	  ordered[key] = counts[key];
	});
	
	$.each(ordered,function(i,data){
		thisangle = data.count*angleperitem;
		ctx.fillStyle = data.color;
		ctx.beginPath();
		ctx.moveTo(centerx,centery);
		ctx.arc(centerx,centery,diameter/2,angle,angle+thisangle);
		ctx.moveTo(centerx,centery);
		ctx.closePath();
		ctx.fill();
		angle += thisangle;
		
	});
	
	
	$.each(ordered,function(i,data){
		thisangle = data.count*angleperitem;
		ctx.fillStyle = data.color;
		ctx.beginPath();
		ctx.moveTo(centerx,centery);
		ctx.arc(centerx,centery,diameter/2,angle,angle+thisangle);
		ctx.moveTo(centerx,centery);
		ctx.closePath();
		ctx.stroke();
		
		half=(angle+angle+thisangle)/2;
		pix_x=diameter*0.4*Math.cos(half)+centerx;
		pix_y=diameter*0.4*Math.sin(half)+centery;
		
		ctx.fillStyle = '#000000';
		fontsize=11*scalefactor;
		ctx.font = "bold "+fontsize+"px Roboto";
		ctx.textAlign="center";
		ctx.fillText(data.name,pix_x,pix_y);
		if($('#regression').is(":checked")){
			display = "(num: " + data.count + ")";
			ctx.fillText(display,pix_x,pix_y+fontsize);
		}
		
		points = centerx/scalefactor+","+centery/scalefactor;
		startangle = angle;
		i=0;
		while(i<=10){
			l=(diameter*0.5*Math.cos(startangle)+centerx)/scalefactor;
			t=(diameter*0.5*Math.sin(startangle)+centery)/scalefactor;
			ctx.lineTo(l,t);
			points+=","+l+","+t;
			startangle+=thisangle/10;
			i++;
		}
		points += ","+centerx/scalefactor+","+centery/scalefactor;
		desc=$('#xaxis').val()+": "+data.name + "<br>"+$('#yaxis').val()+": "+group + "<br>num: " + data.count + "<br>" + (data.count/keys.length*100).toFixed(1) + "% of "+group;
		$('#graphmap').append('<area shape="poly" coords="'+points+'" desc="'+desc+'">');
		
		angle += thisangle;
	});
	
	
	if($('#donut').is(":checked")){
		ctx.fillStyle = 'rgb(255,255,255)';
		ctx.beginPath();
		ctx.arc(centerx,centery,diameter*0.3,0,2*Math.PI);
		ctx.closePath();
		ctx.fill();
		ctx.stroke();
	}
		
	ctx.beginPath();
	ctx.arc(centerx,centery,diameter/2,0,2*Math.PI);
	ctx.stroke();
	
}
