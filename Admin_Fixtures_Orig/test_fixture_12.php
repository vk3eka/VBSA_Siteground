<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Fixture Generator</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>

<div id="fixture_container"></div>

<?php
/*
require_once('Models/Fixture.php');
$year = 2025;
$season = 'S2';
$dayplayed = 'Mon';
$fixture = new Fixture();
$fixture->LoadFixture($year, $season, $dayplayed);
$jsonData = json_encode($fixture);
$data = json_decode($jsonData, true);
echo($jsonData . "<br>");
*/
?>
<script>
/*
var grades = <?php echo json_encode($data['grades']); ?>;
var clubs = <?php echo json_encode($data['clubs']); ?>;
var teams = <?php echo json_encode($data['teams']); ?>;
var rounds = <?php echo json_encode($data['rounds']); ?>;
let data = JSON.stringify(all_home_teams, null, 2);
let parsedData = JSON.parse(data);
let teamList = parsedData.map(d => ({
    home: d.home_team.team,
    away: d.away_team.team,
    club: d.home_team.club,
    round: d.home_team.round,
}));
*/
function generateFixtures(teams, names) {
    console.log(names);
    let ghost = false;
    if (teams % 2 === 1) {
        teams++;
        ghost = true;
    }

    const totalRounds = teams - 1;
    const matchesPerRound = teams / 2;
    let rounds = Array.from({ length: totalRounds }, () => []);

    for (let round = 0; round < totalRounds; round++) {
        for (let match = 0; match < matchesPerRound; match++) {
            let home = (round + match) % (teams - 1);
            let away = (teams - 1 - match + round) % (teams - 1);
            if (match === 0) away = teams - 1;

            rounds[round][match] = `${names[home]} v ${names[away]}`;
        }
    }

    // Interleave rounds
    let interleaved = [];
    let evn = 0;
    let odd = teams / 2;
    for (let i = 0; i < rounds.length; i++) {
        interleaved[i] = (i % 2 === 0) ? rounds[evn++] : rounds[odd++];
    }

    // Flip first match of odd rounds
    interleaved = interleaved.map((round, idx) => {
        if (idx % 2 === 1) {
            round[0] = flip(round[0]);
        }
        return round;
    });

    return interleaved;
}

function flip(match) {
    const [home, away] = match.split(" v ");
    return `${away} v ${home}`;
}

function displayFixtures(rounds, formNo, teamGrade) {
    //console.log(rounds);
    let text = '';
    text += ("<table class='table table-striped table-bordered dt-responsive nowrap display float-container");
    text += ("<tbody>");
    text += ("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");

    // First half
    $.each(rounds, function(i, round) {
        text += ("<tr><td>Set 1</td></tr>");
        text += (`<tr><td colspan=3 align='center'><b>Round ${i + 1}</b></td></tr>`);
        text += (`<tr><td align='right'><b>Date</b></td><td colspan=2 class='text-left'><input type='text' id='A_${formNo}_date_${i}' style='width:100px'></td></tr>`);
        $.each(round, function(x, match) {
            const [home, away] = match.split(" v ");
            text += (`
                <tr>
                    <td align='center'><input class='float-child' type='text' id='A_${teamGrade}_home_${i + 1}_${x + 1}' value='${home}' style='width:200px'></td>
                    <td align='center'>v</td>
                    <td align='center'><input class='float-child' type='text' id='A_${teamGrade}_away_${i + 1}_${x + 1}' value='${away}' style='width:200px'></td>
                </tr>
            `);
        });
    });
/*
    //Second half (reversed home/away)
    let roundCounter = rounds.length + 1;
    //let roundCounter = (rounds.length/2);
    $.each(rounds, function(i, round) {
        text += ("<tr><td>Set 2</td></tr>");
        text += (`<tr><td colspan=3 align='center'><b>Round ${roundCounter}</b></td></tr>`);
        text += (`<tr><td align='right'><b>Date</b></td><td colspan=2 class='text-left'><input type='text' id='A_${formNo}_date_${roundCounter - 1}' style='width:100px'></td></tr>`);
        $.each(round, function(y, match) {
            const [home, away] = match.split(" v ");
            text += (`
                <tr>
                    <td align='center'><input class='float-child' type='text' id='A_${teamGrade}_home_${roundCounter}_${y + 1}' value='${away}' style='width:200px'></td>
                    <td align='center'>v</td>
                    <td align='center'><input class='float-child' type='text' id='A_${teamGrade}_away_${roundCounter}_${y + 1}' value='${home}' style='width:200px'></td>
                </tr>
            `);
        });
        roundCounter++;
    });
*/
    // Third half (same as first)
    $.each(rounds, function(i, round) {
        console.log(round);
        const roundNum = i + 1;
        //let homeOnly = (roundNum >= 12);
        text += ("<tr><td>Set 3</td></tr>");
        text += (`<tr><td colspan=3 align='center'><b>Round ${roundNum}</b></td></tr>`);
        text += (`<tr><td align='right'><b>Date</b></td><td colspan=2 class='text-left'><input type='text' id='A_${formNo}_date_${roundNum - 1}' style='width:100px'></td></tr>`);
        $.each(round, function(x, match) {
            const [home, away] = match.split(" v ");
            text += (`
                <tr>
                    <td align='center'><input class='float-child' type='text' id='A_${teamGrade}_home_${roundNum}_${x + 1}' value='${home}' style='width:200px'></td>
                    <td align='center'>v</td>
                    <td align='center'><input class='float-child' type='text' id='A_${teamGrade}_away_${roundNum}_${x + 1}' value='${away}' style='width:200px'></td>
                </tr>
            `);
        });
    });
    
    text += ("</tbody>");
    text += ("</table>");
    //console.log(text);
    $('#fixture_container').append(text);
    //console.log($('#fixture_container').html())
}

let teams = 12;
//let names = teamList; // team names
let names = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"]; // team names
let formNo = 1;
let teamGrade = "BVS2";

let fixtures = generateFixtures(teams, names);

//let baseRounds = generateFixtures(teams, names);

//let rounds1to7 = baseRounds;  // first cycle
//let rounds8to11 = baseRounds.slice(0, 4);  // partial repeat
//let rounds12to18 = baseRounds;  // final home-only

// Combine:
//let finalRounds = rounds1to7.concat(rounds8to11).concat(rounds12to18);

//displayFixtures(finalRounds, formNo, teamGrade);

displayFixtures(fixtures, formNo, teamGrade);
</script>

</body>
</html>
