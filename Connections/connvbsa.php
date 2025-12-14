<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
//echo("Username " . $_SESSION['MM_Username'] . "<br>");

$hostname_connvbsa = "127.0.0.1";
$database_connvbsa = "vbsa3364_vbsa2";
$username_connvbsa = "peterj";
$password_connvbsa = "abj059XZ@!";

//$hostname_connvbsa = "localhost";
//$database_connvbsa = "vbsa3364_vbsa2";
//$username_connvbsa = "vbsa3364_AlPal";
//$password_connvbsa = "Lurch147";
$connvbsa = mysqli_connect($hostname_connvbsa, $username_connvbsa, $password_connvbsa) or trigger_error(mysql_error(),E_USER_ERROR); 

# Aliased functions for PHP 7 migration

function mysql_connect($host, $username, $password, $dbname) {
	return mysqli_connect($host, $username, $password, $dbname);
}

function mysql_select_db($db, $mysql) {
	return mysqli_select_db($mysql, $db);	
}

function mysql_list_dbs($connection) {
	return mysqli_list_dbs($connection);
}

function mysql_close($connection) {
	return mysqli_close($connection);
}

function mysql_free_result($result) {
	return mysqli_free_result($result);
}

function mysql_fetch_all($result) {
	return mysqli_fetch_all($result);
}

function mysql_insert_id($connection) {
	return mysqli_insert_id($connection);	
}

function mysql_errno() {
	global $connvbsa;
	return mysqli_errno($connvbsa);
}

function mysql_fetch_assoc($result) {
	return mysqli_fetch_assoc($result);
}

function mysql_fetch_field($result) {
	return mysqli_fetch_field($result);
}

function mysql_fetch_object() {
	return mysqli_fetch_object();
}

function mysql_query($query) {
	global $connvbsa;
	return mysqli_query($connvbsa, $query);
}

function mysql_num_rows($result) {
	return mysqli_num_rows($result);
}

function mysql_num_fields($result) {
	return mysqli_num_fields($result);
}

function mysql_error() {
	global $connvbsa;
	return mysqli_error($connvbsa);
}

function mysql_fetch_array($result) {
	return mysqli_fetch_array($result);
}

function mysql_fetch_row($result) {
	return mysqli_fetch_row($result);
}

function mysql_real_escape_string($escapestring) {
	global $connvbsa;
  if(isset($escapestring)) {
    return mysqli_real_escape_string($connvbsa, $escapestring);
  } 
	return "";
}

function mysql_escape_string($escapestring) {
	global $connvbsa;
	return mysqli_escape_string($connvbsa, $escapestring);
}

function mysql_data_seek($result, $offset) {
	return mysqli_data_seek($result, $offset);
}

function eregi($pattern, $string) {
	return preg_match($pattern, $string);
}

# Deprecated functions

function mysql_result($res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
}

function mysql_field_name($result, $field_offset) {
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}

# Commented out by Alec Spyrou 22.3.22 after website error due to this - 
#function get_magic_quotes_gpc() {
#  return false;
#}
?>