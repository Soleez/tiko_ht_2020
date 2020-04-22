<?php include(__DIR__.'/../controllers/Contract.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>
  <body>
    <div>
      <p>Tälle työkohteelle voit lisätä työkaluja ja työtunteja linkistä 'Kirjaa sopimukselle...'.</p>
      <p>Raportin näet 'Projektiin liittyvät laskut'-kohdasta klikkaamalla 'Laskulle...'-linkkiä.</p>
      <p></p>
    </div>

    <div class = "mainDiv">

      <h2>Projektiin liittyvät sopimukset</h2>
      <table>
        <thead><tr>
          <th> Työtuntien ja tarvikkeiden kirjaus </th>
          <th> Sopimustyyppi </th>
          <th> Sopimuksen voimassaolo </th>
        </tr></thead>
        <tbody>
          <!-- Uusia rivejä voidaan kirjata vain mikäli 1.laskun tila on "laskuttamaton" -->
          <?php if ($bills[0]['bill_status_id'] == 1) {
            $billLink = './bill.php';
            $addLink = './add_rows.php';
            // Haetaan taulukon arvot rivi kerrallaan
            for ($row = 0; $row < count($contracts); $row++ ) {
              echo "<tr>";
                echo"<td> <a href='$addLink"
                ."?customer=". $customer[0] 
                ."&project=". $project[0] 
                ."&contract=". $contracts[$row]['contract_id'] 
                ."'> <div> Kirjaa sopimukselle tunnisteella "
                . $contracts[$row]['contract_id']
                . "</div> </a> </td>";
                echo"<td>" . $contracts[$row]['contract_type_name'] . "</td>";
                echo"<td>" . $contracts[$row]['bool_in_use'] . "</td>";
              echo "</tr>";
            }
          }
          else {
            echo "<tr> <td colspan=3> Lisäkirjaukset eivät ole mahdollisia: sopimukselta on jo lähetetty ainakin 1 lasku. </td> </tr>";
          }
          ?>
        </tbody>
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
                  $total_sum = $bills[$billRow]['total_sum'];
                  if ($total_sum == null) {
                    $c_id = $bills[$billRow]['contract_id'];
                    $worksum = getRow(update("SELECT * FROM worksum_function($c_id);"))[0];
                    $toolsum = getRow(update("SELECT * FROM toolsum_function($c_id);"))[0];
                    echo pg_last_error();
                    $total_sum = $worksum + $toolsum;
                  }
                  echo"<td>" . number_format($total_sum, 2, '.', ' ') . "</td>";
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
