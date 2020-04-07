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
  $hoursQuery = pg_query("SELECT * FROM vw_hours
    WHERE contract_id = {$contract[0]}
  ");
  // haetaan funktion avulla
  $hours = getTable($hoursQuery);

  // Haetaan laskujen tietoja
  $billsQuery = pg_query("SELECT * FROM vw_bills
    WHERE contract_id = {$bill[0]}
  ;");
  $bills = getTable($billsQuery);

  // Talletetan kysely muuttujaan
  $toolsQuery = pg_query("SELECT * FROM vw_tools
    WHERE contract_id = {$contract[0]}
  ;");
  $tools = getTable($toolsQuery);

  // Työkalujen summa
  $toolsumQuery = pg_query("SELECT * FROM toolsum_function({$contract[0]});");
  $toolsum = getRow($toolsumQuery);

  // Työtuntien summa
  $worksumQuery = pg_query("SELECT * FROM worksum_function({$contract[0]});");
  $worksum = getRow($worksumQuery);  


  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>
