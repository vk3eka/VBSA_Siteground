<?php require_once('../../Connections/connvbsa.php'); ?>
<?php include('../../security_header.php'); ?>
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

$bm = "-1";
if (isset($_GET['bm'])) {
  $bm = $_GET['bm'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Users = "Select id, vbsa_id, CONCAT(FirstName, ' ', LastName) as name, email_address, Email, MobilePhone,  hashed_password, usertype, block, sendEmail, gid, registerDate, lastvisitDate, activation FROM vbsaorga_users2 LEFT JOIN members ON members.MemberID = vbsaorga_users2.vbsa_id WHERE vbsa_id='$bm'";
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

    <table align="center" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="4" align="center">&nbsp;</td>
        <td colspan="3" align="right" class="greenbg">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" align="center"><span class="red_bold">Affiliate Member Detail for: <?php echo $row_Users['name']; ?></span></td>
        <td colspan="3" align="right" class="greenbg"><a href="../affiliate_members.php">Return to Affiliate members</a></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right" bgcolor="#CCCCCC">Member ID</td>
        <td bgcolor="#CCCCCC"><?php echo $row_Users['vbsa_id']; ?></td>
        <td bgcolor="#CCCCCC">&nbsp;</td>
        <td align="right" bgcolor="#CCCCCC">&nbsp;</td>
        <td colspan="3" rowspan="3" bgcolor="#CCCCCC"><p>Member ID, Mobile and Email come from the Members table, </p>
        <p>to edit any of these items please go to the Members section</p></td>
      </tr>
        <tr>
          <td align="right" bgcolor="#CCCCCC">Mobile:</td>
          <td bgcolor="#CCCCCC"><?php echo $row_Users['MobilePhone']; ?></td>
          <td bgcolor="#CCCCCC">&nbsp;</td>
          <td align="right" nowrap="nowrap" bgcolor="#CCCCCC" class="greenbg">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" bgcolor="#CCCCCC">Personal Email:</td>
          <td bgcolor="#CCCCCC"><?php echo $row_Users['Email']; ?></td>
          <td bgcolor="#CCCCCC">&nbsp;</td>
          <td align="right" nowrap="nowrap" bgcolor="#CCCCCC" class="greenbg">&nbsp;</td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="center">&nbsp;</td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="right" class="greenbg"><a href="affiliate_members_edit.php?bm=<?php echo $row_Users['vbsa_id']; ?>">edit user details</a> </td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Email Address/Username:</td>
          <td><?php echo $row_Users['email_address']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Password:</td>
          <td colspan="2"><input type="checkbox" name="password" value="1"  <?php if (($row_Users['hashed_password'] != '')) {echo "checked=\"checked\"";} ?> disabled /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">UserType:</td>
          <td><?php echo $row_Users['usertype']; ?></td>
        </tr>
         <tr valign="baseline">
          <td nowrap="nowrap" align="right">Block:</td>
          <td colspan="2"><input type="checkbox" name="block" value="1"  <?php if (($row_Users['block']== 1)) {echo "checked=\"checked\"";} ?> disabled /></td>
        </tr>
         <tr valign="baseline">
          <td nowrap="nowrap" align="right">Send Email:</td>
          <td colspan="2"><input type="checkbox" name="sendEmail" value="1"  <?php if (($row_Users['sendEmail']== 1)) {echo "checked=\"checked\"";} ?> disabled /></td>
        </tr>
         <tr valign="baseline">
          <td nowrap="nowrap" align="right">Register Date:</td>
          <td><?php echo($row_Users['registerDate']); ?></td>
        </tr>
        <tr valign="baseline">
          <td colspan=3>&nbsp;</td>
        </tr>
    </table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php

mysql_free_result($Users);
?>
