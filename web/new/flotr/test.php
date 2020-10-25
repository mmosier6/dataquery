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
    <link rel="stylesheet" href="./style.css" type="text/css" />
    
    <script type="text/javascript" src="/new/flotr/lib/prototype-1.6.0.2.js"></script>

    <!--[if lte IE 8]>
      <script language="javascript" type="text/javascript" src="/new/flotr/lib/excanvas.min.js"></script>
      <script language="javascript" type="text/javascript" src="/new/flotr/lib/base64.js"></script>
    <![endif]-->

    <script type="text/javascript" src="/new/flotr/lib/canvas2image.js"></script>
    <script type="text/javascript" src="/new/flotr/lib/canvastext.js"></script>
    <script type="text/javascript" src="/new/flotr/flotr-0.2.0-alpha.js"></script>

<style>
#placeholder > div.legend > table {
  border: 1px solid #ccc;
  padding: 5px;
  background: #fff;
}
</style>

 </head>
    <body>

<div id="wrapper">
                        <div id="container" style="width:800px;height:550px;font-size:12px;"></div>
                </div>


<script type="text/javascript">
/**
* Wait till dom's finished loading.
*/
document.observe('dom:loaded', function(){

var options =
{
  xaxis: { 
	mode: 'time',
	labelsAngle: 45,
        noTicks: 12,
        title: 'Report Time',

        tickFormatter: function(x){
                var x = parseInt(x);
                var myDate = new Date(x);
                var day = myDate.getUTCDate();
                if(day < 10) { day = "0"+day;}
                var hour = myDate.getUTCHours();
                if(hour < 10) { hour = "0"+hour;}
                string = day + "/" + hour + "Z";
                result = string;
                return string;}

         },
         
   yaxis: { 
	noTicks: 10,
        title: 'Report Count',
        titleAngle: 90
	},
   legend: {
	labelBoxBorderColor: "#000",
	backgroundOpacity: 1,
	position: "nw"
	},
   HtmlText: false
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
]};

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

var f= Flotr.draw(
	$('container'),[torn,wind,hail],options);
});
</script>

 </body>
</html>

