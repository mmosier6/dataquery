<?php

function showHeader($title, $srcRoot) {

	$lines = file("$srcRoot/cwi/header-min.txt");
	foreach($lines as $st) {
		if (strpos($st, "|||") !== false) {
			print str_replace("|||TITLE|||", $title, $st);
			}
		else {
			print $st;
			}
		}
	include("$srcRoot/new/MainMenu/topMenu.html");
	}

function showFooter($srcRoot) {
	include("$srcRoot/cwi/footer.txt");
	}
?>
