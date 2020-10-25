<?php
  $validProd = file('./SPCProducts/validProducts.txt');
  $matches = preg_grep('/^MCD[0-9][0-9][0-9][0-9]/', $validProd);
  echo "<h2>MDs:".count($matches)."</h2>";
?>
