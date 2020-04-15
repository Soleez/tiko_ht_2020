<?php include(__DIR__.'/../controllers/Add_rows.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>

  <body>
    <p>/ Etusivu / Projektit / Sopimukset / Lisää tarvikkeita ja työtunteja</p>
    <h2>Lisää tarvikkeita ja työtunteja sopimukselle</h2>
    <div>Kirjataan tälle päivälle <?php echo date("Y-m-d"); ?>.</div>

    <form method="POST" action="add_rows.php">
      <div>
        <table>
          <thead>
            <tr>
              <th>Tarvike</th>
              <th>Lukumäärä</th>
              <th>Alennusprosentti</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <select id="tool1" name="tool1">
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
              <td><input type="number" name="tool_amount1" value="" min="0" /></td>
              <td><input type="number" name="tool_sale1" value="0" min="0" max="100" /></td>
            </tr>
            <tr>
              <td>
                <select id="tool2" name="tool2">
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
              <td><input type="number" name="tool_amount2" value="" min="0" /></td>
              <td><input type="number" name="tool_sale2" value="0" min="0" max="100" /></td>
            </tr>
            <tr>
              <td>
                <select id="tool3" name="tool3">
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
              <td><input type="number" name="tool_amount3" value="" min="0" /></td>
              <td><input type="number" name="tool_sale3" value="0" min="0" max="100" /></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div>
        <table>
          <thead>
            <tr>
              <th>Työn tyyppi</th>
              <th>Lukumäärä (h)</th>
              <th>Alennusprosentti</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <?php 
                  // work_type_id = 1: suunnittelu 
                  echo $worktypes[0]['work_type_name'];
                ?>
              </td>
              <td><input type="number" name="work_amount1" value="" min="0" /></td>
              <td><input type="number" name="work_sale1" value="0" min="0" max="100" /></td>
            </tr>
            <tr>
              <td>
                <?php
                  // work_type_id = 2: asennustyö 
                  echo $worktypes[1]['work_type_name'];
                ?>
              </td>
              <td><input type="number" name="work_amount2" value="" min="0" /></td>
              <td><input type="number" name="work_sale2" value="0" min="0" max="100" /></td>
            </tr>
            <tr>
              <td>
                <?php
                  // work_type_id = 3: aputyö 
                  echo $worktypes[2]['work_type_name'];
                ?>
              </td>
              <td><input type="number" name="work_amount3" value="" min="0" /></td>
              <td><input type="number" name="work_sale3" value="0" min="0" max="100" /></td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <input type="submit" name="formSubmit1" value="Kirjaa tarvikkeet/työtunnit sopimukselle"/>

      <?php if (isset($t_msg1)) echo '<p>'.$t_msg1.'</p>'; ?>
      <?php if (isset($t_msg2)) echo '<p>'.$t_msg2.'</p>'; ?>
      <?php if (isset($t_msg3)) echo '<p>'.$t_msg3.'</p>'; ?>
      <?php if (isset($w_msg1)) echo '<p>'.$w_msg1.'</p>'; ?>
      <?php if (isset($w_msg2)) echo '<p>'.$w_msg2.'</p>'; ?>
      <?php if (isset($w_msg3)) echo '<p>'.$w_msg3.'</p>'; ?>
      <?php if (isset($err_msg)) echo '<p>'.$err_msg.'</p>'; ?>
    </form>
  </body>
</html>