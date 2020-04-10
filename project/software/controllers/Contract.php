<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  // avataan funktiolla tietokantayhteys
  openConnection();
  
  // haetaan sessiolle id url:n perusteella
  setProject($_GET['project']);

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
  $contractQuery = pg_query("SELECT * FROM contract
     WHERE project_id = {$project[0]}
  ");
  // haetaan funktion avulla
  $contracts = getTable($contractQuery);

  // Laskulle kuuluvat tunnit tietokannasta
  $billQuery = pg_query("SELECT * FROM bill
  -- WHERE contract_id = {$contract[0]}
  ");
  // haetaan funktion avulla
  $bills = getTable($billQuery);

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>
