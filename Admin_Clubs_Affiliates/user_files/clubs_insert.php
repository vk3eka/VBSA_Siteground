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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO clubs (ClubNumber, ClubTitle, ClubNameVBSA, Club_Aff_Assoc, ClubStreet, ClubSuburb, ClubPcode, ClubPhone1, ClubEmail, ClubTables, PennantTables,ClubLink, VBSAteam, BBSAteam, BendBSA, ChurchBill, CityClubTeam, DVSAteam, MSBAteam, Over55team, RSLteam, SouthernTeam, WSBSA, affiliate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ClubNumber'], "int"),
                       GetSQLValueString($_POST['ClubTitle'], "text"),
                       GetSQLValueString($_POST['ClubNameVBSA'], "text"),
                       GetSQLValueString($_POST['Club_Aff_Assoc'], "text"),
                       GetSQLValueString($_POST['ClubStreet'], "text"),
                       GetSQLValueString($_POST['ClubSuburb'], "text"),
                       GetSQLValueString($_POST['ClubPcode'], "text"),
                       GetSQLValueString($_POST['ClubPhone1'], "text"),
                       GetSQLValueString($_POST['ClubEmail'], "text"),
                       GetSQLValueString($_POST['ClubTables'], "int"),
                       GetSQLValueString($_POST['PennantTables'], "int"),
                       GetSQLValueString($_POST['ClubLink'], "text"),
                       GetSQLValueString(isset($_POST['VBSAteam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['BBSAteam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['BendBSA']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['ChurchBillteam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['ChurchBill']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['CityClubTeam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['DVSAteam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['MSBAteam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['Over55team']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['RSLteam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['SouthernTeam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['WSBSA']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['affiliate']) ? "true" : "", "defined","'1'","'0'"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = $_SESSION['detail'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table align="center">
  <tr>
    <td colspan="2"><?php echo $_SESSION['page']; ?></td>
  </tr>
  <tr>
        <td class="red_bold">Insert a new Club or Association (You may insert/update contacts for this club later) </td>
        <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
      <tr>
        <td colspan="2" align="center">Please upload an image after you have created the club, the club will be inserted with the default image &quot;NA.jpg&quot;</td>
      </tr>
      <tr>
        <td colspan="2" align="center">&nbsp;</td>
      </tr>
</table>


  <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2" >
    <table align="center">
      <tr>
        <td align="right" nowrap="nowrap">Club ID:</td>
        <td colspan="4" >Auto Generated</td>
        <td colspan="12" align="left" >Active? Auto inserted as &quot;Yes&quot; ( Club will appear on the website ), edit to alter </td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">ClubTitle:</td>
        <td colspan="4" ><input type="text" name="ClubTitle" value="" size="32" /></td>
        <td colspan="12" align="left" class="Italic">Trading Name of Club</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Club Name VBSA:</td>
        <td colspan="4" ><input type="text" name="ClubNameVBSA" value="" size="32" /></td>
        <td colspan="12" nowrap="nowrap" class="Italic">*IMPORTANT IF PARTICIPATING WITH THE VBSA  cannot be updated or inserted later<br />This is the name that will be stored in the drop down menus in all TEAM INSERTS and updates. Please abbreviate and no spaces</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Club or Association:</td>
        <td colspan="4" ><select name="Club_Aff_Assoc">
          <option value="Club" <?php if (!(strcmp("Club", ""))) {echo "SELECTED";} ?>>Club</option>
          <option value="Association" <?php if (!(strcmp("Association", ""))) {echo "SELECTED";} ?>>Association</option>
        </select>
        Please select</td>
        <td colspan="12" align="left" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Affiliate?</td>
        <td colspan="4" ><input type="checkbox" name="affiliate" id="affiliate" /></td>
        <td colspan="12" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Street:</td>
        <td colspan="4" ><input type="text" name="ClubStreet" value="" size="40" /></td>
        <td colspan="5" align="right" >&nbsp;</td>
        <td colspan="7" align="left" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Suburb:</td>
        <td colspan="4" ><input type="text" name="ClubSuburb" value="" size="40" /></td>
        <td colspan="5" >&nbsp;</td>
        <td colspan="7" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Postcode:</td>
        <td colspan="4" ><input type="text" name="ClubPcode" value="" size="10" /></td>
        <td colspan="5" align="left" >&nbsp;</td>
        <td colspan="7" align="left" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Phone:</td>
        <td colspan="4" ><input type="text" name="ClubPhone1" value="" size="32" /></td>
        <td colspan="5" align="left" >&nbsp;</td>
        <td colspan="7" align="left" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Club Tables:</td>
        <td colspan="4" ><input type="text" name="ClubTables" value="" size="10" /></td>
        <td colspan="12" align="left" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Pennant Tables:</td>
        <td colspan="4" ><input type="text" name="PennantTables" value="" size="10" /></td>
        <td colspan="12" align="left" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Email:</td>
        <td colspan="16" ><input type="text" name="ClubEmail" value="" size="60" /></td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">URL:</td>
        <td colspan="16" ><input type="text" name="ClubLink" value="" size="60" /></td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td colspan="16" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td colspan="16" ><span class="red_text">Please check which competitions this club competes in (if applicable)</span></td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td align="right" >VBSA:</td>
        <td align="left" ><input type="checkbox" name="VBSAteam" id="VBSAteam" /></td>
        <td align="right" >Church:</td>
        <td align="left" ><input type="checkbox" name="ChurchBill" id="ChurchBill" /></td>
        
        <td align="right" >BBSA:</td>
        <td align="left" ><input type="checkbox" name="BBSAteam" id="BBSAteam" /></td>
        <td align="right" >BendBSA:</td>
        <td align="left" ><input type="checkbox" name="BendBSA" id="BendBSAteam" /></td>
        <td align="right" >City Clubs</td>
        <td align="left" ><input type="checkbox" name="CityClubTeam" id="CityClubTeam" /></td>
        <td align="right" >DVSA:</td>
        <td align="left" ><input type="checkbox" name="DVSAteam" id="DVSAteam" /></td>
        <td align="right" >MSBA:</td>
        <td colspan="3" align="left" ><input type="checkbox" name="MSBAteam" id="MSBAteam" /></td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td align="right" >Over 55's</td>
        <td align="left" ><input type="checkbox" name="Over55team" id="Over55team" /></td>
        <td align="right" >RSL:</td>
        <td align="left" ><input type="checkbox" name="RSLteam" id="RSLteam" /></td>
        <td align="right" >Southern:</td>
        <td align="left" ><input type="checkbox" name="SouthernTeam" id="SouthernTeam" /></td>
        <td align="right" >WSBSA:</td>
        <td align="left" ><input type="checkbox" name="WSBSA" id="WSBSA" /></td>
        <td align="left" >&nbsp;</td>
        <td align="left" >&nbsp;</td>
        <td align="left" >&nbsp;</td>
        <td align="left" >&nbsp;</td>
        <td align="left" >&nbsp;</td>
        <td colspan="3" align="left" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td colspan="4" align="right" >&nbsp;</td>
        <td colspan="12" align="left" >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td colspan="4" align="right" >&nbsp;</td>
        <td colspan="12" align="left" ><input type="submit" value="Insert Club" /></td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td colspan="4" align="right" >&nbsp;</td>
        <td colspan="12" align="left" >&nbsp;</td>
      </tr>
      </table>
    <input type="hidden" name="MM_insert" value="form1" />
    <input type="hidden" name="ClubNumber" value="" />
    <input type="hidden" name="MM_insert" value="form2" />
</form>


</body>
</html>
