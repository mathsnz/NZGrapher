<?php 

if(!isset($_GET['pass'])){die("you don't have permission to access this page");}
if($_GET['pass']!="ZmBSToyD0A"){die("You don't have permission to access this page");}

function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 

if(isset($_GET['foldername'])){
	$foldername=$_GET['foldername'];
	$src="./foldertemplate/";
	$dst="./$foldername/";
	recurse_copy($src,$dst);
	$password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

	file_put_contents("./$foldername/password.php","<?php \$correctpass='$password'; ?>");

	echo "Hi there,<br>
	<br>
	I have set up a new folder on NZGrapher.<br>
	You can access NZGrapher by going to <a href='http://www.jake4maths.com/grapher'>http://www.jake4maths.com/grapher</a> and changing the folder in the top right to: $foldername<br>
	or you can just go to <a href='http://www.jake4maths.com/grapher/?folder=$foldername'>http://www.jake4maths.com/grapher/?folder=$foldername</a><br>
	<br>
	To edit the contents of the folder you can go to: http://www.jake4maths.com/grapher/$foldername<br>
	Your password is: $password<br>
	This will enable you to upload whatever you need into the folder. There is the babies dataset in there by default, but this can be deleted once you have another file in there, it just doesn't like you not having any files in there. Remember the files need to be in CSV format. There is also an example of a SECURE dataset, when you start a dataset with SECURE it means the download button at the bottom will disappear.<br>
	<br>
	Please note that the folder name and password are case sensitive.<br>
	<br>
	There is a video tutorial available on how to use the school folder available here: <a href='http://students.mathsnz.com/nzgrapher/nzgrapher_d.html'>http://students.mathsnz.com/nzgrapher/nzgrapher_d.html</a><br>
	<br>
	And there are also video tutorials for all the NZGrapher features available here: <a href='http://students.mathsnz.com/nzgrapher'>http://students.mathsnz.com/nzgrapher</a><br>
	<br>
	If you have any questions, or suggestions for improvement please let me know.<br>
	<br>
	And if you're not already signed up... you can sign up for my newsletter here: <a href='http://eepurl.com/4JD3v'>http://eepurl.com/4JD3v</a><br>
	I'll only send you emails every once and a while when there are new features or I have something else exciting to tell you.<br>
	<br>
	And just a reminder, if you are not already aware, you can get NZGrapher running at your school, which makes it run heaps faster as you're not competing with every other school on my server... full details at <a href='http://www.mathsnz.com/localgrapher.html'>http://www.mathsnz.com/localgrapher.html<br>
	With this you can set up as many folders as you would like!";
} else {
	?>
	<form>
	Folder Name: <input type=text name=foldername>
	<input type=hidden value='ZmBSToyD0A' name=pass>
	<input type=submit>
	</form>
	<?php
}
?>