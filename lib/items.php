<?php
/*
 * A PHP file for working with items
 */

function item_shrink($content) {
  $parts = str_split($content, 255);
  $domain = item_domain();
  $found = item_by_content($content);

  if($found) {
    return $found;
  } else {
    for($i = 0; $i < 20; $i++) {
      $abbreviation = item_abbreviation();
      $found = item_by_abbreviation($abbreviation);
      if(!$found) {
        return entity_save('items', ['domain' => item_domain(),
                                     'abbreviation' => $abbreviation,
                                     'content0' => $parts[0],
                                     'content1' => g($parts, 1, null),
                                     'content2' => g($parts, 2, null),
                                     'content3' => g($parts, 3, null)]);
      }
    }
    return false;
  }
}

function item_by_content($content) {
  $domain = item_domain();
  $parts = str_split($content, 255);
  
  return entity_by('items', ['domain' => $domain,
                             'content0' => $parts[0],
                             'content1' => count($parts) > 1 ? $parts[1] : ['NULL'],
                             'content2' => count($parts) > 2 ? $parts[2] : ['NULL'],
                             'content3' => count($parts) > 3 ? $parts[3] : ['NULL'] ]);
}

function item_by_abbreviation($abbreviation) {
  $domain = item_domain();

  return entity_by('items', ['domain' => $domain, 'abbreviation' => $abbreviation]);
}

function item_domain() {
  return $_SERVER['SERVER_NAME'];
}

function item_abbreviation() {
  $charset = ['A','B','C','D','E','F','H','J','K','L','M','N',
              'P','Q','R','S','T','U','W','X','Y','Z',
              2,3,4,5,7,8,9];
  
   $indexes = array_rand($charset, 3);
   return implode('', array_map(function($i) use($charset) { return $charset[$i]; }, $indexes));
}

?>