<?php

    // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
    include(__DIR__.'/../main.php');
    // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
    include(__DIR__.'/../classes/Session.php');
    include(__DIR__.'../views/add_project_contract.php');

    // avataan funktiolla tietokantayhteys
    openConnection();


    // haetaan urakoitisijan tiedot
    $contractor = getContractor();

    // Haetaan asiakkaat
    $customersQuery = pg_query("SELECT customer_id, customer_name FROM Customer WHERE contractor_id=$contractor[0];");
    $customers = getTable($customersQuery);

    // Haetaan sopimustyypi
    $contractTypeQuery = pg_query("SELECT contract_type_id, contract_type_name FROM Contract_type;");
    $contractTypes = getTable($contractTypeQuery);

    // Kun lisättävät tiedot annettu
    if (isset($_POST['formSubmit1'])) {

        // Tiedot lomakkeelta
        $selected_customer = intval($_POST['customer']); 
        $project_name = pg_escape_string($_POST['name']);
        $project_address = pg_escape_string($_POST['address']);
        $bool_tax_credit = pg_escape_string($_POST['bool_tax_credit']);
        $contract_type = intval($_POST['contract_type']);
        $amount_of_payments = intval($_POST['amount_of_payments']);

        // Tietojen tarkistus
        $valid_info = $selected_customer > 0 && strlen(trim($project_name)) > 0  && strlen(trim($project_name)) <= 60 && strlen(trim($project_address)) 
        <= 60 && $bool_tax_credit <> "" && $contract_type > 0;

        if ($valid_info) {
            if ($bool_tax_credit == '') {
                $query = "INSERT INTO Project VALUES (DEFAULT, $selected_customer, '$project_name', '$project_address', null);";
            }
            else {
                $query = "INSERT INTO Project VALUES (DEFAULT, $selected_customer, '$project_name', '$project_address', '$bool_tax_credit');";
            }
            // Lisätään uusi projekti
            $update = update($query);
            
            if ($update && (pg_affected_rows($update) > 0)) {
                // Haetaan lisätyn projektin id
                $result = update("SELECT currval('Project_project_id_seq');");
                $id = getRow($result);
                
                if ($id[0] >0) {
                    // Lisätään projektille uusi sopimus
                    $query3 = "INSERT INTO Contract VALUES (DEFAULT, $id[0], $contract_type, 't', $amount_of_payments);";
                    $update2 = update($query3);
                    
                    if ($update2) {
                        $viesti = "Työkohde lisätty!";
                    }
                    else {
                        $viesti = "Sopimuksen lisäys epäonnistui." . pg_last_error();
                    } 
                }
                else {
                    $viesti = 'Työkohdetta ei lisätty: ' . pg_last_error();
                }
            }
        }
        else {
            $viesti = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
            
        }
    }

    // suljetaan funktiolla tietokantayhteys
    closeConnection();
  
?>


