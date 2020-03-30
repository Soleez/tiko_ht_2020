<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  // avataan funktiolla tietokantayhteys
  openConnection();
  
  
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
  

  /*
  $sessionNow = new Session;
  $sopimus = $sessionNow->$contract;
  var_dump($sessionNow); die;
  */

  // Laskulle kuuluvat tunnit tietokannasta
  $contractQuery = pg_query("SELECT * FROM contract
    -- WHERE contract_id = {$contract[0]}
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
