<?php
  /** 
   * Asetetaan luokka, joka pitää kirjaa valinnoista, joiden perusteella ohjelmassa on navigoitu
   * luokalla voi asettaa sessiolle muuttujia, sekä hakea sessioon liittyiä rivitietoja tietokannasta
   */

  session_start();  

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
    $_SESSION['project_id'] = $id;
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
