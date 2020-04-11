<?php

  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');
  include(__DIR__.'../views/add_rows.php');

  // avataan funktiolla tietokantayhteys
  openConnection();

  /** Jätän otto sulle loput korjaukseen, varmisitn vain että tuo sessio toimii
    * alla oleva vardump() die funktiot auttavat eniten PHP:ssa arvojen debuggaamisessa.
    *
    * var_dump($_GET['contract']); die;
    *
    * Lisäsin NULL tarkistuksen, tuohon Contractin asetukseen, sillä $_GET yrittää hakea sopimusta
    * URL:sta, mutta se on NULL lomakkeen postauksen jälkeen. rivi 25
    * 
    * Lisäksi nuo Contractin Getterit palauttavat taulukon, joten asetan taulukon ensimmäisen arvon
    * selected_contract muuttujaan. rivi 36
    */

  // haetaan urlista
  if( isset($_GET['contract'])) {
    setContract($_GET['contract']);
  }

  // haetaan sopimuksen tiedot
  $contract = getContract();

  // Haetaan työkalut
  $toolsQuery = pg_query("SELECT tool_id, tool_name, unit FROM Tool WHERE Tool.bool_in_use = true;");
  $tools = getTable($toolsQuery);

  // Haetaan työtyypit
  $worktypesQuery = pg_query("SELECT work_type_id, work_type_name FROM Work_type;");
  $worktypes = getTable($worktypesQuery);

  $selected_contract = intval($contract[0]);
    
// Kun lisättävät tiedot annettu
if (isset($_POST['formSubmit1']))
{
    $selected_tool1 = intval($_POST['tool1']);
    $selected_work1 = intval($_POST['work1']);

    $amount_of_tools1 = intval($_POST['tool_amount1']);
    $amount_of_work1 = intval($_POST['work_amount1']);

    $tool_sale1 = intval($_POST['tool_sale1']);
    $work_sale1 = intval($_POST['work_sale1']);

    $currentdate = pg_escape_string(date('Y-m-d'));

    // Tietojen tarkistus
    $valid_info = $selected_contract != 0 && (($selected_tool1 != 0 && $amount_of_tools1 > 0) || ($selected_work1 != 0 && $amount_of_work1 > 0));

    /*
    var_dump($selected_tool1, $amount_of_tools1, $tool_sale1, $selected_contract);
    var_dump($selected_work1, $amount_of_work1, $work_sale1, $selected_contract); die;
    */

    if ($valid_info) {
        if ($selected_tool1 != 0 && $amount_of_tools1 > 0) {

            $query1 = "INSERT INTO Sold_tool VALUES (DEFAULT, '$selected_tool1', '$amount_of_tools1', '$selected_contract', '$currentdate', '$tool_sale1');";
            $update1 = pg_query($query1);

            // asetetaan msg1-muuttuja lisäämisen onnistumisen mukaan
            // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
    
            if ($update1 && (pg_affected_rows($update1) > 0)) {
                $msg1 = 'Tarvikkeet kirjattu!';
            }
            else {
                $msg1 = 'Tarvikkeita ei lisätty: ' . pg_last_error();
            }
        }

        if ($selected_work1 != 0 && $amount_of_work1 > 0) {
            $query2 = "INSERT INTO Billable_hour VALUES (DEFAULT, '$selected_work1', '$selected_contract', '$currentdate', '$amount_of_work1', '$work_sale1');";
            $update2 = pg_query($query2);
            
            // asetetaan msg2-muuttuja lisäämisen onnistumisen mukaan
            // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
    
            if ($update2 && (pg_affected_rows($update2) > 0)) {
                $msg2 = 'Työtunnit kirjattu!';
            }
            else {
                $msg2 = 'Työtunteja ei lisätty: ' . pg_last_error();
            }
        }

    }
    else {
        if ($selected_contract == 0) {
            $msg3 = 'Asiakasta ei ole valittu!';
        }
        /* else if ($selected_tool == 0) {
            $viesti = 'Valitse tarvike!';
        }
        else if ($amount_of_tools <= 0) {
            $viesti = 'Lisättävien tarvikkeiden määrän oltava enemmän kuin 0.';
        } */
        else {
            $msg3 = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
        }
    }
}

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>