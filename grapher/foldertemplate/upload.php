<?php
include ('./password.php');
$correctpass=md5($correctpass);
$loggedin=0;
if(isset($_COOKIE["pass"])){
	$pass = $_COOKIE["pass"];
	if($pass==$correctpass){
		$loggedin=1;
	}
}
if($loggedin==0){
	header( 'Location: ./' ) ;
	die();

}

if(isset($_POST["savesettings"])){
	file_put_contents('datasettings.php','<?php $data="'.base64_encode(serialize($_POST['data'])).'"; ?>');
		echo "Good";
		die();
}

function getCurrentDirectory() {
	$path = dirname($_SERVER['PHP_SELF']);
	$position = strrpos($path,'/') + 1;
	return substr($path,$position);
}
?>

<html>
<head>
	<TITLE>NZGrapher Custom Folder: <?php echo getCurrentDirectory(); ?></TITLE>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
	<link rel="stylesheet" type="text/css" href="../style.css">
	<style>
		body{
			padding-top:40px;
			overflow:auto;
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
<center>

<?php

$_FILES["file"]["name"] = preg_replace('/\s+/', ' ', basename($_FILES["file"]["name"]));
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if(strtolower($extension)!="csv" && strtolower($extension)!="nzgrapher"){
	echo ('Invalid Extension');
} else {
	$mimes = array('application/octet-stream','application/vnd.ms-excel','application/csv','text/plain','text/csv','text/tsv','text/comma-separated-values');
	if (isset($_FILES['file'])){
		if ($_FILES["file"]["error"] > 0) {
			echo "Error: " . $_FILES["file"]["error"] . "<br>";
		} else if (!(in_array($_FILES['file']['type'],$mimes))) {
			echo "Error: file must be a CSV (Type: ".$_FILES["file"]["type"].")";
		} else if ($_FILES["file"]["size"] > 1048576) {
			echo "Error: file too large (Size: ".$_FILES["file"]["size"].")";
		} else {
			    if (file_exists($_FILES["file"]["name"])) {
					echo $_FILES["file"]["name"] . " already exists. ";
				} else {
				  move_uploaded_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
				  echo $_FILES["file"]["name"] . " Saved. ";
				}
				echo "
				 <script>window.location.href = './'</script>";
		}
	}
}
?>
<br><a href='./'>Go Back to Folder</a>
</center>
</body>
</html>