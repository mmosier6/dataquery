<?php
//need to parse population data file

if(isset($_GET['day'])) {
	$inputDay = $_GET['day'];
        $INd = chop($inputDay);
} else { 
$INd = 1;
}

$inFile = "DAY" . $INd . "_ConvOtlkSI.txt";


$FILE = file($inFile);

$dataOut = array();
$j = 0;
//Determine Categorical Risk by checking AD stats
foreach($FILE as $line) {
	$tline = trim($line);
	$content = explode(",",$tline);
	if ($content[1] > 0) { $j++; }
}

foreach($FILE as $line) {
        $tline = trim($line);
        $content = explode(",",$tline);
	//if ((preg_match('/(SLIGHT|MODERATE|HIGH)/',$content[0])) && (!preg_match('/^[0-9]/',$content[1]))) {
	//	$numCity = count(array_filter($content));
        //        print "$numCity\n";
		//needed to use array_filter to remove empty array elements
		//array_filter($content));
	//}
	array_push($dataOut,$content);
}

//Determine 

//print_r($dataOut);

//Now populate the table according to the threat.

print "<table cellspacing='1' cellpadding='1' border='0' width='100%' id='result'>\n";
print "<tr align='center' bgcolor='#eeeeee'>\n";
	
if ($j == 3 ) { 
print "<td align='center' class='ac-high' width='105px'><span class='impacts'>HIGH Risk</span></td>\n";
$num = number_format($dataOut[0][1]);
print "<td align='center' class='cities_high' width='80px'><span class='impacts'>$num</span></td>\n";
$num = number_format($dataOut[0][2]);
print "<td align='center' class='cities_high' width='80px'><span class='impacts'>$num</span></td>\n";
print "<td align='center' class='cities_high' width='320px'><span class='impacts'>";
print "{$dataOut[3][1]},{$dataOut[3][2]}...{$dataOut[3][3]},{$dataOut[3][4]}...{$dataOut[3][5]},{$dataOut[3][6]}...{$dataOut[3][7]},{$dataOut[3][8]}...{$dataOut[3][9]},{$dataOut[3][10]}...";
print "</span></td></tr>\n";
print "<tr align='center' bgcolor='#eeeeee'>\n";
print "<td align='center' class='ac-mod' width='105px'><span class='impacts'>Moderate Risk</span></td>\n";
$num = number_format($dataOut[1][1]);
print "<td align='center' class='cities_mdt' width='80px'><span class='impacts'>$num</span></td>\n";
$num = number_format($dataOut[1][2]);
print "<td align='center' class='cities_mdt' width='80px'><span class='impacts'>$num</span></td>\n";
print "<td align='center' class='cities_mdt' width='320px'><span class='impacts'>";
print "{$dataOut[4][1]},{$dataOut[4][2]}...{$dataOut[4][3]},{$dataOut[4][4]}...{$dataOut[4][5]},{$dataOut[4][6]}...{$dataOut[4][7]},{$dataOut[4][8]}...{$dataOut[4][9]},{$dataOut[4][10]}...";
print "</span></td></tr>\n";
print "<tr align='center' bgcolor='#eeeeee'>\n";
print "<td align='center' class='ac-slgt' width='105px'><span class='impacts'>Slight Risk</span></td>\n";
$num = number_format($dataOut[2][1]);
print "<td align='center' class='cities_slgt' width='80px'><span class='impacts'>$num</span></td>\n";
$num = number_format($dataOut[2][2]);
print "<td align='center' class='cities_slgt' width='80px'><span class='impacts'>$num</span></td>\n";
print "<td align='center' class='cities_slgt' width='320px'><span class='impacts'>";
print "{$dataOut[5][1]},{$dataOut[5][2]}...{$dataOut[5][3]},{$dataOut[5][4]}...{$dataOut[5][5]},{$dataOut[5][6]}...{$dataOut[5][7]},{$dataOut[5][8]}...{$dataOut[5][9]},{$dataOut[5][10]}...";
print "</span></td></tr></table>\n";
}

if ($j == 2) {
print "<td align='center' class='ac-mod' width='105px'><span class='impacts'>Moderate Risk</span></td>\n";
$num = number_format($dataOut[1][1]);
print "<td align='center' class='cities_mdt' width='80px'><span class='impacts'>$num</span></td>\n";
$num = number_format($dataOut[1][2]);
print "<td align='center' class='cities_mdt' width='80px'><span class='impacts'>$num</span></td>\n";
print "<td align='center' class='cities_mdt' width='320px'><span class='impacts'>";
print "{$dataOut[3][1]},{$dataOut[3][2]}...{$dataOut[3][3]},{$dataOut[3][4]}...{$dataOut[3][5]},{$dataOut[3][6]}...{$dataOut[3][7]},{$dataOut[3][8]}...{$dataOut[3][9]},{$dataOut[3][10]}...";
print "</span></td></tr>\n";
print "<tr align='center' bgcolor='#eeeeee'>\n";
print "<td align='center' class='ac-slgt' width='105px'><span class='impacts'>Slight Risk</span></td>\n";
$num = number_format($dataOut[2][1]);
print "<td align='center' class='cities_slgt' width='80px'><span class='impacts'>$num</span></td>\n";
$num = number_format($dataOut[2][2]);
print "<td align='center' class='cities_slgt' width='80px'><span class='impacts'>$num</span></td>\n";
print "<td align='center' class='cities_slgt' width='320px'><span class='impacts'>";
print "{$dataOut[4][1]},{$dataOut[4][2]}...{$dataOut[4][3]},{$dataOut[4][4]}...{$dataOut[4][5]},{$dataOut[4][6]}...{$dataOut[4][7]},{$dataOut[4][8]}...{$dataOut[4][9]},{$dataOut[4][10]}...";
print "</span></td></tr></table>\n";
}

if ($j == 1) {
print "<td align='center' class='ac-slgt' width='105px'><span class='impacts'>Slight Risk</span></td>\n";
$num = number_format($dataOut[2][1]);
print "<td align='center' class='cities_slgt' width='80px'><span class='impacts'>$num</span></td>\n";
$num = number_format($dataOut[2][2]);
print "<td align='center' class='cities_slgt' width='80px'><span class='impacts'>$num</span></td>\n";
print "<td align='center' class='cities_slgt' width='320px'><span class='impacts'>";
print "{$dataOut[3][1]},{$dataOut[3][2]}...{$dataOut[3][3]},{$dataOut[3][4]}...{$dataOut[3][5]},{$dataOut[3][6]}...{$dataOut[3][7]},{$dataOut[3][8]}...{$dataOut[3][9]},{$dataOut[3][10]}...";
print "</span></td></tr></table>\n";
}

if ($j == 0) {
print "<td align='center' colspan='4' width='100%'><span class='impacts'>No Organized Severe Storms Forecast</span></td></tr></table>\n";
} 

?>
