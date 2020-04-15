<?php include(__DIR__.'/../controllers/Home.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>
  <body>
    <p>/ Etusivu</p>
    <h2>Ylläpito</h2>
    <?php 
    echo"<button> <a href='./tool.php'> Hallinnoi varastoa </a> </button>";
    echo"<button> <a href='./home.php'> Suorita laskujen lähetys </a> </button>";
    ?>

    <h2>Kirjaudu urakoitsijana</h2>
    <table>
      <thead><tr>
        <th> Urakoitsija </th>
        <th> Yritys </th>
        <th> Toimiala </th>
      </tr></thead>
      <tbody><?php 
      $projectLink = './project.php';

        // Haetaan taulukon arvot rivi kerrallaan
        for ($row = 0; $row < count($contractors); $row++ ) {
          echo "<tr>";
            echo"<td> <a href='$projectLink?contractor=". $contractors[$row]['contractor_id'] ."'>
            <div>" . $contractors[$row]['contractor_name'] . "</div></a></td>";
            echo"<td>" . $contractors[$row]['company_name'] . "</td>";
            echo"<td>" . $contractors[$row]['industry'] . "</td>";
          echo "</tr>";
        }
      ?></tbody>
    </table>

  </body>
</html>
