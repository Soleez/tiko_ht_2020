<?php include(__DIR__.'/../controllers/Contract.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>
<!-- https://www.sis.uta.fi/~jm98546/tiko/tiko_ht_2020/project/software/views/contract.php -->
<html>
  <body>

    <h2>Laskun tiedot</h2>
    <?php 
      print_r($contracts[0]); 
      echo($contractor[2]);
    ?>

    <h2>Sopimus</h2>
    <table>
      <thead><tr>
        <th> Sopimus </th>
        <th> Työn typpi </th>
        <th> Määrä </th>
        <th> Tuntihinta </th>
        <th> Ale-prosentti </th>
        <th> Linkki </th> 
      </tr></thead>
      <tbody><?php 
      $billLink = './bill.php';
        // Haetaan taulukon arvot rivi kerrallaan
        for ($row = 0; $row < count($contracts); $row++ ) {
          echo "<tr>";
            // Kutsutaan sarakkeita nimeltä, ne ovat samannimisiä kuin tietokannassa,
            // ja asetetaan arvot omiin soluihin html taulussa
            echo"<td>" . $contracts[$row]['contract_id'] . "</td>";
            echo"<td>" . $contracts[$row]['work_type_name'] . "</td>";
            echo"<td>" . $contracts[$row]['quantity'] . "</td>";
            echo"<td>" . $contracts[$row]['hourly_rate'] . "</td>";
            echo"<td>" . $contracts[$row]['sale_percentage'] . "</td>";
            echo"<td>" . $contracts[$row]['vat_rate'] . "</td>";
          echo "</tr>";
          for ($billRow = 0; $billRow < count($bills); $billRow++ ) {
            if($bills[$billRow]['contract_id'] == $contracts[$row]['contract_id']) {
              echo "<tr>";
                echo"<td>" . "Lasku" . "</td>";
                echo"<td>" . $bills[$billRow]['bill_id'] . "</td>";
                echo"<td>" . $bills[$billRow]['quantity'] . "</td>";
                echo"<td>" . $bills[$billRow]['hourly_rate'] . "</td>";
                echo"<td>" . $bills[$billRow]['hourly_rate'] . "</td>";
                echo"<td> <a href='$billLink'> <div> Laskulle " . $bills[$billRow]['bill_id'] . "</div> </a> </td>";
              echo "</tr>";
            }
          }
        }
      ?></tbody>
    </table>

  </body>
</html>
