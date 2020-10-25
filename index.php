<?php
	if(isset($_GET["date"])){
		$d = $_GET["date"];
	}

	if(isset($_GET["debug"])){
		$debug = 1;
	}else{
		$debug = 0;
	}

	if(isset($_GET["winter"])){
		$winter = True;
	}

	if(isset($_GET["all"])){
		$all = True;
	}


#######################################################
#
# Basic SPC WebPage Template
# Uses 2012 NWS/NCEP/SPC Corporate Web Image
#
#######################################################
# Page Title
$pageTitle = "Experimental Data Query Page";
$serverDir = '/Users/mattmosier/Development/spc';
$srcRoot = $_SERVER['DOCUMENT_ROOT'];

if ($srcRoot == ""){		//When running from command line
	$srcRoot = $serverDir."/dataquery/web";
}else{									//When running via a server
	$srcRoot = $srcRoot."/dataquery/web";
}

$cwiDir = "./web/cwi";
$newDir = "./web/new";

//require("${srcRoot}/cwi/SPCCorporateWebImage-min.php");
require("$cwiDir/SPCCorporateWebImage-min.php");
showHeader($pageTitle, $srcRoot, $cwiDir, $newDir);
?>

<!-- ############################################################################## -->
<!-- ######################### BEGIN MAIN PAGE CONTENT ############################ -->
<br><br>
<?php
	include 'page.html';
?>
<br><br>
<!-- ######################### END MAIN PAGE CONTENT ############################ -->
<!-- ############################################################################## -->

<?php
	showFooter($srcRoot, $cwiDir, $newDir);
?>
