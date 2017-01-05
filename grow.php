<?php
/*
 * A PHP file for expanding an abbreviation
 */
require_once('lib/siteconfig.php');

$abbreviation = strtoupper(g($_GET, 'i'));

if(strlen($abbreviation) != 3) {
  echo snippet('pages/error', ['message' => "That doesn't look right.", 'code' => 400]);
} else {
  $found = item_by_abbreviation($abbreviation);
  if($found) {
    $content = item_content($found);
    if(strpos($content, 'http') === 0) {
      header("Location: $content");
    } else {
      echo snippet('pages/grew', ['item' => $found, 'content' => $content]);
    }
  } else {
    echo snippet('pages/error', ['message' => "Sorry, {$abbreviation} wasn't found", 'code' => 404]);
  }
}

?>