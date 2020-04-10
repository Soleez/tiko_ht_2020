<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  // avataan funktiolla tietokantayhteys
  openConnection();

  // haetaan urlista
  setContract($_GET['contract']);
  
  // haetaan urakoitisijan tiedot
  $contractor = getContractor();
  // haetaan sopimuksen tiedot
  $customer = getCustomer();
  // haetaan sopimuksen tiedot
  $project = getProject();
  // haetaan sopimuksen tiedot
  $contract = getContract();
  // haetaan sopimuksen tiedot
  $bill = getBill();
  

  // Laskulle kuuluvat tunnit tietokannasta
  $hoursQuery = pg_query("SELECT * FROM vw_hours
    WHERE contract_id = {$contract[0]}
  ");
  // haetaan funktion avulla
  $hours = getTable($hoursQuery);


  // Talletetan kysely muuttujaan
  $toolsQuery = pg_query("SELECT * FROM vw_tools
    WHERE contract_id = {$contract[0]}
  ;");
  $tools = getTable($toolsQuery);

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>
