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

?>
