<?php require_once('../Connections/connvbsa.php'); ?>
<?php

mysql_select_db($database_connvbsa, $connvbsa);
date_default_timezone_set('Australia/Melbourne');

// Get current month and year
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Previous and next month
$prev_month = $month - 1;
$prev_year = $year;
if ($prev_month == 0) {
    $prev_month = 12;
    $prev_year--;
}

$next_month = $month + 1;
$next_year = $year;
if ($next_month == 13) {
    $next_month = 1;
    $next_year++;
}

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
$start_day = date('w', $first_day_of_month); // 0 (Sun) to 6 (Sat)

$month_name = date('F', $first_day_of_month);
$today = date('Y-m-d');

//echo("Here");

function GetColor($ranking)
{
    switch($ranking)
    {
        case 'Vic':
            $rank_color = (" style='background: #33f3ff;'");
            break;
        case 'SA':
            $rank_color = (" style='background: #33ff76;'");
            break;
        case 'Qld':
            $rank_color = (" style='background: #fffb33;'");
            break;
        case 'WA':
            $rank_color = (" style='background: #ff33f8;'");
            break;
        case 'NSW':
            $rank_color = (" style='background: #d9d5d1;'");
            break;
        case 'Public':
            $rank_color = (" style='background: #F54927;'");
            break;
        default:
            $rank_color = ("");
            break;
/*
        case 'Victorian':
            $rank_color = (" style='background: #33f3ff;'");
            break;
        case 'Womens':
            $rank_color = (" style='background: #33ff76;'");
            break;
        case 'Junior':
            $rank_color = (" style='background: #fffb33;'");
            break;
        case 'National':
            $rank_color = (" style='background: #ff33f8;'");
            break;
        case 'No Entry':
            $rank_color = (" style='background: #d9d5d1;'");
            break;
        default:
            $rank_color = ("");
            break;
*/
    }
    return $rank_color;

}

//echo(GetColor('Victorian'));

?>
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

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.14.1/themes/smoothness/jquery-ui.css">

<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.css">
<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/skins/dhtmlxcalendar_dhx_skyblue.css">
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcommon.js"></script>
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

</head>

<style>

body {
    font-family: Arial;
    text-align: center;
    background: #f4f4f4;
}

table.calendar {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
}

table.calendar th {
    width: 14.28%;
    height: 50px;
    border: 1px solid #ccc;
    vertical-align: top;
    padding: 5px;
    font-size: 30px;
}

table.calendar td {
    width: 14.28%;
    height: 80px;
    border: 1px solid #ccc;
    vertical-align: top;
    padding: 5px;
}

table.calendar td.today {
    background-color: #ffefc4;
    font-weight: bold;
}

.nav a {
    margin: 0 10px;
    text-decoration: none;
    font-weight: bold;
    color: #333;
}

p.event {
    border-style: solid;
    border-width: thin;
    border-color: #030203;
    padding: 5px; 
    text-align: center;
  }

input {
    text-align: center;
}

</style>
<script>
    
function test_click(event_id) {
  $return_page = "../Admin_Tournaments/edit_previous_tournament.php?eventID=" + event_id + "&page=calendar";
  location.href = $return_page;
}

</script>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>


<h2><?= $month_name . ' ' . $year ?></h2>
<div class="nav">
    <a href="?month=<?= $prev_month ?>&year=<?= $prev_year ?>">&laquo; Prev</a>
    <a href="?month=<?= date('m') ?>&year=<?= date('Y') ?>">Today</a>
    <a href="?month=<?= $next_month ?>&year=<?= $next_year ?>">Next &raquo;</a>
</div>
<table class="calendar">
    <tr>
        <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th>
        <th>Thu</th><th>Fri</th><th>Sat</th>
    </tr>
    <tr>
    <?php
    $day = 1;
    $cell = 0;

    // Pad empty cells before first day
    for ($i = 0; $i < $start_day; $i++) {
        echo "<td></td>";
        $cell++;
    }

    // Fill the calendar
    for (; $day <= $days_in_month; $day++, $cell++) {
        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $is_today = ($date == $today) ? 'today' : '';
        echo "<td class='$is_today'>" . $day;

        //$sql = 'Select * FROM calendar WHERE startdate = "' . $date . '"';

        $sql = 'Select event_id, event, state, startdate FROM calendar WHERE startdate = "' . $date . '"
                UNION
                SELECT id, SUMMARY as event, TYPE as state, DTSTART as startdate FROM tbl_ics_dates WHERE DTSTART = "' . $date . '"';


        //$sql = 'Select *, tbl_ics_dates.SUMMARY as event, TYPE as state, tbl_ics_dates.id as event_id FROM tbl_ics_dates WHERE DTSTART = "' . $date . '"';
        //$sql = 'Select *, tbl_ics_dates.SUMMARY as event, DATE_FORMAT(tbl_ics_dates.DTSTART, "Y-m-d") as startdate, TYPE as state, tbl_ics_dates.id as event_id FROM calendar Left Join tbl_ics_dates on DATE_FORMAT(calendar.startdate, "Y-m-d") = DATE_FORMAT(tbl_ics_dates.DTSTART, "Y-m-d") Where startdate = "' . $date . '"';
        //echo($sql . "<br>");
        $result = mysql_query($sql, $connvbsa) or die(mysql_error());
        while($build_data = $result->fetch_assoc())
        {
            if($build_data['event'] != '')
            {
                 echo "<div onclick='test_click(" . $build_data['event_id'] . ")' " . GetColor($build_data['state']) . "><p class='event' >" . $build_data['event'] . "</p></div>";
            }
        }
        if ($cell % 7 == 6) echo "</tr><tr>";
    }

    // Fill trailing empty cells
    while ($cell % 7 != 0) {
        echo "<td></td>";
        $cell++;
    }
    ?>
    </tr>
</table>
<table class='table dt-responsive nowrap display' align='center' width='800px'>
    <tr>
        <td colspan='5' align='center'><b>Legend:</b></td>
    </tr>
    <tr>
        <td align='center'><input type='text' style='background: #33f3ff;' value='Vic'></td>
        <td align='center'><input type='text' style='background: #33ff76;' value='SA'></td>
        <td align='center'><input type='text' style='background: #fffb33;' value='Qld'></td>
        <td align='center'><input type='text' style='background: #ff33f8;' value='WA'></td>
        <td align='center'><input type='text' style='background: #d9d5d1;' value='NSW'></td>         
    </tr>
    <tr>
        <td align='center' colspan='5'><input type='text' style='background: #F54927;' value='Vic Public Holiday'></td>         
    </tr>

</body>
</html>

