<?php
/*
 * A PHP file for being utility functions
 */

function g($array, $index, $default = false) {
  return isset($array[$index]) ? $array[$index] : $default;
}

function A() {
  $array = func_get_args();
  return $array;
}

function snippet($_name, $_params = array()) {
  extract($_params);
  ob_start();
  require(__DIR__ . "/../../snippets/$_name.php");
  $content = ob_get_clean();
  return $content;
}

global $_snippet_stack;
$_snippet_stack = array();

function start_snippet($name, $params = array()) {
  global $_snippet_stack;
  array_push($_snippet_stack, array('__snippet_name' => $name) + $params);
  ob_start();
}

function end_snippet() {
  global $_snippet_stack;
  $params = array_pop($_snippet_stack);
  $body   = ob_get_clean();
  return snippet($params['__snippet_name'], array('body' => $body) + $params);
}

function resource_url($path) {
  $abs = __DIR__ . "/../../$path";
  return "/$path?xcache=" . filemtime($abs);
}

function esc_attr($html) {
  $html = htmlspecialchars($html);
  $html = preg_replace(array('/["]/', "/[']/"),
                       array("&#34;", "&#39;"),
                       $html);
  return $html;
}

?>