<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$page = "../Admin_DB_VBSA/Members_AA_Affiliates.php";
$_SESSION['page'] = $page;

$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

mysql_select_db($database_connvbsa, $connvbsa);

$query_memb_bulk = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, LifeMember, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members Where affiliate_player = 1";

$memb_bulk = mysql_query($query_memb_bulk, $connvbsa) or die(mysql_error());
$row_memb_bulk = mysql_fetch_assoc($memb_bulk);
$totalRows_memb_bulk = mysql_num_rows($memb_bulk);

$query_memb = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, LifeMember, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members Where affiliate_player = 1 and ReceiveEmail = 1 and Email != ''";

$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);

$query_Count_DVSA = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, LifeMember, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members Where affiliate_player = 1
 AND (Affiliate_1 = 'DVSA' OR Affiliate_2 = 'DVSA' OR Affiliate_3 = 'DVSA' OR Affiliate_4 = 'DVSA')";
$Count_DVSA = mysql_query($query_Count_DVSA, $connvbsa) or die(mysql_error());
$row_Count_DVSA = mysql_fetch_assoc($Count_DVSA);
$totalRows_Count_DVSA = mysql_num_rows($Count_DVSA);

$query_Count_MSBA = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, LifeMember, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members Where affiliate_player = 1
 AND (Affiliate_1 = 'MSBA' OR Affiliate_2 = 'MSBA' OR Affiliate_3 = 'MSBA' OR Affiliate_4 = 'MSBA')";
$Count_MSBA = mysql_query($query_Count_MSBA, $connvbsa) or die(mysql_error());
$row_Count_MSBA = mysql_fetch_assoc($Count_MSBA);
$totalRows_Count_MSBA = mysql_num_rows($Count_MSBA);

$query_Count_LVABA = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, LifeMember, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members Where affiliate_player = 1
 AND (Affiliate_1 = 'LVABA' OR Affiliate_2 = 'LVABA' OR Affiliate_3 = 'LVABA' OR Affiliate_4 = 'LVABA')";
$Count_LVABA = mysql_query($query_Count_LVABA, $connvbsa) or die(mysql_error());
$row_Count_LVABA = mysql_fetch_assoc($Count_LVABA);
$totalRows_Count_LVABA = mysql_num_rows($Count_LVABA);


include 'php_mail_include.php'; // local file with the previous emailling code

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
  
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="2" align="center" class="red_bold">Lists all Affiliate Members</td>
  </tr>
  <tr>
    <td class="greenbg" align="center"><a href="export_csv.php?page=vbsa_affiliates">Export Current Data To CSV File</a></td>
    <td align="center" class="greenbg"><a href="A_memb_index.php">Return to members</a></td>
  </tr>
  <tr>
    <td colspan="2" align="center">Total Records: <?php echo $totalRows_memb_bulk ?>  (Receive Email <?php echo $totalRows_memb ?>) of which:-</td>
  </tr>
  <tr>
    <td colspan="2" align="center">Total DVSA: <?php echo $totalRows_Count_DVSA ?></td>
  </tr>
  <tr>
    <td colspan="2" align="center">Total MSBA: <?php echo $totalRows_Count_MSBA ?></td>
  </tr>
  <tr>
    <td colspan="2" align="center">Total LVABA: <?php echo $totalRows_Count_LVABA ?></td>
  </tr>
</table>
<table border="1" align="center" cellpadding="1" class="page">
  <tr>
    <!--<td align="center">ID</td>-->
    <td align="center">Member ID</td>
    <td>Last Name</td>
    <td>First Name</td>
    <td>Mobile Phone</td>
    <td>Email</td>
    <td align="center">DVSA</td>
    <td align="center">MSBA</td>
    <td align="center">LVABA</td>
    <td align="center">Receive Email</td>
    <td align="center">Receive SMS</td>
    <td align="center">Life Member</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_memb_bulk['MemberID']; ?></td>
      <td><?php echo $row_memb_bulk['LastName']; ?></td>
      <td><?php echo $row_memb_bulk['FirstName']; ?></td>
      <td><a href="tel:<?php echo $row_memb_bulk['MobilePhone']; ?>"><?php echo $row_memb_bulk['MobilePhone']; ?></a></td>
      <td><a href="mailto:<?php echo $row_memb_bulk['Email']; ?>"><?php echo $row_memb_bulk['Email']; ?></a></td>
      <?php 
      if(($row_memb_bulk['Affiliate_1'] == 'DVSA') || ($row_memb_bulk['Affiliate_2'] == 'DVSA') || ($row_memb_bulk['Affiliate_3'] == 'DVSA') || ($row_memb_bulk['Affiliate_4'] == 'DVSA'))
      {
        echo("<td align='center'><input type='checkbox' id='DVSA' checked disabled ></td>");
      }
      else
      {
        echo("<td align='center'><input type='checkbox' id='DVSA' disabled></td>");
      }
      if(($row_memb_bulk['Affiliate_1'] == 'MSBA') || ($row_memb_bulk['Affiliate_2'] == 'MSBA') || ($row_memb_bulk['Affiliate_3'] == 'MSBA') || ($row_memb_bulk['Affiliate_4'] == 'MSBA'))
      {
        echo("<td align='center'><input type='checkbox' id='MSBA' checked disabled ></td>");
      }
      else
      {
        echo("<td align='center'><input type='checkbox' id='MSBA' disabled></td>");
      }
      if(($row_memb_bulk['Affiliate_1'] == 'LVABA') || ($row_memb_bulk['Affiliate_2'] == 'LVABA') || ($row_memb_bulk['Affiliate_3'] == 'LVABA') || ($row_memb_bulk['Affiliate_4'] == 'LVABA'))
      {
        echo("<td align='center'><input type='checkbox' id='LVABA' checked disabled ></td>");
      }
      else
      {
        echo("<td align='center'><input type='checkbox' id='LVABA' disabled></td>");
      }
      if($row_memb_bulk['ReceiveEmail'] == 1)
      {
        echo("<td align='center'><input type='checkbox' id='ReceiveEmail' checked disabled ></td>");
      }
      else
      {
        echo("<td align='center'><input type='checkbox' id='ReceiveEmail' disabled></td>");
      }
      if($row_memb_bulk['ReceiveSMS'] == 1)
      {
        echo("<td align='center'><input type='checkbox' id='ReceiveSMS' checked disabled ></td>");
      }
      else
      {
        echo("<td align='center'><input type='checkbox' id='ReceiveSMS' disabled></td>");
      }
      if($row_memb_bulk['LifeMember'] == '1')
      {
        echo("<td align='center'><input type='checkbox' id='life' checked disabled ></td>");
      }
      else
      {
        echo("<td align='center'><input type='checkbox' id='life' disabled></td>");
      }
      ?>
      <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_memb_bulk['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
      <td><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_memb_bulk['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" title="edit"  /></a></td>
  </tr>
    <?php } while ($row_memb_bulk = mysql_fetch_assoc($memb_bulk)); ?>
</table>
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
    <td class="greenbg"><a href="attach_upload.php">Please upload it now</a></td>
    <td align="right" class="greenbg"><a href="Bulk_email_help.pdf">Bulk Email help</a></td>
  </tr>
  <tr>
    <td width="211" align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>To Send a group email: </td>
    <td width="11">&nbsp;</td>
    <td colspan="2">1. Type your email address in the &quot;From&quot; field</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">2. Type VBSA in &quot;Name&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">3. From the &quot;Recordset fields&quot; select &quot;Email&quot;. Click the <img src="php_mail_merge/dynamic_e.gif" alt="1" width="17" height="17" /> button and it will add this field into the &quot;To&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">4. In the message area type a greeting &quot;Hi&quot; followed by a space. From the&quot;recordset fields&quot; select &quot;Firstname&quot; </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;&nbsp;&nbsp;click the <img src="php_mail_merge/dynamic_t.gif" alt="1" /> button and it will add a field that will reflect the first name of the person to receive the email</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">5. Tick the box for &quot;Plain Text&quot; alongside &quot;Send as&quot; </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">6. Type in the subject and your message in the appropriate fields and click &quot;Send&quot;.</td>
  </tr>
</table>
<p><br />
  <script language="JavaScript" src="php_mail_merge/php_mail_merge.js" type="text/javascript">
              </script>
  <script language="JavaScript" type="text/javascript">initPMM();<?php if (isset($_POST["SendAs"]) && !isset($_POST['reset_editor'])){?>flipMessageFormat(document.getElementById('SendAs'));<?php }?></script>
  </p>
<form action="" method="post" name="editor_form" id="editor_form">
<br />
<?php $myRecordset=$memb; $myTotalRecords=$totalRows_memb; ?>
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
