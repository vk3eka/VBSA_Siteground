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

if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_players1 = "SELECT ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, amount_entry, how_paid, entry_confirmed, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, ranknum, wcard, rank_pts, Junior FROM tourn_entry, members  LEFT JOIN rank_S_open_tourn ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=1 ORDER BY FirstName, Junior";
$players1 = mysql_query($query_players1, $connvbsa) or die(mysql_error());
$row_players1 = mysql_fetch_assoc($players1);
$totalRows_players1 = mysql_num_rows($players1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn1 = "SELECT * FROM tournaments WHERE tourn_id = '$tourn_id'";
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn_status = "SELECT status FROM tournaments WHERE tourn_id = '$tourn_id'";
$tourn_status = mysql_query($query_tourn_status, $connvbsa) or die(mysql_error());
$row_tourn_status = mysql_fetch_assoc($tourn_status);
$totalRows_tourn_status = mysql_num_rows($tourn_status);


$query_memb = "Select ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, amount_entry, how_paid, entry_confirmed, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, ranknum, wcard, rank_pts, Junior FROM tourn_entry, members  LEFT JOIN rank_S_open_tourn ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=1 AND (ReceiveEmail = 1 AND Email != '') ORDER BY FirstName, Junior";

$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);

$myRecordset=$memb; $myTotalRecords=$totalRows_memb;

include 'php_mail_include.php'; // local file with the previous emailling code

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


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="1100" align="center">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="middle"><span class="red_bold">Tournament Detail - lists all players and information </span>for the  <?php echo $row_tourn1['tourn_name']; ?> in <?php $newDate = date("Y", strtotime($row_tourn1['tourn_year'])); echo $newDate; ?></td>
    <td valign="middle"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td>Total Entries :<?php echo $totalRows_players1 ?></td>
    <td colspan="2"><span class="page">Entry status: <?php echo $row_tourn_status['status']; ?></span></td>
  </tr>
</table>
<table align="center">
  <tr>
	    <td>Tournament will be seeded using: <?php echo $row_tourn1['how_seed']; ?></td>
      </tr>
</table>
	<table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td align="center">Tourn ID</td>
        <td align="left">Memb ID</td>
        <td align="left">Name</td>
        <td align="left">Email</td>
        <td align="left" nowrap="nowrap">Rec. Email</td>
        <td align="left">Phone</td>
        <td align="center">Paid</td>
        <td align="center" nowrap="nowrap">How Paid</td>
        <td align="center">Member?</td>
        <td align="center" nowrap="nowrap">Entered On</td>
        <td align="center" nowrap="nowrap">Vic Rank</td>
        <td align="center">Wildcard</td>
        <td align="center" nowrap="nowrap">Rank Points</td>
        <td align="center">Junior</td>
      </tr>
      <?php do { ?>
        <tr class="page">
          <td align="center"><?php echo $row_players1['TournID']; ?></td>
          <td align="left"><?php echo $row_players1['MemberID']; ?></td>
          <td align="left"><?php echo $row_players1['FullName']; ?></td>
          <td align="left"><a href="mailto:<?php echo $row_players1['Email']; ?>"><?php echo $row_players1['Email']; ?></a></td>
          <?php 
          if($row_players1['ReceiveEmail'] == 1)
          {
            echo("<td align='center'><input type='checkbox' id='ReceiveEmail' checked disabled ></td>");
          }
          else
          {
            echo("<td align='center'><input type='checkbox' id='ReceiveEmail' disabled></td>");
          }
          ?>
          <td><a href="tel:<?php echo $row_players1['MobilePhone']; ?>"><?php echo $row_players1['MobilePhone']; ?></a></td>
          <td align="center"><?php echo $row_players1['amount_entry']; ?></td>
          <td align="center"><?php echo $row_players1['how_paid']; ?></td>
          <td align="center"><?php echo $row_players1['memb']; ?></td>
          <td align="center"><?php $date = $row_players1['tourn_date_ent']; echo date("d M", strtotime($date)); ?></td>
          <td align="center"><?php echo $row_players1['ranknum']; ?></td>
          <td align="center"><?php echo $row_players1['wcard']; ?></td>
          <td align="center"><?php echo $row_players1['rank_pts']; ?></td>
          <td align="center"><?php echo $row_players1['Junior']; ?></td>
        </tr>
        <?php } while ($row_players1 = mysql_fetch_assoc($players1)); ?>
    </table>
<?php
$genCSVLink = "download_csv.php?id=".$_GET['tourn_id'];
?>
<table cellspacing="5" cellpadding="5" align="center">
  <tr>
    <td class="greenbg"><a href="<?PHP echo $genCSVLink?>">Download .csv</a></td>
  </tr>
</table>
<form action="" method="post" name="editor_form" id="editor_form">
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="134" class="page">&nbsp;</td>
    <td width="551" align="right" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td>Would you like to send an attachment?</td>
    <td>&nbsp;</td>
    <td class="greenbg"><a href="../Admin_DB_VBSA/attach_upload.php">Please upload it now</a></td>
    <td align="right" class="greenbg"><a href="../Admin_DB_VBSA/Bulk_email_help.pdf">Bulk Email help</a></td>
  </tr>
  <tr>
    <td width="211" align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>To Send a group email: </td>
    <td width="11">&nbsp;</td>
    <td colspan="2">1. Type your email address in both the &quot;From&quot; and the &quot;Reply to&quot; fields.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">2. Type your name, e.g. &quot;VBSA Secratary&quot; in the &quot;Name&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">3. From the &quot;Recordset fields&quot; select &quot;Email&quot;. Click the <img src="php_mail_merge/dynamic_e.gif" alt="1" width="17" height="17" /> button and it will add this field into the &quot;To&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">4. Enter the subject fo your email.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">5. If required, attach a file. See above. Only certain file types allowed to be attached.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">6. Select &quot;Design View&quot;. This allows a greater degree of formatting options.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">7. Type your message.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">8. To personalise your message, at the start of the message area, type a greeting e.g. &quot;Hi&quot;
followed by a space. Then from &quot;Recordset fields&quot; select &quot;Firstname&quot;. Click the <img src="php_mail_merge/dynamic_t.gif" alt="1" /> button
and it will add the &quot;Firstname&quot; field into the Message box. This will reflect the first name
of the person to receive the email. You can add additional personalisations if you wish.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">9. Click &quot;Send&quot; then OK to Continue when prompted.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">10. Bulk Emails are only sent to members who have consented to receive emails AND have a valid email address.</td>
  </tr>
</table>
  <p><br />
    <script language="JavaScript" src="php_mail_merge/php_mail_merge.js" type="text/javascript">
                </script>
    <script language="JavaScript" type="text/javascript">initPMM();<?php if (isset($_POST["SendAs"]) && !isset($_POST['reset_editor'])){?>flipMessageFormat(document.getElementById('SendAs'));<?php }?></script>
    </p>
  <br />
  <table width="778" border="0" align="center" cellpadding="3" cellspacing="0" id="filters">
    <tr>
      <td title="Area designated for Recordset filters (form fields)"><fieldset>
        <legend>Filters</legend>
        <br />
        <br />
        Reset Editor:
        <input name="reset_editor" type="checkbox" id="reset_editor" title="Reset Editor fields when filtering the Recordset" value="1" />
        <input name="Filter" type="submit" value="Filter" onclick="refreshSource();document.getElementById('Do_Send').value=''" id="Filter" title="Filter the Recordset."/>
      </fieldset></td>
    </tr>
</table>
  <?php include("php_mail_merge.php"); ?>
  </form>
  <script language="JavaScript" src="php_mail_merge/php_mail_merge.js" type="text/javascript">
    </script>
  <script language="JavaScript" type="text/javascript">initPMM();<?php if (isset($_POST["SendAs"]) && !isset($_POST['reset_editor'])){?>flipMessageFormat(document.getElementById('SendAs'));<?php }?></script>
</center>
</body>
</html>