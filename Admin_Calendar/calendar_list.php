<?php require_once('../Connections/connvbsa.php'); 
error_reporting(0);
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer,Administrator,Boardmember,Secretary,Scores";
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
<?php require_once('../Connections/connvbsa.php'); ?><?php
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

if (isset($_GET['cal_year'])) {
  $cal_year = $_GET['cal_year'];
}
elseif(isset($_POST['CalYear']))
{
  $cal_year = $_POST['CalYear'];
}

//echo($cal_year . "<br>");

$filter = '';
$whereby = '';

if(isset($_POST['state']) && ($_POST['state'] != ""))
{
  $varstate = $_POST['state'];
  if($varstate == 'ALL')
  {
   $filter = ""; 
  }
  else
  {
    $filter = " AND lower(state) = '" . strtolower($varstate) . "'";
  }
}
else
{
  $filter = '';
}

if(isset($_POST['vic_rank']) && ($_POST['vic_rank'] != ""))
{
  $varRank = $_POST['vic_rank'];
  switch ($varRank) {
    case 'None':
      $whereby = " and calendar.ranking_type = 'None'";
      break;
    case 'National':
      $whereby = " and ((aust_rank = 'Yes') OR (aust_rank = 'Yes' AND ranking_type = 'Victorian') OR (aust_rank = 'Yes' AND calendar.ranking_type = 'Womens') OR (aust_rank = 'Yes' AND ranking_type = 'Junior'))";
      break;
    case 'Victorian':
      $whereby = " and calendar.ranking_type = 'Victorian'";
      break;
    case 'Womens':
      $whereby = " and calendar.ranking_type = 'Womens'";
      break;
    case 'Junior':
      $whereby = " and calendar.ranking_type = 'Junior'";
      break;
    default:
      $whereby = "";
      break;
  }
}
else
{
  $filter = '';
}

$filter = $filter . " " . $whereby;

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
<script type='text/javascript'>

function GetState(sel) {
  var state = sel.options[sel.selectedIndex].value;
  document.getElementById("state").value = state;
  var year = <?= $cal_year ?>;
  document.cal_state.vic_rank.value = document.getElementById("vic_rank").value
  document.getElementById("vic_rank").selected = document.getElementById("vic_rank").value
  document.cal_state.CalYear.value = year;
  document.cal_state.submit();
}

function GetVicRank(sel) {
  var rank = sel.options[sel.selectedIndex].value;
  document.getElementById("vic_rank").value = rank;
  var year = <?= $cal_year ?>;
  document.cal_state.state.value = document.getElementById("state").value
  document.getElementById("state").selected = document.getElementById("state").value
  document.cal_state.CalYear.value = year;
  document.cal_state.submit();
}

function ExportCSV() {
  document.export_data.Filter.value = "<?= $filter ?>";
  document.export_data.CalYear.value = <?= $cal_year ?>;
  document.export_data.submit();
}

</script>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<form name='export_data' id='export_data' method="post" action='export_csv.php'>
  <input type='hidden' name='CalYear' id='CalYear' value='<?php echo $cal_year; ?>'>
  <input type='hidden' name='Filter' id='Filter' value='<?php echo $filter; ?>'>
</form>
<table align="center" cellpadding="5" cellspacing="5" class="greenbg">
  <tr>
    <td><a href="calendar_list.php?cal_year=<?php echo date("Y") ?>" title="View, Insert and edit the current calendar">Calendar for the current year</a></td>
    <td><a href="calendar_list.php?cal_year=<?php echo date("Y")+1 ?>" title="View, Insert and edit calendar for next year">Calendar for next year</a></td>
    <td><a href="calendar_event_xx_archive.php" title="No Start Date, Start Date is out of date or Visible is set to No">Archives</a></a></td>
    <td align="right" class="greenbg"><a href="calendar_event_previous.php?page=calendar">Insert a new event</a></td>
    <td><a href="../Admin_web_pages/aa_webpage_index.php">Webpage Menu</a></td>
    <td><a href="../Admin_DB_VBSA/vbsa_login_success.php">Admin Menu</a></td>
  </tr>
</table>
<form name='cal_state' id='cal_state' method="post" action='calendar_list.php'>
<table width="1000" align="center">
  <input type='hidden' name='CalYear' id='CalYear' value='<?php echo $cal_year; ?>'>
  <tr>
      <td colspan="2" class="red_bold">&nbsp;</td>
  </tr>
    <tr>
      <td align="left" class="red_bold"><?php echo $cal_year; ?> Calendar - shows all events where a &quot;Start Date&quot; exists </td>
      <td align="right" class="greenbg"><a href="A_calendar_index.php">Calendar Menu</a></td>
    </tr>
    <tr>
      <td colspan="2" class="red_bold">&nbsp;</td>
  </tr>
    <tr>
      <td colspan="2" align="center"><button type='button' style='background-color: #339999; color: white; font-size: 10px; border: none;' OnClick='ExportCSV()'>Export Data To CSV File</button></td>
  </tr>
  <tr>
    <td colspan="2" align="center">To Edit an event, click the pencil button &nbsp;&nbsp;&nbsp;&nbsp;<img src="../Admin_Images/edit_butt.fw.png" width="20" height="20" />&nbsp; &nbsp;&nbsp;&nbsp;adjacent to the event</td>
  </tr>
  <tr>
    <td colspan="2" align="center">To Create Email link, URL, upload  attachment/s or create a web page item please click the detail button &nbsp;&nbsp;&nbsp;&nbsp;<img src="../Admin_Images/detail.fw.png" width="20" height="20" />&nbsp; &nbsp;&nbsp;&nbsp;adjacent to the event</td>
  </tr>
   <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <label for="state">Filter by State:</label>
      <select name="state" id="state" onchange="GetState(this)">
        <?php 
        if((isset($_POST['state'])) && ($_POST['state'] != ''))
        {
          echo("<option value='" . $_POST['state'] . "'>" . $_POST['state'] . "</option>");
        }
        ?>
        <option value="ALL">All</option>
        <option value="ACT">ACT</option>
        <option value="NSW">NSW</option>
        <option value="NT">NT</option>
        <option value="QLD">QLD</option>
        <option value="SA">SA</option>
        <option value="TAS">TAS</option>
        <option value="VIC">VIC</option>
        <option value="WA">WA</option>
      </select>&nbsp;
      <label for="vic_rank">Filter by Ranking Type:</label>
      <select name="vic_rank" id="vic_rank" onchange="GetVicRank(this)">
      <?php 
      if((isset($_POST['vic_rank'])) && ($_POST['vic_rank'] != ''))
      {
        echo("<option value='" . $_POST['vic_rank'] . "'>" . $_POST['vic_rank'] . "</option>");
      }
      ?>
        <option value="ALL">All</option>
        <option value="None">None</option>
        <option value="National">National</option>
        <option value="Victorian">Victorian</option>
        <option value="Womens">Womens</option>
        <option value="Junior">Junior</option>
      </select>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="greenbg">&nbsp;</td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
<?php
$total_records = 0;
mysql_select_db($database_connvbsa, $connvbsa);
for($month = 1; $month <= 12; $month++)
{
  switch($month)
  {
    case 1:
      $month = 1;
      $title = 'January';
      break;
    case 2:
      $month = 2;
      $title = 'February';
      break;
    case 3:
      $month = 3;
      $title = 'March';
      break;
    case 4:
      $month = 4;
      $title = 'April';
      break;
    case 5:
      $month = 5;
      $title = 'May';
      break;
    case 6:
      $month = 6;
      $title = 'June';
      break;
    case 7:
      $month = 7;
      $title = 'July';
      break;
    case 8:
      $month = 8;
      $title = 'August';
      break;
    case 9:
      $month = 9;
      $title = 'September';
      break;
    case 10:
      $month = 10;
      $title = 'October';
      break;
    case 11:
      $month = 11;
      $title = 'November';
      break;
    case 12:
      $month = 12;
      $title = 'December';
      break;
  }
  mysql_select_db($database_connvbsa, $connvbsa);
  //$query_Cal_01 = "Select calendar.event_id, event, venue, state, aust_rank, calendar.ranking_type, startdate, finishdate, closedate, visible, event_number, attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id LEFT JOIN tournaments ON tournaments.tourn_id = calendar.tourn_id WHERE ((YEAR(startdate) = $cal_year AND MONTH(startdate) = " . $month . "))  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";

  $query_Cal_01 = "Select calendar.event_id, event, venue, state, aust_rank, calendar.ranking_type, startdate, finishdate, closedate, visible, event_number, attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id LEFT JOIN tournaments ON tournaments.tourn_id = calendar.tourn_id WHERE ((YEAR(startdate) = $cal_year AND MONTH(startdate) = " . $month . "))  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";

  //echo($query_Cal_01 . "<br>");
  $Cal_01 = mysql_query($query_Cal_01, $connvbsa) or die(mysql_error());
  $row_Cal_01 = mysql_fetch_assoc($Cal_01);
  $totalRows_Cal_01 = mysql_num_rows($Cal_01);mysql_select_db($database_connvbsa, $connvbsa);
  $total_records = ($total_records + $totalRows_Cal_01);
  include "current_calendar.php";
}
?>

</body>
</html>
