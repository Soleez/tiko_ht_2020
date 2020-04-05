<?php

  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');
  include(__DIR__.'../views/tapahtuma1.php');

  // avataan funktiolla tietokantayhteys
  openConnection();
  
  
  // haetaan urakoitisijan tiedot
  $contractor = getContractor();

  // Haetaan asiakkaat
  $customersQuery = pg_query("SELECT customer_id, customer_name FROM Customer;");
  $customers = getTable($customersQuery);

// Kun lisättävät tiedot annettu
if (isset($_POST['formSubmit1']))
{
$selected_customer = intval($_POST['customer']); 
//echo $selected_customer;

$project_name = pg_escape_string($_POST['name']);
$project_address = pg_escape_string($_POST['address']);
$bool_tax_credit = pg_escape_string($_POST['bool_tax_credit']);

// Tietojen tarkistus
$valid_info = $selected_customer != 0 && trim($project_name) != '' && strlen(trim($project_name)) <= 60 && strlen(trim($project_address)) <= 60;

if ($valid_info)
    {
        if ($bool_tax_credit == '') {
            $query = "INSERT INTO Project VALUES (DEFAULT, $selected_customer, '$project_name', '$project_address', null);";
        }
        else 
            $query = "INSERT INTO Project VALUES (DEFAULT, $selected_customer, '$project_name', '$project_address', '$bool_tax_credit');";
        $update = update($query);
        
        // asetetaan viesti-muuttuja lisäämisen onnistumisen mukaan
        // lisätään virheilmoitukseen myös virheen syy (pg_last_error)

        if ($update && (pg_affected_rows($update) > 0)) {
            $viesti = 'Työkohde lisätty!';
        }
        else {
            if ($bool_tax_credit <> 't' && $bool_tax_credit <> 'f') {
                $viesti = 'Lisäys epäonnistui! Kotitalousvähennys tulee antaa booleanina \'t\' tai \'f\'.';
            }
            else
                $viesti = 'Työkohdetta ei lisätty: ' . pg_last_error();
        }
    }
    else {
        if ($selected_customer == 0) {
            $viesti = 'Valitse asiakas!';
        }
        else
            $viesti = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
    }
}

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>