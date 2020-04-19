<?php include(__DIR__.'/../controllers/Contract.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>
  <body>
    <div class = "mainDiv">
      <h2>Laskun tiedot</h2>
      <?php 
        print_r($contracts[0]);
        print_r($bills[0]);
      ?>
    </div>

    <div class = "mainDiv">

      <h2>Projektiin liittyvät sopimukset</h2>
      <table>
        <thead><tr>
          <th> Työtuntien ja tarvikkeiden kirjaus </th>
          <th> Sopimustyyppi </th>
          <th> Sopimuksen voimassaolo </th>
        </tr></thead>
        <tbody><?php 
          $billLink = './bill.php';
          $addLink = './add_rows.php';
          // Haetaan taulukon arvot rivi kerrallaan
          for ($row = 0; $row < count($contracts); $row++ ) {
            echo "<tr>";
              echo"<td> <a href='$addLink"
              ."?contract=". $contracts[$row]['contract_id'] 
              ."'> <div> Sopimukselle tunnisteella "
              . $contracts[$row]['contract_id']
              . "</div> </a> </td>";
              echo"<td>" . $contracts[$row]['contract_type_name'] . "</td>";
              echo"<td>" . $contracts[$row]['bool_in_use'] . "</td>";
            echo "</tr>";
          }
          ?></tbody>
          </table>
  
      <h2>Projektiin liittyvät laskut</h2>
      <table>
        <thead><tr>
          <th> Laskun tiedot ja lähetys </th>
          <th> Lähetyspäivä </th>
          <th> Eräpäivä </th>
          <th> Kokonaissumma </th>
          <th> Laskun tila </th>
          <th> Laskun tyyppi </th> 
          <th> Sopimustyyppi </th> 
          <th> Sopimuksen tunniste </th> 
        </tr></thead>
        <tbody><?php 
          $billLink = './bill.php';
          // Haetaan taulukon arvot rivi kerrallaan
          for ($row = 0; $row < count($contracts); $row++ ) {
            for ($billRow = 0; $billRow < count($bills); $billRow++ ) {
              if($bills[$billRow]['contract_id'] == $contracts[$row]['contract_id']) {
                echo "<tr>";
                  echo"<td> <a href='$billLink"
                    ."?contract=". $contracts[$row]['contract_id'] 
                    ."&bill=". $bills[$billRow]['bill_id'] 
                    ."'> <div> Laskulle tunnisteella " 
                    . $bills[$billRow]['bill_id'] 
                    . "</div> </a> </td>";
                  echo"<td>" 
                    // En saanut tähän Null arvon tarkista toimimaan oikein, nyt NULL = on 1970 luvulla
                    . date("d.m.Y", strtotime($bills[$billRow]['bill_sending_date'])) 
                    . "</td>";
                  echo"<td>" . $bills[$billRow]['bill_due_date'] . "</td>";
                  echo"<td>" . $bills[$billRow]['total_sum'] . "</td>";
                  echo"<td>" . $bills[$billRow]['bill_status_name'] . "</td>";
                  echo"<td>" . $bills[$billRow]['bill_type_name'] . "</td>";
                  echo"<td>" . $contracts[$row]['contract_type_name'] . "</td>";
                  echo"<td>" . $contracts[$row]['contract_id'] . "</td>";
                echo "</tr>";
              }
            }
          }
        ?></tbody>
      </table>
    <div>
  </body>
</html>
