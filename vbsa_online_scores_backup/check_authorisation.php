<?php

include("connection.inc");
include("php_functions.php");

$player_id = $_GET['ID'];
$team = $_GET['Team'];

$sql_authorise = "Select * from tbl_authorise where PlayerNo = " . $player_id;
$result_authorise = $dbcnx_client->query($sql_authorise);
$num_rows_authorise = $result_authorise->num_rows;
if($num_rows_authorise > 0) 
{
  $row = $result_authorise->fetch_assoc();
  if($row['Password'] != '')
  {
    if($row['Team_1'] != '')
    {
      $teams = $row['Team_1'];
      if($row['Team_2'] != '')
      {
        $teams = $row['Team_1'] . ' and ' . $row['Team_2'];
        if($row['Team_3'] != '')
        {
          $teams = $row['Team_1'] . ', ' . $row['Team_2'] . ' and ' . $row['Team_3'];
          $caption = $row['Name'] . ' is already a captain for three teams.';
          echo($caption);
        }
      }
    }
    $caption = $row['Name'] . ' already has a login for ' . $teams . '.<br>' . $team . ' will be added to their login.';
    echo($caption);
  }
  else
  {
    $caption = 'No Password';
    echo($caption);
  }
  
}
else
{
  $caption = 'Not Listed';
  echo($caption);
}

?>