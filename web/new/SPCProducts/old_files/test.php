<?php

$otlk = file("/local/spcwebsite/products/mx-ac1.html");

$results = system('/bin/grep "See Text" /local/spcwebsite/products/mx-ac1.html');

if (empty($results)) { print "no\n"; }

else { print "result\n"; }

?>
