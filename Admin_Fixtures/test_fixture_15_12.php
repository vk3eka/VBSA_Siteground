<?php

require_once('Models/Fixture.php');
$year = 2025;
$season = 'S2';
$dayplayed = 'Mon';
$fixture = new Fixture();
$fixture->LoadFixture($year, $season, $dayplayed);
$jsonData = json_encode($fixture);
$data = json_decode($jsonData, true);
//echo($jsonData . "<br>");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>12 Teams - 15 Rounds</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <style>
    table { border-collapse: collapse; margin: 20px; }
    td { border: 1px solid #ddd; padding: 4px; }
  </style>
</head>
<body>
<script>

//var teams = <?php echo json_encode($data['teams']); ?>;
//const names = $.map(teams, t => t.name);
//console.log(names);

</script>
<div id="fixture_container"></div>

<script>
function generateFixtures(teams, names) {
  let ghost = false;
  if (teams % 2 === 1) {
    teams++;
    ghost = true;
    names.push("BYE");
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

  // Interleave
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

  console.log(interleaved); // 
  return interleaved;
}

function flip(match) {
  const [home, away] = match.split(" v ");
  return `${away} v ${home}`;
}

function displayFixtures(allRounds) {
  let text = '';
  text += "<table>";
  text += "<tbody>";

  $.each(allRounds, function(i, round) {
    const roundNum = i + 1;
    const homeOnly = (roundNum > 11);

    text += `<tr><td colspan="3" align="center"><b>Round ${roundNum} ${homeOnly ? '(Home Only)' : ''}</b></td></tr>`;
    text += `<tr><td align="right"><b>Date</b></td><td colspan="2"><input type="text" id="date_${roundNum}" style="width:100px"></td></tr>`;

    $.each(round, function(x, match) {
      const [home, away] = match.split(" v ");
      if(homeOnly)
      {
        text += `
          <tr>
            <td align="center"><input type="text" value="${away}" style="width:150px"></td>
            <td align="center">v</td>
            <td align="center"><input type="text" value="${home}" style="width:150px"></td>
          </tr>
        `;
      }
      else
      {
        text += `
        <tr>
          <td align="center"><input type="text" value="${home}" style="width:150px"></td>
          <td align="center">v</td>
          <td align="center"><input type="text" value="${away}" style="width:150px"></td>
        </tr>
      `;
    }
    });

    text += `<tr><td colspan="3">&nbsp;</td></tr>`;
  });

  text += "</tbody></table>";
  $('#fixture_container').append(text);
}

$(document).ready(function() {
  //const teams = 12;
  //const names = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"];

  var teams = <?php echo json_encode($data['teams']); ?>;
  const names = $.map(teams, t => t.name);
  //console.log(names); // 


  const baseRounds = generateFixtures(12, names);
  //console.log(baseRounds); // 
  const rounds1to11 = baseRounds;        // full single round-robin
  const rounds12to15 = baseRounds.slice(0, 4); // pick first 7 rounds to repeat
  const finalRounds = rounds1to11.concat(rounds12to15);
  displayFixtures(finalRounds);
});
</script>

</body>
</html>
