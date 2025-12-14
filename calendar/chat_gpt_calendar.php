<?php require_once('../Connections/connvbsa.php'); ?>
<?php

//$host = 'localhost';
//$dbname = 'demo';
//$user = 'peterj';
//$pass = 'abj059XZ@!';
//$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

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

?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Calendar Planner</title>
    <!--<link rel="stylesheet" href="calendar.css">-->
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

table.calendar th, table.calendar td {
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

</style>
<script>
    
function test_click(day) {
  alert(day);
}

</script>

<body>
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
            //echo($date . "<br>");
            $is_today = ($date == $today) ? 'today' : '';
            echo "<td class='$is_today'>" . $day;

            $sql = 'Select * FROM calendar WHERE startdate = "' . [$date] . '"';
            echo($sql . "<br>");

            $result = mysql_query($sql, $connvbsa) or die(mysql_error());
            $i = 0;
            while($build_data = $result->fetch_assoc())
            {
                echo "<div class='event' onclick='test_click({$day})'>{$build_data['event']}</div>";
            }
            /*
            $stmt = $pdo->prepare("Select * FROM events WHERE evt_start = ?");
            $stmt->execute([$date]);
            $events = $stmt->fetchAll();
            foreach ($events as $event) {
                echo "<div class='event' onclick='test_click({$day})'>{$event['evt_text']}</div>";
            }
            */
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
</body>
</html>

