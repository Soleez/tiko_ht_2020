<html>
  <head>
    <title>tiko_ht_2020</title>
  </head>
  <body>

    <h2>Laskun tiedot</h2>
    <?php
        include(__DIR__.'/../controllers/Bill.php');
    ?>

    <h2>Työkalut</h2>
    <?php
        echo('Loput tunnit');
        include(__DIR__.'/../controllers/Bill2.php');
    ?>

    <p>Osaako renderoida</p>

  </body>
</html>
