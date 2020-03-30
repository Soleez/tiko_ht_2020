<?php
  /** 
   * Asetetaan luokka, joka pitää kirjaa valinnoista, joiden perusteella ohjelmassa on navigoitu
   */

  session_start();
  
  $_SESSION['contractor_id'] = 1;
  $_SESSION['customer_id'] = 3;
  $_SESSION['project_id'] = 5;
  $_SESSION['contract_id'] = 5;
  $_SESSION['bill_id'] = 2;
  
  class createSession {
    // haetaan urakoitisijan tiedot
    //var $sContractor = getContractor();
    //// haetaan asiakkaan tiedot
    //var $customer = getCustomer();
    //// haetaan projektin tiedot
    //var $project = getProject();
    //// haetaan sopimuksen tiedot
    //var $contract = getContract();
    //// haetaan laskun tiedot
    //var $bill = getBill();
  } 


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

  /** Haetaan tietokannasta asiakkaan tiedot */
  function getCustomer() {
    $customerQuery = pg_query("SELECT * 
      FROM customer
      WHERE customer_id = {$_SESSION['customer_id']};  
    ");
    $customer = getRow($customerQuery);
    return $customer;
  }

  /** Haetaan tietokannasta projektin tiedot */
  function getProject() {
    $projectQuery = pg_query("SELECT * 
      FROM project
      WHERE project_id = {$_SESSION['project_id']};  
    ");
    $project = getRow($projectQuery);
    return $project;
  }

  /** Haetaan tietokannasta spimuksen tiedot */
  function getContract() {
    $contractQuery = pg_query("SELECT * 
      FROM contract
      WHERE contract_id = {$_SESSION['contract_id']};  
    ");
    $contract = getRow($contractQuery);
    return $contract;
  }

  /** Haetaan tietokannasta laskun tiedot */
  function getBill() {
    $billQuery = pg_query("SELECT * 
      FROM bill
      WHERE bill_id = {$_SESSION['bill_id']};  
    ");
    $bill = getRow($billQuery);
    return $bill;
  }

?>
