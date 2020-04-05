<?php

  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');
  include(__DIR__.'../views/tapahtuma2.php');

  // avataan funktiolla tietokantayhteys
  openConnection();
  
  // haetaan urakoitisijan tiedot
  $contractor = getContractor();

  // Haetaan asiakkaat, työkohteet ja niihin liittyvät voimassaolevat sopimukset
  $custsProjsQuery = pg_query("SELECT Customer.customer_id, Customer.customer_name, Project.project_id, Project.project_name, Contract.contract_id 
  FROM ((Customer JOIN Project ON Customer.customer_id = Project.customer_id) JOIN Contract ON Project.project_id = Contract.project_id) 
  WHERE Contract.bool_in_use = true;");
  $custsProjs = getTable($custsProjsQuery);

  // Haetaan työkalut
  $toolsQuery = pg_query("SELECT tool_id, tool_name, unit FROM Tool;");
  $tools = getTable($toolsQuery);
    
// Kun lisättävät tiedot annettu
if (isset($_POST['formSubmit1']))
{
$selected_contract = intval($_POST['cust_proj']); 

$selected_tool = intval($_POST['tool']);

$amount_of_tools = intval($_POST['amount']);

$currentdate = pg_escape_string(date('Y-m-d'));

// Tietojen tarkistus
$valid_info = $selected_contract != 0 && $selected_tool != 0 && $amount_of_tools > 0;

if ($valid_info)
    {
        $query = "INSERT INTO Sold_tool VALUES (DEFAULT, '$selected_tool', '$amount_of_tools', '$selected_contract', '$currentdate', DEFAULT);";
        $update = update($query);
        
        // asetetaan viesti-muuttuja lisäämisen onnistumisen mukaan
        // lisätään virheilmoitukseen myös virheen syy (pg_last_error)

        if ($update && (pg_affected_rows($update) > 0)) {
            $viesti = 'Tarvikkeet kirjattu!';
        }
        else {
            $viesti = 'Tarvikkeita ei lisätty: ' . pg_last_error();
        }
    }
    else {
        if ($selected_contract == 0) {
            $viesti = 'Valitse asiakas ja työkohde!';
        }
        else if ($selected_tool == 0) {
            $viesti = 'Valitse tarvike!';
        }
        else if ($amount_of_tools <= 0) {
            $viesti = 'Lisättävien tarvikkeiden määrän oltava enemmän kuin 0.';
        }
        else
            $viesti = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
    }
}

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>