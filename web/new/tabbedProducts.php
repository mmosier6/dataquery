<div class="tabbertab">
  <h2>All Products</h2>
</div>

<div class="tabbertab">
  <?php
    $validProd = file("./SPCProducts/validProducts.txt");
    $matches = preg_grep('/^WW[0-9][0-9][0-9][0-9]/', $validProd);
    echo "<h2>WWs:".count($matches)."</h2>";
  ?>
</div>

<div class="tabbertab">
  <?php
    $validProd = file("./SPCProducts/validProducts.txt");
    $matches = preg_grep('/^MCD[0-9][0-9][0-9][0-9]/', $validProd);
    echo "<h2>MDs:".count($matches)."</h2>";
  ?>
</div>

<div class="tabbertab">
  <h2>Outlooks</h2>
</div>

<div class="tabbertab">
  <h2>Fire</h2>
</div>