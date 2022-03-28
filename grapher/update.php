	<center>
	<h1><img src='img/logob.png' style='position:relative;top:22px;height:65px;'> Update</h1>
<br>
<?php
set_time_limit(0);

$latest = floatval(file_get_contents('http://raw.githubusercontent.com/mathsnz/NZGrapher/master/grapherversion.php'));

echo "Latest Version: $latest<br>";

if(isset($_POST['password']) || isset($_POST['yup']) || isset($_GET['password'])){
	include 'password.php';
	$pw="";
	if(isset($_POST['password'])){$pw=$_POST['password'];}
	if(isset($_GET['password'])){$pw=$_GET['password'];}
	if($password==$pw || file_exists('./windowsapp.php')){
		file_put_contents("Tmpfile.zip", fopen("http://raw.githubusercontent.com/mathsnz/NZGrapher/master/grapher.zip", 'r'));

		$zip = new ZipArchive;
		if ($zip->open('Tmpfile.zip') === TRUE) {
			$zip->extractTo('../');
			$zip->close();
			echo 'NZGrapher successfully updated.<br><br>';
			$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
			file_put_contents('./foldertemplate/password.php','<?php $correctpass="'.$randomString.'"; ?>');
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
	echo "Current Version: $v<br><br>";
} else {
	echo "Not Currently Installed<br>";
	$v=0;
}

if(!extension_loaded('zip')){
	$write="no";
	$error = "to enable the zip extension";
}

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
		<form method=post action='./update.php'>
			<input type=hidden name=password value=yup>";
		if(!file_exists('./windowsapp.php')){
			echo "
			Password: <input type=password name=password> ";
		}
		echo	"
			<input type=submit value='Update' class=button>
		</form>";
		if(!file_exists('./windowsapp.php')){
			echo "
			If you don't know the password and you manage this server, please <a href='//www.mathsnz.com/contact.html' target='_blank'>contact me</a>.
			";
		}
	}

?>
