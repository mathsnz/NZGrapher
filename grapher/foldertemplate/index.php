<?php
include ('./password.php');
$correctpass=md5($correctpass);

$loggedin=0;
$loginerror = '';
if(isset($_COOKIE["pass"])){
	$pass = $_COOKIE["pass"];
	if($pass==$correctpass){
		$loggedin=1;
	}
	setcookie("pass", $pass, time()+3600);
}
if(isset($_POST["password"])){
	$pass = md5($_POST["password"]);
	if($pass==$correctpass){
		setcookie("pass", $pass, time()+3600);
		header( 'Location: ./' ) ;
		die();
	} else {
		$loginerror = "Incorrect Password<br><br>";
	}
}
?>
<html>
<head>
	<TITLE>NZGrapher Custom Folder: <?php echo getCurrentDirectory(); ?></TITLE>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
	<script src="../jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../style.css">
	<link rel="apple-touch-icon" sizes="57x57" href="../icon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="../icon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="../icon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="../icon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="../icon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="../icon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="../icon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="../icon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="../icon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="../icon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../icon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="../icon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../icon/favicon-16x16.png">
	<link rel="manifest" href="../icon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="../icon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=upload" />
	<style>
		tr.highlight td {
			font-size:1em;
			padding:10px;
		}
		h3 {
			padding:10px;
			margin:0px;
		}
		tr.highlight:nth-child(odd)    { background-color:#fafafa; }
		tr.highlight:nth-child(even)    { background-color:#fff; }
		tr.highlight:hover    { background-color:#eee;}
		a {color:#000; text-decoration:none;}
		a:hover {color:#000; text-decoration:underline;}
		a:visited {color:#000;}
		.material-symbols-outlined {
			font-variation-settings:
			'FILL' 0,
			'wght' 400,
			'GRAD' 0,
			'opsz' 24
		}
		#uploadbox {
			border:1px dashed #ccc;
			border-radius:10px;
			padding:20px;
			text-align:center;
		}
		table {
			width:calc(100%);
		}
		body {
			margin-top:60px;
			margin-left:20px;
			margin-right:20px;
			overflow:auto;
		}
		th {
			text-align:left;
			padding:10px;
			background-color:#2B7DD0;
			font-weight:500;
			color:#fff;
		}
	</style>
</head>

<body>
<div id="top" style="filter: none;height:40px;position:fixed;">
	<div style="font-size:30px;background:none;border:none;position:absolute;top:3px;left:5px;z-index:4"><a href='../'><img src="../logow.png" height="37"></a></div>
	<div style="position:absolute;top:6px;right:20px;background:none;border:none;font-size:21px;">
		Custom Folder: <?php echo getCurrentDirectory(); ?>
	</div>
</div>

<?php

if($loggedin==0){
		echo "
		<center>
		$loginerror
		You are not logged in.<br>
		<form action='' method='POST'>
		Password: <input type=password name=password>
		<input type=submit value='Log In' class=button>
		</form>
		";
		die();

}


function getCurrentDirectory() {
	$path = dirname($_SERVER['PHP_SELF']);
	$position = strrpos($path,'/') + 1;
	return substr($path,$position);
}

$folder = getCurrentDirectory();

$datasettingsfiles=glob('./datasettings.php');
$data = array('secure'=>array(),'hidden'=>array(),'disabled'=>array(),'expiry'=>array(),'expirytime'=>array());
foreach($datasettingsfiles as $datasettingfile){
	include($datasettingfile);
	$data = unserialize(base64_decode($data));
}
if(!array_key_exists('secure',$data)){$data['secure']=array();}
if(!array_key_exists('hidden',$data)){$data['hidden']=array();}
if(!array_key_exists('disabled',$data)){$data['disabled']=array();}
if(!array_key_exists('expiry',$data)){$data['expiry']=array();}
if(!array_key_exists('expirytime',$data)){$data['expirytime']=array();}
?>

<h3>Current Files</h3>
<table>
	<tr>
		<th>File
		<th>Secure
		<th>Hidden
		<th>Disabled
		<th style='width:200px;'>Expiry
		<th>Delete
<?php
$dir="./";

$files=glob('./*.csv');
$files2=glob('./*.CSV');
$files3=glob('./*.nzgrapher');$files=array_merge($files,$files2,$files3);
sort($files);

foreach($files as $filename){
	$filename = substr($filename,2);
	$jsonfilename = json_encode($filename);
	echo "<tr class=highlight>
		<td><a href=\"../?folder=$folder&dataset=".urlencode($filename)."\">$filename</a>
		<td><input type=checkbox class='checkbox secure' filename=".$jsonfilename.((in_array($filename,$data['secure']))?' checked':'').">
		<td><input type=checkbox class='checkbox hidden' filename=".$jsonfilename.((in_array($filename,$data['hidden']))?' checked':'').">
		<td><input type=checkbox class='checkbox disabled' filename=".$jsonfilename.((in_array($filename,$data['disabled']))?' checked':'').">
		<td><input type=checkbox class='checkbox expiry' filename=".$jsonfilename.((in_array($filename,$data['expiry']))?' checked':'')."> 
			<input type=datetime-local class='input expirytime' filename=".$jsonfilename." style='display:none' value=".((array_key_exists($filename,$data['expirytime']))?json_encode($data['expirytime'][$filename]):'').">
		<td><a href=\"delete.php?fn=".urlencode($filename)."\">Delete</a>";
}

?>

</table><br>
<h3>Add New Files</h3>
<form id=uploadform action="upload.php" method="post" enctype="multipart/form-data" style='width:200px;'>
	<label for="file" id="droparea">
		<input type="file" name="file" id="file" accept="" hidden>
		<div id="uploadbox">
		<span class="material-symbols-outlined" style='font-size:80px;'>upload</span><br>
		Drag or drop, or click here to upload a file.
		</div>
	</label>
</form>
<h3>Notes</h3>
<ul>
	<li>Your file must be a .CSV or .NZGrapher file</li>
	<li><b>Secure</b> files can be loaded inside NZGrapher, and can be seen in the dropdown for the custom folder, but the "Download Data", "Save Session", and "Select and Copy Data Table" options are not available.</li>
	<li>If a file starts with SECURE then it will be classed as "secure" by default, and this can't be turned off unless you call the file something different.</li>
	<li><b>Hidden</b> files can be loaded inside NZGrapher, but can't be seen in the dropdown for the custom folder.</li>
	<li><b>Disabled</b> files can't be loaded inside NZGrapher and can't be seen in the dropdown for the custom folder.</li>
	<li>Secure, Hidden and Disabled files can still be accessed directly via the folder storage, but people would need to know the link to the file directly... so while they ideally won't be seen by people you don't want to see them, it is still possible.</li>
	<li>If data is loaded before the expiry time, students will be able to continue using that data until they try to re-access the data (so while it is open)</li>
	<li>You can only upload one file at a time.</li>
	<li>The max file size you can upload is 1MB.</li>
</ul>
</div>
</body>
<script>
	dropArea = document.getElementById('droparea');
	uploadFile = document.getElementById('file');
	dropArea.addEventListener('dragover',function(e){
		e.preventDefault();
	})
	dropArea.addEventListener('drop',function(e){
		e.preventDefault();
		uploadFile.files = e.dataTransfer.files;
		document.getElementById('uploadbox').innerHTML='Uploading... <br> please wait...';
		document.getElementById('uploadform').submit();
	})
	uploadFile.addEventListener('change',function(e){
		document.getElementById('uploadbox').innerHTML='Uploading... <br> please wait...';
		document.getElementById('uploadform').submit();
	})
	$('.expiry').on('change',function(e){
		if($(this).is(':checked')){
			$(this).parent().find('.expirytime').show();
		} else {
			$(this).parent().find('.expirytime').hide();
		}
	})
	$('.checkbox, .expirytime').on('change',function(e){
		data = {};
		data.secure = [];
		data.hidden = [];
		data.disabled = [];
		data.expiry = [];
		data.expirytime = {};
		data.expirytimeiso = {};
		$('.secure').each(function(){
			if($(this).attr('filename').substr(0,6)=="SECURE"){
				$(this).prop('checked',true);
			}
		})
		$('.secure:checked').each(function(){
			data.secure.push($(this).attr('filename'))
		})
		$('.hidden:checked').each(function(){
			data.hidden.push($(this).attr('filename'))
		})
		$('.disabled:checked').each(function(){
			data.disabled.push($(this).attr('filename'))
		})
		$('.expiry:checked').each(function(){
			data.expiry.push($(this).attr('filename'))
			data.expirytime[$(this).attr('filename')]=$(this).parent().find('.expirytime').val();
			data.expirytimeiso[$(this).attr('filename')]=(new Date($(this).parent().find('.expirytime').val())).toISOString();
		})
		console.log(data);
		$.post('./upload.php',{savesettings:1,data:data}).done(function(data){
			if(data!="Good"){
				alert(data);
			}
			console.log(data);
		})
	})
	$('.checkbox').change();
</script>
</html>
