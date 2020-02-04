<?php include './version.php'; ?>
<html v=<?php echo $v; ?>>
<head>
	<!-- Google Analytics -->
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-19339458-1', 'auto');
	ga('send', 'pageview');
	</script>
	<!-- End Google Analytics -->
	<script src="./jquery-1.11.1.min.js"></script>
	<script src="./jquery.csv.js"></script>
	<script src="./regression.min.js"></script>
	<title>NZGrapher</title>
	<link href='//fonts.googleapis.com/css?family=Roboto:400,700|Roboto+Condensed' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="style.css?v=<?php
		date_default_timezone_set('Pacific/Auckland');
		echo $v;
	?>">
	<script src="./js.js?v=<?php
		echo $v;
	?>"></script>
	<script src="./jsnew.js?v=<?php
		echo $v;
	?>"></script>
	<meta name="description" content="NZGrapher is a web based graphing tool. NZ Grapher was designed for New Zealand Schools by a New Zealand Teacher.">
	<link rel="canonical" href="https://grapher.nz/" />
	<link rel="apple-touch-icon" sizes="57x57" href="./icon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="./icon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="./icon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="./icon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="./icon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="./icon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="./icon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="./icon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="./icon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="./icon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="./icon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="./icon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="./icon/favicon-16x16.png">
	<link rel="manifest" href="./icon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="./icon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta id="vp" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
</head>
<body>
<script>
if (screen.availWidth < 1024)
{
    var mvp = document.getElementById('vp');
    mvp.setAttribute('content','width=1024');
}
</script>
<div id=welcome onclick="$('#hidewelcome').click()" <?php
	if(isset($_COOKIE['welcome'])){
		echo " style='display:none'";
	}
?>>
	<div id=welcomecontent onclick="event.stopPropagation()" style='text-align:center;font-size:90%;'>
		<br>
		<span style='display:block;width:100%;text-align:center'><img src='logob.png' style='max-height:70px;'></span>
		<table style='width:100%;margin-bottom:5px;max-width: 800px;margin: 0 auto;font-size:100%;'>
			<tr>
				<td style='width:50%;border-right:1px solid #ccc;padding-right:10px;vertical-align:top;padding-bottom:0px;padding-top:0px;'>
					<span style='display:block;width:100%;text-align:center;font-size:150%;'><b>Need Help?</b></span><br>
					Need help <b>getting started</b>? Watching <a target='_blank' href='//www.mathsnz.com/nzgrapher-info/video-tutorials'>these videos</a> is a great place to start.<br>
					<br>
					Want to <b>know more</b>? Infomation on the graphs and datasets, as well as video tutorials are over on <a target='_blank' href='https://www.mathsnz.com/nzgrapher-info'>MathsNZ</a>.<br>
					<br>
					Something <b>not working</b> or <b>have an idea</b> to make NZGrapher better... please <a target='_blank' href='//www.mathsnz.com/contact'>let me know</a>.<br>
				<td style='width:50%;padding-left:10px;vertical-align:top;padding-bottom:0px;padding-top:0px;'>
					<span style='display:block;width:100%;text-align:center;font-size:150%;'><b>Cost</b></span><br>
					NZGrapher is free for non-commercial <b>individual</b> use, you can however <a target='_blank' href='https://www.mathsnz.com/donate'>make a donation</a>.<br>
					<br>
					<b>Schools</b> are required to subscribe at a minimum of $0.50 per student using NZGrapher. <b>Commercial users</b> are also required to pay. Please visit the <a target='_blank' href='https://www.mathsnz.com/nzgrapher-invoice'>invoice creator</a> for details.<br>
					<br>
					Any questions about this please <a target='_blank' href='//www.mathsnz.com/contact'>get in touch</a>.
			</tr>
		</table>
		<br>
		<div style='max-width: 800px;margin: 0 auto;display: inline-block;border: none; position: relative;'>
		By pressing the button below you are acknowledging that NZGrapher uses cookies, and if you acting on behalf of a school, you are agreeing to the costs associated... if you're not happy with this don't use this website.<br>
		<button class=button style='margin-top:10px;margin-top: 10px;padding: 20px;border-radius: 15px;font-size: 18px;font-weight: bold;' id=hidewelcome onclick="$('#welcome').hide();document.cookie='welcome=yes; expires=<?php
			echo date(DateTime::RSS, strtotime('24 hours'));
		?>'">Start Using NZGrapher</button>
		<a href="http://eepurl.com/4JD3v" target='_blank'><button class=button style='margin-top:10px;margin-top: 10px;padding: 20px;border-radius: 15px;font-size: 18px;font-weight: bold;background-color:#C86400'>Newsletter Sign Up</button></a><br>
		<br>
		<div style='text-align:left;max-width: 800px;margin: 0 auto;display: inline-block;border: none; position: relative;'>
		<span style="display:block;font-size:150%;"><b>New in this version of NZGrapher (<?php echo $v; ?>)</b></span>
		<ul id=whatsnew>
		
		</ul>
		Full changes can be seen by changing the graph type to 'Change Log'.<br>
		<br>
		<?php
			if(strpos($_SERVER['SERVER_NAME'],'jake4maths.com')>-1 || strpos($_SERVER['SERVER_NAME'],'grapher.nz')>-1){
				echo "The version of NZGrapher you are using is hosted on my server.<br>";
			} else {
				echo "The version of NZGrapher you are using is not hosted on my server... if it's not working properly first make sure it is up to date, then check with your IT person... if they can't work out what is wrong let me know.<br>";
			};

		?>
		<br>
		</div>
		<script>
		$.get('./change log.php').done(function(data){
			data = data.substr(0,data.indexOf('</ul>'));
			data = data.substr(data.indexOf('<ul>')+4);
			$('#whatsnew').html(data);
		});
		</script>
		</div>
		
	</div>
</div>

<div id=tour onclick="document.getElementById('tour').style.display='none';document.cookie='overlay=none';" style='display:none';>
<table style='position:absolute;width:80%;height:80%;top:10%;left:10%;'>
	<tr><td style='vertical-align:top;text-align:left;'>
		<svg width="60" height="60">
			<defs>
				<marker id="arrow" markerWidth="13" markerHeight="13" refx="2" refy="6" orient="auto">
					<path d="M2,2 L2,11 L10,6 L2,2" style="fill:black;" />
				</marker>
			</defs>
			<path d="M50,50 L20,10"
				  style="stroke:black; stroke-width: 1.25px; fill: none;
						 marker-end: url(#arrow);"
			/>
		</svg><br>
		<span>Under data you can upload your own file, or paste in a table.</span><td><td><td><td style='vertical-align:top;text-align:right;'>
		<svg width="60" height="60">
			<defs>
				<marker id="arrow" markerWidth="13" markerHeight="13" refx="2" refy="6" orient="auto">
					<path d="M2,2 L2,11 L10,6 L2,2" style="fill:black;" />
				</marker>
			</defs>
			<path d="M10,50 L40,10"
				  style="stroke:black; stroke-width: 1.25px; fill: none;
						 marker-end: url(#arrow);"
			/>
		</svg><br>
		<span>Up here you can choose the folder you are wanting if your school has one set up (press enter after typing it in) or select the dataset from the folder. There are lots of good ones loaded in, or you can load your own in the top left.</span>
	<tr><td><td><td><td><td>
	<tr><td><span>This left hand area displays your data (including the id on the left). You can add or delete rows as well as edit the data just by clicking and typing on it. To save the changes click the save changes in the menu.</span><td colspan=2><span style='cursor: pointer;' onclick="document.getElementById('tour').style.display='none';document.cookie='overlay=none';">Okay, I got it.<br>Hide this overlay.</span><td><span>This area displays your graph.</span><td>
	<tr><td><td><td><td><td>
	<tr><td style='vertical-align:bottom;text-align:left;'>
		<span>Down here you can choose the x-variable and the y-variables as well as the type of graph you want to draw.</span><br>
		<svg width="60" height="60">
			<defs>
				<marker id="arrow" markerWidth="13" markerHeight="13" refx="2" refy="6" orient="auto">
					<path d="M2,2 L2,11 L10,6 L2,2" style="fill:black;" />
				</marker>
			</defs>
			<path d="M50,10 L20,50"
				  style="stroke:black; stroke-width: 1.25px; fill: none;
						 marker-end: url(#arrow);"
			/>
		</svg>
	<td><td><td><span>Down here you can change the options for the graph, including the titles and if the point labels are shown.</span><br>
			<svg width="60" height="60">
			<defs>
				<marker id="arrow" markerWidth="13" markerHeight="13" refx="2" refy="6" orient="auto">
					<path d="M2,2 L2,11 L10,6 L2,2" style="fill:black;" />
				</marker>
			</defs>
			<path d="M30,10 L30,50"
				  style="stroke:black; stroke-width: 1.25px; fill: none;
						 marker-end: url(#arrow);"
			/>
		</svg>
	<td>
</table>
</div>

<div id=progressbarholder style='z-index:16;position:fixed;height:100%;width:100%;top:0px;left:0px;background:rgba(255,255,255,0.5);display:none;'>
<div style='position:absolute;top:50%;left:50%;width:400px;height:150px;z-index:12;text-align:center;padding:5px;margin-left:-105px;margin-top:-155px;'>
<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=progresstitle>Progress</div>
<br><br><span id=progressdescription></span><br><span id=progresssize></span><br><br>
<img src='./loading.gif'>
</div>
</div>

<div id=pastetext style='z-index:16;position:fixed;height:100%;width:100%;top:0px;left:0px;background:rgba(255,255,255,0.5);display:none;'>
<div style='position:absolute;top:50%;left:50%;width:400px;height:300px;z-index:12;text-align:center;padding:5px;margin-left:-205px;margin-top:-155px;'>
<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=sampletitle>Paste your table here from Excel, Word or Google Sheets</div><br><small>Only use this if the import clipboard doesn't work, as is slower and doesn't allow for as much data to be imported.<br>Note: Pasting a table from Google Docs doesn't work properly.</small><br><br>
<textarea style='width:400px;height:140px;resize:none;' id=textarea data-gramm_editor="false"></textarea><br>
<br>
<a href='#' class=button onclick='document.getElementById("pastetext").style.display="none"' id=import>Import</a>
<a href='#' class=button onclick='document.getElementById("pastetext").style.display="none"'>Close</a>
</div>
</div>
<div id=pastelink style='z-index:16;position:fixed;height:100%;width:100%;top:0px;left:0px;background:rgba(255,255,255,0.5);display:none;'>
<div style='position:absolute;top:50%;left:50%;width:400px;height:300px;z-index:12;text-align:center;padding:5px;margin-left:-205px;margin-top:-155px;'>
<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=sampletitle>Paste your link here</div><br>
<small><i>Note: the link needs to be accessible on the web for this to work, and must be to a CSV file.</i></small><br>
<textarea style='width:400px;height:190px;resize:none;' id=linkarea data-gramm_editor="false"></textarea><br>
<br>
<a href='#' class=button onclick='document.getElementById("pastelink").style.display="none"' id=importlink>Import</a>
<a href='#' class=button onclick='document.getElementById("pastelink").style.display="none"'>Close</a>
</div>
</div>

<?php
	if(empty($_GET['folder'])){
		$_GET['folder']="datasets";
	}
	$_GET['folder']=basename($_GET['folder']);
	if(substr($_GET['folder'],0,1)=="."){$_GET['folder']="datasets";$_GET['dataset']="Babies.csv";}
?>

</div>

<div style="display:none">
<?php
function isSecure() {
  return
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || $_SERVER['SERVER_PORT'] == 443;
}

$protocol = isSecure() === true ? 'https://' : 'http://';
$actual_link = urlencode($protocol.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$time = date('U');
$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$ip = getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');
if(file_exists('./windowsapp.php')){$actual_link='Windows App v2';}
echo "<script>
var ipaddress = '$ip';
$.get('https://tracking.jake4maths.com/trackingimage.php?v=$v&url=$actual_link&time=$time&r=$randomString');
</script>";
?>
</div>

<div id=top>
	<div style="font-size:30px;background:none;border:none;position:absolute;top:3px;left:5px;z-index:4"><img src='logow.png' height=37></div>
	<div style="position:absolute;top:18px;right:35px;background:none;border:none;">
	<form id=datafolder method=get style='display:inline;'>
		Folder: <input type="text" name=folder value="<?php echo $_GET['folder'];?>" style='width:80px;height:27px;'>
	</form>
	<form id=datasource method=get style='display:inline;'>
		<input type=hidden name=folder value="<?php echo $_GET['folder'];?>">
		Data Source: <select name=dataset onChange="document.getElementById('datasource').submit();" style='width:180px;height:27px;padding:0px;'>
<?php

	$files=glob($_GET['folder'].'/*.csv');
	$files2=glob($_GET['folder'].'/*.CSV');
	$files=array_merge($files,$files2);
	foreach($files as $key => $file){
		$files[$key] = substr($file, strlen($_GET['folder'])+1);
	}
	sort($files);
	if(array_key_exists('dataset',$_GET)){
		$dataset=$_GET['dataset'];
	} else if (array_key_exists(0, $files)) {
		$dataset=$files[0];
	} else {
		echo '<option>ERROR</option>';
	}
	$dataset=basename($dataset);
	foreach($files as $file){
		if (strtolower(substr($file,-3))=='csv'){
			echo "<option";
			if($file==$dataset){echo " selected";}
			echo">$file</option>";
		}
	}
?>
		</select>
	</form>
	</div>
	<img src='3dots.png' id='3dots' style="position:absolute;height:25px;right:15px;top:17px;z-index:10;cursor:pointer;">
</div>
<div class="callout popup" id=showhideleftbottom style='position:absolute;right:5px;left:auto;top:45px;'>
<ul>
	<li id=showhideleftli onclick="showhideleft();">Hide Left Section</li>
	<li id=showhidebottomli onclick="showhidebottom();">Hide Bottom Section</li>
</ul>
</div>

<div id=showhideleft onclick="showhideleft();" style='display:none;'>&#9664;</div>
<div id=showhidebottom onclick="showhidebottom();" style='display:none;'>&#9660;</div>

<div id=buttons><tr>
<div class=abutton id=fileshowhide>Data</div> <div class=spacer></div>
<div class=abutton id=rowshowhide>Row</div> <div class=spacer></div>
<div class=abutton id=colshowhide>Column</div> <div class=spacer></div>
<div class=abutton id=samshowhide>Sample and More</div> <div class=spacer></div>
<div class=abutton id=teachingtoolsshowhide>Teaching Tools</div> <div class=spacer></div>
<div class=abutton id=update>Save Changes</div> <div class=spacer></div>
<div class=abutton id=helper>Help</div>
</div>

<div id=helppopup class="callout popup">
<ul>
	<li onclick="window.open('//www.mathsnz.com/nzgrapher-info/video-tutorials','_blank');try{ga('send', 'event', 'Function', 'Help - Video Tutorials', '');} catch(err) {console.log(err.message);}">Video Tutorials</li>
	<li onclick="window.open('//www.mathsnz.com/nzgrapher-info/graph-information','_blank');try{ga('send', 'event', 'Function', 'Help - Graph Infomation', '');} catch(err) {console.log(err.message);}">Graph Information</li>
	<li onclick="window.open('//www.mathsnz.com/nzgrapher-info/function-information','_blank');try{ga('send', 'event', 'Function', 'Help - Function Infomation', '');} catch(err) {console.log(err.message);}">Function Information</li>
	<li onclick="window.open('//www.mathsnz.com/nzgrapher-info/dataset-information','_blank');try{ga('send', 'event', 'Function', 'Help - Dataset Infomation', '');} catch(err) {console.log(err.message);}">Dataset Information</li>
	<li onclick="document.getElementById('welcome').style.display='block';try{ga('send', 'event', 'Function', 'Help - Show Welcome', '');} catch(err) {console.log(err.message);}">Show Welcome</li>
	<li onclick="document.getElementById('tour').style.display='block';try{ga('send', 'event', 'Function', 'Help - Show Overlay', '');} catch(err) {console.log(err.message);}">Show Overlay</li>
</ul>
</div>

<div class="callout popup" id=teachingtoolsbox>
<ul>
	<li id=bs>Bootstrapping</li>
	<li id=rerand>Re-Randomisation</li>
	<li id=samvar>Sampling Variability</li>
	<li onclick="window.open('//www.mathsnz.com/nzgrapher-info/teaching-tools','_blank');try{ga('send', 'event', 'Function', 'Teaching Tools - Video Tutorials', '');} catch(err) {console.log(err.message);}">Video Tutorials</li>
</ul>
</div>

<div class="callout popup" id=rowbox>
<ul>
	<li id=addrow>Add Row</li>
	<li id=delrow>Delete Last Row</li>
	<li id=delspecrow>Delete Specific Row</li>
</ul>
</div>

<div class="callout popup" id=filepop>
<ul>
	<li id=uploadfileclicknew>
	<input type="file" name="filenew" id="filenew" style='width:80px;height:17px;position:absolute;top:4px;left:6px;z-index:2;opacity:0;cursor:pointer;'/>
	<div style='color:#000;border:0px solid #ccc;width:80px;height:17px;border-radius:0px;padding:5px;font-size:14px;text-align:left;position:relative;top:0px;left:0px;padding:0px;background:none;'>Open File</div>
	</li>
	<li id=directimport>Import from Clipboard</li>
	<li id=pastetableclick>Paste Table (Legacy)</li>
	<li id=pastelinkclick>Open Link</li>
	<li id=highlightdatatable>Select Data Table</li>
<?php
if(substr($dataset,0,6)!="SECURE"){
	echo "<li><a href='#' id=download style='text-decoration:none;color:#000;'>Download Data</a></li>";
}
?>
	<li onclick='updatereset();'>Save Current State for Reset</li>
	<li id=reset>Reset</li>
</ul>
</div>

<div class="callout popup" id=colbox>
<ul>
	<li id=addcol>Add Column</li>
	<li id=delcol>Delete Last Column</li>
	<li id=delspeccol>Delete Specific Column</li>
</ul>
</div>

<div class="callout popup" id=sambox>
<ul>
	<li id=sample>Sample</li>
	<li id=sort>Sort</li>
	<li id=reorder>Reorder Variable</li>
	<li id=newvar>Create New Variable (From 2 Variables)</li>
	<li id=newvarc2>Create New Variable (Linear Function)</li>
	<li id=newvarc3>Create New Variable (From Condition)</li>
	<li id=regroup>Create New Variable (Re-Group)</li>
	<li id=filter>Filter Data</li>
	<li id=addindex onclick="addindex()">Add Graphable Index Column</li>
	<li id=addindex onclick="converttimeshow()">Convert Time Column</li>
	<li id=addindex onclick="encodetimeshow()">Encode Time Column</li>
</ul>
</div>

<div id=left>
<div id="samvardiv" style="display:none;z-index:11;position:absolute;top:30px;left:30px;right:30px;bottom:30px;text-align:center;padding:10px;overflow-y: auto;">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=sampletitle>Sampling Variability</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' onclick="$('#data').html($('#presampledataholder').html());$('#data td div').attr('contenteditable','true');updatebox();$('#samvardiv').hide();">&times;</div><br>
	<span id=samvarcontents style="font-size:14px">
		This section lets you re-sample the same data while looking at a graph to see what happens when you take repeated samples.<br>
		This is really useful for teaching sampling variability.<br>
		You can see a <a href='https://www.mathsnz.com/nzgrapher-info/teaching-tools' target='_blank'>video tutorial on how to use this here</a>.<br>
				<br>
		Sample With: <select style='width:120px' onChange="" id=samvaron></select><br><br>
		<center>
		<table id=samvartable style='text-size:14px;'>
			<tr><td> <td><input id="samvar-">
		</table><br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;line-height:60px;' id=samvargo>(Re)Sample</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=samvaranimateslow>Animate (Slow)</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=samvaranimatefast>Animate (Fast)</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=samvarstop>Stop</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=samvarreset>Reset Data</a>
		<br><br>
		Often it is useful to <b>fix the axis</b> when looking at scatter graphs and or dot plots.<br><br>
		<span href='#' style="text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;cursor:pointer;" onclick='lockaxis()'>Lock Axis Values</span>
		<span href="#" style="text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;cursor:pointer;" onclick="$('#options input').val('auto');">Reset</span>
		<span href='#' style="text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;cursor:pointer;" onclick='moreoptions()'>More Options</span><br>
		</center>
	</span>
</div>
<div id="rerandteachdiv" style="display:none;z-index:11;position:absolute;top:30px;left:30px;right:30px;bottom:30px;text-align:center;padding:10px;overflow-y: auto;">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=rerandteachtitle>Re-Randomisation Teaching Tool</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' onclick="$('#rerandteachdiv').hide();$('#type').val('newabout');">&times;</div><br>
	<span id=rerandteachcontents style="font-size:14px">
		This section lets you manually step through a re-randomisation.<br>
		This is really useful for teaching re-randomisation.<br>
		You can see a <a href='https://www.mathsnz.com/nzgrapher-info/teaching-tools' target='_blank'>video tutorial on how to use this here</a>.<br>
		<br>
		Re-randomisation works by taking the sample, and randomly assigning each of the data points to one of the groups in the same proportion as the sample. 
		It then works out the median or mean for each group and plots the difference between them on the graph below.
		It does this 1000 times and then works out the proportion that are equal or greater than the sample difference.<br>
		<br>
		<b>Do ONE re-randomisation and plot on graph:</b><br>
		<br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=rereandteachoneslow>Slow</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=rereandteachonefast>Fast</a>
		<br><br>
		<b>Step through remaining <span id=rerandteachremaining>1000</span> re-randomisations:</b><br>
		<br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=rereandteachrestslow>Slow</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=rereandteachrestmedium>Medium</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=rereandteachrestfast>Fast</a>
		<br><br>
		<b>Other Controls:</b><br>
		<br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=rerandteachpause>Pause</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=rerandteachreset>Reset</a>
		<br><br>
		
	</span>
</div>

<div id="bsteachdiv" style="display:none;z-index:11;position:absolute;top:30px;left:30px;right:30px;bottom:30px;text-align:center;padding:10px;overflow-y: auto;">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=rerandteachtitle>Bootstrapping Teaching Tool</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' onclick="$('#bsteachdiv').hide();$('#type').val('newabout');">&times;</div><br>
	<span id=rerandteachcontents style="font-size:14px">
		This section lets you manually step through creating a bootstrap confidence interval.<br>
		This is really useful for teaching bootstrapping.<br>
		You can see a <a href='https://www.mathsnz.com/nzgrapher-info/teaching-tools' target='_blank'>video tutorial on how to use this here</a>.<br>
		<br>
		Bootstrapping works by taking the sample, and randomly choosing as many points as in the original sample from the original sample, using replacement. 
		This means that each point can potentially be chosen multiple times, or not at all.
		It then works out the median or mean for each group and plots the difference between them on the graph below.
		It does this 1000 times and shows the interval for the middle 95% (or 950 bootstrap samples).<br>
		<br>
		<b>Do ONE bootstrap sample and plot on graph:</b><br>
		<br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=bsteachoneslow>Slow</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=bsteachonefast>Fast</a>
		<br><br>
		<b>Step through remaining <span id=bsteachremaining>1000</span> bootstrap samples:</b><br>
		<br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=bsteachrestslow>Slow</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=bsteachrestmedium>Medium</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=bsteachrestfast>Fast</a>
		<br><br>
		<b>Other Controls:</b><br>
		<br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=bsteachpause>Pause</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=bsteachreset>Reset</a>
		<br><br>
		
	</span>
</div>
<?php
echo "<table id=data></table>";
if(empty($dataset)){
	echo "No such file";
	die();
}

echo '<script>var csv_data;</script>';

if(isset($_POST['csv_data'])){
	$csv_data=urldecode($_POST['csv_data']);
	echo "<script>
		csv_data = ".json_encode($csv_data).";
		loaddata();
	</script>";
} else {
	if(isset($_GET['url'])){
		$dataset = $_GET['url'];
		echo "<script>
			loaddatafromurl(".json_encode($dataset).");
		</script>";
	} else {
		$link = dirname((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]");
		$dataset = $link.'/'.$_GET['folder'].'/'.$dataset;
		echo "<script>
			loaddatafromurl(".json_encode($dataset).");
		</script>";
	}
}

?>
</div>
<div id=variable>
<table style='margin-left:3px;'>
<tr><td><span id=var1label>variable 1:</span><td style='width:125px'>
<select style='width:120px;display:none;' onChange="document.getElementById('xaxis').value=this.options[this.selectedIndex].text;updategraph();" name=xvals id=xvar>
	<option value=" "> </option>
</select><td>Graph Type: <td>
<select style='width:120px' id=type onChange="graphchange(this);">
	<option value='newabout'>About</option>
	<option disabled></option>
	<option value='newpairsplot'>Pairs Plot</option>
	<option disabled></option>
	<option value='newdotplot'>Dot Plot (and Box and Whisker)</option>
	<option value='newbargraph'>Bar Graph</option>
	<option value='newbargraphf'>Bar Graph - Summary Data</option>
	<option value='newhistogram'>Histogram</option>
	<option value='newhistogramf'>Histogram - Summary Data</option>
	<option value='newpiechart'>Pie Chart</option>
	<option value='newscatter'>Scatter Graph</option>
	<option value='newresiduals'>Residuals Plot</option>
	<option disabled></option>
	<option value='newbootstrap'>Bootstrap Single Variable</option>
	<option value='newbootstrapcimedian'>Bootstrap Confidence Interval - Median</option>
	<option value='newbootstrapcimean'>Bootstrap Confidence Interval - Mean</option>
	<option style='display:none' value='newbsteach'>Boostrap - Teaching</option>
	<option disabled></option>
	<option value='newrerandmedian'>Re-Randomisation - Median</option>
	<option value='newrerandmean'>Re-Randomisation - Mean</option>
	<option style='display:none' value='newrerandteach'>Re-Randomisation - Teaching</option>
	<option disabled></option>
	<option value='newpairedexperiment'>Paired Experiment Dot Plot (and Arrows Graph)</option>
	<option disabled></option>
	<option value='newtimeseries'>Time Series</option>
	<option value='newtimeseriesrecomp'>&nbsp;&nbsp;&nbsp;Re-Composition</option>
	<option value='newtimeseriesseasonaleffects'>&nbsp;&nbsp;&nbsp;Seasonal Effects</option>
	<option value='newtimeseriessforecasts'>&nbsp;&nbsp;&nbsp;Forecasts</option>
	<option disabled></option>
	<option value='newchangelog'>Change Log</option>
	<option value='newupdate'>Update</option>
</select>
<tr><td><span id=var2label>variable 2:</span><td>
<select style='width:120px;display:none;' onChange="document.getElementById('yaxis').value=this.options[this.selectedIndex].text;updategraph();" name=yvals id=yvar>
</select>
<td>Colour by: <td>
<select style='width:120px;display:none' onChange="document.getElementById('colorlabel').value=this.options[this.selectedIndex].text;updategraph();" name=color id=color>
<tr><td><span id=var3label>variable 3:</span><td>
<select style='width:120px;display:none;' onChange="updategraph();" name=zvals id=zvar>
</select>
<td>
</table>
	<input type="hidden" id=width name=width value=500>
	<input type="hidden" id=height name=height value=500>
</div>

<div id=graphdiv>
<div id=jsgraph></div>
<canvas id="myCanvas" width="600" height="400" style='display:none'></canvas>
</div>
<div id=controls>
<table style='width:100%;'>
	<tr><td style='width:130px;font-size:12px;'>
		<div id=addtograph>
			<span id=arrowsshow>
				<label><input type="checkbox" onclick="updategraph();" id="arrows" name="arrows" value="yes"> Arrows</label><br>
			</span>
			<span id=btypeshow>
				Bootstrap:<br>
				<select onchange="updategraph();" id="btype" name="btype" value="Median" style='width:100px;'>
					<option>Median</option>
					<option>Mean</option>
					<option>IQR</option>
					<option>Standard Deviation</option>
				</select>
			</span>
			<span id=regshow><label>
				<input type="checkbox" onclick="updategraph();" id="regression" name="regression" value="yes">
					<span id=sum>Summaries</span>
					<span id=reg>Regression Line</span>
					<span id=for>Forecast output</span>
				</label><br>
			</span>
			<span id=boxplotshow><label>
				<input type="checkbox" onclick="updategraph();" id="boxplot" name="boxplot" value="yes"> Box Plots</label><br>
			</span>
			<span id=stripgraphshow><label>
				<input type="checkbox" onclick="updategraph();" id="stripgraph" name="stripgraphshow" value="yes"> Strip Graph</label><br>
			</span>
			<span id=highboxplotshow><label>
				<input type="checkbox" onclick="updategraph();" id="highboxplot" name="highboxplot" value="yes"> High Box Plot</label><br>
			</span>
			<span id=boxnowhiskershow><label>
				<input type="checkbox" onclick="updategraph();" id="boxnowhisker" name="boxnowhisker" value="yes"> Box (No Whisker)</label><br>
			</span>
			<span id=boxnooutliershow><label>
				<input type="checkbox" onclick="updategraph();" id="boxnooutlier" name="boxnooutlier" value="yes"> Box (No Outlier)</label><br>
			</span>
			<span id=intervalshow>
				<label><input type="checkbox" onclick="updategraph();" id="interval" name="interval" value="yes"> Informal C-I</label><br>
				<label><input type="checkbox" onclick="updategraph();" id="intervallim" name="intervallim" value="yes"> C-I Limits</label><br>
			</span>
			<span id=labelshow><label>
				<input type="checkbox" onclick="updategraph();" id="labels" name="labels" value="yes"> Point Labels</label><br>
			</span>
			<span id=meandotshow><label>
				<input type="checkbox" onclick="updategraph();" id="meandot" name="meandot" value="yes"> Mean Dot</label><br>
			</span>
			<span id=stackdotsshow><label>
				<input type="checkbox" onclick="updategraph();" id="stackdots" name="stackdots" value="yes"> Stack Dots</label><br>
			</span>
			<span id=jittershow><label>
				<input type="checkbox" onclick="updategraph();" id="jitter" name="jitter" value="yes"> Jitter</label><br>
			</span>
			<span id=quadraticshow><label>
				<input type="checkbox" onclick="updategraph();" id="quadratic" name="quadratic" value="yes"> Quadratic</label><br>
			</span>
			<span id=cubicshow><label>
				<input type="checkbox" onclick="updategraph();" id="cubic" name="cubic" value="yes"> Cubic</label><br>
			</span>
			<span id=expshow><label>
				<input type="checkbox" onclick="updategraph();" id="exp" name="exp" value="yes"> y=a*exp(b*x)</label><br>
			</span>
			<span id=logshow><label>
				<input type="checkbox" onclick="updategraph();" id="log" name="log" value="yes"> y=a*ln(x)+b</label><br>
			</span>
			<span id=powshow><label>
				<input type="checkbox" onclick="updategraph();" id="pow" name="pow" value="yes"> y=a*x^b</label><br>
			</span>
			<span id=yxshow><label>
				<input type="checkbox" onclick="updategraph();" id="yx" name="yx" value="yes"> y=x</label><br>
			</span>
			<span id=differentaxisshow><label>
				<input type="checkbox" onclick="updategraph();" id="differentaxis" name="differentaxis" value="yes"> Different Axis</label><br>
			</span>
			<span id=weightedaverageshow><label>
				<input type="checkbox" onclick="updategraph();" id="weightedaverage" name="weightedaverage" value="no"> Weighted Average</label><br>
			</span>
			<span id=residualsforcexshow><label>
				<input type="checkbox" onclick="updategraph();" id="residualsforcex" name="residualsforcex" value="yes"> Change X Axis</label><br>
			</span>
			<span id=regtypeshow>
				Regression Type:<br>
				<select onchange="updategraph();" id="regtype" name="regtype" value="Linear">
					<option>Linear</option>
					<option>Quadratic</option>
					<option>Cubic</option>
					<option>y=a*exp(b*x)</option>
					<option>y=a*ln(x)+b</option>
					<option>y=a*x^b</option>
				</select>
			</span>
			<span id=longtermtrendshow><label>
				<input type="checkbox" onclick="if(this.checked==true){$('#seasonal')[0].checked=true;}else{$('#seasonal')[0].checked=false;};updategraph();" id="longtermtrend" name="longtermtrend" value="yes"> Long Term Trend (STL)</label><br>
			</span>
			<span id=seasonalshow><label>
				<input type="checkbox" onclick="if(this.checked==true){$('#longtermtrend')[0].checked=true;};updategraph();" id="seasonal" name="seasonal" value="yes"> Seasonal (STL)</label><br>
			</span>
			<span id=startfinishshow><label>
				<input type="checkbox" onclick="updategraph();" id="startfinish" name="startfinish" value="yes"> Start / End Points</label><br>
			</span>
			<span id=gridlinesshow><label>
				<input type="checkbox" onclick="updategraph();" id="gridlines" name="gridlines" value="yes"> Gridlines</label><br>
			</span>
			<span id=addmultshow>
				Type:<br>
				<select onchange="updategraph();" id="addmult" name="addmult" value="Additive">
					<option>Additive</option>
					<option>Multiplicative</option>
				</select>
			</span>
			<span id=percent100show><label>
				<input type="checkbox" onclick="updategraph();" id="percent100" name="percent100" value="yes"> 100% Bar Graph</label><br>
			</span>
			<span id=relativefrequencyshow><label>
				<input type="checkbox" onclick="updategraph();" id="relativefrequency" name="relativefrequency" value="yes"> Relative Freq.</label><br>
			</span>
			<span id=relativewidthshow><label>
				<input type="checkbox" onclick="updategraph();" id="relativewidth" name="relativewidth" value="yes"> Relative Width</label><br>
			</span>
			<span id=donutshow><label>
				<input type="checkbox" onclick="updategraph();" id="donut" name="donut" value="yes"> Donut</label><br>
			</span>
			<span id=normalshow><label>
				<input type="checkbox" onclick="updategraph();" id="normaldist" name="normaldist" value="yes"> Normal Dist.</label><br>
			</span>
			<span id=rectangularshow><label>
				<input type="checkbox" onclick="updategraph();" id="rectangulardist" name="rectangulardist" value="yes"> Rectangular Dist.</label><br>
			</span>
			<span id=triangularshow><label>
				<input type="checkbox" onclick="updategraph();" id="triangulardist" name="triangulardist" value="yes"> Triangular Dist.</label><br>
			</span>
			<span id=poissonshow><label>
				<input type="checkbox" onclick="updategraph();" id="poissondist" name="poissondist" value="yes"> Poisson Dist.</label><br>
			</span>
			<span id=binomialshow><label>
				<input type="checkbox" onclick="updategraph();" id="binomialdist" name="binomialdist" value="yes"> Binomial Dist.</label><br>
			</span>
			<span id=invertshow><label>
				<input type="checkbox" onclick="updategraph();" id="invert" name="invert" value="yes"> Invert Colours</label><br>
			</span>
			<span id=thicklinesshow><label>
				<input type="checkbox" onclick="updategraph();" id="thicklines" name="thicklines" value="yes"> Thick Lines</label><br>
			</span>
			<span id=removedpointsshow><label>
				<input type="checkbox" onclick="updategraph();" id="removedpoints" name="removedpoints" value="yes" checked> Show ID of Removed Points</label><br>
			</span>
			<span id=grayscaleshow><label>
				<input type="checkbox" onclick="updategraph();" id="grayscale" name="grayscale" value="yes"> Gray Scale <br>(do not use on Firefox)</label><br>
			</span>
		</div>
		<td><span style='font-size:12px;line-height:27px;'>
			<span style='display:inline-block;width:50px;'>Title: </span><input type="text" id="title" name="title" value="Graph Title"><br>
			<span style='display:inline-block;width:50px;'>x-axis: </span><input type="text" id="xaxis" name="xaxis" value="X Axis Title"><br>
			<span style='display:inline-block;width:50px;'>y-axis: </span><input type="text" id="yaxis" name="yaxis" value="Y Axis Title"><br>
			<span id=colorname><span style='display:inline-block;width:50px;'>Colour: </span><input type="text" id="colorlabel" name="colorlabel" value="Color Label"><br></span>
			<span style='display:inline-block;width:50px;'>Size: </span><select id="standardsize" name="standardsize" onchange="updategraph()" style='width:120px;'>
				<option>Auto</option>
				<?php
				//echo "<option>Auto - High Res</option>";
				?>
				<option>Standard</option>
				<option>Short</option>
				<option>Small</option>
			</select>
			</span>
</table>
<div id=sizediv><span id=pointsizename>Point Size:</span> <input id=size type="range" min=3 max=19 step=1 value=7	onchange="updategraph()"></div>
<div id=transdiv>Transparency: <input id=trans type="range" min=0 max=100 step=10 value=50 onchange="updategraph()"></div>
<div id=updater>
<?php
if(isset($_GET['dev'])){
	?>
<span onclick='reload_js()'>Reload JS</span>

	<?php
}
?>
<span onclick="moreoptions()">More Options</span> <span onclick="updatebox()">Update Graph</span></div>
</div>
<div id=presampledataholder style="display:none;"></div>
<div id=loading>
	<br><br><br><br><br><br>Loading...
</div>
<div id=updating>
	<br><br><br><br><br><br>Updating...
</div>
<div id=sampling>
	<br><br><br><br><br><br>Sampling...
</div>
<div id="regroupdiv" style="z-index:99;max-height:80%;overflow-y:auto;display:none;padding:10px;position:absolute;border:none;box-shadow: 0px 0px 10px rgba(0,0,0,0.5);top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);transform: translate(-50%,-50%);">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;'>Re-Group</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' class=close>&times;</div><br>
	<span id=samplecontents style="font-size:14px">
		Re-group With: <select style='width:120px' onChange="" id=regroupwith></select><br><br>
		<center>
		<table id=regrouptable style='text-size:14px;'>
			<tr><td> <td><input id="regroup-">
		</table><br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=regroupgo>Re-Group</a><br><br>
		</center>
	</span>
</div>
<div id="samplediv" style="z-index:99;max-height:80%;overflow-y:auto;display:none;padding:10px;position:absolute;border:none;box-shadow: 0px 0px 10px rgba(0,0,0,0.5);top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);transform: translate(-50%,-50%);">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=sampletitle>Sample</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' class=close>&times;</div><br>
	<span id=samplecontents style="font-size:14px">
		Sample With: <select style='width:120px' onChange="" id=sampleon></select><br><br>
		<center>
		<table id=samplingtable style='text-size:14px;'>
			<tr><td> <td><input id="sample-">
		</table><br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=samplego>Sample</a><br><br>
		</center>
	</span>
</div>
<div id="deletecoldiv" style="z-index:99;max-height:80%;overflow-y:auto;display:none;padding:10px;position:absolute;border:none;box-shadow: 0px 0px 10px rgba(0,0,0,0.5);top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);transform: translate(-50%,-50%);">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=sampletitle>Remove Column</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' class=close>&times;</div><br>
	<span id=samplecontents style="font-size:14px">
		<center>
		Which Column: <select style='width:120px' onChange="" id=columndel></select><br><br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=deletecolgo>Remove</a><br><br>
		</center>
	</span>
</div>
<div id="orderdiv" style="z-index:6;display:none;padding:10px;position:absolute;border:none;box-shadow: 0px 0px 10px rgba(0,0,0,0.5);top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);transform: translate(-50%,-50%);">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=ordertitle>Reorder</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' class=close>&times;</div><br>
	<span id=samplecontents style="font-size:14px">
		Reorder: <select style='width:120px' onChange="" id=orderby></select><br><br>
		<center>
		<table id=orderingtable style='text-size:14px;'>
		</table><br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=ordergo>Reorder</a><br><br>
		</center>
	</span>
</div>
<div id="newvardiv" style="z-index:6;display:none;padding:10px;position:absolute;border:none;box-shadow: 0px 0px 10px rgba(0,0,0,0.5);top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);transform: translate(-50%,-50%);">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=ordertitle>Create New Variable (From 2 Variables)</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' class=close>&times;</div><br>
	<span id=samplecontents style="font-size:14px">
		<select style='width:120px' onChange="" id=newvar1></select>
		<select style='width:50px' onChange="" id=newvarcom>
			<option>+</option>
			<option>-</option>
			<option>&times;</option>
			<option>&divide;</option>
		</select>
		<select style='width:120px' onChange="" id=newvar2></select><br><br>
		<center>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=creatego>Create</a><br><br>
		</center>
	</span>
</div>
<div id="newvarcdiv" style="z-index:6;display:none;padding:10px;position:absolute;border:none;box-shadow: 0px 0px 10px rgba(0,0,0,0.5);top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);transform: translate(-50%,-50%);">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=ordertitle>Create New Variable (Linear Function)</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' class=close>&times;</div><br>
	<span id=samplecontents style="font-size:14px">
		<select style='width:120px' onChange="" id=newvarcx></select>
		<select style='width:50px' onChange="" id=newvarcmd>
			<option>&times;</option>
			<option>&divide;</option>
		</select>
		<input style='width:50px' onChange="" id=newvarca>
		<select style='width:50px' onChange="" id=newvarcas>
			<option>+</option>
			<option>-</option>
		</select>
		<input style='width:50px' onChange="" id=newvarcb><br><br>
		<center>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=createcgo>Create</a><br><br>
		</center>
	</span>
</div>
<div id="newvarc3div" style="z-index:6;display:none;padding:10px;position:absolute;border:none;box-shadow: 0px 0px 10px rgba(0,0,0,0.5);top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);transform: translate(-50%,-50%);min-width:300px;">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=ordertitle>Create New Variable (From Condition)</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' class=close>&times;</div><br>
	<span id=samplecontents style="font-size:14px">
		<b>With Variable:</b> <select style='width:120px' onChange="" id=newvarc3var></select><br><br>
		<b>Conditions:</b><br>
		<span id=newvarc3conds>
		</span>Otherwise: 'Other'<br><br>
		<center>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' onclick='addnewcond()'>Add Condition</a>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=createc3go>Create</a><br><br><br>
		</center>
	</span>
</div>
<div id="sortdiv" style="z-index:6;display:none;padding:10px;position:absolute;border:none;box-shadow: 0px 0px 10px rgba(0,0,0,0.5);top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);transform: translate(-50%,-50%);">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=ordertitle>Sort Data by Variable</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' class=close>&times;</div><br>
	<span id=sortcontents style="font-size:14px">
		Sort By: <select style='width:120px' onChange="" id=sortby></select><br><br>
		<center>
		<br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=sortgo>Sort</a><br><br>
		</center>
	</span>
</div>
<div id="filterdiv" style="z-index:6;display:none;padding:10px;position:absolute;border:none;box-shadow: 0px 0px 10px rgba(0,0,0,0.5);top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);transform: translate(-50%,-50%);">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=filtertitle>Filter Data</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' class=close>&times;</div><br>
	<span id=filtercontents style="font-size:14px">
		Filter By: <select style='width:120px' onChange="" id=filterby></select><br>
		Between: <input style='width:50px' onChange="" id=filtermin>
		and: <input style='width:50px' onChange="" id=filtermax>
		<center>
		<br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=filtergo>Filter</a><br><br>
		</center>
	</span>
</div>
<div id="converttimediv" style="z-index:6;display:none;padding:10px;position:absolute;border:none;box-shadow: 0px 0px 10px rgba(0,0,0,0.5);top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);transform: translate(-50%,-50%);">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=filtertitle>Convert Time Column</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' class=close>&times;</div><br>
	<span id=filtercontents style="font-size:14px">
		Time Column: <select style='width:120px' onChange="" id=converttimecol></select><br>
		Convert To: <select style='width:120px' onChange="" id=converttimeto>
			<option>Seconds</option>
			<option>Minutes</option>
			<option>Hours</option>
			<option>Days</option>
		</select>
		<center>
		<br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' onclick='converttimego()'>Convert</a><br><br>
		</center>
	</span>
</div>
<div id="encodetimediv" style="z-index:6;display:none;padding:10px;position:absolute;border:none;box-shadow: 0px 0px 10px rgba(0,0,0,0.5);top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);transform: translate(-50%,-50%);">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=filtertitle>Encode Time Column</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' class=close>&times;</div><br>
	<span id=filtercontents style="font-size:14px">
		This is used to encode a normal time or date field into a format NZGrapher can use for it's time series module.<br>
		<table>
		<tr><td>Time Column:<td><select style='width:120px' onChange="" id=encodetimecol></select><br>
		<tr><td>Average / Sum:<td><select style='width:120px' onChange="" id=encodetimesumavg>
			<option value=avg>Average (Mean)</option>
			<option value=sum>Sum</option>
		</select><br>
		<tr><td>Season Length:<td><select style='width:120px' onChange="customshowhide()" id=encodetimetype>
			<option>Quarter</option>
			<option>Month</option>
			<option>Day</option>
			<option>Hour</option>
			<option>Custom</option>
		</select><br>
		<tr class=encodecustomshow><td>Custom Period Length:<td><input id=encodelength><select id=encodemult>
			<option value=1000>Seconds</option>
			<option value=60000>Minutes</option>
			<option value=3600000>Hours</option>
			<option value=86400000>Days</option>
		</select><br>
		<tr class=encodecustomshow><td>Custom Seasons Per Period:<td><input id=encodeseasons><br>
		<tr class=encodecustomshow><td>Custom Seasons Start:<td><input id=encodestart> (must be equal to or before the first time)<br>
		</table>
		<br>
		<center>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' onclick='encodetimego()'>Encode</a><br><br>
		</center>
	</span>
</div>
<div id=cover style="width:100%;height:100%;position:fixed;top:0px;left:0px;background-color:rgba(255,255,255,0.5);z-index:20;display:none;" onclick="$('#cover').hide();$('#options').hide();"></div>
<div id=options style="width:35%;height:50%;position:fixed;top:20%;left:2.5%;border:1px solid #ccc;z-index:21;display:none;">
	<div style="background-color:rgba(0, 100, 200, 0.85096);width:100%;text-align:center;height:25px;position:absolute;top:0px;left:0px;border:none;color:#fff;">
		More Options
		<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' onclick="$('#cover').hide();$('#options').hide();">&times;</div>
	</div>
	<div style='position:absolute;left:0px;top:25px;right:0px;bottom:0px;overflow:auto;padding:5px;font-size:12px;'>
		<table>
			<tr><td colspan=2><b>Dot Plots (and Box and Whisker):</b><br>
			For creating axis limits it pretend the ____ value is ____
			<tr><td>Min:<td><input id=boxplotmin value=auto>
			<tr><td>Max:<td><input id=boxplotmax value=auto>
			<tr><td>&nbsp;
			<tr><td colspan=2><b>Scatter Graphs:</b><br>
			For creating axis limits it pretend the ____ value is ____
			<tr><td>Min X:<td><input id=scatplotminx value=auto>
			<tr><td>Max X:<td><input id=scatplotmaxx value=auto>
			<tr><td>Min Y:<td><input id=scatplotminy value=auto>
			<tr><td>Max Y:<td><input id=scatplotmaxy value=auto>
		</table>
		<br><br>
		<span href="#" style="text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;cursor:pointer;" onclick="$('#options input').val('auto');updategraph();">Reset</span>
		<span href="#" style="text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;cursor:pointer;" onclick="updategraph()">Update Graph</span>
	</div>
</div>
<div id=tooltip style='display:none;text-align:left;font-size:12px;transform:translateY(-50%);position:fixed;top:100px;left:100px;z-index:99999;border:0px solid #000;background-color:#000;color:#fff;padding:5px;padding-top:3px;padding-bottom:3px;'>
  <div id=tooltiparrow style='background:none;z-index:99999;position:absolute;top:50%;margin-top:-8px;left:-8px;width: 0;height: 0;border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-right:8px solid #000;'></div>
  <span style='padding:0px;margin:0px;text-align:left'>Tip</span>
</div>
<map name=graphmap id=graphmap></map>
</body>
</html>
