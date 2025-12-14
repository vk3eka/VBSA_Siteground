<?php 

$num_team = 10;
$num_weeks = (($num_team*2)-2);
//$num_weeks = 5;

if($num_team % 2 != 0)
{
	$num_team++;
}

$n2 = (int)(($num_team-1)/2);

for($x = 0; $x < $num_team; $x++)
{
	$teams[$x] = ($x+1);
}

for($x = 0; $x < $num_weeks; $x++)
{
	echo("Round " . ($x+1) . "<br>");
	for($i = 0; $i < $n2; $i++)
	{
		$team1 = $teams[($n2-$i)];
		$team2 = $teams[($n2+$i+1)];
		$results[$team1][$x] = $team2;
		$results[$team2][$x] = $team1;
		echo($results[$team1][$x] . " v " . $results[$team2][$x] . "<br>");
	}
	echo("<br>");
	$tmp = $teams[1];
	for($i = 1; $i < (sizeof($teams) - 1); $i++)
	{
		$teams[$i] = $teams[$i+1];
	}
	$teams[sizeof($teams)-1] = $tmp;
}

 ?>