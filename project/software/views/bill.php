<?php include(__DIR__.'/../controllers/Bill.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>
  <body>
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
       echo("Laskun lähetyspäivä: " . $bills[0]['bill_sending_date'] . "<br/>");
       echo("Laskun eräpäivä: " . $bills[0]['bill_due_date'] . "<br/>");
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
           <td><?php echo($toolsum[0]); ?></td>
         </tr>
       <tbody>
     </table>
 
     <h2> Yhteensä </h2>
     <div>
       Alkuperäinen hinta: <?php echo number_format(($toolsumNoSale[0] + $worksumNoSale[0]), 2, '.', ' '); ?><br/>
       Verot: <?php echo number_format(($tooltaxsum[0] + $worktaxsum[0]), 2, '.', ' '); ?><br/> 
       Kotitalousvähennykseen kelpaava osuus:
       <?php if ($customer[4] != true) { echo("Asiakas ei ole kotitalousvähennyskelpoinen"); } 
         else if ($project[4] != true) { echo("Työkohde ei ole kotitalousvähennyskelpoinen"); }
         else { echo number_format(($worksum[0] * 0.40), 2, '.', ' '); }
       ?>
       <br/>
       Kotitalousvähennys vuoden alusta: toteuttamatta vielä? <br />
       Lopullinen hinta: <?php echo number_format(($toolsum[0] + $worksum[0]), 2, '.', ' '); ?><br/>
     </div>


     <?php
      /** Näytetään laskun lähetysnäppäin jos ehdot täyttyvät
        * Jos laskun status = 1 (laskutamatta) ja contract_type = 1 tai 2 (tuntilaskutteinen tai urakka) 
        */
      if(($bill[5] == "1") && (($contract[2] == "1") || ($contract[2] == "2"))){

        echo"</br>";
        if(isset($_POST['sendBill'])) { 
          sendBill($bill[0], $totalsum);
          echo "Lasku lähetetty"; 
        } 
      
        echo" 
        <form method='post'> 
          <input type='submit' name='sendBill' value='Lähetä lasku'/> 
        </form> ";
      }
      /** Näytetään urakkatarjouksen hyväksymisnäppäin jos ehdot täyttyvät
        * Jos laskun status = 1 (laskutamatta) ja contract_type = 3 (urakkatarjous) 
        */
      if(($bill[5] == "1") && ($contract[2] == "3")){

        echo"</br>";
        if(isset($_POST['acceptBid'])) {
          acceptBid($contract[0]);
          echo "Urakkatarjous hyväksytty ja muutettu urakkasopimukseksi"; 
        } 
      
        echo" 
        <form method='post'> 
          <input type='submit' name='acceptBid' value='Hyväksy urakkatarjous'/> 
        </form> ";
      }
      // Vastaava logiikka hinta-arviolle
      if(($bill[5] == "1") && ($contract[2] == "4")){

        echo"</br>";
        if(isset($_POST['acceptBid2'])) {
          acceptBid2($contract[0]);
          echo "Hinta-arvio hyväksytty ja muutettu tuntilaskutteiseksi sopimukseksi"; 
        } 
      
        echo" 
        <form method='post'> 
          <input type='submit' name='acceptBid2' value='Hyväksy hinta-arvio'/> 
        </form> ";
      }
    ?> 

    </div>
  </body>
</html>
