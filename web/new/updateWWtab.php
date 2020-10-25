<?php
  $validProd = file(\$_SERVER['DOCUMENT_ROOT'].'/new/SPCProducts/validProducts.txt');
  $matches = preg_grep('/^WW[0-9][0-9][0-9][0-9]/', $validProd);
  echo "<h2>WWs:".count($matches)."</h2>";
?>
