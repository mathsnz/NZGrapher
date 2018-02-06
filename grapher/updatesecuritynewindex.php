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
		<i>There have been some issues with this on Internet Explorer... if it doesn't work try <a href='https://www.google.com/chrome/browser/' target='_blank'>Google Chrome</a>.
		

		";
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
	<TITLE><?php echo ucfirst(getCurrentDirectory()); ?></TITLE>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
	<link rel="stylesheet" type="text/css" href="../style.css">
	<style>
		tr.highlight td {
			font-size:1em;
			padding:10px;
		}
		h3 {
			padding:10px;
			margin:0px;
		}
		tr.highlight:nth-child(odd)    { background-color:#ddd; }
		tr.highlight:nth-child(even)    { background-color:#fff; }
		a {color:#000; text-decoration:none;}
		a:hover {color:#000; text-decoration:underline;}
		a:visited {color:#000;}
		body {overflow:auto;}
	</style>
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



<center><h3><?php echo ucfirst(getCurrentDirectory()); ?><br>
<small><small><small><a href='/grapher'>Home</a></small></small></small></h3></center>
<table width=100%>
<?php
$dir="./"; // Directory where files are stored

if ($dir_list = opendir($dir))
{
while(($filename = readdir($dir_list)) !== false)
{	
	if(substr($filename,0,1)!="." && (substr($filename,-3,3)=="csv")) {
	?>
		<tr class=highlight><td>&nbsp&nbsp&nbsp<a href="<?php echo $filename; ?>"><?php echo substr($filename,0,-4);
		?></a><td><a href="delete.php?fn=<?php echo urlencode($filename); ?>">Delete</a>
		<?php
	} else if(substr($filename,0,1)!="." && strpos ($filename,".")==FALSE) {
	?>
		<tr><td><a href="<?php echo $filename; ?>">+ <?php echo $filename;
		?></a><td>
		<?php
	}
}
closedir($dir_list);
}



?>
</table><br>
<center>
<b>Upload another file</b>
<form action="upload.php" method="post" enctype="multipart/form-data">
<table>
	<tr><td><label for="file">File:</label>
		<td><input type="file" name="file" id="file">
	<tr><td><td><input type="submit" name="submit" value="Submit">
</table>
<small><b>Note: your file must be a CSV</b></small>
</form>
</center>
</div>
</body>
</html>
