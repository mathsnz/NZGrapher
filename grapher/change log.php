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
					<?php
						echo "width: ";
						if(isset($_POST['width'])){
							echo $_POST['width']-20;
							echo "px;
							";
						} else {
							echo "100%;
							";
						}
						echo "height: ";
						if(isset($_POST['height'])){
							echo $_POST['height']-20;
							echo "px;
							";
						} else {
							echo "100%;
							";
						}
					?>
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
	<h1><img src='logob.png' style='position:relative;top:22px;height:65px;'> Change Log</h1>
	</center>
<?php
/*
To Do List
- Custom height/width graphs
- Colour points in residuals graph
- Escape titles on graphs.
*************************
- Look into adding a file from a URL
*/
?>
	<b>2017-08-06</b><br>
	- Various Bug Fixes.<br>
	<br>
	<b>2017-07-21</b><br>
	- Fixed a bug in FireFox preventing the pasting of tables.<br>
	<br>
	<b>2017-07-19</b><br>
	- Added relative frequency option to bar graph.<br>
	<br>
	<b>2017-07-16</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2017-07-01</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2017-06-13</b><br>
	- Bug Fixes.<br>
	- Changes to Dotplot and Scatter Graph for visually impared students.<br>
	- New graph type of Histogram Frequency<br>
	<br>
	<b>2017-06-02</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2017-05-26</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2017-05-23</b><br>
	- Big increase to the limit on the number of different fields you can sample on.<br>
	<br>
	<b>2017-05-12</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2017-05-11</b><br>
	- Bug Fixes.<br>
	- Feedback Module.<br>
	- Grid Lines.<br>
	<br>
	<b>2017-05-07</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2017-05-01</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2017-04-20</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2017-03-23</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2017-02-11</b><br>
	- Bug Fixes.<br>
	- Fixes to Time Series Module.<br>
	<br>
	<b>2017-02-24</b><br>
	- Bug Fixes.<br>
	- Abililty to open file by url (adding ?url=http://whatever.com to the end of the link to grapher).<br>
	<br>
	<b>2016-06-18</b><br>
	- Bug Fixes.<br>
	- Even Huger speed increase to all sampling.<br>
	<br>
	<b>2016-06-15</b><br>
	- Bug Fixes.<br>
	- Huge speed increase to stratified sampling.<br>
	- Ability to remove seasonal graph on time series when fitting long term trend.<br>
	<br>
	<b>2016-05-24</b><br>
	- Bug Fixes.<br>
	- Default time series re-composition is the new version.<br>
	<br>
	<b>2016-06-02</b><br>
	- Security Fixes.<br>
	<br>
	<b>2016-05-24</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2016-04-26</b><br>
	- Default Bootstrapping Graphs are Now the New Versions.<br>
	- Bug Fixes.<br>
	<br>
	<b>2016-04-22</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2016-04-20</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2016-04-19</b><br>
	- Bug Fixes.<br>
	- Updated Logo.<br>
	<br>
	<b>2016-04-16</b><br>
	- Bug Fixes.<br>
	- New Logo.<br>
	- New Welcome Screen.<br>
	<br>
	<b>2016-04-09</b><br>
	- Bug Fixes.<br>
	- Fix for csv files created using terrible language settings that mean that comma separated values are separated by semicolons... these files should now work.<br>
	- 3 new test graphs, bootstrap median, bootstrap mean and time series.<br>
	<br>
	<b>2016-03-30</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2016-03-23</b><br>
	- Bug Fixes.<br>
	- Filter Datasets<br>
	- Create Linear Function of a Variable<br>
	<br>
	<b>2016-03-10</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2016-03-06</b><br>
	- Bug Fixes.<br>
	- Fixed positioning of C-I labels on dotplots.<br>
	<br>
	<b>2016-02-29</b><br>
	- Bug Fixes.<br>
	- Fixed positioning of titles on table of data (left hand side).<br>
	<br>
	<b>2016-02-25</b><br>
	- Bug Fixes.<br>
	- Added ability to set min and max for the axis on Dot Plots (and Box and Whisker) with the more options button.<br>
	<br>
	<b>2016-02-17</b><br>
	- Bug Fixes.<br>
	- Added ability to graph hourly data.<br>
	<br>
	<b>2016-02-11</b><br>
	- Bug Fixes.<br>
	- Updated Dotplot Module<br>
	<br>
	<b>2015-12-07</b><br>
	- Addded ability to add box plots without whiskers.<br>
	- Addded ability to add box plots that stop at 1.5x less than LQ and 1.5x above the UQ.<br>
	<br>
	<b>2015-12-04</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2015-11-20</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2015-11-13</b><br>
	- Fix to code in histogram module to fix numbers ending up in the wrong bucket.<br>
	- Fix point size on paired experiment dotplots.<br>
	<br>
	<b>2015-10-18</b><br>
	- Fix to code in time series module.<br>
	<br>
	<b>2015-10-18</b><br>
	- Big update to how the colors work in dotplots and scatter graphs.<br>
	<br>
	<b>2015-10-15</b><br>
	- You now have the choice between additive and multiplicative models in the Time Series section.<br>
	- Hiding the left and bottom areas can now be done by clicking on the tripple dot menu in the top right corner.<br>
	<br>
	<b>2015-09-22</b><br>
	- You can now sort the data by any variables (under 'Sample and More' -> 'Sort').<br>
	- Bug Fixes.<br>
	<br>
	<b>2015-09-17</b><br>
	- Bootstrapping of single variables is now supported for means, medians, IQR and standard deviation.<br>
	<br>
	<b>2015-09-01</b><br>
	- Added ability to create a new calculated column (under "Sample and More".<br>
	- Make the dots on re-randomisation and bootstraps easier to see.<br>
	- Blank / non-numerical points in re-randomisation and bootstraps are no longer plotted.<br>
	- Changed the data menu to be able to more easily find functions.<br>
	<br>
	<b>2015-08-19</b><br>
	- Make the dots on Dot Plots / Scatter Plots / Residuals Plot easier to see.<br>
	<br>
	<b>2015-08-18</b><br>
	- Changed colour of pie charts to be more bold.<br>
	- Can now set width of bars in histogram.<br>
	- Bug Fixes.<br>
	<br>
	<b>2015-07-15</b><br>
	- Bug Fixes.<br>
	<br>
	<b>2015-07-12</b><br>
	- Added ability to make the box and whisker graphs at the top of the graph rather than overlapping the dot plot.<br>
	- Bug Fixes.<br>
	<br>
	<b>2015-07-11</b><br>
	- Added pie charts.<br>
	- Added histograms.<br>
	- Saving changes on graph now keeps the current selection.<br>
	- General updates.<br>
	- Bug Fixes.<br>
	<br>
	<b>2015-07-09</b><br>
	- Update time series module to use STL for decomposition and Holt-Winters for predictions.<br>
	- Bug Fixes.<br>
	<br>
	<b>2015-07-08</b><br>
	- Colour Coding of Paired Experiment Graphs.<br>
	- Drop-down box for setting of standard size graphs.<br>
	<br>
	<b>2015-06-05</b><br>
	- Bug fixes.<br>
	<br>
	<b>2015-06-04</b><br>
	- Added ability to paste a table in using the "Paste Table" button at the top.<br>
	- Increased size of Informal CI limits in dot plots and paired experiments.<br>
	- Bug fixes.<br>
	<br>
	<b>2015-06-03</b><br>
	- Bug fixes.<br>
	<br>
	<b>2015-05-14</b><br>
	- Added slider to adjust transparency of points.<br>
	- Reordered the check boxes for the paired experiment to put the arrows at the top.<br>
	<br>
	<b>2015-05-09</b><br>
	- Rename of re-randomisation graph (removing 'bootstrap' as was originally mislabelled).<br>
	- Added buttons to show and hide the left and bottom panels, particularly for when graphing on devices with smaller screens.<br>
	<br>
	<b>2015-04-30</b><br>
	- Bug fix in scatter plots with missing data.<br>
	- New method for updating.<br>
	<br>
	<b>2015-04-23</b><br>
	- Added option to include a y=x line on scatter plots.<br>
	<br>
	<b>2015-04-13</b><br>
	- Time Series module can now graph yearly data.<br>
	- Bug fixes.<br>
	<br>
	<b>2015-04-02</b><br>
	- Style Update.<br>
	- Bug fixes.<br>
	<br>
	<b>2015-03-27</b><br>
	- Changes to the time series module.<br>
	- Lots and lots of bug fixes.<br>
	<br>
	<b>2015-03-06</b><br>
	- If a data point is not numeric in dotplots, scatter plots and residuals it now doesn't get graph the data points, but list their id in the top right of the graph.<br>
	- Lots of bug fixes.<br>
	<br>
	<b>2015-03-06</b><br>
	- Lots of bug fixes.<br>
	<br>
	<b>2015-03-04</b><br>
	- Added 'jitter' option on Scatter Graphs.<br>
	<br>
	<b>2015-03-03</b><br>
	- Miscellaneous Bug Fixes.<br>
	- Added different model types to the scatter and residual graphs.<br>
	- Major changes to the way time series data is handled for better predictions and faster performance.<br>
	<br>
	<b>2015-02-18</b><br>
	- Fixed an error when uploading files from IE 11 on Windows 8.1.<br>
	- Added daily dataset for work-week data (5 day cycle).<br>
	<br>
	<b>2014-12-05</b><br>
	- Added in a slider to change the point sizes in dot plots, scatter plots, residuals, and all the bootstrap graphs.<br>
	- Made the section where the graph options are set scrollable.<br>
	<br>
	<b>2014-12-05</b><br>
	- Datasets that start with SECURE (all in capitals) are not able to be downloaded using the download button.<br>
	<br>
	<b>2014-12-05</b><br>
	- Fixed an error on stratified sampling were one category was contained in another, eg: male and female.<br>
	<br>
	<b>2014-11-28</b><br>
	- Added in the ability to display the end points of the informal confidence intervals.<br>
	- Fixed Spelling of 'residual' in the time series graph.<br>
	- Added a new 'time series' graph that only displays the raw time series data.<br>
	<br>
	<b>2014-11-27</b><br>
	- Changed regression lines to being in terms of variable names rather than in y and x.<br>
	<br>
	<b>2014-11-25</b><br>
	- Fixed an error on uploading CSV files from Windows 7.<br>
	<br>
	<b>2014-10-29</b><br>
	- Fixed an error on reordering variables.<br>
	- Fixed an error with how NZGrapher handles commas in uploaded files.<br>
	- Area graphs font has been changed to make it easier to see labels.<br>
	<br>
	<b>2014-10-25</b><br>
	- Significantly improved the sampling module to allow it to use stratified sampling as well as simple random sampling.<br>
	- Added ability to reorder categorical variables.<br>
	<br>
	<b>2014-10-24</b><br>
	- Disabled the ability to enter commas or line breaks into the data as this causes issues with the graphing.<br>
	- Added links to <a target='_blank' href="http://students.mathsnz.com/nzgrapher">MathsNZ Students</a> with video tutorials and dataset information.<br>
	<br>
	<b>2014-10-06</b><br>
	- Graphs on the pairs plot are now click-able so when you click on the graph it loads it up full size.<br>
	- Paired experiment graphing now available including arrows graphs.<br>
	- Bug fix with downloading of data.<br>
	- Various other bug fixes.<br>
	<br>
	<b>2014-10-04</b><br>
	- Added bar and area graph.<br>
	- Various bug fixes for when the range was zero.<br>
	<br>
	<b>2014-10-03</b><br>
	- Added pairs plot.<br>
	- Sorted out when the colours label showed up.<br>
	- Fixed an error on dotplots when calculating LQ and UQ when they only had one point in the dataset.<br>
	- Fixed an issue with the colouring of continuous variables.<br>
	<br>
	<b>2014-09-29</b><br>
	- Added ability to colour points in scatter graphs and dot plots.<br>
	- Changed font used for easier readability.<br>
	<br>
	<b>2014-09-28</b><br>
	- Added ability to subset dotplots.<br>
	- Bolded summary statistics to make easier to read when printing.<br>
	<br>
	<b>2014-09-23</b><br>
	- Added ability to download the current dataset after edits.<br>
	<br>
	<b>2014-09-17</b><br>
	- Fixed an on time series seasonal effects not displaying zero correctly.<br>
	<br>
	<b>2014-09-17</b><br>
	- Fixed an error with the bootstrap re-randomisation reversing the order of the bootstrap depending on the naming of groups.<br>
	- Added labels to the bootstrap axis so it is clear which way round the calculation is being done.<br>
	<br>
	<b>2014-09-08</b><br>
	- Fixed an error with Firefox causing the new data rows not to be editable.<br>
	- Fixed an error in the calculation of quartiles to use the same method we use in New Zealand, rather than the method used by Excel.<br>
	<br>
	<b>2014-09-01</b><br>
	- Added more informative (pop-up) messages if file uploads fail. Maximum file size is 100kb and must be in CSV format.<br>
	<br>
	<b>2014-08-29</b><br>
	- Added in informal confidence intervals for dotplots.<br>
	<br>
	<b>2014-08-28</b><br>
	- Scatter plots no longer automatically add in the trend line, this must be added in by clicking the 'Regression Line' button.<br>
	- Dot plots no longer automatically show the summary statistics or box and whiskers, this must be added in by clicking the 'Summaries' or 'Box Plots' buttons.<br>
	- Changed the way the labels for this button appear as well to make it more clear what each one does.<br>
	This has been done in an attempt to get students to write about what they see rather than what is calculated as a first step.<br>
	<br>
	<b>2014-08-25</b><br>
	- Updated fonts to avoid overlapping of texts.<br>
	<br>
	<b>2014-08-18</b><br>
	- Clicking anywhere will now hide the overlay.<br>
	<br>
	<b>2014-08-12</b><br>
	- Fixed algorithm for bootstrap re-randomisation.<br>
	<br>
	<b>2014-08-08</b><br>
	- Changed to a 0.2 second timeout before reloading graph when resizing to reduce serve load.<br>
	- Removed auto-update of graph when axis or graph title was changed and added the update graph button.<br>
	<br>
	<b>2014-07-30</b><br>
	- Fixed issue with screen rotation on iPad causing graphs to not display correctly.<br>
	- Readjusted the positioning of summary statistics for dotplots.<br>
	<br>
	<b>2014-07-29</b><br>
	- Changed the axis so for large numbers they are smaller and should all fit.<br>
	- Changed the images so you can right click to copy and paste into word / elsewhere or press and hold to save on iPad.<br>
	- Adding scrolling if needed to the holt winters forecasts.<br>
	- Updated the position of the summary statistics for the dotplots.<br>
	- Added Change Log<br>
	<br>
	<b>2014-07-28</b><br>
	Changes prior to this date were not logged<br>
    <br>
	</div>

	</div>
	</body>
</html>
