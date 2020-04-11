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
          <th> Sopimuksen tunniste </th>
          <th> Sopimustyyppi </th>
          <th> Sopimuksen voimassaolo </th>
          <th> Tuntien ja tarvikkeiden kirjaus </th>
        </tr></thead>
        <tbody><?php 
          $billLink = './bill.php';
          $addLink = './add_rows.php';
          // Haetaan taulukon arvot rivi kerrallaan
          for ($row = 0; $row < count($contracts); $row++ ) {
            echo "<tr>";
              echo"<td>" . $contracts[$row]['contract_id'] . "</td>";
              echo"<td>" . $contracts[$row]['contract_type_name'] . "</td>";
              echo"<td>" . $contracts[$row]['bool_in_use'] . "</td>";
              echo"<td> <a href='$addLink"
              ."?contract=". $contracts[$row]['contract_id'] 
              ."'> <div>" . "Työtuntien ja tarvikkeiden kirjaus" 
              . "</div> </a> </td>";
            echo "</tr>";
          }
          ?></tbody>
          </table>
  
      <h2>Projektiin liittyvät laskut</h2>
      <table>
        <thead><tr>
          <th> Laskun tunniste </th>
          <th> Lähetyspäivä </th>
          <th> Eräpäivä </th>
          <th> Kokonaissumma </th>
          <th> Laskun tila </th>
          <th> Laskun tyyppi </th> 
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
                    ."'> <div>" . $bills[$billRow]['bill_id'] 
                    . "</div> </a> </td>";
                  echo"<td>" . $bills[$billRow]['bill_sending_date'] . "</td>";
                  echo"<td>" . $bills[$billRow]['bill_due_date'] . "</td>";
                  echo"<td>" . $bills[$billRow]['total_sum'] . "</td>";
                  echo"<td>" . $bills[$billRow]['bill_status_name'] . "</td>";
                  echo"<td>" . $bills[$billRow]['bill_type_name'] . "</td>";
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
