<?php 
function tfm_cleanOrderBy($theValue, $defaultSort) {
	if (preg_match("/^[\w,]{1,50}\s+(asc|desc)\s*$/i",$theValue, $matches)) {
		return $matches[0];
	}
	return $defaultSort;
}
?>
<?php require_once('../Connections/connvbsa.php'); ?>
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
$tfm_orderby =(!isset($_GET["tfm_orderby"]))?"team_grade, team_id":$_GET["tfm_orderby"];
$tfm_order =(!isset($_GET["tfm_order"]))?"ASC":$_GET["tfm_order"];
$sql_orderby = $tfm_orderby." ".$tfm_order;
$sql_orderby = tfm_cleanOrderBy($sql_orderby, "team_grade, team_id");
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

//TOMLR Special List Recordset
// Defining List Recordset variable
$sqlorderby_curr_play = "team_grade, team_id";
if (isset($sql_orderby)) {
  $sqlorderby_curr_play = $sql_orderby;
}
mysql_select_db($database_connvbsa, $connvbsa);

$query_curr_play = "SELECT scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, scrs.team_grade, scrs.team_id, Team_entries.team_name, team_club, game_type, count_played, captain_scrs, scr_season, memb_by 
FROM scrs 
LEFT JOIN members ON members.MemberID = scrs.MemberID
LEFT JOIN Team_entries ON Team_entries.team_id = scrs.team_id
WHERE count_played>0 
AND current_year_scrs = YEAR(CURDATE( )) 
AND (scrs.MemberID != 1 
AND scrs.MemberID != 100 
AND scrs.MemberID != 1000) 
ORDER BY {$sqlorderby_curr_play}";
$curr_play = mysql_query($query_curr_play, $connvbsa) or die(mysql_error());
$row_curr_play = mysql_fetch_assoc($curr_play);
$totalRows_curr_play = mysql_num_rows($curr_play);
//End TOMLR Special List Recordset//TOMLR Special List Recordset
mysql_select_db($database_connvbsa, $connvbsa);

$query_curr_play = "SELECT scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, scrs.team_grade, scrs.team_id, Team_entries.team_name, team_club, game_type, count_played, captain_scrs, scr_season, memb_by 
FROM scrs 
LEFT JOIN members ON members.MemberID = scrs.MemberID
LEFT JOIN Team_entries ON Team_entries.team_id = scrs.team_id
WHERE count_played>0 
AND current_year_scrs = YEAR(CURDATE( )) 
AND (scrs.MemberID != 1 
AND scrs.MemberID != 100 
AND scrs.MemberID != 1000) 
ORDER BY {$sqlorderby_curr_play}";
$curr_play = mysql_query($query_curr_play, $connvbsa) or die(mysql_error());
$row_curr_play = mysql_fetch_assoc($curr_play);
$totalRows_curr_play = mysql_num_rows($curr_play);
//End TOMLR Special List Recordset
?>
<?php
//sort column headers for curr_play
$tfm_saveParams = explode(",","");
$tfm_keepParams = "";
if($tfm_order == "ASC") {
	$tfm_order = "DESC";
}else{
	$tfm_order = "ASC";
};

foreach ($tfm_saveParams as $key => $val) {
//while (list($key,$val) = each($tfm_saveParams)) {
	if(isset($_GET[$val]))$tfm_keepParams .= ($val)."=".urlencode($_GET[$val])."&";	
	if(isset($_POST[$val]))$tfm_keepParams .= ($val)."=".urlencode($_POST[$val])."&";
}
$tfm_orderbyURL = $_SERVER["PHP_SELF"]."?".$tfm_keepParams."tfm_order=".$tfm_order."&tfm_orderby=";
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

    <table align="center">
      <tr>
        <td colspan="2" align="center" class="greenbg">&nbsp;</td>
      </tr>
      <tr>
        <td class="red_bold">All players playing in the current year (players may appear multiple times if playing in more than one grade) </td>
        <td class="greenbg" nowrap="nowrap"><a href="AA_scores_index_select_season.php">Return to Scores Index</a></td>
      </tr>
      <tr>
        <td align="center">Total players: <?php echo $totalRows_curr_play ?></td>
        <td align="right">&nbsp;</td>
      </tr>
    </table>
<table align="center" cellpadding="2" cellspacing="2" class="page">
      <tr>
        <td align="center"><a href="<?Php echo ($tfm_orderbyURL); ?>scrsID">scrs ID</a></td>
        <td align="center"><a href="<?Php echo ($tfm_orderbyURL); ?>MemberID">Member ID</a></td>
        <td align="left"><a href="<?Php echo ($tfm_orderbyURL); ?>FirstName">First Name</a></td>
        <td align="left"><a href="<?Php echo ($tfm_orderbyURL); ?>LastName">Last Name</a></td>
        <td align="left">MobilePhone</td>
        <td align="left">Email</td>
        <td align="left"><a href="<?Php echo ($tfm_orderbyURL); ?>team_grade">Grade</a></td>
        <td align="center"><a href="<?Php echo ($tfm_orderbyURL); ?>team_id">Team ID</a></td>
        <td align="left" nowrap="nowrap"><a href="<?Php echo ($tfm_orderbyURL); ?>team_name">Team Name</a></td>
        <td align="left" nowrap="nowrap"><a href="<?Php echo ($tfm_orderbyURL); ?>team_club">Team Club</a></td>
        <td align="center"><a href="<?Php echo ($tfm_orderbyURL); ?>captain_scrs">Captain?</a></td>
        <td align="center"><a href="<?Php echo ($tfm_orderbyURL); ?>count_played">Matches Played</a></td>
        <td align="center"><a href="<?Php echo ($tfm_orderbyURL); ?>game_type">Game Type</a></td>
        <td align="center"><a href="<?Php echo ($tfm_orderbyURL); ?>scr_season">Season</a></td>
      </tr>
      <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_curr_play['scrsID']; ?></td>
          <td align="center"><?php echo $row_curr_play['MemberID']; ?></td>
          <td align="left"><?php echo $row_curr_play['FirstName']; ?></td>
          <td align="left"><?php echo $row_curr_play['LastName']; ?></td>
          <td align="left"><a href="tel:<?php echo $row_curr_play['MobilePhone']; ?>" ><?php echo $row_curr_play['MobilePhone']; ?></a></td>
          <td align="left"><a href="mailto:<?php echo $row_curr_play['Email']; ?>" target="_blank"><?php echo $row_curr_play['Email']; ?></a></td>
          <td align="left"><?php echo $row_curr_play['team_grade']; ?></td>
          <td align="center"><?php echo $row_curr_play['team_id']; ?></td>
          <td align="left" nowrap="nowrap"><?php echo $row_curr_play['team_name']; ?></td>
          <td align="left" nowrap="nowrap"><?php echo $row_curr_play['team_club']; ?></td>
          <td align="center"><?php echo $row_curr_play['captain_scrs']; ?></td>
          <td align="center"><?php echo $row_curr_play['count_played']; ?></td>
          <td align="center"><?php echo $row_curr_play['game_type']; ?></td>
          <td align="center"><?php echo $row_curr_play['scr_season']; ?></td>
        </tr>
        <?php } while ($row_curr_play = mysql_fetch_assoc($curr_play)); ?>
    </table>
</center>
</center>
</body>
</html>
<?php

?>