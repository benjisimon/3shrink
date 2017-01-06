<?php
/*
 * A PHP file for settig up our site. our top level config!
 */

require_once(__DIR__ . '/../shared/lib/utils.php');
require_once(__DIR__ . '/../shared/lib/mysql.php');
require_once(__DIR__ . '/items.php');

mysql_do_connect("bighugemap.c7wxmnlmazfd.us-east-1.rds.amazonaws.com", 
                 "3shrink", "9c07T42&x&#I", "3shrink");

function maybe_geek_output($text) {
  if(g($_GET, 'geek')) {
    header("Content-Type: text/plain");
    echo "$text\n";
    exit();
  }
}

?>