<?php
  $validProd = file('./SPCProducts/validProductszz.txt');
  $MDmatches = preg_grep('/^MCD[0-9][0-9][0-9][0-9]/', $validProd);

  //print "<div class='tabbertab' title='"."MDs:".count($MDmatches)."'></div>\n";
  print "MDs:".count($MDmatches);
?>
