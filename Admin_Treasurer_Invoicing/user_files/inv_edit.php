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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE inv_to SET inv_busname=%s, inv_to=%s, inv_street=%s, inv_suburb=%s, inv_city=%s, inv_postcode=%s, inv_email=%s, inv_phone=%s, inv_date=%s, inv_paid_amount=%s, inv_paid_date=%s, inv_comment=%s, inv_bad_debt=%s WHERE inv_id=%s",
                       GetSQLValueString($_POST['inv_busname'], "text"),
                       GetSQLValueString($_POST['inv_to'], "text"),
                       GetSQLValueString($_POST['inv_street'], "text"),
                       GetSQLValueString($_POST['inv_suburb'], "text"),
                       GetSQLValueString($_POST['inv_city'], "text"),
                       GetSQLValueString($_POST['inv_postcode'], "int"),
                       GetSQLValueString($_POST['inv_email'], "text"),
                       GetSQLValueString($_POST['inv_phone'], "text"),
                       GetSQLValueString($_POST['inv_date'], "date"),
                       GetSQLValueString($_POST['inv_paid_amount'], "double"),
                       GetSQLValueString($_POST['inv_paid_date'], "date"),
                       GetSQLValueString($_POST['inv_comment'], "text"),
                       GetSQLValueString(isset($_POST['inv_bad_debt']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['inv_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = $_SESSION['inv_page'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

if (isset($_GET['inv_id'])) {
  $inv_id = $_GET['inv_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv_edit = "SELECT inv_id, inv_busname, inv_to, inv_street, inv_suburb, inv_city, inv_postcode, inv_email, inv_phone, inv_fax, inv_type,  inv_date, inv_paid_amount, inv_paid_date, inv_comment, inv_to.club_id, inv_status, inv_to.inv_amount_total, total_less_disc,  inv_to.inv_total_all, inv_to.club_id, inv_to.inv_bad_debt FROM inv_to WHERE inv_id = '$inv_id'";
$Inv_edit = mysql_query($query_Inv_edit, $connvbsa) or die(mysql_error());
$row_Inv_edit = mysql_fetch_assoc($Inv_edit);
$totalRows_Inv_edit = mysql_num_rows($Inv_edit);
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

<table width="1000" border="0" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>


<table width="1000" align="center" cellpadding="2">
  <tr>
    <td><?php echo $_SESSION['inv_page']; ?></td>
  </tr>
  <tr>
    <td class="red_bold"><?php echo $club_id; ?></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
  <table width="1100" align="center">
    <tr valign="baseline">
          <td colspan="2" align="left" nowrap="nowrap" class="red_bold">Edit Invoice  number <?php echo $row_Inv_edit['inv_id']; ?></td>
          <td align="right" nowrap="nowrap" class="red_bold">&nbsp;</td>
          <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="left" nowrap="nowrap" class="red_bold">Type of invoice:</td>
          <td align="left" nowrap="nowrap"><select name="inv_type">
            <option value="S1" <?php if (!(strcmp("S1", htmlentities($row_Inv_edit['inv_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S1</option>
            <option value="S2" <?php if (!(strcmp("S2", htmlentities($row_Inv_edit['inv_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S2</option>
            <option value="City Club" <?php if (!(strcmp("City Club", htmlentities($row_Inv_edit['inv_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>City Club</option>
            <option value="Other" <?php if (!(strcmp("Other", htmlentities($row_Inv_edit['inv_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Other</option>
            <option value="Association" <?php if (!(strcmp("Association", htmlentities($row_Inv_edit['inv_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Association</option>
          </select></td>
          <td align="left" nowrap="nowrap">&nbsp;</td>
          <td align="left" nowrap="nowrap">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="left" nowrap="nowrap">&nbsp;</td>
          <td align="left" nowrap="nowrap">&nbsp;</td>
          <td align="left" nowrap="nowrap" class="red_bold">&nbsp;</td>
          <td align="left" nowrap="nowrap" class="red_bold">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="4" align="left" nowrap="nowrap" class="red_bold">Invoice to - contact and address</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Invoice To:</td>
          <td><input type="text" name="inv_busname" value="<?php echo htmlentities($row_Inv_edit['inv_busname'], ENT_COMPAT, 'utf-8'); ?>" size="65" /></td>
          <td align="right">Address:</td>
          <td><input type="text" name="inv_street" value="<?php echo htmlentities($row_Inv_edit['inv_street'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Attention:</td>
          <td><input type="text" name="inv_to" value="<?php echo htmlentities($row_Inv_edit['inv_to'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          <td align="right" nowrap="nowrap">Suburb:</td>
          <td><input type="text" name="inv_suburb" value="<?php echo htmlentities($row_Inv_edit['inv_suburb'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Email:</td>
          <td><input type="text" name="inv_email" value="<?php echo htmlentities($row_Inv_edit['inv_email'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          <td align="right" nowrap="nowrap">Postcode:</td>
          <td><input type="text" name="inv_postcode" value="<?php echo htmlentities($row_Inv_edit['inv_postcode'], ENT_COMPAT, 'utf-8'); ?>" size="10" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Phone:</td>
          <td><input type="text" name="inv_phone" value="<?php echo htmlentities($row_Inv_edit['inv_phone'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          <td align="right" nowrap="nowrap">State</td>
          <td><input type="text" name="inv_city" value="<?php echo htmlentities($row_Inv_edit['inv_city'], ENT_COMPAT, 'utf-8'); ?>" size="10" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="left" nowrap="nowrap" class="red_bold">Financial details</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Invoice Total Amount: </td>
          <td><?php echo $row_Inv_edit['inv_total_all']; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Paid:</td>
          <td><input type="text" name="inv_paid_amount" value="<?php echo htmlentities($row_Inv_edit['inv_paid_amount'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          <td align="right" valign="middle">Set  to  &quot;Bad Debt&quot;</td>
          <td><input type="checkbox" name="inv_bad_debt" id="inv_bad_debt"  <?php if (!(strcmp(htmlentities($row_Inv_edit['inv_bad_debt'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />
            <?php 
			if($row_Inv_edit['inv_bad_debt']==1)
			echo '<img src="../../Admin_Images/bad_debt.JPG" height="18" />';
			else
			echo "";
			?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Paid Date</td>
          <td><input type="text" name="inv_paid_date" value="<?php echo htmlentities($row_Inv_edit['inv_paid_date'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
            <input type="button" value="Insert Paid Date" onclick="displayDatePicker('inv_paid_date', false, 'ymd', '.');" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Status</td>
          <td colspan="3" nowrap="nowrap"><?php
		    //paid
		  if($row_Inv_edit['inv_total_all']==$row_Inv_edit['inv_paid_amount'] & $row_Inv_edit['inv_total_all']>0)
		    echo '<span style="color:#090">' . "Paid" . '</span>';
			//part paid
		  elseif($row_Inv_edit['inv_total_all']!=$row_Inv_edit['inv_paid_amount'] & $row_Inv_edit['inv_paid_amount']>0)
		    echo '<span style="color:#F00">' . "Part Paid" . '</span>';
			//bad debt
		  elseif($row_Inv_edit['inv_bad_debt']==1)
			echo '<span style="color:#F00">' . "Bad Debt" . '</span>';
		  else
			echo '<span style="color:#F00">' . $row_Inv_edit['inv_status'] . '</span>';
		  ?>
          - NOTE: This will update when invoice has been printed to &quot;Sent&quot; or to paid when paid amount= the invoice total </td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Issued on:</td>
          <td colspan="3"><input type="text" name="inv_date" value="<?php echo htmlentities($row_Inv_edit['inv_date'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
            <input type="button" value="Set Issue Date" onclick="displayDatePicker('inv_date', false, 'ymd', '.');" />
            Issued on date will be updated when invoice printed</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">Invoic type:</td>
          <td colspan="3"><?php echo $row_Inv_edit['inv_type']; ?> <span class="red_text">(cannot be edited, if incorrect delete the invoice and create a new one)</span></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">Comment</td>
          <td colspan="3" valign="top" ><textarea name="inv_comment" cols="100" rows="4"><?php echo htmlentities($row_Inv_edit['inv_comment'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="4" align="center" nowrap="nowrap" bgcolor="#CCCCCC"><input type="submit" value="Update invoice" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
  </table>
  <input type="hidden" name="MM_update" value="form2" />
      <input type="hidden" name="inv_id" value="<?php echo $row_Inv_edit['inv_id']; ?>" />
  	  <input type="hidden" name="inv_status" value="<?php
		  //paid
		  if($row_Inv_edit['inv_total_all']==$row_Inv_edit['inv_paid_amount'] & $row_Inv_edit['inv_total_all']>0)
		    echo "Paid";
			//part paid
		  elseif($row_Inv_edit['inv_total_all']!=$row_Inv_edit['inv_paid_amount'] & $row_Inv_edit['inv_paid_amount']>0)
		    echo "Part Paid";
			//bad debt
		  elseif($row_Inv_edit['inv_bad_debt']==1)
			echo "Bad Debt";
		  else
			echo "Not Sent"; 
		  ?>" 
          />
</form>
    <table width="1100" align="center">
    <td width="158" align="right" valign="top">Copy and paste as required:</td>
    <td width="930">Thank you for your participation with the VBSA. To explain the invoice, if some teams cost less it is because the team or teams had byes in their draw, naturally, there is no charge for these rounds. AWS = A Willis Snooker, BWS = B Willis Snooker etc. AVS1/2 = A State Snooker Season 1/2, BVS1/2 = B State Snooker Season 1/2 etc, APB = A Premier Billiards, BPB = B Premier Billiards etc</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">City Clubs:</td>
    <td>Thank you for your participation in the VBSA City Clubs Snooker &amp; Billiards Circuit.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Inv_edit);

?>
