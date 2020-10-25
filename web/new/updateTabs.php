<?php
  $validProd = file($_SERVER['DOCUMENT_ROOT'].'/new/SPCProducts/validProductszz.txt');
  $WWmatches = preg_grep('/^WW[0-9][0-9][0-9][0-9]/', $validProd);
  $MDmatches = preg_grep('/^MCD[0-9][0-9][0-9][0-9]/', $validProd);

  print "<div class='tabbertab' title='All Products'></div>\n";
  print "<div class='tabbertab' id='wwtab' title='"."WWs:".count($WWmatches)."'></div>\n";
  print "<div class='tabbertab' id='mdtab' title='"."MDs:".count($MDmatches)."'></div>\n";
  print "<div class='tabbertab' title='Outlooks'></div>\n";
  print "<div class='tabbertab' title='Fire'></div>\n";
?>
