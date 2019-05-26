<?php include './version.php'; ?>
<html v=<?php echo $v; ?>>
<head>
	<script src="./jquery-1.11.1.min.js"></script>
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
	<link rel="canonical" href="https://grapher.jake4maths.com/" />
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
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-19339458-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</head>
<body>
<script>
if (screen.availWidth < 760)
{
    var mvp = document.getElementById('vp');
    mvp.setAttribute('content','width=1024');
}
</script>
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

<div id=pastetext style='z-index:16;position:fixed;height:100%;width:100%;top:0px;left:0px;background:rgba(255,255,255,0.5);display:none;'>
<div style='position:absolute;top:50%;left:50%;width:400px;height:300px;z-index:12;text-align:center;padding:5px;margin-left:-205px;margin-top:-155px;'>
<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=sampletitle>Paste your table here from Excel, Word or Google Sheets</div><br><small><i>Note: Pasting a table from Google Docs doesn't work properly.</i></small><br>
<textarea style='width:400px;height:190px;resize:none;' id=textarea data-gramm_editor="false"></textarea><br>
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
	$fileupload="no";
	$mimes = array('application/vnd.ms-excel','application/ms-excel','application/csv','text/plain','text/csv','text/tsv','text/comma-separated-values','application/excel','application/vnd.msexcel','text/anytext','application/octet-stream','application/txt','application/x-csv');
	if (isset($_FILES['file'])){
		if ($_FILES["file"]["error"] > 0) {
			echo "Error: " . $_FILES["file"]["error"] . "<br>";
		} else if (!(in_array($_FILES['file']['type'],$mimes))) {
			echo "<script>alert('Error: file must be a CSV (Type: ".$_FILES["file"]["type"].")\\nNo file uploaded');</script>";
		} else if ($_FILES["file"]["size"] > 200000) {
			echo "<script>alert('Error: file too large (Size: ".$_FILES["file"]["size"].")\\nNo file uploaded');</script>";
		} else {
			$fileupload="yes";
		}
	}
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
if(file_exists('./windowsapp.php')){$actual_link='Windows App v2';}
echo "<script>
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

	if($fileupload=="yes"){
		$_GET['dataset']=$_FILES["file"]["name"];
		echo "<option>".$_FILES["file"]["name"]."</option>";
	}

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
<div class=abutton id=reset>Reset</div> <div class=spacer></div>
<div class=abutton id=update>Save Changes</div> <div class=spacer></div>
<div class=abutton id=helper>Help</div>
</div>

<div id=helppopup class="callout popup">
<ul>
	<li onclick="window.open('//www.mathsnz.com/nzgrapher-info/video-tutorials','_blank')">Video Tutorials</li>
	<li onclick="window.open('//www.mathsnz.com/nzgrapher-info/graph-information','_blank')">Graph Information</li>
	<li onclick="window.open('//www.mathsnz.com/nzgrapher-info/dataset-information','_blank')">Dataset Information</li>
	<li onclick="document.getElementById('welcome').style.display='block'">Show Welcome</li>
	<li onclick="document.getElementById('tour').style.display='block'">Show Overlay</li>
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
	<li id=addrow>
<form method="post" name=fileform id=uploadfile enctype="multipart/form-data">
	<input type="file" name="file" id="filebox" style='width:80px;height:17px;position:absolute;top:4px;left:6px;z-index:2;opacity:0;cursor:pointer;' onChange="document.getElementById('uploadfile').submit();">
	<div style='color:#000;border:0px solid #ccc;width:80px;height:17px;border-radius:0px;padding:5px;font-size:14px;text-align:left;position:absolute;top:4px;left:6px;padding:0px;background:none;'>Open File</div>
</form>
	</li>
	<li id=pastetableclick>Paste Table</li>
	<li id=pastelinkclick>Paste Link</li>
	<li id=highlightdatatable>Select Data Table</li>
<?php
if(substr($dataset,0,6)!="SECURE"){
	echo "<li><a href='#' id=download style='text-decoration:none;color:#000;'>Download Data</a></li>";
}
?>
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
	<li id=samvar>Sampling Variability</li>
	<li id=sort>Sort</li>
	<li id=reorder>Reorder Variable</li>
	<li id=newvar>Create New Variable (From 2 Variables)</li>
	<li id=newvarc2>Create New Variable (Linear Function)</li>
	<li id=newvarc3>Create New Variable (From Condition)</li>
	<li id=filter>Filter Data</li>
	<li id=addindex onclick="addindex()">Add Graphable Index Column</li>
	<li id=addindex onclick="converttimeshow()">Convert Time Column</li>
	<li id=addindex onclick="encodetimeshow()">Encode Time Column</li>
</ul>
</div>

<div id=left>
<div id="samvardiv" style="display:none;z-index:19;position:absolute;top:30px;left:30px;right:30px;bottom:30px;text-align:center;padding-top:30px;">
	<div style='position:absolute;padding-top:2px;padding-bottom:2px;left:0px;top:0px;width:100%; text-align:center;font-weight:bold;border:none;background-color:rgba(0,100,200,0.85);color:#fff;' id=sampletitle>Sampling Variability</div>
	<div style='position:absolute;right:7px;top:1px;background:none;border:none;cursor:pointer;color:#fff;' onclick="$('#data').html($('#presampledataholder').html());$('#data td div').attr('contenteditable','true');updatebox();$('#samvardiv').hide();">&times;</div><br>
	<span id=samvarcontents style="font-size:14px">
		This section lets you re-sample the same data while looking at a graph to see what happens when you take repeated samples. This is really useful for looking at sampling variability.<br>
				<br>
		Sample With: <select style='width:120px' onChange="" id=samvaron></select><br><br>
		<center>
		<table id=samvartable style='text-size:14px;'>
			<tr><td> <td><input id="samvar-">
		</table><br>
		<a href='#' style='width:100%;text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;' id=samvargo>(Re)Sample</a><br><br><br>
		Often it is useful to <b>fix the axis</b> when looking at scatter graphs and or dot plots.<br><br>
		<span href='#' style="text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;cursor:pointer;" onclick='lockaxis()'>Lock Axis Values</span>
		<span href="#" style="text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;cursor:pointer;" onclick="$('#options input').val('auto');">Reset</span>
		<span href='#' style="text-decoration:none;color:#fff;background-color:rgba(0,100,200,0.85);padding:10px;font-size:12px;cursor:pointer;" onclick='moreoptions()'>More Options</span><br>
		</center>
	</span>
</div>
<?php
echo "<table id=data>\n\n";
if(empty($dataset)){
	echo "No such file";
	die();
}

ini_set("auto_detect_line_endings", "1");
if($fileupload=="yes"){
	$f = fopen($_FILES["file"]["tmp_name"], "r") or die("can't open file");
	$file = $_FILES["file"]["tmp_name"];
} else {
	if(file_exists($_GET['folder'])){
		if(substr($dataset,-3)!='csv'){echo "Invalid File";die();}
		if(!in_array($dataset,$files)){echo "Invalid File";die();}
		if(file_exists($_GET['folder']."/$dataset")){
			$f = fopen($_GET['folder']."/$dataset", "r") or die("can't open file");
			$file = $_GET['folder']."/$dataset";
		} else {
			echo "No such file";
			die();
		}
	} else {
		echo "The directory is empty. Please choose a valid directory. The default is datasets.";
		die();
	}
}
$i=0;
$vars=array();
$data=array();

//Update to check the delimiters
//The delimiters array to look through
$delimiters = array(
    'semicolon' => ";",
    'tab'       => "\t",
    'comma'     => ",",
);


if(isset($_POST['csv_data'])){
	$csv_data=urldecode($_POST['csv_data']);
}

if(isset($_GET['url'])){
	$file = $_GET['url'];
}

//Load the csv file into a string

if(isset($csv_data)){$csv=$csv_data;} else {$csv = file_get_contents($file);}
if(isset($_GET['url'])){$csv_data=$csv;}
foreach ($delimiters as $key => $delim) {
    $res[$key] = substr_count($csv, $delim);
}

//reverse sort the values, so the [0] element has the most occured delimiter
arsort($res);

reset($res);
$first_key = key($res);

$del = $delimiters[$first_key];

if(isset($csv_data)){
	$csv_data = preg_replace('/\r\n|\n\r|\n|\r/', '\n', $csv_data);
	$lines = explode("\\n", $csv_data);
	foreach ($lines as $line) {
		$line = str_getcsv($line);
			if($i==0){
				$i="id";
				$cols=count($line);
			}
			if(count($line)<$cols){
				break;
			}
			echo "<tr";
			if($i=="id"){echo " class=tabletop";}
			echo "><th>$i</th>";
			if($i=="id"){$i=0;}
			$r=1;
			foreach ($line as $cell) {
				$cell=str_replace(",", "-", htmlspecialchars(trim($cell)));
				if($i==0){
					array_push($vars,$cell);
					$data["$r"]=array();
				} else {
					if($r<=$cols){
						array_push($data["$r"],$cell);
					}
				}
				if($r<=$cols){
					echo "<td><div>" . $cell . "<br></div></td>";
				}
				$r++;
			}
			echo "</tr>\n";
			$i++;
	}

} else {
	while (($line = fgetcsv($f,0,$del)) !== false) {
			if($i==0){
				$i="id";
				$cols=count($line);
			}
			echo "<tr";
			if($i=="id"){echo " class=tabletop";}
			echo "><th>$i</th>";
			if($i=="id"){$i=0;}
			$r=1;
			foreach ($line as $cell) {
				$cell=str_replace(",", "-", htmlspecialchars(trim($cell)));
				if($i==0){
					array_push($vars,$cell);
					$data["$r"]=array();
				} else {
					if($r<=$cols){
						array_push($data["$r"],$cell);
					}
				}
				if($r<=$cols){
					echo "<td><div>" . $cell . "<br></div></td>";
				}
				$r++;
			}
			echo "</tr>\n";
			$i++;
	}
}
fclose($f);
echo "\n</table></body></html>";
?>
</div>
<div id=variable>
<form action="scatter.php" method="post" target="graph" id=form>
<table style='margin-left:3px;'>
<tr><td>variable 1: <td style='width:125px'>
<select style='width:120px;display:none;' onChange="document.getElementById('xaxis').value=this.options[this.selectedIndex].text;updategraph();" name=xvals id=xvar>
	<option value=" "> </option>
</select><td>Graph Type: <td>
<select style='width:120px' id=type onChange="graphchange(this);">
	<option value='newabout'>About</option>
	<option disabled></option>
	<option value='newpairsplot'>Pairs Plot</option>
	<option disabled></option>
	<option value='newdotplot'>Dot Plot (and Box and Whisker)</option>
	<option value='bar and area graph'>Bar Graph (and Area Graph)</option>
	<option value='histogram'>Histogram</option>
	<option value='histogramf'>Histogram - Summary Data</option>
	<option value='pie chart'>Pie Chart</option>
	<option value='newscatter'>Scatter Graph</option>
	<option value='residuals'>Residuals Plot</option>
	<option disabled></option>
	<option value='bootstrap'>Bootstrap Single Variable</option>
	<option value='newbootstrapcimedian'>Bootstrap Confidence Interval - Median</option>
	<option value='newbootstrapcimean'>Bootstrap Confidence Interval - Mean</option>
	<option disabled></option>
	<option value='re-randomisation - median'>Re-Randomisation - Median</option>
	<option value='re-randomisation - mean'>Re-Randomisation - Mean</option>
	<option disabled></option>
	<option value='paired experiment'>Paired Experiment Dot Plot (and Arrows Graph)</option>
	<option disabled></option>
	<option value='newtimeseries'>Time Series</option>
	<option value='newtimeseriesrecomp'>&nbsp;&nbsp;&nbsp;Re-Composition</option>
	<option value='newtimeseriesseasonaleffects'>&nbsp;&nbsp;&nbsp;Seasonal Effects</option>
	<option value='newtimeseriessforecasts'>&nbsp;&nbsp;&nbsp;Forecasts</option>
	<option disabled></option>
	<option value='change log'>Change Log</option>
	<option value='update'>Update</option>
</select>
<tr><td>variable 2: <td>
<select style='width:120px;display:none;' onChange="document.getElementById('yaxis').value=this.options[this.selectedIndex].text;updategraph();" name=yvals id=yvar>
</select>
<td>Colour by: <td>
<select style='width:120px;display:none' onChange="document.getElementById('colorlabel').value=this.options[this.selectedIndex].text;updategraph();" name=color id=color>
<tr><td>variable 3: <td>
<select style='width:120px;display:none;' onChange="updategraph();" name=zvals id=zvar>
</select>
<td>
</table>
	<input type="hidden" id=regressionform name=regression value=no>
	<input type="hidden" id=boxplotform name=boxplot value=no>
	<input type="hidden" id=highboxplotform name=highboxplot value=no>
	<input type="hidden" id=intervalform name=interval value=no>
	<input type="hidden" id=intervallimform name=intervallim value=no>
	<input type="hidden" id=titleform name=title value=no>
	<input type="hidden" id=yaxisform name=yaxis value=no>
	<input type="hidden" id=xaxisform name=xaxis value=no>
	<input type="hidden" id=colorform name=colorlabel value=no>
	<input type="hidden" id=labelsform name=labels value=no>
	<input type="hidden" id=arrowsform name=arrows value=no>
	<input type="hidden" id=sizeform name=size value=7>
	<input type="hidden" id=transform name=trans value=70>
	<input type="hidden" id=width name=width value=500>
	<input type="hidden" id=height name=height value=500>
	<input type="hidden" id=datain name=datain value=no>
	<input type="hidden" id=titles name=titles value=no>
	<input type="hidden" id=quadraticform name=quadratic value=no>
	<input type="hidden" id=cubicform name=cubic value=no>
	<input type="hidden" id=expform name=exp value=no>
	<input type="hidden" id=logform name=log value=no>
	<input type="hidden" id=powform name=pow value=no>
	<input type="hidden" id=yxform name=yx value=no>
	<input type="hidden" id=regtypeform name=regtype value=Linear>
	<input type="hidden" id=jitterform name=jitter value=no>
	<input type="hidden" id=btypeform name=type value=median>
	<input type="hidden" id=addmultform name=addmult value='Additive'>
	<input type="hidden" id=longtermtrendform name=longtermtrend value='no'>
	<input type="hidden" id=boxnowhiskerform name=boxnowhisker value='no'>
	<input type="hidden" id=boxnooutlierform name=boxnooutlier value='no'>
	<input type="hidden" id=scalefactor name=scalefactor value='1'>
	<input type="hidden" id=invertform name=invert value='no'>
	<input type="hidden" id=thicklinesform name=thicklines value='no'>
	<input type="hidden" id=relativefrequencyform name=relativefrequency value='0'>
	<input type="hidden" id=residualsforcexform name=residualsforcex value='no'>
</form>
</div>

<div id=welcome <?php
	if(isset($_COOKIE['welcome'])){
		echo " style='display:none'";
	}
?>>
	<div id=welcomecontent style='text-align:center;'>
		<br>
		<span style='display:block;width:100%;text-align:center'><img src='logob.png' style='max-height:70px;'></span>
		<table style='width:100%;margin-bottom:5px;'>
			<tr>
				<td style='width:50%;border-right:1px solid #ccc;padding-right:10px;vertical-align:top;padding-bottom:0px;padding-top:0px;'>
					<span style='display:block;width:100%;text-align:center;font-size:150%;'><b>Need Help?</b></span><br>
					If you're not sure where to start watching <a target='_blank' href='//www.mathsnz.com/nzgrapher-info/video-tutorials'>these videos</a> is a good idea.<br>
					<br>
					There is all the infomation on the graphs and datasets and tutorials on how to use NZGrapher over on <a target='_blank' href='https://www.mathsnz.com/nzgrapher-info'>MathsNZ</a>.<br>
					<br>
					Something not working or have an idea to make NZGrapher better... please <a target='_blank' href='//www.mathsnz.com/contact'>let me know</a>.<br>
				<td style='width:50%;padding-left:10px;vertical-align:top;padding-bottom:0px;padding-top:0px;'>
					<span style='display:block;width:100%;text-align:center;font-size:150%;'><b>Cost</b></span><br>
					NZGrapher is free for non-commercial <b>individual</b> use, you can however <a target='_blank' href='https://www.mathsnz.com/donate'>make a donation</a>.<br>
					<br>
					<b>Schools</b> are required to subscribe at a minimum of $0.50 per student using NZGrapher. <b>Commerial users</b> are also required to pay. Please visit the <a target='_blank' href='https://swww.mathsnz.com/nzgrapher-invoice'>invoice creator</a> for details.<br>
					<br>
					This is optional for 2019, but will be compulsory for 2020.<br>
			</tr>
		</table>
		<br>
		<?php
			if(strpos($_SERVER['SERVER_NAME'],'jake4maths.com')>0){
				echo "The version of NZGrapher you are using is hosted on my server.<br>";
			} else {
				echo "The version of NZGrapher you are using is not hosted on my server... if it's not working properly first make sure it is up to date, then check with your IT person... if they can't work out what is wrong let me know.<br>";
			};

		?>
		<br>By pressing the accept button below you are acknowledging that NZGrapher uses cookies, and if you acting on behalf of a school, you are agreeing to the costs associated... if you're not happy with this don't use this website.<br>
		<button class=button style='font-size:15px;margin-top:10px;' onclick="$('#welcome').hide();document.cookie='welcome=yes; expires=<?php
			echo date(DateTime::RSS, strtotime('2 hours'));
		?>'">Agree</button>
		
	</div>
</div>
<div id=graphdiv>
<div id=jsgraph></div>
<canvas id="myCanvas" width="600" height="400" style='display:none'></canvas>
<iframe src="" name=graph id=graph scrolling=no></iframe>
</div>
<div id=controls>
<table style='width:100%;'>
	<tr><td style='width:130px;font-size:12px;'>
		<div id=addtograph>
			<span id=arrowsshow>
				<label><input type="checkbox" onclick="updategraph();" id="arrows" name="arrows" value="yes"> Arrows</label><br>
			</span>
			<span id=regshow><label>
				<input type="checkbox" onclick="updategraph();" id="regression" name="regression" value="yes">
					<span id=sum>Summaries</span>
					<span id=reg>Regression Line</span>
					<span id=for>Forecast output</span>
				</label><br>
			</span>
			<span id=stackdotsshow><label>
				<input type="checkbox" onclick="updategraph();" id="stackdots" name="stackdots" value="yes"> Stack Dots</label><br>
			</span>
			<span id=boxplotshow><label>
				<input type="checkbox" onclick="updategraph();" id="boxplot" name="boxplot" value="yes"> Box Plots</label><br>
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
			<span id=btypeshow>
				Bootstrap:<br>
				<select onchange="updategraph();" id="btype" name="btype" value="Median" style='width:100px;'>
					<option>Median</option>
					<option>Mean</option>
					<option>IQR</option>
					<option>Standard Deviation</option>
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
			<span id=invertshow><label>
				<input type="checkbox" onclick="updategraph();" id="invert" name="invert" value="yes"> Invert Colours</label><br>
			</span>
			<span id=thicklinesshow><label>
				<input type="checkbox" onclick="updategraph();" id="thicklines" name="thicklines" value="yes"> Thick Lines</label><br>
			</span>
			<span id=relativefrequencyshow><label>
				<input type="checkbox" onclick="updategraph();" id="relativefrequency" name="relativefrequency" value="yes"> Relative Freq.</label><br>
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
<div id=sizediv><span id=pointsizename>Point Size:</span> <input id=size type="range" min=3 max=19 step=2 value=7	onchange="updategraph()"></div>
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
<div id=originaldataholder style="display:none;">
</div>
<div id=presampledataholder style="display:none;">
</div>
<div id=loading>
	<br><br><br><br><br><br>Loading...
</div>
<div id=updating>
	<br><br><br><br><br><br>Updating...
</div>
<div id=sampling>
	<br><br><br><br><br><br>Sampling...
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
<map name=graphmap id=graphmap></map>
</body>
</html>
