<?php include(__DIR__.'/../controllers/Add_rows.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>

<html>

  <body>
    <h2>Lisää tarvikkeita ja työtunteja työkohteelle</h2>
      <div>Kirjataan tälle päivälle <?php echo date("Y-m-d"); ?></div>

    <form method="POST" action="add_rows.php">
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
            <td><input type="number" name="sale" value="0" min="0" max="100" /></td>
          </tr>
        </tbody>
      </table>

      <!--
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
              <select id="work" name="work">
                <option value="" disable selected hidden>Valitse</option>
                <?php 
                // for ($row = 0; $row < count($worktypes); $row++ ) {
                  ?>
                  <option value="<?php // echo $worktypes[$row]['work_type_id'];?>"><?php // echo $work_type_name = $worktypes[$row]['work_type_name']; ?></option>
                  <?php 
                // }
                ?>
              </select>
            </td>
            <td><input type="number" name="amount2" value="" min="0" /></td>
            <td><input type="number" name="sale2" value="0" min="0" max="100" /></td>
          </tr>
        </tbody>
      </table>
      -->
      
      <input type="submit" name="formSubmit1" value="Kirjaa tarvikkeet/työtunnit työkohteeseen"/>

      <?php if (isset($viesti)) echo '<p>'.$viesti.'</p>'; ?>
      <?php if (isset($viesti3)) echo '<p>'.$viesti3.'</p>'; ?>
    </form>
  </body>
</html>