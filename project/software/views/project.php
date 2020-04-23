<?php include(__DIR__.'/../controllers/Project.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>
  <body>
    <div class="mainDiv">
    <h2>Projektit</h2>
    
      <p>Valitse haluamasi projekti tai lisää uusi työkohde (T1).</p>
    <table>
      <thead><tr>
        <th> Projektin nimi </th>
        <th> Asiakkaan nimi </th>
        <th> Projektin osoite </th>
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
                  ."'> <div>" . $projects[$projectRow]['project_name'] 
                  . "</div> </a> </td>";
                echo"<td>" . $customers[$row]['customer_name'] . "</td>";
                echo"<td>" . $projects[$projectRow]['project_address'] . "</td>";
              echo "</tr>";
            }
          }
        }
      ?></tbody>
    </table>
    <button><a href='./add_project_contract.php'>Lisää uusi työkohde</a></button>
    </div>
  </body>
</html>
