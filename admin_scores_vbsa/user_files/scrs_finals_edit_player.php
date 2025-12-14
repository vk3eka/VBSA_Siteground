<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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

$MM_restrictGoTo = "../../page_error.php";
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
<?php require_once('../../Connections/connvbsa.php'); ?><?php
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE scrs SET EF1=%s, EF2=%s, SF1=%s, SF2=%s, GF=%s, EF1_pos=%s, EF2_pos=%s, SF1_pos=%s, SF2_pos=%s, GF_pos=%s WHERE scrsID=%s",
                       GetSQLValueString($_POST['EF1'], "int"),
                       GetSQLValueString($_POST['EF2'], "int"),
                       GetSQLValueString($_POST['SF1'], "int"),
                       GetSQLValueString($_POST['SF2'], "int"),
                       GetSQLValueString($_POST['GF'], "int"),
					             GetSQLValueString($_POST['EF1_pos'], "int"),
                       GetSQLValueString($_POST['EF2_pos'], "int"),
                       GetSQLValueString($_POST['SF1_pos'], "int"),
                       GetSQLValueString($_POST['SF2_pos'], "int"),
                       GetSQLValueString($_POST['GF_pos'], "int"),
                       GetSQLValueString($_POST['scrsID'], "int"));
  
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../scores_index_finals_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

$scrs_id = "-1";
if (isset($_GET['scrs_id'])) {
  $scrs_id = $_GET['scrs_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
//$query_Scrs_Edit = "SELECT scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, scr_season, scrs.SF1, scrs.SF2, scrs.GF, members.MemberID, members.FirstName, members.LastName, scrs.final_sub, scrs.captain_scrs, scrs.SF1_pos, scrs.SF2_pos, scrs.GF_pos FROM scrs, members WHERE scrs.MemberID=members.MemberID AND scrs.scrsID = '$scrs_id'";
$query_Scrs_Edit = "SELECT scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, scr_season, scrs.EF1, scrs.EF2, scrs.SF1, scrs.SF2, scrs.GF, members.MemberID, members.FirstName, members.LastName, scrs.final_sub, scrs.captain_scrs, scrs.EF1_pos, scrs.EF2_pos, scrs.SF1_pos, scrs.SF2_pos, scrs.GF_pos FROM scrs, members WHERE scrs.MemberID=members.MemberID AND scrs.scrsID = '$scrs_id'";
$Scrs_Edit = mysql_query($query_Scrs_Edit, $connvbsa) or die(mysql_error());
$row_Scrs_Edit = mysql_fetch_assoc($Scrs_Edit);
$totalRows_Scrs_Edit = mysql_num_rows($Scrs_Edit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
</head>

<body>
<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table width="800" border="0" align="center">
  <tr>
    <td align="left" class="red_bold">Edit Finals score for : Player ID - <?php echo $row_Scrs_Edit['MemberID']; ?></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
  <table width="800" border="0" align="center">
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"  class="red_bold"><?php echo $row_Scrs_Edit['FirstName']; ?> <?php echo $row_Scrs_Edit['LastName']; ?> in <?php echo $grade; ?> team ID <?php echo $row_Scrs_Edit['team_id'].", ".$comptype." ".$season; ?> </td>
    </tr>
    <tr>
      <td align="center">Scores ID: <?php echo $row_Scrs_Edit['scrsID']; ?></td>
    </tr>
    <tr>
      <td align="center" class="page">&nbsp;</td>
    </tr>
  </table>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table width="240" border="1" align="center">
      <tr>
        <td width="92">&nbsp;</td>
        <?php 
        if($comptype == 'Billiards')
        {
        ?>
          <td width="40" align="center">EF1</td>
          <td width="40" align="center">EF2</td>
        <?php
        }
        ?>
        <td width="40" align="center">SF1</td>
        <td width="40" align="center">SF2</td>
        <td width="40" align="center">GF</td>
      </tr>
      <tr>
        <td align="right">Score</td>
        <?php 
        if($comptype == 'Billiards')
        {
        ?>
        <td width="40" align="center"><input type="text" name="EF1" value="<?php echo $row_Scrs_Edit['EF1']; ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="EF2" value="<?php echo $row_Scrs_Edit['EF2']; ?>" size="3" /></td>
        <?php 
        }
        else
        {
        ?>
          <input type="hidden" name="EF1">
          <input type="hidden" name="EF2">
        <?php
        }
        ?>
        <td width="40" align="center"><input type="text" name="SF1" value="<?php echo $row_Scrs_Edit['SF1']; ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="SF2" value="<?php echo $row_Scrs_Edit['SF2']; ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="GF" value="<?php echo $row_Scrs_Edit['GF']; ?>" size="3" /></td>
      </tr>
      <tr>
        <td align="right">        Position</td>
        <?php 
        if($comptype == 'Billiards')
        {
        ?>
          <td width="40" align="center"><input type="text" name="EF1_pos" value="<?php echo $row_Scrs_Edit['EF1_pos']; ?>" size="3" /></td>
          <td width="40" align="center"><input type="text" name="EF2_pos" value="<?php echo $row_Scrs_Edit['EF2_pos']; ?>" size="3" /></td>
        <?php 
        }
        else
        {
        ?>
          <input type="hidden" name="EF1_pos">
          <input type="hidden" name="EF2_pos">
        <?php
        }
        ?>
        <td width="40" align="center"><input type="text" name="SF1_pos" value="<?php echo $row_Scrs_Edit['SF1_pos']; ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="SF2_pos" value="<?php echo $row_Scrs_Edit['SF2_pos']; ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="GF_pos" value="<?php echo $row_Scrs_Edit['GF_pos']; ?>" size="3" /></td>
      </tr>
    </table>
    <table align="center">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="submit" value="Update player" /></td>
      </tr>
    </table>
<input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="scrsID" value="<?php echo $row_Scrs_Edit['scrsID']; ?>" />
</form>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</center>
</body>
</html>
