<?php include(__DIR__.'/../controllers/Tapahtuma2.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>

  <body>
    <h2>Lisää tarvikkeita työkohteelle</h2>
      <div>Kirjataan tälle päivälle <?php echo date("Y-m-d"); ?></div>

    <!-- Dropdown-list asiakkaista ja työkohteista, jolla haetaan oikea contract_id -->
    <form method="POST" action="tapahtuma2.php">
      <label for="cust_proj">Asiakas ja työkohde</label>
      <select id="cust_proj" name="cust_proj">
          <option value="" disable selected hidden>Valitse</option>
          <?php 
          // Asetetaan kyselyn arvot rivi kerrallaan optioneiksi
          for ($row = 0; $row < count($custsProjs); $row++ ) {
          ?>
          <option value="<?php echo $custsProjs[$row]['contract_id'];?>"><?php echo $customer_name = $custsProjs[$row]['customer_name']; ?>: <?php echo $project_name = $custsProjs[$row]['project_name']; ?></option>
          <?php
          }
          ?>
      </select>
      
      <table>
        <thead>
          <tr>
            <th>Työkalu</th>
            <th>Lukumäärä</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <select id="tool" name="tool">
                <option value="" disable selected hidden>Valitse</option>
                <?php 
                // Asetetaan kyselyn arvot rivi kerrallaan optioneiksi
                for ($row = 0; $row < count($tools); $row++ ) {
                ?>
                <option value="<?php echo $tools[$row]['tool_id'];?>"><?php echo $tool_name = $tools[$row]['tool_name']; ?>, <?php echo $tool_unit = $tools[$row]['unit']; ?></option>
                <?php
                }
                ?>
              </select>
            </td>
            <td><input type="number" name="amount" value="" min="0" /></td>
          </tr>
        </tbody>
      </table>
      
      <input type="submit" name="formSubmit1" value="Kirjaa tarvikkeet työkohteeseen"/>

      <?php if (isset($viesti)) echo '<p>'.$viesti.'</p>'; ?>
    </form>
  </body>
</html>