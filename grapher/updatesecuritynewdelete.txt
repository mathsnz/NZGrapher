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

if ($_GET['check']=="yes") {
	$fn = basename($_GET['fn']);
	unlink ($fn);
	header('Location: ./');
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
error_reporting(0);
$fn = basename($_GET['fn']);
echo "<center>Are you sure? <a href=\"delete.php?check=yes&fn=".urlencode($fn)."\">Yes</a> <a href=\"./\">No</a>";
?>
