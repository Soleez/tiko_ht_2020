<?php
  // linkki etusivulle
  echo"<a href='./home.php'> / Etusivu </a>";
  // tarkistetaan onko asetettuna
  if(isset($contractor[0])) {
    echo"<a href='./project.php?contractor=".$contractor[0]."'> / Projektit </a>";
  }
  if(isset($customer[0]) && isset($project[0])) {
    echo"<a href='./contract.php?customer=".$customer[0]."&project=".$project[0]."'> / Sopimukset </a>";
  }
  if(isset($customer[0]) && isset($project[0]) && isset($contract[0]) && !(isset($bill[0]))) {
    echo"<a href='./add_rows.php?customer=".$customer[0]."&project=".$project[0]."&contract=".$contract[0]."'></a> / Työtuntien ja tarvikkeiden kirjaus";
  }
  if(isset($contract[0]) && isset($bill[0])) {
    echo" / Lasku ";
  }
?>

