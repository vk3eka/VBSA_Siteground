<?php require_once('../../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$item_id = "-1";
if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
}

$inv_id = "-1";
if (isset($_GET['inv_id'])) {
  $inv_id = $_GET['inv_id'];
}

$inv_type = "-1";
if (isset($_GET['inv_type'])) {
  $inv_type = $_GET['inv_type'];
}

$club_id = "-1";
if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

if (isset($_GET['item_id'])) {
  $deleteSQL = sprintf("DELETE FROM inv_items WHERE inv_item_id=%s",
                       GetSQLValueString($_GET['item_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($deleteSQL, $connvbsa) or die(mysql_error());


  $deleteGoTo = "inv_print_detail.php?inv_id=" . $_REQUEST['inv_id'] . "& inv_type=" . $_REQUEST['inv_type'] . "& club_id=" . $_REQUEST['club_id'];
  if (isset($_SERVER['QUERY_STRING'])) { ;
  }
  header(sprintf("Location: %s", $deleteGoTo));
}





mysql_select_db($database_connvbsa, $connvbsa);
$query_itemdel = "SELECT * FROM inv_items WHERE inv_item_id = '$item_id'";
$itemdel = mysql_query($query_itemdel, $connvbsa) or die(mysql_error());
$row_itemdel = mysql_fetch_assoc($itemdel);
$totalRows_itemdel = mysql_num_rows($itemdel);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($itemdel);
?>

