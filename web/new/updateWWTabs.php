<?php
  $validProd = file($_SERVER['DOCUMENT_ROOT'].'/new/SPCProducts/validProductszz.txt');
  $WWmatches = preg_grep('/^WW[0-9][0-9][0-9][0-9]/', $validProd);

  print "WWs:".count($WWmatches);
?>
