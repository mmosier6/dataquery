<?php
/////////////////////////////////////////////////////////////////////////////////////////
// This script is called within the "onClick: function(argsObj)" function which is     //
// defined in index.php.  The script reads "validProducts.txt" which is located in the //
// local directory.  This text file is created by productList.pl which is located in   //
// ~hart/web/webProductList.  The output of the script is displayed in the tabbed      //
// product menu on the main page.                                                      // 
// 11/2012 CMM                                                                         //
/////////////////////////////////////////////////////////////////////////////////////////

//evaluate 'content' parameter
$content = $_GET['content'];

if ($content == "0") { showAllProd(); }
if ($content == "1") { showAllWW(); }
if ($content == "2") { showAllMD(); }
if ($content == "3") { showAllOTLK(); }
if ($content == "4") { showAllFIRE(); }

/////////////////////
// Functions Below //
////////////////////

// All Products
function showAllProd() {
  
  //set time var
  $curEtime = `/bin/date -u +%s`;
  $curEpochTime = trim($curEtime);
  
  #first, read validProducts.txt into memory.
  $validProd = file("./validProducts.txt");
  
  print "<div id='allProd'>\n";
  print "<div class='SPCProducts'>\n";
  
  foreach($validProd as $line) {
    $tline = trim($line);
    $data = explode("|",$tline);
    $prodType = $data[0];
    $prodTime = $data[1];
    $prodTimeYYYY = substr($prodTime,0,4);
    $prodTimeMM = substr($prodTime,4,2);
    $prodTimeDD = substr($prodTime,6,2);
    $prodTimeHHMM = substr($prodTime,8,4);
    $prodEpochTime  = $data[2];
    $timeDiff = $curEpochTime - $prodEpochTime;
    $hhDiff = gmdate("H", $timeDiff);
    $mmDiff = gmdate("i", $timeDiff);
    if ($mmDiff < 10) {
      $mmDiff %= 10;
    }
  
    //determine hazard level of the outlooks
    if (($prodType == "OTLK" ) || ($prodType == "OTLK2" ) || ($prodType == "OTLK3" ) || ($prodType == "OTLK4" )) { 
      $otlkHaz = $data[3];
      $otlkCat = trim($otlkHaz);
      if ($otlkCat == "High") { $otlkCatDiv = "ac-high"; }
      if ($otlkCat == "Moderate") { $otlkCatDiv = "ac-mod"; }
      if ($otlkCat == "Enhanced") { $otlkCatDiv = "ac-enh"; }
      if ($otlkCat == "Slight") { $otlkCatDiv = "ac-slight"; }
      if ($otlkCat == "Marginal") { $otlkCatDiv = "ac-mrgl"; }
      if ($otlkCat == "No Severe") { $otlkCatDiv = "ac-nosevere"; }
      if ($otlkCat == "No Thunder") { $otlkCatDiv = "ac-nothunder"; }
      if ($otlkCat == "Severe") { $otlkCatDiv = "ac48-severe"; }
      if ($otlkCat == "Severe 15%") { $otlkCatDiv = "ac48-15p"; }
      if ($otlkCat == "Severe 30%") { $otlkCatDiv = "ac48-30p"; }
      if ($otlkCat == "No Areas") { $otlkCatDiv = "ac48-noarea"; }
      if ($otlkCat == "30 %") { $otlkCatDiv = "ac48-30p"; }
      if ($otlkCat == "15 %") { $otlkCatDiv = "ac48-15p"; }
    }
 
    if (($otlkCat == "Severe 15%") || ($otlkCat == "Severe 30%")) { $otlkCat = "Severe"; } 

    if (($prodType == "FIRE" ) || ($prodType == "FIRE2" ) || ($prodType == "FIRE3" )) {
      $otlkCat = $data[3];
      if ($otlkCat == "Extreme") { $otlkCatDiv = "fw12-extreme"; }
      if ($otlkCat == "Critical") { $otlkCatDiv = "fw12-critical"; }
      if ($otlkCat == "Elevated") { $otlkCatDiv = "fw12-seetext"; }
      if ($otlkCat == "Iso DryT") { $otlkCatDiv = "fw12-seetext"; }
      if ($otlkCat == "No Critical") { $otlkCatDiv = "fw12-low"; }
      if ($otlkCat == "No Areas") { $otlkCatDiv = "fw38-noarea"; }
    }
  
    if (($prodType == "OTLK" ) && ($timeDiff < "3600" )) { 
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day1otlk.html'><img class='ACbg' id='SWODY1img' src='/products/outlook/day1otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day1otlk.html'>Day 1 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "OTLK" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day1otlk.html'><img class='ACsm' id='SWODY1img' src='/products/outlook/day1otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day1otlk.html'>Day 1 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "OTLK2" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day2otlk.html'><img class='ACbg' id='SWODY2img' src='/products/outlook/day2otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day2otlk.html'>Day 2 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "OTLK2" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day2otlk.html'><img class='ACsm' id='SWODY2img' src='/products/outlook/day2otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day2otlk.html'>Day 2 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "OTLK3" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day3otlk.html'><img class='ACbg' id='SWODY3img' src='/products/outlook/day3otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day3otlk.html'>Day 3 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "OTLK3" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day3otlk.html'><img class='ACsm' id='SWODY3img' src='/products/outlook/day3otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day3otlk.html'>Day 3 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "OTLK4" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/day4-8/'><img class='ACbg' id='SWODY48img' src='/products/exper/day4-8/day48prob_small.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/day4-8/'>Day 4-8 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td align='left' class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "OTLK4" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/day4-8/'><img class='ACsm' id='SWODY48img' src='/products/exper/day4-8/day48prob_small.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/day4-8/'>Day 4-8 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "ENH" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/enhtstm/'><img class='ACbg' id='ENHimg' src='/products/exper/enhtstm/enh_small.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/enhtstm/'>Thunderstorm Outlook</a></td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table>\n";
      }
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "ENH" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/enhtstm/'><img class='ACsm' id='ENHimg' src='/products/exper/enhtstm/enh_small.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/enhtstm/'>Thunderstorm Outlook</a></td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table>\n";
      print "</tr></table></li></ul>\n";
    }	
  
    if (($prodType == "FIRE" ) && ($timeDiff < "3600" )) { 
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/fire_wx/fwdy1.html'><img class='ACbg' id='FIRE1img' src='/products/fire_wx/day1fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/fire_wx/fwdy1.html'>Day 1 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td align='left' class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "FIRE" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/fire_wx/fwdy1.html'><img class='ACsm' id='FIRE1img' src='/products/fire_wx/day1fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/fire_wx/fwdy1.html'>Day 1 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "FIRE2" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/fire_wx/fwdy2.html'><img class='ACbg' id='FIRE2img' src='/products/fire_wx/day2fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/fire_wx/fwdy2.html'>Day 2 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td align='left' class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "FIRE2" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/fire_wx/fwdy2.html'><img class='ACsm' id='FIRE2img' src='/products/fire_wx/day2fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/fire_wx/fwdy2.html'>Day 2 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }
  
    if (($prodType == "FIRE3" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/fire_wx/'><img class='ACbg' id='FIRE38img' src='/products/exper/fire_wx/day3-8fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/fire_wx/'>Day 3-8 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td align='left' class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";        
    }
  
    if (($prodType == "FIRE3" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/fire_wx/'><img class='ACsm' id='FIRE38img' src='/products/exper/fire_wx/day3-8fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/fire_wx/'>Day 3-8 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";                
    }
  
    //Check for WWs	
// WW0283|201306061445|1370529900|FL GA AM GM CW|TORNADO|06 07 2013 0200|NORMAL|TROPICAL STORM|ANDREA
    $strCheck = substr($prodType,0,2);
    if ($strCheck == "WW") {
      $ww = substr($prodType,0,2);
      $wwNum = substr($prodType,2,4);
      if (($wwNum != "none") && ($timeDiff <= "3600")) {
        $wwType = $data[4];
        $wwArea = $data[3];
        $wwExpire = $data[5];
        $wwEnh = $data[6];
        $wwTCType = $data[7];
        $wwTCName = $data[8];
        $wwExpireMM = substr($wwExpire,0,2);
        $wwExpireDD = substr($wwExpire,3,2);
        $wwExpireYYYY = substr($wwExpire,6,4);
        $wwExpireHHMM = substr($wwExpire,11,4);
        $wwEnd = $wwExpireMM . "/" . $wwExpireDD . "/" . $wwExpireYYYY . " " . $wwExpireHHMM . "Z";
        print "<ul class='SPCProductsNew'>\n";
        print "<li><table><tr><td><a href='/products/watch/ww${wwNum}.html'><img class='WWbg' src='/products/watch/ww${wwNum}_thumb.gif'></a></td>\n";
        print "<td valign='top'><a href='/products/watch/ww${wwNum}.html'>${wwType} ${wwNum}</a><br>\n";
        print "&ndash; Valid until: ${wwEnd}<br>\n";
        print "&ndash; States affected: ${wwArea}<br>\n";
        if ($wwEnh == "PDS") { print "&ndash; <font color='#ff0000'><strong>Particularly Dangerous Situation</strong></font><br>\n"; }
        if ($wwTCType != "TCnone") { print "&ndash; <font color='#ff0000'><strong>$wwTCType $wwTCName Related Watch</strong></font><br />\n";}
        if ($mmDiff > 1) {
          print "&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font>\n";
        } else {
          print "&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font>\n";
        }
        print "</td></tr></table></li></ul>\n";
      } else if (($wwNum != "none") && ($timeDiff > "3600")) {
        $wwType = $data[4];
        $wwArea = $data[3];
        $wwExpire = $data[5];
        $wwEnh = $data[6];
        $wwTCType = $data[7];
        $wwTCName = $data[8];
        $wwExpireMM = substr($wwExpire,0,2);
        $wwExpireDD = substr($wwExpire,3,2);
        $wwExpireYYYY = substr($wwExpire,6,4);
        $wwExpireHHMM = substr($wwExpire,11,4);
        $wwEnd = $wwExpireMM . "/" . $wwExpireDD . "/" . $wwExpireYYYY . " " . $wwExpireHHMM . "Z";
        print "<ul class='SPCProductsOld'>\n";
        print "<li><table><tr><td><a href='/products/watch/ww${wwNum}.html'><img class='WWsm' src='/products/watch/ww${wwNum}_thumb.gif'></a></td>\n";
        print "<td valign='top'><a href='/products/watch/ww${wwNum}.html'>${wwType} ${wwNum}</a><br>\n";
        print "&ndash; Valid until: ${wwEnd}<br>\n";
        print "&ndash; States affected: ${wwArea}<br>\n";
        if ($wwEnh == "PDS") { print "&ndash; <font color='#ff0000'><strong>Particularly Dangerous Situation</strong></font><br>\n"; }
        if ($wwTCType != "TCnone") { print "&ndash; <font color='#ff0000'><strong>$wwTCType $wwTCName Related Watch</strong></font><br />\n";}
        print "&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr>\n";
        print "</td></tr></table></li></ul>\n";
      }
    }
  
    //Check for MDs
    $strCheck = substr($prodType,0,3);
    if ($strCheck == "MCD") {
      $mcd = $strCheck;
      $mdNum = substr($prodType,3,4);
      if (($mdNum != "none") && ($timeDiff <= "3600")) {
        $mdArea = $data[3];
        $mdConc = $data[4];
        $mdProb = $data[5];
        $mdTCType = $data[6];
        $mdTCName = $data[7];
        print "<ul class='SPCProductsNew'>\n";
        print "<li><table><tr><td><a href='/products/md/md${mdNum}.html'><img class='MDbg' src='/products/md/mcd${mdNum}_thumb.gif'></a></td>\n";
        print "<td valign='top'><a href='/products/md/md${mdNum}.html'>Mesoscale Discussion ${mdNum}</a><br>\n";
        print "&ndash; Concerning: ${mdConc}<br>\n";
        if ($mdTCType != "TCnone") { print "&ndash; <font color='#ff0000'><strong>$mdTCType $mdTCName Related MD</strong></font><br />\n";}
        if ($mmDiff > 1) {
          print "&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font>\n";
        } else {
          print "&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font>\n";
        }
        print "</td></tr></table></li></ul>\n";
      } else if (($mdNum != "none") && ($timeDiff > "3600")) {
        $mdArea = $data[3];
        $mdConc = $data[4];
        $mdProb = $data[5];
        $mdTCType = $data[6];
        $mdTCName = $data[7];
        print "<ul class='SPCProductsOld'>\n";
        print "<li><table><tr><td><a href='/products/md/md${mdNum}.html'><img class='MDsm' src='/products/md/mcd${mdNum}_thumb.gif'></a></td>\n";
        print "<td valign='top'><a href='/products/md/md${mdNum}.html'>Mesoscale Discussion ${mdNum}</a><br>\n";
        print "&ndash; Concerning: ${mdConc}<br>\n";
        if ($mdTCType != "TCnone") { print "&ndash; <font color='#ff0000'><strong>$mdTCType $mdTCName Related MD</strong></font><br />\n";}
        print "&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr>\n";
        print "</td></tr></table></li></ul>\n";
      }
    }
  }
  print "</div>\n";
  print "</div>\n";
} //end subroutine

//Current Watches
function showAllWW() {

  //set time var
  $curEtime = `/bin/date -u +%s`;
  $curEpochTime = trim($curEtime);

  #first, read validProducts.txt into memory.
  $validProd = file("./validProducts.txt");

  print "<div id='allWW'>\n";
  print "<div class='SPCProducts'>\n";

  foreach($validProd as $line) {
    $tline = trim($line);
    $data = explode("|",$tline);
    $prodType = $data[0];
    $prodTime = $data[1];
    $prodTimeYYYY = substr($prodTime,0,4);
    $prodTimeMM = substr($prodTime,4,2);
    $prodTimeDD = substr($prodTime,6,2);
    $prodTimeHHMM = substr($prodTime,8,4);
    $prodEpochTime  = $data[2];
    $timeDiff = $curEpochTime - $prodEpochTime;
    $hhDiff = gmdate("H", $timeDiff);
    $mmDiff = gmdate("i", $timeDiff);
    if ($mmDiff < 10) {
      $mmDiff %= 10;
    }
    if ($hhDiff == 0) {
      if ($mmDiff > 1) {
        $issueLabel = "&ndash; Issued: $mmDiff minutes ago<br />";
      } else {
        $issueLabel = "&ndash; Issued: $mmDiff minute ago<br />";
      }
    } else {
      if ($hhDiff == 1) {
        if ($mmDiff > 1) {
          $issueLabel = "&ndash; Issued: $hhDiff hour $mmDiff minutes ago<br />";
        } else {
          $issueLabel = "&ndash; Issued: $hhDiff hour $mmDiff minute ago<br />";
        }
      } else {
        if ($mmDiff > 1) {
          $issueLabel = "&ndash; Issued: $hhDiff hours $mmDiff minutes ago<br />";
        } else {
          $issueLabel = "&ndash; Issued: $hhDiff hours $mmDiff minute ago<br />";
        }
      }
    }
    $strCheck = substr($prodType,0,2);
    if ($strCheck == "WW") {
      $ww = substr($prodType,0,2);
      $wwNum = substr($prodType,2,4);
      if ($wwNum == "none" ) {
        print "<table width='330px' height='200px'><tr><td align 'center' class='SPCProductsNoWW'>No Valid Watches</td></tr></table>\n";
      } else if (($wwNum != "none") && ($timeDiff <= "3600")) {
        $wwType = $data[4];
        $wwArea = $data[3];
        $wwExpire = $data[5];
        $wwExpireMM = substr($wwExpire,0,2);
        $wwExpireDD = substr($wwExpire,3,2);
        $wwExpireYYYY = substr($wwExpire,6,4);
        $wwExpireHHMM = substr($wwExpire,11,4);
        $wwEnh = $data[6];
        $wwTCType = $data[7];
        $wwTCName = $data[8];
        print "<ul class='SPCProductsNew'>\n";
        print "<li><table><tr><td><a href='/products/watch/ww${wwNum}.html'><img class='WWbg' src='/products/watch/ww${wwNum}_thumb.gif'></a></td>\n";
        if ($wwType == "TORNADO") {
          print "<td valign='top'><a href='/products/watch/ww${wwNum}.html'><font color='#ff0000'>${wwType} ${wwNum}</font></a><br>\n";
        } else {
          print "<td valign='top'><a href='/products/watch/ww${wwNum}.html'>${wwType} ${wwNum}</a><br>\n";
        }
        print "&ndash; Valid until: ${wwExpireMM}/${wwExpireDD}/${wwExpireYYYY} ${wwExpireHHMM}Z<br>\n";
        print "&ndash; States affected: ${wwArea}<br>\n";
        if ($wwEnh == "PDS") { print "&ndash; <font color='#ff0000'><strong>Particularly Dangerous Situation</strong></font><br>\n"; }
        if ($wwTCType != "TCnone") { print "&ndash; <font color='#ff0000'><strong>$wwTCType $wwTCName Related Watch</strong></font><br />\n";}
        if ($mmDiff > 1) {
          print "&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font>\n";
        } else {
          print "&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font>\n";
        }
        print "</td></tr></table></li></ul>\n";
      } else if (($wwNum != "none") && ($timeDiff > "3600")) {
        $wwType = $data[4];
        $wwArea = $data[3];
        $wwExpire = $data[5];
        $wwExpireMM = substr($wwExpire,0,2);
        $wwExpireDD = substr($wwExpire,3,2);
        $wwExpireYYYY = substr($wwExpire,6,4);
        $wwExpireHHMM = substr($wwExpire,11,4);
        $wwEnh = $data[6];
        $wwTCType = $data[7];
        $wwTCName = $data[8];
        print "<ul class='SPCProductsOld'>\n";
        print "<li><table><tr><td><a href='/products/watch/ww${wwNum}.html'><img class='WWsm' src='/products/watch/ww${wwNum}_thumb.gif'></a></td>\n";
        if ($wwType == "TORNADO") {
          print "<td valign='top'><a href='/products/watch/ww${wwNum}.html'><font color='#ff0000'>${wwType} ${wwNum}</font></a><br>\n";
        } else {
          print "<td valign='top'><a href='/products/watch/ww${wwNum}.html'>${wwType} ${wwNum}</a><br>\n";
        }
        print "&ndash; Valid until: ${wwExpireMM}/${wwExpireDD}/${wwExpireYYYY} ${wwExpireHHMM}Z<br>\n";
        print "&ndash; States affected: ${wwArea}<br>\n";
        if ($wwEnh == "PDS") { print "&ndash; <font color='#ff0000'><strong>Particularly Dangerous Situation</strong></font><br>\n"; }
        if ($wwTCType != "TCnone") { print "&ndash; <font color='#ff0000'><strong>$wwTCType $wwTCName Related Watch</strong></font><br />\n";}
        print "&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr>\n";
	print "</td></tr></table></li></ul>\n";
      }
    }		
  }
  print "</div>\n";
  print "</div>\n";
}  //end function;

// Current MDs
function showAllMD() {

// set time var
  $curEtime = `/bin/date -u +%s`;
  $curEpochTime = trim($curEtime);

  #first, read validProducts.txt into memory.
  $validProd = file("./validProducts.txt");

  print "<div id='allMD'>\n";
  print "<div class='SPCProducts'>\n";

  foreach($validProd as $line) {
    $tline = trim($line);
    $data = explode("|",$tline);
    $prodType = $data[0];
    $prodTime = $data[1];
    $prodTimeYYYY = substr($prodTime,0,4);
    $prodTimeMM = substr($prodTime,4,2);
    $prodTimeDD = substr($prodTime,6,2);
    $prodTimeHHMM = substr($prodTime,8,4);
    $prodEpochTime  = $data[2];
    $timeDiff = $curEpochTime - $prodEpochTime;
    $hhDiff = gmdate("H", $timeDiff);
    $mmDiff = gmdate("i", $timeDiff);
    if ($mmDiff < 10) {
      $mmDiff %= 10;
    }
    if ($hhDiff == 0) {
      if ($mmDiff > 1) {
        $issueLabel = "&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font><br />";
      } else {
        $issueLabel = "&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font><br />";
      }
    } else {
      if ($hhDiff == 1) {
        if ($mmDiff > 1) {
          $issueLabel = "&ndash; Issued: $hhDiff hour $mmDiff minutes ago<br />";
        } else {
          $issueLabel = "&ndash; Issued: $hhDiff hour $mmDiff minute ago<br />";
        }
      } else {
        if ($mmDiff > 1) {
          $issueLabel = "&ndash; Issued: $hhDiff hours $mmDiff minutes ago<br />";
        } else {
          $issueLabel = "&ndash; Issued: $hhDiff hours $mmDiff minute ago<br />";
        }
      }
    }

    $strCheck = substr($prodType,0,3);
    if ($strCheck == "MCD") {
      $mcd = $strCheck;
      $mdNum = substr($prodType,3,4);
      if ($mdNum == "none" ) {
        print "<table width='330px' height='200px'><tr><td align 'center' class='SPCProductsNoMD'>No Valid Mesoscale Discussions</td></tr></table>\n";
      } else if (($mdNum != "none") && ($timeDiff <= "3600")) {
        $mdArea = $data[3];
        $mdConc = $data[4];
        $mdProb = $data[5];
        $mdTCType = $data[6];
        $mdTCName = $data[7];
        print "<ul class='SPCProductsNew'>\n";
        print "<li><table><tr><td><a href='/products/md/md${mdNum}.html'><img class='MDbg' src='/products/md/mcd${mdNum}_thumb.gif'></a></td>\n";
        print "<td valign='top'><a href='/products/md/md${mdNum}.html'>Mesoscale Discussion ${mdNum}</a><br>\n";
        print "&ndash; Concerning: ${mdConc}<br>\n";
        if ($mdTCType != "TCnone") { print "&ndash; <font color='#ff0000'><strong>$mdTCType $mdTCName Related MD</strong></font><br />\n";}
        print "$issueLabel\n";
        print "</td></tr></table></li></ul>\n";
      } else if (($wwNum != "none") && ($timeDiff > "3600")) {
        $mdArea = $data[3];
        $mdConc = $data[4];
        $mdProb = $data[5];
        $mdTCType = $data[6];
        $mdTCName = $data[7];
        print "<ul class='SPCProductsOld'>\n";
        print "<li><table><tr><td><a href='/products/md/md${mdNum}.html'><img class='MDsm' src='/products/md/mcd${mdNum}_thumb.gif'></a></td>\n";
        print "<td valign='top'><a href='/products/md/md${mdNum}.html'>Mesoscale Discussion ${mdNum}</a><br>\n";
        print "&ndash; Concerning: ${mdConc}<br>\n";
        if ($mdTCType != "TCnone") { print "&ndash; <font color='#ff0000'><strong>$mdTCType $mdTCName Related MD</strong></font><br />\n";}
        print "&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr>\n";
	print "</td></tr></table></li></ul>\n";
      }
    }
  }
  print "</div>\n";
  print "</div>\n";
} //end function;

// Current Convective outlooks
function showAllOTLK () {

  // set time var
  $curEtime = `/bin/date -u +%s`;
  $curEpochTime = trim($curEtime);

  #first, read validProducts.txt into memory.
  $validProd = file("./validProducts.txt");

  print "<div id='allOTLK'>\n";
  print "<div class='SPCProducts'>\n";

  foreach($validProd as $line) {
    $tline = trim($line);
    $data = explode("|",$tline);
    $prodType = $data[0];
    $prodTime = $data[1];
    $prodTimeYYYY = substr($prodTime,0,4);
    $prodTimeMM = substr($prodTime,4,2);
    $prodTimeDD = substr($prodTime,6,2);
    $prodTimeHHMM = substr($prodTime,8,4);
    $prodEpochTime  = $data[2];
    $timeDiff = $curEpochTime - $prodEpochTime;
    $hhDiff = gmdate("H", $timeDiff);
    $mmDiff = gmdate("i", $timeDiff);
    if ($mmDiff < 10) {
      $mmDiff %= 10;
    }

    //determine hazard level of the outlooks
    if (($prodType == "OTLK" ) || ($prodType == "OTLK2" ) || ($prodType == "OTLK3" ) || ($prodType == "OTLK4" )) {
      $otlkHaz = $data[3];
      $otlkCat = trim($otlkHaz);
      if ($otlkCat == "High") { $otlkCatDiv = "ac-high"; }
      if ($otlkCat == "Moderate") { $otlkCatDiv = "ac-mod"; }
      if ($otlkCat == "Enhanced") { $otlkCatDiv = "ac-enh"; }
      if ($otlkCat == "Slight") { $otlkCatDiv = "ac-slight"; }
      if ($otlkCat == "Marginal") { $otlkCatDiv = "ac-mrgl"; }
      if ($otlkCat == "See Text") { $otlkCatDiv = "ac-seetext"; }
      if ($otlkCat == "No Severe") { $otlkCatDiv = "ac-nosevere"; }
      if ($otlkCat == "No Thunder") { $otlkCatDiv = "ac-nothunder"; }
      if ($otlkCat == "Severe") { $otlkCatDiv = "ac48-severe"; }
      if ($otlkCat == "Severe 15%") { $otlkCatDiv = "ac48-15p"; }
      if ($otlkCat == "Severe 30%") { $otlkCatDiv = "ac48-30p"; }
      if ($otlkCat == "No Areas") { $otlkCatDiv = "ac48-noarea"; }
    }

    if (($otlkCat == "Severe 15%") || ($otlkCat == "Severe 30%")) { $otlkCat = "Severe"; } 

    if (($prodType == "OTLK" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day1otlk.html'><img class='ACbg' id='SWODY1img' src='/products/outlook/day1otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day1otlk.html'>Day 1 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "OTLK" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day1otlk.html'><img class='ACsm' id='SWODY1img' src='/products/outlook/day1otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day1otlk.html'>Day 1 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "OTLK2" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day2otlk.html'><img class='ACbg' id='SWODY2img' src='/products/outlook/day2otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day2otlk.html'>Day 2 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "OTLK2" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day2otlk.html'><img class='ACsm' id='SWODY2img' src='/products/outlook/day2otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day2otlk.html'>Day 2 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "OTLK3" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day3otlk.html'><img class='ACbg' id='SWODY3img' src='/products/outlook/day3otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day3otlk.html'>Day 3 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "OTLK3" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/outlook/day3otlk.html'><img class='ACsm' id='SWODY3img' src='/products/outlook/day3otlk_sm.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/outlook/day3otlk.html'>Day 3 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "OTLK4" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/day4-8/'><img class='ACbg' id='SWODY48img' src='/products/exper/day4-8/day48prob_small.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/day4-8/'>Day 4-8 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td align='left' class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "OTLK4" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/day4-8/'><img class='ACsm' id='SWODY48img' src='/products/exper/day4-8/day48prob_small.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/day4-8/'>Day 4-8 Convective Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "ENH" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/enhtstm/'><img class='ACbg' id='ENHimg' src='/products/exper/enhtstm/enh_small.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/enhtstm/'>Thunderstorm Outlook</a></td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table>\n";
      }
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "ENH" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/enhtstm/'><img class='ACsm' id='ENHimg' src='/products/exper/enhtstm/enh_small.gif'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/enhtstm/'>Thunderstorm Outlook</a></td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table>\n";
      print "</tr></table></li></ul>\n";
    }
  }

  print "</div>\n";
  print "</div>\n";
} //end subroutine

//Current Fire WX outlooks
function showAllFIRE () {

  // set time var
  $curEtime = `/bin/date -u +%s`;
  $curEpochTime = trim($curEtime);

  #first, read validProducts.txt into memory.
  $validProd = file("./validProducts.txt");

  print "<div id='allFIRE'>\n";
  print "<div class='SPCProducts'>\n";

  foreach($validProd as $line) {
    $tline = trim($line);
    $data = explode("|",$tline);
    $prodType = $data[0];
    $prodTime = $data[1];
    $prodTimeYYYY = substr($prodTime,0,4);
    $prodTimeMM = substr($prodTime,4,2);
    $prodTimeDD = substr($prodTime,6,2);
    $prodTimeHHMM = substr($prodTime,8,4);
    $prodEpochTime  = $data[2];
    $timeDiff = $curEpochTime - $prodEpochTime;
    $hhDiff = gmdate("H", $timeDiff);
    $mmDiff = gmdate("i", $timeDiff);
    if ($mmDiff < 10) {
      $mmDiff %= 10;
    }

    if (($prodType == "FIRE" ) || ($prodType == "FIRE2" ) || ($prodType == "FIRE3" )) {
      $otlkCat = $data[3];
      if ($otlkCat == "Extreme") { $otlkCatDiv = "fw12-extreme"; }
      if ($otlkCat == "Critical") { $otlkCatDiv = "fw12-critical"; }
      if ($otlkCat == "Elevated") { $otlkCatDiv = "fw12-seetext"; }
      if ($otlkCat == "Iso DryT") { $otlkCatDiv = "fw12-seetext"; }
      if ($otlkCat == "No Critical") { $otlkCatDiv = "fw12-low"; }
      if ($otlkCat == "No Areas") { $otlkCatDiv = "fw38-noarea"; }
    }

    if (($prodType == "FIRE" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/fire_wx/'><img class='ACbg' id='FIRE1img' src='/products/fire_wx/day1fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/fire_wx/'>Day 1 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td align='left' class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "FIRE" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/fire_wx/'><img class='ACsm' id='FIRE1img' src='/products/fire_wx/day1fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/fire_wx/'>Day 1 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "FIRE2" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/fire_wx/#Day2'><img class='ACbg' id='FIRE2img' src='/products/fire_wx/day2fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/fire_wx/#Day2'>Day 2 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td align='left' class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "FIRE2" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/fire_wx/#Day2'><img class='ACsm' id='FIRE2img' src='/products/fire_wx/day2fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/fire_wx/#Day2'>Day 2 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "FIRE3" ) && ($timeDiff < "3600" )) {
      print "<ul class='SPCProductsNew'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/fire_wx/'><img class='ACbg' id='FIRE38img' src='/products/exper/fire_wx/day3-8fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/fire_wx/'>Day 3-8 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td align='left' class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      if ($mmDiff > 1) {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minutes ago</font></td></tr></table></td>\n";
      } else {
        print "<tr><td colspan='2'>&ndash; Issued: <font color='#ff0000'>$mmDiff minute ago</font></td></tr></table></td>\n";
      }
      print "</tr></table></li></ul>\n";
    }

    if (($prodType == "FIRE3" ) && ($timeDiff >= "3600" )) {
      print "<ul class='SPCProductsOld'>\n";
      print "<li><table><tr><td>\n";
      print "<div><a href='/products/exper/fire_wx/'><img class='ACsm' id='FIRE38img' src='/products/exper/fire_wx/day3-8fireotlk_sm.png'></a></div></td>\n";
      print "<td valign='top'><table><tr>\n";
      print "<td colspan='2' valign='top'><a href='/products/exper/fire_wx/'>Day 3-8 Fire Weather Outlook</a></td></tr>\n";
      print "<tr><td width='105px'>&ndash; Categorical Risk: </td><td class='${otlkCatDiv}'>$otlkCat</td></tr>\n";
      print "<tr><td colspan='2'>&ndash; Issued: ${prodTimeMM}/${prodTimeDD}/${prodTimeYYYY} at ${prodTimeHHMM}Z</td></tr></table></td>\n";
      print "</tr></table></li></ul>\n";
    }
  }

  print "</div>\n";
  print "</div>\n";
}//end subroutine

?>
