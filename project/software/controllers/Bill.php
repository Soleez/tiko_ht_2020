<?php

  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');
  
  $bill = new Session;

  openConnection();
  
  $bills = pg_query("SELECT * FROM Bill");

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

