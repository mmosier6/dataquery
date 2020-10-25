<link rel="stylesheet" href="/new/css/SPCmain.css" />

<?php
//First, determine if there is a valid PWO (and MP4).
$validPWO = file("./SPCWeatherHeadlines/validPWO.txt");

if ($validPWO[0] == "none" ) { $pwo = 0; print "No valid PWO\n"; }
else { $pwo = 1; }

//If there is a valid PWO, determine the risk category for the Day 1 outlook and whether an MP4 exists.
$validProd = file("./SPCProducts/validProducts.txt");

$mp4 = 0;
foreach($validProd as $line) { 
        $tline = trim($line);
        $data = explode("|",$tline);
        $prodType = $data[0];
	if ($prodType == "OTLK") { $D1Cat = $data[3]; }
	if ($prodType == "OTLK2") { $D2Cat = $data[3]; }
	if ($prodType == "OTLK3") { $D3Cat = $data[3]; }
	if ($prodType == "MP4") { $mp4 = 1; }
	if ($prodType == "D1") { $D1Day = $data[1]; }
	if ($prodType == "D2") { $D2Day = $data[1]; }
	if ($prodType == "D3") { $D3Day = $data[1]; }
}

//The headline needs to be driven off the outlook since if a MDT or HIGH risk is issued at 06z, a PWO will not be issued until ~08-09z.


////////////////
//D1 HIGH RISK//
////////////////
if ($D1Cat == "High") {
	print "<div class='SPCMain2'>\n";
	print "<div class='SPCWeatherHeadlines'>\n";
	print "<div class='SPCWeatherHeadlinesBoxActive'>\n";
	print "<div class='SPCWeatherHeadlinesUrgent'>\n";
	print "A Major Severe Weather Outbreak is Forecast Today and/or Tonight!\n";
	print "</div>\n"; //close SPCWeatherHeadlinesUrgent
	print "<div class='SPCWeatherHeadlinesBody'>\n";
	if ($pwo == 0) {
		print "The Storm Prediction Center is forecasting the development of a major severe weather outbreak today and/or tonight. For additional details, see the latest <a href='/products/outlook/day1otlk.html'>Day 1 Convective Outlook</a>.\n";
	}
	if ($pwo == 1) {
		//print PWO contents
       		foreach($validPWO as $line) { print "$line\n"; }
		print "<br>\n";
		print "&ndash; For additional details, see the current <a href='/products/outlook/pwo.html'> PWO</a>.\n";
	}
	if ($mp4 == 1) {
		print "<br>\n";
                print "&ndash; A <a href='/products/outlook/pwo.mp4'>multi-media briefing</a> is also available.\n";
	}
	if (($D2Cat == "High") || ($D2Cat == "Moderate"))  {
                print "<br>\n";
                print "&ndash; ${D2Cat} Risk for severe storms on ${D2Day}.  See <a href='/products/outlook/day2otlk.html'>details</a>.\n";
        }
        if ($D3Cat == "Moderate")  {
                print "<br>\n";
                print "&ndash; ${D3Cat} Risk for severe storms on ${D3Day}.  See <a href='/products/outlook/day3otlk.html'>details</a>.\n";
        }
	print "</div>\n"; //close SPCWeatherHeadlinesBody
	print "</div>\n"; //close SPCWeatherHeadlinesBox
	print "</div>\n"; //close SPCWeatherHeadlines
	print "</div>\n"; //close SPCMain2
}
///////////////
//D1 MDT RISK//
///////////////
if ($D1Cat == "Moderate") {
        print "<div class='SPCMain2'>\n";
        print "<div class='SPCWeatherHeadlines'>\n";
        print "<div class='SPCWeatherHeadlinesBoxActive'>\n";
        print "<div class='SPCWeatherHeadlinesUrgent'>\n";
        print "A ${D2Cat} Risk of Severe Thunderstorms is Forecast Today and/or Tonight!\n";
        print "</div>\n"; //close SPCWeatherHeadlinesUrgent
        print "<div class='SPCWeatherHeadlinesBody'>\n";
        if ($pwo == 0) {
                print "The Storm Prediction Center is forecasting the development of a severe weather outbreak today and/or tonight. For additional details, see the latest <a href='/products/outlook/day1otlk.html'>Day 1 Convective Outlook</a>.\n";
        }
        if ($pwo == 1) {
                //print PWO contents
                foreach($validPWO as $line) { print "$line\n"; }
                print "<br>\n";
                print "&ndash; For additional details, see the current <a href='/products/outlook/pwo.html'> PWO</a>.\n";
        }
        if ($mp4 == 1) {
                print "<br>\n";
                print "&ndash; A <a href='/products/outlook/pwo.mp4'>multi-media briefing</a> is also available.\n";
        }
	if (($D2Cat == "High") || ($D2Cat == "Moderate"))  {
                print "<br>\n";
                print "&ndash; ${D2Cat} Risk for severe storms on ${D2Day}.  See <a href='/products/outlook/day2otlk.html'>details</a>.\n";
        }
	if ($D3Cat == "Moderate")  {
                print "<br>\n";
                print "&ndash; ${D3Cat} Risk for severe storms on ${D3Day}.  See <a href='/products/outlook/day3otlk.html'>details</a>.\n";
        }
        print "</div>\n"; //close SPCWeatherHeadlinesBody
        print "</div>\n"; //close SPCWeatherHeadlinesBox
        print "</div>\n"; //close SPCWeatherHeadlines
        print "</div>\n"; //close SPCMain2
}
///////////////////////
//D2 MDT or HIGH RISK//
///////////////////////
if (( $D1Cat != "High") && ($D1Cat != "Moderate")) {
        if ($D2Cat == "High") {
                print "<div class='SPCMain2'>\n";
                print "<div class='SPCWeatherHeadlines'>\n";
                print "<div class='SPCWeatherHeadlinesBoxActive'>\n";
                print "<div class='SPCWeatherHeadlinesUrgent'>\n";
                print "A Major Severe Weather Outbreak is Forecast on ${D2Day}\n";
                print "</div>\n"; //close SPCWeatherHeadlinesUrgent
                print "<div class='SPCWeatherHeadlinesBody'>\n";
                print "The Storm Prediction Center is forecasting the development of a tornado outbreak on ${D2Day}.  For additional details, see the latest <a href='/products/outlook/day2otlk.html'>Day 2 Convective Outlook</a>.\n";
		if ($D3Cat == "Moderate")  {
                	print "<br>\n";
                	print "&ndash; ${D3Cat} Risk for severe storms on ${D3Day}.  See <a href='/products/outlook/day3otlk.html'>details</a>.\n";
        	}
                print "</div>\n"; //close SPCWeatherHeadlinesBody
                print "</div>\n"; //close SPCWeatherHeadlinesBox
                print "</div>\n"; //close SPCWeatherHeadlines
                print "</div>\n"; //close SPCMain2
        }
        if ($D2Cat == "Moderate") {
                print "<div class='SPCMain2'>\n";
                print "<div class='SPCWeatherHeadlines'>\n";
                print "<div class='SPCWeatherHeadlinesBoxActive'>\n";
                print "<div class='SPCWeatherHeadlinesUrgent'>\n";
                print "A ${D2Cat} Risk of Severe Thunderstorms is forecast on ${D2Day}\n";
                print "</div>\n"; //close SPCWeatherHeadlinesUrgent
                print "<div class='SPCWeatherHeadlinesBody'>\n";
                print "The Storm Prediction Center is forecasting a ${D2Cat} Risk of severe thunderstorms on ${D2Day}.  For additional details, see the latest <a href='/products/outlook/day2otlk.html'>Day 2 Convective Outlook</a>.\n";
                if ($D3Cat == "Moderate")  {
                	print "<br>\n";
                	print "&ndash; ${D3Cat} Risk for severe storms on ${D3Day}.  See <a href='/products/outlook/day3otlk.html'>details</a>.\n";
        	}
		print "</div>\n"; //close SPCWeatherHeadlinesBody
                print "</div>\n"; //close SPCWeatherHeadlinesBox
                print "</div>\n"; //close SPCWeatherHeadlines
                print "</div>\n"; //close SPCMain2
        }
}

///////////////
//D3 MDT RISK//
///////////////
if (( $D1Cat != "High") && ($D1Cat != "Moderate") && ($D2Cat != "High") && ($D2Cat != "Moderate")) {
	if ($D3Cat == "Moderate") {
		print "<div class='SPCMain2'>\n";
        	print "<div class='SPCWeatherHeadlines'>\n";
        	print "<div class='SPCWeatherHeadlinesBoxActive'>\n";
        	print "<div class='SPCWeatherHeadlinesUrgent'>\n";
		print "A ${D3Cat} Risk of Severe Thunderstorms is forecast on ${D3Day}\n";
		print "</div>\n"; //close SPCWeatherHeadlinesUrgent
		print "<div class='SPCWeatherHeadlinesBody'>\n";
		print "The Storm Prediction Center is forecasting a ${D3Cat} Risk of severe thunderstorms on ${D3Day}.  For additional details, see the latest <a href='/products/outlook/day3otlk.html'>Day 3 Convective Outlook</a>.\n";
		print "</div>\n"; //close SPCWeatherHeadlinesBody
        	print "</div>\n"; //close SPCWeatherHeadlinesBox
        	print "</div>\n"; //close SPCWeatherHeadlines
        	print "</div>\n"; //close SPCMain2
	}
}

///////////////////////////////////
//No Elevated Severe Weather Risk//
///////////////////////////////////

if (( $D1Cat != "High") && ($D1Cat != "Moderate") && ($D2Cat != "High") && ($D2Cat != "Moderate") && ($D3Cat !="Moderate")) {
	print "<div class='SPCMain2'>\n";
        print "<div class='SPCWeatherHeadlines'>\n";
        print "<div class='SPCWeatherHeadlinesBoxNull'>\n";
	print "<div class='SPCWeatherHeadlinesNull'>\n";
	print "Latest SPC Headlines...\n";
	print "</div>\n"; //close SPCWeatherHeadlinesNull
	print "<div class='SPCWeatherHeadlinesBody'>\n";

	//read in the news file.
	$SPCnews = file("./SPCWeatherHeadlines/newsHeadline.txt");
	foreach($SPCnews as $line) { print "$line\n"; }
		

	print "</div>\n"; //close SPCWeatherHeadlinesBody
        print "</div>\n"; //close SPCWeatherHeadlinesBox
        print "</div>\n"; //close SPCWeatherHeadlines
        print "</div>\n"; //close SPCMain2
}


?>

