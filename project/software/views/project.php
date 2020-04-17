<?php include(__DIR__.'/../controllers/Project.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>
  <body>
    <h2>Tiedot</h2>
    <?php 
      print_r($customers[0]); 
      print_r($projects[0]); 
    ?>

    <h2>Projektit</h2>
    <table>
      <thead><tr>
        <th> Projektin osoite </th>
        <th> Asiakkaan nimi </th>
        <th> Projektin nimi </th>
      </tr></thead>
      <tbody><?php 
      $contractLink = './contract.php';

        // Haetaan taulukon arvot rivi kerrallaan
        for ($row = 0; $row < count($customers); $row++ ) {
          for ($projectRow = 0; $projectRow < count($projects); $projectRow++ ) {
            if($projects[$projectRow]['customer_id'] == $customers[$row]['customer_id']) {
              echo "<tr>";
                echo"<td> <a href='$contractLink"
                  ."?customer=". $customers[$row]['customer_id'] 
                  ."&project=". $projects[$projectRow]['project_id'] 
                  ."'> <div>" . $projects[$projectRow]['project_address'] 
                  . "</div> </a> </td>";
                echo"<td>" . $customers[$row]['customer_name'] . "</td>";
                echo"<td>" . $projects[$projectRow]['project_name'] . "</td>";
              echo "</tr>";
            }
          }
        }
      ?></tbody>
    </table>
    <button><a href='./add_project_contract.php'>Lisää uusi työkohde</a></button>

  </body>
</html>
