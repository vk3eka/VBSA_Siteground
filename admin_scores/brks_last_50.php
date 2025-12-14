<?php require_once('../Connections/connvbsa.php'); ?>
<?php include('../security_header.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

$page = "../brks_last_50.php";
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
$query_brks_50 = "SELECT breaks.member_ID_brks, members.FirstName, members.LastName, breaks.grade, breaks.Break_ID, breaks.brk, members.MemberID, breaks.recvd, breaks.brk_type, breaks.season, breaks.finals_brk, brk_team_id FROM breaks, members WHERE breaks.member_ID_brks=members.MemberID AND YEAR(recvd)=YEAR( CURDATE( ) ) ORDER BY breaks.recvd DESC, breaks.Break_ID  DESC LIMIT 50";
$brks_50 = mysql_query($query_brks_50, $connvbsa) or die(mysql_error());
$row_brks_50 = mysql_fetch_assoc($brks_50);
$totalRows_brks_50 = mysql_num_rows($brks_50);
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
  
  <table width="800" border="0" align="center">
    <tr>
      <td colspan="2" align="center" class="red_bold">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" class="red_bold">Last 50 recorded breaks in <?php echo date("Y"); ?> (<span class="greenbg">At the start of the year no breaks will show)</span></td>
      <td align="center" class="red_bold"><span class="greenbg"><span class="page">
        <input type="button" value="Return to previous page" onclick="history.go(-1)"/>
      </td>
    </tr>
    <tr>
      <td  align="center" class="greenbg">&nbsp;</td>
      <td align="center" class="greenbg">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" class="greenbg"><a href="../admin_scores/AA_scores_index_grades.php? season=S1">Return to S1 scores</a></td>
      <td align="center" class="greenbg"><a href="../admin_scores/AA_scores_index_grades.php? season=S2">Return to S2 scores</a></td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="greenbg">&nbsp;</td>
    </tr>
    </table>
     <table width="800" border="0" align="center">
    <tr>
      <td align="center" class="greenbg">&nbsp;</td>
      <td align="center" class="greenbg">&nbsp;</td>
      <td align="center" class="greenbg">&nbsp;</td>
      <td align="center" class="greenbg">&nbsp;</td>
      <td align="center" class="greenbg">&nbsp;</td>
    </tr>
  </table>
  <table width="775" border="1" align="center" class="page">
    <tr>
      <td>Member ID</td>
      <td align="center">Break ID</td>
      <td align="center">Break</td>
      <td>First Name</td>
      <td>Last Name</td>
      <td align="center">Grade</td>
      <td>inserted on</td>
      <td align="center">Finals Break?</td>
      <td align="center">Team ID</td>
      <td align="center">Season</td>
      <td>Type</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php  if($totalRows_brks_50>0) do { ?>
      <tr>
        <td><?php echo $row_brks_50['member_ID_brks']; ?></td>
        <td align="center"><?php echo $row_brks_50['Break_ID']; ?></td>
        <td align="center"><?php echo $row_brks_50['brk']; ?></td>
        <td><?php echo $row_brks_50['FirstName']; ?></td>
        <td><?php echo $row_brks_50['LastName']; ?></td>
        <td align="center"><?php echo $row_brks_50['grade']; ?></td>
        <td><?php echo $row_brks_50['recvd']; ?></td>
        <td align="center"><?php echo $row_brks_50['finals_brk']; ?></td>
        <td align="center"><?php echo $row_brks_50['brk_team_id']; ?></td>
        <td align="center"><?php echo $row_brks_50['season']; ?></td>
        <td nowrap="nowrap"><?php echo $row_brks_50['brk_type']; ?></td> 
        <td nowrap="nowrap"><a href="user_files/break_edit.php?brk_id=<?php echo $row_brks_50['Break_ID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" title="Edit Break" /></a></td>
        <td align="center"><a href="user_files/break_delete_confirm.php?brk_id=<?php echo $row_brks_50['Break_ID']; ?>"><img src="../Admin_Images/Trash.fw.png" width="18" title="Delete Permanently" /></a></td>
      </tr>
    <?php } while ($row_brks_50 = mysql_fetch_assoc($brks_50)); else echo '<tr><td colspan="13"  class="text-center  italic">'."No Breaks Recorded".'</td></tr>';  ?>
</table>
  <input name="" type="hidden" value="" />
  <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_brks_50['Break_ID']; ?>" />

</body>
</html>
<?php

?>
