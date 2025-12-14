<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE clubs_contact SET club_id=%s, cont_memb_id=%s, last_update=%s, update_by=%s, cont_type=%s, cont_title=%s WHERE cont_id=%s",
                       GetSQLValueString($_POST['club_id'], "int"),
                       GetSQLValueString($_POST['cont_memb_id'], "int"),
                       GetSQLValueString($_POST['last_update'], "date"),
                       GetSQLValueString($_POST['update_by'], "text"),
                       GetSQLValueString($_POST['cont_type'], "text"),
                       GetSQLValueString($_POST['cont_title'], "text"),
                       GetSQLValueString($_POST['cont_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "clubs_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);

if (isset($_GET['cont_id'])) {
  $cont_id = $_GET['cont_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_contact = "SELECT cont_id, club_id, cont_memb_id, last_update, update_by, cont_type, cont_title, ClubNumber, ClubTitle, members.MemberID, members.FirstName, members.LastName FROM clubs_contact, clubs, members WHERE cont_memb_id=MemberID AND club_id=ClubNumber AND cont_id = '$cont_id'";
$contact = mysql_query($query_contact, $connvbsa) or die(mysql_error());
$row_contact = mysql_fetch_assoc($contact);
$totalRows_contact = mysql_num_rows($contact);

mysql_select_db($database_connvbsa, $connvbsa);
$query_club_list = "SELECT team_club, team_club_id FROM Team_entries WHERE team_club is not null GROUP BY team_club_id ORDER BY team_club ASC";
$club_list = mysql_query($query_club_list, $connvbsa) or die(mysql_error());
$row_club_list = mysql_fetch_assoc($club_list);
$totalRows_club_list = mysql_num_rows($club_list);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>


<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1"  >
  <table align="center">
    <tr valign="baseline">
      <td colspan="2" align="left" class="red_bold">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="left" class="red_bold">Edit Club details for: <?php echo $row_contact['FirstName']; ?> <?php echo $row_contact['LastName']; ?>, From: <?php echo $row_contact['ClubTitle']; ?>:</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Club ID</td>
      <td><input type="text" name="club_id" value="<?php echo htmlentities($row_contact['club_id'], ENT_COMPAT, 'utf-8'); ?>" size="10" /> 
        Refer list of clubs below</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Member ID:</td>
      <td><input type="text" name="cont_memb_id" value="<?php echo htmlentities($row_contact['cont_memb_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Contact Type:</td>
      <td><select name="cont_type">
        <option value="Club Management" <?php if (!(strcmp("Club Management", htmlentities($row_contact['cont_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Club Management</option>
        <option value="Team organiser" <?php if (!(strcmp("Team organiser", htmlentities($row_contact['cont_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Team organiser</option>
        <option value="Invoice to" <?php if (!(strcmp("Invoice to", htmlentities($row_contact['cont_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Invoice to</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Job Title:</td>
      <td><input type="text" name="cont_title" value="<?php echo htmlentities($row_contact['cont_title'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update contact" /></td>
    </tr>
  </table>

    <p>&nbsp;</p>
    <p>
      <input type="hidden" name="cont_id" value="<?php echo $row_contact['cont_id']; ?>" />
      <input type="hidden" name="update_by" value="<?php echo $row_getusername['name']; ?>" />
      <input type="hidden" name="last_update" value="<?php echo date("Y-m-d")?> " />
      <input type="hidden" name="MM_update" value="form1" />
  </p>
</form>


  <table align="center">
    <tr>
      <td>Club</td>
      <td>Club ID</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_club_list['team_club']; ?></td>
        <td><?php echo $row_club_list['team_club_id']; ?></td>
      </tr>
      <?php } while ($row_club_list = mysql_fetch_assoc($club_list)); ?>
  </table>

</body>
</html>
<?php
mysql_free_result($getusername);

mysql_free_result($contact);

mysql_free_result($club_list);
?>

