  <div class="tabbertab" id="MDtab">
    <?php
      $validProd = file($_SERVER['DOCUMENT_ROOT']."/new/SPCProducts/validProducts.txt");
      $matches = preg_grep('/^MCD[0-9][0-9][0-9][0-9]/', $validProd);
      echo "<h2>MDs:".count($matches)."</h2>";
    ?>
  </div>
