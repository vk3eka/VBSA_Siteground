<?php 
require_once('../../Connections/connvbsa.php'); 
include('../../security_header.php');
include('../../vbsa_online_scores/php_functions.php');
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("Update vbsaorga_users2 SET email_address=%s, usertype=%s, block=%s, sendEmail=%s, registerDate=%s, lastvisitDate=%s, activation=%s WHERE vbsa_id=%s",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['usertype'], "text"),
                       GetSQLValueString(isset($_POST['block']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['sendEmail']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['registerDate'], "date"),
                       GetSQLValueString($_POST['lastvisitDate'], "date"),
                       GetSQLValueString($_POST['activation'], "text"),
                       GetSQLValueString($_POST['vbsa_id'], "int"));
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = $_SERVER['PHP_SELF'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  echo("<script type='text/javascript'>");
  echo("alert('The Data has been saved.');");
  echo("</script>");
  //header(sprintf("Location: %s", $updateGoTo));
}

$bm = "-1";
if (isset($_GET['bm'])) {
  $bm = $_GET['bm'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Board = "Select id, vbsa_id, CONCAT(FirstName, ' ', LastName) as name, email_address, MobilePhone, hashed_password, usertype, block, sendEmail, gid, registerDate, lastvisitDate, activation FROM vbsaorga_users2 LEFT JOIN members ON members.MemberID = vbsaorga_users2.vbsa_id WHERE vbsa_id='$bm'";
//echo($query_Board . "<br>");
$Board = mysql_query($query_Board, $connvbsa) or die(mysql_error());
$row_Board = mysql_fetch_assoc($Board);
$totalRows_Board = mysql_num_rows($Board);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

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
    var Password = $("#password").val();
    var UserType = $("#usertype").val();
    var Email = $("#username").val();
    var MemberID = $("#vbsa_id").val();

    $.ajax({
      url:"extra_send_email.php?Password=" + Password + "&UserType=" + UserType + "&Email=" + Email + "&MemberID=" + MemberID,
      success : function(response){
        alert(response);
      }
    });
  });

});
</script>
<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2" >
  <input type='hidden' name='ButtonName' id='ButtonName'>
  <table align="center" style="min-width:800px">
    <tr valign="baseline">
      <td colspan="3" align="left" nowrap="nowrap" class="red_bold">&nbsp;</td>
      <td align="right">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" align="left" nowrap="nowrap" class="red_bold">Edit an Affiliate Member:<?php echo $row_Board['name']; ?><?php echo $_POST['ButtonName']; ?></td>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>

    </tr>
    <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right" bgcolor="#CCCCCC">Member ID</td>
        <td bgcolor="#CCCCCC"><?php echo $row_Board['vbsa_id']; ?></td>
        <td bgcolor="#CCCCCC">&nbsp;</td>
        <td rowspan="3" bgcolor="#CCCCCC"><p>Member ID, Mobile and Email come from the Members table, </p>
        <p>to edit any of these items please go to the Members section</p></td>
      </tr>
        <tr>
          <td align="right" bgcolor="#CCCCCC">Mobile:</td>
          <td bgcolor="#CCCCCC"><?php echo $row_Board['MobilePhone']; ?></td>
          <td bgcolor="#CCCCCC">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" bgcolor="#CCCCCC">Personal Email:</td>
          <td bgcolor="#CCCCCC"><?php echo $row_Board['Email']; ?></td>
          <td bgcolor="#CCCCCC">&nbsp;</td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="right">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Username:</td>
          <td><input type="text" name="username" id="username" value="<?php echo $row_Board['email_address']; ?>" size="32" /></td>
        </tr>
        <?php
          if($row_Board['hashed_password'] != "")
          {
            $checked = ' checked';
          }
        ?>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Password:</td>
          <td><input type=checkbox <?php echo($checked); ?> disabled></td>
        </tr>

        <tr valign="baseline">
           <td nowrap="nowrap" align="right">User Type:</td>
          <td align='left'><select name='usertype' id="usertype">
            <option value='<?php echo $row_Board['usertype']; ?>'><?php echo $row_Board['usertype']; ?></option>
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
            </select>
          </td>  
        </tr>   

        <!--
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">UserType:</td>
          <td><input type="text" name="usertype" id="usertype" value="<?php echo $row_Board['usertype']; ?>" size="32" /></td>
        </tr>
      -->
         <tr valign="baseline">
          <td nowrap="nowrap" align="right">Block:</td>
          <td><input type="checkbox" name="block" value="1"  <?php if (($row_Board['block']== '1')) {echo "checked=\"checked\"";} ?> /></td>
        </tr>
         <tr valign="baseline">
          <td nowrap="nowrap" align="right">Send Email:</td>
          <td><input type="checkbox" name="sendEmail" value="1"  <?php if (($row_Board['sendEmail']== '1')) {echo "checked=\"checked\"";} ?> /></td>
        </tr>
         <tr valign="baseline">
          <td nowrap="nowrap" align="right">Register Date:</td>
          <td><input type="text" name="registerDate" value="<?php echo ($row_Board['registerDate']); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td colspan=3>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan=2 nowrap="nowrap" align="center">
          <!--<input type="button" value="Generate Password" name="getPW" id="getPW"/>-->
          <input type="submit" value="Update Record Only"/>
          <!--<input type="button" value="Email New Password" id="sendNewEmail"/></td>-->
        </tr>
      </table>

      <br>
      <br>
      <br>
      <table align="center" border=0 style="min-width:600px">
        <tr>
          <td bgcolor="#CCCCCC" style="padding-left:10px" nowrap="nowrap" align="right"><input type="button" value="Generate New Password" id="getPW"/></td>
          <td bgcolor="#CCCCCC" style="padding-left:10px" nowrap="nowrap" align="right">Password:</td></td>
          <td bgcolor="#CCCCCC" style="padding-left:10px"><input type="password" name="password" id="password" value="" size="25" readonly/></td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC" style="padding-left:10px" colspan=3 align=center><input type="button" value="Save & Email New Password" id="sendNewEmail"/></td>
        </tr>
      </table>


  <input type="hidden" name="vbsa_id" id="vbsa_id" value="<?php echo $row_Board['vbsa_id']; ?>" />
  <input type="hidden" name="lastvisitDate" id="lastvisitDate" value="<?php echo $row_Board['lastvisitDate']; ?>" />
    <input type="hidden" name="activation" id="activation" value="<?php echo $row_Board['activatione']; ?>" />
  <!--<input type="hidden" name="name" id="name" value="<?php echo $row_Board['name']; ?>" />
  <input type="hidden" name="email" id="email" value="<?php echo $row_Board['Email']; ?>" /> -->
  <input type="hidden" name="MM_update" value="form2" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Board);
?>