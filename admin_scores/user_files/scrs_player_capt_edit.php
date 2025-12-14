<?php require_once('../../Connections/connvbsa.php'); 
include ("../../vbsa_online_scores/php_functions.php");
?>
<?php

mysql_select_db($database_connvbsa, $connvbsa);

if (!isset($_SESSION)) {
  session_start();
}

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

$team_id = "-1";
if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

$team_grade = "-1";
if (isset($_GET['team_grade'])) {
  $team_grade = $_GET['team_grade'];
}

$team_club = "-1";
if (isset($_GET['team_club'])) {
  $team_club = $_GET['team_club'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$scrsID = "-1";
if (isset($_GET['scrsID'])) {
  $scrsID = $_GET['scrsID'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) 
{
  //echo("Captain " . $_POST['capt_auth'] . "<br>");
  if($_POST['capt_auth'] == 'captain_scrs')
  {
    $captain_scrs = 1;
    $authoriser_scrs = 0;
  }
  else if($_POST['capt_auth'] == 'authoriser_scrs')
  {
    $captain_scrs = 0;
    $authoriser_scrs = 1;
  }
  
  // update authorise table

  // check if captain or authoriser checked already (scrs)
  //$query_captain = "Select MemberID, team_grade, team_id, scr_season, captain_scrs, authoriser_scrs FROM scrs WHERE MemberID = " . $_POST['MemberID'] . " AND team_id = " . $_POST['team_id'];
  //$scrs_captain = mysql_query($query_captain, $connvbsa) or die(mysql_error());
  //$row_scrs_captain = mysql_fetch_assoc($scrs_captain);

 

  // get team name from team id
  $query_team_name = "Select team_name FROM Team_entries WHERE team_id = " . $_POST['team_id'];
  $scrs_team_name = mysql_query($query_team_name, $connvbsa) or die(mysql_error());
  $row_team_name = mysql_fetch_assoc($scrs_team_name);
  $team_name = $row_team_name['team_name'];

  // check if captain or authoriser checked already (authorise)
  $query_authorise = "Select PlayerNo, Access, Team_1, Team_2, Team_3 FROM tbl_authorise WHERE PlayerNo = " . $_POST['MemberID'] . " AND (Team_1 = '" . $team_name . "' OR Team_2 = '" . $team_name . "' OR Team_3 = '" . $team_name . "') AND Active = 1";
  $scrs_authorise = mysql_query($query_authorise, $connvbsa) or die(mysql_error());
  $row_scrs_authorise = mysql_fetch_assoc($scrs_authorise);
  $authorise_select = '';
  $select_rows = $scrs_authorise->num_rows;
  //echo("Rows " . $scrs_authorise->num_rows);
  if($scrs_authorise->num_rows > 0)
  {
    if($row_scrs_authorise['Team_1'] == $team_name)
    {
      //echo('Update this one 1<br>');
      //echo("Captain/Authoriser exists in authorise as " . $row_scrs_authorise['Access'] . " in " . $row_scrs_authorise['Team_1'] . " for " . $team_name . "<br>");
      $authorise_select = " Team_1 = '" . $row_team_name['team_name'] . "'";
      $select_team = ' Team_1 ';
    }
    else if($row_scrs_authorise['Team_2'] == $team_name)
    {
      //echo('Update this one 2<br>');
      //echo("Captain/Authoriser exists in authorise as " . $row_scrs_authorise['Access'] . " in " . $row_scrs_authorise['Team_2'] . " for " . $team_name . "<br>");
      $authorise_select = " Team_2 = '" . $row_team_name['team_name'] . "'";
      $select_team = ' Team_2 ';
    }
    else if($row_scrs_authorise['Team_3'] == $team_name)
    {
      //echo('Update this one 3<br>');
      //echo("Captain/Authoriser exists in authorise as " . $row_scrs_authorise['Access'] . " in " . $row_scrs_authorise['Team_3'] . " for " . $team_name . "<br>");
      $authorise_select = " Team_3 = '" . $row_team_name['team_name'] . "'";
      $select_team = ' Team_3 ';

    }
  }
  //echo("<br>");

  //echo("Post Captain " . $captain_scrs . "<br>");
  //echo("Post Authoriser " . $authoriser_scrs . "<br>");
  //echo("Post Captain " . isset($_POST['captain_scrs']) . "<br>");
  //echo("Post Authoriser " . isset($_POST['authoriser_scrs']) . "<br>");
  //echo("<br>");

  // get email from member id
  $query_email = "Select FirstName, LastName, Email FROM members WHERE MemberID = " . $_POST['MemberID'];
  $scrs_email = mysql_query($query_email, $connvbsa) or die(mysql_error());
  $row_email = mysql_fetch_assoc($scrs_email);
  $fullname = $row_email['FirstName'] . " " . $row_email['LastName'];
  $email = $row_email['Email'];


  if(($captain_scrs == 1) && ($row_scrs_authorise['Access'] == 'Team Captain'))
  //if((isset($_POST['captain_scrs'])) && ($row_scrs_authorise['Access'] == 'Team Captain'))
  {
    // no change, do nothing
    if($select_team == ' Team_1 ')
    {
      $sql_insert_capt = "Update tbl_authorise SET Access = '" . $row_scrs_authorise['Access'] . "', " . $authorise_select . ", Active = 1 WHERE PlayerNo = " . $_POST['MemberID']; // update here
      //echo("Update team name, access in tbl_authorise (capt) - " . $sql_insert_capt . "<br>"); 
    }
    else if(($select_team == ' Team_2 ') || ($select_team == ' Team_3 '))
    {
      $sql_insert_capt = "Update tbl_authorise SET " . $authorise_select . ", Active = 1 WHERE PlayerNo = " . $_POST['MemberID']; // update here
      //echo("Update team name, access in tbl_authorise (capt) - " . $sql_insert_capt . "<br>"); 
    }
    //$sql_insert_capt = "Update tbl_authorise SET Access = '" . $row_scrs_authorise['Access'] . "', " . $authorise_select . ", Active = 1 WHERE PlayerNo = " . $_POST['MemberID']; // update here
    //echo("Update team name, access in tbl_authorise (capt) - " . $sql_insert_capt . "<br>"); 
    $update = mysql_query($sql_insert_capt, $connvbsa) or die(mysql_error());

  }
  //if((!isset($_POST['captain_scrs'])) && ($row_scrs_authorise['Access'] == 'Team Captain'))
  else if(($captain_scrs == 0) && ($row_scrs_authorise['Access'] == 'Team Captain'))
  {
    // already a captain but not any more
    // remove from admin and sews
    $sql_delete_capt = "Update tbl_authorise Set " . $select_team . " = 'Not Allocated', Active = 0 Where PlayerNo = " . $_POST['MemberID'] . " AND Access = 'Team Captain'";  
    //echo("Delete Capt " . $sql_delete_capt . "<br>"); 
    $update = mysql_query($sql_delete_capt, $connvbsa) or die(mysql_error());
  }
  else if(($captain_scrs == 1) && (!isset($row_scrs_authorise['Access'])) && ($select_rows == 0)) // add in not a captain for any team
  //if((isset($_POST['captain_scrs']) && (!isset($row_scrs_authorise['Access']))))
  {
    //echo("Not existing in authorise!<br>");
    // not a captain but is now
    // add to sews
    $access = 'Team Captain';
    $password = generatePassword(10);
    // send email to new captain
    $sql_insert_capt = "Insert into tbl_authorise (Name, Password, Access, Team_1, PlayerNo, Email, Active) VALUES ('" . $fullname . "', '" . password_hash($password, PASSWORD_DEFAULT) . "', '" . $access . "', '" . $row_team_name['team_name'] . "', " . $_POST['MemberID'] . ", '" . $row_email['Email'] . "', 1)";  
    $update = mysql_query($sql_insert_capt, $connvbsa) or die(mysql_error());
    AuthoriseEmail($access, $fullname, $_POST['MemberID'], $password, $row_email['Email'], $row_team_name['team_name'], '', '');
    //echo("Insert Capt " . $sql_insert_capt . "<br>"); 
  }
  else if(($captain_scrs == 1) && (isset($row_scrs_authorise['Access'])))
  //if((isset($_POST['captain_scrs']) && (isset($row_scrs_authorise['Access']))))
  {
    //echo("Existing in authorise!<br>");
    // is a captain in both
    // update sews
    $access = 'Team Captain';
    $sql_insert_capt = "Update tbl_authorise SET Access = '" . $access . "', " . $authorise_select . ", Active = 1 WHERE PlayerNo = " . $_POST['MemberID']; // update here
    //echo("Update Capt " . $sql_insert_capt . "<br>"); 
    $update = mysql_query($sql_insert_capt, $connvbsa) or die(mysql_error());
  }


  if(($authoriser_scrs == 1) && ($row_scrs_authorise['Access'] == 'Team Authoriser'))
  //if((isset($_POST['authoriser_scrs'])) && ($row_scrs_authorise['Access'] == 'Team Captain'))
  {
    // no change, do nothing
    if($select_team == ' Team_1 ')
    {
      $sql_insert_auth = "Update tbl_authorise SET Access = '" . $row_scrs_authorise['Access'] . "', " . $authorise_select . ", Active = 1 WHERE PlayerNo = " . $_POST['MemberID']; // update here
      //echo("Update team name, access in tbl_authorise (auth) - " . $sql_insert_auth . "<br>"); 
    }
    else if(($select_team == ' Team_2 ') || ($select_team == ' Team_3 '))
    {
      $sql_insert_auth = "Update tbl_authorise SET " . $authorise_select . ", Active = 1 WHERE PlayerNo = " . $_POST['MemberID']; // update here
      //echo("Update team name, access in tbl_authorise (auth) - " . $sql_insert_auth . "<br>"); 
    }

    //$sql_insert_auth = "Update tbl_authorise SET Access = '" . $row_scrs_authorise['Access'] . "', " . $authorise_select . ", Active = 1 WHERE PlayerNo = " . $_POST['MemberID'];
    //echo("Update team name, access in tbl_authorise (auth) - " . $sql_insert_auth . "<br>"); // Access needs to stay the same .............................
    $update = mysql_query($sql_insert_auth, $connvbsa) or die(mysql_error());

  }
  else if(($row_scrs_authorise['Access'] == 'Team Authoriser'))
  {
    // already a captain but not any more
    // remove from admin and sews
    $sql_delete_auth = "Update tbl_authorise Set " . $select_team . " = 'Not Allocated', Active = 0 Where PlayerNo = " . $_POST['MemberID'] . " AND Access = 'Team Authoriser'";  
    //echo("Delete Auth " . $sql_delete_auth . "<br>"); 
    $update = mysql_query($sql_delete_auth, $connvbsa) or die(mysql_error());
  }
  else if(($authoriser_scrs == 1) && (!isset($row_scrs_authorise['Access'])) && ($select_rows == 0)) // add if not an authosiser for any team
  {
    //echo("Not existing in authorise!<br>");
    // not a captain but is now
    // add to admin and sews
    $access = 'Team Authoriser';
    $password = generatePassword(10);
    // send email to new captain
    $sql_insert_auth = "Insert into tbl_authorise (Name, Password, Access, Team_1, PlayerNo, Email, Active) VALUES ('" . $fullname . "', '" . password_hash($password, PASSWORD_DEFAULT) . "', '" . $access . "', '" . $row_team_name['team_name'] . "', " . $_POST['MemberID'] . ", '" . $row_email['Email'] . "', 1)";  
    //echo("Insert Auth " . $sql_insert_auth . "<br>"); 
    $update = mysql_query($sql_insert_auth, $connvbsa) or die(mysql_error());
    AuthoriseEmail($access, $fullname, $_POST['MemberID'], $password, $row_email['Email'], $row_team_name['team_name'], '', '');    
  }
  else if(($authoriser_scrs == 1) && (isset($row_scrs_authorise['Access'])))
  {
    //echo("Existing in authorise!<br>");
    // not a captain but is now
    // add to admin and sews
    $access = 'Team Authoriser';
    $sql_insert_auth = "Update tbl_authorise SET Access = '" . $access . "', " . $authorise_select . ", Active = 1 WHERE PlayerNo = " . $_POST['MemberID'];
    //echo("Update Auth " . $sql_insert_auth . "<br>"); 
    $update = mysql_query($sql_insert_auth, $connvbsa) or die(mysql_error());
  }

  $updateSQL = sprintf("UPDATE scrs SET MemberID=%s, team_grade=%s, team_id=%s, scr_season=%s, captain_scrs=%s, authoriser_scrs=%s WHERE scrsID=%s",
       GetSQLValueString($_POST['MemberID'], "int"),
       GetSQLValueString($_POST['team_grade'], "text"),
       GetSQLValueString($_POST['team_id'], "int"),
       GetSQLValueString($_POST['scr_season'], "text"),
       //GetSQLValueString(isset($_POST['captain_scrs']) ? "true" : "", "defined","1","0"),
       //GetSQLValueString(isset($_POST['authoriser_scrs']) ? "true" : "", "defined","1","0"),
       GetSQLValueString($captain_scrs, 'int'),
       GetSQLValueString($authoriser_scrs, 'int'),
       GetSQLValueString($_POST['scrsID'], "int"));
  //echo($updateSQL . "<Br>");
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../team_entries_player_multiple_insert.php?team_id=".$team_id."&season=".$season."&team_club=".$team_club."&team_grade=".$team_grade;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));

}

//echo("Grade " . $_GET['grade'] . "<br>");

//mysql_select_db($database_connvbsa, $connvbsa);
$query_scrs_edit = "Select scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id,  members.FirstName, members.LastName, scrs.captain_scrs, scrs.authoriser_scrs, scr_season, Club FROM scrs, members WHERE scrs.MemberID=members.MemberID AND scrs.scrsID ='$scrsID'";
//echo($query_scrs_edit . "<br>");
$scrs_edit = mysql_query($query_scrs_edit, $connvbsa) or die(mysql_error());
$row_scrs_edit = mysql_fetch_assoc($scrs_edit);
$totalRows_scrs_edit = mysql_num_rows($scrs_edit);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_vbsa_grades = "Select grade, grade_name, season  FROM Team_grade  WHERE season='$season' AND current='Yes' and fix_cal_year = " . date("Y") . " ORDER BY type, grade";
$vbsa_grades = mysql_query($query_vbsa_grades, $connvbsa) or die(mysql_error());
$row_vbsa_grades = mysql_fetch_assoc($vbsa_grades);
$totalRows_vbsa_grades = mysql_num_rows($vbsa_grades);
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
<?php //echo('Here<br>'); ?>
<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<table align="center">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="red_bold">Edit player details</td>
    <td align="right">&nbsp;</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" cellpadding="3" cellspacing="3">
    <tr>
      <td align="right" nowrap="nowrap">Scrs ID:</td>
      <td>&nbsp;<?php echo $scrsID; ?></td>
    </tr>
    <tr>
      <td align="right" >Season:</td>
      <td align="left">&nbsp;<?php echo $season; ?></td>
    </tr>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Member ID:</td>
      <td valign="middle"><input type="text" name="MemberID" value="<?php echo $row_scrs_edit['MemberID']; ?>" size="10" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Grade:</td>
      <td>
            <select name="team_grade">
            <?php
            do {  
            ?>
            <!--<option value='test'><?php echo $row_scrs_edit['team_grade']?></option>-->
            <option value="<?php echo $row_scrs_edit['team_grade']; ?>"<?php if (!(strcmp($row_vbsa_grades['grade'], $row_scrs_edit['team_grade']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vbsa_grades['grade_name']?></option>
            <?php
            } while ($row_vbsa_grades = mysql_fetch_assoc($vbsa_grades));
              $rows = mysql_num_rows($vbsa_grades);
              if($rows > 0) {
                  mysql_data_seek($vbsa_grades, 0);
            	  $row_vbsa_grades = mysql_fetch_assoc($vbsa_grades);
              }
            ?>
            </select>
      </td>
       <!--<td valign="middle"><input type="text" name="team_grade" value="<?php echo $row_scrs_edit['team_grade']; ?>" size="10" /></td>-->
    </tr>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Team ID:</td>
      <td valign="middle"><input type="text" name="team_id" value="<?php echo $row_scrs_edit['team_id']; ?>" size="10" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Season:</td>
      <td valign="middle"><select name="scr_season">
        <option value="S1" <?php if (!(strcmp("S1", htmlentities($row_scrs_edit['scr_season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S1</option>
        <option value="S2" <?php if (!(strcmp("S2", htmlentities($row_scrs_edit['scr_season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S2</option>
      </select>
      
      </td>
    </tr>
       <td align="right" valign="middle" nowrap="nowrap">Captain:</td>
       <td colspan="3" align="left"><input type="radio" name="capt_auth" value="captain_scrs"  <?php if ($row_scrs_edit['captain_scrs'] == 1) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Authoriser:</td>
      <td colspan="3" align="left"><input type="radio" name="capt_auth" value="authoriser_scrs"  <?php if ($row_scrs_edit['authoriser_scrs'] == 1) {echo "checked=\"checked\"";} ?> /></td>

    <!--<tr>
      <td align="right" valign="middle" nowrap="nowrap">Captain:</td>
      <td colspan="3" align="left"><input type="checkbox" name="captain_scrs" id="captain_scrs"  <?php if (!(strcmp(htmlentities($row_scrs_edit['captain_scrs'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Authoriser:</td>
      <td colspan="3" align="left"><input type="checkbox" name="authoriser_scrs" id="authoriser_scrs"  <?php if (!(strcmp(htmlentities($row_scrs_edit['authoriser_scrs'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>-->
    <?php 
      if(($row_scrs_edit['captain_scrs'] == 1) || ($row_scrs_edit['authoriser_scrs'] == 1))
      {
        $checked = '';
      }
      else
      {
        $checked = "checked='checked'";
      }
    ?>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Player Only:</td>
      <td colspan="3" align="left"><input type="radio" name="capt_auth" value="player_only" <?php echo($checked); ?>/></td>
    <tr>
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update Player" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="scrsID" value="<?php echo $row_scrs_edit['scrsID']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php

?>


