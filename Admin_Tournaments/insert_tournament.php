<?php require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

require_once("../webassist/ckeditor/ckeditor.php"); 

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

  if($_POST['startdate'] == '')
  {
    $year = date('Y');
  }
  else
  {
    $year = date('Y', strtotime($_POST['startdate']));
  }

  //$insert_tourn_SQL = sprintf("Insert INTO tournaments (tourn_name, site_visible, tourn_type, tourn_class, ranking_type, how_seed, tourn_year, move_top_seed, matches_day_1, matches_day_2, matches_day_3, matches_day_4, best_of_128, best_of_64, best_of_32, best_of_16, best_of_8, best_of_4, best_of_2, time_day_1, time_day_2, time_day_3, time_day_4, status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",

  $insert_tourn_SQL = sprintf("Insert INTO tournaments (tourn_name, site_visible, tourn_type, tourn_class, ranking_type, how_seed, tourn_year, status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['event'], "text"),
                       GetSQLValueString($_POST['visible'], "text"),
                       GetSQLValueString($_POST['tourn_type'], "text"),
                       GetSQLValueString($_POST['tourn_class'], "text"),
                       GetSQLValueString($_POST['ranking_type'], "text"),
                       GetSQLValueString($_POST['how_seed'], "text"),
                       GetSQLValueString($year, "date"),
                       /*
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
                       */
                       GetSQLValueString($_POST['entries'], "text"));
  //echo($insert_tourn_SQL . "<br>");
  //mysql_select_db($database_connvbsa, $connvbsa);
  $Result_copy = mysql_query($insert_tourn_SQL, $connvbsa) or die(mysql_error());

  // get next tourn_id
  $query_tourn_id = 'Select * FROM vbsa3364_vbsa2.tournaments order by tourn_id DESC Limit 1';
  $result_tourn_id = mysql_query($query_tourn_id, $connvbsa) or die(mysql_error());
  $build_tourn_id = $result_tourn_id->fetch_assoc();
  $last_id = $build_tourn_id['tourn_id'];

  //$about_text = nl2br($_POST['about']);
  $about_text = str_replace("\r\n", '', $_POST['about']);
  $insertSQL = sprintf("Insert INTO calendar (tourn_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate, about, tourn_type, tourn_class, tourn, visible, footer1, footer2, footer3, footer4, special_dates, next_venue, tourn_director, referee_early, referee_later, current_trophy_numbers, current_trophy_costs, next_trophy_numbers, next_trophy_costs, comments) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($last_id, "int"),
                       GetSQLValueString($_POST['event'], "text"),
                       GetSQLValueString($_POST['venue'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['aust_rank'], "text"),
                       GetSQLValueString($_POST['ranking_type'], "text"),
                       GetSQLValueString($_POST['startdate'], "date"),
                       GetSQLValueString($_POST['finishdate'], "date"),
                       GetSQLValueString($_POST['closedate'], "date"),
                       GetSQLValueString($about_text, "text"),
                       GetSQLValueString($_POST['tourn_type'], "text"),
                       GetSQLValueString($_POST['tourn_class'], "text"),
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
                       GetSQLValueString($_POST['comments'], "text"));
  //echo($insertSQL . "<br>");
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
  

  // need to add to non playing dates.
  $start = $_POST['startdate'];
  $end = $_POST['finishdate'];
  $summary = $_POST['event'];
  $description = 'NA';
  $type = 'vbsa';

  // check if event is already listed
  $query_event = 'Select SUMMARY FROM vbsa3364_vbsa2.tbl_ics_dates Where SUMMARY = "' . $summary . '"';
  $result_event = mysql_query($query_event, $connvbsa) or die(mysql_error());
  $date_rows = $result_event->num_rows;
  if($_POST['non_dates'] === 'Y')
  {
    if($date_rows == 0)
    {
      $days = (strtotime($end) - strtotime($start)) / (60 * 60 * 24);
      for($i = 0; $i <= $days; $i++)
      {
        $new_start = date('Y-m-d', strtotime($start . ' + ' . $i . ' days'));
        $sql = "Insert into tbl_ics_dates (DTSTART, SUMMARY, DESCRIPTION, Year, TYPE) VALUES
        ('" . $new_start . "', '" . addslashes($summary) . "', '" . addslashes($description) . "', " . $year . ", '" . $type . "')";
        $update = mysql_query($sql, $connvbsa) or die(mysql_error());
      }
    }
  }
  else if($_POST['non_dates'] === 'N')
  {
    if($date_rows > 0)
    {
      $days = (strtotime($end) - strtotime($start)) / (60 * 60 * 24);
      for($i = 0; $i <= $days; $i++)
      {
        $new_start = date('Y-m-d', strtotime($start . ' + ' . $i . ' days'));
        $sql = "Delete from tbl_ics_dates Where DTSTART = '" . $new_start . "' AND SUMMARY = '" . addslashes($summary) . "' AND DESCRIPTION = '" . addslashes($description) . "' AND Year = " . $year . " AND TYPE = '" . $type . "'";
        $update = mysql_query($sql, $connvbsa) or die(mysql_error());
      }
    }
  }


  // get event_id
  $sql_event_id = 'Select * FROM vbsa3364_vbsa2.calendar order by tourn_id DESC Limit 1';
  //echo($sql_event_id . "<br>");
  $result_event_id = mysql_query($sql_event_id, $connvbsa) or die(mysql_error());
  $build_event_id = $result_event_id->fetch_assoc();

  // update tournaments
  $query_event_id = 'Update tournaments Set event_id = ' . $build_event_id['event_id'] . ' Where tourn_id = ' . $last_id;
  //echo($query_event_id . "<br>");
  $result_event = mysql_query($query_event_id, $connvbsa) or die(mysql_error());

  if($_GET['page'] == 'calendar')
  {
    $insertGoTo = "../Admin_Calendar/A_calendar_index.php";
  }
  elseif($_GET['page'] == 'tournament')
  {
    $insertGoTo = "../Admin_Tournaments/aa_tourn_index.php";
  }
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $insertGoTo));
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
    <td align="center"><span class="red_bold">Insert a new event into the tournament and calendar tables</span></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><span class="red_bold">If a &quot;Start Date&quot; is not set, or, &quot;Visible is set to &quot;No&quot; then event will not appear in the calendar</span></td>
  </tr>
  <tr>
    <td colspan="2" align="center">For events that do not have a start date, start date is not set to the current year or &quot;visible&quot; is set to &quot;No&quot; please go to the Archives</td>
  </tr>
  <tr>
    <td colspan="2" align="center">For events that have the start date set for next year go to next years events</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
<table width="800" align="center" class="page">
  <tr valign="baseline">
    <td nowrap="nowrap" align="right" valign="top">Tournament ID</td>
    <td>&nbsp;</td>
    <td><input type="text" name="tourn_id" value="" size="10" readonly/> Auto Generated on Save</td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right" valign="top">Name of event or Tournament</td>
    <td>&nbsp;</td>
    <td><input type="text" name="event" value="" size="50" /> 50 Characters Max</td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="top" nowrap="nowrap">About</td>
    <td>&nbsp;</td>
    <td><?php
    // The initial value to be displayed in the editor.
    $CKEditor_initialValue = "";
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
    <td nowrap="nowrap" align="right">Start date:</td>
    <td>&nbsp;</td>
    <td><input type="text" name="startdate" id="startdate" value="" size="15" /></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Finish date:</td>
    <td>&nbsp;</td>
    <td><input type="text" name="finishdate" id="finishdate" value="" size="15" /></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Close date:</td>
    <td>&nbsp;</td>
    <td><input type="text" name="closedate" id="closedate" value="" size="15" /></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">State:</td>
    <td>&nbsp;</td>
    <td><select name="state">
      <option value="">No Entry</option>
      <option value="ACT">ACT</option>
      <option value="NSW">NSW</option>
      <option value="NT">NT</option>
      <option value="Qld">Qld</option>
      <option value="SA">SA</option>
      <option value="Tas">Tas</option>
      <option value="Vic" selected="SELECTED">Vic</option>
      <option value="WA">WA</option>
    </select></td>
  <tr>  
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Australian Ranking event</td>
    <td>&nbsp;</td>
    <td><select name="aust_rank">
      <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
      <option selected="selected" value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
    </select></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Is this event a tournament?</td>
    <td>&nbsp;</td>
    <td><select name="tourn">
      <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
      <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
    </select></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Entries?</td>
    <td>&nbsp;</td>
    <td><select name="entries">
      <option value="Open">Open</option>
      <option value="Closed" selected="SELECTED">Closed</option>
    </select></td>
  </tr>
  <tr valign="baseline">
      <td nowrap="nowrap" align="right">Tournament Type: </td>
      <td>&nbsp;</td>
      <td><select name="tourn_type">
        <option value="Both" <?php if (!(strcmp("Both", htmlentities($row_tourn1['tourn_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Both</option>
        <option value="Snooker" selected="selected" <?php if (!(strcmp("Snooker", ""))) {echo "SELECTED";} ?>>Snooker</option>
        <option value="Billiards" <?php if (!(strcmp("Billiards", ""))) {echo "SELECTED";} ?>>Billiards</option>
      </select></td>
  </tr>
  <tr valign="baseline">
        <td nowrap="nowrap" align="right">What Victorian Ranking Points does this Tournament Attract?</td>
        <td>&nbsp;</td>
        <td><select name="ranking_type">
          <option value="No Entry" selected="selected" <?php if (!(strcmp("", ""))) {echo "SELECTED";} ?>>No Entry</option>
          <option value="Victorian" <?php if (!(strcmp("Victorian", ""))) {echo "SELECTED";} ?>>Victorian</option>
          <option value="Womens" <?php if (!(strcmp("Womens", ""))) {echo "SELECTED";} ?>>Womens</option>
          <option value="Junior" <?php if (!(strcmp("Junior", ""))) {echo "SELECTED";} ?>>Junior</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td>&nbsp;</td>
        <td colspan="2" align="left" class="red_text">"No Entry" for tournaments that do not attract Victorian Ranking points. <br />
          "Victorian" for tournaments that DO attract Victorian Ranking points. <br />
          "Junior" for tournaments that attract Victorian JUNIOR Ranking points. <br />
          "Womens" for tournaments that DO attract Victorian WOMENS Ranking points. <br />
         </td>
      </tr>
      <tr valign="baseline">
      <td nowrap="nowrap" align="right">Please Select a Class for this tournament:</td>
      <td>&nbsp;</td>
      <td><select name="tourn_class">
        <option value="Victorian" <?php if (!(strcmp("Victorian", ""))) {echo "SELECTED";} ?>>Victorian</option>
        <option value="Aust Rank" <?php if (!(strcmp("Aust Rank", ""))) {echo "SELECTED";} ?>>Aust Rank</option>
        <option value="Junior" <?php if (!(strcmp("Junior", ""))) {echo "SELECTED";} ?>>Junior</option>
      </select></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">How is this tournament seeded?</td>
    <td>&nbsp;</td>
    <td><select name="how_seed">
    <option value="NA" <?php if (!(strcmp("NA", ""))) {echo "SELECTED";} ?>>Not Applicable</option>
      <option value="Aust Rankings" <?php if (!(strcmp("Aust Rankings", ""))) {echo "SELECTED";} ?>>Aust Rankings</option>
      <option value="Vic Rankings" <?php if (!(strcmp("Vic Rankings", ""))) {echo "SELECTED";} ?>>Victorian Rankings</option>
      <option value="Aust Womens Rankings" <?php if (!(strcmp("Aust Womens Rankings", ""))) {echo "SELECTED";} ?>>Aust Womens Rankings</option>
      <option value="Vic Womens Rankings" <?php if (!(strcmp("Vic WomensRankings", ""))) {echo "SELECTED";} ?>>Victorian Womens Rankings</option>
      <option value="Junior Rankings" <?php if (!(strcmp("Junior Rankings", ""))) {echo "SELECTED";} ?>>Junior Rankings</option>
    </select></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Visible - will be seen on the website:</td>
    <td>&nbsp;</td>
    <td><select name="visible">
      <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
      <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
    </select></td>
  </tr>
  <tr valign="baseline">
      <td nowrap="nowrap" align="right">Add to Non Playing Dates:</td>
      <td>&nbsp;</td>
      <td><select name="non_dates">
        <option value="N" <?php if (!(strcmp("N", ''))) {echo "SELECTED";} ?>>No</option>
        <option value="Y" <?php if (!(strcmp("Y", ''))) {echo "SELECTED";} ?>>Yes</option>
      </select></td>
    </tr>
  <tr valign="baseline">
    <td colspan="8" nowrap="nowrap" align="right">&nbsp;</td>
  </tr>
</table>
<br>
<table width="700" align="center" class="page">
  <tr valign="baseline">
    <td colspan='3' align='center'><b>Shows only when data is exported</b></td>
  </tr>
</table>
<table width="800" style='border: 1px solid black; border-collapse: collapse' align="center" class="page">
  <tr valign="baseline">
    <td colspan='3' align='center'>&nbsp;</td>
  </tr>
  <!--<tr valign="baseline">
    <td nowrap="nowrap" align="right">Special Dates:</td>
    <td>&nbsp;</td>
    <td><input type="text" name="special_dates" value="" size="49" /></td>
  <tr>-->  
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Next Year Venue</td>
    <td>&nbsp;</td>
    <td><select name="next_venue">
      <?php
      // get list of venues
      $query_venue_2 = 'Select * FROM clubs order by ClubTitle';
      $result_venue_2 = mysql_query($query_venue_2, $connvbsa) or die(mysql_error());
      echo("<option value=''>&nbsp;</option>");
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
    <td><input type="text" name="tourn_director" value="" size="49" /></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Referee standard Match, Roving, Self (early rounds)</td>
    <td>&nbsp;</td>
    <td><input type="text" name="referee_early" value="" size="49" /></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Referee standard Match, Roving, Self (later rounds)</td>
    <td>&nbsp;</td>
    <td><input type="text" name="referee_later" value="" size="49" /></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Current Year Trophy Numbers</td>
    <td>&nbsp;</td>
    <td><input type="text" name="current_trophy_numbers" value="" size="10" /></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Current Year Trophy Costs</td>
    <td>&nbsp;</td>
    <td><input type="text" name="current_trophy_costs" value="" size="10" /></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Next Year Trophy Numbers</td>
    <td>&nbsp;</td>
    <td><input type="text" name="next_trophy_numbers" value="" size="10" /></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Next Year Trophy Costs</td>
    <td>&nbsp;</td>
    <td><input type="text" name="next_trophy_costs" value="" size="10" /></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Comments</td>
    <td>&nbsp;</td>
    <td valign='top'><textarea name="comments" rows="4" cols="50"></textarea></td>
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
    <td><input type="checkbox" name="footer1" value="" checked/></td>
    <td>To enter this event, pay your membership or make a payment to the VBSA please go to the <a href="https://vbsa.org.au/vbsa_shop/shop_cart.php">payments page</a>. Enquiries - <a href="mailto:treasurer@vbsa.org.au">VBSA treasurer </a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="checkbox" name="footer2" value="" checked/></td>
    <td>To check the VBSA have received your entry please go to <a href="https://vbsa.org.au/Tournaments/tourn_index.php">&quot;VBSA Tournament entries&quot;</a> . Allow a couple of days for your entry to be processed and then check that your name is listed for the tournament.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="57">Non VBSA</td>
    <td width="20"><input type="checkbox" name="footer3" value="" /></td>
    <td width="931"><p>Please Note: The VBSA do not accept entries for this event, please refer the entry form fo details on how to enter</p></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="checkbox" name="footer4" value="" /></td>
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
    <td align='center'><input type="submit" value="Insert Tournament" /></td>
  </tr>
</table>
<input type="hidden" name="event_id" value="" />
<input type="hidden" name="MM_insert" value="form1" />
<input type="hidden" name="status" value="Open" />
</form>
</center>
</body>
</html>
