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


  /** Luodaan tietokantafunktio jolla saadaan haettua tietokantataulu 
   * funktio pg_fetch_all() nimeäää taulukoiden sarakkeet samannimisiksi kuin
   * ne ovat tietokannassa
  */
  function getTable($query) {
    // Käsitellään virhe
    if (!$query) {
      echo "Virhe kyselyssä.\n";
      return array();
    }
    else {
      // Talletetaan kyselyn tulos taulukkoon
      $table = pg_fetch_all($query);
      return $table;
    }
  }

  /** Luodaan tietokantafunktio jolla saadaan haettua tietokantataulun rivi 
   * rivin arvoja ei ole nimetty, vaan niitä voidaan kutsua sarkkeen indeksillä
  */
  function getRow($query) {
    // Käsitellään virhe
    if (!$query) {
      echo "Virhe kyselyssä.\n";
      return array();
    }
    else {
      // Talletetaan kyselyn tulos taulukkoon
      $row = pg_fetch_row($query);
      return $row;
    }
  }
  
  /** Luodaan tietokantafunktion, jolla päivitetään $query tietokantaan. */
  function update($query) {
    if (!$query) {
      echo "Virhe kyselyssä.\n";
      return array();
    }
    else {
      $update = pg_query($query);
      return $update;
    }
  }

?>
