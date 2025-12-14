<?php require_once('../Connections/connvbsa.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO forum_alert (alert_id, alert_by, alert_date, alert_comment, alert_priority) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['alert_id'], "int"),
                       GetSQLValueString($_POST['alert_by'], "text"),
                       GetSQLValueString($_POST['alert_date'], "date"),
                       GetSQLValueString($_POST['alert_comment'], "text"),
                       GetSQLValueString($_POST['alert_priority'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
  
    /*Sending Email starts here */
  include("mailer.php");  
  function sendEmail($to,$message){

		$mailer = new mailer($to,"NOTIFICATION ONLY - AUTOMATED EMAIL",$message);
  }
  
  $message = "Reply/s have been posted to the forum by: " .$_POST['alert_by']."\r\n
			PLEASE VISIT THE FORUM - DO NOT REPLY TO THIS EMAIL!\r\n
			" .$_POST['alert_comment']."\r\n;
			Priority: " .$_POST['alert_priority']."\r\n;";
  
  $q = "SELECT * FROM vbsaorga_users";
  $r = mysql_query($q) or die("Error 1.1. Contact admin".mysql_error());
  
  
  $final_message="An email has been sent to all Board members including your name, comment and message priority<BR>";
  while($row=mysql_fetch_array($r)){
    $to = $row['email_vbsa'];
	if(empty($to))continue;
	sendEmail($to,$message);
  }
  
  /*Sending email part ends here*/  
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO forum_alert (alert_id, alert_by, alert_date, alert_comment, alert_priority) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['alert_id'], "int"),
                       GetSQLValueString($_POST['alert_by'], "text"),
                       GetSQLValueString($_POST['alert_date'], "date"),
                       GetSQLValueString($_POST['alert_comment'], "text"),
                       GetSQLValueString($_POST['alert_priority'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_forum_users = "SELECT * FROM vbsaorga_users WHERE email_vbsa is not null AND password is not null ORDER BY vbsaorga_users.name";
$forum_users = mysql_query($query_forum_users, $connvbsa) or die(mysql_error());
$row_forum_users = mysql_fetch_assoc($forum_users);
$totalRows_forum_users = mysql_num_rows($forum_users);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Board Members Forum</title>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/forum_db.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/forum_db_links.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="forum_header">
  <div id="logo"><img src="../images/VBSA1.jpg" alt="" width="90" height="87" /></div>

<table width="870" align="right">
  <tr>

    <td class="red_bold">VBSA Administrators Forum</td>
    <td align="right" class="bluebg"><a href="How%20to%20use%20the%20VBSA%20Board%20Forum.pdf" target="_blank">How to use the Forum</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="red_bold">Forum Users Current</td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

<div id="ContentDB">
<table align="center" cellpadding="5">
    <tr>
      <td>Name</td>
      <td>Email </td>
    </tr>
    <?php do { ?><tr>
      <td><?php echo $row_forum_users['name']; ?></td>
      <td class="page"><a href="mailto:<?php echo $row_forum_users['email_vbsa']; ?>"><?php echo $row_forum_users['email_vbsa']; ?></a></td>
    </tr><?php } while ($row_forum_users = mysql_fetch_assoc($forum_users)); ?>
  </table>
  
<p>&nbsp;</p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td class="red_bold">&nbsp;</td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td class="red_bold">Send an email notification to all Board members</td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">From (please type your name): </td>
      <td><span id="sprytextfield1">
        <input type="text" name="alert_by" value="" size="50" />
        <span class="textfieldRequiredMsg">Please type your name.</span></span></td>
      </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Comment: </td>
      <td><span id="sprytextarea1">
        <textarea name="alert_comment" cols="80" rows=""></textarea>
        <span class="textareaRequiredMsg">Please type a comment.</span></span></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Priority: </td>
      <td>
        <div id="spryradio1">
          <table width="300">
            <tr>
              <td><label>
                <input type="radio" name="alert_priority" value="Urgent" id="RadioGroup1_0" />
                Urgent</label></td>
              
              <td><label>
                <input type="radio" name="alert_priority" value="FYI" id="RadioGroup1_1" />
                FYI</label></td>
              
              <td><label>
                <input type="radio" name="alert_priority" value="Administrator" id="RadioGroup1_1" />
                Administrator</label></td>
              </tr>
            </table>
        <span class="radioRequiredMsg">Please make a selection.</span></div></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Send Email" /></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td align="left" class="red_bold">
  <?php
echo $final_message;
?>
        </td>
      </tr>
    </table>
  <p>
    <input type="hidden" name="alert_id" value="" />
    <input type="hidden" name="alert_date" value="" />
    <input type="hidden" name="MM_insert" value="form1" />
    </p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1");
</script>
</div>

</body>
</html>
<?php
mysql_free_result($forum_users);
?>
