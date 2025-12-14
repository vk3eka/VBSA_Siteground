<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

// set page url in session for insert / update files
$detail = "../by_association.php";
$_SESSION['detail'] = $detail;

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

$redirect = "-1";
if (isset($_GET['redirect'])) {
  $redirect = $_GET['redirect'];
}

$assoc = "-1";
if (isset($_GET['assoc'])) {
  $assoc = $_GET['assoc'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_AllClubs = "SELECT ClubNumber, ClubTitle, Club_Aff_Assoc, ClubStreet, ClubSuburb, ClubPcode, ClubPhone1, ClubEmail, ClubContact, VBSAteam, BBSAteam, BendBSA, ChurchBill, CityClubTeam, DVSAteam, MSBAteam, Over55team, RSLteam, SouthernTeam, WSBSA FROM clubs WHERE ".$assoc."=1 ORDER BY ClubTitle";
$AllClubs = mysql_query($query_AllClubs, $connvbsa) or die(mysql_error());
$row_AllClubs = mysql_fetch_assoc($AllClubs);
$totalRows_AllClubs = mysql_num_rows($AllClubs);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="769" align="center">
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><form id="form7" name="form7" method="get" action="clubs_srch_res.php">
      <input name="clubfind" type="text" id="clubfind" size="12" />
      <input type="submit" value="Search Clubs by name" />
    </form></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><span class="page">When you update a clubs &quot;Public View&quot; details please check the <a href="../Club_dir/club_index.php" target="_blank">web page</a></span></td>
    <td align="right">Total Clubs:</td>
    <td align="left"><?php echo $totalRows_AllClubs ?></td>
  </tr>
</table>

<table align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="red_bold">All 
      <?php 
	if($assoc=='VBSAteam') echo "VBSA";
	elseif($assoc=='BBSAteam') echo "BBSA";
	elseif($assoc=='ChurchBill') echo "Church Billiards";
	elseif($assoc=='CityClubTeam') echo "City Clubs";
	elseif($assoc=='DVSAteam') echo "DVSA";
	elseif($assoc=='MSBAteam') echo "MSBA";
	elseif($assoc=='Over55team') echo "Over 55's";
	elseif($assoc=='RSLteam') echo "RSL";
	elseif($assoc=='SouthernTeam') echo "SBSA";
	else echo "Error please contact the webmaster"; 
	?> 
    Clubs - contact details are as per the &quot;Club Directory&quot; (Public view) to view &quot;Club Management, Snooker Section or Invoice to&quot; go to: </td>
  </tr>
  <tr>
    <td align="center" class="red_text"><span class="red_bold">to view / edit club details or contacts, upload an image, go to the detail page <img src="../Admin_Images/detail.fw.png" alt="" width="20" height="20" title="Detail" /></span></td>
  </tr>
  <tr>
    <td align="center" class="red_text">Scroll to bottom of page to bulk email this list</td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
</table>
<table align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="center">
    <div class="dropdown">
      <button>View by Association</button>
      <div class="dropdown-content"> 
      	<a href="by_association.php?assoc=VBSAteam&redirect=by_assoc">VBSA</a> 	
        <a href="by_association.php?assoc=BBSAteam&redirect=by_assoc">Ballarat BSA</a> 
        <a href="by_association.php?assoc=BendBSA&redirect=by_assoc">BendigoBSA</a> 
        <a href="by_association.php?assoc=ChurchBill&redirect=by_assoc">Church Billiards</a> 
        <a href="by_association.php?assoc=CityClubTeam&redirect=by_assoc">City Clubs</a> 
        <a href="by_association.php?assoc=DVSAteam&redirect=by_assoc">DVSA</a> 
        <a href="by_association.php?assoc=MSBAteam&redirect=by_assoc">MSBA</a> 
        <a href="by_association.php?assoc=Over55team&redirect=by_assoc">Over 55's</a> 
        <a href="by_association.php?assoc=RSLteam&redirect=by_assoc">RSL</a> 
        <a href="by_association.php?assoc=SouthernTeam&redirect=by_assoc">Southern</a>
        <a href="by_association.php?assoc=WSBSA&redirect=by_assoc">WSBSA</a>
      </div>
    </div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<table border="1" align="center" class="page">
  <tr>
    <td align="center">Club ID</td>
    <td>Title</td>
      <td align="left" bgcolor="#CCCCCC">Contact</td>
      <td align="left" bgcolor="#CCCCCC">Phone</td>
      <td align="left" bgcolor="#CCCCCC">Email</td>
      <td align="center">VBSA</td>
      <td align="center">Ballarat BSA</td>
      <td align="center">Bendigo BSA</td>
      <td align="center">Church</td>
      <td align="center">City Clubs</td>
      <td align="center">DVSA</td>
      <td align="center">MSBA</td>
      <td align="center">Over 55</td>
      <td align="center">RSL</td>
      <td align="center">Southern</td>
      <td align="center">WSBSA</td>
      <td align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_AllClubs['ClubNumber']; ?></td>
      <td><?php echo $row_AllClubs['ClubTitle']; ?></td>
        <td align="left" bgcolor="#CCCCCC"><?php if(!isset($row_AllClubs['ClubContact'])) echo "na"; else echo $row_AllClubs['ClubContact']; ?></td>
      <td align="left" bgcolor="#CCCCCC">
      <?php if(!isset($row_AllClubs['ClubPhone1'])) echo "NA"; else { ?>
      <a href="tel:<?php echo $row_AllClubs['ClubPhone1']; ?>"><?php echo $row_AllClubs['ClubPhone1']; ?></a>
      <?php } ?>
      </td>
      <td align="left" bgcolor="#CCCCCC">
      <?php if(!isset($row_AllClubs['ClubEmail'])) echo "na"; else { ?>
      <a href="mailto:<?php echo $row_AllClubs['ClubEmail']; ?>"><?php echo $row_AllClubs['ClubEmail']; ?></a>
      <?php } ?>
      </td>
          <td align="center"><?php if($row_AllClubs['VBSAteam']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "";?></td>
          <td align="center"><?php if($row_AllClubs['BBSAteam']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "";?></td>
          <td align="center"><?php if($row_AllClubs['BendBSA']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "";?></td>
          <td align="center"><?php if($row_AllClubs['ChurchBill']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "";?></td>
          <td align="center"><?php if($row_AllClubs['CityClubTeam']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "";?></td>
          <td align="center"><?php if($row_AllClubs['DVSAteam']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "";?></td>
          <td align="center"><?php if($row_AllClubs['MSBAteam']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "";?></td>
          <td align="center"><?php if($row_AllClubs['Over55team']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "";?></td>
          <td align="center"><?php if($row_AllClubs['RSLteam']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "";?></td>
          <td align="center"><?php if($row_AllClubs['SouthernTeam']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "";?></td>
          <td align="center"><?php if($row_AllClubs['WSBSA']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "";?></td>
          <td align="center"><a href="user_files/clubs_detail.php?club_id=<?php echo $row_AllClubs['ClubNumber']; ?>&assoc=<?php echo $assoc; ?>&redirect=<?php echo $redirect; ?>"><img src="../Admin_Images/detail.fw.png" height="20" title="Detail" /></a></td>
    </tr>
    <?php } while ($row_AllClubs = mysql_fetch_assoc($AllClubs)); ?>
</table>
<p>&nbsp;</p>
<form action="" method="post" name="editor_form" id="editor_form">
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="4" class="red_text">NOTE: All listings that have an email address will be included in the bulk email</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="134" class="page">&nbsp;</td>
    <td width="551" align="right" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td>Would you like to send an attachment?</td>
    <td>&nbsp;</td>
    <td class="greenbg"><a href="user_files/upload_attach.php?assoc=<?php echo $assoc ?>">Please upload it now</a></td>
    <td align="right" class="greenbg"><a href="help_Bulk_email.pdf">Bulk Email help</a></td>
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
    <td colspan="2">3. From the &quot;Recordset fields&quot; select &quot;ClubEmail&quot;. Click the <img src="php_mail_merge/dynamic_e.gif" alt="1" width="17" height="17" /> button and it will add this field into the &quot;To&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">4. Tick the box for &quot;Plain Text&quot; alongside &quot;Send as&quot; </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">5. Type in the subject and your message in the appropriate fields and click &quot;Send&quot;.</td>
  </tr>
</table>
<br />
<?php $myRecordset=$AllClubs; $myTotalRecords=$totalRows_AllClubs; ?>
<table width="960" border="0" align="center" cellpadding="3" cellspacing="0" id="filters">
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
<script language="javascript" src="php_mail_merge/php_mail_merge.js" type="text/javascript">
</script>
<script language="javascript" type="text/javascript">initPMM();<?php if (isset($_POST["SendAs"]) && !isset($_POST['reset_editor'])){?>flipMessageFormat(document.getElementById('SendAs'));<?php }?></script>
</body>
</html>
<?php
mysql_free_result($AllClubs);

?>