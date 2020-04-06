<?php include(__DIR__.'/../controllers/Bill.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>
  <body>

    <h2>Laskun tiedot</h2>
    <?php 
      // print_r($contractor); 
      echo("Urakoitsija: " . $contractor[2] . "<br/>");
      echo("Asiakas: " . $customer[2] . "<br/>");
      echo("Työkohde: " . $project[2] . "<br/>");
      echo("Työkohteen osoite: " . $project[3] . "<br/>");
      echo("Laskutusosoite: " . $bill[3] . "<br/>");
    ?>

    <h2>Tunnit</h2>
    <table>
      <thead><tr>
        <th> Asiakas </th>
        <th> Työn typpi </th>
        <th> Määrä </th>
        <th> Tuntihinta </th>
        <th> Ale-prosentti </th>
        <th> Alv-prosentti </th>
        <th> Verot </th>
        <th> Hinta ilman veroa alennusten kanssa </th>
        <th> Lopullinen hinta </th>
      </tr></thead>
      <tbody><?php 
        // Haetaan taulukon arvot rivi kerrallaan
        for ($row = 0; $row < count($hours); $row++ ) {
          echo "<tr>";
            // Kutsutaan sarakkeita nimeltä, ne ovat samannimisiä kuin tietokannassa,
            // ja asetetaan arvot omiin soluihin html taulussa
            echo"<td>" . $hours[$row]['customer_name'] . "</td>";
            echo"<td>" . $hours[$row]['work_type_name'] . "</td>";
            echo"<td>" . $hours[$row]['quantity'] . "</td>";
            echo"<td>" . $hours[$row]['hourly_rate'] . "</td>";
            echo"<td>" . $hours[$row]['sale_percentage'] . "</td>";
            echo"<td>" . $hours[$row]['vat_rate'] . "</td>";
            echo"<td>" . $hours[$row]['tax_only'] . "</td>";
            echo"<td>" . $hours[$row]['price_wo_tax_w_sale'] . "</td>";
            echo"<td>" . $hours[$row]['total_sum'] . "</td>";
          echo "</tr>";
        }
      ?>
        <tr>
          <td>Tunnit yhteensä</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>tähän verot</td>
          <td></td>
          <td><?php echo($worksum[0]); ?></td>
        </tr>
      
      </tbody>
    </table>

    <h2>Työkalut</h2>
    <table>
      <thead><tr>
        <th> Asiakas </th>
        <th> Tuote </th>
        <th> Määrä </th>
        <th> Yksikköhinta </th>
        <th> Ale-prosentti </th>
        <th> Alv-prosentti </th>
        <th> Verot </th>
        <th> Hinta ilman veroa alennusten kanssa </th>
        <th> Lopullinen hinta </th>
      </tr></thead>
      <tbody><?php
        for ($row = 0; $row < count($tools); $row++ ) {
          echo"<tr>";
            echo"<td>" . $tools[$row]['customer_name'] . "</td>";
            echo"<td>" . $tools[$row]['tool_name'] . "</td>";
            echo"<td>" . $tools[$row]['quantity'] . "</td>";
            echo"<td>" . $tools[$row]['tool_price'] . "</td>";
            echo"<td>" . $tools[$row]['sale_percentage'] . "</td>";
            echo"<td>" . $tools[$row]['vat_rate'] . "</td>";
            echo"<td>" . $tools[$row]['tax_only'] . "</td>";
            echo"<td>" . $tools[$row]['price_wo_tax_w_sale'] . "</td>";
            echo"<td>" . $tools[$row]['total_sum'] . "</td>";
          echo"</tr>";
        }
      ?>

        <tr>
          <td>Työkalut yhteensä</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>tähän verot</td>
          <td></td>
          <td><?php echo($toolsum[0]); ?></td>
        </tr>
      <tbody>
    </table>

    <h2> Yhteensä </h2>
    <div>
      Alkuperäinen hinta: <br/>
      Verot: <br/>
      Lopullinen hinta: <?php echo($toolsum[0] + $worksum[0]); ?><br/>
    </div>
  </body>
</html>
