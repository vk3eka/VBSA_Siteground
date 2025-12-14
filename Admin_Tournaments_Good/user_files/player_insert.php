<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
error_reporting(0);

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  /*
  $insertSQL = sprintf("INSERT INTO tourn_entry (ID, tourn_type, tourn_memb_id, tournament_number, amount_entry, entered_by, how_paid, seed, wcard, junior_cat, entry_cal_year, entry_fin_year) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
					             GetSQLValueString($_POST['tourn_type'], "text"),
                       GetSQLValueString($_POST['tourn_memb_id'], "int"),
                       GetSQLValueString($_POST['tournament_number'], "int"),
                       GetSQLValueString($_POST['amount_entry'], "int"),
                       GetSQLValueString($_POST['entered_by'], "text"),
                       GetSQLValueString($_POST['how_paid'], "text"),
                       GetSQLValueString($_POST['seed'], "int"),
                       GetSQLValueString($_POST['wcard'], "int"),
                       GetSQLValueString($_POST['junior_cat'], "text"),
                       GetSQLValueString($_POST['entry_cal_year'], "date"),
                       GetSQLValueString($_POST['entry_fin_year'], "date"));
  */
  $insertSQL = sprintf("INSERT INTO tourn_entry (ID, tourn_type, tourn_memb_id, tournament_number, amount_entry, entered_by, how_paid, wcard, junior_cat, entry_cal_year, entry_fin_year) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
                       GetSQLValueString($_POST['tourn_type'], "text"),
                       GetSQLValueString($_POST['tourn_memb_id'], "int"),
                       GetSQLValueString($_POST['tournament_number'], "int"),
                       GetSQLValueString($_POST['amount_entry'], "int"),
                       GetSQLValueString($_POST['entered_by'], "text"),
                       GetSQLValueString($_POST['how_paid'], "text"),
                       GetSQLValueString($_POST['wcard'], "int"),
                       GetSQLValueString($_POST['junior_cat'], "text"),
                       GetSQLValueString($_POST['entry_cal_year'], "date"),
                       GetSQLValueString($_POST['entry_fin_year'], "date"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
  //echo($insertSQL . "<br>");
  $insertGoTo = "../tournament_detail.php"; 
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  //echo($_SERVER['QUERY_STRING'] . "<br>");
  header(sprintf("Location: %s", $insertGoTo));
}

if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
}

if (isset($_GET['tourn_type'])) {
  $tourn_type = $_GET['tourn_type'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn1 = "SELECT tourn_id, tourn_name, date_format( tourn_year, '%Y') AS Tyear, site_visible, tourn_type, tourn_draw, tourn_class FROM tournaments WHERE tourn_id = '$tourn_id'";
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);

mysql_select_db($database_connvbsa, $connvbsa);
/*
$query_tourn_entries = "SELECT DATE_FORMAT(tourn_date_ent,'%m') AS mnth, tourn_entry.ID, tourn_entry.tourn_memb_id, tourn_entry.tournament_number, tourn_entry.amount_entry, tourn_entry.entered_by, tourn_entry.how_paid, tourn_entry.seed, tourn_entry.wcard, tourn_entry.ranked, tourn_entry.junior_cat, tourn_entry.entry_cal_year, tourn_entry.entry_fin_year, tourn_entry.tourn_date_ent FROM tourn_entry WHERE tournament_number =  '$tourn_id'";
*/

// removed seed 10/04/2024
$query_tourn_entries = "SELECT DATE_FORMAT(tourn_date_ent,'%m') AS mnth, tourn_entry.ID, tourn_entry.tourn_memb_id, tourn_entry.tournament_number, tourn_entry.amount_entry, tourn_entry.entered_by, tourn_entry.how_paid, tourn_entry.wcard, tourn_entry.ranked, tourn_entry.junior_cat, tourn_entry.entry_cal_year, tourn_entry.entry_fin_year, tourn_entry.tourn_date_ent FROM tourn_entry WHERE tournament_number =  '$tourn_id'";
$tourn_entries = mysql_query($query_tourn_entries, $connvbsa) or die(mysql_error());
$row_tourn_entries = mysql_fetch_assoc($tourn_entries);
$totalRows_tourn_entries = mysql_num_rows($tourn_entries);

$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);
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
<script type="text/javascript" src="../../ScriptLibrary/jquery-latest.pack.js"></script>

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
<table width="800" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="left"><span class="red_bold">Insert a player into:</span> <?php echo $row_tourn1['Tyear']; ?> <?php echo $row_tourn1['tourn_name']; ?></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
	<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
      <table align="center" cellpadding="5" cellspacing="5">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Member ID:</td>
          <td><input type="text" name="tourn_memb_id" value="" size="10" /></td>
          <td rowspan="3">
          <!--------- Nested table starts-------------------------->
        <table>
          <tr>
            <td><input name="quicksearch" type="text" id="quick_search" size="20" /></td><td>Quick Surname Search</td>
            </tr>
          <tr>
            <td><div id="qs_result" align="left" style="z-index: 1; position: absolute; "></div></td><td>&nbsp;</td>
            </tr>
        </table>
        <!--------- Nested table ends-------------------------->
          
          </td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">$ Amount:</td>
          <td><input type="text" name="amount_entry" value="" size="10" /> 
            (if a Junior is entering several age groups list 1 payment only, then enter as 0)</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Entered by:</td>
          <td><?php echo $row_getusername['name']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">How paid:</td>
          <td><select name="how_paid">
            <option selected="selected" value="PP" <?php if (!(strcmp("PP", ""))) {echo "SELECTED";} ?>>PP</option>
            <option value="BT" <?php if (!(strcmp("BT", ""))) {echo "SELECTED";} ?>>BT</option>
            <option value="Chq" <?php if (!(strcmp("Chq", ""))) {echo "SELECTED";} ?>>Chq</option>
            <option value="Cash" <?php if (!(strcmp("Cash", ""))) {echo "SELECTED";} ?>>Cash</option>
            <option value="Other" <?php if (!(strcmp("Other", ""))) {echo "SELECTED";} ?>>Other</option>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap="nowrap">Entry Confirmed:</td>
          <td>Auto entered as &quot;Yes&quot;.<br/> 
          Edit and clear checkbox. Entry will then appear in the &quot;Unconfirmed&quot; list.</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Entered on: </td>
          <td><?php echo date("d M"); ?></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Seed:</td>
          <td><input type="text" name="seed" value="" size="10" /> (disabled)</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament Type:</td>
          <td><input type="text" name="seed" value="<?php echo $row_tourn1['tourn_type']; ?>" size="10" readonly/> (auto inserted)</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Wildcard: </td>
          <td><input type="text" name="wcard" value="" size="10" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Junior Category:</td>
          <td><select name="junior_cat">
            <option selected="selected" value="na" <?php if (!(strcmp("na", ""))) {echo "SELECTED";} ?>>Not Required</option>
            <option value="U12" <?php if (!(strcmp("U12", ""))) {echo "SELECTED";} ?>>U12</option>
            <option value="U15" <?php if (!(strcmp("U15", ""))) {echo "SELECTED";} ?>>U15</option>
            <option value="U18" <?php if (!(strcmp("U18", ""))) {echo "SELECTED";} ?>>U18</option>
            <option value="U21" <?php if (!(strcmp("U21", ""))) {echo "SELECTED";} ?>>U21</option>
          </select>            &nbsp;&nbsp;(Use for Junior tournaments only) </td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insert record" /></td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <input type="hidden" name="ID" value="" />
      <input type="hidden" name="tourn_type" value="<?php echo $row_tourn1['tourn_type']; ?>" />
      <input type="hidden" name="entered_by" value="<?php echo $row_getusername['name']; ?>" />
      <input type="hidden" name="entry_fin_year" value="<?php if(date('m')<=6) {echo date("Y")-1; } elseif(date('m')>=7) {echo date("Y"); }?> " />
      <input type="hidden" name="entry_cal_year" value="<?php echo date("Y"); ?>" />
      <input type="hidden" name="tournament_number" value="<?php echo $row_tourn1['tourn_id']; ?>" />
      <input type="hidden" name="MM_insert" value="form1" />
</form>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
<script type="text/javascript">
    $(function(){
        $('#quick_search').keyup(function(e){
            console.log("Got call");
            var input = $(this).val();
            input = input.trim();
            if(input.length == 0) {
                return;
            }
            $.ajax({
                type: "get",
                url: "../ajax/quick_member_search.php",
                data: {last_name: input},
                async: true,
                success: function(data){
                    var member = $.parseJSON(data);
                    $('#qs_result').html('');
                    for(x = 0; x < member.length; x++){
                        $('#qs_result').prepend('<div>' + member[x].MemberID + ' ' + member[x].FirstName + ' ' + member[x].LastName +  '</div>'); //Fills the #auto div with the options
                    }
                }
            })
        })
    });
</script>


