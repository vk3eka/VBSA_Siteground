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

$editFormAction = "ajax/Ladder_All_team_edit_scores.php";
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
  		$Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
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
$colname_Team_Score_Edit = "-1";
if (isset($_GET['team_id'])) {
  $colname_Team_Score_Edit = $_GET['team_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Team_Score_Edit = sprintf("SELECT scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, r01s, r02s, r03s, r04s, r05s, r06s, r07s, r08s, r09s, r10s, r11s, r12s, r13s, r14s, r15s, r16s, r17s, r18s, r01pos, r02pos, r03pos, r04pos, r05pos, r06pos, r07pos, r08pos, r09pos, r10pos, r11pos, r12pos, r13pos, r14pos, r15pos, r16pos, r17pos, r18pos, members.MemberID, members.FirstName, members.LastName, Team_entries.team_id, Team_entries.team_name, Team_entries.team_grade FROM scrs, members, Team_entries WHERE members.MemberID=scrs.MemberID AND Team_entries.team_id=scrs.team_id AND Team_entries.team_id=%s ORDER BY members.FirstName", GetSQLValueString($colname_Team_Score_Edit, "int"));
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
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
</head>

<body>
</div>
<center>
<table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
</table>
<center>
  
  
  <table width="800" border="0">
    <tr>
      <td align="center" class="red_bold">Edit all players</td>
    </tr>
  </table>
  <form name="form1" id="form1" method="post" onsubmit="return doit()">
    
    <?php $counter=1;
  do { ?>
      <table width="1000" border="1">
        <tr>
          <td width="179"> ID : <?php echo $row_Team_Score_Edit['MemberID']; ?> Record : <?php echo($counter);?></td>
          <td width="92">Scrs : <?php echo $row_Team_Score_Edit['scrsID']; ?></td>
          <td width="40" align="center">1</td>
          <td width="40" align="center">2</td>
          <td width="40" align="center">3</td>
          <td width="40" align="center">4</td>
          <td width="40" align="center">5</td>
          <td width="40" align="center">6</td>
          <td width="40" align="center">7</td>
          <td width="40" align="center">8</td>
          <td width="40" align="center">9</td>
          <td width="40" align="center">10</td>
          <td width="40" align="center">11</td>
          <td width="40" align="center">12</td>
          <td width="40" align="center">13</td>
          <td width="40" align="center">14</td>
          <td width="40" align="center">15</td>
          <td width="40" align="center">16</td>
          <td width="40" align="center">17</td>
          <td width="40" align="center">18</td>
        </tr>
        <tr>
          <td>Team Name : <?php echo $row_Team_Score_Edit['team_name']; ?></td>
          <td align="right">Round</td>
          <td width="40"><input name="r01s[]" type="text" id="r01s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r01s']; ?>" size="2" /></td>
          <td width="40"><input name="r02s[]" type="text" id="r02s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r02s']; ?>" size="2" /></td>
          <td width="40"><input name="r03s[]" type="text" id="r03s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r03s']; ?>" size="2" /></td>
          <td width="40"><input name="r04s[]" type="text" id="r04s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r04s']; ?>" size="2" /></td>
          <td width="40"><input name="r05s[]" type="text" id="r05s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r05s']; ?>" size="2" /></td>
          <td width="40"><input name="r06s[]" type="text" id="r06s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r06s']; ?>" size="2" /></td>
          <td width="40"><input name="r07s[]" type="text" id="r07s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r07s']; ?>" size="2" /></td>
          <td width="40"><input name="r08s[]" type="text" id="r08s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r08s']; ?>" size="2" /></td>
          <td width="40"><input name="r09s[]" type="text" id="r09s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r09s']; ?>" size="2" /></td>
          <td width="40"><input name="r10s[]" type="text" id="r10s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r10s']; ?>" size="2" /></td>
          <td width="40"><input name="r11s[]" type="text" id="r11s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r11s']; ?>" size="2" /></td>
          <td width="40"><input name="r12s[]" type="text" id="r12s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r12s']; ?>" size="2" /></td>
          <td width="40"><input name="r13s[]" type="text" id="r13s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r13s']; ?>" size="2" /></td>
          <td width="40"><input name="r14s[]" type="text" id="r14s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r14s']; ?>" size="2" /></td>
          <td width="40"><input name="r15s[]" type="text" id="r15s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r15s']; ?>" size="2" /></td>
          <td width="40"><input name="r16s[]" type="text" id="r16s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r16s']; ?>" size="2" /></td>
          <td width="40"><input name="r17s[]" type="text" id="r17s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r17s']; ?>" size="2" /></td>
          <td width="40"><input name="r18s[]" type="text" id="r18s<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r18s']; ?>" size="2" /></td>
        </tr>
        <tr>
          <td><?php echo $row_Team_Score_Edit['FirstName']; ?> <?php echo $row_Team_Score_Edit['LastName']; ?></td>
          <td align="right">          Position</td>
          <td width="40"><input name="r01pos[]" type="text" id="r01pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r01pos']; ?>" size="2" /></td>
          <td width="40"><input name="r02pos[]" type="text" id="r02pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r02pos']; ?>" size="2" /></td>
          <td width="40"><input name="r03pos[]" type="text" id="r03pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r03pos']; ?>" size="2" /></td>
          <td width="40"><input name="r04pos[]" type="text" id="r04pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r04pos']; ?>" size="2" /></td>
          <td width="40"><input name="r05pos[]" type="text" id="r05pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r05pos']; ?>" size="2" /></td>
          <td width="40"><input name="r06pos[]" type="text" id="r06pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r06pos']; ?>" size="2" /></td>
          <td width="40"><input name="r07pos[]" type="text" id="r07pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r07pos']; ?>" size="2" /></td>
          <td width="40"><input name="r08pos[]" type="text" id="r08pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r08pos']; ?>" size="2" /></td>
          <td width="40"><input name="r09pos[]" type="text" id="r09pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r09pos']; ?>" size="2" /></td>
          <td width="40"><input name="r10pos[]" type="text" id="r10pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r10pos']; ?>" size="2" /></td>
          <td width="40"><input name="r11pos[]" type="text" id="r11pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r11pos']; ?>" size="2" /></td>
          <td width="40"><input name="r12pos[]" type="text" id="r12pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r12pos']; ?>" size="2" /></td>
          <td width="40"><input name="r13pos[]" type="text" id="r13pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r13pos']; ?>" size="2" /></td>
          <td width="40"><input name="r14pos[]" type="text" id="r14pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r14pos']; ?>" size="2" /></td>
          <td width="40"><input name="r15pos[]" type="text" id="r15pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r15pos']; ?>" size="2" /></td>
          <td width="40"><input name="r16pos[]" type="text" id="r16pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r16pos']; ?>" size="2" /></td>
          <td width="40"><input name="r17pos[]" type="text" id="r17pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['r17pos']; ?>" size="2" /></td>
          <td width="40"><input name="r18pos[]" type="text" id="r18pos<?php echo($counter++);?>" value="<?php echo $row_Team_Score_Edit['r18pos']; ?>" size="2" /></td>
        </tr>
      </table>
      <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_Team_Score_Edit['team_id']; ?>" />
      <input type="hidden" name="id[]" value="<?php echo $row_Team_Score_Edit['scrsID']; ?>" />
      <br />
      <?php } while ($row_Team_Score_Edit = mysql_fetch_assoc($Team_Score_Edit)); ?>
    
    
    <input name="submit" value="submit" type="submit" />
  </form>
</center>
</body>
</html>
<?php
mysql_free_result($Team_Score_Edit);
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
