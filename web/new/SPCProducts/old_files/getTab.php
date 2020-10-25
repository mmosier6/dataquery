<?php

if ($_GET['content'] == 1) {
	$products  = file('./AllProd.html');
	foreach ($products as $line) {
  		print "$line\n";
	}

} 
if ($_GET['content'] == 2) { 
	$pwo  = file('./WWprod.html');
	foreach ($pwo as $line) {
  		print "$line\n";
	}
} 
if ($_GET['content'] == 3) { 
	$ww = file('./MDprod.html');
	foreach ($ww as $line) {
                print "$line\n";
        }
}
if ($_GET['content'] == 4) { 
	$md = file('./OTLKprod.html');
	foreach ($md as $line) {
                print "$line\n";
        }
}
if ($_GET['content'] == 5) { 
	$otlk = file('./FIREprod.html');
	foreach ($otlk as $line) {
                print "$line\n";
        }

}
?>
