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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if(isset($_POST["r01s"])) {
	for($i=0; $i < count($_POST["r01s"]); $i++) {
		$updateSQL = sprintf("UPDATE scrs
	    SET r01s = %s, 
		r02s = %s,
		r03s = %s,
		r04s = %s,
		r05s = %s,
		r06s = %s,
		r07s = %s,
		r08s = %s,
		r09s = %s,
		r10s = %s,
		r11s = %s,
		r12s = %s,
		r13s = %s,
		r14s = %s,
		r15s = %s,
		r16s = %s,
		r17s = %s,
		r18s = %s,
		r01pos = %s,
		r02pos = %s,
		r03pos = %s,
		r04pos = %s,
		r05pos = %s,
		r06pos = %s,
		r07pos = %s,
		r08pos = %s,
		r09pos = %s,
		r10pos = %s,
		r11pos = %s,
		r12pos = %s,
		r13pos = %s,
		r14pos = %s,
		r15pos = %s,
		r16pos = %s,
		r17pos = %s,
		r18pos = %s
		WHERE scrsID = %s",
		GetSQLValueString($_POST["r01s"][$i], "int"),
		GetSQLValueString($_POST["r02s"][$i], "int"),
		GetSQLValueString($_POST["r03s"][$i], "int"),
		GetSQLValueString($_POST["r04s"][$i], "int"),
		GetSQLValueString($_POST["r05s"][$i], "int"),
		GetSQLValueString($_POST["r06s"][$i], "int"),
		GetSQLValueString($_POST["r07s"][$i], "int"),
		GetSQLValueString($_POST["r08s"][$i], "int"),
		GetSQLValueString($_POST["r09s"][$i], "int"),
		GetSQLValueString($_POST["r10s"][$i], "int"),
		GetSQLValueString($_POST["r11s"][$i], "int"),
		GetSQLValueString($_POST["r12s"][$i], "int"),
		GetSQLValueString($_POST["r13s"][$i], "int"),
		GetSQLValueString($_POST["r14s"][$i], "int"),
		GetSQLValueString($_POST["r15s"][$i], "int"),
		GetSQLValueString($_POST["r16s"][$i], "int"),
		GetSQLValueString($_POST["r17s"][$i], "int"),
		GetSQLValueString($_POST["r18s"][$i], "int"),
		GetSQLValueString($_POST["r01pos"][$i], "int"),
		GetSQLValueString($_POST["r02pos"][$i], "int"),
		GetSQLValueString($_POST["r03pos"][$i], "int"),
		GetSQLValueString($_POST["r04pos"][$i], "int"),
		GetSQLValueString($_POST["r05pos"][$i], "int"),
		GetSQLValueString($_POST["r06pos"][$i], "int"),
		GetSQLValueString($_POST["r07pos"][$i], "int"),
		GetSQLValueString($_POST["r08pos"][$i], "int"),
		GetSQLValueString($_POST["r09pos"][$i], "int"),
		GetSQLValueString($_POST["r10pos"][$i], "int"),
		GetSQLValueString($_POST["r11pos"][$i], "int"),
		GetSQLValueString($_POST["r12pos"][$i], "int"),
		GetSQLValueString($_POST["r13pos"][$i], "int"),
		GetSQLValueString($_POST["r14pos"][$i], "int"),
		GetSQLValueString($_POST["r15pos"][$i], "int"),
		GetSQLValueString($_POST["r16pos"][$i], "int"),
		GetSQLValueString($_POST["r17pos"][$i], "int"),	
		GetSQLValueString($_POST["r18pos"][$i], "int"),
		GetSQLValueString($_POST["id"][$i], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  //echo("<br>Set Rounds " . $updateSQL . "<br>");
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../scores_ladders.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
	if(isset($_POST["active"])) {
		$active = implode(",",$_POST["active"]);
		$updateSQL = sprintf("UPDATE scrs 
		SET active = 1
		WHERE scrsID IN (%s)", $active);
		mysql_select_db($database_connvbsa, $connvbsa);
  		$Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
 		 echo "Error please contact the webmaster";
 		 exit;				
	}	
}
?>
<?php
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}


if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}


if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Team_Score_Edit = "SELECT scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, r01s, r02s, r03s, r04s, r05s, r06s, r07s, r08s, r09s, r10s, r11s, r12s, r13s, r14s, r15s, r16s, r17s, r18s, r01pos, r02pos, r03pos, r04pos, r05pos, r06pos, r07pos, r08pos, r09pos, r10pos, r11pos, r12pos, r13pos, r14pos, r15pos, r16pos, r17pos, r18pos, members.MemberID, members.FirstName, members.LastName, Team_entries.team_id, Team_entries.team_name, Team_entries.team_grade FROM scrs, members, Team_entries WHERE members.MemberID=scrs.MemberID AND Team_entries.team_id=scrs.team_id AND Team_entries.team_id='$team_id' ORDER BY members.FirstName";
//echo("<br>Select Team Score - " . $query_Team_Score_Edit . "<br>");
$Team_Score_Edit = mysql_query($query_Team_Score_Edit, $connvbsa) or die(mysql_error());
$row_Team_Score_Edit = mysql_fetch_assoc($Team_Score_Edit);
$totalRows_Team_Score_Edit = mysql_num_rows($Team_Score_Edit);
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
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">

<!-- added to tab vertically 30/08/2023 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function()
{
  $.fn.fixVerticalTabindex = function (selector) {
    if (typeof selector == 'undefined') {
      selector = '.reset-tabindex';
    }
    var tabindex = 1;
    $(selector).each(function(i, tbl) {
      $(tbl).find('tr').first().find('td').each(function(clmn, el) {
        $(tbl).find('tr td:nth-child(' + (clmn + 1) + ') input').each(function(j, input) {
          //$(input).attr('placeholder', tabindex);
          $(input).attr('tabindex', tabindex++);
        });
      });
    });
  }

  $(function() {
    $('#btn-fix').click(function() {
      fixVerticalTabindex('.reset-tabindex');
    });
  });

  window.onload = function() 
  {
    $.fn.fixVerticalTabindex('.reset-tabindex');
  }

});

</script>
<!-- end vertical tab -->

</head>
<body>
<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table width="800" border="0" align="center">
  <tr>
    <td align="left" class="red_bold">Edit all players</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<form name="form1" id="form1" method="post" >
<table width="1000" border="1" align="center" class="reset-tabindex">
<?php 
$counter=1;
do { ?>
    <!--<table width="1000" border="1" align="center" class="reset-tabindex">-->
      <tr>
        <td width="179"> ID : <?php echo $row_Team_Score_Edit['MemberID']; ?> Record : <?php echo($counter);?></td>
        <td width="92">Scrs : <?php echo $row_Team_Score_Edit['scrsID']; ?></td>
        <?php
        for($i = 0; $i < 18; $i++)
        {
          if($i > 8)
          {
              $rnd_no = ($i+1);
          }
          else
          {
              $rnd_no = '0' . ($i+1);
          }
          echo("<td width='40' align='center'>" . ($i+1) . "</td>");
        }
        ?>
      </tr>
      <tr>
        <td>Team Name : <?php echo $row_Team_Score_Edit['team_name']; ?></td>
        <td align="right">Score</td>
        <?php
        for($i = 0; $i < 18; $i++)
        {
          if($i > 8)
          {
              $rnd_no = ($i+1);
          }
          else
          {
              $rnd_no = '0' . ($i+1);
          }
          echo("<td width='40'><input name='r" . $rnd_no . "s[]' type='text' id='r" . $rnd_no . "s" . $counter . "'");
          //echo(" tabindex=" . ($i+1) . ($counter+1) . " ");
          //echo(" value='" . $x . "' size='4'/></td>");
          echo(" value='" . $row_Team_Score_Edit['r' . $rnd_no . 's'] . "' size='2'/></td>");
        }
        ?>
      </tr>
      <tr>
        <td><?php echo $row_Team_Score_Edit['FirstName']; ?> <?php echo $row_Team_Score_Edit['LastName']; ?></td>
        <td align="right">Position</td>
        <?php
        for($i = 0; $i < 18; $i++)
        {
          if($i > 8)
          {
              $rnd_no = ($i+1);
          }
          else
          {
              $rnd_no = '0' . ($i+1);
          }
          echo("<td width='40'><input name='r" . $rnd_no . "pos[]' type='text' id='r" . $rnd_no . "pos" . $counter . "'");
          //echo(" tabindex=" . ($i+1) . ($counter+1) . " ");
          echo(" value='" . $row_Team_Score_Edit['r' . $rnd_no . 'pos'] . "' size='2'/></td>");
        }
        $counter++;
        ?>
      </tr>
      <tr><td colspan=20>&nbsp;</td></tr>
    <!--</table>-->
    <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_Team_Score_Edit['team_id']; ?>" />
    <input type="hidden" name="id[]" value="<?php echo $row_Team_Score_Edit['scrsID']; ?>" />
    <br />
    <?php } while ($row_Team_Score_Edit = mysql_fetch_assoc($Team_Score_Edit)); ?>
    </table>
    <br>
  <div align="center"><input name="submit" value="Update all" type="submit" /></div>
</form>
</center>
</body>
</html>



