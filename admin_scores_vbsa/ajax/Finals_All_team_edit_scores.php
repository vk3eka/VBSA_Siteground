<?php require_once('../../Connections/connvbsa.php'); ?>
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

$editFormAction = "ajax/Finals_All_team_edit_scores.php";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_POST["SF1"])) {
	for($i=0; $i < count($_POST["SF1"]); $i++) {
		$updateSQL = sprintf("UPDATE scrs
	    SET SF1 = %s,
		SF2 = %s,
		GF = %s,
		SF1_pos = %s,
		SF2_pos = %s,
		GF_pos = %s
		WHERE scrsID = %d",
		GetSQLValueString($_POST["SF1"][$i], "int"),
		GetSQLValueString($_POST["SF2"][$i], "int"),
		GetSQLValueString($_POST["GF"][$i], "int"),
		GetSQLValueString($_POST["SF1_pos"][$i], "int"),
		GetSQLValueString($_POST["SF2_pos"][$i], "int"),
		GetSQLValueString($_POST["GF_pos"][$i], "int"),
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
		echo "Successfully updated!";
 		exit;					
	}
}

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

$team_id = "-1";
if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

$colname_Rds = "-1";
if (isset($_GET['team_id'])) {
  $colname_Rds = $_GET['team_id'];
}


mysql_select_db($database_connvbsa, $connvbsa);
$query_Team_Score_Edit = "SELECT scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, scrs.SF1, scrs.SF2,  scrs.GF,  scrs.SF1_pos,  scrs.SF2_pos, scrs.GF_pos, members.MemberID, members.FirstName, members.LastName, Team_entries.team_id, Team_entries.team_name, Team_entries.team_grade,  final_sub FROM scrs, members, Team_entries WHERE members.MemberID=scrs.MemberID  AND Team_entries.team_id=scrs.team_id   AND (count_played>3 OR  final_sub='Yes')  AND Team_entries.team_id='$team_id' ORDER BY members.FirstName";
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

<center>
  <form name="form1" id="form1" method="post" onsubmit="return doit()">
    
    <?php $counter=1;
  do { ?>
      : 
      <table width="425" border="1">
        <tr>
          <td width="179">Member ID : <?php echo $row_Team_Score_Edit['MemberID']; ?> Record : <?php echo($counter);?></td>
          <td width="92">Scrs : <?php echo $row_Team_Score_Edit['scrsID']; ?></td>
          <td width="40" align="center">SF1</td>
          <td width="40" align="center">SF2</td>
          <td width="40" align="center">GF</td>
        </tr>
        <tr>
          <td>Team Name : <?php echo $row_Team_Score_Edit['team_name']; ?></td>
          <td align="right">Round</td>
          <td width="40"><input name="SF1[]" type="text" id="SF1<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['SF1']; ?>" size="2" /></td>
          <td width="40"><input name="SF2[]" type="text" id="SF2<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['SF2']; ?>" size="2" /></td>
          <td width="40"><input name="GF[]" type="text" id="GF<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['GF']; ?>" size="2" /></td>
        </tr>
        <tr>
          <td><?php echo $row_Team_Score_Edit['FirstName']; ?> <?php echo $row_Team_Score_Edit['LastName']; ?></td>
          <td align="right">          Position</td>
          <td width="40"><input name="SF1_pos[]" type="text" id="SF1_pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['SF1_pos']; ?>" size="2" /></td>
          <td width="40"><input name="SF2_pos[]" type="text" id="SF2_pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['SF2_pos']; ?>" size="2" /></td>
          <td width="40"><input name="GF_pos[]" type="text" id="GF_pos<?php echo($counter);?>" value="<?php echo $row_Team_Score_Edit['GF_pos']; ?>" size="2" /></td>
        </tr>
      </table>
      <input type="hidden" name="id[]" value="<?php echo $row_Team_Score_Edit['scrsID']; ?>" />
      <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_Team_Score_Edit['team_id']; ?>" />
      <br />
      <?php } while ($row_Team_Score_Edit = mysql_fetch_assoc($Team_Score_Edit)); ?>
    
    
    <input name="submit" value="Update finals scores" type="submit" />
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
