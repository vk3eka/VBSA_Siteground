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

function LogAccess() 
{
  global $dbcnx_client;
  //date_default_timezone_set('Australia/Melbourne');
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
<script type="text/javascript">
/*
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' should be 5.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
*/
</script>
</head>
<body id="vbsa">
<!-- Include Google Tracking -->
<script>
/*
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-7874393-1', 'auto');
  ga('send', 'pageview');
*/
</script>
<div class="container"> 
<div class="new_header" style="width: 100%; background-color: black; margin-bottom: 13px;">
<img src="https://vbsa.org.au/ui_assets/Logo-full-lockup_horizontal_invert.svg" style="margin: auto; display: block; padding: 25px; max-width: 600px; width: calc(100% - 50px);">
</div>    
    <!-- Include header -->
<!--<div class="visible-md visible-lg" style="margin-left:5%; margin-right:5%">
  <h1 style="text-align: center; padding-bottom: 10px">Victorian Billiards & Snooker Association Inc.</h1> 
  <h4 style="color:#900; text-align: center; padding-bottom: 10px;">Representing & Developing Billiards &amp; Snooker as a sport in Victoria</h4> 
</div>

<div class="visible-sm visible-xs"style="margin-left:5%; margin-right:5%">
  <h1 style="text-align: center; padding-bottom: 10px">VBSA</h1> 
  <h6 style="text-align: center; padding-bottom: 10px">(Victorian Billiards & Snooker Association Inc.)</h6>  
  <h5 style="color:#900; text-align: center; padding-bottom: 10px">Representing & Developing Billiards &amp; Snooker as a sport in Victoria</h5> 
</div>-->
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
            echo("<li><a href='" . $url . "/create_fixture_upload.php" . "'>Upload Fixture List</a></li>");
            echo("<li><a href='" . $url . "/edit_fixtures.php'>Edit Fixture List</a></li>");
            echo("<li><a href='" . $url . "/authorise.php" . "'>Authorise</a></li>");
            echo("<li><a href='" . $url . "/captain_authorise.php" . "'>Add Captain</a></li>");
            echo("<li><a href='" . $url . "/list_players.php" . "'>List Players</a></li>");
            echo("<li><a href='" . $url . "/list_player_scores.php" . "'>Player Scores</a></li>");
            echo("<li><a href='" . $url . "/scores_analysis.php" . "'>Scores Analysis</a></li>");
            echo("<li><a href='" . $url . "/list_team_scores.php" . "'>Team Scores</a></li>");
            echo("<li><a href='" . $url . "/change_season.php" . "'>Change Season/Year</a></li>");
            echo("<li><a href='" . $url . "/grade_settings.php" . "'>Change Grade Settings</a></li>");
            echo("</ul>");
            //echo("<li class='info'><a class='dropdown-toggle' data-toggle='dropdown' href='#''>Fixture Creation</a>");
            //echo("<ul class='dropdown-menu'>");
            //echo("<li><a href='" . $url . "/fixture_grid.php" . "'>Generate Fixtures</a></li>");
            //echo("<li><a href='" . $url . "/create_fixture_test.php'>Create Fixture List</a></li>");
            //echo("</ul>");
            echo("<li><a href='" . $url . "/change_pwd.php'>Change Password</a></li>");
            echo("<li><a href='https://vbsa.org.au'>Log Out</a></li>");
          }
          if($_SESSION['login_rights'] == 'Team Administrator')
          {
            echo("<li><a href='" . $url . "/select_fixtures.php'>Select Fixture</a></li>");
            echo("<li class='info'><a class='dropdown-toggle' data-toggle='dropdown' href='#''>Database Management</a>");
            echo("<ul class='dropdown-menu'>");
            echo("<li><a href='" . $url . "/captain_authorise.php" . "'>Captain Authorise</a></li>");
            //echo("<li><a href='" . $url . "/select_team_players.php" . "'>Setting Pennant Team</a></li>");
            echo("<li><a href='" . $url . "/list_player_scores.php" . "'>Player Scores</a></li>");
            echo("<li><a href='" . $url . "/scores_analysis.php" . "'>Scores Analysis</a></li>");
            echo("</ul>");
            echo("<li><a href='" . $url . "/change_pwd.php'>Change Password</a></li>");
            echo("<li><a href='https://vbsa.org.au'>Log Out</a></li>");
          }
          if($_SESSION['login_rights'] == 'Team Captain')
          {
            echo("<li><a href='" . $url . "/select_fixtures.php'>Select Fixture</a></li>");
            //echo("<li><a href='" . $url . "/select_team_players.php" . "'>Setting Pennant Team</a></li>");
            echo("<li><a href='" . $url . "/list_player_scores.php" . "'>Player Scores</a></li>");
            echo("<li><a href='" . $url . "/captain_authorise.php" . "'>Add Captain </a></li>");
            echo("<li><a href='" . $url . "/change_pwd.php'>Change Password</a></li>");
            echo("<li><a href='https://vbsa.org.au'>Log Out</a></li>");
          }
          ?>
        </ul>
        </li> 
      </ul>
    </div>
  </div>
</nav>
</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  
