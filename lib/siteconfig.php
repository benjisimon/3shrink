<?php
/*
 * A PHP file for settig up our site. our top level config!
 */

date_default_timezone_set('UTC');

require_once(__DIR__ . '/../shared/lib/utils.php');
require_once(__DIR__ . '/../shared/lib/mysql.php');
require_once(__DIR__ . '/../shared/vendor/autoload.php');
require_once(__DIR__ . '/items.php');
require_once(__DIR__ . '/upload.php');
require_once(__DIR__ . '/private.php');

define('UPLOAD_BUCKET', 'files.3shrink.com');

function geek_val() {
  return g($_GET, 'geek', g($_COOKIE, 'geek'));
}

function maybe_geek_output($text) {
  if(geek_val()) {
    header("Content-Type: text/plain");
    echo "$text\n";
    exit();
  }
}

?>