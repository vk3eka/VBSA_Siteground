<!DOCTYPE html>
<html>
<head>
    <title>Fixture Generator</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div id="output"></div>

<script>
function main(fixtures, team_grade, form_no, year, season) {
    let teams = isNaN(parseInt(fixtures)) ? fixtures.split(", ") : nums(parseInt(fixtures));
    show_fixtures(teams, team_grade, form_no, year, season);
}

function nums(n) {
    let ns = [];
    for (let i = 1; i <= n; i++) {
        ns.push(i.toString());
    }
    return ns;
}

function team_name(num, names) {
    let i = num - 1;
    return names[i] && names[i].trim().length > 0 ? names[i].trim() : num;
}

function flip(match) {
    let components = match.split(" v ");
    return components[1] + " v " + components[0];
}

function show_fixtures(names, team_grade, form_no, year, season) {
    let teams = names.length;
    let output = $("#output");
    output.append("Teams " + teams + "<br>");

    let ghost = false;
    if (teams % 2 === 1) {
        names.push("Ghost");
        teams++;
        ghost = true;
    }

    let totalRounds = teams - 1;
    let matchesPerRound = teams / 2;
    let rounds = [];

    for (let i = 0; i < totalRounds; i++) {
        rounds[i] = [];
    }

    for (let round = 0; round < totalRounds; round++) {
        for (let match = 0; match < matchesPerRound; match++) {
            let home = (round + match) % (teams - 1);
            let away = (teams - 1 - match + round) % (teams - 1);
            if (match === 0) away = teams - 1;
            rounds[round][match] = team_name(home + 1, names) + " v " + team_name(away + 1, names);
        }
    }

    // Interleave rounds
    let interleaved = new Array(totalRounds).fill(null).map(() => []);
    let evn = 0, odd = matchesPerRound;
    for (let i = 0; i < rounds.length; i++) {
        interleaved[i] = (i % 2 === 0) ? rounds[evn++] : rounds[odd++];
    }

    rounds = interleaved;

    // Flip home/away for last team in odd rounds
    for (let round = 0; round < rounds.length; round++) {
        if (round % 2 === 1) {
            rounds[round][0] = flip(rounds[round][0]);
        }
    }

    // First half
    for (let i = 0; i < rounds.length; i++) {
        output.append("Round " + (i + 1) + "<br>");
        output.append("date => (A_" + form_no + "_date_" + i + ")<br>");
        rounds[i].forEach((r, x) => {
            let [home, away] = r.split(" v ");
            output.append("A_" + team_grade + "_home_" + (i + 1) + "_" + (x + 1) + " = " + home + "<br>");
            output.append("A_" + team_grade + "_away_" + (i + 1) + "_" + (x + 1) + " = " + away + "<br>");
        });
    }

    // Second half (mirrored)
    let round_counter = rounds.length + 1;
    for (let b = 0; b < rounds.length; b++) {
        output.append("Round " + round_counter + "<br>");
        output.append("A_" + form_no + "_date_" + (round_counter - 1) + "<br>");
        rounds[b].forEach((r, y) => {
            let [home, away] = r.split(" v ");
            output.append("A_" + team_grade + "_home_" + (round_counter) + "_" + (y + 1) + " = " + away + "<br>");
            output.append("A_" + team_grade + "_away_" + (round_counter) + "_" + (y + 1) + " = " + home + "<br>");
        });
        round_counter++;
    }
}

// Example call
$(document).ready(function() {
    main("Team A, Team B, Team C, Team D, Team E, Team F", "A", 1, 2025, "Spring");
});
</script>

</body>
</html>