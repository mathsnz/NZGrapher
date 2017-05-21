<html>
<head>
<title>Update NZGrapher</title>
			<link href='//fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
			<style>
				body {
					font-family: 'Roboto', sans-serif;
				}
				table {
					border-collapse:collapse;
				}
				td, th {
					border:1px solid #000;
					padding-left:4px;
					padding-right:4px;
					width:80px;
				}
				*.minmax {
					color:#bbb;
				}
				#content {
					position:absolute;
					top:0px;
					left:0px;
					width:<?php echo $_POST['width']-20; ?>px;
					height:<?php echo $_POST['height']-20; ?>px;
					overflow-y:scroll;
					padding:10px;
				}
</style>
</head>
<body>
	<div id=content>
	<center>
	<h1><img src='logob.png' style='position:relative;top:22px;height:65px;'> Update</h1>
<br>
<?php
set_time_limit(0);

$latest = floatval(file_get_contents('http://raw.githubusercontent.com/mathsnz/NZGrapher/master/grapherversion.php'));

if(isset($_POST['password']) || isset($_POST['yup']) || isset($_GET['password'])){
	include 'password.php';
	if($password==$_POST['password'] || file_exists('./windowsapp.php') || ($password==$_GET['password'] && $latest>$v)){
		file_put_contents("Tmpfile.zip", fopen("http://raw.githubusercontent.com/mathsnz/NZGrapher/master/grapher.zip", 'r'));

		$zip = new ZipArchive;
		if ($zip->open('Tmpfile.zip') === TRUE) {
			$zip->extractTo('../');
			$zip->close();
			echo 'NZGrapher successfully updated.<br><br>';
		} else {
			echo 'Something went wrong updating NZGrapher... please try again later.<br><br>';
		}
		unlink("Tmpfile.zip");
	} else {
		echo "Sorry, wrong password, try again.<br><br>";
	}
}

if(file_exists('./version.php')){
	include './version.php';
	echo "Current Version: $v<br>";
} else {
	echo "Not Currently Installed<br>";
	$v=0;
}
if(!extension_loaded('zip')){
	$write="no";
	$error = "to enable the zip extension";
}

echo "Latest Version: $latest<br><br>";

$filename = './test.txt';
if (file_put_contents($filename,"1")) {
    $write="yes";
	unlink($filename);
} else {
    $write="no";
}
if(basename(getcwd())!="grapher"){
	$write="no";
}


if($write=="no") {
	echo "Your server settings do not allow for updating automatically. Check with your server administrator and get them to change the permissions and or name of this folder.<br>
	See the README for more details.";
}
if($latest>$v){
} else {
	echo "You have the latest version.<br><br>Force ";
}

	if($write=="yes"){
		echo "Update:<br>
		<form method=post>
			<input type=hidden name=password value=yup>";
		if(!file_exists('./windowsapp.php')){
			echo "
			Password: <input type=password name=password> ";
		}
		echo	"
			<input type=submit value='Update'>
		</form>";
		if(!file_exists('./windowsapp.php')){
			echo "
			If you don't know the password and you manage this server, please <a href='//www.mathsnz.com/contact.html' target='_blank'>contact me</a>.
			";
		}
	}

?>
</body>
</html>
