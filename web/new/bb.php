<?php
       $validProd = file("./SPCProducts/validProducts.txt");
       $matches = preg_grep('/^WW/', $validProd);
       echo "<h2>WWs-";
       print_r(count($matches));
       echo "</h2>";
?>

