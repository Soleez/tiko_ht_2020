<?php include(__DIR__.'/../controllers/Contract.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>
<!-- https://www.sis.uta.fi/~jm98546/tiko/tiko_ht_2020/project/software/views/contract.php -->
<html>
  <body>

    <h2>Laskun tiedot</h2>
    <?php 
      print_r($contracts[0]);
      print_r($bills[0]);
    ?>

  <h2>Sopimuksen tiedot</h2>
    <table>
      <thead><tr>
        <th> Sopimuksen tunniste </th>
        <th> Työn typpi </th>
      </tr></thead>
      <tbody><?php 
        $billLink = './bill.php';
        // Haetaan taulukon arvot rivi kerrallaan
        for ($row = 0; $row < count($contracts); $row++ ) {
          echo "<tr>";
            echo"<td>" . $contracts[$row]['contract_id'] . "</td>";
            echo"<td>" . $contracts[$row]['contract_type_id'] . "</td>";
          echo "</tr>";
        }
        ?></tbody>
        </table>

    <h2>Sopimukseen liittyvät laskut</h2>
    <table>
      <thead><tr>
        <th> Laskun tunniste </th>
        <th> Lähetyspäivä </th>
        <th> Eräpäivä </th>
        <th> total_sum </th>
        <th> bill_status_id </th>
        <th> bill_type_id </th> 
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
                echo"<td>" . $bills[$billRow]['bill_status_id'] . "</td>";
                echo"<td>" . $bills[$billRow]['bill_type_id'] . "</td>";
              echo "</tr>";
            }
          }
        }
      ?></tbody>
    </table>

  </body>
</html>
