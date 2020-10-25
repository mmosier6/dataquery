<?php
  $validProd = file($_SERVER['DOCUMENT_ROOT'].'/new/SPCProducts/validProductszz.txt');
  $MDmatches = preg_grep('/^MCD[0-9][0-9][0-9][0-9]/', $validProd);

  //print "<div class='tabbertab' id='mdtab' title='"."MDs:".count($MDmatches)."'></div>\n";
  print "MDs:".count($MDmatches);
?>
