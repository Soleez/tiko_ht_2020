<?php

  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');
  
  $bill = new Session;

  openConnection();
  
  $opiskelijat = pg_query("SELECT * FROM opiskelija");

  if (!$opiskelijat) {
    echo "Virhe kyselyssä.\n";
    exit;
  }
  while ($rivi = pg_fetch_row($opiskelijat)) {
    echo "Opiskelijan $rivi[0]  numero on $rivi[1]";
    echo "<br />\n";
  }
  
  closeConnection();
  
?>

