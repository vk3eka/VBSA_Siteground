<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once("../webassist/database_management/wa_appbuilder_php.php"); ?>
<?php

error_reporting(0);

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}
//echo($grade . "<br>");
$page = "http://www.vbsa.org.au/admin_scores/team_entries_player_multiple_insert.php?club_id=$club_id";
$_SESSION['page'] = $page;

$MM_authorizedUsers = "Webmaster,Treasurer,Scores";
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
$query_teams = "SELECT * FROM Team_entries WHERE team_id = '$team_id'";
$teams = mysql_query($query_teams, $connvbsa) or die(mysql_error());
$row_teams = mysql_fetch_assoc($teams);
$totalRows_teams = mysql_num_rows($teams);

// added to get grade name
mysql_select_db($database_connvbsa, $connvbsa);
$query_grade_name = "SELECT * FROM Team_grade WHERE grade = '$grade'";
$grade_name = mysql_query($query_grade_name, $connvbsa) or die(mysql_error());
$row_grade_name = mysql_fetch_assoc($grade_name);
$totalRows_grade_name = mysql_num_rows($grade_name);
//echo("Grade name = " . $row_grade_name['grade_name'] . "<br>");
$grade_name = $row_grade_name['grade_name'];

mysql_select_db($database_connvbsa, $connvbsa);
$query_players = "SELECT scrsID, scrs.MemberID, team_grade, team_id, members.MemberID, members.FirstName, members.LastName, scrs.captain_scrs, scrs.authoriser_scrs, Club, members.MobilePhone, members.Email FROM scrs, members WHERE scrs.team_id=" . $team_id . " AND scrs.MemberID=members.MemberID";
//echo("Players " . $query_players . "<br>");
$players = mysql_query($query_players, $connvbsa) or die(mysql_error());
$row_players = mysql_fetch_assoc($players);
$totalRows_players = mysql_num_rows($players);

mysql_select_db($database_connvbsa, $connvbsa);
$query_club = "SELECT scrs.MemberID, LastName, FirstName FROM Team_entries, scrs, members WHERE team_club_id = " . $club_id . " AND scrs.team_id = Team_entries.team_id AND scrs.MemberID = members.MemberID AND team_cal_year >= curdate() - interval 2 year AND (scrs.MemberID !=1 AND scrs.MemberID !=10 AND scrs.MemberID !=100 AND scrs.MemberID !=1000) GROUP BY members.MemberID ORDER BY LastName, FirstName";
//echo($query_club . "<br>");
$club = mysql_query($query_club, $connvbsa) or die(mysql_error());
$row_club = mysql_fetch_assoc($club);
$totalRows_club = mysql_num_rows($club);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grade_det = "SELECT grade, RP, type, season FROM Team_grade WHERE grade='$grade'";
$grade_det = mysql_query($query_grade_det, $connvbsa) or die(mysql_error());
$row_grade_det = mysql_fetch_assoc($grade_det);
$totalRows_grade_det = mysql_num_rows($grade_det);?>
<?php
// WA DataAssist Multiple Inserts
if (isset($_POST["submit"]) || isset($_POST["submit_x"])) // Trigger
{
  if (!session_id()) session_start();
  $WA_loopedFields = array("scrsID", "MemberID", "team_grade", "allocated_rp", "game_type", "scr_season", "team_id", "maxpts", "final_sub", "fin_year_scrs", "current_year_scrs");
  $WA_connection = $connvbsa;
  $WA_table = "scrs";
  $WA_redirectURL = "team_entries_player_multiple_insert.php?club_id=$club_id";
  if (function_exists("rel2abs")) $WA_redirectURL = $WA_redirectURL?rel2abs($WA_redirectURL,dirname(__FILE__)):"";
  $WA_keepQueryString = true;
  $WA_fieldNamesStr = "scrsID|MemberID|team_grade|allocated_rp|game_type|scr_season|team_id|maxpts|final_sub|fin_year_scrs|current_year_scrs";
  $WA_columnTypesStr = "none,none,NULL|none,none,NULL|',none,''|none,none,NULL|',none,''|',none,''|none,none,NULL|none,none,NULL|',none,''|',none,NULL|',none,NULL";
  $WA_insertIfNotBlank = "MemberID";
  $WA_fieldNames = explode("|", $WA_fieldNamesStr);
  $WA_columns = explode("|", $WA_columnTypesStr);
  $WA_connectionDB = $database_connvbsa;
  $WA_multipleInsertCounter = 0;
  mysql_select_db($WA_connectionDB, $WA_connection);
  while (WA_AB_checkMultiInsertLoopedFieldsExist($WA_loopedFields, $WA_multipleInsertCounter)) {
    if ($WA_insertIfNotBlank == "" || WA_AB_checkLoopedFieldsNotBlank(array($WA_insertIfNotBlank), $WA_multipleInsertCounter)) {
      $WA_fieldValuesStr = "".WA_AB_getLoopedFieldValue("scrsID", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("MemberID", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("team_grade", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("allocated_rp", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("game_type", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("scr_season", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("team_id", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("maxpts", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("final_sub", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("fin_year_scrs", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("current_year_scrs", $WA_multipleInsertCounter)  ."";
      $WA_fieldValues = explode($WA_AB_Split, $WA_fieldValuesStr);
      $insertParamsObj = WA_AB_generateInsertParams($WA_fieldNames, $WA_columns, $WA_fieldValues, -1);
      $WA_Sql = "INSERT INTO `" . $WA_table . "` (" . $insertParamsObj->WA_tableValues . ") VALUES (" . $insertParamsObj->WA_dbValues . ")";

//  WA_AB_Split . "".WA_AB_getLoopedFieldValue("grade_name", $WA_multipleInsertCounter)  ."" . $
//echo($WA_Sql . "<br>");
      $MM_editCmd = mysql_query($WA_Sql, $WA_connection) or die(mysql_error());
    }
    $WA_multipleInsertCounter++;
  }
  if ($WA_redirectURL != "")  {
    if ($WA_keepQueryString && $WA_redirectURL != "" && isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] !== "" && sizeof($_POST) > 0) {
      $WA_redirectURL .= ((strpos($WA_redirectURL, '?') === false)?"?":"&").$_SERVER["QUERY_STRING"];
    }
  //echo("Redirect " . $WA_redirectURL . "<br>");
  header("Location: ".$WA_redirectURL);
  }
}
?>
<?php
	// RepeatSelectionCounter_1 Initialization
	$RepeatSelectionCounter_1 = 0;
	$RepeatSelectionCounterBasedLooping_1 = true;
	$RepeatSelectionCounter_1_Iterations = "10";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table width="1000" align="center">
  <tr>
    <td colspan="2">Club id = <?php echo $club_id ?></td>
  </tr>
  <tr>
    <td align="left" class="red_bold">Insert players to team ID: <?php echo $team_id; ?> competing in  <?php echo $grade; ?> for: <?php echo $row_teams['team_name']; ?>, in season: <?php echo $season; ?></td>
    <td align="right" nowrap="nowrap" class="greenbg"><a href="team_entries.php?season=<?php echo $season ?>">Return to <?php echo $season ?> team entries</a></td>
  </tr>
  <tr>
    <td colspan="2" align="center"></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">For scoring purposes if there is a &quot;Bye&quot; in this grade  enter a player as Member ID : &quot;1&quot;.</td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
</table>
<table align="center" cellpadding="3" cellspacing="3">
  <tr>
    <th scope="col">If person does not exist:</th>
    <th scope="col"><span class="greenbg"><a href="../A_common/vbsa_member_insert.php?team_id=<?php echo $team_id ?>&amp;team_club=<?php echo $team_club ?>&amp;season=<?php echo $season ?>" rel="facebox">Insert a new player to the members table</a></span></th>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table align="center">
  <tr>
     <td><input name="quicksearch" type="text" id="quick_search" size="20" /></td><td>Quick Surname Search</td>
  </tr>
  <tr>
     <td><div id="qs_result" align="left" style="z-index: 1; position: absolute; "></div></td><td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<form id="form1" name="form1" method="post" action="">
  <table align="center">
	    <tr valign="top">
        
        <!--Nested Left Table start -->
	    <td> 
        <div class="insert_player_table">
<table align="center" cellpadding="3" cellspacing="3">
  <input type="hidden" name="grade" value="<?php echo $grade; ?>" />
  <tr>
    <td>Select player ID</br>from list</br>OR</br>Use quick surname search</br>for player ID</br></br>Insert player ID's</br>As required</td>
  </tr>
  
<?php
	// RepeatSelectionCounter_1 Begin Loop
	$RepeatSelectionCounter_1_IterationsRemaining = $RepeatSelectionCounter_1_Iterations;
	while($RepeatSelectionCounter_1_IterationsRemaining--){
		if($RepeatSelectionCounterBasedLooping_1 || $row_None){
?>  
<tr>
    <td>
    <input name="MemberID_<?php echo $RepeatSelectionCounter_1; ?>" type="text" id="MemberID_<?php echo $RepeatSelectionCounter_1; ?>" size="15" />
    <input type="hidden" name="scrsID_mihidden_<?php echo $RepeatSelectionCounter_1; ?>" id="scrsID_mihidden_<?php echo $RepeatSelectionCounter_1; ?>" value="1" />
      <input type="hidden" name="team_id_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $team_id; ?>" />
      <input type="hidden" name="maxpts_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php if($comptype=='Billiards') echo 2; else echo 3 ?>" />
      <input type="hidden" name="team_grade_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $grade; ?>" />
      <input type="hidden" name="allocated_rp_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_grade_det['RP']; ?>" />
      <input type="hidden" name="game_type_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $comptype; ?>" />
      <input type="hidden" name="scr_season_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $season; ?>" />

      <!--<input type="hidden" name="grade_name_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $grade_name; ?>" />-->


      <input type="hidden" name="fin_year_scrs_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php 
		if(date('m')<07) echo date("Y"); // Enters Current year as fin year if date before June 30
		else echo date("Y")+1; // Enters New fin year if date after June 30
		?> " />
      <input type="hidden" name="current_year_scrs_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo date("Y")?> " />
      <input type="hidden" name="final_sub_<?php echo $RepeatSelectionCounter_1; ?>" value="No" />
    </td>
  </tr>
  
  <?php
	} // RepeatSelectionCounter_1 Begin Alternate Content
	else{
?>
<?php } // RepeatSelectionCounter_1 End Alternate Content
		if(!$RepeatSelectionCounterBasedLooping_1 && $RepeatSelectionCounter_1_IterationsRemaining != 0){
			if(!$row_None && $RepeatSelectionCounter_1_Iterations == -1){$RepeatSelectionCounter_1_IterationsRemaining = 0;}
			$row_None = mysql_fetch_assoc($None);
		}
		$RepeatSelectionCounter_1++;
	} // RepeatSelectionCounter_1 End Loop
?>
  <tr>
    <td><input type="submit" name="submit" id="submit" value="Enter Players" /></td>
  </tr>
</table>

        </div>
      </td>
      <!--Nested Left Table finish -->
      <!--Nested Middle Table start -->
	  <td>
      <div class="insert_player_table">
        <table align="left" cellpadding="3" cellspacing="3"><tr>
            <td colspan="3" nowrap="nowrap">Players currently in this team</td>
            </tr>
          <tr>
            <td>Memb ID</td>
            <td>Name</td>
            <td>&nbsp;</td>
            </tr>
          
          <?php do { ?>
            <tr>
              <td><?php echo $row_players['MemberID']; ?></td>
              <td nowrap="nowrap"><?php echo $row_players['FirstName']; ?> <?php echo $row_players['LastName']; ?> <?php if($row_players['captain_scrs']>0) echo " (Capt)"; else if($row_players['authoriser_scrs']>0) echo " (Auth)"; else echo ""; ?></td>
              <td nowrap="nowrap" class="greenbg"><a href="user_files/scrs_player_capt_edit.php?scrsID=<?php echo $row_players['scrsID']; ?>&team_id=<?php echo $team_id ?>&team_grade=<?php echo $grade ?>&season=<?php echo $season ?>&club_id=<?php echo $club_id; ?>" >Edit / Select Captain/Authoriser</a></td>
              </tr>
            <?php } while ($row_players = mysql_fetch_assoc($players)); ?>
        </table>
        </div>
        </td>
      <!--Nested Middle Table finish --> 
      
      <!--Nested Right Table start -->
	  <td>
      <div class="insert_player_table">
        <table align="right" cellpadding="3" cellspacing="3">
          <tr>
            <td colspan="3" class="greenbg">All players for this club in the last 2 years</td>
            </tr>
          <tr>
          </tr>
          <?php do { ?>
            <tr>
              <td><?php echo $row_club['MemberID']; ?></td>
              <td><?php echo $row_club['LastName']; ?></td>
              <td><?php echo $row_club['FirstName']; ?></td>
            </tr>
            <?php } while ($row_club = mysql_fetch_assoc($club)); ?>
        </table>
        </div>
        </td>
      <!--Nested Right Table finish --> 
        </tr>
  </table>
	  


</form>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($teams);

mysql_free_result($players);

mysql_free_result($club);

mysql_free_result($grade_det);
?>
<script type="text/javascript">
    $(function(){
        $('#quick_search').keyup(function(e){
            console.log("Got call");
            var input = $(this).val();
            input = input.trim();
            if(input.length == 0) {
                return;
            }
            $.ajax({
                type: "get",
                url: "ajax/quick_member_search.php",
                data: {last_name: input},
                async: true,
                success: function(data){
                    var member = $.parseJSON(data);
                    $('#qs_result').html('');
                    for(x = 0; x < member.length; x++){
                        $('#qs_result').prepend('<div>' + member[x].MemberID + ' ' + member[x].FirstName + ' ' + member[x].LastName +  '</div>'); //Fills the #auto div with the options
                    }
                }
            })
        })
    });
</script>

