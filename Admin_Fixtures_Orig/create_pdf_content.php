<?php require_once('../Connections/connvbsa.php');

require_once('Models/Fixture.php');

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);

?>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<?php

$current_year = $_GET['Year'];
$current_season = $_GET['Season'];
$team_grade = $_GET['Team_Grade'];
$day_played = $_GET['DayPlayed'];
$comptype = $_GET['CompType'];
$sql_table = $_GET['Table'];

$fixture = new Fixture();
$fixture->LoadFixture($current_year, $current_season, $day_played);
$jsonData = json_encode($fixture);
$data = json_decode($jsonData, true);
$public_holiday = $data['non_dates'];

// get from grade settings table
$sql_grades = "Select * From Team_grade Where grade = '" . $team_grade . "' and season = '" . $current_season . "' and fix_cal_year = " . $current_year . " AND current = 'Yes'";
//echo($sql_grades . "<br>");
$result_grades = mysql_query($sql_grades, $connvbsa) or die(mysql_error());
$build_grades = $result_grades->fetch_assoc();

$comp_type = $build_grades['type'];
$NoOfFixtures = $build_grades['no_of_matches'];
$NoOfRounds = $build_grades['no_of_rounds']; // from Team_grades, includes finals...
$final_players = $build_grades['finals_teams'];

$add_rounds_for_finals = ($final_players/2);
$home_away_rounds = (($NoOfRounds)-$add_rounds_for_finals); // remove finals
$no_of_rounds = $NoOfRounds; // inc finals

$sql = "Select date From $sql_table Where team_grade = '" . $team_grade . "' AND year = " . $current_year . " AND season = '" . $current_season . "' Order By date ASC Limit 1";
//echo($sql . "<br>");
$result_first_date = mysql_query($sql, $connvbsa) or die(mysql_error());
$row_first = $result_first_date->fetch_assoc();
$first_date = $row_first['date'];

$sql = "Select date From $sql_table Where team_grade = '" . $team_grade . "' AND year = " . $current_year . " AND season = '" . $current_season . "' AND round = '" . $home_away_rounds . "'";
//echo($sql . "<br>");
$result_last_date = mysql_query($sql, $connvbsa) or die(mysql_error());
$row_last = $result_last_date->fetch_assoc();
$last_date = $row_last['date'];

$sql = "Select * From $sql_table Where team_grade = '" . $team_grade . "' AND year = " . $current_year . " AND season = '" . $current_season . "' Order By round";
//echo($sql . "<br>");
$result_fixture = mysql_query($sql, $connvbsa) or die(mysql_error());
$num_rows = $result_fixture->num_rows;
/*
echo("Rounds " . $home_away_rounds . "<br>");
echo("Total Rounds " . $no_of_rounds . "<br>");
echo("Compare " . $i . " > " . $home_away_rounds . "<br>");
echo("Compare " . $i . " < " . $no_of_rounds . "<br>");
echo("Type " . $comp_type . "<br>");
*/
if($num_rows > 0)
{
    echo("<script type='text/javascript'>");
    echo("function FillElementArray() {");
    $i = 0;
    $k = 0;
    while ($build_fixture_data = $result_fixture->fetch_assoc()) 
    {
        if($i < $home_away_rounds)
        {
            $team_grade = $build_fixture_data['team_grade'];
            $dayplayed = $build_fixture_data['dayplayed'];
            echo("document.getElementById('round" . $i . "_date').innerHTML = 'Round " . ($i+1) . "<br>" . $dayplayed . "<br>" . $build_fixture_data['date']) . "';";
            for ($j = 0; $j < $NoOfFixtures; $j++) 
            {
            echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_home').innerHTML = '" . $build_fixture_data["fix" . ($j+1) . "home"] . "';");
            echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_away').innerHTML = '" . $build_fixture_data["fix" . ($j+1) . "away"] . "';");
            }
        }
        else if(($i >= $home_away_rounds) && ($i < $no_of_rounds))
        //else if(($k = 0) && ($k < ($no_of_rounds-$home_away_rounds)))
        {

            //if($type == 'Snooker')
            //{
                echo("document.getElementById('finals_" . ($k+1) . "_date').innerHTML = '" . $build_fixture_data['date'] . "';");
            //}
            
            $k++;
        }
        $i++;
    }
    echo("}");
    echo("window.onload = function()");
    echo("{");
    echo("  FillElementArray();");
    echo("}");
}
echo("</script>");

?>
<style>

.all {
  border: 1px solid black;
  border-collapse: collapse;
}

.top {
  border-top: 1px solid black;
  border-collapse: collapse;
}

.bottom {
  border-bottom: 1px solid black;
  border-collapse: collapse;
}

.left {
  border-left: 1px solid black;
  border-collapse: collapse;
}

.right {
  border-right: 1px solid black;
  border-collapse: collapse;
}

</style>

<?php
$html_content = 
"<center>
<table class='table table-striped table-bordered dt-responsive nowrap display fixture' width='1000px' align='center' border='0'>
    <tr>
        <td colspan='9' class='border' style='text-align: center; background-color: black; color: white; height:40px;'><strong>$current_year VBSA - $comptype Season $current_season $team_grade Fixture</strong></td>
</tr>";

$add_rounds_for_finals = ($final_players/2);
$no_of_rounds = (($NoOfRounds)-$add_rounds_for_finals); // remove finals

for($i = 0; $i < ($no_of_rounds-2); $i++)
{
    $j = 0;
    $html_content .= "<tr>
        <td colspan='9'>&nbsp;</td>
    </tr>
    <tr>
        <td rowspan=" . $NoOfFixtures . " width='50px' align='center' class='all' id='round" . $i . "_date'></td>
        <td width='100px' align='center' class='top' id='round" . ($i+1) . "_fix" . ($j+1) . "_home'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Home</td>
        <td width='10px' align='center' class='top'>v</td>
        <td width='100px' align='center' class='top right' id='round" . ($i+1) . "_fix" . ($j+1) . "_away'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Away</td>

        <td width='10px' >&nbsp;</td>";
    $html_content .= "<td width='50px' rowspan=" . $NoOfFixtures . " align='center' class='all' id='round" . ($i+1) . "_date'></td>
        <td width='100px' align='center' class='top' id='round" . ($i+2) . "_fix" . ($j+1) . "_home'>Round " . ($i+2) . ", Fix " . ($j+1) . ", Home</td>
        <td width='10px' align='center' class='top'>v</td>
        <td width='100px' align='center' class='top right' id='round" . ($i+2) . "_fix" . ($j+1) . "_away'>Round " . ($i+2) . ", Fix " . ($j+1) . ", Away</td>
    </tr>";  
    for($j = 1; $j < $NoOfFixtures; $j++)
    {
        if($j === ($NoOfFixtures-1))
        {
            $class = " bottom";
        }
        else
        {
            $class = "";
        }
        $html_content .= "<tr>
            <td width='100px' align='center' class='" . $class . "' id='round" . ($i+1) . "_fix" . ($j+1) . "_home'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Home</td>
            <td width='10px' align='center' class='" . $class . "'>v</td>
            <td width='100px' align='center' class='right " . $class . "' id='round" . ($i+1) . "_fix" . ($j+1) . "_away'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Away</td>
            <td width='10px' >&nbsp;</td>";
        $html_content .= "<td width='100px' align='center' class='" . $class . "' id='round" . ($i+2) . "_fix" . ($j+1) . "_home'>Round " . ($i+2) . ", Fix " . ($j+1) . ", Home</td>
                <td width='10px' align='center' class='" . $class . "'>v</td>
                <td width='100px' align='center' class='right " . $class . "' id='round" . ($i+2) . "_fix" . ($j+1) . "_away'>Round " . ($i+2) . ", Fix " . ($j+1) . ", Away</td>
            </tr>"; 
    }
    $j++;
    $i++;
}

if($no_of_rounds % 2 == 0)
{
    for($i = ($no_of_rounds-2); $i < ($no_of_rounds); $i++)
    {
        $j = 0;
        $html_content .= "<tr>
            <td colspan='9'>&nbsp;</td>
        </tr>
        <tr>
            <td rowspan=" . $NoOfFixtures . " width='50px' align='center' class='all' id='round" . $i . "_date'></td>
            <td width='100px' align='center' class='top' id='round" . ($i+1) . "_fix" . ($j+1) . "_home'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Home</td>
            <td width='10px' align='center' class='top'>v</td>
            <td width='100px' align='center' class='top right' id='round" . ($i+1) . "_fix" . ($j+1) . "_away'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Away</td>

            <td width='10px' >&nbsp;</td>";
        $html_content .= "<td width='50px' rowspan=" . $NoOfFixtures . " align='center' class='all' id='round" . ($i+1) . "_date'></td>
            <td width='100px' align='center' class='top' id='round" . ($i+2) . "_fix" . ($j+1) . "_home'>Round " . ($i+2) . ", Fix " . ($j+1) . ", Home</td>
            <td width='10px' align='center' class='top'>v</td>
            <td width='100px' align='center' class='top right' id='round" . ($i+2) . "_fix" . ($j+1) . "_away'>Round " . ($i+2) . ", Fix " . ($j+1) . ", Away</td>
        </tr>";  
        for($j = 1; $j < $NoOfFixtures; $j++)
        {
            if($j === ($NoOfFixtures-1))
            {
                $class = " bottom";
            }
            else
            {
                $class = "";
            }
            $html_content .= "<tr>
                <td width='100px' align='center' class='" . $class . "' id='round" . ($i+1) . "_fix" . ($j+1) . "_home'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Home</td>
                <td width='10px' align='center' class='" . $class . "'>v</td>
                <td width='100px' align='center' class='right " . $class . "' id='round" . ($i+1) . "_fix" . ($j+1) . "_away'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Away</td>
                <td width='10px' >&nbsp;</td>";
            $html_content .= "<td width='100px' align='center' class='" . $class . "' id='round" . ($i+2) . "_fix" . ($j+1) . "_home'>Round " . ($i+2) . ", Fix " . ($j+1) . ", Home</td>
                    <td width='10px' align='center' class='" . $class . "'>v</td>
                    <td width='100px' align='center' class='right " . $class . "' id='round" . ($i+2) . "_fix" . ($j+1) . "_away'>Round " . ($i+2) . ", Fix " . ($j+1) . ", Away</td>
                </tr>"; 
        }
        $j++;
        $i++;
    }
}
else if($no_of_rounds % 2 == 1)
{
    $i = ($no_of_rounds-1);
    $j = 0;
    $html_content .= "<tr>
        <td colspan='9'>&nbsp;</td>
    </tr>
    <tr>
        <td colspan='2'>&nbsp;</td>
        <td rowspan=" . $NoOfFixtures . " width='50px' align='center' class='all' id='round" . $i . "_date'></td>
        <td width='100px' align='center' class='top' id='round" . ($i+1) . "_fix" . ($j+1) . "_home'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Home</td>
        <td width='10px' align='center' class='top'>v</td>
        <td width='100px' align='center' class='top right' id='round" . ($i+1) . "_fix" . ($j+1) . "_away'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Away</td></tr>
        "; 
    for($j = 1; $j < $NoOfFixtures; $j++)
    {
        if($j === ($NoOfFixtures-1))
        {
            $class = " bottom";
        }
        else
        {
            $class = "";
        }
        $html_content .= "<tr>
            <td colspan='3'>&nbsp;</td>
            <td width='100px' align='center' class='" . $class . "' id='round" . ($i+1) . "_fix" . ($j+1) . "_home'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Home</td>
            <td width='10px' align='center' class='" . $class . "'>v</td>
            <td width='100px' align='center' class='right " . $class . "' id='round" . ($i+1) . "_fix" . ($j+1) . "_away'>Round " . ($i+1) . ", Fix " . ($j+1) . ", Away</td>"; 
    }
    $j++;
    $i++;
    $html_content .= "
    <tr>
        <td colspan='9'>&nbsp;</td>
    </tr>";
}
$html_content .= "
<tr>
    <td colspan='9'>&nbsp;</td>
</tr>";
/*
function GetFinalsDate($public_holiday, $last_date)
{
    foreach($public_holiday as $vbsa_holiday) 
    {
        if(date('Y-m-d', strtotime($last_date . ' + 7 days')) === date('Y-m-d', strtotime($vbsa_holiday['date'])))
        {
            $finals_date = date('Y-m-d', strtotime($vbsa_holiday['date']));
            break;
        }
        else
        {
            $finals_date = date('Y-m-d', strtotime($last_date . ' + 7 days'));
        }
    }
    return $finals_date;

}
*/
// display finals and public holidays
if($comptype === 'Billiards')
{
    //$finals_date  = GetFinalsDate($public_holiday, $last_date);
    $html_content .= "
    <tr>
        <td colspan='3'>&nbsp;</td>
        <td colspan='2' width='100px' align='center' class='top left'>Elimination Finals</td>
        <td width='100px' align='center' class='top right' id='finals_1_date'></td>
        <td colspan='3'>&nbsp;</td>
    </tr>";
    //$finals_date  = GetFinalsDate($public_holiday, $finals_date);
    $html_content .= "
    <tr>
        <td colspan='3'>&nbsp;</td>
        <td colspan='2' width='100px' align='center' class='left'>Semi Finals</td>
        <td width='100px' align='center' class='right' id='finals_2_date'></td>
        <td colspan='3'>&nbsp;</td>
    </tr>";
    //$finals_date  = GetFinalsDate($public_holiday, $finals_date);
    $html_content .= "
    <tr>
        <td colspan='3'>&nbsp;</td>
        <td colspan='2' width='100px' align='center' class='bottom left'>Grand Finals</td>
        <td width='100px' align='center' class='bottom right' id='finals_3_date'></td>
        <td colspan='3'>&nbsp;</td>
    </tr>
    <tr>
        <td colspan='9'>&nbsp;</td>
    </tr>";
}
else if($comptype === 'Snooker')
{
    //$finals_date  = GetFinalsDate($public_holiday, $last_date);
    $html_content .= "
    <tr>
        <td colspan='3'>&nbsp;</td>
        <td colspan='2' width='100px' align='center' class='top left'>Semi Finals</td>
        <td width='100px' align='center' class='top right' id='finals_1_date'></td>
        <td colspan='3'>&nbsp;</td>
    </tr>";
    //$finals_date  = GetFinalsDate($public_holiday, $finals_date);
    $html_content .= "
    <tr>
        <td colspan='3'>&nbsp;</td>
        <td colspan='2' width='100px' align='center' class='bottom left'>Grand Finals</td>
        <td width='100px' align='center' class='bottom right' id='finals_2_date'></td>
        <td colspan='3'>&nbsp;</td>
    </tr>
    <tr>
        <td colspan='9'>&nbsp;</td>
    </tr>";
}

/*
if($day_played == 'Mon')
{
    $date_index = 1;
}
else if($day_played == 'Wed')
{
    $date_index = 3;
}

$next_date = date('Y-m-d', strtotime($last_date . ' + 7 days'));
$filtered = array_filter($public_holiday, function($holiday) use ($first_date, $next_date, $date_index) 
{
    return ($holiday['date'] >= $first_date) && ($holiday['date'] <= $next_date) && (date('N', strtotime($holiday['date'])) == (int)$date_index);
});

foreach ($filtered as $holiday) 
{
    $html_content .= "<tr>
        <td colspan='3'>&nbsp;</td>
        <td colspan='3' align='center'> Miss. " . $holiday['date'] . " " . $holiday['summary'] . "</td>
        <td colspan='3'>&nbsp;</td>
    </tr>";
}
*/
$html_content .= "<tr>
    <td colspan='9'>&nbsp;</td>
</tr>
<tr>
    <td colspan='3'>&nbsp;</td>
    <td colspan='3' id='displayContent' align='center'></td>
    <td colspan='3'>&nbsp;</td>
</tr>
<tr>
    <td colspan='9'>&nbsp;</td>
</tr>
</table>
</center>";

?>
<div id='content'><?= $html_content ?></div>
<hr><hr>
<center>

    <table class='table table-striped table-bordered dt-responsive nowrap display fixture' width='1000px' align='center' border='0'>
    <tr>
        <td colspan='9' align='center'><button id="generate">Generate PDF</button></td>
    </tr>
    <tr>
        <td colspan='9' align='center'>&nbsp;</td>
    </tr>
    <tr>
        <td colspan='9' align='center'>Add text for any special conditions</td>
    </tr>
    <tr>
        <td colspan='3'>&nbsp;</td>
        <td colspan='3' align='center'><textarea rows="5" cols="50" id='special_conditions' onkeyup="updateContent()"></textarea></td>
        <td colspan='3'>&nbsp;</td>
    </tr>
    <tr>
        <td colspan='9' align='center'>&nbsp;</td>
    </tr>
    <tr>
        <td colspan='9' align='center'><button id="upload" onClick='UploadPDF()'>Upload PDF via the Grades page</button></td>
    </tr>
    </table>

</center>
<script>

function UploadPDF()
{
    window.location.href = "../admin_scores/team_grades.php?season=<?= $current_season ?>&year=<?= $current_year ?>";
}

function updateContent()
{
    const textarea = document.getElementById("special_conditions");
    const displayElement = document.getElementById('displayContent');
    displayElement.textContent = textarea.value;
}

document.getElementById("generate").addEventListener("click", async () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const content = document.getElementById("content").innerText;
    var elementHTML = document.querySelector("#content");
    doc.html(elementHTML, {
        callback: function(doc) {
            doc.save("<?= $current_year ?>_<?= $current_season ?>_<?= $dayplayed ?>_<?= $team_grade ?>.pdf");
        },
        x: 15,
        y: 15,
        width: 140, //target width in the PDF document
        windowWidth: 750 //window width in CSS pixels
    });
});

</script>



