<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once("../vbsa_online_scores/icalendar/zapcallib.php");

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
//echo($year . "<br>");

//$page = "../team_entries.php";
//$_SESSION['page'] = $page;


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
  $summary = $_POST['Summary'];
  $description = $_POST['Description'];
  $type = 'vbsa';

  $sql = "Insert into tbl_ics_dates (DTSTART, DTEND, SUMMARY, DESCRIPTION, Year, TYPE) VALUES
('" . $start . "', '" . $start . "', '" . addslashes($summary) . "', '" . addslashes($description) . "', " . $year . ", '" . $type . "')";
  $update = mysql_query($sql, $connvbsa) or die(mysql_error());
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

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table align="center">
  <tr>
    <td colspan=3 class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan=3 class="red_bold">View/Edit/Upload Non-Usable Dates.</td>
  </tr>
  <tr>
    <td colspan=3 class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan=2>&nbsp;</td>
    <td align="right" nowrap="nowrap" class="greenbg" style='width:350px'><a href="AA_scores_index_grades.php?season=S2">Return to opening page</a></td>
    </tr>
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
</table>
<?php

function ImportExcel($filename, $year) 
{
  echo("Import Function<br>");
  global $connvbsa;
  $data_exists = false;

  // check if any data for this season exists
  $sql = "Select * From tbl_ics_dates Where Year = " . $year;
  $result_date = mysql_query($sql, $connvbsa) or die(mysql_error());
  $build_data = mysql_fetch_assoc($result_date);
  $num_rows = mysql_num_rows($result_date);
  if($num_rows > 0)
  {
      $data_exists = true;
  } 
  if($data_exists)
  {
      $sql_delete = "Delete from tbl_ics_dates Where year = " . $year . " and TYPE = 'public'";
      $update_delete = mysql_query($sql_delete, $connvbsa) or die(mysql_error());
      if(! $update_delete )
      {
        die("Could not delete data: " . mysqli_error($dbcnx_client));
      }
  }
  else
  {
    $icalfeed = file_get_contents($filename);
    // create the ical object
    $icalobj = new ZCiCal($icalfeed);
    // read back icalendar data that was just parsed
    if(isset($icalobj->tree->child))
    {
      foreach($icalobj->tree->child as $node)
      {
        if($node->getName() == "VEVENT")
        {
          $sql_insert = "Insert into tbl_ics_dates (";
          foreach($node->data as $key => $value)
          {
            if($key != '')
            {
              $sql_insert .=  addslashes($key) . ", ";
            }
          }
          $sql_insert .= "TYPE, Year)";
          $sql_insert .= " Values (";
          foreach($node->data as $key => $value)
          {
            if($value->getValues() != '')
            {
              if(($key == 'DTSTART') OR ($key == 'DTEND'))
              {
                $date = FormatDate($value->getValues());
                $sql_insert .=  "'" . $date . "', ";
              }
              else
              {
                $sql_insert .=  "'" . addslashes($value->getValues()) . "', "; 
              }
            }
          }
          $sql_insert .= "'Public', 2024)";
          $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
          if(!$update )
          {
            die("Could not insert data into settings: " . mysqli_error($dbcnx_client));
          }
        }
      }
    }
  }
  header("Location: non_playable_dates.php");

}

//if they DID upload a file...
if(isset($_FILES['ics_file']['name']))
{
  echo("File Exists<br>");
    if(!$_FILES['ics_file']['error'])
    {
        $new_file_name = strtolower($_FILES['ics_file']['tmp_name']); //rename file
        if($_FILES['ics_file']['size'] > (1024000)) //can't be larger than 1 MB
        {
            $valid_file = false;
            echo('Your file\'s size is too large.' . "<br>");
        }
        else
        {
            $valid_file = true;
        }
        if($valid_file)
        {
            $sql_ics = "Select UID From tbl_ics_dates Where Year = " . $year . " and TYPE = 'public'";
            $result_ics = mysql_query($sql_ics, $connvbsa) or die(mysql_error());
            $ics_data_exists = false;
            while($build_ics_data = mysql_fetch_assoc($result_ics))
            {
                if($build_ics_data['UID'] != '')
                {
                    $ics_data_exists = true;
                    break;
                }
            }
            $ics_data_exists = false; // temp setting until in production
            if($ics_data_exists === true)
            {
                echo("<center>");
                echo("<table border='0' align='center' cellpadding='0' cellspacing='0'>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>The dates list of public holidays already contains public holidays data.</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>The existng public hoilday dates will be deleted.</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("</table>");
                echo("</center>");
            }
            else
            {
                ImportExcel($_FILES['ics_file']['tmp_name'], $year);
                echo('<br><br><center>Your file '. $_FILES['ics_file']['name'] . " has been uploaded and imported.</center><br>");
            }
        }
    }
    else
    {
        echo('Your upload triggered the following error:  '. $_FILES['ics_file']['error'] . "<br>");
    }
}
else
{
    echo("<center>");
    echo("<table border='0' align='center' cellpadding='0' cellspacing='0'>");
    echo("<tr>");
    echo("<td align=center><h2>Upload .ics file</h2></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>Download Victorian Public Holiday dates ics file at <a href='https://www.vic.gov.au/ical#public-holiday-dates'>https://www.vic.gov.au/ical#public-holiday-dates</a>.</td>");
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
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><input type='file' name='ics_file' size='25' /></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
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
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><b><font color=red>Please note: any existing public holiday dates for " . $year . " will be deleted.</b></font></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
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
    //echo("</form>");
}

function FormatDate($date_string)
{
  $yyyy = substr($date_string,0,4);
  $mm = substr($date_string,4,2);
  $dd = substr($date_string,6,8);
  $today = $yyyy . '-' . $mm . '-' . $dd;

  return $today;
}

$sql = "Select * From tbl_ics_dates Where Year(DTSTART) >= YEAR(CURDATE() - 1) order by DTSTART";
$result_date = mysql_query($sql, $connvbsa) or die(mysql_error());
?>
<input type='hidden' name='ButtonName' />
<input type='hidden' name='Start' />
<input type='hidden' name='Summary' />
<input type='hidden' name='Description' />
<input type='hidden' name='DoNotUse' />
<input type='hidden' name='PackedData' />
<table align='center' class='table table-striped table-bordered dt-responsive nowrap display' width='900' border=1>
<thead> 
  <tr> 
    <th class='text-center' style='width : 70px;'>Date</th>
    <th class='text-center' style='width : 150px;'>Summary</th>
    <th class='text-center' style='width : 300px;'>Description</th>
    <th class='text-center' style='width : 50px;'>OK to use!</th>
  </tr>
</thead>
<tbody>
<?php 
$i = 0;
while ($build_data = mysql_fetch_assoc($result_date))
{ 
?>
  <input type='hidden' name='ID' id='ID_<?= $i ?>' value='<?= $build_data['id'] ?>' />
  <tr> 
    <td align='center'><?= $build_data['DTSTART'] ?></td>
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
    <th class='text-center' style='width : 80px;'>Date</th>
    <th class='text-center' style='width : 100px;'>Summary</th>
    <th class='text-center' style='width : 200px;'>Description</th>
  </tr>
</thead>
<tbody>
  <tr> 
    <td align='center'><input type="text" id="startdate" value="" size="20"/></td>
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

