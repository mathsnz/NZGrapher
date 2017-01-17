<html>
		<head>
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
					width:<?php if(array_key_exists('width',$_POST)){echo $_POST['width']-20;} else {echo "500";} ?>px;
					height:<?php if(array_key_exists('height',$_POST)){echo $_POST['height']-20;} else {echo "500";} ?>px;
					overflow-y:scroll;
					padding:10px;
				}
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
	<div id=content>
	<center>
	<h1>About <img src='logob.png' style='position:relative;top:22px;height:65px;'></h1>
	<a href='https://www.facebook.com/mathsnz' target='_blank'>Check us out on Facebook</a><br><br>
	<?php
		if(file_exists('./version.php')){
			include './version.php';
		} else {
			$v=0;
		}

		$latest = 0;
		$latest = floatval(file_get_contents('https://raw.githubusercontent.com/mathsnz/NZGrapher/master/grapherversion.php'));


		if($v<$latest){echo "<span style='color:#f00;'>Your version of NZGrapher is out of date<br>
		Change the graph type to update to get the latest version.</span><br><br>";}
	?>
	</center>
	NZGrapher has been developed by Jake Wills, a maths teacher in New Zealand specifically for supporting the teaching of the NCEA Statistics Standards. The idea behind NZGrapher was to be able to run on <b>any device</b>, without an install. NZGrapher was developed to run on anything with a browser, computers, iPads, ChromeBooks, Microsoft Surface, Android, even Phones. On the iPad the best way to make it work is click on the <img src='share.jpg' height=15> button and add it to your home screen. NZGrapher is provided free of charge... but <a href='http://www.mathsnz.com/donate.html' target='_blank'>donations</a> are gladly accepted.<br>
	<br>
	NZGrapher has proved to be immensely popular, to the point that at times the server struggles with the load. If you would like to arrange to have NZGrapher <b>hosted at your school</b> (just for your own school's use, or shared with others if you want) this can normally be easily arranged, as most schools already have a server capable of running NZGrapher (you need a web server running PHP). If this is of interest to you please see full details on <a href='http://www.mathsnz.com/localgrapher.html' target='_blank'>MathsNZ</a>.<br>
	<br>
	<b>Help</b><br>
	You can access <b><a target='_blank' href="//students.mathsnz.com/nzgrapher">video tutorials</a></b> to help you getting started on <a target='_blank' href="http://students.mathsnz.com/nzgrapher">MathsNZ Students</a>. They are organised in two ways, firstly by the type of graph you are trying to draw, and secondly by the NCEA standard that they relate to. There is a help button in the middle of the bottom which will give you an overlay explaining what each of the sections does. The data section on the left also allows you to edit the data directly just by clicking on the part you want to edit and typing the changes in.<br>
	<br>
	<b>Graphs</b><br>
	For information on what each graph type does, change the graph type (currently set to About) to 'Graphs Information'.<br>
	<br>
	<b>Dataset Information</b>
	Information on all of the datasets, what each of the columns are and where the dataset is from is available from <a target='_blank' href="http://students.mathsnz.com/nzgrapher/nzgrapher_c.html">MathsNZ Students</a>.<br>
	<br>
	<b>Saving / Copying Graphs</b><br>
	To save or copy the graph right click on it or tap and hold if you are using a Tablet and the options should show up for copying and saving.<br>
	<br>
	<b>Updates</b><br>
	A full list if changes is always available by changing the graph type to 'change log'. You can also like me on <a href='https://www.facebook.com/mathsnz' target='_blank'>facebook</a> or sign up to the newsletter by <a href='http://eepurl.com/4JD3v' target='_blank'>clicking here</a>.<br>
	<br>
	<b>For Teachers</b><br>
	NZ Grapher also supports custom folders for assessments or your own datasets, allowing students with iPads to access assessment material, as they do not support uploading of files. If you are a teacher and would like me to set up a custom folder for you, please let me know. You can contact me at <a href='http://www.mathsnz.com/contact.html' target='_blank'>MathsNZ</a>. Once the folder is set up you can manage the files inside it via a password protected page.<br>
	<br>
	<b>More Info</b><br>
	I created NZGrapher as a labour of love... if you find it useful please consider dropping me a line to say thanks, and if you have a bit of spare cash I wouldn't complain if you gave me a small donation. You can donate either via <b>PayPal</b> (using a credit / debit card or your PayPal account) or via a <b>bank transfer</b> (<a href='http://www.mathsnz.com/contact.html' target='_blank'>contact me</a> for the bank account number). I can provide you with a <b>receipt</b> if needed.<br>
	<br>
	Please don't feel any pressure to donate as you can use NZGrapher <b>for free</b>, but donations are appreciated.<br>
    <br>
	<center>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="VZ2MNV3YGV5QL">
		<input type="image" src="btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	</form>
	</center>
	</div>

	</div>
	</body>
</html>
