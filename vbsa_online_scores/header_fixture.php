<?php
if (!isset($_SESSION)) 
{
  session_start();
}

error_reporting(0);

date_default_timezone_set('Australia/Melbourne');

include('server_name.php');
include('connection.inc');

// check if member logged in
if(!isset($_SESSION['isloggedin']))
{
  header("Location: index.php");
}

$clubname = $_SESSION['clubname'];

function LogAccess() 
{
  global $dbcnx_client;
  $date = date('Y-m-d H:i', time());
  $page = basename($_SERVER['PHP_SELF']);
  $sql = "Insert into tbl_alertlog (username, login_date_time, login_ip, login_comments) VALUES ('" . $_SESSION['username'] . "', '" . $date . "', '" . $_SERVER['REMOTE_ADDR'] . "', '" . $page . "')";
  $update = $dbcnx_client->query($sql);
  if(! $update )
  {
    die("Could not update log: " . mysqli_error($dbcnx_client));
  }   
}

LogAccess();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Score Sheet</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link href="https://vbsa.org.au/CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="vbsa">
<div class="container"> 
<div class="new_header" style="width: 100%; background-color: black; margin-bottom: 13px;">
<img src="https://vbsa.org.au/ui_assets/Logo-full-lockup_horizontal_invert.svg" style="margin: auto; display: block; padding: 25px; max-width: 600px; width: calc(100% - 50px);">
</div>    
<!-- Include navigation -->
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
         <?php
          if($_SESSION['login_rights'] == 'Administrator')
          {
            echo("<li><a href='" . $url . "/select_fixtures.php'>Select Fixture</a></li>");
            echo("<li class='info'><a class='dropdown-toggle' data-toggle='dropdown' href='#''>Database Management</a>");
            echo("<ul class='dropdown-menu'>");
            echo("<li><a href='" . $url . "/create_fixture_upload.php'>Upload Fixture List</a></li>");
            echo("<li><a href='" . $url . "/edit_fixtures.php'>Edit Fixture List</a></li>");
            echo("<li><a href='" . $url . "/authorise.php'>Authorise</a></li>");
            echo("<li><a href='" . $url . "/select_team_players.php'>Setting Pennant Team</a></li>");
            echo("<li><a href='" . $url . "/captain_authorise.php'>Add Captain</a></li>");
            echo("<li><a href='" . $url . "/list_players.php'>List Players</a></li>");
            echo("<li><a href='" . $url . "/list_player_scores.php'>Player Scores</a></li>");
            echo("<li><a href='" . $url . "/scores_analysis.php'>Scores Analysis</a></li>");
            echo("<li><a href='" . $url . "/list_team_scores.php'>Team Scores</a></li>");
            echo("<li><a href='" . $url . "/change_season.php'>Change Season/Year</a></li>");
            echo("<li><a href='" . $url . "/grade_settings.php'>Change Grade Settings</a></li>");
            echo("</ul>");
            echo("<li><a href='" . $url . "/change_pwd.php'>Change Password</a></li>");
            echo("<li><a href='https://vbsa.org.au'>Log Out</a></li>");
          }
          if($_SESSION['login_rights'] == 'Team Administrator')
          {
            echo("<li><a href='" . $url . "/select_fixtures.php'>Select Fixture</a></li>");
            echo("<li class='info'><a class='dropdown-toggle' data-toggle='dropdown' href='#''>Database Management</a>");
            echo("<ul class='dropdown-menu'>");
            echo("<li><a href='" . $url . "/captain_authorise.php'>Add Captain</a></li>");
            echo("<li><a href='" . $url . "/list_player_scores.php'>Player Scores</a></li>");
            echo("<li><a href='" . $url . "/scores_analysis.php'>Scores Analysis</a></li>");
            echo("</ul>");
            echo("<li><a href='" . $url . "/change_pwd.php'>Change Password</a></li>");
            echo("<li><a href='https://vbsa.org.au'>Log Out</a></li>");
          }
          if($_SESSION['login_rights'] == 'Team Captain')
          {
            // added for a no team login...............
            if($clubname === 'Temp Login')
            {
              echo("<li><a href='" . $url . "/team_registration.php'>Pennant Team Registration</a></li>");
              echo("<li><a href='" . $url . "/change_pwd.php'>Change Password</a></li>");
              echo("<li><a href='https://vbsa.org.au'>Log Out</a></li>");
            }
            else
            {
              echo("<li><a href='" . $url . "/select_fixtures.php'>Select Fixture</a></li>");
              echo("<li><a href='" . $url . "/list_player_scores.php'>Player Scores</a></li>");
              echo("<li><a href='" . $url . "/captain_authorise.php'>Add Captain </a></li>");
              echo("<li><a href='" . $url . "/change_pwd.php'>Change Password</a></li>");
              echo("<li><a href='https://vbsa.org.au'>Log Out</a></li>");
            }
          }
          ?>
        </ul>
        </li> 
      </ul>
    </div>
  </div>
</nav>
</div>

  
