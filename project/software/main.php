<?php

  /** Luodaan funktio, jolla saadaan yhteys tietokantaan */
  function openConnection() {
    // Haetaan configuraatio tietokantaan
    include (__DIR__.'/config/DbConnection.php');

    // Avataan yhteys tietokantaan
    if (!$connection = pg_connect($DbConnectionString)) {
        die("Tietokantayhteyden luominen epäonnistui.");
    }
  }

  /** Funktio sulkee tietokantayhteyden */
  function closeConnection() {
    pg_close($connection);
  }

  // ToDo Virheiden käsittely kyselyn aikana

?>
