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
       echo("Laskujen määrä sopimuksella: ". $bills[0]['amount_of_payments'] . "<br/>");
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
       <!-- Jaetaan amount_of_payments:illä jos se on yli 1 ja sopimukselle on luotu jo yli 1 lasku. -->
       Alkuperäinen hinta: <?php if ((count($billsContract) != 1) && $bills[0]['amount_of_payments'] > 1) { echo number_format((($toolsumNoSale[0] + $worksumNoSale[0]) / $bills[0]['amount_of_payments']), 2, '.', ' '); } 
       else { echo number_format(($toolsumNoSale[0] + $worksumNoSale[0]), 2, '.', ' '); } ?><br/>

       Verojen osuus: <?php if ((count($billsContract) != 1) && $bills[0]['amount_of_payments'] > 1) { echo number_format((($tooltaxsum[0] + $worktaxsum[0]) / $bills[0]['amount_of_payments']), 2, '.', ' '); }
        else { echo number_format(($tooltaxsum[0] + $worktaxsum[0]), 2, '.', ' '); } ?><br/> 

       Kotitalousvähennykseen kelpaava osuus:
       <?php if ($customer[4] != true) { echo("asiakas ei ole kotitalousvähennyskelpoinen"); } 
         else if ($project[4] != true) { echo("työkohde ei ole kotitalousvähennyskelpoinen"); }
         else if ((count($billsContract) != 1) && $bills[0]['amount_of_payments'] > 1) { echo number_format(($worksum[0] * 0.40 / $bills[0]['amount_of_payments']), 2, '.', ' ');}
         else { echo number_format(($worksum[0] * 0.40), 2, '.', ' '); }
       ?><br/>

       <?php if ($bills[0]['bill_type_id'] == 2 || $bills[0]['bill_type_id'] == 3) { echo("Laskutuslisä: " . number_format((($bill[11] - 1)*5.00), 2, '.', ' ') . "<br/>"); } ?>

       <?php if ($bills[0]['bill_type_id'] == 3) { echo("Viivästyskorko: " . number_format(($bills[0]["total_sum"] - (($bill[11] - 1)*5.00) - $toolsum[0] - $worksum[0]), 2, '.', ' ') . "<br/>"); } ?>

       Lopullinen hinta: <?php if ((count($billsContract) != 1) && $bills[0]['amount_of_payments'] > 1) {echo number_format(($bills[0]["total_sum"]), 2, '.', ' '); }
        else if ($bills[0]['bill_type_id'] == 1) { echo number_format(($toolsum[0] + $worksum[0]), 2, '.', ' '); }
        else {echo number_format(($bills[0]["total_sum"]), 2, '.', ' '); } ?><br/>

     </div>


     <?php
      /** Näytetään laskun lähetysnäppäin jos ehdot täyttyvät
        * Jos laskun status = 1 (laskutamatta) ja contract_type = 1 (tuntilaskutteinen) 
        */
      if(($bill[5] == "1") && ($contract[2] == "1")){

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

      /** Näytetään monilaskuisen urakan laskuille mahdollisuus lähettä lasku heti, jos on vielä laskuttamatta. 
       *  laskun stautus = 1 (laskuttamtta), contract_type = 2 ja sopimuksella olemassa yli 1 lasku.
       * 
       *  Erona ylempään kohtaan: tässä ei muuteta totalsumia, se on jo määritetty muualla.
       */
      if($bill[5] == "1" && $contract[2] == "2" && count($billsContract) > 1){
        echo"</br>";
        if(isset($_POST['sendBill2'])) {
          sendBill2($bill[0]);
          
          echo "Lasku lähetetty"; 
        } 
      
        echo" 
        <form method='post'>
          <input type='submit' name='sendBill2' value='Lähetä lasku'/> 
        </form> ";
      }

      /** Näytetään laskun hyväksymisnäppäin jos ehdot täyttyvät
        * Jos laskun status = 2 (laskutettu) ja contract_type = 1 tai 2 (tuntilaskutteinen tai urakka) 
        */
      elseif(($bill[5] == "2") && (($contract[2] == "1") || ($contract[2] == "2"))) {

        echo"</br>";
        if(isset($_POST['setBillAsPaid'])) { 
          setBillAsPaid($customer, $bill, $project, $taxCredit);
          echo "Lasku kuitattu maksetuksi"; 
        } 
        
        echo" 
        <form method='post'>
          <input type='submit' name='setBillAsPaid' value='Kuittaa lasku maksetuksi'/> 
        </form> ";
      }  

      /** Näytetään urakan hyväksymisnäppäin jos ehdot täyttyvät
        * Jos laskun status = 1 (laskutamatta) ja contract_type = 2 (urakka) ja urakkaan liittyy vain 1 lasku.
        */

      elseif ($bill[5] == "1" && $contract[2] == "2" && count($billsContract) == 1) {
        echo"</br><b> Urakan laskutus ja myöhempien laskujen eräpäivien määritys </b><br/><br/>";
      
        echo"Lasku 1 lähetetään heti ja eräpäivä on 21 päivän päästä.";

        echo "<form method='post'>";
        for ($row = 2; $row <= $bills[0]["amount_of_payments"]; $row++) {
          echo "Anna laskun " . $row . " eräpäivä: <input type='date' name='bill_no_" . $row . "' value='2021-01-01'/><br/>";
        } 
          echo "<input type='submit' name='acceptBid2' value='Laskuta urakka'/>
        </form>";

        if(isset($_POST['acceptBid2'])) {
          acceptBid($contract[0], $bills[0]["amount_of_payments"], $bill[0], $totalsum, $bill[3]);
          echo "<br/>Urakka laskutettu ja mahdolliset lisälaskut luotu."; 
        }         
      }

      /** Näytetään urakkatarjouksen hyväksymisnäppäin jos ehdot täyttyvät
        * Jos laskun status = 1 (laskutamatta) ja contract_type = 3 (urakkatarjous) 
        */
      elseif(($bill[5] == "1") && ($contract[2] == "3")){
        echo"</br><b> Urakkatarjouksen hyväksyntä ja laskujen luonti </b><br/><br/>";
      
        echo"Lasku 1 lähetetään heti ja eräpäivä on 21 päivän päästä.";

        echo "<form method='post'>";
        for ($row = 2; $row <= $bills[0]["amount_of_payments"]; $row++) {
          echo "Anna laskun " . $row . " eräpäivä: <input type='date' name='bill_no_" . $row . "' value='2021-01-01'/><br/>";
        } 
          echo "<input type='submit' name='acceptBid' value='Hyväksy urakkatarjous ja luo laskut'/>
        </form>";

        if(isset($_POST['acceptBid'])) {
          acceptBid($contract[0], $bills[0]["amount_of_payments"], $bill[0], $totalsum, $bill[3]);
          echo "<br/>Urakkatarjous hyväksytty ja muutettu urakkasopimukseksi. Laskut luotu."; 
        } 
      }
      else {
        /* Jos näppäimiä ei tarvita ei näytetä mitään s*/
      }
    ?> 

    </div>
  </body>
</html>
