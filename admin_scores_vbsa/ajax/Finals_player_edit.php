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
<?php require_once('../../Connections/connvbsa.php'); ?><?php
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

$editFormAction = "ajax/Finals_player_edit.php";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE scrs SET EF1=%s, EF2=%s, SF1=%s, SF2=%s, PF=%s, GF=%s, EF1_pos=%s, EF2_pos=%s, SF1_pos=%s, SF2_pos=%s, PF_pos=%s, GF_pos=%s WHERE scrsID=%s",
                       GetSQLValueString($_POST['EF1'], "int"),
                       GetSQLValueString($_POST['EF2'], "int"),
                       GetSQLValueString($_POST['SF1'], "int"),
                       GetSQLValueString($_POST['SF2'], "int"),
                       GetSQLValueString($_POST['PF'], "int"),
                       GetSQLValueString($_POST['GF'], "int"),
					   GetSQLValueString($_POST['EF1_pos'], "int"),
                       GetSQLValueString($_POST['EF2_pos'], "int"),
                       GetSQLValueString($_POST['SF1_pos'], "int"),
                       GetSQLValueString($_POST['SF2_pos'], "int"),
                       GetSQLValueString($_POST['PF_pos'], "int"),
                       GetSQLValueString($_POST['GF_pos'], "int"),
                       GetSQLValueString($_POST['scrsID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
}

$colname_Scrs_EditS2 = "-1";
if (isset($_GET['finscrs'])) {
  $colname_Scrs_EditS2 = $_GET['finscrs'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Scrs_EditS2 = sprintf("SELECT scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, scrs.EF1, scrs.EF2, scrs.SF1, scrs.SF2, scrs.PF, scrs.GF, members.MemberID, members.FirstName, members.LastName, scrs.EF1_pos, scrs.EF2_pos, scrs.SF1_pos, scrs.SF2_pos, scrs.PF_pos, scrs.GF_pos, scrs.final_sub FROM scrs, members WHERE scrs.MemberID=members.MemberID AND scrs.scrsID = %s", GetSQLValueString($colname_Scrs_EditS2, "int"));
$Scrs_EditS2 = mysql_query($query_Scrs_EditS2, $connvbsa) or die(mysql_error());
$row_Scrs_EditS2 = mysql_fetch_assoc($Scrs_EditS2);
$totalRows_Scrs_EditS2 = mysql_num_rows($Scrs_EditS2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
</head>

<body>


  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return doit()">
    <table align="center">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="red_bold">Edit Final Scores Only</td>
      </tr>
    </table>
    <table width="540" border="1" align="center">
    <tr>
      <td nowrap="nowrap">Member ID : <?php echo $row_Scrs_EditS2['MemberID']; ?></td>
        <td colspan="2" align="left" nowrap="nowrap"><?php echo $row_Scrs_EditS2['FirstName']; ?> <?php echo $row_Scrs_EditS2['LastName']; ?></td>
        <td align="left">Grade: <?php echo $row_Scrs_EditS2['team_grade']; ?></td>
      </tr>
    <tr>
      <td width="92">scrs ID : <?php echo $row_Scrs_EditS2['scrsID']; ?></td>
        <td width="140" align="center">SF1</td>
        <td width="140" align="center">SF2</td>
        <td width="140" align="center">GF</td>
      </tr>
    <tr>
      <td align="right">Score</td>
        <td width="140" align="center"><input type="text" name="SF1" value="<?php echo htmlentities($row_Scrs_EditS2['SF1'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="140" align="center"><input type="text" name="SF2" value="<?php echo htmlentities($row_Scrs_EditS2['SF2'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="140" align="center"><input type="text" name="GF" value="<?php echo htmlentities($row_Scrs_EditS2['GF'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
      </tr>
    <tr>
      <td align="right">Position</td>
        <td width="140" align="center"><input type="text" name="SF1_pos" value="<?php echo htmlentities($row_Scrs_EditS2['SF1_pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="140" align="center"><input type="text" name="SF2_pos" value="<?php echo htmlentities($row_Scrs_EditS2['SF2_pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="140" align="center"><input type="text" name="GF_pos" value="<?php echo htmlentities($row_Scrs_EditS2['GF_pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
      </tr>
    <tr>
      <td align="center">&nbsp;</td>
      <td width="140" align="right"><input type="submit" value="Update Finals Player" /></td>
        <td width="140" align="right">Finals Sub</td>
        <td width="140" align="center"><?php echo $row_Scrs_EditS2['final_sub']; ?></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="scrsID" value="<?php echo $row_Scrs_EditS2['scrsID']; ?>" />
</form>

</body>
</html>
<?php
mysql_free_result($Scrs_EditS2);

?>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>

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