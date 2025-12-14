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

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_chk_rds = "SELECT SUM(r01s), SUM(r02s), SUM(r03s), SUM(r04s), SUM(r05s), SUM(r06s), SUM(r07s), SUM(r08s), SUM(r09s), SUM(r10s), SUM(r11s), SUM(r12s), SUM(r13s), SUM(r14s), SUM(r15s), SUM(r16s), SUM(r17s), SUM(r18s) FROM `scrs` WHERE team_grade='$grade' AND current_year_scrs = YEAR( CURDATE( ) )";
$chk_rds = mysql_query($query_chk_rds, $connvbsa) or die(mysql_error());
$row_chk_rds = mysql_fetch_assoc($chk_rds);
$totalRows_chk_rds = mysql_num_rows($chk_rds);

mysql_select_db($database_connvbsa, $connvbsa);
$query_count_teams = "SELECT COUNT(team_id), team_cal_year FROM Team_entries WHERE team_grade ='$grade' AND team_cal_year = YEAR( CURDATE( ) ) AND `include_draw`='Yes' AND `team_name`!='Bye'";
$count_teams = mysql_query($query_count_teams, $connvbsa) or die(mysql_error());
$row_count_teams = mysql_fetch_assoc($count_teams);
$totalRows_count_teams = mysql_num_rows($count_teams);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Adjust_notes = "SELECT team_id, team_name, team_grade, team_club, scr_adj_rd, scr_adjust, adj_comment, current_year_team FROM Team_entries WHERE team_grade='$grade' AND scr_adjust != 0 AND YEAR( current_year_team ) = YEAR( CURDATE( ) ) ";
$Adjust_notes = mysql_query($query_Adjust_notes, $connvbsa) or die(mysql_error());
$row_Adjust_notes = mysql_fetch_assoc($Adjust_notes);
$totalRows_Adjust_notes = mysql_num_rows($Adjust_notes);



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

  <table align="center">
    <tr>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" class="red_bold">Check round total for - Grade code: <?php echo $grade ?> in <?php echo $row_count_teams['team_cal_year']; ?></td>
      <td align="left" class="red_bold">&nbsp;</td>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">Participating teams = 
	  <?php echo $row_count_teams['COUNT(team_id)']; 
      $number = $row_count_teams['COUNT(team_id)'];
      if ($number % 2 == 0) {
      echo " "; 
      } 
	  else echo " + Bye";
	  ?>
      </td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
<table border="1" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="center" nowrap="nowrap">Rd 01</td>
    <td align="center" nowrap="nowrap">Rd 02</td>
    <td align="center" nowrap="nowrap">Rd 03</td>
    <td align="center" nowrap="nowrap">Rd 04</td>
    <td align="center" nowrap="nowrap">Rd 05</td>
    <td align="center" nowrap="nowrap">Rd 06</td>
    <td align="center" nowrap="nowrap">Rd 07</td>
    <td align="center" nowrap="nowrap">Rd 08</td>
    <td align="center" nowrap="nowrap">Rd 09</td>
    <td align="center" nowrap="nowrap">Rd 10</td>
    <td align="center" nowrap="nowrap">Rd 11</td>
    <td align="center" nowrap="nowrap">Rd 12</td>
    <td align="center" nowrap="nowrap">Rd 13</td>
    <td align="center" nowrap="nowrap">Rd 14</td>
    <td align="center" nowrap="nowrap">Rd 15</td>
    <td align="center" nowrap="nowrap">Rd 16</td>
    <td align="center" nowrap="nowrap">Rd 17</td>
    <td align="center" nowrap="nowrap">Rd 18</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_chk_rds['SUM(r01s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r02s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r03s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r04s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r05s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r06s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r07s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r08s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r09s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r10s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r11s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r12s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r13s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r14s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r15s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r16s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r17s)']; ?></td>
      <td align="center"><?php echo $row_chk_rds['SUM(r18s)']; ?></td>
    </tr>
    <?php } while ($row_chk_rds = mysql_fetch_assoc($chk_rds)); ?>
</table>
<table align="center">
  <tr>
    <td align="left" class="page">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="red_bold">Manual adjustments to this grade</td>
  </tr>
  <tr>
    <td align="center"><?php if($totalRows_Adjust_notes == 0) echo "No manual adjustments to this grade"; ?>&nbsp;</td>
  </tr>
</table>

<?php if ($totalRows_Adjust_notes != 0) {  ?>
  <table width="1100" border="1" align="center">
    <tr>
      <td width="60" height="44">Team ID</td>
      <td width="162">Team Name</td>
      <td width="50">Club</td>
      <td width="50">Round</td>
      <td width="50">Pts</td>
      <td>Explanation</td>
    </tr>
    <?php do { ?>
      <tr>
        <td width="60"><?php echo $row_Adjust_notes['team_id']; ?></td>
        <td width="162"><?php echo $row_Adjust_notes['team_name']; ?></td>
        <td width="50"><?php echo $row_Adjust_notes['team_club']; ?></td>
        <td width="50"><?php echo $row_Adjust_notes['scr_adj_rd']; ?></td>
        <td width="50"><?php echo $row_Adjust_notes['scr_adjust']; ?></td>
        <td><?php echo $row_Adjust_notes['adj_comment']; ?></td>
      </tr>
      <?php } while ($row_Adjust_notes = mysql_fetch_assoc($Adjust_notes)); ?>
  </table>
  <?php } // Show if recordset empty ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php

?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>

<script type="text/javascript">

function doit(){     
	
	var tx = jQuery.noConflict();
        tx.ajax({
            url     : '<?PHP echo $editFormAction ?>',
            type    : tx('#form1').attr('method'),
            data    : tx('#form1').serialize(),
            success : function( data ) {
                        alert('Updated Succesfully!');
						location.reload(); 
                      },
            error   : function( xhr, err ) {
                        alert('Error');     
                      }
        }); 
        return false;
}

</script>
