<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  // avataan funktiolla tietokantayhteys
  openConnection();

  // haetaan urlista
  setContract($_GET['contract']);
  setBill($_GET['bill']);

  
  // haetaan urakoitisijan tiedot
  $contractor = getContractor();
  // haetaan asiakkaan tiedot
  $customer = getCustomer();
  // haetaan kohteen tiedot
  $project = getProject();
  // haetaan sopimuksen tiedot
  $contract = getContract();
  // haetaan laskun tiedot
  $bill = getBill();
  

  // Laskulle kuuluvat tunnit tietokannasta
  $hoursQuery = pg_query("SELECT * FROM vw_hours
    WHERE contract_id = {$contract[0]}
  ");
  // haetaan funktion avulla
  $hours = getTable($hoursQuery);

  // Haetaan laskujen tietoja vw_bills viewstä, session antaman bill_id:n perusteella
  $billsQuery = pg_query("SELECT * FROM vw_bills
    WHERE contract_id = {$contract[0]} AND
          bill_id = {$bill[0]}
  ;");
  $bills = getTable($billsQuery);

  // Talletetan kysely muuttujaan
  $toolsQuery = pg_query("SELECT * FROM vw_tools
    WHERE contract_id = {$contract[0]}
  ;");
  $tools = getTable($toolsQuery);

  // Työkalujen summa ennen alennusta
  $toolsumNoSaleQuery = pg_query("SELECT * FROM toolsum_wo_discount_function({$contract[0]});");
  $toolsumNoSale = getRow($toolsumNoSaleQuery);

  // Työkalujen verot
  $tooltaxsumQuery = pg_query("SELECT * FROM tool_tax_sum_function({$contract[0]});");
  $tooltaxsum = getRow($tooltaxsumQuery);  

  // Työkalujen summa
  $toolsumQuery = pg_query("SELECT * FROM toolsum_function({$contract[0]});");
  $toolsum = getRow($toolsumQuery);

  // Työtuntien summa ennen alennusta
  $worksumNoSaleQuery = pg_query("SELECT * FROM worksum_wo_discount_function({$contract[0]});");
  $worksumNoSale = getRow($worksumNoSaleQuery);

  // Työtuntien verot
  $worktaxsumQuery = pg_query("SELECT * FROM work_tax_sum_function({$contract[0]});");
  $worktaxsum = getRow($worktaxsumQuery);    

  // Työtuntien summa
  $worksumQuery = pg_query("SELECT * FROM worksum_function({$contract[0]});");
  $worksum = getRow($worksumQuery);  

  // lasketaan loppusumma
  $totalsum = $toolsum[0] + $worksum[0];

  /** Luodaan funktio, jolla lasku saadaan lähtettyä
    * Eräpäivä asettuu aina kolmen viikon päähän
    */
  function sendBill($id, $totalsum) {
    // Laskulle kuuluvat tunnit tietokannasta
    $sendBill = pg_query("UPDATE bill
      SET
        bill_status_id = 2,
        bill_sending_date = CURRENT_DATE,
        bill_due_date = (CURRENT_DATE + 21),
        date_modified = clock_timestamp(),
        total_sum = {$totalsum}
    WHERE bill_id = {$id}
    ");

    // haetaan funktion avulla
    update($sendBill);
  }

  // Muuttaa urakkatarjouksen urakaksi.
  function acceptBid($id, $payments, $billId, $totalSum, $address) {
    $acceptBid = pg_query("UPDATE contract
      SET contract_type_id = 2
      WHERE contract_id = {$id};
    ");

    // Lasketaan ositettu laskujen summa.
    $sum_per_bill = (float)$totalSum / (float)$payments;

    // Luodaan 1. lasku
    $acceptBid .= pg_query("UPDATE bill
      SET
        bill_status_id = 2,
        bill_sending_date = CURRENT_DATE,
        bill_due_date = (CURRENT_DATE + 21),
        date_modified = clock_timestamp(),
        total_sum = {$sum_per_bill}
      WHERE bill_id = {$billId};
    ");

    // Lisätään uusia laskuja alkaen laskusta 2.
    for ($row = 2; $row <= $payments; $row++) {
      $row_date_string = 'bill_no_' . $row;
      $row_date = date($_POST[$row_date_string]);
      // echo "päiväys: " . $row_date . "<br/>";

      $acceptBid .= pg_query("INSERT INTO Bill
      VALUES (DEFAULT, $id, $sum_per_bill, '$address', DEFAULT, DEFAULT, CURRENT_DATE, CURRENT_DATE, '$row_date', null, null, DEFAULT);");
    }

    // haetaan funktion avulla
    update($acceptBid);
  }

  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>
