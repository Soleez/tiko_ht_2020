<?php include(__DIR__.'/../controllers/Project.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>
  <body>

    <h2>Laskun tiedot</h2>
    <?php 
      print_r($contractor); 
      echo($contractor);
      print_r($customers[0]); 
      echo($customers[2]);
      print_r($projects[0]); 
      echo($projects[2]);
    ?>

    <h2>Asiakkaat</h2>
    <table>
      <thead><tr>
        <th> Urakoitsija </th>
        <th> Työn typpi </th>
        <th> Määrä </th>
        <th> Tuntihinta </th>
        <th> Ale-prosentti </th>
        <th> Linkki </th> 
      </tr></thead>
      <tbody><?php 
      $contractLink = './contract.php';

        // Haetaan taulukon arvot rivi kerrallaan
        for ($row = 0; $row < count($customers); $row++ ) {
          echo "<tr>";
            // Kutsutaan sarakkeita nimeltä, ne ovat samannimisiä kuin tietokannassa,
            // ja asetetaan arvot omiin soluihin html taulussa
            echo"<td>" . $customers[$row]['customer_id'] . "</td>";
            echo"<td>" . $customers[$row]['customer_name'] . "</td>";
            echo"<td>" . $customers[$row]['customer_address'] . "</td>";
            echo"<td>" . $customers[$row]['bool_tax_credit'] . "</td>";
          echo "</tr>";
          for ($projectRow = 0; $projectRow < count($projects); $projectRow++ ) {
            if($projects[$projectRow]['customer_id'] == $customers[$row]['customer_id']) {
              echo "<tr>";
                echo"<td>" . "Projekti" . "</td>";
                echo"<td>" . $projects[$projectRow]['project_id'] . "</td>";
                echo"<td>" . $projects[$projectRow]['project_name'] . "</td>";
                echo"<td>" . $projects[$projectRow]['customer_address'] . "</td>";
                echo"<td>" . $projects[$projectRow]['bool_tax_credit'] . "</td>";
                echo"<td> <a href='$contractLink' onclick='$_SESSION[project_id] = $projects[$projectRow]['project_id']'> 
                <div> Sopimukselle " . $projects[$projectRow]['project_id'] . "</div> </a> </td>";
              echo "</tr>";
            }
          }
        }
      ?></tbody>
    </table>

  </body>
</html>
