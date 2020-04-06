<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  // avataan funktiolla tietokantayhteys
  openConnection();
  setContractor(1);

  // haetaan urakoitisijan tiedot
  $contractor = getContractor();
  

  // Laskulle kuuluvat tunnit tietokannasta
  $customerQuery = pg_query("SELECT * FROM customer
   WHERE contractor_id = {$contractor[0]}
  ");
  // haetaan funktion avulla
  $customers = getTable($customerQuery);

  
  // Laskulle kuuluvat tunnit tietokannasta
  $projectQuery = pg_query("SELECT * FROM project
    WHERE customer_id IN (
      SELECT customer_id FROM customer
      WHERE contractor_id = {$contractor[0]}
    )
  ");
  // haetaan funktion avulla
  $projects = getTable($projectQuery);


  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>
