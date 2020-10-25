<?php

function showHeader($title, $srcRoot, $cwiDir, $newDir) {

	$lines = file("$cwiDir/header-min.txt");
	foreach($lines as $st) {
		if (strpos($st, "|||") !== false) {
			print str_replace("|||TITLE|||", $title, $st);
		}elseif(strpos($st, "<cwiDir>") !== false){
			print str_replace("<cwiDir>", $cwiDir, $st);
		}elseif(strpos($st, "<newDir>") !== false){
			print str_replace("<newDir>", $newDir, $st);
		}else {
			print $st;
			}
		}

	//include("$newDir/MainMenu/topMenu.html");

	$lines = file("$newDir/MainMenu/topMenu.txt");
	foreach($lines as $st){
		if(strpos($st, "<cwiDir>") !== false){
			print str_replace("<cwiDir>", $cwiDir, $st);
		}elseif(strpos($st, "<newDir>") !== false){
			print str_replace("<newDir>", $newDir, $st);
		}else {
			print $st;
		}
	}

}

function showFooter($srcRoot, $cwiDir, $newDir) {
	$lines = file("$cwiDir/footer.txt");
	foreach($lines as $st){
		if(strpos($st, "<cwiDir>") !== false){
			print str_replace("<cwiDir>", $cwiDir, $st);
		}elseif(strpos($st, "<newDir>") !== false){
			print str_replace("<newDir>", $newDir, $st);
		}else {
			print $st;
		}
	}
	//include("$cwiDir/footer.txt");
}
?>
