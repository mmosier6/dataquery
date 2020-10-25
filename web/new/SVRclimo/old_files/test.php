<?php

$days = array(); 
if ($handle = opendir('/web/spcweb/public_html2/misc/climographics/all_torn')) {
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
$lstwk = $wk -1;
$diff = 53 - $lstwk;

$pre_wk = array_slice($weeks,0,$lstwk);
$post_wk = array_slice($weeks,-$diff);

foreach($pre_wk as $line) {
	array_push($post_wk,$line);
}

$i=0;
foreach($post_wk as $line) { 
		print "$i: $line\n";
		$i++;
}

?>
