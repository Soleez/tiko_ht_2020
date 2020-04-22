<?php include(__DIR__.'/../controllers/Add_project_contract.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>
  <body>
    <div class="mainDiv">
      <h2>Lisää uusi työkohde</h2>
  
      <!--- Dropdown-list asiakkaista--->
      <form method="POST">
      <table>
        <tr>
          <td>Valitse asiakas:</td>
          <td>Lisää uusi asiakas:</td> 
        </tr>
        <tr>
          <td>
          <select id="bool_tax_credit" name="bool_tax_credit">
              <option value="" disable selected hidden>Valitse</option>
              <option value="t">kyllä</option>
              <option value="f">ei</option>
          </select>
          </td>
	    </tr>
	    <tr>
    	    <td>Sopimustyyppi:</td>
    	    <td>
            <select id="contract_type" name="contract_type" onchange="restrictCount(this.value)">
              <option value=0 disable selected hidden>Valitse</option>
            <select id="customer" name="customer">
              <option value="" >Valitse</option>
              <?php 
              // Asetetaan kyselyn arvot rivi kerrallaan optioneiksi
              for ($row = 0; $row < count($customers); $row++ ) {
              ?>
              <option value="<?php echo $customers[$row]['customer_id'];?>"><?php echo $customer_name = $customers[$row]['customer_name']; ?></option>
              <?php
              }
              ?>
            </select>
          </td>
	    </tr>
      <tr>
    	    <td>Laskujen lukumäärä:</td>
    	    <td><input type="number" min="1" max="20"  name="amount_of_payments" id="amount_of_payments" value="1" class="textbox"/></td>
	    </tr>
    </table>
    
    <input type="submit" name="formSubmit1" value="Lisää työkohde"/>
    <?php if (isset($viesti)) echo '<p>'.$viesti.'</p>'; ?>
    </form>
    
          <td>
            <label for="cName">Asiakkaan nimi: </label><input type="text" id="cName" name="cName" class="textbox"/><br>
            <label for="cAddress">Asiakkaan osoite: </label><input type="text" id="cAddress" name="cAddress" class="textbox"/><br>
            <label for="bool_tax_credit_customer">Kotitalousvähennys kelpoinen: </label>
            <select id="bool_tax_credit_customer" name="bool_tax_credit_customer">
                <option value="" disable selected hidden>Valitse</option>
                <option value="t">kyllä</option>
                <option value="f">ei</option>
            </select>
          </td>
        </tr>
       
      </table>
  
      <table>
	      <tr>
      	    <td>Työkohteen nimi:</td>
      	    <td><input type="text" name="name" value="" placeholder="max 60 merkkiä" class="textbox"/></td>
	      </tr>
	      <tr>
      	    <td>Työkohteen osoite:</td>
      	    <td><input type="text" name="address" value=""  placeholder="max 60 merkkiä" class="textbox"/></td>
        </tr>
        <tr>
            <td>Kotitalousvähennys:</td>
            <td>
            <select id="bool_tax_credit" name="bool_tax_credit">
                <option value="" disable selected hidden>Valitse</option>
                <option value="t">kyllä</option>
                <option value="f">ei</option>
            </select>
            </td>
	      </tr>
	      <tr>
      	    <td>Sopimustyyppi:</td>
      	    <td>
              <select id="contract_type" name="contract_type">
                <option value=0 disable selected hidden>Valitse</option>
                <?php 
                // Asetetaan kyselyn arvot rivi kerrallaan optioneiksi
                for ($row = 0; $row < count($contractTypes); $row++ ) {
                ?>
                <option value="<?php echo $contractTypes[$row]['contract_type_id'];?>"><?php echo $contract_type = $contractTypes[$row]['contract_type_name']; ?></option>
                <?php
                }
                ?>
              </select>
            </td>
	      </tr>
        <tr>
      	    <td>Laskujen lukumäärä:</td>
      	    <td><input type="number" min="1" max="20"  name="amount_of_payments" value="" placeholder="0" class="textbox"/></td>
	      </tr>
      </table>
      
      <input type="submit" name="formSubmit1" value="Lisää työkohde"/>
      <?php if (isset($viesti)) echo '<p>'.$viesti.'</p>'; ?>
      </form>
    </div>
  </body>

  <script>
    function restrictCount(val) {
      let amount = document.getElementById("amount_of_payments");
      if (val==1 || val==4) {
        amount.value = 1;
        amount.disabled = true;
      }
      else {
        amount.disabled = false;
      }
    }
  </script>

</html>