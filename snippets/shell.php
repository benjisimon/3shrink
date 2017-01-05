<?php
/*
 * A PHP file for rendering the shell of our user pages
 */
?>
<!DOCTYPE html>
<html>
  <head>
    <title>3shrink</title>
    <link rel="Stylesheet" href="<?= resource_url('css/layout.css')?>" type="text/css"/>
  </head>

  <body>
    <div class="page">
      <?= $body ?>
    </div>

    <div class="footer">
      <a href="/">Home</a> | <a href="http://blogbyben.com/">Built By Ben</a>
    </div>
  </body>
</html>
