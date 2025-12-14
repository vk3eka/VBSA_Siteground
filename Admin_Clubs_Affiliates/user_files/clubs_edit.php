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
  $updateSQL = sprintf("UPDATE clubs SET ClubLogo=%s, ClubTitle=%s, ClubNameVBSA=%s, Club_Aff_Assoc=%s, ClubStreet=%s, ClubSuburb=%s, ClubPcode=%s, ClubPhone1=%s, ClubPhone2=%s, ClubFax=%s, ClubEmail=%s, ClubContact=%s, ClubContactEmail=%s, ClubMelwaysRef=%s, ClubTables=%s, PennantTables=%s, ClubLink=%s, VBSAteam=%s, BBSAteam=%s, BendBSA=%s, ChurchBill=%s, CityClubTeam=%s, DVSAteam=%s, MSBAteam=%s, Over55team=%s, RSLteam=%s, SouthernTeam=%s, WSBSA=%s, affiliate=%s, aff_URL=%s, LastUpdated=%s, UpdatedBy=%s, inactive=%s, Comments=%s WHERE ClubNumber=%s",
                       GetSQLValueString($_POST['ClubLogo'], "text"),
                       GetSQLValueString($_POST['ClubTitle'], "text"),				   
                       GetSQLValueString($_POST['ClubNameVBSA'], "text"),
                       GetSQLValueString($_POST['Club_Aff_Assoc'], "text"),
                       GetSQLValueString($_POST['ClubStreet'], "text"),
                       GetSQLValueString($_POST['ClubSuburb'], "text"),
                       GetSQLValueString($_POST['ClubPcode'], "text"),
                       GetSQLValueString($_POST['ClubPhone1'], "text"),
                       GetSQLValueString($_POST['ClubPhone2'], "text"),
                       GetSQLValueString($_POST['ClubFax'], "text"),
                       GetSQLValueString($_POST['ClubEmail'], "text"),
                       GetSQLValueString($_POST['ClubContact'], "text"),
                       GetSQLValueString($_POST['ClubContactEmail'], "text"),
                       GetSQLValueString($_POST['ClubMelwaysRef'], "text"),
                       GetSQLValueString($_POST['ClubTables'], "int"),
                       GetSQLValueString($_POST['PennantTables'], "int"),
                       GetSQLValueString($_POST['ClubLink'], "text"),
					   GetSQLValueString(isset($_POST['VBSAteam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['BBSAteam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['BendBSA']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['ChurchBill']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['CityClubTeam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['DVSAteam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['MSBAteam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['Over55team']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['RSLteam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['SouthernTeam']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['WSBSA']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString(isset($_POST['affiliate']) ? "true" : "", "defined","'1'","'0'"),
					   GetSQLValueString($_POST['aff_URL'], "text"),
                       GetSQLValueString($_POST['LastUpdated'], "date"),
                       GetSQLValueString($_POST['UpdateBy'], "text"),
					   GetSQLValueString(isset($_POST['inactive']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['Comments'], "text"),
                       GetSQLValueString($_POST['ClubNumber'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = $_SESSION['detail'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Clubs = "SELECT * FROM clubs WHERE ClubNumber = '$club_id'";
$Clubs = mysql_query($query_Clubs, $connvbsa) or die(mysql_error());
$row_Clubs = mysql_fetch_assoc($Clubs);
$totalRows_Clubs = mysql_num_rows($Clubs);

$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);

mysql_select_db($database_connvbsa, $connvbsa);
$query_vbsa_clubs = "SELECT ClubNumber, ClubTitle, ClubNameVBSA FROM clubs WHERE VBSAteam=1 ORDER BY ClubNameVBSA";
$vbsa_clubs = mysql_query($query_vbsa_clubs, $connvbsa) or die(mysql_error());
$row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs);
$totalRows_vbsa_clubs = mysql_num_rows($vbsa_clubs);
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

<table width="900" align="center">
  <tr>
    <td align="left" class="red_bold">Edit Public view information for Clubs, Affiliates &amp; Associations </td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td align="left">page=<?php echo $_SESSION['page']; ?> detail = <?php echo $_SESSION['detail']; ?> </td>
    <td align="right">&nbsp;</td>
  </tr>
</table>
<table width="1000" border="0" align="center">
</table>

<table width="1000" border="0" align="center">
</table>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
<div class="club_border">
<table align="center" cellpadding="5" cellspacing="5">
          <tr>
            <td colspan="4" align="center" nowrap="nowrap">Edit Club Details (For Club ID<?php echo $row_Clubs['ClubNumber']; ?>)</td>
          </tr>
          <tr>
                <td nowrap="nowrap">Club Name</td>
                <td><input type="text" name="ClubTitle" value="<?php echo htmlentities($row_Clubs['ClubTitle'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                <td>Club Logo</td>
                <td><input type="text" name="ClubLogo" value="<?php echo htmlentities($row_Clubs['ClubLogo'], ENT_COMPAT, 'utf-8'); ?>" size="45" /></td>
      </tr>
                  <tr>
                    <td>Club Name VBSA </td>
                    <td>
                    <!-- If the Abbreviation for this club is not set and the club competes in the VBSA -->
                    <?php if(empty($row_Clubs['ClubNameVBSA']) && $row_Clubs['VBSAteam']==1) { ?>
                    <input type="text" name="ClubNameVBSA" value="<?php echo htmlentities($row_Clubs['ClubNameVBSA'], ENT_COMPAT, 'utf-8'); ?>" size="16" /> <span class="red_text">"please set - No Spaces"</span>
                    <!-- If the Abbreviation for this club is set and the club competes in the VBSA -->
                    <?php } elseif(!empty($row_Clubs['ClubNameVBSA']) && $row_Clubs['VBSAteam']==1)  echo $row_Clubs['ClubNameVBSA']. "  (Cannot be altered)"; 
                    // Else NR
                    else echo " NR" ?>
                    
                    
                    </td>
                    <td>Club Email</td>
                    <td><input type="text" name="ClubEmail" value="<?php echo htmlentities($row_Clubs['ClubEmail'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                  </tr>
                  <tr>
                    <td>Address</td>
                    <td><input type="text" name="ClubStreet" value="<?php echo htmlentities($row_Clubs['ClubStreet'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                    <td>Phone</td>
                    <td><input type="text" name="ClubPhone1" value="<?php echo htmlentities($row_Clubs['ClubPhone1'], ENT_COMPAT, 'utf-8'); ?>" size="20" /></td>
                </tr>
                  <tr>
                    <td>Suburb</td>
                    <td><input type="text" name="ClubSuburb" value="<?php echo htmlentities($row_Clubs['ClubSuburb'], ENT_COMPAT, 'utf-8'); ?>" size="20" /></td>
                    <td>Fax</td>
                    <td><input type="text" name="ClubFax" value="<?php echo htmlentities($row_Clubs['ClubFax'], ENT_COMPAT, 'utf-8'); ?>" size="20" /></td>
                </tr>
                <tr>
                    <td>Pcode </td>
                    <td><input type="text" name="ClubPcode" value="<?php echo htmlentities($row_Clubs['ClubPcode'], ENT_COMPAT, 'utf-8'); ?>" size="6" /></td>
                    <td>Type</td>
                  <td><select name="Club_Aff_Assoc">
                    <option value="Club" <?php if (!(strcmp("Club", htmlentities($row_Clubs['Club_Aff_Assoc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Club</option>
                    <option value="Association" <?php if (!(strcmp("Association", htmlentities($row_Clubs['Club_Aff_Assoc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Association</option>
                  </select></td>
                </tr>
                <tr>
                  <td>Club Tables</td>
                    <td><input type="text" name="ClubTables" value="<?php echo htmlentities($row_Clubs['ClubTables'], ENT_COMPAT, 'utf-8'); ?>" size="20" /></td>
                  <td>Pennant Tables</td>
                    <td><input type="text" name="PennantTables" value="<?php echo htmlentities($row_Clubs['PennantTables'], ENT_COMPAT, 'utf-8'); ?>" size="20" /></td>
                </tr>
                <tr>
                    <td>Club Contact</td>
                    <td><input type="text" name="ClubContact" value="<?php echo htmlentities($row_Clubs['ClubContact'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                    <td>Affiliate?:</td>
                    <td><input type="checkbox" name="affiliate" id="affiliate"  <?php if (!(strcmp(htmlentities($row_Clubs['affiliate'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                </tr>
                <tr>
                  <td>Web Address</td>
                  <td><input type="text" name="ClubLink" value="<?php echo htmlentities($row_Clubs['ClubLink'], ENT_COMPAT, 'utf-8'); ?>" size="45" /></td>
                  <td align="left">Web Address</td>
                  <td align="left"><input type="text" name="CluLink" value="<?php echo htmlentities($row_Clubs['CluLink'], ENT_COMPAT, 'utf-8'); ?>" size="45" /></td>
                </tr>
                <tr>
                    <td>Updated on : </td>
                    <td><?php echo $row_Clubs['LastUpdated']; ?></td>
                    <td>Affiliate URL</td>
                    <td><input type="text" name="aff_URL" value="<?php echo htmlentities($row_Clubs['aff_URL'], ENT_COMPAT, 'utf-8'); ?>" size="45" /></td>
                </tr>
                <tr>
                  <td>Comment</td>
                  <td><input type="text" name="Comments" value="<?php echo htmlentities($row_Clubs['Comments'], ENT_COMPAT, 'utf-8'); ?>" size="45" /></td>
                  <td colspan="2">If Web Adddress is not available use Affiliate URL</td>
                </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                  <td colspan="2" align="left">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left">Active?</td>
                  <td colspan="3" align="left"><input type="checkbox" name="inactive" id="inactive"  <?php if (!(strcmp(htmlentities($row_Clubs['inactive'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /> 
                  &nbsp;&nbsp;&nbsp;&nbsp; If unchecked this Club will move to the &quot;Inactive Club&quot; list and will not appear on the website</td>
                </tr>
                <tr>
                  <td colspan="4" align="center">Last Update By: <?php echo $row_Clubs['UpdatedBy']; ?></td>
                </tr>
              </table>

              <table align="center" cellpadding="5" cellspacing="5">
                <tr>
                  <td align="center" class="red_bold">This Club Fields teams in (Not applicable if Affiliate or Association):</td>
                </tr>
                <tr>
                  <td align="center">
               VBSA: <input type="checkbox" name="VBSAteam" id="VBSAteam"  <?php if (!(strcmp(htmlentities($row_Clubs['VBSAteam'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />
               &nbsp;&nbsp;&nbsp;&nbsp;BBSA: 
               <input type="checkbox" name="BBSAteam" id="BBSAteam"  <?php if (!(strcmp(htmlentities($row_Clubs['BBSAteam'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />&nbsp;&nbsp;&nbsp;&nbsp;BendBSA
               <input type="checkbox" name="BendBSA" id="BendBSA"  <?php if (!(strcmp(htmlentities($row_Clubs['BendBSA'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />&nbsp;&nbsp;&nbsp;&nbsp;
               Church Billiards: <input type="checkbox" name="ChurchBill" id="ChurchBill"  <?php if (!(strcmp(htmlentities($row_Clubs['ChurchBill'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />&nbsp;&nbsp;&nbsp;&nbsp;
               City Clubs: <input type="checkbox" name="CityClubTeam" id="CityClubTeam"  <?php if (!(strcmp(htmlentities($row_Clubs['CityClubTeam'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />&nbsp;&nbsp;&nbsp;&nbsp;
               DVSA: <input type="checkbox" name="DVSAteam" id="DVSAteam"  <?php if (!(strcmp(htmlentities($row_Clubs['DVSAteam'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td align="center"> MSBA:
                    <input type="checkbox" name="MSBAteam" id="MSBAteam"  <?php if (!(strcmp(htmlentities($row_Clubs['MSBAteam'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />
                    &nbsp;&nbsp;&nbsp;&nbsp;
               Over 55's:
               <input type="checkbox" name="Over55team" id="Over55team"  <?php if (!(strcmp(htmlentities($row_Clubs['Over55team'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />
               &nbsp;&nbsp;&nbsp;&nbsp;
               RSL:
               <input type="checkbox" name="RSLteam" id="RSLteam"  <?php if (!(strcmp(htmlentities($row_Clubs['RSLteam'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />
               &nbsp;&nbsp;&nbsp;&nbsp;
               Southern:
               <input type="checkbox" name="SouthernTeam" id="SouthernTeam"  <?php if (!(strcmp(htmlentities($row_Clubs['SouthernTeam'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />
               &nbsp;&nbsp;&nbsp;&nbsp;
               WSBSA:
               <input type="checkbox" name="WSBSA" id="WSBSA"  <?php if (!(strcmp(htmlentities($row_Clubs['WSBSA'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                </tr>
                <tr>
                  <td align="center"><input type="submit" value="Update" /></td>
                </tr>
    </table>
  </div>
  <input type="hidden" name="ClubMelwaysRef"  value="<?php echo $row_Clubs['ClubMelwaysRef']; ?>"><!-- not required-->
  <input type="hidden" name="LastUpdated" value="<?php echo date("Y-m-d"); ?> " />
  <input type="hidden" name="ClubNumber" value="<?php echo $row_Clubs['ClubNumber']; ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="UpdateBy" value="<?php echo $row_getusername['name']; ?>" />
</form>
</body>
</html>
<?php
mysql_free_result($Clubs);

mysql_free_result($getusername);

mysql_free_result($vbsa_clubs);
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script type="text/javascript">

function doit(){     
	
	var tx = jQuery.noConflict();
        tx.ajax({
            url     : '<?PHP echo $editFormAction ?>',
            type    : tx('#form1').attr('method'),
            data    : tx('#form1').serialize(),
            success : function( data ) {
                        alert('Updated Succesfully!');
						location.reload();
                      },
            error   : function( xhr, err ) {
                        alert('Error');     
                      }
        }); 
        return false;
}

</script>
