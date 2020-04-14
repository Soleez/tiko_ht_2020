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
    // Valittujen tarvikkeiden id:t.
    $selected_tool1 = intval($_POST['tool1']);
    $selected_tool2 = intval($_POST['tool2']);
    $selected_tool3 = intval($_POST['tool3']);
    
    // Tarvikkeiden ja työtuntien määrät. 
    $amount_of_tools1 = intval($_POST['tool_amount1']);
    $amount_of_tools2 = intval($_POST['tool_amount2']);
    $amount_of_tools3 = intval($_POST['tool_amount3']);
    $amount_of_work1 = intval($_POST['work_amount1']);
    $amount_of_work2 = intval($_POST['work_amount2']);
    $amount_of_work3 = intval($_POST['work_amount3']);

    // Alennusprosentit.
    $tool_sale1 = intval($_POST['tool_sale1']);
    $tool_sale2 = intval($_POST['tool_sale2']);
    $tool_sale3 = intval($_POST['tool_sale3']);
    $work_sale1 = intval($_POST['work_sale1']);
    $work_sale2 = intval($_POST['work_sale2']);
    $work_sale3 = intval($_POST['work_sale3']);

    $currentdate = pg_escape_string(date('Y-m-d'));

    // Tietojen tarkistus
    $valid_info = $selected_contract != 0 && (($selected_tool1 != 0 && $amount_of_tools1 > 0) || ($selected_tool2 != 0 && $amount_of_tools2 > 0) || ($selected_tool3 != 0 && $amount_of_tools3 > 0) 
    || $amount_of_work1 > 0 || $amount_of_work2 > 0 || $amount_of_work3 > 0);

    if ($valid_info) {
        if ($selected_tool1 != 0 && $amount_of_tools1 > 0) {

            $t_query1 = "INSERT INTO Sold_tool VALUES (DEFAULT, '$selected_tool1', '$amount_of_tools1', '$selected_contract', '$currentdate', '$tool_sale1');";
            $t_update1 = pg_query($t_query1);

            // asetetaan msg1-muuttuja lisäämisen onnistumisen mukaan
            // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
    
            if ($t_update1 && (pg_affected_rows($t_update1) > 0)) {
                $t_msg1 = 'Tarvike kirjattu!';
            }
            else {
                $t_msg1 = 'Tarvikkeita ei lisätty: ' . pg_last_error();
            }
        }

        if ($selected_tool2 != 0 && $amount_of_tools2 > 0) {

            $t_query2 = "INSERT INTO Sold_tool VALUES (DEFAULT, '$selected_tool2', '$amount_of_tools2', '$selected_contract', '$currentdate', '$tool_sale2');";
            $t_update2 = pg_query($t_query2);

            // asetetaan msg1-muuttuja lisäämisen onnistumisen mukaan
            // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
    
            if ($t_update2 && (pg_affected_rows($t_update2) > 0)) {
                $t_msg2 = 'Tarvike kirjattu!';
            }
            else {
                $t_msg2 = 'Tarvikkeita ei lisätty: ' . pg_last_error();
            }
        }

        if ($selected_tool3 != 0 && $amount_of_tools3 > 0) {

            $t_query3 = "INSERT INTO Sold_tool VALUES (DEFAULT, '$selected_tool3', '$amount_of_tools3', '$selected_contract', '$currentdate', '$tool_sale3');";
            $t_update3 = pg_query($t_query3);

            // asetetaan msg1-muuttuja lisäämisen onnistumisen mukaan
            // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
    
            if ($t_update3 && (pg_affected_rows($t_update3) > 0)) {
                $t_msg3 = 'Tarvike kirjattu!';
            }
            else {
                $t_msg3 = 'Tarvikkeita ei lisätty: ' . pg_last_error();
            }
        }

        // Work_type_id 1 = suunnittelu, 2 = asennustyö, 3 = aputyö
        
        if ($amount_of_work1 > 0) {
            $w_query1 = "INSERT INTO Billable_hour VALUES (DEFAULT, 1, '$selected_contract', '$currentdate', '$amount_of_work1', '$work_sale1');";
            $w_update1 = pg_query($w_query1);
            
            // asetetaan msg2-muuttuja lisäämisen onnistumisen mukaan
            // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
    
            if ($w_update1 && (pg_affected_rows($w_update1) > 0)) {
                $w_msg1 = 'Työtunnit kirjattu!';
            }
            else {
                $w_msg1 = 'Työtunteja ei lisätty: ' . pg_last_error();
            }
        }

        if ($amount_of_work2 > 0) {
            $w_query2 = "INSERT INTO Billable_hour VALUES (DEFAULT, 2, '$selected_contract', '$currentdate', '$amount_of_work2', '$work_sale2');";
            $w_update2 = pg_query($w_query2);
            
            // asetetaan msg2-muuttuja lisäämisen onnistumisen mukaan
            // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
    
            if ($w_update2 && (pg_affected_rows($w_update2) > 0)) {
                $w_msg2 = 'Työtunnit kirjattu!';
            }
            else {
                $w_msg2 = 'Työtunteja ei lisätty: ' . pg_last_error();
            }
        }

        if ($amount_of_work3 > 0) {
            $w_query3 = "INSERT INTO Billable_hour VALUES (DEFAULT, 3, '$selected_contract', '$currentdate', '$amount_of_work3', '$work_sale3');";
            $w_update3 = pg_query($w_query3);
            
            // asetetaan msg2-muuttuja lisäämisen onnistumisen mukaan
            // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
    
            if ($w_update3 && (pg_affected_rows($w_update3) > 0)) {
                $w_msg3 = 'Työtunnit kirjattu!';
            }
            else {
                $w_msg3 = 'Työtunteja ei lisätty: ' . pg_last_error();
            }
        }

    }

    // Virheentarkastelu ilmoittaa virheestä vain kun yhtään riviä ei onnistuta lisäämään.

    else {
        if ($selected_contract == 0) {
            $err_msg = 'Asiakasta ei ole valittu!';
        }
        /* else if ($selected_tool == 0) {
            $viesti = 'Valitse tarvike!';
        }
        else if ($amount_of_tools <= 0) {
            $viesti = 'Lisättävien tarvikkeiden määrän oltava enemmän kuin 0.';
        } */
        else {
            $err_msg = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
        }
    }
}

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>