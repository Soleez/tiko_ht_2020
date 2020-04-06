<?php
  /** 
   * Asetetaan luokka, joka pitää kirjaa valinnoista, joiden perusteella ohjelmassa on navigoitu
   */

  session_start();
  
  //$_SESSION['contractor_id'] = 1;
  //$_SESSION['customer_id'] = 3;
  //$_SESSION['project_id'] = 5;
  //$_SESSION['contract_id'] = 5;
  //$_SESSION['bill_id'] = 2;
  

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

  /** asettaa urakoitsijan */
  function setContractor($id) {
    $_SESSION['contractor_id'] = $id;
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
  /** asettaa asiakkaan ID:n sessiolle */
  function setCustomer($id) {
    $_SESSION['customer_id'] = $id;
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

  /** asettaa asiakkaan ID:n sessiolle */
  function setProject($id) {
    var_dump($id); die;
    $_SESSION['project_id'] = $_POST[$id];
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

  /** asettaa asiakkaan ID:n sessiolle */
  function setContract($id) {
    $_SESSION['contract_id'] = $id;
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

  /** asettaa asiakkaan ID:n sessiolle */
  function setBill($id) {
    $_SESSION['bill_id'] = $id;
  }

?>
