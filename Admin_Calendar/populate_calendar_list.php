<?php require_once('../Connections/connvbsa.php'); 
error_reporting(0);
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer,Administrator,Boardmember,Secretary,Scores";
$MM_donotCheckaccess = "false";

mysql_select_db($database_connvbsa, $connvbsa);

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

$current_year = (date("Y"));
$next_year = (date("Y")+1);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// insert new data
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{

  // get current years calendar data
  $query_get_current = "Select * FROM vbsa3364_vbsa2.calendar where startdate > '" . $current_year . "-01-01' and startdate < '" . $current_year . "-12-31'";
  //echo($query_get_current . "<br>");
  $result_current = mysql_query($query_get_current, $connvbsa) or die(mysql_error());

  // delete any next year calendar data
  $deleteSQL = "Delete FROM vbsa3364_vbsa2.calendar where startdate > '" . $next_year . "-01-01' and startdate < '" . $next_year . "-12-31'";
  //echo($deleteSQL . "<br>");
  $Result = mysql_query($deleteSQL, $connvbsa) or die(mysql_error());


  // delete any next year tournaments data
  $deleteSQL_T = "Delete FROM vbsa3364_vbsa2.tournaments where tourn_year > '" . $current_year . "'";
  //echo($deleteSQL . "<br>");
  $Result = mysql_query($deleteSQL_T, $connvbsa) or die(mysql_error());

  // get next tourn_id
  $query_tourn_id = 'Select * FROM vbsa3364_vbsa2.tournaments order by tourn_id DESC Limit 1';
  //echo($query_tourn_id . "<br>");
  $result_tourn_id = mysql_query($query_tourn_id, $connvbsa) or die(mysql_error());
  $build_tourn_id = $result_tourn_id->fetch_assoc();
  $last_id = $build_tourn_id['tourn_id'];
  $next_id = ($last_id+1);
  //echo("Next ID 1 " . $next_id . "<br>");
  while ($row_date = $result_current->fetch_assoc())
  {
    if($row_date['startdate'] == '')
    {
      $new_close_date = '0000-00-00';
    }
    else
    {
      $new_close_date = date('Y-m-d', strtotime($row_date['startdate'] . ' + 357 days'));
    }
    $new_start_date = date('Y-m-d', strtotime($row_date['startdate'] . ' + 364 days'));
    $new_finish_date = date('Y-m-d', strtotime($row_date['finishdate'] . ' + 364 days'));

    //$insertSQL = "Insert INTO calendar (event, tourn_id, venue, state, aust_rank, ranking_type, startdate, finishdate, entry_close, about, tourn, visible, footer1, footer2, footer3, footer4) VALUES ('" . 
    //addslashes($row_date['event']) . "', " . 
    $insertSQL = "Insert INTO calendar (event, tourn_id, tourn_type, tourn_class, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate, about, tourn, visible, footer1, footer2, footer3, footer4, special_dates) VALUES ('" . 
    addslashes($row_date['event']) . "', " . 
    $next_id . ", '" .
    $row_date['tourn_type'] . "', '" .
    $row_date['tourn_class'] . "', '" .
    $row_date['venue'] . "', '" .
    $row_date['state'] . "', '" .
    $row_date['aust_rank'] . "', '" .
    $row_date['ranking_type'] . "', '" .
    $new_start_date . "', '" .
    $new_finish_date . "', '" .
    $new_close_date . "', '" .
    addslashes($row_date['about']) . "', '" .
    $row_date['tourn'] . "', 
    'Yes', '" .
    $row_date['footer1'] . "', '" .
    $row_date['footer2'] . "', '" .
    $row_date['footer3'] . "', '" .
    $row_date['footer4'] . "', '" .
    $row_date['special_dates'] . "')";
    //echo($insertSQL . "<br>");
    $Result1 = mysql_query($insertSQL, $connvbsa);
    $next_id++;
    //echo("Next ID 2 " . $next_id . "<br>");
/*
    // temp update for existing years calendar events
    $UpdateSQL = "Update calendar Set tourn_id = " . $next_id . " Where event_id = " . $row_date['event_id'];
    echo($UpdateSQL . "<br>");
    $Result1 = mysql_query($UpdateSQL, $connvbsa) or die(mysql_error());
    // increment tourn_id
    $next_id++;
*/
    if(str_contains($row_date['event'], 'Snooker'))
    {
      $comp_type = 'Snooker';
    }    
    else
    {
      $comp_type = 'Billiards';
    }
    //echo($comp_type . "<br>");    
    $insert_tourn_SQL = "Insert INTO tournaments (tourn_name, site_visible, tourn_type, tourn_class, ranking_type, how_seed, tourn_year, status) 
    VALUES 
    ('" . addslashes($row_date['event']) . "', 'No', '" . $comp_type . "', 'Victorian', '" . $row_date['ranking_type'] . "', 'NA', '" . (date("Y")+1) . "', 'Closed')";
    //echo($insert_tourn_SQL . "<br>"); // change to year +1
    $Result2 = mysql_query($insert_tourn_SQL, $connvbsa);
  }

  // get next years calendar data and update event id
  $query_get_new = "Select * FROM vbsa3364_vbsa2.calendar where startdate > '" . (date("Y")+1) . "-01-01' and startdate < '" . (date("Y")+1) . "-12-31' Order By event";
  //echo($query_get_new . "<br>");
  $result_new = mysql_query($query_get_new, $connvbsa) or die(mysql_error());
  while($build_new = $result_new->fetch_assoc())
  {
    $sql_update = "Update tournaments set event_id = " . $build_new['event_id'] . " where tourn_name = '" . addslashes($build_new['event']) . "' and tourn_year = " . (date("Y")+1);
    //echo("Add Event ID " . $sql_update . "<br>");
    $Result = mysql_query($sql_update, $connvbsa) or die(mysql_error());
  }


  // get next years tournament data and update tourn id
  $query_get_tourn = "Select * FROM vbsa3364_vbsa2.tournaments where tourn_year = " . (date("Y")+1) . " Order By tourn_name";
  //echo($query_get_tourn . "<br>");
  $result_tourn = mysql_query($query_get_tourn, $connvbsa) or die(mysql_error());
  while($build_tourn = $result_tourn->fetch_assoc())
  {
    $sql_update_tourn = "Update calendar set tourn_id = " . $build_tourn['tourn_id'] . " where event = '" . addslashes($build_tourn['tourn_name']) . "' and startdate > '" . (date("Y")+1) . "-01-01' and startdate < '" . (date("Y")+1) . "-12-31'";
    //echo("Add tourn_id " . $sql_update_tourn . "<br>");
    $Result = mysql_query($sql_update_tourn, $connvbsa) or die(mysql_error());
  }

  $insertGoTo = "calendar_list.php?cal_year=" . ($current_year);
  //echo("Insert to go " . $insertGoTo . "<br>");
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
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
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5" class="greenbg">
  <tr>
    <td><a href="calendar_list.php?cal_year=<?php echo date("Y") ?>" title="View, Insert and edit the current calendar">Calendar for the current year</a></td>
    <td><a href="calendar_list.php?cal_year=<?php echo date("Y")+1 ?>" title="View, Insert and edit calendar for next year">Calendar for next year</a></td>
    <td><a href="calendar_event_xx_archive.php" title="No Start Date, Start Date is out of date or Visible is set to No">Archives</a></a></td>
    <td align="right" class="greenbg"><a href="calendar_event_previous.php">Insert a new event</a></td>
    <td><a href="../Admin_web_pages/aa_webpage_index.php">Webpage Menu</a></td>
    <td><a href="../Admin_DB_VBSA/vbsa_login_success.php">Admin Menu</a></td>
    <td align="right" class="greenbg"><a href="A_calendar_index.php">Calendar Menu</a></td>
  </tr>
</table>

<!--<form name='cal_state' id='cal_state' method="post" action='calendar_list.php'>-->
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
<input type="hidden" name="MM_insert" value="form1" />
<table width="1000" align="center">
  <input type='hidden' name='CalYear' id='CalYear' value='<?php echo $cal_year; ?>'>
  <!--<input type='hidden' name='filter' id='filter'>-->
  <tr>
      <td colspan="2" class="red_bold">&nbsp;</td>
  </tr>
    <tr>
      <td colspan="2"  align="center" class="red_bold">This action will take all the entries from the <?php echo date("Y") ?> calendar and insert them in the <?php echo date("Y")+1 ?> calendar, overwriting or deleting any existing entries.</td>
    </tr>
    <tr>
      <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" value="Populate Calendar" /></td>
    </tr>
</table>
</form>

</body>
</html>
