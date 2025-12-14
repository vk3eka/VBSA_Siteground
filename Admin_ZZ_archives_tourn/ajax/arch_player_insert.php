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

$editFormAction = 'ajax/arch_player_insert.php';
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tourn_archives_players (player_ID, tourn_id, tourn_year, winner, runner_up, high_brk_name, high_brk_amount, venue, why_not_run) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['player_ID'], "int"),
                       GetSQLValueString($_POST['tourn_id'], "int"),
                       GetSQLValueString($_POST['tourn_year'], "date"),
                       GetSQLValueString($_POST['winner'], "text"),
                       GetSQLValueString($_POST['runner_up'], "text"),
                       GetSQLValueString($_POST['high_brk_name'], "text"),
                       GetSQLValueString($_POST['high_brk_amount'], "text"),
                       GetSQLValueString($_POST['venue'], "text"),
                       GetSQLValueString($_POST['why_not_run'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
  echo "Error!";
  exit;
}

$colname_arch_tourn = "-1";
if (isset($_GET['newplayer'])) {
  $colname_arch_tourn = $_GET['newplayer'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_arch_tourn = sprintf("SELECT * FROM tourn_archives WHERE tournament_ID = %s", GetSQLValueString($colname_arch_tourn, "int"));
$arch_tourn = mysql_query($query_arch_tourn, $connvbsa) or die(mysql_error());
$row_arch_tourn = mysql_fetch_assoc($arch_tourn);
$totalRows_arch_tourn = mysql_num_rows($arch_tourn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>

</head>

<body>
	<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1"  onsubmit="return doit()">
      <table align="center" cellpadding="3">
        <tr valign="baseline">
          <td align="right" nowrap="nowrap" class="red_text">Insert item for the selected year into: </td>
          <td class="red_bold"><?php echo $row_arch_tourn['tourn_name']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament year:</td>
          <td><input type="text" name="tourn_year" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Winner:</td>
          <td><input type="text" name="winner" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Runner up:</td>
          <td><input type="text" name="runner_up" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">High Break Name:</td>
          <td><input type="text" name="high_brk_name" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">High Break Total:</td>
          <td><input type="text" name="high_brk_amount" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Venue:</td>
          <td><input type="text" name="venue" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Reason if tournament not run in this year </td>
          <td><input type="text" name="why_not_run" value="" size="60" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insert record" /></td>
        </tr>
      </table>
      <input type="hidden" name="player_ID" value="" />
      <input type="hidden" name="tourn_id" value="<?php echo $row_arch_tourn['tournament_ID']; ?>" />
      <input type="hidden" name="MM_insert" value="form1" />
</form>
    <p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($arch_tourn);
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
                        alert('Inserted Succesfully!');
						location.reload(); 
                      },
            error   : function( xhr, err ) {
                        alert('Error');     
                      }
        }); 
        return false;
}

</script>
