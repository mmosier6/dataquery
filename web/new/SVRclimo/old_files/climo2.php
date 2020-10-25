<?php

//evaluate 'parm' parameter
$parm = $_GET['parm'];

if ( $parm == 0 ) {
	$absPath = "/web/spcweb/public_html2/misc/climographics/all_torn";
	$relPath = "../../misc/climographics/all_torn";
}
elseif ( $parm == 1 ) {
	$absPath = "/web/spcweb/public_html2/misc/climographics/all_wind";
        $relPath = "../../misc/climographics/all_wind";
}
elseif ( $parm == 2 ) {
        $absPath = "/web/spcweb/public_html2/misc/climographics/all_hail";
        $relPath = "../../misc/climographics/all_hail";
} 	

$days = array();
if ($handle = opendir($absPath)) {
        while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
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


<table width="945px">
<tr><td>

<form name="form" action="">
<input type="hidden" id="fh" name="fh" value="0">

     <table border="0" cellspacing="0" cellpadding="0" width="945px" bgcolor="#D5D5D5" style="border-bottom:1px solid #DDDDDD">
        <tr>
	  <td style="padding:0 2px 0 2px;text-align:center;">
          <input value="Start" onclick="start_play();" name="button3" type="button" class="mapbutton" style="float:left" />
          <input value="Stop" onclick="stop_play();" name="button" type="button" class="mapbutton" style="float:right" />
          </td>
          <td bgcolor="" style="border-left:1px solid #BBBBBB;text-align:center;"><font style="font-size:10px;"><b>FRAME</b></font></td>
	  <td>
          <input value="" name="frame" size="4" type="text" class="maptext" />
	  </td>
          <td bgcolor="" style="border-left:1px solid #BBBBBB;text-align:center;"><font style="font-size:10px;"><b>SPEED</b></font></td>
	  <td> 
          <input value="&lt;&lt;" onclick="delay=delay*inc; show_delay();" class="mapbutton" name="button2" type="button" />
          <input value="&gt;&gt;" onclick="delay=delay/inc; show_delay();" class="mapbutton" name="button2" type="button" />
          <input value="" name="dly" size="1" type="text" class="maptext" />img/sec
	  </td>
          <td bgcolor="" style="border-left:1px solid #BBBBBB;text-align:center;"><font style="font-size:10px;"><b>DIR</b></font></td>
	  <td style="text-align:center;">
          <input value="REV" onclick="reverse();start_play();" name="rev" type="button" class="mapbutton" />
          <input value="FWD" onclick="forward();start_play();" name="fwd" type="button" class="mapbutton" />
          <label><input name="rock" type="checkbox" />Rock</label>
	  </td>
          <td bgcolor="" style="border-left:1px solid #BBBBBB;text-align:center;"><font style="font-size:10px;"><b>DWELL</b></font></td>
          <td>
          <label><input type="checkbox" name="dwell" onclick="show_dwell();" checked="checked" />Dwell</label> 
          <input type="button" value=" - " onclick="decDwell(); show_dwell();" class="mapbutton" />
          <input type="button" value=" + " onclick="incDwell(); show_dwell();" class="mapbutton" />
          <input type="text" size="1" name="dwl" class="maptext" />sec
          </td>
          <td bgcolor="" style="border-left:1px solid #BBBBBB;text-align:center;"><font style="font-size:10px;"><b>STEP</b></font></td>
          <td style="text-align:center">
          <input value=" &lt; " onclick="backstep();" name="button2" type="button" class="mapbutton" />
          <input value=" &gt; " onclick="forwardstep();" name="button2" type="button" class="mapbutton" />
          </td>
          <td><label><input type="checkbox" checked="checked" name="nostep"/>On/Off</label></td>
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

</td></tr></table>

