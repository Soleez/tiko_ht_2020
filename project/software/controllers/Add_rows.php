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

  $selected_contract = intval($contract[0]);

  /* Haetaan työtyypit
  $worktypesQuery = pg_query("SELECT work_type_id, work_type_name FROM Work_type;");
  $worktypes = getTable($worktypesQuery);
  */
    
// Kun lisättävät tiedot annettu
if (isset($_POST['formSubmit1']))
{
    $selected_tool1 = intval($_POST['tool']);
    // $selected_work = intval($_POST['work']);

    $amount_of_tools1 = intval($_POST['amount']);
    // $amount_of_work = intval($_POST['amount2']);

    $sale1 = intval($_POST['sale']);
    // $sale2 = intval($_POST['sale2']);

    $currentdate = pg_escape_string(date('Y-m-d'));

    // Tietojen tarkistus
    $valid_info = (($selected_tool1 != 0 && $amount_of_tools1 > 0) 
/*|| ($selected_work != 0 && $amount_of_work > 0)*/);

    if ($valid_info) {
        if ($selected_tool1 != 0 && $amount_of_tools1 > 0) {
            echo 'yritetään lisätä: selected_contract: ' . $selected_contract 
            . ', selectedtool1: ' . $selected_tool1 . ', amount_of_tools1: ' . $amount_of_tools1;
            

            /*
            $query1 = "INSERT INTO Sold_tool VALUES (DEFAULT, '$selected_tool1', '$amount_of_tools1', '$selected_contract', '$currentdate', '$sale1');";
            $update1 = update($query1);
            
            // asetetaan viesti-muuttuja lisäämisen onnistumisen mukaan
            // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
    
            if ($update1 && (pg_affected_rows($update1) > 0)) {
                $viesti = 'Tarvikkeet kirjattu!';
            }
            else {
                $viesti = 'Tarvikkeita ei lisätty: ' . pg_last_error();
            }
            */
        }

        /*
        if ($selected_work != 0 && $amount_of_work > 0) {
            $query2 = "INSERT INTO Billable_hour VALUES (DEFAULT, '$selected_work', '$selected_contract', '$currentdate', '$amount_of_work', '$sale2');";
            $update2 = update($query2);
            
            // asetetaan viesti-muuttuja lisäämisen onnistumisen mukaan
            // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
    
            if ($update2 && (pg_affected_rows($update2) > 0)) {
                $viesti2 = 'Työtunnit kirjattu!';
            }
            else {
                $viesti2 = 'Työtunteja ei lisätty: ' . pg_last_error();
            }
        }
        */
    }
    else {
        /*
        if ($selected_contract == 0) {
            $viesti3 = 'Valitse asiakas ja työkohde!';
        }
        */
        /* else if ($selected_tool == 0) {
            $viesti = 'Valitse tarvike!';
        }
        else if ($amount_of_tools <= 0) {
            $viesti = 'Lisättävien tarvikkeiden määrän oltava enemmän kuin 0.';
        } */
        // $viesti3 = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
        $viesti3 = 'Virhe. Contract: ' . $selected_contract . ',selectedtool1: ' . $selected_tool1 . ',amount_of_tools1: ' . $amount_of_tools1;
        
    }
}

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>