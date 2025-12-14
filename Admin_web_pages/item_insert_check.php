<?php require_once('../Connections/connvbsa.php'); ?>
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

mysql_select_db($database_connvbsa, $connvbsa);
$query_next_id = "SELECT MAX( webpage_items.ID)+1 AS item_id FROM webpage_items";
$next_id = mysql_query($query_next_id, $connvbsa) or die(mysql_error());
$row_next_id = mysql_fetch_assoc($next_id);
$totalRows_next_id = mysql_num_rows($next_id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>


<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="DBheader"></div>
<p>&nbsp;</p>
<table width="1000" align="center">
  <tr>
    <td align="left">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><span class="red_bold">Insert an item to any of the web pages</span></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="red_bold">DOES THE ITEM YOU ARE ABOUT TO INSERT EXIST IN THE CALENDAR OF EVENTS?</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><span class="red_bold">IF IT DOES PLEASE INSERT FROM THE </span><span class="greenbg"><a href="../Admin_Calendar/A_calendar_index.php">CALENDAR</a></span></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="greenbg">If it does not exist in the calendar <a href="item_insert_webpage.php?item_id=<?php echo $row_next_id['item_id']; ?>">Insert a new Item </a></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="greenbg"><em class="red_text">Q. Why have 2 methods of inserting items to web pages? <br />
    A. To avoid duplicate attachments - PLEASE NOTE: If an item has been created from the Calendar then Information items - attachments, links, email contacts can only be inserted from the calendar, thus ensuring any updates appear in both places (avoids having 2 versions of the one document. eg entry forms, draws etc.). Items created from the calendar will show attachments both in the calendar and with the items on the web pages. </em></td></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="greenbg">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($next_id);
?>
