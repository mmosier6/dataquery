<?php
//evaluate 'parm' parameter
$parm = $_GET['parm'];

if ( $parm == anySvr ) {
  $absPath = $_SERVER['DOCUMENT_ROOT']."/misc/climographics/any_severe";
  $relPath = "../../misc/climographics/any_severe";
}
elseif ( $parm == allTorn ) {
  $absPath = $_SERVER['DOCUMENT_ROOT']."/misc/climographics/all_torn";
  $relPath = "../../misc/climographics/all_torn";
}
elseif ( $parm == sigTorn  ) {
  $absPath = $_SERVER['DOCUMENT_ROOT']."/misc/climographics/sig_torn";
  $relPath = "../../misc/climographics/sig_torn";
}
elseif ( $parm == allWind  ) {
  $absPath = $_SERVER['DOCUMENT_ROOT']."/misc/climographics/all_wind";
  $relPath = "../../misc/climographics/all_wind";
}
elseif ( $parm == sigWind  ) {
  $absPath = $_SERVER['DOCUMENT_ROOT']."/misc/climographics/sig_wind";
  $relPath = "../../misc/climographics/sig_wind";
}
elseif ( $parm == allHail ) {
  $absPath = $_SERVER['DOCUMENT_ROOT']."/misc/climographics/all_hail";
  $relPath = "../../misc/climographics/all_hail";
}
elseif ( $parm == sigHail ) {
  $absPath = $_SERVER['DOCUMENT_ROOT']."/misc/climographics/sig_hail";
  $relPath = "../../misc/climographics/sig_hail";
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
  if ($i++ % 7 == 0) {
    array_push($weeks,$line);
    $j++;
  }
}

$wk = `date -u +%W`;

$diff = 53 - $wk;

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
<td width="100%"><a href="key_help.html" onClick="window.open('key_help.html','Svr Wx Climo Key Commands','location=0,width=340,height=220,left=0,top=200'); return false" target="_blank">*Click here to see keyboard commands</a></td>
</tr></table>
<table width="700px" id="climoTbl">
<tr>
<td width="100px" id="climoTab"><a href="/new/SVRclimo/climo.php?parm=anySvr">Any severe</a></td>
<td width="100px" id="climoTab"><a href="/new/SVRclimo/climo.php?parm=allTorn">All Tornado</a></td>
<td width="100px" id="climoTab"><a href="/new/SVRclimo/climo.php?parm=sigTorn">Sig Tornado</a></td>
<td width="100px" id="climoTab"><a href="/new/SVRclimo/climo.php?parm=allWind">All Wind</a></td>
<td width="100px" id="climoTab"><a href="/new/SVRclimo/climo.php?parm=sigWind">Sig Wind</a></td>
<td width="100px" id="climoTab"><a href="/new/SVRclimo/climo.php?parm=allHail">All Hail</a></td>
<td width="100px" id="climoTab"><a href="/new/SVRclimo/climo.php?parm=sigHail">Sig Hail</a></td>
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
echo "var imax = 53;\n";
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
<div id="ClimoTxt"><font color="#ff0000"><b>*</b></font>These probability values were estimated from a 30-year period of severe weather reports from 1982-2011.  The procedure to create the maps is as follows:<br><br>
&nbsp;&nbsp;1.  Reports for each day are put onto a grid 80 km x 80 km.<br>
&nbsp;&nbsp;2.  If one or more reports occur in a grid box, that box is assigned the value "1" for the day.  If no reports occur, it's a zero.<br>
&nbsp;&nbsp;3.  The raw frequency for each day at each grid location is found for the period (number of "1" values divided by number of years) to get a raw annual cycle.<br>
&nbsp;&nbsp;4.  The raw annual cycle at each point is smoothed in time, using a Gaussian filter with a standard deviation of 15 days.<br>
&nbsp;&nbsp;5.  The smoothed time series are then smoothed in space with a 2-D Gaussian filter (SD = 120 km in each direction).<br><br>
</div>

         <div> <!-- End Page Content -->
      </div> <!-- SPCMain2 -->
   </div> <!-- SPCMain --> 

   <!-- ##### FOOTER ACROSS BOTTOM OF PAGE ##### -->
   <div class="footer_container">
      <div id="footer">
        <?php include($_SERVER['DOCUMENT_ROOT']."/new/footer/footer1.html"); ?>

        <?php
         $filename = $_SERVER['DOCUMENT_ROOT'].'/new/SVRclimo/climo.php';
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
