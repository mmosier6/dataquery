<?php
  $validProd = file('./SPCProducts/validProductszz.txt');
  $WWmatches = preg_grep('/^WW[0-9][0-9][0-9][0-9]/', $validProd);

  //print "<div class='tabbertab' title='"."WWs:".count($WWmatches)."'></div>\n";
  print "WWs:".count($WWmatches);
?>
