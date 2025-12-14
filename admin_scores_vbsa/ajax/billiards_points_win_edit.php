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

$editFormAction = "ajax/billiards_points_win_edit.php";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Team_entries SET SF1_pts=%s, SF2_pts=%s, GF_pts=%s WHERE team_id=%s",
                       GetSQLValueString(isset($_POST['SF1_pts']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['SF2_pts']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['GF_pts']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['team_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
}

$colname_final_points_win = "-1";
if (isset($_GET['team_id'])) {
  $colname_final_points_win = $_GET['team_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_final_points_win = sprintf("SELECT team_id, team_name, team_grade, SF1_pts, SF2_pts, GF_pts FROM Team_entries WHERE team_id = %s", GetSQLValueString($colname_final_points_win, "int"));
$final_points_win = mysql_query($query_final_points_win, $connvbsa) or die(mysql_error());
$row_final_points_win = mysql_fetch_assoc($final_points_win);
$totalRows_final_points_win = mysql_num_rows($final_points_win);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<p>&nbsp;</p>

  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team ID:</td>
      <td><?php echo $row_final_points_win['team_id']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team Name:</td>
      <td><?php echo $row_final_points_win['team_name']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Grade:</td>
      <td><?php echo $row_final_points_win['team_grade']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap">APPLICABLE TO BILLIARDS FINALS ONLY</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap">If Checked this team will show an * to denote a points win. If it is in a semi final the team will automatically go thru to the Grand Final</td>
    </tr>
  </table>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return doit()" >
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">SF1_pts:</td>
      <td><input type="checkbox" name="SF1_pts" value="SF1_pts"  <?php if (!(strcmp(htmlentities($row_final_points_win['SF1_pts'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">SF2_pts:</td>
      <td><input type="checkbox" name="SF2_pts" value="SF2_pts"  <?php if (!(strcmp(htmlentities($row_final_points_win['SF2_pts'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">GF_pts:</td>
      <td><input type="checkbox" name="GF_pts" value="GF_pts"  <?php if (!(strcmp(htmlentities($row_final_points_win['GF_pts'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="team_id" value="<?php echo htmlentities($row_final_points_win['team_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="team_id" value="<?php echo $row_final_points_win['team_id']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($final_points_win);

mysql_free_result($final_points_win);
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
