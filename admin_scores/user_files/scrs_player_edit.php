<?php require_once('../../Connections/connvbsa.php'); 
include ("../../vbsa_online_scores/php_functions.php");

error_reporting(0); 
mysql_select_db($database_connvbsa, $connvbsa);

?>
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

function AuthoriseEmail($access, $firstname, $rego_id, $password, $email, $team1, $team2, $team3)
{
    $team = $team1 . " team.";
    if($team2 != '')
    {
        $team = $team1 . " and " . $team2 . " teams.";
    }
    if($team3 != '')
    {
        $team = $team1 . ", " . $team2 . " and " . $team3 . " teams.";
    }
/*
    $subject = 'VBSA Scoring APP System Access'; 
    $message = '<html><body>';
    $message .= "<p>" . $firstname . "</p>";
    $message .= "<p>Here are your log-in details for the VBSA Scoring APP. You have been granted " . $access . " access to the system for the " . $team . "</p>";
    $message .= "<p>Username " . $email . "</p>";
    $message .= "<p>Password " . $password . "</p>";
    $message .= "<p>Please access the system and change your password, if you wish to.</p>";
    $message .= "<p>Click <a href='http://vbsa.org.au/'>here </a>to access the Scoring APP portal.</p>";
    $message .= "<p>Select from the menu item at far right of menu bar.</p>";
    $message .= "<p>Instructions on how to use the APP are located on the VBSA website Scores <a href='https://www.vbsa.org.au/VBSA_scores/scores_index.php'> page.</a></p>";
    $message .= "<p>Thanks.</p>";
    $message .= "<p>VBSA Scores Registrar.</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<p><i>NOTE: Please do not reply to this email as this email address is not monitored.</i></p>";
    $message .= "<p><i>Direct all emails to <a href='mailto:scores@vbsa.org.au'>scores@vbsa.org.au</a></i></p>";
    $message .= "<img src='http://vbsa.org.au/vbsa_online_scores/MarkDunn.jpg' width = '400px' height = '140px'>";
    $message .= "</body></html>";
    //echo($message . "<br>");
*/

    $subject = 'VBSA Scoring System Access'; 
    $message = '<html><body>';
    $message .= "<p>" . $firstname . "</p>";
    $message .= "<p>Here are your log-in details for the VBSA scoring system, the Scores Entry Webpage System (SEWS). You have been granted " . $access . " access for " . $team . ".</p>";
    $message .= "<p>Username " . $email . "</p>";
    $message .= "<p>Password " . $password . "</p>";
    $message .= "<p>Click <a href='https://vbsa.org.au/vbsa_online_scores'>here </a>to access the Scores Entry Webpage System (SEWS).</p>";
    $message .= "<p>Select 'Scoresheet Log In' from the menu item at far right of menu bar.</p>";
    $message .= "<p>Once logged in to SEWS, you can change your password, if you wish to.</p>";
    $message .= "<p>Instructions on how to use SEWS are located on the VBSA website Scores <a href='https://www.vbsa.org.au/VBSA_scores/scores_index.php'> page.</a></p>";
    $message .= "<p>Please note that a condition of being a SEWS Captain or Authoriser is that you agree to receive important or time sensitive messages regarding weekday Pennant.</p>";
    $message .= "<p>This does not override any other communication preferences you may have made with us.</p>";
    $message .= "<p>Your co-operation in this regard is appreciated.</p>";
    $message .= "<p>Thanks.</p>";
    $message .= "<p>VBSA Scores Registrar.</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<p>" . date('d/m/Y') . "</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<p><i>NOTE: Please do not reply to this email as this email address is not monitored.</i></p>";
    $message .= "<p><i>Direct all emails to <a href='mailto:scores@vbsa.org.au'>scores@vbsa.org.au</a></i></p>";
    $message .= "<img src='" . $url . "/MarkDunn.jpg' width = '400px' height = '140px'>";
    $message .= "</body></html>";

    SendBulkEmail($subject, $message, $email);
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}
//echo($season . "<br>");

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) 
{
  
  //echo($_POST['capt_auth'] . "<br>");
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
  //echo($query_team_name . "<br>");
  $scrs_team_name = mysql_query($query_team_name, $connvbsa) or die(mysql_error());
  $row_team_name = mysql_fetch_assoc($scrs_team_name);
  $team_name = $row_team_name['team_name'];

  // check if captain or authoriser checked already (authorise)
  $query_authorise = "Select PlayerNo, Access, Team_1, Team_2, Team_3 FROM tbl_authorise WHERE PlayerNo = " . $_POST['MemberID'] . " AND (Team_1 = '" . $team_name . "' OR Team_2 = '" . $team_name . "' OR Team_3 = '" . $team_name . "') AND Active = 1";
  //echo($query_authorise . "<br>");
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
  $query_email = "Select Email FROM members WHERE MemberID = " . $_POST['MemberID'];
  //echo("SQL " . $query_email . "<br>");
  $scrs_email = mysql_query($query_email, $connvbsa) or die(mysql_error());
  $row_email = mysql_fetch_assoc($scrs_email);
  $email = $row_email['Email'];

echo("Here 1<br>");
echo($captain_scrs . "<br>");
echo($row_scrs_authorise['Access'] . "<br>");

  if(($captain_scrs == 1) && ($row_scrs_authorise['Access'] == 'Team Captain'))
  //if((isset($_POST['captain_scrs'])) && ($row_scrs_authorise['Access'] == 'Team Captain'))
  {
    // no change, do nothing
    if($select_team == ' Team_1 ')
    {
      $sql_insert_capt = "Update tbl_authorise SET Access = '" . $row_scrs_authorise['Access'] . "', " . $authorise_select . ", Active = 1, Season =  '" . $season . "' WHERE PlayerNo = " . $_POST['MemberID']; // update here
      //echo("Update team name, access in tbl_authorise (capt) - " . $sql_insert_capt . "<br>"); 
    }
    else if(($select_team == ' Team_2 ') || ($select_team == ' Team_3 '))
    {
      $sql_insert_capt = "Update tbl_authorise SET " . $authorise_select . ", Active = 1, Season =  '" . $season . "'  WHERE PlayerNo = " . $_POST['MemberID']; // update here
      //echo("Update team name, access in tbl_authorise (capt) - " . $sql_insert_capt . "<br>"); 
    }
    //$sql_insert_capt = "Update tbl_authorise SET Access = '" . $row_scrs_authorise['Access'] . "', " . $authorise_select . ", Active = 1 WHERE PlayerNo = " . $_POST['MemberID']; // update here
    //echo("Update team name, access in tbl_authorise (capt) - " . $sql_insert_capt . "<br>"); 
    $update = mysql_query($sql_insert_capt, $connvbsa) or die(mysql_error());
  }
  //if((!isset($_POST['captain_scrs'])) && ($row_scrs_authorise['Access'] == 'Team Captain'))
  else if(($captain_scrs == 0) && ($row_scrs_authorise['Access'] == 'Team Captain'))
  {
    echo("Here 3<br>");
    // already a captain but not any more
    // remove from admin and sews
    $sql_delete_capt = "Update tbl_authorise Set " . $select_team . " = 'Not Allocated', Active = 0, Season =  '" . $season . "' Where PlayerNo = " . $_POST['MemberID'] . " AND Access = 'Team Captain'";  
    echo("Delete Capt " . $sql_delete_capt . "<br>"); 
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
    $sql_insert_capt = "Insert into tbl_authorise (Name, Password, Access, Team_1, PlayerNo, Email, Active, Season) VALUES ('" . $_POST['fullname'] . "', '" . password_hash($password, PASSWORD_DEFAULT) . "', '" . $access . "', '" . $row_team_name['team_name'] . "', " . $_POST['MemberID'] . ", '" . $row_email['Email'] . "', 1, Season =  '" . $season . "')";  
    $update = mysql_query($sql_insert_capt, $connvbsa) or die(mysql_error());
    AuthoriseEmail($access, $_POST['fullname'], $_POST['MemberID'], $password, $row_email['Email'], $row_team_name['team_name'], '', '');
    echo("Insert Capt " . $sql_insert_capt . "<br>"); 
  }
  else if(($captain_scrs == 1) && (isset($row_scrs_authorise['Access'])))
  //if((isset($_POST['captain_scrs']) && (isset($row_scrs_authorise['Access']))))
  {
    //echo("Existing in authorise!<br>");
    // is a captain in both
    // update sews
    $access = 'Team Captain';
    $sql_insert_capt = "Update tbl_authorise SET Access = '" . $access . "', " . $authorise_select . ", Active = 1, Season =  '" . $season . "'  WHERE PlayerNo = " . $_POST['MemberID']; // update here
    echo("Update Capt " . $sql_insert_capt . "<br>"); 
    $update = mysql_query($sql_insert_capt, $connvbsa) or die(mysql_error());
  }

echo("Here 2<br>");
  if(($authoriser_scrs == 1) && ($row_scrs_authorise['Access'] == 'Team Authoriser'))
  //if((isset($_POST['authoriser_scrs'])) && ($row_scrs_authorise['Access'] == 'Team Captain'))
  {
    //echo("Here<br>");
    // no change, do nothing
    if($select_team == ' Team_1 ')
    {
      $sql_insert_auth = "Update tbl_authorise SET Access = '" . $row_scrs_authorise['Access'] . "', " . $authorise_select . ", Active = 1, Season =  '" . $season . "' WHERE PlayerNo = " . $_POST['MemberID']; // update here
      echo("Update team name, access in tbl_authorise (auth) - " . $sql_insert_auth . "<br>"); 
    }
    else if(($select_team == ' Team_2 ') || ($select_team == ' Team_3 '))
    {
      $sql_insert_auth = "Update tbl_authorise SET " . $authorise_select . ", Active = 1, Season =  '" . $season . "' WHERE PlayerNo = " . $_POST['MemberID']; // update here
      //echo("Update team name, access in tbl_authorise (auth) - " . $sql_insert_auth . "<br>"); 
    }

    //$sql_insert_auth = "Update tbl_authorise SET Access = '" . $row_scrs_authorise['Access'] . "', " . $authorise_select . ", Active = 1 WHERE PlayerNo = " . $_POST['MemberID'];
    echo("Update team name, access in tbl_authorise (auth) - " . $sql_insert_auth . "<br>"); // Access needs to stay the same .............................
    $update = mysql_query($sql_insert_auth, $connvbsa) or die(mysql_error());

  }
  else if(($row_scrs_authorise['Access'] == 'Team Authoriser'))
  {
    // already a captain but not any more
    // remove from admin and sews
    $sql_delete_auth = "Update tbl_authorise Set " . $select_team . " = 'Not Allocated', Active = 0, Season =  '" . $season . "'  Where PlayerNo = " . $_POST['MemberID'] . " AND Access = 'Team Authoriser'";  
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
    $sql_insert_auth = "Insert into tbl_authorise (Name, Password, Access, Team_1, PlayerNo, Email, Active, Season) VALUES ('" . $_POST['fullname'] . "', '" . password_hash($password, PASSWORD_DEFAULT) . "', '" . $access . "', '" . $row_team_name['team_name'] . "', " . $_POST['MemberID'] . ", '" . $row_email['Email'] . "', 1, '" . $season . "')";  
    echo("Insert Auth " . $sql_insert_auth . "<br>"); 
    $update = mysql_query($sql_insert_auth, $connvbsa) or die(mysql_error());
    AuthoriseEmail($access, $_POST['fullname'], $_POST['MemberID'], $password, $row_email['Email'], $row_team_name['team_name'], '', '');    
  }
  else if(($authoriser_scrs == 1) && (isset($row_scrs_authorise['Access'])))
  {
    //echo("Existing in authorise!<br>");
    // not a captain but is now
    // add to admin and sews
    $access = 'Team Authoriser';
    $sql_insert_auth = "Update tbl_authorise SET Access = '" . $access . "', " . $authorise_select . ", Active = 1, Season =  '" . $season . "' WHERE PlayerNo = " . $_POST['MemberID'];
    echo("Update Auth " . $sql_insert_auth . "<br>"); 
    $update = mysql_query($sql_insert_auth, $connvbsa) or die(mysql_error());
  }

  $updateSQL = sprintf("Update scrs SET MemberID=%s, team_grade=%s, allocated_rp=%s, game_type=%s, scr_season=%s, team_id=%s, maxpts=%s, r01s=%s, r02s=%s, r03s=%s, r04s=%s, r05s=%s, r06s=%s, r07s=%s, r08s=%s, r09s=%s, r10s=%s, r11s=%s, r12s=%s, r13s=%s, r14s=%s, r15s=%s, r16s=%s, r17s=%s, r18s=%s, r01pos=%s, r02pos=%s, r03pos=%s, r04pos=%s, r05pos=%s, r06pos=%s, r07pos=%s, r08pos=%s, r09pos=%s, r10pos=%s, r11pos=%s, r12pos=%s, r13pos=%s, r14pos=%s, r15pos=%s, r16pos=%s, r17pos=%s, r18pos=%s, EF1=%s, EF2=%s, SF1=%s, SF2=%s, PF=%s, GF=%s, captain_scrs=%s, authoriser_scrs=%s, final_sub=%s WHERE scrsID=%s",
    GetSQLValueString($_POST['MemberID'], "int"),
    GetSQLValueString($_POST['team_grade'], "text"),
    GetSQLValueString($_POST['allocated_rp'], "int"),
    GetSQLValueString($_POST['game_type'], "text"),
    GetSQLValueString($_POST['scr_season'], "text"),
    GetSQLValueString($_POST['team_id'], "int"),
    GetSQLValueString($_POST['maxpts'], "int"),
    GetSQLValueString($_POST['r01s'], "int"),
    GetSQLValueString($_POST['r02s'], "int"),
    GetSQLValueString($_POST['r03s'], "int"),
    GetSQLValueString($_POST['r04s'], "int"),
    GetSQLValueString($_POST['r05s'], "int"),
    GetSQLValueString($_POST['r06s'], "int"),
    GetSQLValueString($_POST['r07s'], "int"),
    GetSQLValueString($_POST['r08s'], "int"),
    GetSQLValueString($_POST['r09s'], "int"),
    GetSQLValueString($_POST['r10s'], "int"),
    GetSQLValueString($_POST['r11s'], "int"),
    GetSQLValueString($_POST['r12s'], "int"),
    GetSQLValueString($_POST['r13s'], "int"),
    GetSQLValueString($_POST['r14s'], "int"),
    GetSQLValueString($_POST['r15s'], "int"),
    GetSQLValueString($_POST['r16s'], "int"),
    GetSQLValueString($_POST['r17s'], "int"),
    GetSQLValueString($_POST['r18s'], "int"),
    GetSQLValueString($_POST['r01pos'], "int"),
    GetSQLValueString($_POST['r02pos'], "int"),
    GetSQLValueString($_POST['r03pos'], "int"),
    GetSQLValueString($_POST['r04pos'], "int"),
    GetSQLValueString($_POST['r05pos'], "int"),
    GetSQLValueString($_POST['r06pos'], "int"),
    GetSQLValueString($_POST['r07pos'], "int"),
    GetSQLValueString($_POST['r08pos'], "int"),
    GetSQLValueString($_POST['r09pos'], "int"),
    GetSQLValueString($_POST['r10pos'], "int"),
    GetSQLValueString($_POST['r11pos'], "int"),
    GetSQLValueString($_POST['r12pos'], "int"),
    GetSQLValueString($_POST['r13pos'], "int"),
    GetSQLValueString($_POST['r14pos'], "int"),
    GetSQLValueString($_POST['r15pos'], "int"),
    GetSQLValueString($_POST['r16pos'], "int"),
    GetSQLValueString($_POST['r17pos'], "int"),
    GetSQLValueString($_POST['r18pos'], "int"),
    GetSQLValueString($_POST['EF1'], "int"),
    GetSQLValueString($_POST['EF2'], "int"),
    GetSQLValueString($_POST['SF1'], "int"),
    GetSQLValueString($_POST['SF2'], "int"),
    GetSQLValueString($_POST['PF'], "int"),
    GetSQLValueString($_POST['GF'], "int"),
    //GetSQLValueString(isset($_POST['captain_scrs']) ? "true" : "", "defined","1","0"),
    //GetSQLValueString(isset($_POST['authoriser_scrs']) ? "true" : "", "defined","1","0"),
    GetSQLValueString($captain_scrs, 'int'),
    GetSQLValueString($authoriser_scrs, 'int'),
    GetSQLValueString(isset($_POST['final_sub']) ? "true" : "", "defined","'Yes'","'No'"),
    GetSQLValueString($_POST['scrsID'], "int"));

  echo($updateSQL . "<br>");

  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../scores_ladders_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

echo($season . "<br>");
/*
$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}
*/

$scrs_id = "-1";
if (isset($_GET['scrs_id'])) {
  $scrs_id = $_GET['scrs_id'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

//$year = $_SESSION['year'];
$year = date("Y");
//echo("Year " . $year . "<br>");
$query_Scrs_Edit = "Select scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, scr_season, game_type, scrs.r01s, scrs.r02s, scrs.r03s, scrs.r04s, scrs.r05s, scrs.r06s, scrs.r07s, scrs.r08s, scrs.r09s, scrs.r10s, scrs.r11s, scrs.r12s, scrs.r13s, scrs.r14s, scrs.r15s, scrs.r16s, scrs.r17s, scrs.r18s, scrs.r01pos, scrs.r02pos, scrs.r03pos, scrs.r04pos, scrs.r05pos, scrs.r06pos, scrs.r07pos, scrs.r08pos, scrs.r09pos, scrs.r10pos, scrs.r11pos, scrs.r12pos, scrs.r13pos, scrs.r14pos, scrs.r15pos, scrs.r16pos, scrs.r17pos, scrs.r18pos, scrs.EF1, scrs.EF2, scrs.SF1, scrs.SF2, scrs.PF, scrs.GF, members.MemberID, members.FirstName, members.LastName, scrs.final_sub, scrs.captain_scrs, scrs.authoriser_scrs FROM scrs, members WHERE scrs.MemberID=members.MemberID AND scrs.scrsID = '$scrs_id'";

$Scrs_Edit = mysql_query($query_Scrs_Edit, $connvbsa) or die(mysql_error());
$row_Scrs_Edit = mysql_fetch_assoc($Scrs_Edit);
$totalRows_Scrs_Edit = mysql_num_rows($Scrs_Edit);

$query_grades_S1 = "SELECT grade, grade_name FROM Team_grade WHERE current ='Yes' AND season='S1' AND fix_cal_year = $year ORDER BY season, type, grade";

$grades_S1 = mysql_query($query_grades_S1, $connvbsa) or die(mysql_error());
$row_grades_S1 = mysql_fetch_assoc($grades_S1);
$totalRows_grades_S1 = mysql_num_rows($grades_S1);

$query_grades_S2 = "SELECT grade, grade_name FROM Team_grade WHERE current ='Yes' AND season='S2' AND fix_cal_year = $year ORDER BY season, type, grade";
$grades_S2 = mysql_query($query_grades_S2, $connvbsa) or die(mysql_error());
$row_grades_S2 = mysql_fetch_assoc($grades_S2);
$totalRows_grades_S2 = mysql_num_rows($grades_S2);

$query_grade_det = "SELECT grade, grade_name, RP, type, season FROM Team_grade WHERE grade='$grade' AND fix_cal_year = $year ";
$grade_det = mysql_query($query_grade_det, $connvbsa) or die(mysql_error());
$row_grade_det = mysql_fetch_assoc($grade_det);
$totalRows_grade_det = mysql_num_rows($grade_det);

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
<table border="0" align="center">
  <tr>
    <td align="left" class="red_bold">Edit a player score details for: <?php echo $row_Scrs_Edit['FirstName']; ?> <?php echo $row_Scrs_Edit['LastName']; ?> (<?php echo $row_Scrs_Edit['team_grade']; ?>)</td>
    <td>&nbsp;</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="page">&nbsp; </td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <input type='hidden' name='fullname' value='<?php echo $row_Scrs_Edit['FirstName'] . " " . $row_Scrs_Edit['LastName']; ?>'>
  <table width="930" border="1" align="center">
    <tr>
      <td colspan="2">Memb ID
        <input type="text" name="MemberID" value="<?php echo $row_Scrs_Edit['MemberID']; ?>" size="6" />
      </td>
      <td colspan="4" align="center">Grade
        <select name="team_grade">
          <?php if($season=='S1') do {  ?>
          <option value="<?php echo $row_grades_S1['grade']?>"<?php if (!(strcmp($row_grades_S1['grade'], $row_Scrs_Edit['team_grade']))) {echo "selected=\"selected\"";} ?>> <?php echo $row_grades_S1['grade_name']?></option>
          <?php
			} while ($row_grades_S1 = mysql_fetch_assoc($grades_S1));
				$rows = mysql_num_rows($grades_S1);
				if($rows > 0) {
    			mysql_data_seek($grades_S1, 0);
  			$row_grades_S1 = mysql_fetch_assoc($grades_S1);
			
			}if($season=='S2') do {  ?>
          <option value="<?php echo $row_grades_S2['grade']?>"<?php if (!(strcmp($row_grades_S2['grade'], $row_Scrs_Edit['team_grade']))) {echo "selected=\"selected\"";} ?>> <?php echo $row_grades_S2['grade_name']?></option>
          <?php
			} while ($row_grades_S2 = mysql_fetch_assoc($grades_S2));
				 $rows = mysql_num_rows($grades_S2);
				if($rows > 0) {
   			 mysql_data_seek($grades_S2, 0);
 			 $row_grades_S2 = mysql_fetch_assoc($grades_S2);
			}  ?>
      </select></td>
      <td colspan="4" align="center">Game Type: 
        <select name="game_type">
          <option value="Snooker" <?php if (!(strcmp("Snooker", htmlentities($row_Scrs_Edit['game_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Snooker</option>
          <option value="Billiards" <?php if (!(strcmp("Billiards", htmlentities($row_Scrs_Edit['game_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Billiards</option>
      </select></td>
      <td colspan="3" align="center" nowrap="nowrap">Season: <?php echo $row_Scrs_Edit['scr_season']; ?></td>
      <!--<td colspan="2" align="center">Capt? 
      <input type="checkbox" name="captain_scrs" id="captain_scrs"  <?php if (!(strcmp(htmlentities($row_Scrs_Edit['captain_scrs'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />
      </td>
      <td colspan="2" align="center">Authoriser? 
      <input type="checkbox" name="authoriser_scrs" id="authoriser_scrs"  <?php if (!(strcmp(htmlentities($row_Scrs_Edit['authoriser_scrs'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />
      </td>-->

      <td colspan="2" align="center">Capt? 
      <input type="radio" name="capt_auth" value="captain_scrs"  <?php if ($row_Scrs_Edit['captain_scrs'] == 1) {echo "checked=\"checked\"";} ?> />
      </td>
      <td colspan="2" align="center">Authoriser? 
      <input type="radio" name="capt_auth" value="authoriser_scrs"  <?php if ($row_Scrs_Edit['authoriser_scrs'] == 1) {echo "checked=\"checked\"";} ?> />
      </td>
      

      <td colspan="3" align="center">Team ID
      <input type="text" name="team_id" value="<?php echo $row_Scrs_Edit['team_id']; ?>" size="3" /></td>
    </tr>
    <tr>
      <td width="92">scrs ID : <?php echo $row_Scrs_Edit['scrsID']; ?></td>
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
      <td align="right">Score</td>
      <td width="40" align="center"><input type="text" name="r01s" value="<?php echo $row_Scrs_Edit['r01s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r02s" value="<?php echo $row_Scrs_Edit['r02s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r03s" value="<?php echo $row_Scrs_Edit['r03s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r04s" value="<?php echo $row_Scrs_Edit['r04s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r05s" value="<?php echo $row_Scrs_Edit['r05s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r06s" value="<?php echo $row_Scrs_Edit['r06s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r07s" value="<?php echo $row_Scrs_Edit['r07s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r08s" value="<?php echo $row_Scrs_Edit['r08s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r09s" value="<?php echo $row_Scrs_Edit['r09s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r10s" value="<?php echo $row_Scrs_Edit['r10s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r11s" value="<?php echo $row_Scrs_Edit['r11s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r12s" value="<?php echo $row_Scrs_Edit['r12s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r13s" value="<?php echo $row_Scrs_Edit['r13s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r14s" value="<?php echo $row_Scrs_Edit['r14s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r15s" value="<?php echo $row_Scrs_Edit['r15s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r16s" value="<?php echo $row_Scrs_Edit['r16s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r17s" value="<?php echo $row_Scrs_Edit['r17s']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r18s" value="<?php echo $row_Scrs_Edit['r18s']; ?>" size="3" /></td>
    </tr>
    <tr>
      <td align="right">Position</td>
      <td width="40" align="center"><input type="text" name="r01pos" value="<?php echo $row_Scrs_Edit['r01pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r02pos" value="<?php echo $row_Scrs_Edit['r02pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r03pos" value="<?php echo $row_Scrs_Edit['r03pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r04pos" value="<?php echo $row_Scrs_Edit['r04pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r05pos" value="<?php echo $row_Scrs_Edit['r05pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r06pos" value="<?php echo $row_Scrs_Edit['r06pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r07pos" value="<?php echo $row_Scrs_Edit['r07pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r08pos" value="<?php echo $row_Scrs_Edit['r08pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r09pos" value="<?php echo $row_Scrs_Edit['r09pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r10pos" value="<?php echo $row_Scrs_Edit['r10pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r11pos" value="<?php echo $row_Scrs_Edit['r11pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r12pos" value="<?php echo $row_Scrs_Edit['r12pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r13pos" value="<?php echo $row_Scrs_Edit['r13pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r14pos" value="<?php echo $row_Scrs_Edit['r14pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r15pos" value="<?php echo $row_Scrs_Edit['r15pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r16pos" value="<?php echo $row_Scrs_Edit['r16pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r17pos" value="<?php echo $row_Scrs_Edit['r17pos']; ?>" size="3" /></td>
      <td width="40" align="center"><input type="text" name="r18pos" value="<?php echo $row_Scrs_Edit['r18pos']; ?>" size="3" /></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
      <td align="center">EF1</td>
      <td align="center"><input type="text" name="EF1" value="<?php echo $row_Scrs_Edit['EF1']; ?>" size="3" /></td>
      <td align="center">EF2</td>
      <td align="center"><input type="text" name="EF2" value="<?php echo $row_Scrs_Edit['EF2']; ?>" size="3" /></td>
      <td align="center">SF1</td>
      <td align="center"><input type="text" name="SF1" value="<?php echo $row_Scrs_Edit['SF1']; ?>" size="3" /></td>
      <td align="center">SF2</td>
      <td align="center"><input type="text" name="SF2" value="<?php echo $row_Scrs_Edit['SF2']; ?>" size="3" /></td>
      <td align="center">PF</td>
      <td align="center"><input type="text" name="PF" value="<?php echo $row_Scrs_Edit['PF']; ?>" size="3" /></td>
      <td align="center">GF</td>
      <td align="center"><input type="text" name="GF" value="<?php echo $row_Scrs_Edit['GF']; ?>" size="3" /></td>
      <!--<td align="center">&nbsp;</td>-->
      <?php 
      if(($row_Scrs_Edit['captain_scrs'] == 1) || ($row_Scrs_Edit['authoriser_scrs'] == 1))
      {
        $checked = '';
      }
      else
      {
        $checked = "checked='checked'";
      }
      ?>
      <td colspan="4" align="center">Player Only? 
      <input type="radio" name="capt_auth" value="player_only" <?php echo($checked); ?>/></td>
      <td colspan="2" align="center">Final Sub 
      <input type="checkbox" name="final_sub" id="final_sub"  <?php if (!(strcmp(htmlentities($row_Scrs_Edit['final_sub'], ENT_COMPAT, 'utf-8'),"Yes"))) {echo "checked=\"checked\"";} ?> /></td>
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
  <input type="hidden" name="allocated_rp" value="<?php echo $row_grade_det['RP']; ?>" />
  <input type="hidden" name="scr_season" value="<?php echo $row_grade_det['season']; ?>" />
  <input type="hidden" name="maxpts" value="<?php if($comptype=='Billiards') echo 2; else echo 3 ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</center>
</body>
</html>

