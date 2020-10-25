<?php 
#######################################################
#
# Basic SPC WebPage Template
# Uses 2012 NWS/NCEP/SPC Corporate Web Image
#
#######################################################
# Page Title
$pageTitle = "Public Weather Page";

$srcRoot = $_SERVER['DOCUMENT_ROOT']; 
if ($srcRoot == "") $srcRoot = "/local/spcwebsite";

require("${srcRoot}/cwi/SPCCorporateWebImage_pwp.php");
showHeader($pageTitle, $srcRoot); 
?>


<!-- ############################################################################## -->
<!-- ######################### BEGIN MAIN PAGE CONTENT ############################ -->
<br><br>
<?php include ("./pwp.html") ?>
<br>
<!-- ##### Social Media content ##### -->
<div class="feature_container">
<div>
  <?php include($_SERVER['DOCUMENT_ROOT']."/new/PanelContent/panelContentSM.php"); ?>
</div>
</div><!-- Social Media content -->
<!-- ######################### END MAIN PAGE CONTENT ############################ -->
<!-- ############################################################################## -->

<?php showFooter($srcRoot); ?>
