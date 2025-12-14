<?php
/*    
        event.preventDefault();
        var season = '<?= $season ?>';
        var year = '<?= $year?>';
        var dayplayed = '<?= $dayplayed?>';
        var team_grades = '<?= $team_grades>';
        var no_of_clashes = 0;
        $.fn.shuffle_fixtures(team_grades, "NoResponse");
        for(i = 1; i < 3; i++)
        {
            $.fn.save_fixtures(i, 'NoResponse');
        }
        alert('Shuffled');

        $.ajax({
            url:"get_clashes.php?TeamGrade=" + team_grades + "&Year=" + year + "&Season=" + season,
            method: 'GET',
            async: false,
            success : function(response)
            {
                no_of_clashes = response;
                alert("Clashes " + no_of_clashes);
                //if(action == 'Response')
                //{
                    //alert(response);
                //}
                //window.location.href = '<?= $_SERVER['PHP_SELF'] ?>?DayPlayed=' + dayplayed + '&season=' + season;
            },
            error: function (request, error) 
            {
              alert("No data saved!");
            }
        });
        
        //var no_of_clashes;
        var season = '<?= $season ?>';
        var year = '<?= $year?>';
        var dayplayed = '<?= $dayplayed?>';
        var no_of_clashes = 10;
        var team_grades = '<?= $team_grades>';
        //var grade = team_grades.split(",");
        //var form_no = 1;
        //alert(team_grades);
        //do 
        //{
            //setTimeout(function() { // why do i need this?????
            //   $.fn.shuffle_fixtures(team_grades, 'NoResponse');
            //}, 1000);

            $.ajax({
                url:"shuffle_fixtures.php?TeamGrade=" + team_grades + "&Year=" + year + "&Season=" + season,
                method: 'GET',
                async: false,
                success : function(response)
                {
                    //no_of_clashes = ($('#no_of_clashes').val());
                    //console.log(no_of_clashes);
                    //if(action == 'Response')
                    //{
                        alert(response);
                    //}
                    //window.location.href = '<?= $_SERVER['PHP_SELF'] ?>?DayPlayed=' + dayplayed + '&season=' + season;
                },
                error: function (request, error) 
                {
                  alert("No data saved!");
                }
            });
        
        $.ajax({
            url:"get_clashes.php?TeamGrade=" + team_grades + "&Year=" + year + "&Season=" + season,
            method: 'GET',
            async: false,
            success : function(response)
            {
                no_of_clashes = response;
                console.log("Clashes " + no_of_clashes);
                //if(action == 'Response')
                //{
                    //alert(response);
                //}
                //window.location.href = '<?= $_SERVER['PHP_SELF'] ?>?DayPlayed=' + dayplayed + '&season=' + season;
            },
            error: function (request, error) 
            {
              alert("No data saved!");
            }
        });

        //while (no_of_clashes > 20) 
        //for(i = 0; i < 10; i++)
        //{
            setTimeout(function() { // why do i need this?????
               $.fn.shuffle_fixtures(team_grades, 'NoResponse');
            }, 1000);

            //$.fn.shuffle_fixtures(team_grades, 'NoResponse');
 
           for(x = 0; x < 3; x++) // number of forms
            { 
                $.fn.save_fixtures(x, 'NoResponse');
            }
            //no_of_clashes = ($('#no_of_clashes').val());
            //no_of_clashes = ('<?= $clashes ?>');
            //console.log("Clashes " + no_of_clashes);
           //alert(no_of_clashes);
        //}
        //while (no_of_clashes <= 10)
        */
//include('fixture_gen_functions_js.js');
?>
<!-- Include jQuery -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="fixture_gen_functions_js.js"></script>

<div id="output"></div>

<script>
/*
function addWeek(startDate, days, round) {
    const date = new Date(startDate);
    date.setDate(date.getDate() + days * round);
    return date.toISOString().split('T')[0];
}

function flip(match) {
    const parts = match.split(" v ");
    return parts[1] + " v " + parts[0];
}

function team_name(num, names) {
    return names[num - 1]?.trim() || num;
}

function showFixtures(names, team_grade, form_no, year, season, start_date) {
    const teams = names.length;
    const container = $("#fixture-output");
    if (teams !== 6) return; // only support 6-team format for now

    let ghost = false;
    let rounds = [];

    // Cyclic fixture generation
    const totalRounds = teams - 1;
    const matchesPerRound = teams / 2;

    for (let round = 0; round < totalRounds; round++) {
        let roundMatches = [];
        for (let match = 0; match < matchesPerRound; match++) {
            let home = (round + match) % (teams - 1);
            let away = (teams - 1 - match + round) % (teams - 1);
            if (match === 0) away = teams - 1;
            roundMatches.push(team_name(home + 1, names) + " v " + team_name(away + 1, names));
        }
        rounds.push(roundMatches);
    }

    // Interleave
    let interleaved = [];
    let evn = 0, odd = teams / 2;
    for (let i = 0; i < rounds.length; i++) {
        interleaved[i] = i % 2 === 0 ? rounds[evn++] : rounds[odd++];
    }
    rounds = interleaved;

    // Flip last team on odd rounds
    for (let i = 0; i < rounds.length; i++) {
        if (i % 2 === 1) {
            rounds[i][0] = flip(rounds[i][0]);
        }
    }

    // Generate HTML table
    let table = $("<table>").addClass("table table-striped table-bordered").css("width", "1000px");
    let tbody = $("<tbody>").addClass("row_position_10");
    table.append(tbody);
    tbody.append(`<tr><td colspan="3" align="center">(Algorithm)</td></tr>`);

    function renderRounds(roundSet, offset = 0, reverse = false) {
        for (let i = 0; i < roundSet.length; i++) {
            let roundNum = i + 1 + offset;
            let round = reverse ? roundSet[i].map(r => flip(r)) : roundSet[i];
            tbody.append(`<tr><td>&nbsp;</td></tr>`);
            tbody.append(`<tr><td colspan="3" align="center"><b>Round ${roundNum}</b></td></tr>`);
            tbody.append(`
                <tr>
                    <td align="right"><b>Date</b></td>
                    <td colspan="2" class="text-left">
                        <input type="text" id="A_${form_no}_date_${roundNum - 1}" value="${addWeek(start_date, 7, roundNum)}" style="width:100px">
                    </td>
                </tr>
            `);
            round.forEach((match, j) => {
                const [home, away] = match.split(" v ");
                tbody.append(`
                    <tr>
                        <td align="center">
                            <input class="float-child" type="text" id="A_${team_grade}_home_${roundNum}_${j + 1}" value="${home}" style="width:200px">
                        </td>
                        <td align="center">v</td>
                        <td align="center">
                            <input class="float-child" type="text" id="A_${team_grade}_away_${roundNum}_${j + 1}" value="${away}" style="width:200px">
                        </td>
                    </tr>
                `);
            });
        }
    }

    // Render 5 sets of rounds (like PHP)
    renderRounds(rounds, 0, false);       // Set 1
    renderRounds(rounds, 6, true);        // Set 2
    renderRounds(rounds, 12, false);      // Set 3
    //renderRounds(rounds, 18, true);       // Set 4
    //renderRounds(rounds, 24, false);      // Set 5

    container.append(table);
}

// Example usage
const teamNames = ["Brunswick Titan", "YCBSC Break Builders", "Brunswick Taylor", "Dandy RSL Ball Breakers", "Brunswick Eagles", "NBC Mustangs"];
const teamGrade = "APS";
const formNo = 1;
const year = 2025;
const season = "S1";
const startDate = "2025-06-01";

$(document).ready(function () {
    showFixtures(teamNames, teamGrade, formNo, year, season, startDate);
});
*/
</script>
<!--
<!DOCTYPE html>
<html>
<head>
    <title>6-Team Round Robin Fixture</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<h2>6-Team Round Robin Fixture</h2>
<textarea id="fixtureOutput" rows="20" cols="50"></textarea>

<script>
function generateRoundRobinFixture(teams) {
    if (teams.length % 2 !== 0) {
        teams.push("BYE"); // Add bye if odd number of teams
    }

    const totalRounds = teams.length - 1;
    const matchesPerRound = teams.length / 2;
    let rounds = [];

    for (let round = 0; round < totalRounds; round++) {
        let roundMatches = [];
        for (let match = 0; match < matchesPerRound; match++) {
            let home = (round + match) % (teams.length - 1);
            let away = (teams.length - 1 - match + round) % (teams.length - 1);
            if (match === 0) away = teams.length - 1;
            roundMatches.push(teams[home] + " vs " + teams[away]);
        }
        rounds.push(roundMatches);
    }

    // Flip home/away in alternate rounds
    for (let r = 0; r < rounds.length; r++) {
        if (r % 2 === 1) {
            let parts = rounds[r][0].split(" vs ");
            rounds[r][0] = parts[1] + " vs " + parts[0];
        }
    }

    return rounds;
}

$(document).ready(function () {
    const teams = ["Team A", "Team B", "Team C", "Team D", "Team E", "Team F"];
    const rounds = generateRoundRobinFixture(teams);

    console.log(rounds);

    let output = "";
    $.each(rounds, function (i, matches) {
        output += "Round " + (i + 1) + ":\n";
        $.each(matches, function (j, match) {
            output += match + "\n";
        });
        output += "\n";
    });

    $("#fixtureOutput").val(output);
});
</script>

</body>
</html>
-->

<script>
// Example usage
fixtures = ["Brunswick Titan", "YCBSC Break Builders", "Brunswick Taylor", "Dandy RSL Ball Breakers", "Brunswick Eagles", "NBC Mustangs"];
team_grade = "APS";
form_no = 1;
year = 2025;
season = "S1";
startdate = "2025-06-01";


main(fixtures, team_grade, form_no, year, season, startdate); 


</script>
