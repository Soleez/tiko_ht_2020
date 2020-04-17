<?php include(__DIR__.'/../controllers/Tool.php'); ?>
<?php include(__DIR__.'/general/header.php'); ?>
<html>
  <body>
    <h2>Työkalut tietokannassa</h2>
    <table>
      <thead><tr>
        <th> tool_name </th>
        <th> tool_id </th>
        <th> tool_purchase_price </th>
        <th> availability </th>
        <th> tool_selling_price </th>
        <th> unit </th>
        <th> bool_in_use </th>
        <th> vat_type_id </th>
        <th> vat_type_name </th>
        <th> vat_rate </th>
      </tr></thead>
      <tbody><?php
        for ($row = 0; $row < count($tool); $row++ ) {
          echo"<tr>";
            echo"<td>" . $tool[$row]['tool_name'] . "</td>";
            echo"<td>" . $tool[$row]['tool_id'] . "</td>";
            echo"<td>" . $tool[$row]['tool_purchase_price'] . "</td>";
            echo"<td>" . $tool[$row]['availability'] . "</td>";
            echo"<td>" . $tool[$row]['tool_selling_price'] . "</td>";
            echo"<td>" . $tool[$row]['unit'] . "</td>";
            echo"<td>" . $tool[$row]['bool_in_use'] . "</td>";
            echo"<td>" . $tool[$row]['vat_type_id'] . "</td>";
            echo"<td>" . $tool[$row]['vat_type_name'] . "</td>";
            echo"<td>" . $tool[$row]['vat_rate'] . "</td>";
          echo"</tr>";
        }
      ?><tbody>
    </table>

  </body>
</html>