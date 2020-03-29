<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  // avataan funktiolla tietokantayhteys
  openConnection();
  
  // haetaan urakoitisijan tiedot
  $contractor = getContractor();

  // Laskulle kuuluvat tunnit tietokannasta
  $hoursQuery = pg_query("SELECT * FROM vw_hours;
    --WHERE contract_id = {$_SESSION['contract_id']}
  ");
  // haetaan funktion avulla
  $hours = getTable($hoursQuery);


  // Talletetan kysely muuttujaan
  $toolsQuery = pg_query("SELECT * FROM vw_tools
    --WHERE contract_id = {$_SESSION['contract_id']}
  ;");
  $tools = getTable($toolsQuery);

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>
