<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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
  $updateSQL = sprintf("UPDATE members SET BoardMemb=%s, board_position=%s WHERE MemberID=%s",
                       GetSQLValueString($_POST['BoardMemb'], "int"),
                       GetSQLValueString($_POST['board_position'], "text"),
                       GetSQLValueString($_POST['MemberID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "affiliate_member_delete_confirm.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['bm_del'])) {
  $bm_del = $_GET['bm_del'];
}

mysql_select_db($database_connvbsa, $connvbsa);

$query_Board = "Select id, vbsa_id, CONCAT(FirstName, ' ', LastName) as name, email_address, Email, MobilePhone, hashed_password, usertype, block, sendEmail, gid, registerDate, lastvisitDate, activation FROM vbsaorga_users2 LEFT JOIN members ON members.MemberID = vbsaorga_users2.vbsa_id WHERE vbsa_id='$bm'";


//$query_Board = "SELECT id, board_member_id, name, vbsaorga_users2.email, vbsaorga_users2.email_vbsa, password, usertype, display, register_year, order_display, `comment`, MemberID, BoardMemb, board_position FROM vbsaorga_users, members WHERE board_member_id=MemberID AND id='$bm_del' ORDER BY order_display ASC";
$Board = mysql_query($query_Board, $connvbsa) or die(mysql_error());
$row_Board = mysql_fetch_assoc($Board);
$totalRows_Board = mysql_num_rows($Board);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Users = "Select id, vbsa_id FROM vbsaorga_users2, members WHERE MemberID=vbsa_id";
$Users = mysql_query($query_Users, $connvbsa) or die(mysql_error());
$row_Users = mysql_fetch_assoc($Users);
$totalRows_Users = mysql_num_rows($Users);
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

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include '../../admin_xx_includes/db_nav.php';?>
<?php include '../../admin_xx_includes/db_srch.php';?>

	<table align="center">
	  <tr>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
      </tr>
	  <tr>
	    <td class="red_bold">You are about to delete an Affiliate Member &quot;<?php echo $row_Board['name']; ?>&quot; from the &quot;vbsa_users&quot; table</td>
	    <td><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
      </tr>
	  <tr>
	    <td colspan="2">&nbsp;</td>
      </tr>
</table>

		<?php  // if board member is set to "1" - checkbox is checked - show the form to edit the "members" table
		  if($row_Board['BoardMemb']==1)
		  { ?>
          
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td colspan="2" align="right" nowrap="nowrap" class="red_text">To delete an Affiliate Member you must remove the following from the &quot;Member&quot; table</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Board Member:</td>
          <td><?php echo $row_Board['BoardMemb']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Board Position</td>
          <td><?php echo $row_Board['board_position']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td>
          

		  </td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Remove from members table" /></td>
        </tr>
      </table>
      <input type="hidden" name="MemberID" value="<?php echo $row_Board['MemberID']; ?>" />
      <input type="hidden" name="BoardMemb" value="0" />
      <input type="hidden" name="board_position" value="" />
      <input type="hidden" name="MM_update" value="form1" />
</form>

		<?php } // end display members table edit
		
		// When boardmember and board position are removed from the members table then display the delete button
		  elseif($row_Board['BoardMemb']==0) 
		  { ?>
		
		  
    <table align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="red_bold">IF YOU PROCEED  <?php echo $row_Board['name']; ?> WILL BE PERMANENTLY DELETED FROM THE DATABASE</td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="red_bold">YOU CANNOT UNDO THIS ACTION</td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Do you wish to proceed?</td>
        <td class="greenbg"><a href="affiliate_member_delete.php?bm_del=<?php echo($bm_del); ?>">Yes</a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
	
	<?php 
		  } ?>
</body>
</html>
<?php
mysql_free_result($Board);

mysql_free_result($Users);
?>