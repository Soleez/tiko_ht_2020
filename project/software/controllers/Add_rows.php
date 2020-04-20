<?php

  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');
  include(__DIR__.'../views/add_rows.php');

  // avataan funktiolla tietokantayhteys
  openConnection();

  // haetaan urlista
  if( isset($_GET['customer'])) {
    setCustomer($_GET['customer']);
  }

  if( isset($_GET['contract'])) {
    setContract($_GET['contract']);
  }

  if( isset($_GET['project'])) {
    setProject($_GET['project']);
  }

  // haetaan asiakkaan, sopimuksen ja projektin tiedot
  $contractor = getContractor();
  $customer = getCustomer();
  $project = getProject();
  $contract = getContract();

  // Haetaan työkalut
  $toolsQuery = pg_query("SELECT tool_id, tool_name, unit FROM Tool WHERE Tool.bool_in_use = true;");
  $tools = getTable($toolsQuery);

  // Haetaan työtyypit
  $worktypesQuery = pg_query("SELECT work_type_id, work_type_name FROM Work_type;");
  $worktypes = getTable($worktypesQuery);

  // Haetaan sopimukselle kuuluvat laskut
  $activeBillQuery = pg_query("SELECT * FROM bill
        WHERE contract_id = {$contract[0]} 
            AND bill_status = 1
        ;
  ");

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
            $t_update1 = update($t_query1);

            // asetetaan t_msg1-muuttuja lisäämisen onnistumisen mukaan
            // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
    
            if ($t_update1 && (pg_affected_rows($t_update1) > 0)) {
                $t_msg1 = 'Tarvikerivi 1 kirjattu!';
            }
            else {
                $t_msg1 = 'Tarvikerivi 1, ei lisätty: ' . pg_last_error();
            }
        }
        else if (($selected_tool1 != 0 && $amount_of_tools1 == 0) || ($selected_tool1 == 0 && $amount_of_tools1 > 0)) {
            $t_msg1 = 'Tarvikerivi 1: tiedot puutteelliset!';
        }

        if ($selected_tool2 != 0 && $amount_of_tools2 > 0) {

            $t_query2 = "INSERT INTO Sold_tool VALUES (DEFAULT, '$selected_tool2', '$amount_of_tools2', '$selected_contract', '$currentdate', '$tool_sale2');";
            $t_update2 = update($t_query2);
    
            if ($t_update2 && (pg_affected_rows($t_update2) > 0)) {
                $t_msg2 = 'Tarvikerivi 2 kirjattu!';
            }
            else {
                $t_msg2 = 'Tarvikerivi 2, ei lisätty: ' . pg_last_error();
            }
        }
        else if (($selected_tool2 != 0 && $amount_of_tools2 == 0) || ($selected_tool2 == 0 && $amount_of_tools2 > 0)) {
            $t_msg2 = 'Tarvikerivi 2: tiedot puutteelliset!';
        }

        if ($selected_tool3 != 0 && $amount_of_tools3 > 0) {

            $t_query3 = "INSERT INTO Sold_tool VALUES (DEFAULT, '$selected_tool3', '$amount_of_tools3', '$selected_contract', '$currentdate', '$tool_sale3');";
            $t_update3 = update($t_query3);
    
            if ($t_update3 && (pg_affected_rows($t_update3) > 0)) {
                $t_msg3 = 'Tarvikerivi 3 kirjattu!';
            }
            else {
                $t_msg3 = 'Tarvikerivi 3, ei lisätty: ' . pg_last_error();
            }
        }
        else if (($selected_tool3 != 0 && $amount_of_tools3 == 0) || ($selected_tool3 == 0 && $amount_of_tools3 > 0)) {
            $t_msg3 = 'Tarvikerivi 3: tiedot puutteelliset!';
        }

        // Work_type_id 1 = suunnittelu, 2 = asennustyö, 3 = aputyö
        if ($amount_of_work1 > 0) {
            $w_query1 = "INSERT INTO Billable_hour VALUES (DEFAULT, 1, '$selected_contract', '$currentdate', '$amount_of_work1', '$work_sale1');";
            $w_update1 = update($w_query1);
    
            if ($w_update1 && (pg_affected_rows($w_update1) > 0)) {
                $w_msg1 = 'Työtunnit (suunnittelu) kirjattu!';
            }
            else {
                $w_msg1 = 'Työtunteja (suunnittelu) ei lisätty: ' . pg_last_error();
            }
        }

        if ($amount_of_work2 > 0) {
            $w_query2 = "INSERT INTO Billable_hour VALUES (DEFAULT, 2, '$selected_contract', '$currentdate', '$amount_of_work2', '$work_sale2');";
            $w_update2 = update($w_query2);
    
            if ($w_update2 && (pg_affected_rows($w_update2) > 0)) {
                $w_msg2 = 'Työtunnit (asennustyö) kirjattu!';
            }
            else {
                $w_msg2 = 'Työtunteja (asennustyö) ei lisätty: ' . pg_last_error();
            }
        }

        if ($amount_of_work3 > 0) {
            $w_query3 = "INSERT INTO Billable_hour VALUES (DEFAULT, 3, '$selected_contract', '$currentdate', '$amount_of_work3', '$work_sale3');";
            $w_update3 = update($w_query3);
    
            if ($w_update3 && (pg_affected_rows($w_update3) > 0)) {
                $w_msg3 = 'Työtunnit (aputyö) kirjattu!';
            }
            else {
                $w_msg3 = 'Työtunteja (aputyö) ei lisätty: ' . pg_last_error();
            }
        }

    }

    else {
        if ($selected_contract == 0) {
            $err_msg = 'Asiakasta ei ole valittu!';
        }
        else {
            $err_msg = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
        }
    }
}

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>