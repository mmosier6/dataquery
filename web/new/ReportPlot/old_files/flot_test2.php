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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

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
        <?php include("/local/spcwebsite/new/MainMenu/topMenu.html"); ?>

         <div class="full_width" > <!-- Page Content Below -->
   

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




<!-- ##### FOOTER ACROSS BOTTOM OF PAGE ##### -->
   <div class="footer_container">
      <div id="footer">
        <?php include("/local/spcwebsite/new/footer/footer1.html"); ?>

        Page last modified:
        <!--#config timefmt="%B %d, %Y"-->
        <!--#config errmsg=""-->
        <?php include("/local/spcwebsite/new/footer/footer2.html"); ?>

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



