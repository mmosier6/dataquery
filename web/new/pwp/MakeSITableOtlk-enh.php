<?php
error_reporting (E_ALL ^ E_NOTICE);
//**********************************************************************************************
//This script reads and parses .dat Convective Outlook files.  
//The categorical risk areas are defined in order to determine 
//which cities are affected.  The cities for each risk area    
//are then appending to a file created by Andy Dean which      
//provides area and total population statistics for each      
//risk level.  These data are subsequently used by a php     
//script which creates a societal impacts table on the
//SPC webpage.
//
//This script requires cities.tbl and usotln.dat
//
//CMM and JAH 10/2012
//
// Modified to handle prob outlook population and area tables
// Jay Liang, Wed May 28 21:50:08 UTC 2014
//
// closearea() modified to use "%4d" instead of "%3d" to handle the larger cities.tbl file
// Jay Liang, Mon Jun 16 19:14:06 UTC 2014
//
// Modified to handle the new 5-categorical Day 1-3 outlooks
// Jay Liang, Wed Oct 15 16:11:20 UTC 2014
//
// Fix day2 0600 0700 outlook prob SItable bug, Jay Liang, Sun Nov 16 14:22:06 UTC 2014
//**********************************************************************************************

$sqkm2sqmi = 0.386277865;
//$wwwftp = "/web/spcwebgen/bin/wwwftp.exp";

if ($argc != 3) {
  echo "Usage: $0 Day TimeStamp\n";
  exit();
} else {
  $inDay = trim($argv[1]);
  $timestamp = trim($argv[2]);
}

$yyyy = substr($timestamp, 0, 4);
$popDataDir = "/nfsops/ops_users/archive/OUTLOOK/${yyyy}/popdata";
$datDataDir = "/nfsops/ops_users/archive/OUTLOOK/${yyyy}/dat";

////////////////////////////////////////
//  Day 1 outlooks /////////////////////
////////////////////////////////////////

if (($inDay == 1) || ($inDay == 2) || ($inDay == 3)) {

  // Create a uniquely named file to store temp data
  $fileout = uniqid(rand(), true) . '.txt';
  $fh = fopen($fileout, 'w') or die ("can't open file");

  // day1 categorical file
  $filenam = `/usr/bin/find ${datDataDir} -maxdepth 1 -type f -name 'outlook_DAY${inDay}_*Z.dat.${timestamp}' | /usr/bin/tail -1`;
#  $filenam = "example/outlook_DAY1_191630Z.dat.20150219194417";
  $filenam0 = trim($filenam);
  $filenam1 = basename($filenam);
  $filename = trim($filenam1);

  print "Day ${inDay} categorical .dat file: $filename\n";

  $outlookDay = substr($filename,11,1);
  $outlookDate = substr($filename,25,8);
  if ($inDay == 2) {
    $dummy = substr($filename,33,1);
    $outlookTime = '0600';
    if ($dummy == 1) {
      $outlookTime = '1730';
    }
  } else {
    $outlookTime = substr($filename,15,4);
  }
  $outlookYear = substr($filename,25,4);

  print "outlookDay = ${outlookDay}, mm = ${mm}, dd = ${dd}, outlookDate = ${outlookDate}, outlookTime = ${outlookTime}, outlookYear = ${outlookYear}\n";

  // Read all of file into memory
  $lines = file($filenam0);

  $gen = 0;
  $mrgl = 0;
  $slgt = 0;
  $enh = 0;
  $modt = 0;
  $high = 0;

  // Loop through forecast areas and decode the .dat files.
  // Prior to calling closed area function, the script looks for various labels. 
  // Otherwise, it breaks the line into lat/lon values and stores
  // them in the "area" array, stored in "lat" and "lon" keys.
  // Once the script encounters a "$$,"  the if loop is restarted, but the for loop
  // continues.

  for ($i=3; $i<count($lines); $i++) {
    //print "i is:$lines[$i]\n";
    if (strpos($lines[$i], "GENERAL") !== false)  {
      // Skip
      $label = "GENERAL";
      $curprob = $label;
      $dum = ""; 
      $gen = 1;
    } elseif (strpos($lines[$i], "MARGINAL") !== false)  {
      // New area...decode prob value
      $label = "MRGL";
      $curprob = $label;
      $j=0;
      $area = ""; 
      $mrgl = 1;
    } elseif (strpos($lines[$i], "SLIGHT") !== false)  {
      // New area...decode prob value
      $label = "SLGT";
      $curprob = $label;
      $j=0;
      $area = ""; 
      $slgt = 1;
    } elseif (strpos($lines[$i], "ENHANCED") !== false)  {
      // New area...decode prob value
      $label = "ENH";
      $curprob = $label;
      $j=0;
      $area = ""; 
      $enh = 1;
    } elseif (strpos($lines[$i], "MODERATE") !== false)  {
      // New area...decode prob value
      $label = "MODT";
      $curprob = $label;
      $j=0;
      $area = ""; 
      $modt = 1;
    } elseif (strpos($lines[$i], "HIGH") !== false)  {
      // New area...decode prob value
      $label = "HIGH";
      $curprob = $label;
      $j=0;
      $area = ""; 
      $high = 1;
    } elseif (strpos($lines[$i], "LABEL:") !== false ) {
      // Skip label line, only provides lat/lon for label location
      $dum = "";
    } elseif (strpos($lines[$i], "...CONT") !== false ) {
      // Continuation line
      $j++;
      $area["lat"][$j] = "...CO";
      $dum = "";
    } else {
      $j++;
      list($latx, $lonb, $lonx, $dum, $dum, $dum) = explode(" ", $lines[$i]);
      if ($lonx >= 0) $lonx = $lonb;
      $area["lat"][$j] = $latx;
      $area["lon"][$j] = $lonx;
      if (strpos($lines[$i], "$$") !== false) {
        $j--;
        // send all the lat/lon data through the closedarea function, using
        // the lat/lon information stored in "area," the number of lat/lon
        // pairs, the label for the lat/lon data as inputs.
        $area = closearea($area, $j, $curprob, $fh);
        // the value of "area" is the result of the closedarea and fillgap
        // functions. 
      }
    }
  }

  fclose($fh);

  //Begin formatting of the HTML page
  $html = "ImpactTable.html";
  #$htmlArchive = "ac" . $outlookDay . "_" . $outlookDate . "_" . $outlookTime . "_SItable.html";
  $fhtml = fopen($html, 'w') or die ("can't open file");
  fwrite($fhtml,"<table cellspacing='1' cellpadding='1' border='0' width='100%'>\n");
  fwrite($fhtml,"<tr style='background-color: #0A2390; text-align: center;'>\n");
  fwrite($fhtml,"<td align='center' bgcolor='#0062ae' width='100px'><span class='impacts'><font color='#ffffff'>Day&nbsp;${inDay}&nbsp;Risk</font></span></td>\n");
  fwrite($fhtml,"<td align='center' bgcolor='#0062ae' width='100px'><span class='impacts'><font color='#ffffff'>Area&nbsp;(sq.&nbsp;mi.)</font></span></td>\n");
  fwrite($fhtml,"<td align='center' bgcolor='#0062ae' width='100px'><span class='impacts'><font color='#ffffff'>Area&nbsp;Pop.</font></span></td>\n");
  fwrite($fhtml,"<td align='center' bgcolor='#0062ae' width='515px'><span class='impacts'><font color='#ffffff'>Some&nbsp;Larger&nbsp;Population&nbsp;Centers&nbsp;in&nbsp;Risk&nbsp;Area</font></span></td></tr>\n");
  
  //Following code removes duplicate cities
  $text = file_get_contents($fileout);
  $file = explode("\n",$text);
  $result = array_unique($file);
  $fh = fopen($fileout, 'w') or die ("can't open file");
  foreach ($result as $line) {
    if (($line == "HIGH") || ($line == "MODERATE") || ($line == "ENHANCED") || ($line == "SLIGHT") || ($line == "MARGINAL")) {
      $label = $line;
    } else {
      fwrite($fh,"$label,$line\n");
    }
  }
  fclose($fh);

  //Read popdata into an array

  echo "Finding ${yyyy}${mm}${dd} pop file.\n";
  $popfil = `/usr/bin/find ${popDataDir} -maxdepth 1 -type f -name 'cat_day${inDay}_gem_*_${timestamp}*.pop' | /usr/bin/tail -1`;
 # $popfil = "example/cat_day1_gem_1630_20150219194417.pop";
  $popFile = trim($popfil);

  echo "Day ${inDay} population file: $popFile\n";

  $popData = file($popFile);
  $dataOut = array();

  array_shift($popData);
  $result = array_reverse($popData);

  foreach($result as $line) { 
    $tline = trim($line);
    $content = explode(",",$tline);
    array_push($dataOut,$content); 
  }

  //This section determines whether cities were defined for HIGH, MDT, or SLGT
  //risk areas.  If so, the top five cities (ranked by population) are listed. 
  $cities = file($fileout);
  sort($cities);

  $HighCities = array();
  $MdtCities = array();
  $EnhCities = array();
  $SlgtCities = array();
  $MrglCities = array();

  foreach($cities as $loc) {
    $tloc = trim($loc);
    $line = explode(",",$tloc);
    if ($line[1] != '') {
      if ($line[0] == "HIGH") {
        $in = $line[2] . "," . $line[3];
        array_push($HighCities,$in);
      }
      if ($line[0] == "MODERATE") {
        $in = $line[2] . "," . $line[3];
        array_push($MdtCities,$in);		
      }
      if ($line[0] == "ENHANCED") {
        $in = $line[2] . "," . $line[3];
        array_push($EnhCities,$in);
      }
      if ($line[0] == "SLIGHT") {
        $in = $line[2] . "," . $line[3];
        array_push($SlgtCities,$in);
      }
      if ($line[0] == "MARGINAL") {
        $in = $line[2] . "," . $line[3];
        array_push($MrglCities,$in);
      }
    }
  }

  // if (!empty($HighCities))
  if ($high > 0) {
    $total = count($HighCities);
    fwrite($fhtml,"<tr style='background-color: #0A2390; text-align: center;'>\n");
    fwrite($fhtml,"<td align='center' class='ac-high' width='100px'><span class='impacts'>HIGH</span></td>\n");
    $numArea = number_format(number_format($dataOut[0][1], 2, '.', '') * $sqkm2sqmi);
    fwrite($fhtml,"<td align='center' class='cities_high' width='100px'><span class='impacts'>$numArea</span></td>\n");
    $numPop = number_format($dataOut[0][2]);
    fwrite($fhtml,"<td align='center' class='cities_high' width='100px'><span class='impacts'>$numPop</span></td>\n");
    fwrite($fhtml,"<td align='center' class='cities_high' width='515px'><span class='impacts'>");

    if ($total == 0) {
       fwrite($fhtml,"No&nbsp;Major&nbsp;Population&nbsp;Center&nbsp;in&nbsp;Risk&nbsp;Area");
    } else if ($total > 5) {
      for ($c = 0; $c < 5; $c++) { 
        fwrite($fhtml,"$HighCities[$c]...");
      }
    } else { 
      for ($c = 0; $c < $total; $c++) {
        fwrite($fhtml,"$HighCities[$c]..."); 
      }
    }
    fwrite($fhtml,"</td></tr>\n");
  }		

  // if (!empty($MdtCities))
  if ($modt > 0) {
    $total = count($MdtCities);
    fwrite($fhtml,"<tr style='background-color: #0A2390; text-align: center;'>\n");
    fwrite($fhtml,"<td align='center' class='ac-mod' width='100px'><span class='impacts'>MODERATE</span></td>\n");
    $numArea = number_format(number_format($dataOut[1][1], 2, '.', '') * $sqkm2sqmi);
    fwrite($fhtml,"<td align='center' class='cities_mdt' width='100px'><span class='impacts'>$numArea</span></td>\n");
    $numPop = number_format($dataOut[1][2]);
    fwrite($fhtml,"<td align='center' class='cities_mdt' width='100px'><span class='impacts'>$numPop</span></td>\n");
    fwrite($fhtml,"<td align='center' class='cities_mdt' width='515px'><span class='impacts'>");

    if ($total == 0) {
      fwrite($fhtml,"No&nbsp;Major&nbsp;Population&nbsp;Center&nbsp;in&nbsp;Risk&nbsp;Area");
    } else if ($total > 5) {
      for ($c = 0; $c < 5; $c++) { 
        fwrite($fhtml,"$MdtCities[$c]...");
      }
    } else {
      for ($c = 0; $c < $total; $c++) {
        fwrite($fhtml,"$MdtCities[$c]..."); 
      }
    }
    fwrite($fhtml,"</td></tr>\n");       		
  }

  // if (!empty($EnhCities))
  if ($enh > 0) {
    $total = count($EnhCities);
    fwrite($fhtml,"<tr style='background-color: #0A2390; text-align: center;'>\n");
    fwrite($fhtml,"<td align='center' class='ac-enh' width='100px'><span class='impacts'>ENHANCED</span></td>\n");
    $numArea = number_format(number_format($dataOut[2][1], 2, '.', '') * $sqkm2sqmi);
    fwrite($fhtml,"<td align='center' class='cities_enh' width='100px'><span class='impacts'>$numArea</span></td>\n");
    $numPop = number_format($dataOut[2][2]);
    fwrite($fhtml,"<td align='center' class='cities_enh' width='100px'><span class='impacts'>$numPop</span></td>\n");
    fwrite($fhtml,"<td align='center' class='cities_enh' width='515px'><span class='impacts'>");

    if ($total == 0) {
       fwrite($fhtml,"No&nbsp;Major&nbsp;Population&nbsp;Center&nbsp;in&nbsp;Risk&nbsp;Area");
    } else if ($total > 5) {
      for ($c = 0; $c < 5; $c++) {
        fwrite($fhtml,"$EnhCities[$c]...");
      }
    } else {
      for ($c = 0; $c < $total; $c++) {
        fwrite($fhtml,"$EnhCities[$c]...");
      }
    }
    fwrite($fhtml,"</td></tr>\n");
  }

  // if (!empty($SlgtCities))
  if ($slgt > 0) {
    $total = count($SlgtCities);
    fwrite($fhtml,"<tr style='background-color: #0A2390; text-align: center;'>\n");
    fwrite($fhtml,"<td align='center' class='ac-slgt' width='100px'><span class='impacts'>SLIGHT</span></td>\n");
    $numArea = number_format(number_format($dataOut[3][1], 2, '.', '') * $sqkm2sqmi);
    fwrite($fhtml,"<td align='center' class='cities_slgt' width='100px'><span class='impacts'>$numArea</span></td>\n");
    $numPop = number_format($dataOut[3][2]);
    fwrite($fhtml,"<td align='center' class='cities_slgt' width='100px'><span class='impacts'>$numPop</span></td>\n");
    fwrite($fhtml,"<td align='center' class='cities_slgt' width='515px'><span class='impacts'>");

    if ($total == 0) {
       fwrite($fhtml,"No&nbsp;Major&nbsp;Population&nbsp;Center&nbsp;in&nbsp;Risk&nbsp;Area");
    } else if ($total > 5) {
      for ($c = 0; $c < 5; $c++) {
        fwrite($fhtml,"$SlgtCities[$c]...");
      }
    } else {
      for ($c = 0; $c < $total; $c++) {
        fwrite($fhtml,"$SlgtCities[$c]...");
      }
    }
    fwrite($fhtml,"</td></tr>\n");
  }

  // if (!empty($MrglCities))
  if ($mrgl > 0) {
    $total = count($MrglCities);
    fwrite($fhtml,"<tr style='background-color: #0A2390; text-align: center;'>\n");	
    fwrite($fhtml,"<td align='center' class='ac-mrgl' width='100px'><span class='impacts'>MARGINAL</span></td>\n");
    $numArea = number_format(number_format($dataOut[4][1], 2, '.', '') * $sqkm2sqmi);
    fwrite($fhtml,"<td align='center' class='cities_mrgl' width='100px'><span class='impacts'>$numArea</span></td>\n");
    $numPop = number_format($dataOut[4][2]);
    fwrite($fhtml,"<td align='center' class='cities_mrgl' width='100px'><span class='impacts'>$numPop</span></td>\n");
    fwrite($fhtml,"<td align='center' class='cities_mrgl' width='515px'><span class='impacts'>");

    if ($total == 0) {
       fwrite($fhtml,"No&nbsp;Major&nbsp;Population&nbsp;Center&nbsp;in&nbsp;Risk&nbsp;Area");
    } else if ($total > 5) {
      for ($c = 0; $c < 5; $c++) { 
        fwrite($fhtml,"$MrglCities[$c]...");
      }
    } else {
      for ($c = 0; $c < $total; $c++) {
        fwrite($fhtml,"$MrglCities[$c]..."); 
      }
    }
    fwrite($fhtml,"</td></tr>\n");
  }

  // if ((empty($HighCities)) && (empty($MdtCities)) && (empty($EnhCities)) && (empty($SlgtCities)) && (empty($MrglCities)))
  if (($mrgl == 0) && ($slgt == 0) && ($enh == 0) && ($modt == 0) && ($high == 0)) {
    fwrite($fhtml,"<tr style='background-color: #0A2390; text-align: center;'>\n");
    fwrite($fhtml,"<td align='center' colspan='4' class='ac-nosvr' width='100%'><span class='impacts'>No&nbsp;Risk&nbsp;Areas&nbsp;Forecast</span></td></tr>\n");
  }

  fwrite($fhtml,"</table>\n"); 
  fclose($fhtml);

//  system("$wwwftp $html public_html2/products/outlook/$html");

  //remove temporary file
  unlink($fileout);
  //unlink($html);

/////////////////////////////////////////////////////////////////////////////////////////////////
// end of Day 1 categorical outlook
// start of Day 1 prob outlooks
/////////////////////////////////////////////////////////////////////////////////////////////////

}

//------------------------------------------------------------------------
//Begin Functions----------------------------------------------------------
//-------------------------------------------------------------------------

//**********Begin Closearea Function**********//

function closearea($area, $numpts, $prob, $fh) {
  if ($prob == "HIGH") { 
    fwrite($fh, "HIGH\n");
  }
  if ($prob == "MODT") {
    fwrite($fh, "MODERATE\n");
  }
  if ($prob == "ENH") {
    fwrite($fh, "ENHANCED\n");
  }
  if ($prob == "SLGT") {
    fwrite($fh, "SLIGHT\n");
  }
  if ($prob == "MRGL") {
    fwrite($fh, "MARGINAL\n");
  }
  if ($prob == "D4") {
    fwrite($fh, "D4\n");
  }
  if ($prob == "D5") {
    fwrite($fh, "D5\n");
  }
  if ($prob == "D6") {
    fwrite($fh, "D6\n");
  }
  if ($prob == "D7") {
    fwrite($fh, "D7\n");
  }
  if ($prob == "D8") {
    fwrite($fh, "D8\n");
  }
  if ($prob == "2%") {
    fwrite($fh, "2%\n");
  }
  if ($prob == "5%") {
    fwrite($fh, "5%\n");
  }
  if ($prob == "10%") {
    fwrite($fh, "10%\n");
  }
  if ($prob == "15%") {
    fwrite($fh, "15%\n");
  }
  if ($prob == "30%") {
    fwrite($fh, "30%\n");
  }
  if ($prob == "45%") {
    fwrite($fh, "45%\n");
  }
  if ($prob == "60%") {
    fwrite($fh, "60%\n");
  }
  if ($prob == "SIG") {
    fwrite($fh, "SIG\n");
  }
  print "Processing $prob area, numpts = $numpts\n";
  // adding "prob" and "numpts" keys to "area" array.
  $area["prob"] = $prob;
  $area["numpts"] = $numpts;
  $area2 = $area;
 
  // Loop through the "area2" array, using the "numpts" key to count incrementally.
  // Search for CONTINUE sections
  $i2 = 1;
  $latlon=array();
  $finalout=array();

  for ($i=1;$i<=$area["numpts"];$i++) {
    // If a "lat" key is not numeric and starts with "...CO," the fillgap routine is called
    if ($area["lat"][$i] == "...CO") {		
      // Put the lat/lon points prior to and after the continuation line in the "lat" and "lon" keys			
      $lat1 = $area["lat"][$i-1];
      $lon1 = $area["lon"][$i-1];
      $lat2 = $area["lat"][$i+1];
      $lon2 = $area["lon"][$i+1];
      //need to close the area via fillgap
      $npts = fillgap($lat1, $lon1, $lat2, $lon2);
      for ($j = 0; $j < $npts["numpts"]; $j++) {
        $area2["lat"][$i2] = $npts["lat"][$j];
        $area2["lon"][$i2] = $npts["lon"][$j];
        $i2++;
      }
    } else { 
      // area closed, so "area2" lat/lon keys are populated with the 
      // "area" lat/lon key data 
      $area2["lat"][$i2] = $area["lat"][$i];
      $latclosed = $area["lat"][$i];
      $area2["lon"][$i2] = $area["lon"][$i];
      $lonclosed = $area["lon"][$i];
      //combining lat/lon info
      $dummy = $latclosed . "," . $lonclosed;
      //put lat/lon pairs into an array
      array_push($latlon, $dummy);
      $i2++;
    }
    $area2["numpts"] = $i2-1;
  }

  // Exit routine if area is not open-ended.
  if (($area["lat"][1] == $area["lat"][$numpts]) && ($area["lon"][1] == $area["lon"][$numpts])) {
    //print "   Closed area...no need to finish outline <br>\n";
    //Start parsing lat/lon prob/cat points for input into overlap	
    //parse lat info
    $lats = array();
    $lons = array();
    foreach ($latlon as $i) { 
      $lat = substr($i,0,5);
      chop($lat);
      array_push($lats,$lat);
      if (substr($i,7,1) == "1") {
        $lon = substr($i,6,7); chop($lon); array_push($lons,$lon);
      } else {
        $lon = substr($i,6,6); chop($lon); array_push($lons,$lon);
      }
    }
    //Next, read the contents of the city table into memory.
    $infile = "cities.tbl";
    $inlines = file($infile);	

    for ($i = 0; $i < count($inlines); $i++) {
      $line = trim($inlines[$i], "\n");
      list($city,$st,$ptlat,$ptlon) = explode(",", $line);
      //Call overlap routine
      $ok = overlap( $lats, $lons, $ptlat+.001, $ptlon+.001);
      //If overlap returns a value of 1, the location is in the area
      if ( $ok == "1" && $prob =="HIGH" || $ok == "1" && $prob =="MODT" || $ok == "1" && $prob =="ENH"
        || $ok == "1" && $prob =="SLGT" || $ok == "1" && $prob =="MRGL"
        || $ok == "1" && $prob =="D4" || $ok == "1" && $prob =="D5" || $ok == "1" && $prob =="D6"
        || $ok == "1" && $prob =="D7" || $ok == "1" || $ok == "1" && $prob =="D8"
        || $ok == "1" && $prob =="2%" || $ok == "1" && $prob =="5%" || $ok == "1" && $prob =="10%"
        || $ok == "1" && $prob =="15%" || $ok == "1" && $prob =="30%"
        || $ok == "1" && $prob =="45%" || $ok == "1" && $prob =="60%" || $ok == "1" && $prob =="SIG") {
        // print "city is: $city, $st\n";
        $input = $prob . $city . ", " . $st;
        $num = sprintf('%04d', $i);
        fwrite($fh, "$num,$city, $st\n");
        array_push($finalout,$input);
      }
    }
  } else {
    // close open-ended areas
    //print " ----------------- CLOSING AREA --------------------- <br>\n";
    $lat1 = $area["lat"][$numpts];
    $lon1 = $area["lon"][$numpts];
    $lat2 = $area["lat"][1];
    $lon2 = $area["lon"][1];

    $npts = fillgap($lat1, $lon1, $lat2, $lon2);
    for ($j=0;$j<$npts["numpts"];$j++) {
      //print "j is $j\n";
      //put the continuation line points to the area2 array lat/lon keys.
      $latclosing = $npts["lat"][$j];
      //print "latclose = $latclosing\n";
      $lonclosing = $npts["lon"][$j];
      //print "lonclose = $lonclosing\n";
      $dummy = sprintf("%.2f", $latclosing) . "," . sprintf("%.2f", $lonclosing);
      //print "dummy is $dummy\n";		
      array_push($latlon, $dummy);
      $i2++;
    }

    //Start parsing lat/lon prob/cat points for input into overlap	
    //parse lat info
    $lats = array();
    foreach ($latlon as $i) { 
      $lat = substr($i,0,5);
      chop($lat);
      array_push($lats,$lat);
    }
    
    //parse lon info
    $lons = array();
    foreach ($latlon as $z) {
      if (substr($z,7,1) == "1") {
        $lon = substr($z,6,7); chop($lon); array_push($lons,$lon);
      } else {
        $lon = substr($z,6,6); chop($lon); array_push($lons,$lon);
      }
    }

    //Next, read the contents of the city table into memory.
    $infile = "cities.tbl";
    $inlines = file($infile);	

    for ($i = 0; $i < count($inlines); $i++) {
      $line = trim($inlines[$i], "\n");
      list($city,$st,$ptlat,$ptlon) = explode(",", $line);
      //Call overlap routine
      $ok = overlap( $lats, $lons, $ptlat+.001, $ptlon+.001);
      //If overlap returns a value of 1, the location is in the area
      if ( $ok == "1" && $prob =="HIGH" || $ok == "1" && $prob =="MODT" || $ok == "1" && $prob =="ENH"
        || $ok == "1" && $prob =="SLGT" || $ok == "1" && $prob =="MRGL"
        || $ok == "1" && $prob =="D4" || $ok == "1" && $prob =="D5" || $ok == "1" && $prob =="D6"
        || $ok == "1" && $prob =="D7" || $ok == "1" && $prob =="D8"
        || $ok == "1" && $prob =="2%" || $ok == "1" && $prob =="5%" || $ok == "1" && $prob =="10%"
        || $ok == "1" && $prob =="15%" || $ok == "1" && $prob =="30%"
        || $ok == "1" && $prob =="45%" || $ok == "1" && $prob =="60%" || $ok == "1" && $prob =="SIG" ) {
        // print "city is: $city, $st, $ptlat, $ptlon\n"; 
        // print "city is: $city, $st\n";
        $input = $prob . $city . ", " . $st;
        $num = sprintf('%04d', $i);
        fwrite($fh, "$num,$city, $st\n");
        array_push($finalout,$input);
      }
    } 
  }
}

//**********Begin Fillgap Function**********//

function fillgap($lat1, $lon1, $lat2, $lon2) {
  $outln["numpts"] = 0;

  // Read US Outline file into memory
  if ($outln["numpts"] < 100) {
    // Read outline points
    $dat = file("usotln.dat");
    $j = 0;
    foreach ($dat as $d) {
      $outln["lat"][$j] = substr($d, 5, 4) / 100;
      $outln["lon"][$j] = substr($d, 10, 5) / -100;
      $j++;
    }
    $outln["numpts"] = $j;
  }

  // Loop through outline file, gathering points between endpoints
  $mind1 = 9999;
  $mind2 = 9999;
  $minp1 = 0;
  $minp2 = 0;
  for ($i = 0; $i < $outln["numpts"]; $i++) {
    $test = pow(($lat1 - $outln["lat"][$i]),2);
    $dist1 = sqrt(pow(($lat1 - $outln["lat"][$i]),2) + pow(($lon1 - $outln["lon"][$i]), 2));
    $dist2 = sqrt(pow(($lat2 - $outln["lat"][$i]),2) + pow(($lon2 - $outln["lon"][$i]), 2));
    //print "dist2 = $dist2\n";
    if ($dist1 < $mind1) {
      $mind1 = $dist1; $minp1 = $i;
    }
    if ($dist2 < $mind2) {
      $mind2 = $dist2; $minp2 = $i;
    }
  }

  $minp1 -=1;
  $minp2 -=1;
  $j=0;
  if ($minp2 < $minp1) $minp2 = $minp1;
  for ($i = $minp1; $i <= $minp2; $i++) {
    $j++;
    $npts["lat"][] = $outln["lat"][$i];
    $npts["lon"][] = $outln["lon"][$i];
  }
  $npts["numpts"] = $j;
  return $npts;
}

//**********Begin Overlap Function**********//

function overlap( $lats, $lons, $ptlat, $ptlon) {
/************************************************************************/
/* OVERLAP                                                              */
/* by John A. Hart, SPC, KCMO                                           */
/*                                                                      */
/* This routine will determine if the given point (latx, lonx) lies     */
/* within the shape described by the points in arrays (lat, lon).       */
/*                                                                      */
/* -  lat[0] = Number of lat/lon pairs describing shape.                */
/* -  There must be at least 3 points in array.                         */
/* -                no more than 99 points in array.                    */
/* -  Points must be arranged in "right-of-a-line" format.              */
/* -  Routine will return a "1" if point is within the shape, otrw "0". */
/*                                                                      */
/************************************************************************/

  // Check to make sure polygon has at least three points
  if (count($lats) < 3) return 0;

  // Do a rough check using max/min values.  No need to proceed
  // unless you pass this test.
  $north = max($lats);
  $south = min($lats);
  $west  = min($lons);
  $east  = max($lons);
  if ($ptlat < $south) return -1;
  if ($ptlat > $north) return -1;
  if ($ptlon < $west) return -1;
  if ($ptlon > $east) return -1;

  /* ----- SMOOTH CHECK ALGORITHM:  This routine employs a method of ----- */
  /* ----- checking for "in vs. out" that looks at line crossings.   ----- */
  /* ----- Envision a set of vertices drawn through the given point  ----- */
  /* ----- (latx, lonx).  Each of the 4 lines that originate from the----- */
  /* ----- point must be crossed an odd number of times for the point----- */
  /* ----- to be in the area.  Checks must also be made for points on----- */
  /* ----- lines or so forth.                                        ----- */
  $nok=0; $sok=0; $wok=0; $eok=0;
  for ($i = 1; $i < count($lats); $i++) {
    $j=$i-1;
    if ($j<=0) {
      $j = count($lats)-1;
    }
    # Check North/South vertices
    if (($lons[$j] <= $ptlon) && ($lons[$i] >= $ptlon)) {
      # Calculate distance from point to edge of polygon along vertex.  Sign indicates whether line passes north or south of point.
      $lz = $ptlat - ($lats[$i] - (($lats[$i] - $lats[$j]) * (($ptlon - $lons[$i]) / ($lons[$j] - $lons[$i]))));
      if ($lz == 0) {
        return 1;
      } # Point on line
      if ($lz < 0) {
        $nok++;
      } else {
        $sok++;
      }
    }
    if (($lons[$j] >= $ptlon) && ($lons[$i] <= $ptlon)) {
      # Calculate distance from point to edge of polygon along vertex.  Sign indicates whether line passes north or south of point.
      $lz = $ptlat - ($lats[$i] - (($lats[$i] - $lats[$j]) * (($ptlon - $lons[$i]) / ($lons[$j] - $lons[$i]))));
      if ($lz == 0) {
        return 1;
      } # Point on line
      if ($lz > 0) {
        $sok++;
      } else {
        $nok++;
      }
    }

    # Check East/West vertices
    if (($lats[$j] <= $ptlat) && ($lats[$i] >= $ptlat)) {
      # Calculate distance from point to edge of polygon along vertex.  Sign indicates whether line passes north or south of point.
      $lz = $ptlon - ($lons[$i] - (($lons[$i] - $lons[$j]) * (($ptlat - $lats[$i]) / ($lats[$j] - $lats[$i]))));
      if ($lz == 0) {
        return 1;
      } # Point on line
      if ($lz < 0) {
        $eok++;
      } else {
        $wok++;
      }
    }
    if (($lats[$j] >= $ptlat) && ($lats[$i] <= $ptlat)) {
      # Calculate distance from point to edge of polygon along vertex.  Sign indicates whether line passes north or south of point.
      $lz = $ptlon - ($lons[$i] - (($lons[$i] - $lons[$j]) * (($ptlat - $lats[$i]) / ($lats[$j] - $lats[$i]))));
      if ($lz == 0) {
        return 1;
      } # Point on line
      if ($lz > 0) {
        $wok++;
      } else {
        $eok++;
      }
    }
  }

  if ((($nok % 2) == 1)  && (($sok % 2) ==1) && (($wok % 2) == 1) && (($eok % 2) == 1)) {
    return 1;
  }
  return 0;
}	

?>
