<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  // avataan funktiolla tietokantayhteys
  openConnection();
  
  // haetaan sessiolle id url:n perusteella
  setCustomer($_GET['customer']);
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
    LEFT OUTER JOIN contract_type 
      ON contract.contract_type_id = contract_type.contract_type_id
    WHERE project_id = {$project[0]}
  ");
  // haetaan funktion avulla
  $contracts = getTable($contractQuery);

  // Laskulle kuuluvat tunnit tietokannasta
  $billQuery = pg_query("SELECT * FROM bill
    LEFT OUTER JOIN bill_type 
      ON bill.bill_type_id = bill_type.bill_type_id
    LEFT OUTER JOIN bill_status 
      ON bill.bill_status_id = bill_status.bill_status_id
    WHERE contract_id IN (
      SELECT contract_id FROM contract
      WHERE project_id = {$project[0]}
    ) 
  ");

  // haetaan funktion avulla
  $bills = getTable($billQuery);

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>
