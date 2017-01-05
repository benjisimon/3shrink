<?php
/*
 * A PHP file for responsible for shriking a URL
 */
require_once('lib/siteconfig.php');

$content = g($_GET, 'i');

if(!$content) {
  echo snippet('pages/error', ['message' => "Can't shrink nothing.", 'code' => 400]);
} else {
  $item = item_shrink($content);

  if($item) {
    echo snippet('pages/shrunk', array('item' => $item));
  } else {
    echo snippet('pages/error', ['message' => "Something horrible went wrong.", 'code' => 500]);
  }
}

?>