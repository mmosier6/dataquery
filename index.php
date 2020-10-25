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

$srcRoot = $_SERVER['DOCUMENT_ROOT'];
if ($srcRoot == "") $srcRoot = getcwd()."/web/";
$srcRoot = getcwd()."/web/";

require("${srcRoot}/cwi/SPCCorporateWebImage-min.php");
showHeader($pageTitle, $srcRoot);
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
	showFooter($srcRoot);
?>
