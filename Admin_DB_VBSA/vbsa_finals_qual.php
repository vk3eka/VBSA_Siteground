<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$page = "../Admin_DB_VBSA/vbsa_finals_qual.php?season=$season";
$_SESSION['page'] = $page;

$MM_authorizedUsers = "Webmaster,Treasurer,Administrator,Secretary,Scores";
$MM_donotCheckaccess = "false";

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
    if (($strUsers == "") && false) { 
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
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php require_once('../Connections/connvbsa.php'); ?><?php
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

$query_qual_play = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, ReceiveEmail, Email, Team_entries.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size, byes_to_date FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE scr_season = '$season' AND Team_entries.team_grade != '' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 ORDER BY team_grade, team_id, FirstName, LastName";
//echo($query_qual_play . "<br>");
$qual_play = mysql_query($query_qual_play, $connvbsa) or die(mysql_error());
$row_qual_play = mysql_fetch_assoc($qual_play);
$totalRows_qual_play = mysql_num_rows($qual_play);


$query_memb = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, Team_entries.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size, byes_to_date FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE scr_season = '$season' AND Team_entries.team_grade != '' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 AND (ReceiveEmail=1 AND Email != '') ORDER BY team_grade, team_id, FirstName, LastName";
$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
//echo($query_memb . "<br>");
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
    <td class="red_bold"><?php echo date("Y") ?></span> - All Qualified for finals players in season <?php echo $season; ?>, scroll to bottom of Page for bulk email</td>
    <td>&nbsp;</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <!--<tr>
    <td>Total Players: <?php echo $totalRows_qual_play ?></td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>-->
  <tr>
    <td colspan="3" class="red_bold"><p>This list is for information only or bulk email.<br/>If you wish to copy to an excel file, select all cells and copy. <br/>Remember you are copying personal contact info, please treat this information with discression</p></td>
  </tr>
</table>
<?php if($totalRows_qual_play > 0 )  { ?>
<table border="1" align="center" cellpadding="5" class="page">
  <tr>
    <td>ID</td>
    <td>First Name</td>
    <td>Last Name</td>
    <td>Mobile</td>
    <td>Email</td>
    <td>Captain</td>
    <td>Team ID</td>
    <td>Grade</td>
    <td>Club</td>
    <td>Team Name</td>
    <td>Played</td>
    <<td>Email OK</td>
  </tr>
  <?php 
  	mysql_data_seek($qual_play, 0);
    $x = 0;
  	while ($row_qual_play = mysql_fetch_assoc($qual_play))
  	{ 

      // get number of byes

      $sql_byes = "Select 
        SUM((fix1home = 'Bye') +
        (fix2home = 'Bye') +
        (fix3home = 'Bye') +
        (fix4home = 'Bye') +
        (fix5home = 'Bye') +
        (fix6home = 'Bye') +
        (fix7home = 'Bye') +
        (fix1away = 'Bye') +
        (fix2away = 'Bye') +
        (fix3away = 'Bye') +
        (fix4away = 'Bye') +
        (fix5away = 'Bye') +
        (fix6away = 'Bye') +
        (fix7away = 'Bye'))
        as byes FROM vbsa3364_vbsa2.tbl_fixtures Where season = '$season' and year = " . date('Y') . " and team_grade = '" . $row_qual_play['team_grade'] . "'";
        //echo($sql_byes . "<br>");
      $byes = mysql_query($sql_byes, $connvbsa) or die(mysql_error());
      $row_byes = mysql_fetch_assoc($byes);
      if($row_byes['byes'] != '')
      {
        $total_byes = $row_byes['byes'];
      }
      else
      {
        $total_byes = 0;
      }
      
      //echo("Total byes " . $total_byes . "<br>");

      $sql_teams = "Select * FROM vbsa3364_vbsa2.Team_entries Where team_season = '$season' and team_cal_year = " . date('Y') . " and team_grade = '" . $row_qual_play['team_grade'] . "' and team_name != 'Bye'";
      $teams = mysql_query($sql_teams, $connvbsa) or die(mysql_error());
      //$row_teams = mysql_fetch_assoc($teams);
      $no_of_teams = mysql_num_rows($teams);
      //echo("Number of teams " . $no_of_teams . "<br>");

      if($total_byes == 0)
      {
        $no_of_byes = 0;
      }
      else
      {
        $no_of_byes = ($total_byes/$no_of_teams);
      }
      
      //echo("Number of byes " . $no_of_byes . "<br>");


      // get last round played

      $query_total_rounds = "Select no_of_rounds, grade, season, fix_cal_year, type, grade FROM Team_grade WHERE season = '$season' and fix_cal_year = " . date('Y') . " and current = 'Yes' and grade = '" . $row_qual_play['team_grade'] . "'";
      //echo($query_total_rounds . "<br>");
      $total_rounds = mysql_query($query_total_rounds, $connvbsa) or die(mysql_error());
      $row_total_rounds = mysql_fetch_assoc($total_rounds);
      $type = $row_total_rounds['type'];
      $total_rounds_available = ($row_total_rounds['no_of_rounds']);
      if($type == 'Snooker')
      {
        $total_rounds_available = ($total_rounds_available-2); // two finals
      }
      else if($type == 'Billiards')
      {
        $total_rounds_available = ($total_rounds_available-3); // three finals
      }
     
      if((($row_qual_play['count_played']) >= ceil(($total_rounds_available-$no_of_byes)*0.5)) && ($row_qual_play['count_played'] > 0))
	  	{
        $x++;
	  	?>
	    <tr>
	      <td><?php echo $row_qual_play['MemberID']; ?></td>
	      <td><?php echo $row_qual_play['FirstName']; ?></td>
	      <td><?php echo $row_qual_play['LastName']; ?></td>
	      <td><a href="tel:<?php echo $row_qual_play['MobilePhone']; ?>"><?php echo $row_qual_play['MobilePhone']; ?></a></td>
	      <td><a href="mailto:<?php echo $row_qual_play['Email']; ?>" target="_blank"><?php echo $row_qual_play['Email']; ?></a></td>
	      <td><?php echo $row_qual_play['captain_scrs']; ?></td>
	      <td><?php echo $row_qual_play['team_id']; ?></td>
	      <td><?php echo $row_qual_play['team_grade']; ?></td>
	      <td><?php echo $row_qual_play['team_club']; ?></td>
	      <td><?php echo $row_qual_play['team_name']; ?></td>
	      <td><?php echo $row_qual_play['count_played']; ?></td>
	      <td><?php $b = $row_qual_play['ReceiveEmail'] == 1 ? "Yes" :  "No"; echo $b; ?></td>
	    </tr>
	    <?php 
	  	}
	  }
  ?>
</table>
<?php } else { ?>
<table align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="Italic">Nothing listed</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?php } ?>
<form action="" method="post" name="editor_form" id="editor_form">
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <!--<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="134" class="page">&nbsp;</td>
    <td width="551" align="right" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">Total Players: <?php echo $x ?></td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>-->
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
<br />
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
</center>
</body>
</html>
<?php

?>