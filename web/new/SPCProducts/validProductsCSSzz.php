<?php
//first open valid products file to determine operational mode of mainpage
$prodFile = file("./validProductszz.txt");

foreach($prodFile as $line) {
  $tline = trim($line);
  $data = explode("|",$tline);
  $prodType = $data[0];
  //check for valid WWs
  $strCheck = substr($prodType,0,2);
  if ($strCheck == "WW") {
    $ww = substr($prodType,0,2);
    $wwNum = substr($prodType,2,4);
    if ($wwNum != "none") { $validWW = 1; }
    elseif ($wwNum == "none") { $validWW = 0; }
  }
  $strCheck = substr($prodType,0,3);
  if ($strCheck == "MCD") {
    $md = substr($prodType,0,3);
    $mdNum = substr($prodType,3,4);
    if ($mdNum != "none") { $validMD = 1; }
    elseif ($mdNum == "none") { $validMD = 0; }
  }
}

print "<style>\n";

if ( $validWW == 1)  {
  print "ul.tabbernav li a#prodsnav2 {\n";
  print "	  padding: 3px 0.5em;\n";
  print "   margin-left: 3px;\n";
  print "   border: 1px solid #778;\n";
  print "   border-bottom: none;\n";
  print "   color: #111;\n";
  print "   background: #ff0000;\n";
  print "   text-decoration: none;\n";
  print "   animation:pulse 5s;\n";
  print "   animation-iteration-count: infinite;\n";
  print "   -moz-animation:pulse 5s;\n";
  print "   -moz-animation-iteration-count: infinite;\n";
  print "   -webkit-animation:pulse 5s;\n";
  print "   -webkit-animation-iteration-count: infinite;\n";
  print "   -o-animation:pulse 5s;\n";
  print "   -o-animation-iteration-count: infinite;\n";
  print "}\n";
  print "ul.tabbernav li a#prodsnav2:hover {\n";
  print "   background: #ff9b9b;\n";
  print "}\n";
  print "@keyframes pulse\n";  
  print "{\n";
  print "0% { background: #ff0000; }\n";
  print "50% { background: #ffb1b1; }\n";
  print "100% { background: #ff0000; }\n";
  print "}\n";
  print "@-moz-keyframes pulse\n";
  print "{\n";
  print "0% { background: #ff0000; }\n";
  print "50% { background: #ffb1b1; }\n";
  print "100% { background: #ff0000; }\n";
  print "}\n";
  print "@-webkit-keyframes pulse\n";
  print "{\n";
  print "0% { background: #ff0000; }\n";
  print "50% { background: #ffb1b1; }\n";
  print "100% { background: #ff0000; }\n";
  print "}\n";
  print "@-o-keyframes pulse\n";
  print "{\n";
  print "0% { background: #ff0000; }\n";
  print "50% { background: #ffb1b1; }\n";
  print "100% { background: #ff0000; }\n";
  print "}\n";
} elseif ($validWW == 0) {
  print "ul.tabbernav li a#prodsnav2 {\n";
  print "   padding: 3px 0.5em;\n";
  print "   margin-left: 3px;\n";
  print "   border: 1px solid #778;\n";
  print "   border-bottom: none;\n";
  print "   color: #111;\n";
  print "   background: #d2ffa6;\n";
  print "   text-decoration: none;\n";
  print "}\n";
  print "ul.tabbernav li a#prodsnav2:hover {\n";
  print "   background: #84fe0c;\n";
  print "}\n";
}

if ( $validMD == 1)  {
  print "#prodsnav3 {\n";
  print "   padding: 3px 0.5em;\n";
  print "   margin-left: 3px;\n";
  print "   border: 1px solid #778;\n";
  print "   border-bottom: none;\n";
  print "   color: #111;\n";
  print "   background-color: #ff0000;\n";
  print "   text-decoration: none;\n";
  print "   animation:pulse 3s;\n";
  print "   animation-iteration-count: infinite;\n";
  print "   -moz-animation:pulse 3s;\n";
  print "   -moz-animation-iteration-count: infinite;\n";
  print "   -webkit-animation:pulse 3s;\n";
  print "   -webkit-animation-iteration-count: infinite;\n";
  print "   -o-animation:pulse 3s;\n";
  print "   -o-animation-iteration-count: infinite;\n";
  print "}\n";
  print "ul.tabbernav li a#prodsnav3:hover {\n";
  print "   background: #ff9b9b;\n";
  print "}\n";
  print "@keyframes pulse\n"; 
  print "{\n";
  print "0% { background: #ff0000; }\n";
  print "50% { background: #ffb1b1; }\n";
  print "100% { background: #ff0000; }\n";
  print "}\n";
  print "@-moz-keyframes pulse\n";
  print "{\n";
  print "0% { background: #ff0000; }\n";
  print "50% { background: #ffb1b1; }\n";
  print "100% { background: #ff0000; }\n";
  print "}\n";
  print "@-webkit-keyframes pulse\n";
  print "{\n";
  print "0% { background: #ff0000; }\n";
  print "50% { background: #ffb1b1; }\n";
  print "100% { background: #ff0000; }\n";
  print "}\n";
  print "@-o-keyframes pulse\n";
  print "{\n";
  print "0% { background: #ff0000; }\n";
  print "50% { background: #ffb1b1; }\n";
  print "100% { background: #ff0000; }\n";
  print "}\n";
} elseif ($validMD == 0) {
  print "ul.tabbernav li a#prodsnav3 {\n";
  print "   padding: 3px 0.5em;\n";
  print "   margin-left: 3px;\n";
  print "   border: 1px solid #778;\n";
  print "   border-bottom: none;\n";
  print "   color: #111;\n";
  print "   background: #d2ffa6;\n";
  print "   text-decoration: none;\n";
  print "}\n";
  print "ul.tabbernav li a#prodsnav3:hover {\n";
  print "   background: #84fe0c;\n";
  print "}\n";
}

print "</style>\n";

?>
