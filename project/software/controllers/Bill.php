<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  openConnection();
  
  // Laskulle kuuluvat tunnit tietokannasta
  $hoursQuery = pg_query("SELECT * FROM vw_hours;
    --WHERE contract_id = {$_SESSION['contract_id']}
  ");

  if (!$hoursQuery) {
    echo "Virhe kyselyssä.\n";
    exit;
  }
  else {
    $hours = pg_fetch_all($hoursQuery);
  }


  // Talletetan kysely muuttujaan
  $toolsQuery = pg_query("SELECT * FROM vw_tools
    --WHERE contract_id = {$_SESSION['contract_id']}
  ;");

  // Käsitellään virhe
  if (!$toolsQuery) {
    echo "Virhe kyselyssä.\n";
    exit;
  }
  else {
    // Talletetaan kyselyn tulos taulukkoon
    $tools = pg_fetch_all($toolsQuery);
  }

  
  // Haetaan sessioon liittyvää dataa
  $contractorQuery = pg_query("SELECT contactor_id, contractor_name 
    FROM contractor
    WHERE contractor_id = {$_SESSION['contractor_id']};  
  ");
    // Käsitellään virhe
    if (!$contractorQuery) {
      $contractor = "tuntematon";
    }
    else {
      // Talletetaan kyselyn tulos taulukkoon
      $contractor = pg_fetch_row($contractorQuery);
    }

  
  closeConnection();
  
?>
