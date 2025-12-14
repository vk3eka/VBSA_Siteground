<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer";
$MM_donotCheckaccess = "false";

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
    if (($strUsers == "") && false) { 
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
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
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
  /*
  $updateSQL = sprintf("UPDATE tourn_entry SET tourn_memb_id=%s, tournament_number=%s, amount_entry=%s, entered_by=%s, how_paid=%s, entry_confirmed=%s, seed=%s, wcard=%s, ranked=%s, rank_pts=%s, junior_cat=%s WHERE ID=%s",
                       GetSQLValueString($_POST['tourn_memb_id'], "int"),
                       GetSQLValueString($_POST['tournament_number'], "int"),
                       GetSQLValueString($_POST['amount_entry'], "int"),
                       GetSQLValueString($_POST['entered_by'], "text"),
                       GetSQLValueString($_POST['how_paid'], "text"),
                       GetSQLValueString(isset($_POST['entry_confirmed']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['seed'], "int"),
                       GetSQLValueString($_POST['wcard'], "int"),
                       GetSQLValueString($_POST['ranked'], "int"),
                       GetSQLValueString($_POST['rank_pts'], "int"),
                       GetSQLValueString($_POST['junior_cat'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));
  */

  // removed seed 10/04/2024
  $updateSQL = sprintf("UPDATE tourn_entry SET tourn_memb_id=%s, tournament_number=%s, amount_entry=%s, entered_by=%s, how_paid=%s, entry_confirmed=%s, wcard=%s, ranked=%s, rank_pts=%s, junior_cat=%s WHERE ID=%s",
                       GetSQLValueString($_POST['tourn_memb_id'], "int"),
                       GetSQLValueString($_POST['tournament_number'], "int"),
                       GetSQLValueString($_POST['amount_entry'], "int"),
                       GetSQLValueString($_POST['entered_by'], "text"),
                       GetSQLValueString($_POST['how_paid'], "text"),
                       GetSQLValueString(isset($_POST['entry_confirmed']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['wcard'], "int"),
                       GetSQLValueString($_POST['ranked'], "int"),
                       GetSQLValueString($_POST['rank_pts'], "int"),
                       GetSQLValueString($_POST['junior_cat'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

//echo("SQL " . $updateSQL . "<br>");
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../tournament_detail.php"; 
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
}

if (isset($_GET['player_id'])) {
  $player_id = $_GET['player_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_players1 = "SELECT tourn_entry.ID, members.MemberID, members.LastName, members.FirstName, tourn_entry.tourn_memb_id, tourn_entry.tournament_number, tourn_entry.amount_entry, tourn_entry.entered_by, tourn_entry.how_paid, entry_confirmed, tourn_entry.seed, tourn_entry.junior_cat, tourn_entry.tourn_date_ent, members.Email, members.MobilePhone, tourn_entry.wcard, tourn_entry.ranked, tourn_entry.rank_pts FROM tourn_entry, members WHERE ID = '$player_id' AND tourn_entry.tourn_memb_id=members.MemberID";
//echo($query_players1 . "<br>");
$players1 = mysql_query($query_players1, $connvbsa) or die(mysql_error());
$row_players1 = mysql_fetch_assoc($players1);
$totalRows_players1 = mysql_num_rows($players1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<table width="600" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td width="395" align="left"><span class="red_bold">Edit this player : </span><?php echo $row_players1['FirstName']; ?> <?php echo $row_players1['LastName']; ?></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1"  onsubmit="return doit()" >
  <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Member ID:</td>
      <td><input type="text" name="tourn_memb_id" value="<?php echo htmlentities($row_players1['tourn_memb_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Tournament number:</td>
      <td><input type="text" name="tournament_number" value="<?php echo htmlentities($row_players1['tournament_number'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Amount entry:</td>
      <td><input type="text" name="amount_entry" value="<?php echo htmlentities($row_players1['amount_entry'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Entered by:</td>
      <td>
      <input type="text" name="entered_by" value="<?php echo htmlentities($row_players1['entered_by'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">How paid:</td>
      <td><select name="how_paid">
        <option value="PP" selected="selected"<?php if (!(strcmp("PP", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>PP</option>
        <option value="Cash" <?php if (!(strcmp("Cash", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Cash</option>
        <option value="BT" <?php if (!(strcmp("BT", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>BT</option>
        <option value="Chq" <?php if (!(strcmp("Chq", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Chq</option>
        <option value="Other" <?php if (!(strcmp("Other", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Other</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Confirmed?</td>
      <td><input type="checkbox" name="entry_confirmed"  id="entry_confirmed"  <?php if (!(strcmp(htmlentities($row_players1['entry_confirmed'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Seed:</td>
      <td><input type="text" name="seed" value="" size="32" /> (disabled)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Wildcard:</td>
      <td><input type="text" name="wcard" value="<?php $row_players1['wcard']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ranked:</td>
      <td><input type="text" name="ranked" value="<?php echo htmlentities($row_players1['ranked'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Rank Points</td>
      <td><input type="text" name="rank_pts" value="<?php echo htmlentities($row_players1['rank_pts'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Junior Cat:</td>
      <td><select name="junior_cat">
        <option value="na" selected="selected"<?php if (!(strcmp("na", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Not Required</option>
        <option value="U12" <?php if (!(strcmp("U12", htmlentities($row_players1['junior_cat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>U12</option>
        <option value="U15" <?php if (!(strcmp("U15", htmlentities($row_players1['junior_cat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>U15</option>
        <option value="U18" <?php if (!(strcmp("U18", htmlentities($row_players1['junior_cat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>U18</option>
        <option value="U21" <?php if (!(strcmp("U21", htmlentities($row_players1['junior_cat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>U21</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Entry Date:</td>
      <td><?php echo $row_players1['tourn_date_ent']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="ID" value="<?php echo $row_players1['ID']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>

