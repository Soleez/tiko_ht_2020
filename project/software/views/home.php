<?php include(__DIR__.'/../controllers/Home.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>
  <body>
    <div class="mainDiv">
      <h2>Ylläpito</h2>

      'Hallinnoi varastoa' näyttää työkalut varastossa. 'Lähetä muistutuslaskut' vastaa tapahtumaa T3, 'lähetä karhulaskut' tapahtumaa T4.
      
      <form method="post">
        <button> <a href='./tool.php'> Hallinnoi varastoa </a> </button>
        <button type="submit" name="muistutuslaskuButton">Lähetä muistutuslaskut</button>
        <button type="submit" name="karhulaskuButton">Lähetä karhulaskut</button> 
      </form> 
      <?php
      if(isset($msg)) {
        echo($msg);
      }
      ?>
    </div>
    <div class="mainDiv">
      <h2>Kirjaudu urakoitsijana</h2><p>Valitse urakoitsija nähdäksesi työkohteet:</p>

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
    </div>
  </body>
</html>
