<?php
  /** 
   * Asetetaan luokka, joka pitää kirjaa valinnoista, joiden perusteella ohjelmassa on
   */

  session_start();

  $_SESSION['contractor_id'] = 1;
  $_SESSION['customer_id'] = 2;
  $_SESSION['project_id'] = 2;
  $_SESSION['contract_id'] = 3;
  $_SESSION['bill_id'] = 2;

  //$_SESSION['contract_id'] = $_POST['contract_id'];


  /** Haetaan tietokannasta contractorin tiedot */
  function getContractor() {
    // Haetaan sessioon liittyvää dataa
    $contractorQuery = pg_query("SELECT * 
      FROM contractor
      WHERE contractor_id = {$_SESSION['contractor_id']};  
    ");
    // Talletetaan kyselyn tulos taulukkoon
    $contractor = getRow($contractorQuery);
    return $contractor;
  }

  /** Haetaan tietokannasta contractorin tiedot */
  function getCustomer() {
    $customerQuery = pg_query("SELECT * 
      FROM customer
      WHERE customer_id = {$_SESSION['customer_id']};  
    ");
    $customer = getRow($customerQuery);
    return $customer;
  }


?>
