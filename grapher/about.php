<center>
	<h1>About <img src='logob.png' style='position:relative;top:22px;height:65px;'></h1>
</center>
<br>
<br>	
NZGrapher has been developed by Jake Wills, a maths teacher in New Zealand specifically for supporting the teaching of the statistics in New Zealand. 
The idea behind NZGrapher was to create a web based statistical package that can run on <b>any device</b>, without an install.<br>
<span id=whichschoolholder></span>
<br>
<b>Help</b><br>
You can access <b><a target='_blank' href="https://info.grapher.nz/video-tutorials/">video tutorials</a></b> to help you getting started on <a target='_blank' href="https://info.grapher.nz/video-tutorials/">the support site</a>. 
They are organised in two ways, firstly by the type of feature you are trying to use, and secondly by the NCEA standard that they relate to. 
There is a help button in the top menu with more help content. 
The data section on the left also allows you to edit the data directly just by clicking on the part you want to edit and typing the changes in.<br>
<br>
<b>Saving / Copying Graphs</b><br>
To save or copy the graph right click on it or tap and hold if you are using a Tablet and the options should show up for copying and saving.<br>
<br>
<b>Getting Data Into NZGrapher</b><br>
NZGrapher has a number of built in datasets. If you want to use your own:
<ul>
	<li>You can upload / open files from your computer up to 200kb using Data -> Open Files.</li>
	<li>You can import from clipboard (data you have already copied) by going to Data -> Import from Clipboard. This only works from google sheets and excel. It doesn't work from a table in a Google doc.</li>
	<li>You can also paste a link to a csv. The CSV needs to be accessible on the internet for this to work. You can publish Google Sheets to a CSV from Google Docs.</li>
</ul>
If you have a dataset you want to share with lots of students see the section below 'for teachers'.<br>
<br>
<b>For Teachers</b><br>
NZ Grapher also supports custom folders for assessments or your own datasets, allowing students to easily access the datasets. 
If you are a teacher and would like me to set up a custom folder for you, please let me know. 
You can find my contact details <a href='https://info.grapher.nz/contact/' target='_blank'>here</a>. 
Once the folder is set up you can manage the files inside it via a password protected page.<br>
<br>
<b>Costs</b><br>
NZGrapher is free for non-commercial individual use, you can however <a href='https://info.grapher.nz/donate/'>make a donation</a>.<br>
<br>Schools are required to subscribe. I'm not asking much (50c or $1 per annum, per student, using NZGrapher), this just helps cover my costs for running NZGrapher.
<br>Commerial users are also required to pay. Please visit the <a href='https://info.grapher.nz/nzgrapher-invoice/'>invoice creator</a> for details.<br>
</div>
<script>
if(getCookie('whichschool')!='yes'){
	$.get('https://tracking.jake4maths.com/whichschool.php').done(function(data){
		$('#whichschoolholder').html(data);
	})
}

</script>