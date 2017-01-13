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

global $_all_hooks;
$_all_hooks = array();

function register_hook($name, $fn, $seq = 10) {
  global $_all_hooks;


  if(!isset($_all_hooks[$name])) {
    $_all_hooks[$name] = A();
  }

  $_all_hooks[$name][] = array('seq' => $seq, 'fn' => $fn);
  uasort($_all_hooks[$name], create_function('$a,$b','return $a["seq"] - $b["seq"];'));
}

/*
 * $name, $value, $arg1, $arg2, ...
 */
function apply_hook($name, $value) {
  global $_all_hooks;

  $args  = func_get_args();
  $name  = array_shift($args);
  $value = array_shift($args);

  if(is_array(g($_all_hooks, $name))) {
    foreach($_all_hooks[$name] as $h) {
      $params = array_merge(A($value), $args);
      $value  = call_user_func_array($h['fn'], $params);
    }
  }

  return $value;
}


?>