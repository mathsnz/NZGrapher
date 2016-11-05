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
	<h1><img src='logob.png' style='position:relative;top:22px;height:65px;'> Graphs</h1>
	</center>
	Note: You can also access <b><a target='_blank' href="//students.mathsnz.com/nzgrapher">video tutorials</a></b> to help you getting started on <a target='_blank' href="http://students.mathsnz.com/nzgrapher">MathsNZ - Students</a>. They are organised in two ways, firstly by the type of graph you are trying to draw, and secondly by the NCEA standard that they relate to.<br>
	<br>
	<b>Pairs Plot</b><br>
	This graph gives a quick overview of all of the variables in the dataset. Note: it will not work if the first data point has any missing values.<br>
	<br>
	<br>
	<b>Dot Plot (and Box and Whisker)</b><br>
	This graph displays a dot plot with a box and whisker overlaid on the top.<br>
	It takes two variables. The x-variable must be numerical. The y-variable is optional but if selected must have 10 or fewer categories or if it is numerical it will automatically be split into 4 categories.<br>
	The summaries tick box will add in the summaries over the top in red for the individual groups.<br>
	<br>
	<b>Bar Graph (and Area Graph)</b><br>
	If you select an x-variable a bar graph will be drawn. If you select both an x and y-variable then it will draw an area graph where the area of each rectangle is proportional to the number of items it represents.<br>
	<br>
	<b>Histogram</b><br>
	This graph draws a histogram for the data. The x-variable needs to be numerical. The y-variable is optional but if selected must have 4 or fewer categories or if it is numerical it will automatically be split into 4 categories.<br>
	<br>
	<b>Pie Chart</b><br>
	This graph draws a pie chart for the data. The y-variable is optional but if selected split the data into groups.<br>
	<br>
	<b>Scatter Graph</b><br>
	This graph draws a scatter plot of two variables. It can optionally be subset by a third variable.<br>
	It must have two variables. The x and y variables both must be numerical. The subset variable is optional but if selected must have 4 or fewer categories or if it is numerical it will automatically be split into 4 categories.<br>
	The regression line tick box will add in a linear regression line and will display the equation and the correlation coefficient (r) value on the graph. Other models can also be fitted by ticking the appropriate boxes.<br>
	<br>
	<b>Residuals Plot</b><br>
	This graph must have two variables. The x and y variables both must be numerical.<br>
	The residuals is based on the linear regression line by default as displayed in the scatter graph, but other options can be chosen below.<br>
	You cannot change the axis labels on this graph.<br>
	<br>
	<br>
	<b>Bootstrap Confidence Interval (Median or Mean)</b><br>
	This graph constructs a 95% bootstrap confidence interval for the difference in the medians or means based on 1000 iterations rounded to 4 significant figures.<br>
	It must have two variables. The x-variable must be numerical and the y-variable must have 2 categories.<br>
	The summaries tick box will add in the summaries over the top in red for the individual groups.<br>
	<br>
	<br>
	<b>Re-randomisation (Median or Mean)</b><br>
	This graph creates a re-sampling or re-randomisation distribution by doing 1000 iterations, randomly allocating the data into two groups based on the original number in each group and calculating the difference between the medians or means, and then calculates the probability of the sample difference occurring by chance alone (i.e. the tail proportion).<br>
	It must have two variables. The x-variable must be numerical and the y-variable must have 2 categories.<br>
	The summaries tick box will add in the summaries over the top in red for the individual groups.<br>
	<br>
	<br>
	<b>Paired Experiment Dot Plot (and Arrows Graph)</b><br>
	This graph creates a dotplot from two columns of the data, and has the option of producing an arrows graph.<br>
	It must have two variables. The x-variable  and the y-variable must both be numerical.<br>
	The arrows tick box will replace the dotplot with an arrows graph.<br>
	<br>
	<br>
	<b>Time Series</b><br>
	All of the time series graphs are calculated using Seasonal Trend LOESS decomposition and Holt-Winters predictions based on minimising sum of the squares of one step ahead errors.<br>
	They all require a time input for the x-variable and the y-variable must be numerical.<br>
	If you want to know more how these models work please see <a target='_blank' href="//www.mathsnz.com/blog/nzgraphertimeseriesplots">my blog post</a>.<br>
	The supported time formats are: 2005Q1 (quarterly, 4 seasons), 2005M01 (monthly, 12 seasons), 2001D1 (daily, 7 'seasons'), 2001W1 (daily, 5 'seasons') or 2001H01 (hourly, 24 'seasons').
	<blockquote>
		<b><i>Re-composition</i></b><br>
		The top graph shows the raw data as well as the trend and the fitted data.<br>
		The bottom graph shows the residuals, with a grey line at zero, and a light grey line at 10% of the range above and below.<br>
		<br>
		<b><i>Seasonal Effects</i></b><br>
		The left graph displays all the individual years overlapping each other with the most recent years in blue, and previous years slowly fading out.<br>
		The right graph displays the average seasonal effect (with light grey lines for the individual years if using a multipliciative model).<br>
		<br>
		<b><i>Forecasts</i></b><br>
		The forecast intervals are based on a Monte Carlo simulation (similar idea to bootstrapping) on the error correction/state space model equations for Holt-Winters.<br>
		The forecast output tick box will display a text output of the forecasts.<br>
		<br>
	</blockquote>
</div>
	</div>
	</body>
</html>
