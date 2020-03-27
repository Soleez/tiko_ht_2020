<?php

  include(__DIR__.'/../main.php');
  openConnection();
  
  $bills = pg_query("SELECT * FROM bill");

  if (!$bills) {
    echo "Virhe kyselyssä.\n";
    exit;
  }
  while ($bill = pg_fetch_row($bills)) {
    echo "Opiskelijan $bill[0]  numero on $bill[1]";
    echo "<br />\n";
  }
  
  closeConnection();
  
?>

