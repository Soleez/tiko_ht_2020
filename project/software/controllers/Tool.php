<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  // avataan funktiolla tietokantayhteys
  openConnection();
  
  
  // Tarvitaankohan näitä? Jätin mukaan vielä tässä vaiheessa.

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

  // Nimetty yksikössä $tool, koska $tools oli jo käytössä muualla.

  // Haetaan työkalut + veroasteet
  $toolQuery = pg_query("SELECT * FROM tool JOIN vat_type ON tool.vat_type_id = vat_type.vat_type_id
  ");
  // haetaan funktion avulla
  $tool = getTable($toolQuery);

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>
