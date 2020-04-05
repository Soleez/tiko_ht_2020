<?php include(__DIR__.'/../controllers/Tapahtuma1.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>

  <body>
    <h2>Lisää uusi työkohde</h2>

    <!--- Dropdown-list asiakkaista--->
    <form method="POST" action="tapahtuma1.php">
    <label for="customer">Asiakas</label>
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
    
    <table>
	    <tr>
    	    <td>Työkohteen nimi:</td>
    	    <td><input type="text" name="name" value="" class="textbox"/></td>
	    </tr>
	    <tr>
    	    <td>Työkohteen osoite:</td>
    	    <td><input type="text" name="address" value="" class="textbox"/></td>
      </tr>
      <tr>
    	    <td>Kotitalousvähennys: t/f </td>
    	    <td><input type="text" name="bool_tax_credit" value="" class="textbox"/></td>
	    </tr>
	    
    </table>
    
    <input type="submit" name="formSubmit1" value="Lisää työkohde"/>
    <?php if (isset($viesti)) echo '<p>'.$viesti.'</p>'; ?>
    </form>
    
  </body>
</html>