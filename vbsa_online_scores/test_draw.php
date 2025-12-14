<?php

$names = "Brunswick Champs Test
Brunswick Mafia Test
Bye
Camberwell All Stars
Camberwell Club All Stars
Camberwell Junction Test
Chelt Baulkers Test
FRSL Laggards Test
Kooyong Nets Test
RACV Blue Bloods Test
Test Ballarat Diggers
Yarra Crossing Test";

$names = explode("\n", $names);
$group1 = [];
$group2 = [];

for ($i = 0; $i < (count($names)/2); $i++)
{
    array_push($group1, $names[$i]); 
}
for ($i = (count($names)/2); $i < count($names); $i++)
{
    array_push($group2, $names[$i]); 
}

// Number of rounds
$rounds = 6;

// Initialize fixtures array
$fixtures = [];

// Generate fixtures for 6 rounds
$roundFixtures = [];

// Determine which group plays at home in this round
$homeGroup = $group2;
$awayGroup = $group1;
$x = 1;
// Generate fixtures for this round
foreach ($homeGroup as $homeTeam) {
  $round = 1;
    foreach ($awayGroup as $awayTeam) {
        switch($x) // sort fixtures so a team doesn't play more than one team per night 
        {
          case 1:
            $round = 1;
            break;
          case 2:
            $round = 2;
            break;
          case 3:
            $round = 3;
            break;
          case 4:
            $round = 4;
            break;
          case 5:
            $round = 5;
            break;
          case 6:
            $round = 6;
            break;
          case 7:
            $round = 2;
            break;
          case 8:
            $round = 3;
            break;
          case 9:
            $round = 4;
            break;
          case 10:
            $round = 5;
            break;
          case 11:
            $round = 6;
            break;
          case 12:
            $round = 1;
            break;
          case 13:
            $round = 3;
            break;
          case 14:
            $round = 4;
            break;
          case 15:
            $round = 5;
            break;
          case 16:
            $round = 6;
            break;
          case 17:
            $round = 1;
            break;
          case 18:
            $round = 2;
            break;
          case 19:
            $round = 4;
            break;
          case 20:
            $round = 5;
            break;
          case 21:
            $round = 6;
            break;
          case 22:
            $round = 1;
            break;
          case 23:
            $round = 2;
            break;
          case 24:
            $round = 3;
            break;
          case 25:
            $round = 5;
            break;
          case 26:
            $round = 6;
            break;
          case 27:
            $round = 1;
            break;
          case 28:
            $round = 2;
            break;
          case 29:
            $round = 3;
            break;
          case 30:
            $round = 4;
            break;
          case 31:
            $round = 6;
            break;
          case 32:
            $round = 1;
            break;
          case 33:
            $round = 2;
            break;
          case 34:
            $round = 3;
            break;
          case 35:
            $round = 4;
            break;
          case 36:
            $round = 5;
            break;
        }
        if ($round %2 == 0) { // round is even number
            // Home vs Away
            $matchup = "$homeTeam vs $awayTeam";
        } else {
            // Away vs Home (reverse fixtures)
            $matchup = "$awayTeam vs $homeTeam";
        }
        $team_names = explode(" vs ", $matchup);
        //echo("Totals " . $x . ", Round " . $round . ", " . $matchup . "<br>");
        //echo("Round " . $round . ", " . $matchup . "<br>");
        $roundFixtures[$x] = $round . ", " . $team_names[0] . ", " . $team_names[1];
        //$roundFixtures[] = $matchup;
        $round++;
        $x++;
    }

}

sort($roundFixtures);
//foreach ($roundFixtures as $key => $val) {
//    echo "FixArray[" . $key . "] = " . $val . "<br>";
//}

echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
echo("<tbody class='row_position_10'>");
echo("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");
for ($i = 0; $i < 6; $i++) 
{
  echo("<tr><td>&nbsp;</td></tr>");
  echo("<td colspan=3 align='center'><b>Round " . ($i+11) . "</b></td></tr>");
  echo("<tr><td align='right'><b>Date</b></td>");
  echo("<td colspan=2 class='text-left'><input type='text' id='A_1_date_" . ($i+11) . "' style='width:100px'></td></tr>");
  echo("<tr>");
  $x = 0;
  foreach($roundFixtures as $fixture)
  {
    $new_array = explode(", ", $fixture);
    for ($y = 0; $y < 6; $y++) 
    {
      if($new_array[0] == ($i+1))
      {
          echo("<td align='center'><input class='float-child' type='text' id='A_PB(T)_home_" . ($i+11) . "_" . ($x+1) . "' value='" . ($new_array[1]) . "' style='width:200px'></td>");
          echo("<td align='center'>v</td>");
          echo("<td align='center'><input class='float-child' type='text' id='A_PB(T)_away_" . ($i+11) . "_" . ($x+1) . "' value='" . ($new_array[2]) . "' style='width:200px'></td>");
          echo("</tr>");
          break;
      }   
    }
    $x++;
  }
}
echo("</tbody>");
echo("</table>");

?>