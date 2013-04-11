<?php
/*
* import from drupal
*/
define('DB_QUERY_REGEXP', '/(%d|%s|%%|%f|%b|%n)/');

global $connection;
function db_connect()
{
	global $db_url, $connection;
	$args = explode(';',$db_url);
	$connection = @mysql_connect($args[0], $args[1], $args[2], TRUE, 2);
	if (!$connection || !mysql_select_db($args[3], $connection))
	{
		// Show error screen otherwise
		_db_error_page(mysql_error());
	}
	mysql_query('SET NAMES utf8', $connection);
	return $connection;
}

function db_query($query)
{
  global $quert_count;
  ++$quert_count;
	db_connect();
  $args = func_get_args();
  array_shift($args);
  if (isset($args[0]) and is_array($args[0])) { // 'All arguments in one array' syntax
    $args = $args[0];
  }
  _db_query_callback($args, TRUE);
  $query = preg_replace_callback(DB_QUERY_REGEXP, '_db_query_callback', $query);
  //echo $query;
  return _db_query($query);
}

function _db_query($query, $debug = 0) {
  global $connection;
  $result = mysql_query($query);

  if (!mysql_errno()) {
    return $result;
  }
  else {
    // Indicate to drupal_error_handler that this is a database error.
   // ${DB_ERROR} = TRUE;
    trigger_error(mysql_error($connection)."\nquery: ".$query, E_USER_WARNING);
    return FALSE;
  }
}

function _db_query_callback($match, $init = FALSE) {
  static $args = NULL;
  if ($init) {
    $args = $match;
    return;
  }

  switch ($match[1]) {
    case '%d': // We must use type casting to int to convert FALSE/NULL/(TRUE?)
      $value = array_shift($args);
      // Do we need special bigint handling?
      if ($value > PHP_INT_MAX) {
        $precision = ini_get('precision');
        @ini_set('precision', 16);
        $value = sprintf('%.0f', $value);
        @ini_set('precision', $precision);
      }
      else {
        $value = (int) $value;
      }
      // We don't need db_escape_string as numbers are db-safe.
      return $value;
    case '%s':
      return mysql_real_escape_string(array_shift($args));
    case '%n':
      // Numeric values have arbitrary precision, so can't be treated as float.
      // is_numeric() allows hex values (0xFF), but they are not valid.
      $value = trim(array_shift($args));
      return is_numeric($value) && !preg_match('/x/i', $value) ? $value : '0';
    case '%%':
      return '%';
    case '%f':
      return (float) array_shift($args);
    case '%b': // binary data
      return "'" . mysql_real_escape_string(array_shift($args)) . "'";
  }
}

function _db_error_page($error = '') {
  $message = '<p>The site is currently not available due to technical problems. Please try again later. Thank you for your understanding.</p>';
  //$message .= $error;
  echo $message;
  exit;
}

function db_result($result) {
  if ($result && mysql_num_rows($result) > 0) {
    // The mysql_fetch_row function has an optional second parameter $row
    // but that can't be used for compatibility with Oracle, DB2, etc.
    $array = mysql_fetch_row($result);
    return $array[0];
  }
  return FALSE;
}
?>