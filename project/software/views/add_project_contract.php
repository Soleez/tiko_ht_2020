<?php include(__DIR__.'/../controllers/Add_project_contract.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>

  <body>
    <p>/ Etusivu / Projektit /  Lisää uusi työkohde</p>
    <h2>Lisää uusi työkohde</h2>

    <!--- Dropdown-list asiakkaista--->
    <form method="POST" action="add_project_contract.php">
    <table>
	    <tr>
          <td>Asiakas</td>
          <td>
          <select id="customer" name="customer">
              <option value="" disable selected hidden>Valitse</option>
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
    	    <td><input type="text" name="amount_of_payments" value="" placeholder="0" class="textbox"/></td>
	    </tr>
    </table>
    
    <input type="submit" name="formSubmit1" value="Lisää työkohde"/>
    <?php if (isset($viesti)) echo '<p>'.$viesti.'</p>'; ?>
    </form>
    
  </body>
</html>