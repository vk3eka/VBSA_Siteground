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

$editFormAction = "ajax/Adjust_ladder_edit.php";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Team_entries SET day_played=%s, players=%s, scr_adjust=%s, scr_adj_rd=%s, Final5=%s, Result_pos=%s, Result_score=%s, HB=%s, S1=%s, S2=%s, adj_comment=%s WHERE team_id=%s",
                       GetSQLValueString($_POST['day_played'], "text"),
                       GetSQLValueString($_POST['players'], "text"),
                       GetSQLValueString($_POST['scr_adjust'], "int"),
                       GetSQLValueString($_POST['scr_adj_rd'], "int"),
                       GetSQLValueString($_POST['Final5'], "int"),
                       GetSQLValueString($_POST['Result_pos'], "int"),
                       GetSQLValueString($_POST['Result_score'], "int"),
                       GetSQLValueString($_POST['HB'], "text"),
                       GetSQLValueString($_POST['S1'], "int"),
                       GetSQLValueString($_POST['S2'], "int"),
                       GetSQLValueString($_POST['adj_comment'], "text"),
                       GetSQLValueString($_POST['team_id'], "int"));
  
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
  echo "Successfully updated!";
  exit;
}

$colname_Adjust_notes = "-1";
if (isset($_GET['adj'])) {
  $colname_Adjust_notes = (get_magic_quotes_gpc()) ? $_GET['adj'] : addslashes($_GET['adj']);
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Adjust_notes = sprintf("SELECT * FROM Team_entries WHERE team_id = %s", GetSQLValueString($colname_Adjust_notes, "int"));
$Adjust_notes = mysql_query($query_Adjust_notes, $connvbsa) or die(mysql_error());
$row_Adjust_notes = mysql_fetch_assoc($Adjust_notes);
$totalRows_Adjust_notes = mysql_num_rows($Adjust_notes);
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
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1"  onsubmit="return doit()">
    <table>
    <tr>
      <td colspan="11" align="center" class="red_bold">Edit a team or adjust the ladder for:</td>
      </tr>
    <tr class="red_bold">
      <td>Team ID:</td>
      <td><?php echo $row_Adjust_notes['team_id']; ?></td>
      <td>&nbsp;</td>
      <td>Club:</td>
      <td><?php echo $row_Adjust_notes['team_club']; ?></td>
      <td>&nbsp;</td>
      <td>Team Name: </td>
      <td><?php echo $row_Adjust_notes['team_name']; ?></td>
      <td>&nbsp;</td>
      <td>Grade: </td>
      <td><?php echo $row_Adjust_notes['team_grade']; ?></td>
    </tr>
    <tr class="red_bold">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Day played:</td>
      <td>
        <select name="day_played">
          <option value="Mon" <?php if (!(strcmp("Mon", htmlentities($row_Adjust_notes['day_played'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>Mon</option>
          <option value="Wed" <?php if (!(strcmp("Wed", htmlentities($row_Adjust_notes['day_played'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>Wed</option>
        </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Players:</td>
        <td>
          <select name="players">
            <option value="4" <?php if (!(strcmp(4, htmlentities($row_Adjust_notes['players'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>4</option>
            <option value="6" <?php if (!(strcmp(6, htmlentities($row_Adjust_notes['players'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>6</option>
        </select></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Adjust points: </td>
      <td><input type="text" name="scr_adjust" value="<?php echo htmlentities($row_Adjust_notes['scr_adjust'], ENT_COMPAT, 'utf-8'); ?>" size="32" /> 
        Please ensure the ladder balances after adjusting</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Round to be adjusted: </td>
        <td><input type="text" name="scr_adj_rd" value="<?php echo htmlentities($row_Adjust_notes['scr_adj_rd'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        To make a comment appear in finals only set this number to 20</td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Final 4 or final 5:</td>
        <td>
          <select name="Final5">
            <option value="4" <?php if (!(strcmp(4, htmlentities($row_Adjust_notes['Final5'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>4</option>
            <option value="5" <?php if (!(strcmp(6, htmlentities($row_Adjust_notes['Final5'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>5</option>
        </select></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Result position:</td>
      <td><input type="text" name="Result_pos" value="<?php echo htmlentities($row_Adjust_notes['Result_pos'], ENT_COMPAT, 'utf-8'); ?>" size="32" /> 
        Decides where team will display in match report</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Result score:</td>
        <td><input type="text" name="Result_score" value="<?php echo htmlentities($row_Adjust_notes['Result_score'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
          Reports score in match report</td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">HB:</td>
      <td><input type="text" name="HB" value="<?php echo htmlentities($row_Adjust_notes['HB'], ENT_COMPAT, 'utf-8'); ?>" size="32" /> 
        Reports High Break in match report</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">S1:</td>
        <td>
          <select name="S1">
            <option value="" <?php if (!(strcmp("", htmlentities($row_Adjust_notes['S1'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>No</option>
            <option value="1" <?php if (!(strcmp(1, htmlentities($row_Adjust_notes['S1'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>Yes</option>
        </select></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">S2:</td>
        <td><select name="S2">
          <option value="" <?php if (!(strcmp("", htmlentities($row_Adjust_notes['S2'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>No</option>
          <option value="1" <?php if (!(strcmp(1, htmlentities($row_Adjust_notes['S2'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>Yes</option>
        </select></td>
      </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Comment:</td>
      <td><textarea name="adj_comment" cols="80" rows="4"><?php echo htmlentities($row_Adjust_notes['adj_comment'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team entered on:</td>
        <td><?php $newDate = date("l jS F Y g:ia", strtotime($row_Adjust_notes['current_year_team'])); echo $newDate; ?></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Update record" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="team_id" value="<?php echo $row_Adjust_notes['team_id']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</center>
</body>
</html>
<?php
mysql_free_result($Adjust_notes);

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