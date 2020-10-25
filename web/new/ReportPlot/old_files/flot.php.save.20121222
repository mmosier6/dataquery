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

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="/new/js/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="/new/js/jquery.flot.time.min.js"></script>

<style>
#placeholder > div.legend > table {
  border: 1px solid #ccc;
  padding: 1px;
  background: #fff;
}

</style>

<div id="reportWrapper" style="width:930px;height:730px; padding:0px;">
<table border="0px" width="930px" cellpadding="0px" cellspacing="0px">
<tr>
<td colspan="2" align="middle" style="font-family:arial;font-size:1.2em;font-weight:bold;"><?php echo $label ?></td></tr>
<td width="30px" height="700px" valign="middle"><img src="../images/repCount_sm.png"></td>
<td> <div id="placeholder" style="width:900px;height:700px; float: left;"></div></td>
</tr>
<tr><td colspan="2" align="middle" style="font-family:arial;font-size:1.0em;font-weight:bold;">report time</td></tr>
</table>
</div>



<script type="text/javascript">
var $j = jQuery.noConflict();

$j(function () {

var $j = jQuery.noConflict();

var options =
{
        lines: { show: true  },
	points: { show: true, radius: .5 },
        xaxis: { "mode": "time", "timeformat": "%0d/%HZ" },
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
$torn=file("./torn.txt");
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
$wind=file("./wind.txt");
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
$hail=file("./hail.txt");
foreach ($hail as $line) { print "$line\n"; }
?>
],};

    $j.plot($j("#placeholder"), [torn,wind,hail], options);
});
</script>


