<?php 
require_once('../../Connections/connvbsa.php'); 
include '../../vbsa_online_scores/php_functions.php';
?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $hashed_pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $insertSQL = sprintf("Insert INTO vbsaorga_users2 (vbsa_id, email_address, hashed_password, usertype, block, sendEmail, registerDate, lastvisitDate, activation) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['vbsa_id'], "int"),
                       GetSQLValueString($_POST['email_address'], "text"),
                       GetSQLValueString($hashed_pwd, "text"),
                       GetSQLValueString($_POST['usertype'], "text"),
					             GetSQLValueString(isset($_POST['block']) ? "true" : "", "defined","1","0"),
					             GetSQLValueString(isset($_POST['sendEmail']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['registerDate'], "date"),
                       GetSQLValueString($_POST['lastvisitDate'], "date"),
                       GetSQLValueString($_POST['activation'], "text"));
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../affiliate_members.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$ID = "-1";
if (isset($_POST['ID'])) {
  $ID = $_POST['ID'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_memb = "SELECT MemberID, CONCAT(FirstName,' ',LastName) AS name, MobilePhone, Email, members.BoardMemb, members.board_position FROM members WHERE MemberID = '$ID'";
$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);

mysql_select_db($database_connvbsa, $connvbsa);
$query_User = "SELECT * FROM vbsaorga_users2";
$User = mysql_query($query_User, $connvbsa) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

mysql_select_db($database_connvbsa, $connvbsa);
$query_exist_user = "SELECT * FROM vbsaorga_users2 WHERE vbsa_id ='$ID'";
$exist_user = mysql_query($query_exist_user, $connvbsa) or die(mysql_error());
$row_exist_user = mysql_fetch_assoc($exist_user);
$totalRows_exist_user = mysql_num_rows($exist_user);
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />

</head>
<body>
<script type="text/javascript">

$(document).ready(function()
{

 $('#getPW').click(function(event){
     event.preventDefault();
    var generatedPW = '<?php echo(generatePassword(10)); ?>';
    $("#password").val(generatedPW);
  });

  $('#sendNewEmail').click(function(event){
    event.preventDefault();
    var ID = $("#id").val();
    var Password = $("#password").val();
    var UserType = $("#usertype").val();
    var Email = $("#username").val();
    var Block = $("input[id='block']:checked").val();
    //alert(Block);
    var sendEmail = $("input[id='sendEmail']:checked").val();
    var RegisterDate = $("#registerDate").val();
    var Activation = '';
    //var Activation = $("#activation").val();
    $.ajax({
      url:"new_extra_email.php?MemberID=" + ID + "&Password=" + Password  + "&UserType=" + UserType + "&Email=" + Email + "&Block=" + Block + "&sendEmail=" + sendEmail + "&RegisterDate=" + RegisterDate + "&Activation=" + Activation,
      success : function(response){
        alert(response);
      }
    });
  });

});
</script>

<?php include '../../admin_xx_includes/db_nav.php';?>
<?php include '../../admin_xx_includes/db_srch.php';?>

<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="red_bold">You are about to insert Member Id number <?php echo $ID; ?> as an Affiliate Member</td>
    <td>
      <input type="button" value="Return to previous page" onclick="history.go(-1)"/>
</td>
  </tr>
</table>
<?php if(isset($row_exist_user['vbsa_id'])) { ?>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td><?php echo $row_exist_user['username']; ?> is already listed as:<?php echo $row_exist_user['usertype']; ?></td>
  </tr>
</table>
<?php } else { ?>
<table align="center">
	<tr>
  	<td colspan="3" align="center" class="red_text">The Folowing details will be inserted</td>
	</tr>
  <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align="right">Name:</td>
      <td><?php echo $row_memb['name']; ?></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Personal Email:</td>
    <td><?php echo $row_memb['Email']; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td valign="middle">&nbsp;</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email Address:</td>
      <td><input type="text" name="username" id="username" value="<?php echo $row_memb['Email']; ?>" size="32" /> 
      Registered Email Address</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Password:</td>
      <td><input type="password" name="password" id="password" value="" size="32" readonly/>
      <input type="button" value="Generate Password" name="getPW" id="getPW"/></td>
    </tr>
    <!--
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">UserType:</td>
      <td><input type="text" name="usertype" id="usertype" value="" size="32" /></td>
    </tr>
  -->
    <tr valign="baseline">
       <td nowrap="nowrap" align="right">User Type:</td>
      <td align='left'><select name='usertype' id="usertype">
        <option value=''>Select User Type</option>
        <option value='BBSA'>BBSA</option>
        <option value='BendBSA'>BendBSA</option>
        <option value='ChurchBill'>Church (ChurchBill)</option>
        <option value='CC'>City Clubs (CC)</option>
        <option value='DVSA'>DVSA</option>
        <option value='MSBA'>MSBA</option>
        <option value='O55'>Over 55's (O55)</option>
        <option value='RSL'>RSL</option>
        <option value='SBSA'>SBSA</option>
        <option value='VBSA'>VBSA Gallery (VBSA)</option>
        <option value='WSBSA'>Western Suburbs Over 55's</option>
        </select>
      </td>  
    </tr>   



     <tr valign="baseline">
      <td nowrap="nowrap" align="right">Block:</td>
      <td><input type="checkbox" name="block" id="block" /></td>
    </tr>
     <tr valign="baseline">
      <td nowrap="nowrap" align="right">Send Email:</td>
      <td><input type="checkbox" name="sendEmail" id="sendEmail" /></td>
    </tr>
     <tr valign="baseline">
      <td nowrap="nowrap" align="right">Register Date:</td>
      <td><input type="text" name="registerDate" id="registerDate" value="<?php echo date("d/m/Y"); ; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td colspan=3>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan=3 nowrap="nowrap" align="center">
      <input type="submit" value="Insert Record"/>
      <input type="button" value="Insert Record and Email Password" id="sendNewEmail"/></td>
    </tr>
    <tr valign="baseline">
      <td colspan=2 align=center>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="id" id="id" value="<?php echo $ID; ?>" />
  <input type="hidden" name="vbsa_id" value="<?php echo $ID; ?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>

<?php } ; ?>
</body>
</html>
<?php

?>
