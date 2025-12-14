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
  $updateSQL = sprintf("Update vbsaorga_users SET board_member_id=%s, name=%s, email=%s, username=%s, usertype=%s, board_desc=%s, display=%s, register_year=%s, order_display=%s, assist=%s, `comment`=%s WHERE id=%s",
                       GetSQLValueString($_POST['board_member_id'], "int"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['email_vbsa'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['usertype'], "text"),
                       GetSQLValueString($_POST['board_desc'], "text"),
                       GetSQLValueString(isset($_POST['display']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['register_year'], "date"),
                       GetSQLValueString($_POST['order_display'], "text"),
                       GetSQLValueString(isset($_POST['assist']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['comment'], "text"),
                       GetSQLValueString($_POST['id'], "int"));
  mysql_select_db($database_connvbsa, $connvbsa);
  //echo($updateSQL . "<br>");
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  // delete password data if checkbox unchecked
  if($_POST['password_checked'] == false)
  {
    $updateSQL = sprintf("Update vbsaorga_users2 SET hashed_password = '' WHERE id=%s",
                       GetSQLValueString($_POST['id'], "int"));
    mysql_select_db($database_connvbsa, $connvbsa);
    $Result2 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
  }
  
  $updateGoTo = $_SERVER['PHP_SELF'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  echo("<script type='text/javascript'>");
  echo("alert('The Data has been saved.');");
  echo("</script>");
  header(sprintf("Location: %s", $updateGoTo));
}

$bm = "-1";
if (isset($_GET['bm'])) {
  $bm = $_GET['bm'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Board = "Select id, board_member_id, CONCAT(FirstName,' ',Lastname) AS name, MobilePhone, members.Email, board_desc, vbsaorga_users.username, hashed_password, usertype, display, register_year, order_display, assist, `comment` FROM vbsaorga_users LEFT JOIN members ON MemberID = board_member_id WHERE id='$bm'";
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
    $("input[id='password_checked']").prop( "checked", true );
  });

  $('#sendNewEmail').click(function(event){
    event.preventDefault();
    var Password = $("#password").val();
    var UserType = $("input[name='usertype']").val();
    var Email = $("#email_vbsa").val();
    var MemberID = $("#board_member_id").val();
    var UserName = $("#username").val();
    $.ajax({
      url:"board_send_email.php?Password=" + Password + "&UserType=" + UserType + "&Email=" + Email + "&MemberID=" + MemberID + "&UserName=" + UserName,
      success : function(response){
        alert(response);
        $('#form2').submit();
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
      <td colspan="3" align="center" nowrap="nowrap" class="red_bold">Edit a Board/Assist Member:<?php echo $row_Board['name']; ?><?php echo $_POST['ButtonName']; ?></td>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>

    </tr>
    <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Member ID:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="board_member_id" id="board_member_id" value="<?php echo $row_Board['board_member_id']; ?>" size="32" /></td>
      <td rowspan="4" valign="middle" bgcolor="#CCCCCC" style="padding-left:10px">Member ID Denotes who holds the position. Name, Mobile and Email come from the Members table, <br/>
when you submit this page it will update the &quot;Users&quot; table <br/> 
To update any personal details please go to the &quot;Members&quot; area, this page will then reflect those changes.
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Phone:</td>
      <td>&nbsp;</td>
      <td><?php echo $row_Board['MobilePhone']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Personal email:</td>
      <td>&nbsp;</td>
      <td><?php echo $row_Board['Email']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Position:</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type="text" name="board_desc" id="board_desc" value="<?php echo $row_Board['board_desc']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Username:</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type="text" name="username" id="username" value="<?php echo $row_Board['username']; ?>" size="32" /></td>
    </tr>
    <?php
      if($row_Board['hashed_password'] != "")
      {
        $checked = ' checked';
      }
    ?>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Password:</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type=checkbox <?php echo($checked); ?> name="password_checked" id="password_checked"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Email display?:</td>
      <td>&nbsp;</td>
      <td colspan="2"><p>
        <input type="text" name="email_vbsa" id="email_vbsa" value="<?php echo $row_Board['username']; ?>" size="32" />
        Email will be visible on the website, or, 
      If the Board member is to receive email via a &quot;@vbsa.org.au&quot; address insert here</td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Please set <br/>Usertype:</td>
      <td>&nbsp;</td>
      <td colspan="2"><!-- Nested table -->
      		<table>
            <tr>
              <td><input type="radio" name="usertype" id="usertype" value="Forum"  <?php if ($row_Board['usertype'] == "Forum") {echo "checked=\"checked\"";} ?> />
              Forum</td>
              <td>&nbsp;</td>
              <td>Access to the forum only</td>
            </tr>
             <tr>
          	<td><input type="radio" name="usertype" id="usertype" value="Boardmember"  <?php if ($row_Board['usertype'] == "Boardmember") {echo "checked=\"checked\"";} ?> />Boardmember</td>
          	<td>&nbsp;</td>
          	<td>Webpages, Calendar and the Forum</td>
        	</tr>
        	<tr>
          	<td><input type="radio" name="usertype" id="usertype" value="Administrator"  <?php if ($row_Board['usertype'] == "Administrator") {echo "checked=\"checked\"";} ?> />Administrator</td>
          	<td>&nbsp;</td>
          	<td>Webpages, Calendar, Forum and the Administrative database (no scores or financials)</td>
        	</tr>
        	<tr>
          	<td><input type="radio" name="usertype" id="usertype" value="Scores"  <?php if ($row_Board['usertype'] == "Scores") {echo "checked=\"checked\"";} ?> /> Scores</td>
          	<td>&nbsp;</td>
          	<td>Webpages, Calendar, Forum and the Administrative database (no  financials)</td>
        	</tr>
            <tr>
          	<td><input type="radio" name="usertype" id="usertype" value="Treasurer"  <?php if ($row_Board['usertype'] == "Treasurer") {echo "checked=\"checked\"";} ?> /> Treasurer</td>
          	<td>&nbsp;</td>
          	<td>Webpages, Calendar, Forum all areas of the Administrative database (no webmaster)</td>
        	</tr>
             <tr>
          	<td><input type="radio" name="usertype" id="usertype" value="Webmaster"  <?php if ($row_Board['usertype'] ==  "Webmaster") {echo "checked=\"checked\"";} ?> /> Webmaster</td>
          	<td>&nbsp;</td>
          	<td>All areas</td>
        	</tr>
      		</table>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Appear on Website:</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type="checkbox" name="display" value="1"  <?php if (!(strcmp(htmlentities($row_Board['display'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Assistant to Board</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type="checkbox" name="assist" value="1"  <?php if (!(strcmp(htmlentities($row_Board['assist'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />        (Does not hold a position on the board, assists only) </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Register year:</td>
      <td>&nbsp;</td>
      <td colspan="2"><input type="text" name="register_year" value="<?php echo $row_Board['register_year']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Order display:</td>
      <td>&nbsp;</td>
      <td colspan="2"><select name="order_display">
        <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
        <option value="01" <?php if (!(strcmp("01", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>01</option>
        <option value="02" <?php if (!(strcmp("02", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>02</option>
        <option value="03" <?php if (!(strcmp("03", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>03</option>
        <option value="04" <?php if (!(strcmp("04", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>04</option>
        <option value="05" <?php if (!(strcmp("05", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>05</option>
        <option value="06" <?php if (!(strcmp("06", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>06</option>
        <option value="07" <?php if (!(strcmp("07", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>07</option>
        <option value="08" <?php if (!(strcmp("08", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>08</option>
        <option value="09" <?php if (!(strcmp("09", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>09</option>
        <option value="10" <?php if (!(strcmp("10", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
        <option value="11" <?php if (!(strcmp("11", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>11</option>
        <option value="12" <?php if (!(strcmp("12", htmlentities($row_Board['order_display'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>12</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Comment:</td>
      <td>&nbsp;</td>
      <td colspan="2"><textarea name="comment" cols="75" rows="5"><?php echo $row_Board['comment']; ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td colspan=2 >&nbsp;&nbsp;<input type="submit" value="Save Record Only"/>
      </td>
      <td>&nbsp;</td>
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
    <tr>
      <td colspan=3 align="center">Email is sent to the 'vbsa.org.au' email address.</td>    
    </tr>
  </table>


  <input type="hidden" name="id" value="<?php echo $row_Board['id']; ?>" />
  <input type="hidden" name="name" value="<?php echo $row_Board['name']; ?>" />
  <input type="hidden" name="email" value="<?php echo $row_Board['Email']; ?>" /> 
  <input type="hidden" name="MM_update" value="form2" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Board);
?>