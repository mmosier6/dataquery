<?php
//set date variables

$cdate = strftime("%Y%m%d");
$cHHmm = strftime("%H%M");
//need to make sure of the right date if time >0z and <12z
if (($cHHmm >= 1200) && ($cHHmm <= 2359)) {
	$YY = substr($cdate, 0, 4);
        $MM = substr($cdate, 4, 2);
        $DD = substr($cdate, 6, 2);
        $ctime = mktime(00, 0, 0, $MM, $DD, $YY);
} elseif (($cHHmm >= 0000) && ($cHHmm <= 1159)) {
        $YY = substr($cdate, 0, 4);
        $MM = substr($cdate, 4, 2);
        $DD = substr($cdate, 6, 2);
        $ctim = mktime(0, 0, 0, $MM, $DD, $YY);
        $ctime = strftime("%s", $ctim - (24*60*60));
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

?>

<!--[if lte IE 8]<script language="javascript" type="text/javascript" src="/new/js/excanvas.min.js"></script><![endif]-->

<div id="reportWrapper" style="width:310px;height:200px; padding:0px;">
<table border="0px" width="300px" cellpadding="0px" cellspacing="0px">
<tr>
<td width="15px" height="200px" valign="middle"><img src="/new/images/repCount_sm.png"></td>
<td> <div id="placeholder" style="width:285px;height:200px; float: left;"></div></td>
</tr>
<tr><td colspan="2" align="middle" style="font-family:arial;font-size:1.0em;font-weight:bold;line-height:1.3em;">report time</td></tr>
</table>
</div>



<script type="text/javascript">
var $j = jQuery.noConflict();

$j(function () {

var $j = jQuery.noConflict();

var options =
{
        lines: { show: true  },
	points: { show: true, radius: .1 },
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


