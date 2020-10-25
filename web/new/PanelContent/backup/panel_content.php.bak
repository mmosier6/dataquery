<?php

//first open valid products file to determine operational mode of mainpage

$prodFile = file("/local/spcwebsite/new/SPCProducts/validProducts.txt");

///Hazard Levels///
//0 = No Thunder //
//1 = No Severe  //
//2 = See Text   //
//3 = Slight     //
//4 = Moderate   //
//5 = High       //
///////////////////

foreach($prodFile as $line) {
        $tline = trim($line);
        $data = explode("|",$tline);
        $prodType = $data[0];
        $otlkCat = $data[3];
        if ($prodType == "OTLK" ) {
                if ($otlkCat == "High") { $hazard = 5; }
                elseif ($otlkCat == "Moderate") { $hazard = 4; }
                elseif ($otlkCat == "Slight") { $hazard = 3; }
                elseif ($otlkCat == "See Text") { $hazard = 2; }
                elseif ($otlkCat == "No Severe") { $hazard = 1; }
                elseif ($otlkCat == "No Thunder") { $hazard = 0; }
        }
        $strCheck = substr($prodType,0,2);
        if ($strCheck == "WW") {
                $ww = substr($prodType,0,2);
                $wwNum = substr($prodType,2,4);
        if ($wwNum != "none") { $validWW = 1; }
        else { $validWW = 0; }
        }
}

if (($hazard > "3") || ($validWW == 1)) {
?>

	<!--Active Weather 6-panel-->
<div id="HighlightContainer">
    <table>
     <tr>
      <td valign="top" width="33%" id="TopicContainer">
        <table summary="layout" border="0">
          <tr><td id="HighlightTitle">SPC Mesoanalysis</td></tr>
          <tr><td align="center"><a href="/exper/mesoanalysis/" target="_blank"><img src="./images/mesoanalysis_sm.png" /></a></td></tr>
        </table>
      </td>
        <td valign="top" width="33%" id="TopicContainer">
	<table summary="layout" border="0">
          <tr><td id="HighlightTitle">Daily Storm Report Chart</td></tr>
          <tr><td align="center"><a href="/wcm/ptorngraph-big.png" target="_blank"><img src="./images/rptTrend_sm.png" /></a></td></tr>
        </table>
      </td>
        <td valign="top" width="33%" id="TopicContainer"> 
	<table summary="layout" border="0">
          <tr><td align="center"><a href="http://www.nws.noaa.gov/cgi-bin/nwsexit.pl?url=http://www.youtube.com/watch?v=x3V3HZBs1Y4" target="YouTube Clip"><img src="./images/WatchvWarn.png"/></a></td></tr>
          <tr><td align="left">Find out the difference between a severe weather <b>Watch</b> and <b>Warning</b>.</td></tr>
         </table>
        </td></tr>
        <tr>
        <td valign="top" width="33%" id="TopicContainer">
	<table summary="layout" border="0">
          <tr><td id="HighlightTitle">SPC SSEO Guidance</td></tr>
          <tr><td align="center"><a href="/exper/sseo/" target="_blank"><img src="./images/sseo_sm.png" /></a></td></tr>
        </table>
      </td>
        <td valign="top" width="33%" id="TopicContainer">
	<table summary="layout" border="0">
          <tr><td id="HighlightTitle">Current Severe Weather Climatology</td></tr>
          <tr><td align="center"><a href="/wcm/2011-wbc-anoms.png" target="_blank"><img src="./images/svr_climo_sm.png" /></a></td></tr>
          <tr><td align="left">Current tornado, damaging wind, and hail climatology.  See <a href="/wcm/#torgraph"> more...</a></td></tr>
        </table>
        </td>
	<td valign="top" width="33%" id="TopicContainer">
         <table summary="layout" border="0">
          <tr><td align="center"><a href="/faq/tornado/#Safety"><img src="./images/TorSafety2.png"></a></td></tr>
          <tr><td align="left">Tornado Safety <a href="/faq/tornado/#Safety"> Tips...</a>.</td></tr>
         </table>
        </td>
       </tr>
     </table>
   </td>
  </tr>
</table>
</div><!--end HighlightContainer-->
<!--End 6-panel-->      
<?php
} else {
?>
 
<!--Quiet Weather 9-panel-->
<div id="HighlightContainer">
    <table>
     <tr>
      <td valign="top" width="33%" id="TopicContainer">
        <table summary="layout" border="0">
          <tr><td id="HighlightTitle">Severe Weather Climatology (1982-2011)</td></tr>
          <tr><td align="center">
          <?php include("./SVRclimo/climoSlides.php"); ?>
	  </td></tr>
	  <tr><td align="left"><br>More severe weather climatology data <a href="./SVRclimo/climo.php?parm=anySvr"> here</a>.</td></tr>
        </table>
      </td>

<?php 

//check the filesize of torn.tx, hail.txt, wind.txt in flot to determine if the report trends plot should be shown
// If not, display the situational awareness link

$tornSize = filesize("./flot/torn.txt");
$windSize = filesize("./flot/wind.txt");
$hailSize = filesize("./flot/hail.txt");

if (($tornSize < 25) && ($windSize < 25) && ($hailSize < 25)) {

	print "<td valign='top' width='33%' id='TopicContainer'>\n";
	print "<table summary='layout' border='0'>\n";
	print "<tr><td id='HighlightTitle'>Situational Awareness Monitor</td></tr>\n";
        print "<tr><td align='center'><a href='http://www.spc.noaa.gov/exper/situation/?lat=32.00&lon=-102.00'><img src='/new/images/SituAware.png'></a></td></tr>\n";
        print "<tr><td align='left'><br>SPC Situational Awareness <a href='http://www.spc.noaa.gov/exper/situation/?lat=32.00&lon=-102.00'>monitor</a>.</td></tr>\n";
        print "</table></td>\n";

} else {
	print "<td valign='top' width='33%' id='TopicContainer'>\n";
	print "<table summary='layout' border='0'>\n";
	print "<tr><td id='HighlightTitle'>Today's Storm Report Trend</td></tr>\n";
        print "<tr><td align='center'>\n";
        include("./flot/flot_sm.php"); 
        print "</td></tr>\n";
        print "<tr><td align='left'><br><br>Full-size plot <a href='./flot/flot.php'>here</a>.</td></tr>\n";
        print "</table></td>\n";
}
?>

	<td valign='top' width='33%' id='TopicContainer'>
         <table summary="layout" border="0" background="#eee">
	  <tr><td id="HighlightTitle">Watch vs. Warning</td></tr>
          <tr><td align="center"><a href="http://www.nws.noaa.gov/cgi-bin/nwsexit.pl?url=http://www.youtube.com/watch?v=x3V3HZBs1Y4" target="YouTube Clip"><img src="./images/WatchWarn.png"/></a></td></tr>
          <tr><td align="left">Find out the difference between a severe weather <b>Watch</b> and <b>Warning</b>.</td></tr>
         </table>
        </td></tr>
        <tr>
        <td valign="top" width="33%" id="TopicContainer">
	<table summary="layout" border="0">
          <tr><td id="HighlightTitle">2012 Tornado Statistics</td></tr>
          <tr><td align="center"><a href="/wcm/ptorngraph-big.png" target="_blank"><img src="./images/TorTrend2.png" /></a></td></tr>
          <tr><td align="left">Daily count and annual running trend.  See <a href="/wcm/#torgraph"> more...</a></td></tr>
        </table>
      </td>
        <td valign="top" width="33%" id="TopicContainer">
	<table summary="layout" border="0">
          <tr><td id="HighlightTitle">2012 SPC Watch Summaries</td></tr>
          <tr><td align="center"><a href="/wcm/2011-wbc-anoms.png" target="_blank"><img src="./images/AnnualWatch.png" /></a></td></tr>
          <tr><td align="left">Tornado and severe thunderstorm frequency maps.  See <a href="/wcm/#watchfreq">more...</a></td></tr>
        </table>
        </td>
	<td valign="top" width="33%" id="TopicContainer">
         <table summary="layout" border="0">
          <tr><td align="center"><a href="/faq/tornado/#Safety"><img src="./images/TornSafety.png"></a></td></tr>
          <tr><td align="left">Tornado Safety <a href="/faq/tornado/#Safety"> Tips...</a>.</td></tr>
         </table>
        </td>
       </tr>
       <tr>
        <td colspan="2" valign="top" width="66%" id="TopicContainer">
	<div id="HighlightTitle">Latest SPC Publications</div>
        <?php include("/local/spcwebsite/new/SPCResearch/research.html"); ?>
        </td>
        <td valign="top" width="33%" id="TopicContainer">  
	<div id="HighlightTitle">Latest SPC Case Archive</div>
        <?php include("/local/spcwebsite/new/SPCCaseArchive/casestudy.html"); ?>
        </td>
       </tr>

     </table>
   </td>
  </tr>
</table>
</div><!--end HighlightContainer-->
<!--End 9-panel-->
<?php
}

?>
