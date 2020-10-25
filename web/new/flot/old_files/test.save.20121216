<?php
//set date variables

$today = gmdate("Y-M-d H:i:s \U\T\C");
$YYYY = gmdate("Y");
$MM = gmdate("m");
$DD = gmdate("d");
$HH = gmdate("H");
$mm = gmdate("i");
$HHmm = $HH . $mm;

$curday = strtotime("now");
$tdy = date('m-d-Y', $curday);
$yesterday = strtotime('-1 days');
$ydy = date('m-d-Y', $yesterday);
$tomorrow = strtotime('+1 days');
$tmrw = date('m-d-Y', $tomorrow);

if (($HHmm >= "1200") && ($HHmm <=2359)) {
	$label = "Daily storm report trend for the period: " . $tdy . "/12z to " . $tmrw . "/12z";
} else { $label = "Daily storm report trend for the period: " . $ydy . "/12z to " . $tdy . "/12z";
}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SPC daily storm report trend for: <?php echo $label?></title>
    <link href="layout.css" rel="stylesheet" type="text/css">
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../excanvas.min.js"></s
cript><![endif]-->
    <script language="javascript" type="text/javascript" src="jquery.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.time.min.js"></script> 

<style>
#placeholder > div.legend > table {
  border: 1px solid #ccc;
  padding: 5px;
  background: #fff;
}
</style>

 </head>
    <body>

<div id="reportWrapper" style="width:900px;height:700px;">
<table border="0px" width="900px" cellpadding="0px" cellspacing="0px">
<tr>
<td colspan="2" align="middle" style="font-family:arial;font-size:1.3em;"><?php echo $label ?></td></tr>
<td width="25px" valign="middle"><img src="/new/images/repCount.png"></td>
<td> <div id="placeholder" style="width:800px;height:500px; float: left;"></div></td>
</tr>
<tr><td colspan="2" align="middle" style="font-family:arial;font-size:1.3em;">report time</td></tr>
</table>
</div> 

<script type="text/javascript">
$(function () {

var options =
{
        lines: { show: true  },
	points: { show: true },
        xaxis: { "mode": "time", "timeformat": "%0d/%HZ", "tickSize": [1, "hour"] },
	yaxis: { tickSize: "10" },
        grid: { backgroundColor: { colors: ["#fff", "#ccc"] }},
	legend: { position: "nw", backgroundOpacity: 1, labelBoxBorderColor: "#000" }
   };

var torn =
{
        label: "Tornado",
        color: "#ff0000",
        data:
        [
<?php
$torn=file("torn.txt");
foreach ($torn as $line) { print "$line\n"; }
?>
],};

var wind =
{
        label: "Wind",
        color: "#0000ff",
        data:
        [
<?php
$wind=file("wind.txt");
foreach ($wind as $line) { print "$line\n"; }
?>
],};

var hail =
{
        label: "Hail",
        color: "#008000",
        data:
        [
<?php
$hail=file("hail.txt");
foreach ($hail as $line) { print "$line\n"; }
?>
],};

    $.plot($("#placeholder"), [torn,wind,hail], options);
});
</script>

 </body>
</html>

