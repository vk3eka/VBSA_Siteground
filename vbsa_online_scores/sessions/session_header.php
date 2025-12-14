<?php
if (!isset($_SESSION)) {
  session_start();
}
include('server_name.php');
include('connection.inc');

// check if member logged in

if(!isset($_SESSION['isloggedin']))
{
  header("Location: session_index.php");
}
else
{

function LogAccess() 
{
  global $dbcnx_client;
  date_default_timezone_set('Australia/Melbourne');
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

    <!-- Include header -->
<div class="visible-md visible-lg" style="margin-left:5%; margin-right:5%">
  <h1 style="text-align: center; padding-bottom: 10px">Victorian Billiards & Snooker Association Inc.</h1> 
  <h4 style="color:#900; text-align: center; padding-bottom: 10px;">Representing & Developing Billiards &amp; Snooker as a sport in Victoria</h4> 
  <!--<h6 style="text-align: center; padding-bottom: 10px">VBSA Patron: The Honourable Linda Dessau AC, Governor of Victoria.  </h6> -->  
</div>

<div class="visible-sm visible-xs"style="margin-left:5%; margin-right:5%">
  <h1 style="text-align: center; padding-bottom: 10px">VBSA</h1> 
  <h6 style="text-align: center; padding-bottom: 10px">(Victorian Billiards & Snooker Association Inc.)</h6>
  <!--<h6 style="text-align: center; padding-bottom: 10px">VBSA Patron: The Honourable Linda Dessau AC, Governor of Victoria.  </h6>   --> 
  <h5 style="color:#900; text-align: center; padding-bottom: 10px">Representing & Developing Billiards &amp; Snooker as a sport in Victoria</h5> 
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
            echo("<li><a href='" . $url . "/session_fixtures.php'>Session Fixture</a></li>");
            echo("<li><a href='" . $url . "/session_tabs.php'>Test Tabs</a></li>");
            echo("<li><a href='" . $url . "/session_index.php?logout=Yes'>Log Out</a></li>");
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

<?php
}
?>
