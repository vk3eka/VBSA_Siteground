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

$editFormAction = "ajax/Player_captain_edit_S2.php";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE scrs SET MemberID=%s, team_grade=%s, team_id=%s, captain_scrs=%s, final_sub=%s WHERE scrsID=%s",
                       GetSQLValueString($_POST['MemberID'], "int"),
                       GetSQLValueString($_POST['team_grade'], "text"),
                       GetSQLValueString($_POST['team_id'], "int"),
                       GetSQLValueString($_POST['captain_scrs'], "text"),
                       GetSQLValueString($_POST['final_sub'], "text"),
                       GetSQLValueString($_POST['scrsID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
    echo "Successfully updated!";
  exit;
}

$colname_Scrs_Edit = "-1";
if (isset($_GET['scrs'])) {
  $colname_Scrs_Edit = $_GET['scrs'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Scrs_Edit = sprintf("SELECT scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, members.MemberID, members.FirstName, members.LastName, scrs.final_sub, scrs.captain_scrs FROM scrs, members WHERE scrs.MemberID=members.MemberID AND scrs.scrsID = %s", GetSQLValueString($colname_Scrs_Edit, "int"));
$Scrs_Edit = mysql_query($query_Scrs_Edit, $connvbsa) or die(mysql_error());
$row_Scrs_Edit = mysql_fetch_assoc($Scrs_Edit);
$totalRows_Scrs_Edit = mysql_num_rows($Scrs_Edit);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grades = "SELECT grade, grade_name FROM Team_grade WHERE `current`='Yes'  ORDER BY season, type, grade";
$grades = mysql_query($query_grades, $connvbsa) or die(mysql_error());
$row_grades = mysql_fetch_assoc($grades);
$totalRows_grades = mysql_num_rows($grades);
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
</div>
<center>
<table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
</table>
<center>
  
  <table width="800" border="0">
    <tr>
      <td align="center" class="red_bold">Edit a Captain</td>
    </tr>
    <tr>
      <td align="center" class="page"><a href="../Team_entries_S2.php">Return to team entries</a></td>
    </tr>
  </table>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return doit()">
  
    <table border="1" cellpadding="5" cellspacing="5">
      <tr>
        <td>Member ID
          <input type="text" name="MemberID" value="<?php echo htmlentities($row_Scrs_Edit['MemberID'], ENT_COMPAT, 'utf-8'); ?>" size="8" />
        </td>
        <td align="left"><?php echo $row_Scrs_Edit['FirstName']; ?> <?php echo $row_Scrs_Edit['LastName']; ?></td>
        <td align="center">Capt
          <select name="captain_scrs">
            <option value="No" <?php if (!(strcmp("No", htmlentities($row_Scrs_Edit['captain_scrs'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Scrs_Edit['captain_scrs'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option></select></td>
        <td align="center">Grade
          <select name="team_grade">
          <?php do {  ?>
          <option value="<?php echo $row_grades['grade']?>"<?php if (!(strcmp($row_grades['grade'], $row_Scrs_edit['team_grade']))) {echo "selected=\"selected\"";} ?>><?php echo $row_grades['grade']?></option>
          <?php
} while ($row_grades = mysql_fetch_assoc($grades));
  $rows = mysql_num_rows($grades);
  if($rows > 0) {
      mysql_data_seek($grades, 0);
	  $row_grades = mysql_fetch_assoc($grades);
  }
?>
        </select></td>
        <td align="center">Team ID
        <input type="text" name="team_id" value="<?php echo htmlentities($row_Scrs_Edit['team_id'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td align="center">Final Sub 
          <select name="final_sub">
            <option value="No" <?php if (!(strcmp("No", htmlentities($row_Scrs_Edit['final_sub'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
            <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Scrs_Edit['final_sub'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
        </select></td>
        <td align="center"><input type="submit" value="Update player" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="scrsID" value="<?php echo $row_Scrs_Edit['scrsID']; ?>" />
  </form>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</center>
</body>
</html>
<?php
mysql_free_result($Scrs_Edit);

mysql_free_result($grades);

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