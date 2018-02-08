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

error_reporting(0);
if ($_GET['check']=="yes") {
	unlink ($_GET['fn']);
	header('Location: index.php');
} else {
	echo "<center>Are you sure? <a href=\"delete.php?check=yes&fn=".urlencode($_GET['fn'])."\">Yes</a>";
}
?>
