<?php
//set date variables

if (isset($_GET["date"])) {
	//condition: a date variable passed, define $ctime
	$cdate = $_GET["date"]; 
        $cHHmm = "1200";
	$YY = substr($cdate, 0, 4);
	$MM = substr($cdate, 4, 2);
	$DD = substr($cdate, 6, 2);
	$ctime = mktime(0, 0, 0, $MM, $DD, $YY);
} else {
	//condition: no date variable passed, define $ctime
	$cdate = strftime("%Y%m%d");
	$cHHmm = strftime("%H%M");
	//need to make sure of the right date if time >0z and <12z
	if (($cHHmm >= 1200) && ($cHHmm <= 2359)) {
		$YY = substr($cdate, 0, 4);
                $MM = substr($cdate, 4, 2);
                $DD = substr($cdate, 6, 2);
		$ctime = mktime(0, 0, 0, $MM, $DD, $YY);
	} elseif (($cHHmm >= 0000) && ($cHHmm <= 1159)) {
		$YY = substr($cdate, 0, 4);
        	$MM = substr($cdate, 4, 2);
       	 	$DD = substr($cdate, 6, 2);
		$ctim = mktime(0, 0, 0, $MM, $DD, $YY);
		$ctime = strftime("%s", $ctim - (24*60*60));
	}
}

$tdyEpoch = $ctime;
$tmrwEpoch = $tdyEpoch + 86400;
$tdyLabel = strftime("%m-%d-%Y", $ctime);
$tmrwLabel = strftime("%m-%d-%Y", $ctime + (24*60*60));
$ydyNav = strftime("%Y%m%d", $ctime - (24*60*60));
$tmrwNav = strftime("%Y%m%d", $ctime + (24*60*60));
$rptDate = strftime("%y%m%d", $ctime);

$tornfile = file($_SERVER['DOCUMENT_ROOT']."/climo/reports/${rptDate}_rpts_torn.csv");
$windfile = file($_SERVER['DOCUMENT_ROOT']."/climo/reports/${rptDate}_rpts_wind.csv");
$hailfile = file($_SERVER['DOCUMENT_ROOT']."/climo/reports/${rptDate}_rpts_hail.csv");

//remove the header from the arrays
$tornFile = array_shift($tornfile);
$windFile = array_shift($windfile);
$hailFile = array_shift($hailfile);

$label = "Daily storm report trend for the period: " . $tdyLabel . "/12z to " . $tmrwLabel . "/12z";

//check to see if any reports have been received
$tornSize = count($tornfile);
$windSize = count($windfile);
$hailSize = count($hailfile);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="DC.title" content="NOAA/NWS Storm Prediction Center" />
    <meta name="DC.language" scheme="DCTERMS.RFC1766" content="EN-US" />
    <meta name="language" content="EN-US" />
    <title>NOAA/NWS Storm Prediction Center Severe Weather Climatology Data</title>

    <!-- Sitewide css and scripts -->
    <link rel="stylesheet" href="/new/css/SPCmain.css" />

    <!--Conflict between prototype.js and jquery, so using jQuery.noConflict() -->
    <script src="//ajax.googleapis.com/ajax/libs/prototype/1.7.1.0/prototype.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="/new/js/SPCindex.js"></script>
    <script type="text/javascript" src="/misc/usno_gmttime.js"></script>
    <script type="text/javascript" src="/misc/lastMod.js"></script>
    <script type="text/javascript" src="/new/js/jquery.hoverIntent.minified.js"></script>

    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/new/js/excanvas.min.js"></script><![endif]-->
    <script language="javascript" type="text/javascript" src="/new/js/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="/new/js/jquery.flot.time.min.js"></script>


    <script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore)
{
        //v3.0
        eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
        if (restore) selObj.selectedIndex=0;
}
</script>
<script type="text/javascript" src="/new/js/topNavMenu.js"></script>
</head>

<body>

<div id="SPCWrapper">
   <div id="SPCMain">
      <div class="SPCMain2">
         <!-- ##### SPC TOP MENU ##### -->
        <?php include($_SERVER['DOCUMENT_ROOT']."/new/MainMenu/topMenu.html"); ?>

         <div class="full_width" > <!-- Page Content Below -->
<br>
<div id="reportWrapper" style="width:930px;height:760px; padding:0px;">

<?php

print "<table border='0px' width='930px' cellpadding='0px' cellspacing='0px'><tr>\n";
print "<td align='left' id='rptNav'><a href='reportPlot.php?date=$ydyNav' target='_parent'><< ${ydyNav} (previous)</a></td><td align='right' id='rptNav'><a href='reportPlot.php?date=$tmrwNav' target='_parent'>${tmrwNav} (next) >></a></td>\n";
print "</tr></table>\n";


//create table if reports have been recieved
if (($tornSize != 0) || ($windSize != 0) || ($hailSize != 0)) {
?>

<table border="0px" width="930px" cellpadding="0px" cellspacing="0px">
<tr>
<td colspan="2" align="middle" id='rptLabel'><?php echo $label ?></td></tr>
<td width="30px" height="700px" valign="middle"><img src="/new/images/repCount.png"></td>
<td><div id="placeholder" style="width:900px;height:700px; float: left;"></div></td>

</tr>
<tr><td colspan="2" align="middle" style="font-family:arial;font-size:1.1em;line-height:1.3em;">report time</td></tr>
</table>
</div>
<br>

<script type="text/javascript">
var $j = jQuery.noConflict();

$j(function () {

var $j = jQuery.noConflict();

var options =
{
        lines: { show: true  },
	points: { show: true, radius: .5 },
        xaxis: { "mode": "time", "timeformat": "%0d/%HZ" },
	yaxis: { tickDecimals: 0 },
        grid: { backgroundColor: { colors: ["#fff", "#fcf9de"] }},
	legend: { position: "nw", backgroundOpacity: 1, labelBoxBorderColor: "#000" }
};

<?php
//count the reports for the legend
$t=0;
foreach($tornfile as $line) { $t++; }
?>

var torn =
{
        <?php print "label: \"Tornado ($t)\",\n"; ?>
        color: "#ff0000",
        data:
        [
<?php
$torn = array();
foreach($tornfile as $line) {
        $tline = trim($line);
        $data = explode(",", $tline);
        $time = $data[0];
        $repHH = substr($time,0,2);
        $repMM = substr($time,2,2);
        $repHHMM = $repHH . $repMM;
        $repHHsec = $repHH * 3600;
        $repMMsec = $repMM * 60;
        if (($time >= 1200) && ($time <=2359)) {
                $repEpoch = intval($tdyEpoch + $repHHsec + $repMMsec);
        } else { 
                $repEpoch = intval($tmrwEpoch + $repHHsec + $repMMsec);
        }
        $repEpochMilli = $repEpoch * 1000;
	array_push($torn, $repEpochMilli); 
}

//sort the array
asort($torn);
$i = 1;
foreach ($torn as $event) {
	print "[$event, \"$i\"],\n";
	$i++;
}

?>
],};

<?php
//count the reports for the legend
$w=0;
foreach($windfile as $line) { $w++; }
?>

var wind =
{
        <?php print "label: \"Wind ($w)\",\n"; ?>
        color: "#0000ff",
        data:
        [
<?php
$wind = array();
foreach($windfile as $line) {
        $tline = trim($line);
        $data = explode(",", $tline);
        $time = $data[0];
        $repHH = substr($time,0,2);
        $repMM = substr($time,2,2);
        $repHHMM = $repHH . $repMM;
        $repHHsec = $repHH * 3600;
        $repMMsec = $repMM * 60;
        if (($time >= 1200) && ($time <=2359)) {
                $repEpoch = intval($tdyEpoch + $repHHsec + $repMMsec);
        } else { 
                $repEpoch = intval($tmrwEpoch + $repHHsec + $repMMsec);
        }
	$repEpochMilli = $repEpoch * 1000;
	array_push($wind, $repEpochMilli);
}

//sort the array
asort($wind);
$i = 1;
foreach ($wind as $event) {
        print "[$event, \"$i\"],\n";
        $i++;
}

?>
],};

<?php
//count the reports for the legend
$h=0;
foreach($hailfile as $line) { $h++; }
?>
	
var hail =
{
        <?php print "label: \"Hail ($h)\",\n"; ?>
        color: "#008000",
        data:
        [
<?php
$hail = array();
foreach($hailfile as $line) {
        $tline = trim($line);
        $data = explode(",", $tline);
        $time = $data[0];
        $repHH = substr($time,0,2);
        $repMM = substr($time,2,2);
        $repHHsec = $repHH * 3600;
        $repMMsec = $repMM * 60;
	if (($time >= 1200) && ($time <=2359)) {
        	$repEpoch = intval($tdyEpoch + $repHHsec + $repMMsec);
	} else { 
		$repEpoch = intval($tmrwEpoch + $repHHsec + $repMMsec); 
	}
        $repEpochMilli = $repEpoch * 1000;
	array_push($hail, $repEpochMilli);
}
//sort the array
asort($hail);
$i = 1;
foreach ($hail as $event) {
        print "[$event, \"$i\"],\n";
        $i++;
}

?>
],};

    $j.plot($j("#placeholder"), [torn,wind,hail], options);
});
</script>

<?php
} else { 
print "<table border='0px' width='930px' cellpadding='0px' cellspacing='0px'><tr>\n";
print "<td align='middle' id='noRPT'>No Reports Received for the period:<br>$tdyLabel/12z to $tmrwLabel/12z</td></tr></table>\n"; 
}
?>
    
   </div> <!-- SPCMain2 -->
   </div> <!-- SPCMain -->

   <!-- ##### FOOTER ACROSS BOTTOM OF PAGE ##### -->
   <div class="footer_container">
      <div id="footer">
        <?php include($_SERVER['DOCUMENT_ROOT']."/new/footer/footer1.html"); ?>

        Page last modified:
        <!--#config timefmt="%B %d, %Y"-->
        <!--#config errmsg=""-->
        <?php include($_SERVER['DOCUMENT_ROOT']."/new/footer/footer2.html"); ?>

      </div>
   </div>

<div class="clear"> </div> <!-- Sets background to white -->

 </div>
<script type="text/javascript">
  var $j = jQuery.noConflict();
  show_tab("TABoverview");
  $j("#query-field").blur();
</script>
</body>
</html>