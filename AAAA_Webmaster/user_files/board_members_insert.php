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
  $insertSQL = sprintf("Insert INTO vbsaorga_users (id, board_member_id, name, username, email, hashed_password, usertype, board_desc, display, register_year, order_display, assist, `comment`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['board_member_id'], "int"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($hashed_pwd, "text"),
                       GetSQLValueString($_POST['usertype'], "text"),
					             GetSQLValueString($_POST['board_desc'], "text"),
					             GetSQLValueString(isset($_POST['display']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['register_year'], "date"),
                       GetSQLValueString($_POST['order_display'], "text"),
					             GetSQLValueString(isset($_POST['assist']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['comment'], "text"));
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../board_members.php";
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
$query_User = "SELECT * FROM vbsaorga_users";
$User = mysql_query($query_User, $connvbsa) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

mysql_select_db($database_connvbsa, $connvbsa);
$query_exist_user = "SELECT * FROM vbsaorga_users WHERE board_member_id ='$ID'";
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
    var UserType = $("input[id='usertype']:checked").val();
    var Email = $("#username").val();
    var Name = $("#name").val();
    var MemberID = $("#board_member_id").val();
    var BoardDesc = $("#board_desc").val();
    var Order = $("#order_display").val();
    var Display = $("input[id='display']:checked").val();
    var Assist = $("input[id='assist']:checked").val();
    var Comment = $("#comment").val();

    $.ajax({
      url:"new_board_email.php?Password=" + Password + "&UserType=" + UserType  + "&Name=" + Name + "&Email=" + Email + "&MemberID=" + MemberID + "&BoardDesc=" + BoardDesc + "&Order=" + Order + "&Display=" + Display + "&Assist=" + Assist + "&ID=" + ID + "&Comment=" + Comment,
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
    <td class="red_bold">You are about to insert Member Id number <?php echo $ID; ?> as a Board/Assist Member</td>
    <td>
      <input type="button" value="Return to previous page" onclick="history.go(-1)"/>
    </td>
  </tr>
</table>
<?php if(isset($row_exist_user['board_member_id'])) { ?>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td><?php echo $row_exist_user['name']; ?> is already listed as:<?php echo $row_exist_user['board_desc']; ?></td>
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
    <tr>
      <td colspan="3" align="center" class="red_text">Please enter Board/Assist Position &amp; Login details - Username, Password, VBSA Email, Display order and database access level</td>
    </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <input type="hidden" name="id" id="id" value="<?php echo $ID; ?>"/>
  <input type="hidden" name="name" id="name" value="<?php echo $row_memb['name']; ?>"/>
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Board Position:</td>
      <td><input type="text" name="board_desc" id="board_desc" value="" size="40" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Username:</td>
      <td><input type="text" name="username" id="username" value="" size="40" />&nbsp;Your vbsa email address.</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Password:</td>
      <td><input type="password" name="password" id="password" value="" readonly size="40" />&nbsp;<input type="button" value="Generate Password" id="getPW"/></td>
    </tr>
    <!--<tr valign="baseline">
      <td nowrap="nowrap" align="right">VBSA Email:</td>
      <td><input type="text" name="username" id="username" value="" size="40" /> 
      Email will be visible on the website. </td>
    </tr>-->
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Display on Website?:</td>
      <td><input type="checkbox" name="display" id="display" />Check to make visible on the website.</td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Assitant to the Board?</td>
      <td><input type="checkbox" name="assist" id="assist"/></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Database Access Level</td>
      <td>
        <table>
        <tr>
          <td><input type="radio" name="usertype" id="usertype" value="VBSA" <?php if ($row_users['usertype'] == "VBSA") {echo "checked=\"checked\"";} ?> />
            VBSA</td>
          <td nowrap="nowrap">&nbsp;</td>
          <td nowrap="nowrap">Webpage menu only (Not the Calendar or Forum)</td>
        </tr>
        <tr>
          <td><input type="radio" name="usertype" id="usertype" value="Boardmember" <?php if ($row_users['usertype'] == "Boardmember") {echo "checked=\"checked\"";} ?> />
Boardmember</td>
          <td>&nbsp;</td>
          <td>Webpage, Calendar and the Forum</td>
        </tr>
        <tr>
          <td><input type="radio" name="usertype" id="usertype" value="Administrator" <?php if ($row_users['usertype'] == "Administrator") {echo "checked=\"checked\"";} ?> />
Administrator</td>
          <td>&nbsp;</td>
          <td nowrap="nowrap">Webpage, Calendar, Forum and the Administrative database (no scores or financials)</td>
        </tr>
        <tr>
          <td><input type="radio" name="usertype" id="usertype" value="Scores" <?php if ($row_users['usertype'] == "Scores") {echo "checked=\"checked\"";} ?> />
Scores </td>
          <td>&nbsp;</td>
          <td>Webpage, Calendar, Forum and the Administrative database (no  financials)</td>
        </tr>
        <tr>
          <td><input type="radio" name="usertype" id="usertype" value="Treasurer" <?php if ($row_users['usertype'] == "Treasurer") {echo "checked=\"checked\"";} ?> /> 
            Treasurer
          </td>
          <td>&nbsp;</td>
          <td>Webpage, Calendar, Forum all areas of the Administrative database (no webmaster)</td>
        </tr>
        <tr>
          <td><input type="radio" name="usertype" id="usertype" value="Webmaster" <?php if ($row_users['usertype'] == "Webmaster") {echo "checked=\"checked\"";} ?> />
            Webmaster</td>
          <td>&nbsp;</td>
          <td>All areas</td>
        </tr>
        </table>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Order display:</td>
      <td><select name="order_display" id="order_display" >
        <option value="not ordered" selected="selected" >not ordered</option>
        <option value="01">1</option>
        <option value="02">2</option>
        <option value="03">3</option>
        <option value="04">4</option>
        <option value="05">5</option>
        <option value="06">6</option>
        <option value="07">7</option>
        <option value="08">8</option>
        <option value="09">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
      </select> 
        Set the order Board/Assist member will be displayed in</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Comment:</td>
      <td><textarea name="comment" id="comment" cols="100" rows="5"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td colspan=2 >&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan=2 nowrap="nowrap" align="center">
      <!--<input type="button" value="Generate Password" id="getPW"/>-->
      <input type="submit" value="Insert Record Only"/>
      <input type="button" value="Insert Record and Email Password" id="sendNewEmail"/></td>

      <!--<td><input type="submit" value="Insert record" /></td>-->
    </tr>
    <tr valign="baseline">
      <td colspan=2 >&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="id" value="" />
  <input type="hidden" name="board_member_id" value="<?php echo $row_memb['MemberID']; ?>" />
  <input type="hidden" name="name" value="<?php echo $row_memb['name']; ?>" />
  <input type="hidden" name="email" value="<?php echo $row_memb['Email']; ?>" />
  <input type="hidden" name="display" value="0" />
  <input type="hidden" name="register_year" value="<?php echo date("Y"); ?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>

<?php } ; ?>
</body>
</html>
<?php

?>
