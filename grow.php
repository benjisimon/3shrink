<?php
/*
 * A PHP file for expanding an abbreviation
 */
require_once('lib/siteconfig.php');

$abbreviation = strtoupper(g($_GET, 'i', ''));
$abbreviation = substr($abbreviation, -3);

if(strlen($abbreviation) != 3) {
  echo snippet('pages/error', ['message' => "That doesn't look right.", 'code' => 400]);
} else {
  $found = item_by_abbreviation($abbreviation);
  if($found) {
    $content = item_content($found);
    maybe_geek_output($content);
    if(strpos($content, 'http') === 0 && preg_match('|^http[^\r\n ]+$|', $content)) {
      header("Location: $content");
    } else {
      header("Content-Type: text/plain");
      echo $content;
    }
  } else {
    echo snippet('pages/error', ['message' => "Sorry, {$abbreviation} wasn't found", 'code' => 404]);
  }
}

?>
