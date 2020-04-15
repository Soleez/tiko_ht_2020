<?php include(__DIR__.'/../controllers/Bill.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>
  <body>
    <p>/ Etusivu / Projektit / Sopimukset / Lasku</p>
    <div class = "mainDiv">
 
     <h2>Laskun tiedot</h2>
     <?php 
       echo("Urakoitsija: " . $contractor[2] . "<br/>");
       echo("Asiakas: " . $customer[2] . "<br/>");
       echo("Työkohde: " . $project[2] . "<br/>");
       echo("Työkohteen osoite: " . $project[3] . "<br/>");
       echo("Laskutusosoite: " . $bill[3] . "<br/>");
       echo("Sopimuksen tyyppi: " . $bills[0]['contract_type_name'] . "<br/>");
       echo("Laskun tyyppi: " . $bills[0]['bill_type_name'] . "<br/>");
       echo("Laskun tila: " . $bills[0]['bill_status_name'] . "<br/>");
     ?>
 
     <h2>Tunnit</h2>
     <table>
       <thead><tr>
         <th> Asiakas </th>
         <th> Työn typpi </th>
         <th> Määrä </th>
         <?php 
           if ($bills[0]['contract_type_name'] != 'urakka' && $bills[0]['contract_type_name'] != 'urakkatarjous') {
             echo"<th> Tuntihinta </th>";
             echo"<th> Ale-prosentti </th>";
             echo"<th> Alv-prosentti </th>";
           }
         ?>
         <th> Hinta ennen alennuksia </th>
         <th> Verot </th>
         <th> Hinta ilman veroa alennusten kanssa </th>
         <th> Lopullinen hinta </th>
       </tr></thead>
       <tbody><?php 
         // Haetaan taulukon arvot rivi kerrallaan
         // Urakoille (contract_type_name 'urakka' tai 'urakkatarjous') ei näytetä kuin osa riveistä.
         for ($row = 0; $row < count($hours); $row++ ) {
           echo "<tr>";
             // Kutsutaan sarakkeita nimeltä, ne ovat samannimisiä kuin tietokannassa,
             // ja asetetaan arvot omiin soluihin html taulussa
             echo"<td>" . $hours[$row]['customer_name'] . "</td>";
             echo"<td>" . $hours[$row]['work_type_name'] . "</td>";
             echo"<td>" . $hours[$row]['quantity'] . " h </td>";
             if ($bills[0]['contract_type_name'] != 'urakka' && $bills[0]['contract_type_name'] != 'urakkatarjous') {
               echo"<td>" . $hours[$row]['hourly_rate'] . "</td>";
               echo"<td>" . $hours[$row]['sale_percentage'] . "</td>";
               echo"<td>" . $hours[$row]['vat_rate'] . "</td>";
               echo"<td>" . $hours[$row]['total_before_sale'] . "</td>";
               echo"<td>" . $hours[$row]['tax_only'] . "</td>";
               echo"<td>" . $hours[$row]['price_wo_tax_w_sale'] . "</td>";
               echo"<td>" . $hours[$row]['total_sum'] . "</td>";
             }
             else {
               echo"<td colspan='4'></td>";
             }
           echo "</tr>";
         }
       ?>
         <tr>
         <?php 
             if ($bills[0]['contract_type_name'] != 'urakka' && $bills[0]['contract_type_name'] != 'urakkatarjous') {
               echo"<td colspan='6'>Tunnit yhteensä</td>";
             }
             else {
               echo"<td colspan='3'>Tunnit yhteensä</td>";
             }
           ?>
           <td><?php echo($worksumNoSale[0]); ?></td>
           <td><?php echo($worktaxsum[0]); ?></td>
           <td><?php echo($worksum[0] - $worktaxsum[0]); ?></td>
           <td><?php echo($worksum[0]); ?></td>
         </tr>
       
       </tbody>
     </table>
 
     <h2>Tarvikkeet</h2>
     <table>
       <thead><tr>
         <th> Asiakas </th>
         <th> Tuote </th>
         <th> Määrä </th>
         <?php 
           if ($bills[0]['contract_type_name'] != 'urakka' && $bills[0]['contract_type_name'] != 'urakkatarjous') {
             echo"<th> Yksikköhinta </th>";
             echo"<th> Ale-prosentti </th>";
             echo"<th> Alv-prosentti </th>";
           }
         ?>
         <th> Hinta ennen alennuksia </th>
         <th> Verot </th>
         <th> Hinta ilman veroa alennusten kanssa </th>
         <th> Lopullinen hinta </th>
       </tr></thead>
       <tbody><?php
         for ($row = 0; $row < count($tools); $row++ ) {
           echo"<tr>";
             echo"<td>" . $tools[$row]['customer_name'] . "</td>";
             echo"<td>" . $tools[$row]['tool_name'] . "</td>";
             echo"<td>" . $tools[$row]['quantity'] . " " . $tools[$row]['unit'] . "</td>";
             if ($bills[0]['contract_type_name'] != 'urakka' && $bills[0]['contract_type_name'] != 'urakkatarjous') {
               echo"<td>" . $tools[$row]['tool_selling_price'] . "</td>";
               echo"<td>" . $tools[$row]['sale_percentage'] . "</td>";
               echo"<td>" . $tools[$row]['vat_rate'] . "</td>";
               echo"<td>" . $tools[$row]['total_before_sale'] . "</td>";
               echo"<td>" . $tools[$row]['tax_only'] . "</td>";
               echo"<td>" . $tools[$row]['price_wo_tax_w_sale'] . "</td>";
               echo"<td>" . $tools[$row]['total_sum'] . "</td>";
             }
             else {
               echo"<td colspan='4'></td>";
             }
           echo"</tr>";
         }
       ?>
 
         <tr>
           <?php 
             if ($bills[0]['contract_type_name'] != 'urakka' && $bills[0]['contract_type_name'] != 'urakkatarjous') {
               echo"<td colspan='6'>Tarvikkeet yhteensä</td>";
             }
             else {
               echo"<td colspan='3'>Tarvikkeet yhteensä</td>";
             }
           ?>
           <td><?php echo($toolsumNoSale[0]); ?></td>
           <td><?php echo($tooltaxsum[0]); ?></td>
           <td><?php echo($toolsum[0] - $tooltaxsum[0]); ?></td>
           <td><?php echo($toolsum[0]); ?></td>
         </tr>
       <tbody>
     </table>
 
     <h2> Yhteensä </h2>
     <div>
       Alkuperäinen hinta: <?php echo($toolsumNoSale[0] + $worksumNoSale[0]); ?><br/>
       Verot: <?php echo($tooltaxsum[0] + $worktaxsum[0]); ?><br/>
       Kotitalousvähennykseen kelpaava osuus:
       <?php if ($customer[4] != true) { echo("Asiakas ei ole kotitalousvähennyskelpoinen"); } 
         else if ($project[4] != true) { echo("Työkohde ei ole kotitalousvähennyskelpoinen"); }
         else { echo($worksum[0] * 0.40); }
       ?>
       <br/>
       Kotitalousvähennys vuoden alusta: <br />
       Lopullinen hinta: <?php echo($toolsum[0] + $worksum[0]); ?><br/>
     </div>
    </div>
  </body>
</html>
