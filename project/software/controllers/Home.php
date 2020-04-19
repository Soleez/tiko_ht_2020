<?php


  // Tuodaan tietokantayhteyden avauksen käsittelevä main luokka
  include(__DIR__.'/../main.php');
  // Tuodaan session, jonka saadaan muistiin rajaukset where ehtoon
  include(__DIR__.'/../classes/Session.php');

  // avataan funktiolla tietokantayhteys
  openConnection();


  // Laskulle kuuluvat tunnit tietokannasta
  $contractorQuery = pg_query("SELECT * FROM contractor
  ");
  // haetaan funktion avulla
  $contractors = getTable($contractorQuery);


  /**  
   *  Lähetetään muistutusmaksut
  */

  // Muistutuslasku
  if(isset($_POST['muistutuslaskuButton'])) { 
    $billsQuery = update("SELECT * from recursive_bills_function()
      where tier=1 and previous_bill_id is null and bill_id not in 
        (select previous_bill_id as bill_id 
        from recursive_bills_function() 
        where previous_bill_id is not null) 
      and bill_due_date < current_date and bill_status_id = 2;");

    if ($billsQuery) {  
      // bill_id, contract_id, total_sum, billing_address, bill_status_id, bill_due_date, previous_bill_id, tier
      $billsTable = getTable($billsQuery);
      if ($billsTable[0]['bill_id'] != null) {
        $billCount = 0;
        for ($row = 0; $row < count($billsTable); $row++) {
          // bill_id, contract_id, total_sum, billing_address, bill_type_id, 
          // bill_status_id, date_added, date_modified, bill_due_date,
          // bill_sending_date, previous_bill_id
          $bill_id = $billsTable[$row]['bill_id'];
          $address = $billsTable[$row]['billing_address'];
          $sum = $billsTable[$row]['total_sum'] + $billsTable[$row]['handling_fee'];
          $contract_id = $billsTable[$row]['contract_id'];
          $reminderBill = ("INSERT INTO Bill VALUES 
            (DEFAULT, $contract_id, $sum, '$address', 2, 2, current_date,
            null, current_date+30, current_date, $bill_id, 2)");
          $result = update($reminderBill);
          if ($result) {
            $billCount++;
          }
          else {
            $msg = "Laskun lähetys epäonnistui". pg_last_error();
          }
          $msg = "Muistutuslaskuja lähetetty $billCount kappale(tta).";
        }
      }
      else {
        $msg = "Ei lähetettäviä muistutuslaskuja.";
      }
    }
    else {
      echo("Kysely epäonnistui.");
    }
  }
  
  // Karhulasku
  if(isset($_POST['karhulaskuButton'])) { 
    $billsQuery = update("SELECT * from recursive_bills_function()
      where tier=2 and previous_bill_id is null and bill_id not in 
        (select previous_bill_id as bill_id 
        from recursive_bills_function() 
        where previous_bill_id is not null) 
      and bill_due_date < current_date and bill_status_id = 2;");

    if ($billsQuery) {
      // billsTable sarakkeet  
      // bill_id, contract_id, total_sum, billing_address, bill_status_id, 
      // bill_due_date, previous_bill_id, handling_fee, tier
      $billsTable = getTable($billsQuery);
      if ($billsTable[0]['bill_id'] != null) {
        $billCount = 0;
        for ($row = 0; $row < count($billsTable); $row++) {
          // Bill-taulun sarakkeet
          // bill_id, contract_id, total_sum, billing_address, bill_type_id, 
          // bill_status_id, date_added, date_modified, bill_due_date,
          // bill_sending_date, previous_bill_id
          $bill_id = $billsTable[$row]['bill_id'];
          $address = $billsTable[$row]['billing_address'];
          
          // Edellisen laskun loppusumma(sis. laskutuslisän) + uuden laskun 
          // laskutuslisä + viivästyskorko 16% (vuosikorko)
          $tmp_date = $billsTable[$row]['bill_due_date'];
          $penaltyintr_res = update("SELECT * FROM 
                                    count_penalty_interest_function(CAST('$tmp_date' as date));");
          if ($penaltyintr_res) {
            $penaltyintr = getRow($penaltyintr_res);
            // Summa josta viivästysmaksu lasketaan
            $orig_total = $billsTable[$row]['total_sum'] - 5;
            $handling_fee_count = $billsTable[$row]['bill_number'];
            // Viivästysmaksun määrä
            $surcharge = $orig_total*$penaltyintr[0];

            $sum = $billsTable[$row]['total_sum'] + 
              $billsTable[$row]['handling_fee']*$handling_fee_count
               + $surcharge;

            $contract_id = $billsTable[$row]['contract_id'];
            $reminderBill = ("INSERT INTO Bill VALUES 
              (DEFAULT,
              $contract_id, 
              $sum,
              '$address', 3, 2, current_date,
              null, current_date+30, current_date, $bill_id, 3)");
            $result = update($reminderBill);
            $testi = $billsTable[$row]['bill_id'];
            if ($result) {
              $billCount++;
            }
            else {
              $msg = "Laskun lähetys epäonnistui". pg_last_error();
            }
          }
          else {
            $msg = "Viivästyskoron lasku epäonnistui." . pg_last_error();
          }
          $msg = $msg."Karhulaskuja lähetetty $billCount kappale(tta).";
        }
      }
      else {
        $msg = $msg."Ei lähetettäviä karhulaskuja.";
      }
    }
    else {
      echo("Kysely epäonnistui.");
    }
  }



  // suljetaan funktiolla tietokantayhteys
  closeConnection();
  
?>
