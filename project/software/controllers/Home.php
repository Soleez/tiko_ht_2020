<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  // avataan funktiolla tietokantayhteys
  openConnection();


  // Laskulle kuuluvat tunnit tietokannasta
  $contractorQuery = pg_query("SELECT * FROM contractor
  ");
  // haetaan funktion avulla
  $contractors = getTable($contractorQuery);


  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>
