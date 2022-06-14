<center>
	<h1><img src='logob.png' style='position:relative;top:22px;height:65px;'> Change Log</h1>
</center>
<?php
/*
To Do List
- Custom height/width graphs
On Dot Plots:
- IQR only
- median only - bolder
Projector Mode?
Leave a trail of medians on dotplot when doing resampling
*/

?>
	<b>2022-06-15</b><ul>
		<li>Fix for small numbers of residual graphs not working correctly.</li>
	</ul>
	<b>2022-06-13</b><ul>
		<li>Added "C-I Highlight" option on "Dot Plot (and Box and Whisker)"</li>
		<li>Added "Colour by Difference" option on "Paired Experiment Dot Plot (and Arrows Graph)"</li>
	</ul>
	<b>2022-05-04</b><ul>
		<li>Fixed size of start and end points on time series graphs when high-res option was ticked.</li>
	</ul>
	<b>2022-03-22</b><ul>
		<li>Added option to use the viridis colour scale rather than a rainbow colour scale.</li>
		<li>Fixed the clicking on two categorical variables on the pairs plot.</li>
	</ul>
	<b>2022-03-02</b><ul>
		<li>New option on dot plots to show OVS (Overall Visual Spread) & DBM (Difference Between Medians).</li>
	</ul>
	<b>2022-02-21</b><ul>
		<li>Update to display of timeseries components.</li>
		<li>Changes to how urls are fetched.</li>
	</ul>
	<b>2022-02-10</b><ul>
		<li>Update the method of saving graphs to avoid duplicate codes.</li>
	</ul>
	<b>2022-01-22</b><ul>
		<li>New function: Display Explorer... great for exploring data.</li>
		<li>Under More Options is the ability to adjust the smoothness of the violin and shape outline graphs.</li>
		<li>Updated Sea Ice dataset (thanks Marion).</li>
		<li>Highlight ½ ¾ Rule on dot plots.</li>
		<li>Can draw a second custom line on scatter graphs.</li>
	</ul>
	<b>2021-12-15</b><ul>
		<li>New Graph Type: Grid Density Plot</li>
		<li>New options on Dot Plot with "Shape Outline", "Violin" and "Bee Swarm" options, as well as a "Hide Points" tickbox.</li>
		<li>Added ability to create stacked scatter graphs, where you fit the same linear trend line for each colour.... play around, it's quite cool.</li>
	</ul>
	<b>2021-08-15</b><ul>
		<li>'Select Data Table' is now 'Select and Copy Data Table'</li>
		<li>In timeseries forecast output there is a button to 'Select and Copy Forecast Table'</li>
		<li>Update to show a note to high usage schools that have not paid their subscriptions</li>
	</ul>
	<b>2021-07-20</b><ul>
		<li>Changed the colour of the cubic lines to be a darker green</li>
		<li>Changed the font size of summary statistics on dot plots so they are more readable size. Option to adjust the size in the more options area.</li>
		<li>Added Survey to Front Page.</li>
	</ul>
	<b>2021-06-04</b><ul>
		<li>Switch to hosting javascript libraries locally as lots of school networks were blocking schools from accessing them</li>
	</ul>
	<b>2021-06-02</b><ul>
		<li>Added sqrt() as a function in the custom functions</li>
		<li>Bug fix for dot dragging causing unintended consequences</li>
	</ul>
	<b>2021-05-08</b><ul>
		<li>Improvement to fitting of non-linear models when one variable is much larger than the other.</li>
		<li>Added option to show mean dot onto scatter graphs which provides a pivot point for the trend line (useful for teaching trend lines).</li>
		<li>Create a new variable with custom functions</li>
		<li>Create a new variable by averaging up to 5 columns</li>
		<li>Rename the "x" and "y" in the equations that show on scatter graphs</li>
		<li>Add a custom line on scatter graphs through two points that are draggable</li>
	</ul>
	<b>2021-03-22</b><ul>
		<li>Fix for special characters not working when uploading files.</li>
	</ul>
	<b>2021-03-10</b><ul>
		<li>Updated wording on re-randomisation teaching tool to make it more clear the difference between a re-randomisation distribution and a randomisation test.</li>
		<li>Bug fix for extra thick line at top of time series graphs when showing gridlines.</li>
		<li>Added privacy statement.</li>
		<li>Improvements to visibility of bootstrap confidence intervals when dataset has an extreme range but most data is within a small range.</li>
		<li>When launching the teaching tools the data box scrolls to the top so you can see the teaching tool if you are elsewhere in the dataset.</li>
	</ul>
	<b>2020-09-18</b><ul>
		<li>Fix for manually setting the max of the timeseries graphs.</li>
		<li>Fixes for Offline Access.</li>
		<li>Standardise branding after Te Wiki o te Reo Māori.</li>
	</ul>
	<b>2020-09-13</b><ul>
		<li>Update for Te Wiki o te Reo Māori.</li>
	</ul>
	<b>2020-09-05</b><ul>
		<li>Ability to show gridlines on scatter graphs.</li>
		<li>Save the current session, including the variables, graph type and settings (Data -> Save Session)... this downloads a .nzgrapher file which can then be loaded back into NZGrapher later (using Data -> Open File or put into a school folder).</li>
		<li>NZGrapher now works offline... as long as you've visited the site before, it will work. There are a couple of limitiations while you are offline:
			<ul>
				<li>You can't load new datasets from online sources (opening files and importing from clipboard still works fine)</li>
				<li>You can't copy and paste the graph into older versions of Word (sheenshots still work fine)</li>
			</ul>
		</li>
	</ul>
	<b>2020-07-30</b><ul>
		<li>Added option to manually set minimum and maximum on time series graphs under "More Options".</li>
		<li>Fix for 0 sometimes not actually being zero, but a really tiny number.</li>
	</ul>
	<b>2020-06-29</b><ul>
		<li>Fix for shuffle function not working properly on Safari.</li>
		<li>Fix for time series with funny numbers.</li>
		<li>Fix for typo of 'Redidual' (should be 'Residual').</li>
	</ul>
	<b>2020-06-13</b><ul>
		<li>Fix for uploading custom files to school folders where there were duplicate spaces in the name.</li>
		<li>Fix for some servers not recognising certificates properly.</li>
		<li>Fix for time series when the values before the letter changed from 1 digit to 2 digits.</li>
	</ul>
	<b>2020-05-24</b><ul>
		<li>Fix error message not showing in firefox when unable to access the clipboard.</li>
		<li>Fix for the word "Error" showing on time series forecast output when there wasn't actually an error.</li>
	</ul>
	<b>2020-05-14</b><ul>
		<li>Improve compatibility with different extensions.</li>
		<li>Fix dotplot stack option unchecking on update graph.</li>
		<li>Fix pairs plot not working when clicking on a histogram.</li>
	</ul>
	<b>2020-05-04</b><ul>
		<li>Update to fix issue when importing single column of data.</li>
	</ul>
	<b>2020-04-28</b><ul>
		<li>Updated datasets: Visitors and Sea Ice.</li>
		<li>Change to auto-rotate password on foldertemplate folder.</li>
	</ul>
	<b>2020-04-25</b><ul>
		<li>Update to fix links on about page.</li>
	</ul>
	<b>2020-04-22</b><ul>
		<li>Update to min and max values for some datasets on paired experiment graph.</li>
	</ul>
	<b>2020-04-08</b><ul>
		<li>Update to fix custom time series model fitting.</li>
	</ul>
	<b>2020-03-15</b><ul>
		<li>Update to fix file upload on Chromebook.</li>
		<li>Added in Advanced Tools.</li>
	</ul>
	<b>2020-02-04</b><ul>
		<li>Re added paste table for browsers that don't support import from clipboard.</li>
		<li>New menu option - "Save Current State for Reset" which saves the current state of the data for when you press the reset button. Useful for if you are adding extra columns and then wanting to sample and reset.</li>
	</ul>
	<b>2020-01-20</b><ul>
		<li>New Bootstrapping teaching tool.</li>
		<li>New <a href='https://youtu.be/RSeubxWEFAc' target='_blank'>video guide showing how to use the Bootstrapping teaching tool</a>.</li>
		<li>Updates to the shading of points in bootstrap modules.</li>
	</ul>
	<b>2020-01-08</b><ul>
		<li>Changes to splash screen for the start of 2020.</li>
		<li>Re-Group option now under "Sampling and More" - great for grouping lots of variables down into 2 groups.</li>
		<li>Can now load larger datasets.</li>
		<li>Improvements to Re-Randomisation teaching tool.</li>
		<li>Rebuild of both Histogram and Histogram - Summary Data... heaps of new features and speed improvements.</li>
		<li>Rebuild of Paired Experiment Dot Plot (and Arrows Graph).</li>
		<li>Rebuild of Bootstrap Single Variable Module.</li>
		<li>Change to how 0 is handled when doing multiplicative time series modelling.</li>
	</ul>
	<b>2019-12-01</b><ul>
		<li>Updates to 'Bar Graph - Summary Data' graph type... 
			<ul>
				<li>now groups variables that are the same into the same bar</li>
				<li>stacks the graph when color by.</li>
				<li>option for relative frequency display.</li>
				<li>option for 100% bar graph display.</li>
				<li>when summaries is ticked numbers or percentages show in each group rather than at the top of all groups.</li>
				<li>create 'Area Graphs' by using the 'Relative Width' option.</li>
			</ul>
		</li>
		<li>Rebuild of 'Bar Graph' graph type - now has all the same features as the 'Bar Graph - Summary Data' graph type.</li>
	</ul>
	<b>2019-11-06</b><ul>
		<li>Added 'Bar Graph - Summary Data' graph type - more features coming to this soon.</li>
	</ul>
	<b>2019-10-08</b><ul>
		<li>New Re-Randomisation teaching tool.</li>
		<li>New <a href='https://youtu.be/ttnhFlTUSag' target='_blank'>video guide showing how to use the Re-Randomisation teaching tool</a>.</li>
		<li>Remove Google advertising from NZGrapher.</li>
		<li>Update welcome screen to make it more intuitive to dismiss.</li>
	</ul>
	<b>2019-09-11</b><ul>
		<li>New <a href='https://youtu.be/Mj8jwAmqCGY' target='_blank'>video guide showing how to use the Sampling Variability teaching tool</a>.</li>
		<li>Bug fix for extreme differences on re-randomisation modules.</li>
	</ul>
	<b>2019-09-07</b><ul>
		<li>Rebuild of the re-randomisation modules.</li>
		<li>Change to welcome screen to show new features.</li>
	</ul>
	<b>2019-08-23</b><ul>
		<li>Rebuild of Pie Charts, changing colours used, giving option for Donut graphs, and adding hovers.</li>
		<li>Rebuild of Change Log.</li>
		<li>Added ability colour points on residuals graphs.</li>
		<li>Changed location of 'Reset' to inside the 'Data' menu.</li>
		<li>Added new 'Teaching Tools' menu.</li>
		<li>Big revamp to sampling variability teaching tool.</li>
		<li>Changes to variable labels on dotplot.</li>
	</ul>
	<b>2019-07-22</b><ul>
		<li>Added hover boxes for Time Series Graphs.</li>
		<li>Changing method of data collection for usage.</li>
	</ul>
	<b>2019-07-03</b><ul>
		<li>Change method of data collection for graph types used to better cope with higher volumes of traffic to NZGrapher.</li>
	</ul>
	<b>2019-06-30</b><ul>
		<li>New option in dot plots to show as a strip graph, where instead of stacking dots, the vertical position of the dot is randomly allocated.</li>
		<li>Fix for titles on residuals graphs when switching back to scatter graphs.</li>
	</ul>
	<b>2019-06-15</b><ul>
		<li>Bug fix on vertical axis to stop very small numbers showing instead of zero.</li>
		<li>Bug fix on scatter plots and residuals for non-linear models where points had a zero value.</li>
	</ul>
	<b>2019-06-02</b><ul>
		<li>Updated to statistical tracking to ask students which school they are from.</li>
		<li>Added hover boxes on scatter graphs, residuals, dot plots, and bootstrap confidence intervals so when you hover over a point it gives you details on the point and highlights it in the data table.</li>
		<li>Rebuild of residuals graph into javascript, including by default not showing weighted average line, this is now an option in the graph options area.</li>
	</ul>
	<b>2019-05-26</b><ul>
		<li>Updated welcome message and about page.</li>
		<li>Added fee structure for 2020.</li>
		<li>Update to back end that does the modelling for the scatter graphs.</li>
		<li>Update a number of links.</li>
	</ul>
	<b>2019-04-05</b><ul>
		<li>Change to title of Re-randomisation graphs to remove incorrect part of title.</li>
		<li>Added "(STL)" after the Long Term Trend and Seasonal tickboxes on time series to clarify the method being used to draw these.</li>
	</ul>
	<b>2019-03-15</b><ul>
		<li>Made it so negatives in equations show up as " - " rather than " +- ".</li>
	</ul>
	<b>2019-01-13</b><ul>
		<li>Added "Update Graph" button to "More Options" and changed it so the more options didn't overlap the graph.</li>
	</ul>
	<b>2018-12-18</b><ul>
		<li>New feature - Sampling Variability... <a href='https://drive.google.com/file/d/1oox1SKylU7RdJU82ER5Imiw6dtju_qh2/view' target='_blank'>see a video here</a>.</li>
	</ul>
	<b>2018-11-17</b><ul>
		<li>Bug fix for freezing when resetting dataset after adding new columns.</li>
	</ul>
	<b>2018-11-17</b><ul>
		<li>Add option to hide the id of points removed.</li>
		<li>New Create Variable Option (from Condition) - useful for condensing categories or for creating categorical from numerical.</li>
	</ul>
	<b>2018-10-09</b><ul>
		<li>Recode of Pairs Plot module.</li>
	</ul>
	<b>2018-06-01</b><ul>
		<li>Major Visual Overhaul.</li>
		<li>Addition of Delete Specific Column.</li>
		<li>Various Bug Fixes.</li>
	</ul>
	<b>2018-05-05</b><ul>
		<li>Bug fixes for time series modules with custom seasons.</li>
	</ul>
	<b>2018-04-29</b><ul>
		<li>Bug fixes for time series encode.</li>
	</ul>
	<b>2018-04-25</b><ul>
		<li>Upgrade of 'Time Series' graph.</li>
		<li>Upgrade of 'Time Series Seasonal Effects' graph.</li>
		<li>Upgrade of 'Time Series Forecasts' graph.</li>
		<li>New Option under 'Sample and More' -> 'Convert Time' to convert dates or times into seconds, minutes, hours or days (great for bivariate data).</li>
		<li>New Option under 'Sample and More' -> 'Encode Time' to encode dates / times into the correct format for the time series module. It averages / sums the numerical values and finds the most common non-numerical values if there is more than one time in each interval.</li>
	</ul>
	<b>2018-02-03</b><ul>
		<li>Improvement to Update Graph button.</li>
	</ul>
	<b>2018-01-21</b><ul>
		<li>Styling Changes.</li>
		<li>Option to paste link to URL (note: must be accessible on the web for this to work).</li>
		<li>Fix for correlation coefficient not showing on scatter graph.</li>
	</ul>
	<b>2018-01-09</b><ul>
		<li>Re-write of Scatter Plot module into Javascript.</li>
		<li>Can now manually set min and max for x and y axis on scatter graphs using "More Options".</li>
	</ul>
	<b>2017-11-25</b><ul>
		<li>Changes to dot plots to make the points not round before graphing to be more statistically accurate.</li>
	</ul>
	<b>2017-08-06</b><ul>
		<li>Various Bug Fixes.</li>
	</ul>
	<b>2017-07-21</b><ul>
		<li>Fixed a bug in FireFox preventing the pasting of tables.</li>
	</ul>
	<b>2017-07-19</b><ul>
		<li>Added relative frequency option to bar graph.</li>
	</ul>
	<b>2017-07-16</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2017-07-01</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2017-06-13</b><ul>
		<li>Bug Fixes.</li>
		<li>Changes to Dotplot and Scatter Graph for visually impared students.</li>
		<li>New graph type of Histogram Frequency</li>
	</ul>
	<b>2017-06-02</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2017-05-26</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2017-05-23</b><ul>
		<li>Big increase to the limit on the number of different fields you can sample on.</li>
	</ul>
	<b>2017-05-12</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2017-05-11</b><ul>
		<li>Bug Fixes.</li>
		<li>Feedback Module.</li>
		<li>Grid Lines.</li>
	</ul>
	<b>2017-05-07</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2017-05-01</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2017-04-20</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2017-03-23</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2017-02-11</b><ul>
		<li>Bug Fixes.</li>
		<li>Fixes to Time Series Module.</li>
	</ul>
	<b>2017-02-24</b><ul>
		<li>Bug Fixes.</li>
		<li>Abililty to open file by url (adding ?url=http://whatever.com to the end of the link to grapher).</li>
	</ul>
	<b>2016-06-18</b><ul>
		<li>Bug Fixes.</li>
		<li>Even Huger speed increase to all sampling.</li>
	</ul>
	<b>2016-06-15</b><ul>
		<li>Bug Fixes.</li>
		<li>Huge speed increase to stratified sampling.</li>
		<li>Ability to remove seasonal graph on time series when fitting long term trend.</li>
	</ul>
	<b>2016-05-24</b><ul>
		<li>Bug Fixes.</li>
		<li>Default time series re-composition is the new version.</li>
	</ul>
	<b>2016-06-02</b><ul>
		<li>Security Fixes.</li>
	</ul>
	<b>2016-05-24</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2016-04-26</b><ul>
		<li>Default Bootstrapping Graphs are Now the New Versions.</li>
		<li>Bug Fixes.</li>
	</ul>
	<b>2016-04-22</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2016-04-20</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2016-04-19</b><ul>
		<li>Bug Fixes.</li>
		<li>Updated Logo.</li>
	</ul>
	<b>2016-04-16</b><ul>
		<li>Bug Fixes.</li>
		<li>New Logo.</li>
		<li>New Welcome Screen.</li>
	</ul>
	<b>2016-04-09</b><ul>
		<li>Bug Fixes.</li>
		<li>Fix for csv files created using terrible language settings that mean that comma separated values are separated by semicolons... these files should now work.</li>
		<li>3 new test graphs, bootstrap median, bootstrap mean and time series.</li>
	</ul>
	<b>2016-03-30</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2016-03-23</b><ul>
		<li>Bug Fixes.</li>
		<li>Filter Datasets</li>
		<li>Create Linear Function of a Variable</li>
	</ul>
	<b>2016-03-10</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2016-03-06</b><ul>
		<li>Bug Fixes.</li>
		<li>Fixed positioning of C-I labels on dotplots.</li>
	</ul>
	<b>2016-02-29</b><ul>
		<li>Bug Fixes.</li>
		<li>Fixed positioning of titles on table of data (left hand side).</li>
	</ul>
	<b>2016-02-25</b><ul>
		<li>Bug Fixes.</li>
		<li>Added ability to set min and max for the axis on Dot Plots (and Box and Whisker) with the more options button.</li>
	</ul>
	<b>2016-02-17</b><ul>
		<li>Bug Fixes.</li>
		<li>Added ability to graph hourly data.</li>
	</ul>
	<b>2016-02-11</b><ul>
		<li>Bug Fixes.</li>
		<li>Updated Dotplot Module</li>
	</ul>
	<b>2015-12-07</b><ul>
		<li>Addded ability to add box plots without whiskers.</li>
		<li>Addded ability to add box plots that stop at 1.5x less than LQ and 1.5x above the UQ.</li>
	</ul>
	<b>2015-12-04</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2015-11-20</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2015-11-13</b><ul>
		<li>Fix to code in histogram module to fix numbers ending up in the wrong bucket.</li>
		<li>Fix point size on paired experiment dotplots.</li>
	</ul>
	<b>2015-10-18</b><ul>
		<li>Fix to code in time series module.</li>
	</ul>
	<b>2015-10-18</b><ul>
		<li>Big update to how the colors work in dotplots and scatter graphs.</li>
	</ul>
	<b>2015-10-15</b><ul>
		<li>You now have the choice between additive and multiplicative models in the Time Series section.</li>
		<li>Hiding the left and bottom areas can now be done by clicking on the tripple dot menu in the top right corner.</li>
	</ul>
	<b>2015-09-22</b><ul>
		<li>You can now sort the data by any variables (under 'Sample and More' -> 'Sort').</li>
		<li>Bug Fixes.</li>
	</ul>
	<b>2015-09-17</b><ul>
		<li>Bootstrapping of single variables is now supported for means, medians, IQR and standard deviation.</li>
	</ul>
	<b>2015-09-01</b><ul>
		<li>Added ability to create a new calculated column (under "Sample and More".</li>
		<li>Make the dots on re-randomisation and bootstraps easier to see.</li>
		<li>Blank / non-numerical points in re-randomisation and bootstraps are no longer plotted.</li>
		<li>Changed the data menu to be able to more easily find functions.</li>
	</ul>
	<b>2015-08-19</b><ul>
		<li>Make the dots on Dot Plots / Scatter Plots / Residuals Plot easier to see.</li>
	</ul>
	<b>2015-08-18</b><ul>
		<li>Changed colour of pie charts to be more bold.</li>
		<li>Can now set width of bars in histogram.</li>
		<li>Bug Fixes.</li>
	</ul>
	<b>2015-07-15</b><ul>
		<li>Bug Fixes.</li>
	</ul>
	<b>2015-07-12</b><ul>
		<li>Added ability to make the box and whisker graphs at the top of the graph rather than overlapping the dot plot.</li>
		<li>Bug Fixes.</li>
	</ul>
	<b>2015-07-11</b><ul>
		<li>Added pie charts.</li>
		<li>Added histograms.</li>
		<li>Saving changes on graph now keeps the current selection.</li>
		<li>General updates.</li>
		<li>Bug Fixes.</li>
	</ul>
	<b>2015-07-09</b><ul>
		<li>Update time series module to use STL for decomposition and Holt-Winters for predictions.</li>
		<li>Bug Fixes.</li>
	</ul>
	<b>2015-07-08</b><ul>
		<li>Colour Coding of Paired Experiment Graphs.</li>
		<li>Drop-down box for setting of standard size graphs.</li>
	</ul>
	<b>2015-06-05</b><ul>
		<li>Bug fixes.</li>
	</ul>
	<b>2015-06-04</b><ul>
		<li>Added ability to paste a table in using the "Paste Table" button at the top.</li>
		<li>Increased size of Informal CI limits in dot plots and paired experiments.</li>
		<li>Bug fixes.</li>
	</ul>
	<b>2015-06-03</b><ul>
		<li>Bug fixes.</li>
	</ul>
	<b>2015-05-14</b><ul>
		<li>Added slider to adjust transparency of points.</li>
		<li>Reordered the check boxes for the paired experiment to put the arrows at the top.</li>
	</ul>
	<b>2015-05-09</b><ul>
		<li>Rename of re-randomisation graph (removing 'bootstrap' as was originally mislabelled).</li>
		<li>Added buttons to show and hide the left and bottom panels, particularly for when graphing on devices with smaller screens.</li>
	</ul>
	<b>2015-04-30</b><ul>
		<li>Bug fix in scatter plots with missing data.</li>
		<li>New method for updating.</li>
	</ul>
	<b>2015-04-23</b><ul>
		<li>Added option to include a y=x line on scatter plots.</li>
	</ul>
	<b>2015-04-13</b><ul>
		<li>Time Series module can now graph yearly data.</li>
		<li>Bug fixes.</li>
	</ul>
	<b>2015-04-02</b><ul>
		<li>Style Update.</li>
		<li>Bug fixes.</li>
	</ul>
	<b>2015-03-27</b><ul>
		<li>Changes to the time series module.</li>
		<li>Lots and lots of bug fixes.</li>
	</ul>
	<b>2015-03-06</b><ul>
		<li>If a data point is not numeric in dotplots, scatter plots and residuals it now doesn't get graph the data points, but list their id in the top right of the graph.</li>
		<li>Lots of bug fixes.</li>
	</ul>
	<b>2015-03-06</b><ul>
		<li>Lots of bug fixes.</li>
	</ul>
	<b>2015-03-04</b><ul>
		<li>Added 'jitter' option on Scatter Graphs.</li>
	</ul>
	<b>2015-03-03</b><ul>
		<li>Miscellaneous Bug Fixes.</li>
		<li>Added different model types to the scatter and residual graphs.</li>
		<li>Major changes to the way time series data is handled for better predictions and faster performance.</li>
	</ul>
	<b>2015-02-18</b><ul>
		<li>Fixed an error when uploading files from IE 11 on Windows 8.1.</li>
		<li>Added daily dataset for work-week data (5 day cycle).</li>
	</ul>
	<b>2014-12-05</b><ul>
		<li>Added in a slider to change the point sizes in dot plots, scatter plots, residuals, and all the bootstrap graphs.</li>
		<li>Made the section where the graph options are set scrollable.</li>
	</ul>
	<b>2014-12-05</b><ul>
		<li>Datasets that start with SECURE (all in capitals) are not able to be downloaded using the download button.</li>
	</ul>
	<b>2014-12-05</b><ul>
		<li>Fixed an error on stratified sampling were one category was contained in another, eg: male and female.</li>
	</ul>
	<b>2014-11-28</b><ul>
		<li>Added in the ability to display the end points of the informal confidence intervals.</li>
		<li>Fixed Spelling of 'residual' in the time series graph.</li>
		<li>Added a new 'time series' graph that only displays the raw time series data.</li>
	</ul>
	<b>2014-11-27</b><ul>
		<li>Changed regression lines to being in terms of variable names rather than in y and x.</li>
	</ul>
	<b>2014-11-25</b><ul>
		<li>Fixed an error on uploading CSV files from Windows 7.</li>
	</ul>
	<b>2014-10-29</b><ul>
		<li>Fixed an error on reordering variables.</li>
		<li>Fixed an error with how NZGrapher handles commas in uploaded files.</li>
		<li>Area graphs font has been changed to make it easier to see labels.</li>
	</ul>
	<b>2014-10-25</b><ul>
		<li>Significantly improved the sampling module to allow it to use stratified sampling as well as simple random sampling.</li>
		<li>Added ability to reorder categorical variables.</li>
	</ul>
	<b>2014-10-24</b><ul>
		<li>Disabled the ability to enter commas or line breaks into the data as this causes issues with the graphing.</li>
		<li>Added links to <a target='_blank' href="http://students.mathsnz.com/nzgrapher">MathsNZ Students</a> with video tutorials and dataset information.</li>
	</ul>
	<b>2014-10-06</b><ul>
		<li>Graphs on the pairs plot are now click-able so when you click on the graph it loads it up full size.</li>
		<li>Paired experiment graphing now available including arrows graphs.</li>
		<li>Bug fix with downloading of data.</li>
		<li>Various other bug fixes.</li>
	</ul>
	<b>2014-10-04</b><ul>
		<li>Added bar and area graph.</li>
		<li>Various bug fixes for when the range was zero.</li>
	</ul>
	<b>2014-10-03</b><ul>
		<li>Added pairs plot.</li>
		<li>Sorted out when the colours label showed up.</li>
		<li>Fixed an error on dotplots when calculating LQ and UQ when they only had one point in the dataset.</li>
		<li>Fixed an issue with the colouring of continuous variables.</li>
	</ul>
	<b>2014-09-29</b><ul>
		<li>Added ability to colour points in scatter graphs and dot plots.</li>
		<li>Changed font used for easier readability.</li>
	</ul>
	<b>2014-09-28</b><ul>
		<li>Added ability to subset dotplots.</li>
		<li>Bolded summary statistics to make easier to read when printing.</li>
	</ul>
	<b>2014-09-23</b><ul>
		<li>Added ability to download the current dataset after edits.</li>
	</ul>
	<b>2014-09-17</b><ul>
		<li>Fixed an on time series seasonal effects not displaying zero correctly.</li>
	</ul>
	<b>2014-09-17</b><ul>
		<li>Fixed an error with the bootstrap re-randomisation reversing the order of the bootstrap depending on the naming of groups.</li>
		<li>Added labels to the bootstrap axis so it is clear which way round the calculation is being done.</li>
	</ul>
	<b>2014-09-08</b><ul>
		<li>Fixed an error with Firefox causing the new data rows not to be editable.</li>
		<li>Fixed an error in the calculation of quartiles to use the same method we use in New Zealand, rather than the method used by Excel.</li>
	</ul>
	<b>2014-09-01</b><ul>
		<li>Added more informative (pop-up) messages if file uploads fail. Maximum file size is 100kb and must be in CSV format.</li>
	</ul>
	<b>2014-08-29</b><ul>
		<li>Added in informal confidence intervals for dotplots.</li>
	</ul>
	<b>2014-08-28</b><ul>
		<li>Scatter plots no longer automatically add in the trend line, this must be added in by clicking the 'Regression Line' button.</li>
		<li>Dot plots no longer automatically show the summary statistics or box and whiskers, this must be added in by clicking the 'Summaries' or 'Box Plots' buttons.</li>
		<li>Changed the way the labels for this button appear as well to make it more clear what each one does.</li>
	This has been done in an attempt to get students to write about what they see rather than what is calculated as a first step.</li>
	</ul>
	<b>2014-08-25</b><ul>
		<li>Updated fonts to avoid overlapping of texts.</li>
	</ul>
	<b>2014-08-18</b><ul>
		<li>Clicking anywhere will now hide the overlay.</li>
	</ul>
	<b>2014-08-12</b><ul>
		<li>Fixed algorithm for bootstrap re-randomisation.</li>
	</ul>
	<b>2014-08-08</b><ul>
		<li>Changed to a 0.2 second timeout before reloading graph when resizing to reduce serve load.</li>
		<li>Removed auto-update of graph when axis or graph title was changed and added the update graph button.</li>
	</ul>
	<b>2014-07-30</b><ul>
		<li>Fixed issue with screen rotation on iPad causing graphs to not display correctly.</li>
		<li>Readjusted the positioning of summary statistics for dotplots.</li>
	</ul>
	<b>2014-07-29</b><ul>
		<li>Changed the axis so for large numbers they are smaller and should all fit.</li>
		<li>Changed the images so you can right click to copy and paste into word / elsewhere or press and hold to save on iPad.</li>
		<li>Adding scrolling if needed to the holt winters forecasts.</li>
		<li>Updated the position of the summary statistics for the dotplots.</li>
		<li>Added Change Log</li>
	</ul>
	<b>2014-07-28</b><br>
	Changes prior to this date were not logged
