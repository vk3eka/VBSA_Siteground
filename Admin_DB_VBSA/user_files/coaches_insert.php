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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO coaches_vbsa (coach_id, memb_id, `class`, `comment`, URL) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['coach_id'], "int"),
                       GetSQLValueString($_POST['memb_id'], "int"),
                       GetSQLValueString($_POST['class'], "text"),
                       GetSQLValueString($_POST['comment'], "text"),
                       GetSQLValueString($_POST['URL'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../coaches.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_coaches = "SELECT MemberID, memb_id, FirstName, LastName, Email, MobilePhone, coach_id, class, comment, URL, coach_order FROM members, coaches_vbsa WHERE members.MemberID = coaches_vbsa.memb_id ORDER BY coach_order";
$coaches = mysql_query($query_coaches, $connvbsa) or die(mysql_error());
$row_coaches = mysql_fetch_assoc($coaches);
$totalRows_coaches = mysql_num_rows($coaches);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td class="red_bold">Insert a new Coach</td>
    <td class="greenbg">&nbsp;</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="3">Please Note:You will need a member id to insert a new coach, please search the members table. <br />
      If the person does not exist in the members table please insert from the members area, then insert here</td>
  </tr>
</table>
<p>&nbsp;</p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Member ID:</td>
      <td><input type="text" name="memb_id" value="" size="10" /> 
      (Name, phone and email come from the members table and are auto inserted as per the Member ID)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Class:</td>
      <td><input type="text" name="class" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">About:</td>
      <td><textarea name="comment" cols="120" rows="4"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Website URL:</td>
      <td><input type="text" name="URL" value="" size="60" />Optional: visit their webpage and copy the web address out of the address bar in the browser</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ordered:</td>
      <td>Auto inserted as &quot;No&quot; - Please edit to set the order you want this person to appear in</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert New Coach" /></td>
    </tr>
  </table>
  <input type="hidden" name="coach_id" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($coaches);
?>
