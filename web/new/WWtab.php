  <div class="tabbertab" id="WWtab">
    <?php
      $validProd = file("./SPCProducts/validProducts.txt");
      $matches = preg_grep('/^WW[0-9][0-9][0-9][0-9]/', $validProd);
      echo "<h2>WWs:".count($matches)."</h2>";
    ?>
  </div>
