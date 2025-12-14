<?php require_once('../../Connections/connvbsa.php'); ?>
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

$scrsID = "-1";
if (isset($_GET['scrsID'])) {
  $scrsID = $_GET['scrsID'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

$team_id = "-1";
if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

if ((isset($_GET['scrsID'])) && ($_GET['scrsID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM scrs WHERE scrsID=%s",
                       GetSQLValueString($_GET['scrsID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($deleteSQL, $connvbsa) or die(mysql_error());

  $deleteGoTo = "../scores_ladders_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) { 
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}


mysql_select_db($database_connvbsa, $connvbsa);
$query_scrsdel = "SELECT * FROM scrs WHERE scrsID = '$scrsID'";
$scrsdel = mysql_query($query_scrsdel, $connvbsa) or die(mysql_error());
$row_scrsdel = mysql_fetch_assoc($scrsdel);
$totalRows_scrsdel = mysql_num_rows($scrsdel);
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

