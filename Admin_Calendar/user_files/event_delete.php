<?php require_once('../../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);
?>
<?php
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

if ((isset($_GET['eventID'])) && ($_GET['eventID'] != "")) {
  $deleteTourn = sprintf("DELETE FROM tournaments WHERE event_id=%s",
                       GetSQLValueString($_GET['eventID'], "int"));
  $ResultTourn = mysql_query($deleteTourn, $connvbsa) or die(mysql_error());

  $deleteCalendar = sprintf("DELETE FROM calendar WHERE event_id=%s",
                       GetSQLValueString($_GET['eventID'], "int"));
  $ResultCalendar = mysql_query($deleteCalendar, $connvbsa) or die(mysql_error());
 
  $deleteGoTo = "../calendar_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if (isset($_GET['eventID'])) {
  $eventID = $_GET['eventID'];
}

$query_events = "SELECT * FROM calendar WHERE event_id=$eventID";
$events = mysql_query($query_events, $connvbsa) or die(mysql_error());
$row_events = mysql_fetch_assoc($events);
$totalRows_events = mysql_num_rows($events);
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

?>

