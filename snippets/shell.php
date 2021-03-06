<?php
/*
 * A PHP file for rendering the shell of our user pages
 */
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?= apply_hook('shell_title', $_SERVER['SERVER_NAME']) ?></title>
    <link rel="Stylesheet" href="<?= resource_url('css/layout.css')?>" type="text/css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>

  <body>
    <div class="page">
      <?= $body ?>
    </div>

    <div class="footer">
      <a href="/">Home</a> | <a href="http://blogbyben.com/">Built By Ben</a> |
      Bookmarklets: 
      [<?= snippet('bookmarklet', ['label' => "URL", 'src' => 'window.location']) ?>]
      [<?= snippet('bookmarklet', ['label' => "TXT", 'src' => "prompt('Text to Shrink')"]) ?>] |
      <?= snippet('protocol_handler'); ?>
    </div>
  </body>
</html>
