<?php

  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');
  
  $bill = new Session;

  openConnection();
  
  // Talletetan kyselymuuttujaan
  $toolsQuery = pg_query("SELECT * FROM vw_tools;");

  // Käsitellään virhe
  if (!$toolsQuery) {
    echo "Virhe kyselyssä.\n";
    exit;
  }
  else {
    // Talletetaan kyselyn tulos taulukkoon
    $tools = pg_fetch_all($toolsQuery);
  }


  $hoursQuery = pg_query("SELECT * FROM vw_hours;");

  if (!$hoursQuery) {
    echo "Virhe kyselyssä.\n";
    exit;
  }
  else {
    $hours = pg_fetch_all($hoursQuery);
  }
  
  closeConnection();
  
?>

