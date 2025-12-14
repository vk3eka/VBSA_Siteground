<?php 

// created by gemini 2.4 AI 19-0702025

require_once('../Connections/connvbsa.php');
require_once("../vbsa_online_scores/icalendar/zapcallib.php");

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['year'])) {
  $year = $_GET['year'];
}
else
{
  $year = date("Y");
}
if (isset($_GET['season'])) {
  $season = $_GET['season'];
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
      //case "long":
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

if(isset($_POST['ButtonName']) && (($_POST['ButtonName']) == 'UpdateDates'))
{
  $start = $_POST['Start'];
  $end = $_POST['End'];
  $summary = $_POST['Summary'];
  $description = $_POST['Description'];
  $type = 'vbsa';

  $days = (strtotime($end) - strtotime($start)) / (60 * 60 * 24);
  for($i = 0; $i <= $days; $i++)
  {
    $new_start = date('Y-m-d', strtotime($start . ' + ' . $i . ' days'));
    $sql = "Insert into tbl_ics_dates (DTSTART, DTEND, SUMMARY, DESCRIPTION, Year, TYPE) VALUES
    ('" . $new_start . "', '" . $end . "', '" . addslashes($summary) . "', '" . addslashes($description) . "', " . $year . ", '" . $type . "')";
      $update = mysql_query($sql, $connvbsa) or die(mysql_error());
  }
}

if(isset($_POST['ButtonName']) && (($_POST['ButtonName']) == 'UpdateChanges'))
{
  $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
  for ($i = 0; $i < count($packeddata); $i++) 
  {
      $dates = explode(", ", $packeddata[$i]);
      $sql = "Update tbl_ics_dates Set ok_to_use = " . $dates[1] . " Where id = " . $dates[0];
      $update = mysql_query($sql, $connvbsa) or die(mysql_error());
  }
}

?>

<script type='text/javascript'>

function doOnLoad() {
  var mystartdate;  
  mystartdate = new dhtmlXCalendarObject("startdate");
  mystartdate.setSkin('dhx_skyblue');
  mystartdate.hideTime();
  mystartdate.hideWeekNumbers();
  mystartdate.setDateFormat("%Y-%m-%d");

  var myenddate;  
  myenddate = new dhtmlXCalendarObject("enddate");
  myenddate.setSkin('dhx_skyblue');
  myenddate.hideTime();
  myenddate.hideWeekNumbers();
  myenddate.setDateFormat("%Y-%m-%d");
}

window.onload = function() 
{
    doOnLoad();
}

function UpdateDates() 
{
  document.ics_data.ButtonName.value = 'UpdateDates'; 
  document.ics_data.Summary.value = document.getElementById('summary').value;
  document.ics_data.Start.value = document.getElementById('startdate').value;
  document.ics_data.End.value = document.getElementById('enddate').value;
  document.ics_data.Description.value = document.getElementById('description').value;
  alert("Updates saved.");
  document.ics_data.submit();
}

function UpdateChanges(no_of_dates) 
{
  var transferdata = {};
  var donotuse_chk = 0;
  for (var i = 0; i < no_of_dates; i++) { // get number of dates in list
      id = document.getElementById("ID_" + i).value;
      if (document.getElementById("donotuse_" + i).checked == true) {
          donotuse_chk = 1;
      }
      else
      {
          donotuse_chk = 0;
      }
      transferdata[i] = id + ", " + donotuse_chk;
  }
  var data = JSON.stringify(transferdata);
  document.ics_data.PackedData.value = data;  
  document.ics_data.ButtonName.value = 'UpdateChanges'; 
  alert("Changes saved.");
  document.ics_data.submit();
}

</script>

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

<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.css">
<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/skins/dhtmlxcalendar_dhx_skyblue.css">
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcommon.js"></script>
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.js"></script>

</head>
<body>
<form action='non_playable_dates.php' method='post' enctype='multipart/form-data' name='ics_data'>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table align="center">
  <tr>
    <td colspan=3 class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan=3 class="red_bold" align="center">View/Edit/Upload Non-Usable Dates.</td>
  </tr>
  <tr>
    <td colspan=3 class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan=2>&nbsp;</td>
    <td align="center" nowrap="nowrap" class="greenbg" style='width:350px'><a href="AA_scores_index_grades.php?season=S2">Return to opening page</a></td>
    </tr>
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
</table>
<?php

// Helper function to save a parsed event to the database
function save_event_to_db($event, $connvbsa, $year) 
{
    if (!isset($event['DTSTART']) || !isset($event['SUMMARY'])) {
        echo "<p style='color:orange;'>Skipping event with missing DTSTART or SUMMARY.</p>";
        return;
    }

    // Sanitize data
    $summary = mysql_real_escape_string(trim($event['SUMMARY']));
    $description = isset($event['DESCRIPTION']) ? mysql_real_escape_string(trim($event['DESCRIPTION'])) : '';
    $uid = isset($event['UID']) ? mysql_real_escape_string(trim($event['UID'])) : '';
    $dtstart = trim($event['DTSTART']);
    $dtend = trim($event['DTEND']);

    // Parse the start date. Handle YYYYMMDD and YYYYMMDDTHHMMSS formats.
    try {
        $date_part = substr($dtstart, 0, 8);
        $date_obj = new DateTime($date_part);
        $formatted_date = $date_obj->format('Y-m-d');
    } catch (Exception $e) {
        echo "<p style='color:red;'>Error parsing date '" . htmlspecialchars($dtstart) . "'. Skipping event.</p>";
        return;
    }
    // Parse the end date. Handle YYYYMMDD and YYYYMMDDTHHMMSS formats.
    try {
        $date_end = substr($dtend, 0, 8);
        $end_date = strtotime($date_end . ' - 1 days');
        $formatted_end_date = date('Y-m-d', $end_date);
        if($formatted_end_date == '')
        {
          $formatted_end_date = $date_obj->format('Y-m-d');
        }
    } catch (Exception $e) {
        echo "<p style='color:red;'>Error parsing date '" . htmlspecialchars($dtend) . "'. Skipping event.</p>";
        return;
    }

    // Build and execute query
    $sql = "Insert INTO tbl_ics_dates (DTSTART, DTEND, SUMMARY, DESCRIPTION, UID, Year, TYPE, ok_to_use) VALUES ("
         . "'" . $formatted_date . "', "
         . "'" . $formatted_end_date . "', "
         . "'" . $summary . "', "
         . "'" . $description . "', "
         . "'" . $uid . "', "
         . intval($year) . ", "
         . "'Public', "
         . "0)"; // Default to not usable, as in original logic

    $result = mysql_query($sql, $connvbsa);
    if ($result) {
        //echo "Successfully imported: " . htmlspecialchars(trim($event['SUMMARY'])) . " on " . $formatted_date . "<br>";
    } else {
        echo "<p style='color:red;'>Database error for event '" . htmlspecialchars($summary) . "': " . mysql_error() . "</p>";
    }
}

// Check if a file was uploaded
if (isset($_FILES['ics_file']) && $_FILES['ics_file']['error'] === UPLOAD_ERR_OK) {
    $file_tmp_name = $_FILES['ics_file']['tmp_name'];
    $file_name = $_FILES['ics_file']['name'];
    $file_size = $_FILES['ics_file']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if ($file_ext !== 'ics') {
        echo "<p style='color:red;'><b>Error:</b> Please upload a valid .ics file.</p>";
    } else if ($file_size > 1024000) { // 1MB
        echo "<p style='color:red;'><b>Error:</b> File is too large (max 1MB).</p>";
    } else {
        //echo "<div align='center' style='border: 1px solid #ccc; padding: 10px; margin: 20px auto; width: 80%;'>";
        //echo "<h3>Importing from " . htmlspecialchars($file_name) . "</h3>";
        
        global $connvbsa, $year;

        // Delete existing public holidays for the selected year to prevent duplicates
        $sql_delete = "Delete FROM tbl_ics_dates WHERE year = " . intval($year) . " AND TYPE = 'Public'";
        $result_delete = mysql_query($sql_delete, $connvbsa);
        if (!$result_delete) {
            die("Error clearing old data: " . mysql_error());
        }
        //echo "Cleared existing public holiday data for " . intval($year) . ".<br><hr>";

        // Process the new file
        $lines = file($file_tmp_name, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            die("Error: Could not read the uploaded file.");
        }

        $event = null;
        $last_key = '';

        foreach ($lines as $line) {
            if (trim($line) === '') continue;

            // Handle folded lines (RFC 5545 section 3.1)
            if (isset($line[0]) && ($line[0] == ' ' || $line[0] == "	")) {
                if ($event !== null && $last_key !== '') {
                    $event[$last_key] .= substr($line, 1);
                }
                continue;
            }

            if (strcasecmp(trim($line), 'BEGIN:VEVENT') == 0) {
                $event = [];
                $last_key = '';
                continue;
            }

            if (strcasecmp(trim($line), 'END:VEVENT') == 0) {
                if ($event !== null) {
                    save_event_to_db($event, $connvbsa, $year);
                    $event = null; // Reset for next event
                }
                continue;
            }

            if ($event !== null) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $key_parts = explode(';', $parts[0]);
                    $key = $key_parts[0];
                    $value = $parts[1];
                    $event[$key] = $value;
                    $last_key = $key;
                }
            }
        }
        
        //echo "<hr>";
        //echo "<p style='color:green; font-weight:bold;'>File processing complete.</p>";
        //echo "<p><a href='" . $_SERVER['PHP_SELF'] . "?year=" . intval($year) . "'>Click here to refresh and see the imported dates.</a></p>";
        //echo "</div>";
    }
} else if (isset($_FILES['ics_file']) && $_FILES['ics_file']['error'] !== UPLOAD_ERR_NO_FILE) {
    // Handle other upload errors
    $error_code = $_FILES['ics_file']['error'];
    $upload_errors = [
        UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
        UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
        UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded.',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
    ];
    echo "<p style='color:red;'><b>Upload Error:</b> " . ($upload_errors[$error_code] ?? 'An unknown error occurred.') . "</p>";
} else {
    // Display the upload form if no file has been submitted yet
    echo("<center>");
    echo("<table border='0' align='center' cellpadding='0' cellspacing='0'>");
    echo("<tr>");
    echo("<td align=center><h2>Upload .ics file</h2></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>Download Victorian Public Holiday dates ics file at <a href='https://www.vic.gov.au/ical#public-holiday-dates' target='_blank'>https://www.vic.gov.au/ical#public-holiday-dates</a>.</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>Select the ics file to upload from the Choose File button.</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>Click 'Upload' to upload the file.</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><input type='file' name='ics_file' size='25' /></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    //echo("<td align=center><input type='submit' name='submit' value='Upload' /></td>");
    echo("<td align=center><input type='submit' value='Upload' /></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><b><font color=red>Please note: any existing public holiday dates for " . $year . " will be deleted.</b></font></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><b>Check 'OK to use!' if date is avaiable for a match.</b></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("</table>");
    echo("</center>");
}

function FormatDate($date_string)
{
  $yyyy = substr($date_string,0,4);
  $mm = substr($date_string,4,2);
  $dd = substr($date_string,6,8);
  $today = $yyyy . '-' . $mm . '-' . $dd;

  return $today;
}

//$sql = "Select distinct DTSTART, DTEND, SUMMARY, DESCRIPTION, ok_to_use From tbl_ics_dates Where Year(DTSTART) >= YEAR(CURDATE() - 1) and Year(DTSTART) <= YEAR(CURDATE() + 1) Group By DTSTART Order By DTSTART";
//Select * From tbl_ics_dates where (Year(DTSTART) = 2024 OR Year(DTSTART) = 2025 OR Year(DTSTART) = 2026) and Year = 2025
$sql = "Select * FROM vbsa3364_vbsa2.tbl_ics_dates where (Year(DTSTART) = " . ($year) . " OR Year(DTSTART) = " . ($year+1) . ") and Year = " . $year . " Order By DTSTART";
$result_date = mysql_query($sql, $connvbsa) or die(mysql_error());
?>
<input type='hidden' name='ButtonName' />
<input type='hidden' name='Start' />
<input type='hidden' name='End' />
<input type='hidden' name='Summary' />
<input type='hidden' name='Description' />
<input type='hidden' name='DoNotUse' />
<input type='hidden' name='PackedData' />
<table align='center' class='table table-striped table-bordered dt-responsive nowrap display' width='1200' border=1>
<thead> 
  <tr> 
    <th class='text-center' style='width : 150px;'>Start Date</th>
    <!--<th class='text-center' style='width : 70px;'>End Date</th>-->
    <th class='text-center' style='width : 200px;'>Summary</th>
    <th class='text-center' style='width : 450px;'>Description</th>
    <th class='text-center' style='width : 50px;'>OK to use!</th>
  </tr>
</thead>
<tbody>
<?php 
$i = 0;
while ($build_data = mysql_fetch_assoc($result_date))
{ 
      $date = new DateTime($build_data['DTSTART']);
      $start_date = $date->format("l Y-m-d");
?>
  <input type='hidden' name='ID' id='ID_<?= $i ?>' value='<?= $build_data['id'] ?>' />
  <tr> 
    <td align='center'><?= $start_date ?></td>
    <?php 
    if($build_data['TYPE'] === 'vbsa')
    {
      //echo("<td align='center'>" . $build_data['DTEND'] . "</td>");
    }
    else
    {
      //echo("<td align='center'>&nbsp;</td>");
    }
    ?>
    <!--<td align='center'><?= $build_data['DTEND'] ?></td>-->
    <td align='center'><?= $build_data['SUMMARY'] ?></td>
    <td align='center'><?= $build_data['DESCRIPTION'] ?></td>
    <?php
    if($build_data['ok_to_use'] == 1)
    {
      echo("<td align='center'><input type='checkbox' id='donotuse_" . $i . "' checked></td>");
    }
    else
    {
      echo("<td align='center'><input type='checkbox' id='donotuse_" . $i . "'></td>");
    }
    ?>
  </tr>
<?php  
  $i++;
}
?>
  <tr> 
    <td colspan=4 align="center"><input type="button" onclick='UpdateChanges(<?= $i ?>);' value="Save 'OK to use!' Changes"/></td>
  </tr>
</tbody>  
</table>
<br><br><br>
<table align='center' class='table table-striped table-bordered dt-responsive nowrap display' width='800' border=1>
<thead> 
  <tr> 
    <th class='text-center' style='width : 80px;'>Start Date</th>
    <!--<th class='text-center' style='width : 80px;'>End Date</th>-->
    <th class='text-center' style='width : 100px;'>Summary</th>
    <th class='text-center' style='width : 200px;'>Description</th>
  </tr>
</thead>
<tbody>
  <tr> 
    <td align='center'><input type="text" id="startdate" value="" size="20"/></td>
    <!--<td align='center'><input type="text" id="enddate" value="" size="20"/></td>-->
    <td align='center'><input type='text' id='summary' value=''></td>
    <td align='center'><input type='text' id='description' value=''></td>
  </tr>
  <tr> 
    <td colspan=4 align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan=4 align="center"><input type="button" onclick='UpdateDates();' value="Add New Non Public Date"/></td>
  </tr>
</tbody>  
</table>
</form>
</body>
</html>
