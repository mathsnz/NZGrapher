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
if(isset($_POST["password"])){
	$pass = md5($_POST["password"]);
	if($pass==$correctpass){
		setcookie("pass", $pass, time()+3600);
		header( 'Location: ./' ) ;
		die();
	} else {
		echo "<center>Incorrect Password<br>";
	}
	
}
if($loggedin==0){
		echo "
		<center>
		You are not logged in.<br>
		<form action='' method='POST'>
		Password: <input type=password name=password>
		<input type=submit>
		</form>
		

		";
		die();

}
$_FILES["file"]["name"] = basename($_FILES["file"]["name"]);
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if(strtolower($extension)!="csv"){die('Invalid Extension');}
echo "<center>";

$mimes = array('application/vnd.ms-excel','application/csv','text/plain','text/csv','text/tsv','text/comma-separated-values');
	if (isset($_FILES['file'])){
		if ($_FILES["file"]["error"] > 0) {
			echo "Error: " . $_FILES["file"]["error"] . "<br>";
		} else if (!(in_array($_FILES['file']['type'],$mimes))) {
			echo "Error: file must be a CSV (Type: ".$_FILES["file"]["type"].")";
		} else if ($_FILES["file"]["size"] > 100000) {
			echo "Error: file too large (Size: ".$_FILES["file"]["size"].")";
		} else {
			    if (file_exists($_FILES["file"]["name"])) {
					echo $_FILES["file"]["name"] . " already exists. ";
				} else {
				  move_uploaded_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
				  echo $_FILES["file"]["name"] . " Saved. ";
				}
				echo "
				 <br><a href='./'>Go Back to Folder</a>";
		}
	}
?>