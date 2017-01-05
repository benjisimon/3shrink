<?php
/*
 * A PHP file for to help with SQL processing.
 */

/*
 * A helper function to format an SQL statement. If more than one
 * arg is provided, we assume they are arguments to a sprintf type string, but
 * we need to format them for the SQL.
 */
function sql_fmt() {
  global $_dbh;
  $args = func_get_args();
  if(count($args) == 1) {
    return $args[0];
  }
  $template = array_shift($args);
  $template = preg_replace(array('/%%/', '/%([^%s])/'), array('%', '%%$1'), $template);
  $enc_args = array();
  foreach($args as $v) {
    if(is_array($v)) {
      $enc_args[] = '(' . implode(',', array_map(fn1('return sql_fmt("%s", $x);'), $v)) . ')';
    } else if(preg_match('/^[0-9]+[.]?[0-9]*$/', $v)) {
      $enc_args[] = $v;
    } else if($v === null) {
      $enc_args[] = 'NULL';
    } else if($v === false) {
      $enc_args[] = 0;
    } else {
      $enc_args[] = "'" . $_dbh->real_escape_string($v) . "'";
    }
  }
  array_unshift($enc_args, $template);
  return call_user_func_array("sprintf", $enc_args);
}

function sql_fmt_array($args) {
  return call_user_func_array('sql_fmt', $args);
}

function sql_fmt_criteria($criteria, $options = array()) {
  $where_sql = A();
  foreach($criteria as $k => $v) {
    if(is_array($v)) {
      switch($v[0]) {
        case 'IN':
          $where_sql[] = sql_fmt("$k IN %s", $v[1]);
          break;
        case 'NOT NULL':
          $where_sql[] = sql_fmt("$k IS NOT NULL");
          break;
        case 'LIKE':
          $where_sql[] = sql_fmt("$k LIKE %s", $v[1]);
          break;
        case '<>':
        case '>':
        case '<':
        case '>=':
        case '<=':
        case '=':
          $where_sql[] = sql_fmt("$k {$v[0]} %s", $v[1]);
          break;
        case 'NULL':
          $where_sql[] = sql_fmt("$k IS NULL");
          break;
        case 'BETWEEN':
          $where_sql[] = sql_fmt("$k BETWEEN %s AND %s", $v[1][0], $v[1][1]);
          break;
        default:
          trigger_error("Unkown operator: " . print_r($v, true), E_USER_ERROR);
      }
    } else {
      $where_sql[] = sql_fmt("($k = %s)", $v);
    }
  }
  $glue = g($options, 'glue', 'AND');

  return implode(" $glue\n", $where_sql);
}


$_dbh = false;
global $_dbh;

function mysql_do_connect($host, $user, $pass, $db) {
  global $_dbh;

  $_dbh = mysqli_connect($host, $user, $pass, $db);
  if($_dbh->connect_errno) {
    trigger_error(E_USER_ERROR, "Failed to connect: $user@$host:$db : " . $_dbh->connect_error);
  }
  return true;
}

/*
 * Private function - do not call directly
 */
function mysql_do_exec($sql) {
  global $_dbh;

  $start  = microtime(true);
  $result = $_dbh->query($sql);
  $end    = microtime(true);
  if($result) {
    if(defined('MYSQL_QUERY_LOGGER')) {
      call_user_func(MYSQL_QUERY_LOGGER, $sql, $result, round($end - $start, 4));
    }
    return $result;
  } else {
    $error = $_dbh->error;
    $_dbh->query("ROLLBACK");
    trigger_error("SQL exec failed: [$sql]: $error", E_USER_ERROR);
  }
}

function mysql_exec() {
  $all_args = func_get_args();
  $sql = sql_fmt_array($all_args);
  $result = mysql_do_exec($sql);
  return $result;
}

/*
 * Run the SQL query and return back an array of results.
 * Does the scooping out of rows.
 */
function mysql_eval() {
  global $_dbh;

  $all_args = func_get_args();
  $sql = sql_fmt_array($all_args);
  $results = mysql_do_exec($sql);
  $data = array();
  while($row = $results->fetch_assoc()) {
    $data[] = $row;
  }
  return $data;
}

/*
 * Like mysql_eval, but only return the first item in each row.
 * useful for getting a simple list from an SQL query
 */
function mysql_list() {
  global $_dbh;

  $all_args = func_get_args();
  $sql = sql_fmt_array($all_args);
  $results = mysql_do_exec($sql);
  $data = array();
  while($row = $results->fetch_assoc()) {
    $data[] = $row[0];
  }
  return $data;
}

function mysql_get() {
  $all_args = func_get_args();
  $sql = sql_fmt_array($all_args);
  $results = mysql_do_exec($sql);
  $data = $results->fetch_assoc();
  return $data;
}

function mysql_value() {
  $all_args = func_get_args();
  $sql = sql_fmt_array($all_args);
  $results = mysql_do_exec($sql);
  $data = $results->fetch_array();
  return $data ? $data[0] : false;
}

function sql_dateify($date) {
  return $date ? date('Y-m-d', is_numeric($date) ? $date : strtotime($date)) : null;
}

function sql_timestampify($date) {
  return $date ? date('Y-m-d H:i:s', is_numeric($date) ? $date : strtotime($date)) : null;
}

function as_entity($table, $id_or_obj, $where = false) {
  global $_dbh;

  if(is_numeric($id_or_obj)) {
    return mysql_get("SELECT * FROM $table WHERE id = %s", $id_or_obj);
  } else if(is_array($id_or_obj)) {
    return $id_or_obj;
  } else if(is_array($where)) {
    $clauses = array();
    foreach($where as $col => $val) {
      $clauses[] = "$col = '" . $_dbh->real_escape_string($val) . "'";
    }
    return mysql_get("SELECT * FROM $table WHERE " . implode($clauses, " AND "));
  } else {
    return false;
  }
}

function entity_hop() {
  $args = func_get_args();
  $entity = array_shift($args);
  foreach($args as $what) {
    if(isset($entity["{$what}_id"])) {
      $id = $entity["{$what}_id"];
      $entity = call_user_func("as_{$what}", $id);
    } else {
      trigger_error("Failed to walk the entity. Failed at: $what", E_USER_ERROR);
    }
  }
  return $entity;
}

function entity_by($table, $params) {
  if(empty($params)) {
    trigger_error("Need at least one param for entity_by($table)", E_USER_ERROR);
  }

  return mysql_get("SELECT * FROM $table WHERE " . sql_fmt_criteria($params));
}

function entities_by($table, $params, $options = array()) {
  if(empty($params)) {
    trigger_error("Need at least one param for entities_by($table)", E_USER_ERROR);
  }

  return mysql_eval("SELECT * FROM $table WHERE " . sql_fmt_criteria($params));
}

function entity_delete($table, $id) {
  mysql_exec("DELETE FROM $table WHERE id = %s LIMIT 1", $id);
}

function entity_id($data) {
  return $data['id'];
}

/*
 * Save our data. If we have an 'id' col and it's non null, assume this is
 * an insert. Otherwise, assume it's an update. Return the row inserted.
 */
function entity_save($table, $data) {
  global $_dbh;

  if(g($data, 'id', null) == null) {
    $cols   = implode(',', array_keys($data));
    $params = implode(',', array_fill(0, count($data), "%s"));
    $args   = array_merge(array("INSERT INTO $table($cols) VALUES($params)"), array_values($data));
    mysql_exec(call_user_func_array("sql_fmt", $args));
    return as_entity($table, $_dbh->insert_id);
  } else {
    $id = $data['id'];
    unset($data['id']);
    $sets = implode(" = %s,", array_keys($data)) . " = %s";
    $args = array_merge(array("UPDATE $table SET $sets WHERE id = %s"),
                        array_values($data),
                        array($id));
    mysql_exec(call_user_func_array("sql_fmt", $args));
    return as_entity($table, $id);
  }
}

?>