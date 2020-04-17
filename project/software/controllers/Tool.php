<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  // avataan funktiolla tietokantayhteys
  openConnection();
  

  // Nimetty yksikössä $tool, koska $tools oli jo käytössä muualla.

  // Haetaan työkalut + veroasteet
  $toolQuery = pg_query("SELECT * FROM tool JOIN vat_type ON tool.vat_type_id = vat_type.vat_type_id
  ");
  // haetaan funktion avulla
  $tool = getTable($toolQuery);

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>
