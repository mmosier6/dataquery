<?php 
#######################################################
#
# Basic SPC WebPage Template
# Uses 2012 NWS/NCEP/SPC Corporate Web Image
#
#######################################################
# Page Title
$pageTitle = "Place Name Here";

$srcRoot = $_SERVER['DOCUMENT_ROOT']; 
if ($srcRoot == "") $srcRoot = "/local/spcwebsite";
require("${srcRoot}/cwi/SPCCorporateWebImage.php");
showHeader($pageTitle, $srcRoot); 
?>

<!-- ############################################################################## -->
<!-- ######################### BEGIN MAIN PAGE CONTENT ############################ -->
<br><br>
Place Content Here
<br><br>
<!-- ######################### END MAIN PAGE CONTENT ############################ -->
<!-- ############################################################################## -->

<?php showFooter($srcRoot); ?>
