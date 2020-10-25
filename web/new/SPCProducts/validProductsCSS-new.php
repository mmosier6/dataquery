<?php
//first open valid products file to determine operational mode of mainpage
$prodFile = file("./validProducts.txt");

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

echo "<style>";

if ( $validWW == 1)  {
  echo "ul.tabbernav li a#prodsnav2 {";
  echo "	  padding: 3px 0.5em;";
  echo "   margin-left: 3px;";
  echo "   border: 1px solid #778;";
  echo "   border-bottom: none;";
  echo "   color: #111;";
  echo "   background: #ff0000;";
  echo "   text-decoration: none;";
  echo "   animation:pulse 5s;";
  echo "   animation-iteration-count: infinite;";
  echo "   -moz-animation:pulse 5s;";
  echo "   -moz-animation-iteration-count: infinite;";
  echo "   -webkit-animation:pulse 5s;";
  echo "   -webkit-animation-iteration-count: infinite;";
  echo "   -o-animation:pulse 5s;";
  echo "   -o-animation-iteration-count: infinite;";
  echo "}";
  echo "ul.tabbernav li a#prodsnav2:hover {";
  echo "   background: #ff9b9b;";
  echo "}";
  echo "@keyframes pulse";  
  echo "{";
  echo "0% { background: #ff0000; }";
  echo "50% { background: #ffb1b1; }";
  echo "100% { background: #ff0000; }";
  echo "}";
  echo "@-moz-keyframes pulse";
  echo "{";
  echo "0% { background: #ff0000; }";
  echo "50% { background: #ffb1b1; }";
  echo "100% { background: #ff0000; }";
  echo "}";
  echo "@-webkit-keyframes pulse";
  echo "{";
  echo "0% { background: #ff0000; }";
  echo "50% { background: #ffb1b1; }";
  echo "100% { background: #ff0000; }";
  echo "}";
  echo "@-o-keyframes pulse";
  echo "{";
  echo "0% { background: #ff0000; }";
  echo "50% { background: #ffb1b1; }";
  echo "100% { background: #ff0000; }";
  echo "}";
} elseif ($validWW == 0) {
  echo "ul.tabbernav li a#prodsnav2 {";
  echo "   padding: 3px 0.5em;";
  echo "   margin-left: 3px;";
  echo "   border: 1px solid #778;";
  echo "   border-bottom: none;";
  echo "   color: #111;";
  echo "   background: #d2ffa6;";
  echo "   text-decoration: none;";
  echo "}";
  echo "ul.tabbernav li a#prodsnav2:hover {";
  echo "   background: #84fe0c;";
  echo "}";
}

if ( $validMD == 1)  {
  echo "#prodsnav3 {";
  echo "   padding: 3px 0.5em;";
  echo "   margin-left: 3px;";
  echo "   border: 1px solid #778;";
  echo "   border-bottom: none;";
  echo "   color: #111;";
  echo "   background-color: #ff0000;";
  echo "   text-decoration: none;";
  echo "   animation:pulse 3s;";
  echo "   animation-iteration-count: infinite;";
  echo "   -moz-animation:pulse 3s;";
  echo "   -moz-animation-iteration-count: infinite;";
  echo "   -webkit-animation:pulse 3s;";
  echo "   -webkit-animation-iteration-count: infinite;";
  echo "   -o-animation:pulse 3s;";
  echo "   -o-animation-iteration-count: infinite;";
  echo "}";
  echo "ul.tabbernav li a#prodsnav3:hover {";
  echo "   background: #ff9b9b;";
  echo "}";
  echo "@keyframes pulse"; 
  echo "{";
  echo "0% { background: #ff0000; }";
  echo "50% { background: #ffb1b1; }";
  echo "100% { background: #ff0000; }";
  echo "}";
  echo "@-moz-keyframes pulse";
  echo "{";
  echo "0% { background: #ff0000; }";
  echo "50% { background: #ffb1b1; }";
  echo "100% { background: #ff0000; }";
  echo "}";
  echo "@-webkit-keyframes pulse";
  echo "{";
  echo "0% { background: #ff0000; }";
  echo "50% { background: #ffb1b1; }";
  echo "100% { background: #ff0000; }";
  echo "}";
  echo "@-o-keyframes pulse";
  echo "{";
  echo "0% { background: #ff0000; }";
  echo "50% { background: #ffb1b1; }";
  echo "100% { background: #ff0000; }";
  echo "}";
} elseif ($validMD == 0) {
  echo "ul.tabbernav li a#prodsnav3 {";
  echo "   padding: 3px 0.5em;";
  echo "   margin-left: 3px;";
  echo "   border: 1px solid #778;";
  echo "   border-bottom: none;";
  echo "   color: #111;";
  echo "   background: #d2ffa6;";
  echo "   text-decoration: none;";
  echo "}";
  echo "ul.tabbernav li a#prodsnav3:hover {";
  echo "   background: #84fe0c;";
  echo "}";
}

echo "</style>";

?>
