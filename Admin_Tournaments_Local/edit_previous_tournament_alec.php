<?php require_once('../Connections/connvbsa.php'); 

error_reporting(0);
mysql_select_db($database_connvbsa, $connvbsa);

?>
<?php require_once("../webassist/ckeditor/ckeditor.php"); ?>
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

if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    /*
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }
    */

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

if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
}

//echo("Page " . $_GET['page'] . "<br>");

$query_tourn1 = "Select * FROM tournaments WHERE tourn_id = '$tourn_id'";
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);

$tourn_type = $row_tourn1['tourn_type'];

if($tourn_type == 'Snooker')
{
  // Snooker Tournaments
  $query_players_confirmed = "Select ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, Gender, amount_entry, how_paid, entry_confirmed, HomeState, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, ranknum, wcard, Junior, rank_S_open_tourn.total_tourn_rp as rank_pts FROM tourn_entry, members  LEFT JOIN rank_S_open_tourn ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=1 ORDER BY FirstName, Junior";
}
elseif($tourn_type == 'Billiards')
{
  // Billiard Tournaments
  $query_players_confirmed = "Select ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, Gender, amount_entry, how_paid, entry_confirmed, HomeState, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, ranknum, wcard, Junior, rank_Billiards.total_rp as rank_pts FROM tourn_entry, members  LEFT JOIN rank_Billiards ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=1 ORDER BY FirstName, Junior";
}

$players_confirmed = mysql_query($query_players_confirmed, $connvbsa) or die(mysql_error());
$row_players_confirmed = mysql_fetch_assoc($players_confirmed);
$total_players = mysql_num_rows($players_confirmed);

echo("Players " . $total_players . "<br>");

switch ($total_players) 
{
    case ($total_players <= 8):
      $tourn_size = 8;
      break;
    case ($total_players <= 16) && ($total_players > 8):
      $tourn_size = 16;
      break;
    case ($total_players <= 32) && ($total_players > 16):
      $tourn_size = 32;
      break;
    case ($total_players <= 64) && ($total_players > 32):
      $tourn_size = 64;
      break;
    case ($total_players <= 128) && ($total_players > 64):
      $tourn_size = 128;
      break;
}
$no_of_matches = $tourn_size;
//echo("Tourn Size " . $tourn_size . "<br>");
//echo("Matches " . $no_of_matches . "<br>");

$query_Cal_edit = "Select * FROM calendar WHERE tourn_id = '$tourn_id'";
$Cal_edit = mysql_query($query_Cal_edit, $connvbsa) or die(mysql_error());
$row_Cal_edit = mysql_fetch_assoc($Cal_edit);
$totalRows_Cal_edit = mysql_num_rows($Cal_edit);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  //$about_text = str_replace(PHP_EOL, '', $_POST['about']);
  $about_text = str_replace("\r\n", '', $_POST['about']);
  //$about_text = nl2br($_POST['about']);
  $updateCalSQL = sprintf("Update calendar SET event=%s, venue=%s, state=%s, aust_rank=%s, ranking_type=%s, startdate=%s, finishdate=%s, closedate=%s, about=%s, tourn=%s, visible=%s, footer1=%s, footer2=%s, footer3=%s, footer4=%s, special_dates=%s, next_venue=%s, tourn_director=%s, referee_early=%s, referee_later=%s, current_trophy_numbers=%s, current_trophy_costs=%s, next_trophy_numbers=%s, next_trophy_costs=%s, comments=%s WHERE event_id=%s",
                       GetSQLValueString($_POST['event'], "text"),
                       GetSQLValueString($_POST['venue'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['aust_rank'], "text"),
                       GetSQLValueString($_POST['ranking_type'], "text"),
                       GetSQLValueString($_POST['startdate'], "date"),
                       GetSQLValueString($_POST['finishdate'], "date"),
                       GetSQLValueString($_POST['closedate'], "date"),
                       GetSQLValueString($about_text, "text"),
                       GetSQLValueString($_POST['tourn'], "text"),
                       GetSQLValueString($_POST['visible'], "text"),
                       GetSQLValueString(isset($_POST['footer1']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer2']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer3']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer4']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString($_POST['special_dates'], "text"),
                       GetSQLValueString($_POST['next_venue'], "text"),
                       GetSQLValueString($_POST['tourn_director'], "text"),
                       GetSQLValueString($_POST['referee_early'], "text"),
                       GetSQLValueString($_POST['referee_later'], "text"),
                       GetSQLValueString($_POST['current_trophy_numbers'], "int"),
                       GetSQLValueString($_POST['current_trophy_costs'], "int"),
                       GetSQLValueString($_POST['next_trophy_numbers'], "int"),
                       GetSQLValueString($_POST['next_trophy_costs'], "int"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['event_id'], "int"));
  $Result1 = mysql_query($updateCalSQL, $connvbsa) or die(mysql_error());

  $updateTournSQL = sprintf("Update tournaments SET tourn_name=%s, tourn_year=%s, site_visible=%s, tourn_type=%s, ranking_type=%s, tourn_class=%s, how_seed=%s, move_top_seed=%s, matches_day_1=%s, matches_day_2=%s, matches_day_3=%s, matches_day_4=%s, best_of_128=%s, best_of_64=%s, best_of_32=%s, best_of_16=%s, best_of_8=%s, best_of_4=%s, best_of_2=%s, time_day_1=%s, time_day_2=%s, time_day_3=%s, time_day_4=%s, status=%s, previous_winner=%s WHERE tourn_id=%s",
                      GetSQLValueString($_POST['event'], "text"),
                      GetSQLValueString($_POST['tourn_year'], "date"),
                      GetSQLValueString($_POST['visible'], "text"),
                      GetSQLValueString($_POST['tourn_type'], "text"),
                      GetSQLValueString($_POST['ranking_type'], "text"),
                      GetSQLValueString($_POST['tourn_class'], "text"),
                      GetSQLValueString($_POST['how_seed'], "text"),
                      GetSQLValueString($_POST['top_seed'], "int"),
                      GetSQLValueString($_POST['matches_day_1'], "int"),
                      GetSQLValueString($_POST['matches_day_2'], "int"),
                      GetSQLValueString($_POST['matches_day_3'], "int"),
                      GetSQLValueString($_POST['matches_day_4'], "int"),
                      GetSQLValueString($_POST['best_of_128'], "int"),
                      GetSQLValueString($_POST['best_of_64'], "int"),
                      GetSQLValueString($_POST['best_of_32'], "int"),
                      GetSQLValueString($_POST['best_of_16'], "int"),
                      GetSQLValueString($_POST['best_of_8'], "int"),
                      GetSQLValueString($_POST['best_of_semis'], "int"),
                      GetSQLValueString($_POST['best_of_finals'], "int"),
                      GetSQLValueString($_POST['time_day_1'], "date"),
                      GetSQLValueString($_POST['time_day_2'], "date"),
                      GetSQLValueString($_POST['time_day_3'], "date"),
                      GetSQLValueString($_POST['time_day_4'], "date"),
                      GetSQLValueString($_POST['entries'], "text"),
                      GetSQLValueString($_POST['previous'], "text"),
                      GetSQLValueString($_POST['tourn_id'], "int"));
  //echo("Update Tourn " . $updateTournSQL . "<br>");
  $Result_copy = mysql_query($updateTournSQL, $connvbsa) or die(mysql_error());

  //echo("Page " . $_GET['page'] . "<br>");
  if($_GET['page'] == 'calendar')
  {
    $updateGoTo = "../Admin_Calendar/A_calendar_index.php";
  }
  elseif($_GET['page'] == 'tournament')
  {
    $updateGoTo = "../Admin_Tournaments/aa_tourn_index.php";
  }
  //$updateGoTo = "../Admin_Tournaments/aa_tourn_index.php";
  //echo($updateGoTo . "<br>");
  header(sprintf("Location: %s", $updateGoTo));
}

function GetMemberName($memberid)
{
    global $connvbsa;
    global $database_connvbsa;
    $query_member_name = 'Select FirstName, LastName FROM vbsa3364_vbsa2.members where MemberID = ' . $memberid;
    $result_member_name = mysql_query($query_member_name, $connvbsa) or die(mysql_error());
    $build_member_name = $result_member_name->fetch_assoc();
    $member_name = $build_member_name['FirstName'] . " " . $build_member_name['LastName'];
    return $member_name;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">


<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.css">
<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/skins/dhtmlxcalendar_dhx_skyblue.css">

<script src="../Admin_Calendar/calendar/codebase/dhtmlxcommon.js"></script>
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.js"></script>
</head>

<body>

<script type='text/javascript'>

window.onload = function() {
  doOnLoad();
}

function doOnLoad() {
  var mystartdate;  
  mystartdate = new dhtmlXCalendarObject("startdate");
  mystartdate.setSkin('dhx_skyblue');
  mystartdate.hideTime();
  mystartdate.hideWeekNumbers();
  mystartdate.setDateFormat("%Y-%m-%d");

  var myfinishdate;  
  myfinishdate = new dhtmlXCalendarObject("finishdate");
  myfinishdate.setSkin('dhx_skyblue');
  myfinishdate.hideTime();
  myfinishdate.hideWeekNumbers();
  myfinishdate.setDateFormat("%Y-%m-%d");

  var myclosedate;  
  myclosedate = new dhtmlXCalendarObject("closedate");
  myclosedate.setSkin('dhx_skyblue');
  myclosedate.hideTime();
  myclosedate.hideWeekNumbers();
  myclosedate.setDateFormat("%Y-%m-%d");
}
</script>
<script type='text/javascript'>

$(document).ready(function()
{
  $('.timepicker').timepicker({
    timeFormat: 'HH:mm',
    interval: 15,
    minTime: '08',
    dynamic: false,
    dropdown: true,
    scrollbar: true
  });
});
</script>
<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
  
  <table align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td align="center"><span class="red_bold">Edit selected tournament</span></td>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
  </table>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
    <table align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top">Event ID</td>
        <td>&nbsp;</td>
        <td><input type="text" name="event_id" value="<?php echo $row_tourn1['event_id']; ?>"></td>
        <td> </td>
      </tr>
       <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top">Tournament ID</td>
        <td>&nbsp;</td>
        <td><input type="text" name="tourn_id" value="<?php echo $row_tourn1['tourn_id']; ?>"></td>
        <td> </td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top">Name of event or Tournament</td>
        <td>&nbsp;</td>
        <td><input type="text" name="event" value="<?php echo htmlentities($row_tourn1['tourn_name'], ENT_COMPAT, 'utf-8'); ?>" size="50" /> 50 Characters Max <?= $row_tourn1['about'] ?></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap="nowrap">About</td>
        <td>&nbsp;</td>
        <td><?php
        // The initial value to be displayed in the editor.
        $CKEditor_initialValue = $row_Cal_edit['about'];
        $CKEditor = new CKEditor();
        $CKEditor->basePath = "../webassist/ckeditor/";
        $CKEditor_config = array();
        $CKEditor_config["wa_preset_name"] = "Normal";
        $CKEditor_config["wa_preset_file"] = "Normal.xml";
        $CKEditor_config["width"] = "100%";
        $CKEditor_config["height"] = "200px";
        $CKEditor_config["dialog_startupFocusTab"] = false;
        $CKEditor_config["fullPage"] = false;
        $CKEditor_config["tabSpaces"] = 4;
        $CKEditor_config["toolbar"] = array(
        array( 'Bold','Italic','Underline'),
        array( 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
        array( 'NumberedList','BulletedList','-','Outdent','Indent'),
        array( 'FontName','FontSize'),
        array( 'TextColor'));
        $CKEditor_config["contentsLangDirection"] = "ltr";
        $CKEditor_config["entities"] = false;
        $CKEditor_config["pasteFromWordRemoveFontStyles"] = false;
        $CKEditor_config["pasteFromWordRemoveStyles"] = false;
        $CKEditor->editor("about", $CKEditor_initialValue, $CKEditor_config);
      ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Venue:</td>
      <td>&nbsp;</td>
      <td><select name="venue">
        <?php
        // get list of venues
        $query_venue = 'Select * FROM vbsa3364_vbsa2.clubs order by ClubTitle';
        $result_venue = mysql_query($query_venue, $connvbsa) or die(mysql_error());
       if($row_tourn1['venue'] != '')
        {
          $selected = ' seleced';
        }
        else
        {
          $selected = '';
        }
        echo("<option value='" . $row_Cal_edit['venue'] . "' " . $selected . ">" . $row_Cal_edit['venue'] . "</option>");
        echo("<option value=''>&nbsp;</option>");
        echo("<option value='Multiple Venues'>Multiple Venues</option>");
        echo("<option value=''>--------------</option>");
        while($build_venue = $result_venue->fetch_assoc())
        {
           echo("<option value='" . $build_venue['ClubTitle'] . "'>" . $build_venue['ClubTitle'] . "</option>");
        }
        ?>
      </select></td>
    </tr>

    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Tournament Year:</td>
      <td>&nbsp;</td>
      <td><select name="tourn_year">
        <?php
        if($row_Cal_edit['tourn_year'] != '')
        {
          echo("<option value=" . $row_Cal_edit['tourn_year'] . " selected >" . $row_Cal_edit['tourn_year'] . "</option>");
        }
        ?>
        <!--<option value="<?= $row_Cal_edit['tourn_year'] ?>"><?= $row_Cal_edit['tourn_year'] ?></option>-->
        <option value="<?= date('Y') ?>"><?= date("Y") ?></option>
        <option value="<?= date('Y')+1 ?>"><?= date("Y")+1 ?></option>
        <option value="<?= date('Y')+2 ?>"><?= date("Y")+2 ?></option>
        <option value="<?= date('Y')+3 ?>"><?= date("Y")+3 ?></option>
      </select></td>
    </tr>

    <!--<tr valign="baseline">
      <td nowrap="nowrap" align="right">Tournament Year:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="tourn_year" value="<?php $newDate = date("Y", strtotime($row_tourn1['tourn_year'])); echo $newDate; ?>" size="5" readonly /> Can not be edited</td>
    </tr>-->

    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Start date:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="startdate" id="startdate" value="<?php echo $row_Cal_edit['startdate']; ?>" size="15" />
      <!--<input type="button" value="Select Date" onclick="displayDatePicker('startdate', false, 'ymd', '-');" />--></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Finish date:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="finishdate" id="finishdate" value="<?php echo $row_Cal_edit['finishdate']; ?>" size="15" />
      <!--<input type="button" value="Select Date" onclick="displayDatePicker('finishdate', false, 'ymd', '-');" />--></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Close date:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="closedate" id="closedate" value="<?php echo $row_Cal_edit['closedate']; ?>" size="15" />
        <!--<input type="button" value="Entries Close On" onclick="displayDatePicker('entry_close', false, 'ymd', '.');" />--></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">State:</td>
      <td>&nbsp;</td>
      <td><select name="state">
        <option value="" <?php if (!(strcmp("", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No Entry</option>
          <option value="ACT" <?php if (!(strcmp("ACT", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>ACT</option>
          <option value="NSW" <?php if (!(strcmp("NSW", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>NSW</option>
          <option value="NT" <?php if (!(strcmp("NT", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>NT</option>
          <option value="Qld" <?php if (!(strcmp("Qld", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Qld</option>
          <option value="SA" <?php if (!(strcmp("SA", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>SA</option>
          <option value="Tas" <?php if (!(strcmp("Tas", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Tas</option>
          <option value="Vic" <?php if (!(strcmp("Vic", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Vic</option>
          <option value="WA" <?php if (!(strcmp("WA", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>WA</option>
      </select></td>
    <tr>  
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Australian Ranking event</td>
      <td>&nbsp;</td>
      <td><select name="aust_rank">
        <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Cal_edit['aust_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", htmlentities($row_Cal_edit['aust_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
      </select></td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Is this event a tournament?</td>
      <td>&nbsp;</td>
      <td><select name="tourn">
       <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Cal_edit['tourn'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", htmlentities($row_Cal_edit['tourn'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Entries?</td>
      <td>&nbsp;</td>
      <td><select name="entries">
        <option value="Open" <?php if (!(strcmp("Open", htmlentities($row_tourn1['status'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Open</option>
            <option value="Closed" <?php if (!(strcmp("Closed", htmlentities($row_tourn1['status'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Closed</option>
      </select></td>
    </tr>
    <tr valign="baseline">
        <td nowrap="nowrap" align="right">Tournament Type: </td>
        <td>&nbsp;</td>
        <td><select name="tourn_type">
          <option value="Snooker" <?php if (!(strcmp("Snooker", htmlentities($row_tourn1['tourn_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Snooker</option>
            <option value="Billiards" <?php if (!(strcmp("Billiards", htmlentities($row_tourn1['tourn_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Billiards</option>
        </select></td>
    </tr>
    <tr valign="baseline">
          <td nowrap="nowrap" align="right">Victorian Ranking Points this Tournament Attracts <b>(used in Vic rankings calculation)</b></td>
           <td>&nbsp;</td>
           <td><select name="ranking_type">
            <option value="None" <?php if (!(strcmp("None", htmlentities($row_tourn1['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>None</option>
          <option value="No Entry" <?php if (!(strcmp("No Entry", htmlentities($row_tourn1['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No Entry</option>
            <option value="Victorian" <?php if (!(strcmp("Victorian", htmlentities($row_tourn1['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Victorian</option>
            <option value="Womens" <?php if (!(strcmp("Womens", htmlentities($row_tourn1['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Womens</option>
            <option value="Junior" <?php if (!(strcmp("Junior", htmlentities($row_tourn1['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Junior</option>
          </select></td>
        </tr>
        <tr valign="baseline">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
        <td colspan="2" align="left" class="red_text">
            "None" for tournaments that do not attract Victorian Ranking points. <br />
            "No Entry" for tournaments that do not attract Victorian Ranking points. <br />
            "Victorian" for tournaments that DO attract Victorian Ranking points. <br />
            "Junior" for tournaments that attract Victorian JUNIOR Ranking points. <br />
            "Womens" for tournaments that DO attract Victorian WOMENS Ranking points. <br />
        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Please Select a Class for this tournament:</td>
        <td>&nbsp;</td>
        <td><select name="tourn_class">
          <option value="Aust Rank" <?php if (!(strcmp("Aust Rank", htmlentities($row_tourn1['tourn_class'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Aust Rank</option>
            <option value="Victorian" <?php if (!(strcmp("Victorian", htmlentities($row_tourn1['tourn_class'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Victorian</option>
            <option value="Junior" <?php if (!(strcmp("Junior", htmlentities($row_tourn1['tourn_class'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Junior</option>
        </select></td>
    </tr>
    <?php 
    if($row_tourn1['move_top_seed'] != 0) 
    {
      $value = $row_tourn1['move_top_seed'];
      $selected =  " SELECTED";
    }
    else
    {
      $value = 0;
    }
    ?>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">How is this tournament seeded?</td>
      <td>&nbsp;</td>
      <td><select name="how_seed">
      <option value="NA" <?php if (!(strcmp("NA", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Not Applicable</option>
          <option value="Aust Rankings" <?php if (!(strcmp("Aust Rankings", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Aust Rankings</option>
          <option value="Vic Rankings" <?php if (!(strcmp("Vic Rankings", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Victorian Rankings</option>
          <option value="Aust Womens Rankings" <?php if (!(strcmp("Aust Womens Rankings", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Aust Womens Rankings</option>
          <option value="Vic Womens Rankings" <?php if (!(strcmp("Vic Womens Rankings", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Vic Womens Rankings</option>
          <option value="Junior Rankings" <?php if (!(strcmp("Junior Rankings", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Junior Rankings</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Visible - will be seen on the website:</td>
      <td>&nbsp;</td>
      <td><select name="visible">
        <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Cal_edit['visible'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", htmlentities($row_Cal_edit['visible'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
      </select></td>
    </tr>

<?php echo("Here<br>");?>
<tr><td colspan="3" style="height: 20px;"></td></tr> <!-- Blank line before -->
    <tr valign="baseline">
      <td colspan='3' align='center'><b>Used to create Tournament Draw</b></td>
</tr>

<table width="50%" border="0" align="center">
  <tr>
    <td>
      <table style="border: 1px solid black; width: 100%; padding: 10px;" cellpadding="5" cellspacing="0">

    <tr valign="baseline">
      <td nowrap="nowrap" align="right">If moving the top seeds, enter how many:</td>
      <td>&nbsp;</td>
      <td><select name="top_seed">
        <option value="<?= $value ?>" <?= $selected ?> ><?= $value ?></option>
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
      </select></td>
    </tr>
  </table>

    <table width="700" align="center" class="page">
    <tr valign="baseline">
      <td colspan='3' align='center'><b>Shows only when data is exported</b></td>
    </tr>
  </table>
  <table width="700" align="center" class="page">

    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Previous Winner</td>
      <td>&nbsp;</td>
      <td><select name="previous">
    <?php
    $sql_previous_winner = "Select tourn_memb_id FROM vbsa3364_vbsa2.tourn_entry where tournament_number = " . $row_tourn1['tourn_id'];
    $result_previous_winner = mysql_query($sql_previous_winner, $connvbsa) or die(mysql_error());
    if($row_tourn1['previous_winner'] == '')
    {
      echo("<option value=''>&nbsp;</option>");
    }
    else
    {
      echo("<option value='" . $row_tourn1['previous_winner'] . "'>" . GetMemberName($row_tourn1['previous_winner']) . "</option>");
    }
    echo("<option value=''>&nbsp;</option>");
    while($build_previous_winner = $result_previous_winner->fetch_assoc())
        echo("<option value='" . $build_previous_winner['tourn_memb_id'] . "'>" . GetMemberName($build_previous_winner['tourn_memb_id']) . "</option>");
    ?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Entered Players:</td>
      <td>&nbsp;</td>
      <td><?= $total_players ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Tournament Draw Size:</td>
      <td>&nbsp;</td>
      <td><?= $tourn_size ?></td>
    </tr>
    <!--<tr valign="baseline">
      <td nowrap="nowrap" align="right">Matches to Play:</td>
      <td>&nbsp;</td>
      <td><?= $no_of_matches ?></td>
    </tr>-->

    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Matches per Day (Maximum):</td>
      <td>&nbsp;</td>
      <td>Day 1&nbsp;<input type='text' name="matches_day_1" size='2' value="<?= $row_tourn1['matches_day_1'] ?>">&nbsp;Day 2&nbsp;<input type='text' name="matches_day_2" size='2' value="<?= $row_tourn1['matches_day_2'] ?>">&nbsp;Day 3&nbsp;<input type='text' name="matches_day_3" size='2' value="<?= $row_tourn1['matches_day_3'] ?>">&nbsp;Day 4&nbsp;<input type='text' name="matches_day_4" size='2' value="<?= $row_tourn1['matches_day_4'] ?>"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>

    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Default Start Times:</td>
      <td>&nbsp;</td>
      <td>Day 1&nbsp;<input type="text" class="timepicker" name="time_day_1" value="<?= $row_tourn1['time_day_1'] ?>" size="10" />
      &nbsp;Day 2&nbsp;<input type="text" class="timepicker" name="time_day_2" value="<?= $row_tourn1['time_day_2'] ?>" size="10" />
      &nbsp;Day 3&nbsp;<input type="text" class="timepicker" name="time_day_3" value="<?= $row_tourn1['time_day_3'] ?>" size="10" />
      &nbsp;Day 4&nbsp;<input type="text" class="timepicker" name="time_day_4" value="<?= $row_tourn1['time_day_4'] ?>" size="10" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>

<tr>
  <td colspan="3">
    <table width="100%" border="0">
      <tr>
        <td align="center" colspan="8"><b>Matches 'Best of' Tournament Size</b></td>
      </tr>
      <tr>
        <td align="center">128</td>
        <td align="center">64</td>
        <td align="center">32</td>
        <td align="center">16</td>
        <td align="center">8</td>
        <td align="center">Semi Finals</td>
        <td align="center">Grand Finals</td>
      </tr>
      <tr>
        <td align="center"><input type="text" name="best_of_128" value="<?= $row_tourn1['best_of_128'] ?>" size="10" /></td>
        <td align="center"><input type="text" name="best_of_64" value="<?= $row_tourn1['best_of_64'] ?>" size="10" /></td>
        <td align="center"><input type="text" name="best_of_32" value="<?= $row_tourn1['best_of_32'] ?>" size="10" /></td>
        <td align="center"><input type="text" name="best_of_16" value="<?= $row_tourn1['best_of_16'] ?>" size="10" /></td>
        <td align="center"><input type="text" name="best_of_8" value="<?= $row_tourn1['best_of_8'] ?>" size="10" /></td>
        <td align="center"><input type="text" name="best_of_semis" value="<?= $row_tourn1['best_of_4'] ?>" size="10" /></td>
        <td align="center"><input type="text" name="best_of_finals" value="<?= $row_tourn1['best_of_2'] ?>" size="10" /></td>
      </tr>
    </table>
  </td>
</tr>
</table>


    <tr>
      <td align="center" colspan="8">&nbsp;</td>
    </tr>
  </table>
  <br>
  <table width="700" align="center" class="page">
    <tr valign="baseline">
      <td colspan='3' align='center'><b>Shows only when data is exported</b></td>
    </tr>
  </table>
  <table width="700" style='border: 1px solid black; border-collapse: collapse' align="center" class="page">
    <tr valign="baseline">
      <td colspan='3' align='center'>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Special Dates:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="special_dates" value="<?= $row_Cal_edit['special_dates'] ?>" size="49" /></td>
    <tr>  
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Next Year Venue</td>
      <td>&nbsp;</td>
      <td><select name="next_venue">
        <?php
        // get list of venues
        $query_venue_2 = 'Select * FROM clubs order by ClubTitle';
        $result_venue_2 = mysql_query($query_venue_2, $connvbsa) or die(mysql_error());
        echo("<option value='" .  $row_Cal_edit['next_venue'] . "'>" . $row_Cal_edit['next_venue'] . "</option>");
        echo("<option value='Multiple Venues'>Multiple Venues</option>");
        echo("<option value=''>--------------</option>");
        while($build_venue_2 = $result_venue_2->fetch_assoc())
        {
           echo("<option value='" . $build_venue_2['ClubTitle'] . "'>" . $build_venue_2['ClubTitle'] . "</option>");
        }
        ?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Tournament Director</td>
      <td>&nbsp;</td>
      <td><input type="text" name="tourn_director" value="<?= $row_Cal_edit['tourn_director'] ?>" size="49" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Referee standard Match, Roving, Self (early rounds)</td>
      <td>&nbsp;</td>
      <td><input type="text" name="referee_early" value="<?= $row_Cal_edit['referee_early'] ?>" size="49" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Referee standard Match, Roving, Self (later rounds)</td>
      <td>&nbsp;</td>
      <td><input type="text" name="referee_later" value="<?= $row_Cal_edit['referee_later'] ?>" size="49" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current Year Trophy Numbers</td>
      <td>&nbsp;</td>
      <td><input type="text" name="current_trophy_numbers" value="<?= $row_Cal_edit['current_trophy_numbers'] ?>" size="10" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current Year Trophy Costs</td>
      <td>&nbsp;</td>
      <td><input type="text" name="current_trophy_costs" value="<?= $row_Cal_edit['current_trophy_costs'] ?>" size="10" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Next Year Trophy Numbers</td>
      <td>&nbsp;</td>
      <td><input type="text" name="next_trophy_numbers" value="<?= $row_Cal_edit['next_trophy_numbers'] ?>" size="10" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Next Year Trophy Costs</td>
      <td>&nbsp;</td>
      <td><input type="text" name="next_trophy_costs" value="<?= $row_Cal_edit['next_trophy_costs'] ?>" size="10" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Comments</td>
      <td>&nbsp;</td>
      <td valign='top'><textarea name="comments" rows="4" cols="50"><?= $row_Cal_edit['comments'] ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td colspan='3' align='center'>&nbsp;</td>
    </tr>
  </table>
  <br>
  <table align="center" class="page">
    <tr>
      <td colspan="3" class="red_text">Check any of these to add to the Footer </td>
    </tr>
    <tr>
      <td>VBSA</td>
      <td><input type="checkbox" name="footer1" value="Y" id="footer1" <?php if (!(strcmp(htmlentities($row_Cal_edit['footer1'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
      <td>To enter this event, pay your membership or make a payment to the VBSA please go to the payments page. Enquiries -  <a href="mailto:treasurer@vbsa.org.au">VBSA treasurer</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="checkbox" name="footer2" value="Y" id="footer2" <?php if (!(strcmp(htmlentities($row_Cal_edit['footer2'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
      <td>To check the VBSA have received your entry please go to <a href="http://www.vbsa.org.au/Tournaments/tournindex.php">&quot;VBSA Tournament entries&quot;</a> . Allow a couple of days for your entry to be processed and then check that your name is listed for the tournament.</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="57">Non VBSA</td>
      <td width="20"><input type="checkbox" name="footer3" value="Y" id="footer3" <?php if (!(strcmp(htmlentities($row_Cal_edit['footer3'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
      <td width="931"><p>Please Note: The VBSA do not accept entries for this event, please refer the entry form for details on how to enter</p></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="checkbox" name="footer4" value="Y" id="footer4" <?php if (!(strcmp(htmlentities($row_Cal_edit['footer4'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
      <td>Please go to the <a href="http://absc.com.au/results.aspx">ABSC Site</a> for results</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align='center'><input type="submit" value="Update Tournament" /></td>
    </tr>
  </table>
  <!--<input type="hidden" name="event_id" value="" />-->
  <input type="hidden" name="MM_insert" value="form1" />
  <input type="hidden" name="status" value="Open" />
</form>
</center>
</body>
</html>
