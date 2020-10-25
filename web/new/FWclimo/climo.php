<?php
//evaluate 'parm' parameter
$dummy = filter_var($_GET['parm'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES|FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH| FILTER_FLAG_STRIP_BACKTICK|FILTER_FLAG_ENCODE_LOW|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_AMP);

switch ($dummy) {
  case "300ac":
    $parm = $dummy;
    $absPath = $_SERVER['DOCUMENT_ROOT']."/misc/fire_climo/Figs_Climo300ac";
    $relPath = "../../misc/fire_climo/Figs_Climo300ac";
    break;
  case "1000ac":
    $parm = $dummy;
    $absPath = $_SERVER['DOCUMENT_ROOT']."/misc/fire_climo/Figs_Climo1000ac";
    $relPath = "../../misc/fire_climo/Figs_Climo1000ac";
    break;
  case "5000ac":
    $parm = $dummy;
    $absPath = $_SERVER['DOCUMENT_ROOT']."/misc/fire_climo/Figs_Climo5000ac";
    $relPath = "../../misc/fire_climo/Figs_Climo5000ac";
    break;
  default:
    $parm = "100ac";
    $absPath = $_SERVER['DOCUMENT_ROOT']."/misc/fire_climo/Figs_Climo100ac";
    $relPath = "../../misc/fire_climo/Figs_Climo100ac";
    break;
}
        
$days = array();
if ($handle = opendir($absPath)) {
  while (false !== ($entry = readdir($handle))) {
    $pattern = '/^sm/';
    $check = preg_match($pattern,$entry);
    //print "$entry,$check\n";
    if ($entry != "." && $entry != ".." && $check == 0) {
      //echo "$entry\n";
      array_push($days, $entry);
    }
  }
  closedir($handle);
}

sort($days);
 
$weeks = array();

$i=0;
$j=0; 
foreach($days as $line) {
  if ($i++ % 3 == 0) {
    array_push($weeks,$line);
    $j++;
  }
}

$wk = `date -u +%j`;

$diff = 122 - $wk/3;

$pre_wk = array_slice($weeks,0,$wk); 
$post_wk = array_slice($weeks,-$diff);

foreach($pre_wk as $line) {
  array_push($post_wk,$line);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "//www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="//www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="DC.title" content="NOAA/NWS Storm Prediction Center" />
    <meta name="DC.language" scheme="DCTERMS.RFC1766" content="EN-US" />
    <meta name="language" content="EN-US" />
    <title>NOAA/NWS Storm Prediction Center</title>
    
    <!-- Sitewide css and scripts -->
    <link rel="stylesheet" href="/new/css/SPCmain.css" />

    <!--Conflict between prototype.js and jquery, so using jQuery.noConflict() -->
    <script src="//ajax.googleapis.com/ajax/libs/prototype/1.7.1.0/prototype.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="/new/js/SPCindex.js"></script>
    <script type="text/javascript" src="/misc/usno_gmttime.js"></script>
    <script type="text/javascript" src="/misc/lastMod.js"></script>
    <script type="text/javascript" src="/new/js/ProdTab.js"></script>
    <script type="text/javascript" src="/new/js/jquery.hoverIntent.minified.js"></script>
    <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>

    <script src="/new/js/climo_looper.js" type="text/javascript"></script>
    <script src="/new/js/keycommands.js" type="text/javascript"></script>

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
<table width='250px' id='climoHelp'>
<tr>
<td width="100%"><a href="key_help.html" onClick="window.open('key_help.html','Wildfire Wx Climo Key Commands','location=0,width=340,height=220,left=0,top=200'); return false" target="_blank">*Click here to see keyboard commands</a></td>
</tr></table>
<table width="700px" id="climoTbl">
  <tr>
    <td width="100px" id="climoTab"><a href="/new/FWclimo/climo.php?parm=100ac">100 Acres</a></td>
    <td width="100px" id="climoTab"><a href="/new/FWclimo/climo.php?parm=300ac">300 Acres</a></td>
    <td width="100px" id="climoTab"><a href="/new/FWclimo/climo.php?parm=1000ac">1000 Acres</a></td>
    <td width="100px" id="climoTab"><a href="/new/FWclimo/climo.php?parm=5000ac">5000 Acres</a></td>
  </tr>
</table>

<form name="form" action="">
<input type="hidden" id="fh" name="fh" value="0">

     <table cellspacing="0" cellpadding="0" width="945px" height="20px" id="formTbl">
        <tr>
          <td id="formCell">
          <input value="Start" onclick="start_play();" name="button3" type="button" class="mapbutton" style="float:left" />
          <input value="Stop" onclick="stop_play();" name="button" type="button" class="mapbutton" style="float:right" />
          </td>
          <td id="formCell">FRAME</td>
          <td>
          <input value="" name="frame" size="4" type="text" class="maptext" />
          </td>
          <td id="formCell">SPEED</td>
          <td id="formCell">
          <input value="&lt;&lt;" onclick="delay=delay*inc; show_delay();" class="mapbutton" name="button2" type="button" />
          <input value="&gt;&gt;" onclick="delay=delay/inc; show_delay();" class="mapbutton" name="button2" type="button" />
          <input value="" name="dly" size="1" type="text" class="maptext" />img/sec
          </td>
          <td id="formCell">DIR</td>
          <td id="formCell">
          <input value="REV" onclick="reverse();start_play();" name="rev" type="button" class="mapbutton" />
          <input value="FWD" onclick="forward();start_play();" name="fwd" type="button" class="mapbutton" />
          <label><input name="rock" type="checkbox" />Rock</label>
          </td>
          <td id="formCell">DWELL</td>
          <td id="formCell">
          <label id="formLbl"><input type="checkbox" name="dwell" onclick="show_dwell();" checked="checked" />Dwell</label>
          <input type="button" value=" - " onclick="decDwell(); show_dwell();" class="mapbutton" />
          <input type="button" value=" + " onclick="incDwell(); show_dwell();" class="mapbutton" />
          <input type="text" size="1" name="dwl" class="maptext" />sec
          </td>
          <td id="formCell">STEP</td>
          <td id="formCell">
          <input value=" &lt; " onclick="backstep();" name="button2" type="button" class="mapbutton" />
          <input value=" &gt; " onclick="forwardstep();" name="button2" type="button" class="mapbutton" />
          </td>
          <td id="formCell"><label><input type="checkbox" checked="checked" name="nostep"/>On/Off</label></td>
        </tr>
    </table>

<table width="935px" height="663px"><tr><td background="#000">
<div class="imgwrapper">
<img src="<?php print "${relPath}/${post_wk[0]}";?>" name="animation" width="935" height="663" />
</div>
</td></tr></table>

</form>
<?php


echo "<script type='text/javascript'>\n";
echo "<!--\n";
echo "var imax = 122;\n";
echo "window.pauseOnStart = true;\n";
echo "window.pauseWhere = 0;\n";
echo "window.temp_list = new Array(imax);\n";

$i=0;
foreach($post_wk as $img) {
  echo "window.temp_list[$i]= '${relPath}/${img}';\n";
  $i++;
}
echo "initialize_looper();\n";

echo "// -->\n";

echo "</script>\n";

?>
<div id="ClimoTxt"><font color="#ff0000"><b>*</b></font>&nbsp;The procedure to create the maps is as follows:<br>
  <li>New reported wildfires for each day are put onto a grid 80 km x 80 km using the discovery date.</li>
  <li>If one or more wildfires occur in a grid box three days before through three days after the valid date, that box is assigned the value "1" for the day. If no wildfires occur, it's a zero.</li>
  <li>The raw frequency for each day at each grid location is found for the period (number of "1" values divided by number of years) to get a raw annual cycle.</li>
  <li>The smoothed time series are then smoothed in space with a 2-D Gaussian filter (40 km in each direction).</li>
  <li>For questions or feedback please contact <a href="mailto:nick.nauslar@noaa.gov" target="_blank">nick.nauslar@noaa.gov</a>.</li>
</div>

         <div> <!-- End Page Content -->
      </div> <!-- SPCMain2 -->
   </div> <!-- SPCMain --> 

   <!-- ##### FOOTER ACROSS BOTTOM OF PAGE ##### -->
   <div class="footer_container">
      <div id="footer">
        <?php include($_SERVER['DOCUMENT_ROOT']."/new/footer/footer1.html"); ?>

        <?php
         $filename = $_SERVER['DOCUMENT_ROOT'].'/new/FWclimo/climo.php';
         if (file_exists($filename)) {
          echo "Page last modified: " . date ("F d Y H:i T", filemtime($filename));
         }
        ?>

        <?php include($_SERVER['DOCUMENT_ROOT']."/new/footer/footer2.html"); ?>
      
      </div>
   </div>

<div class="clear"> </div> <!-- Sets background to white -->

 </div>
<script type="text/javascript">
   show_tab("TABoverview");
   $("#query-field").blur();
</script>
</body>
</html>
