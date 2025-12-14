<?php
if (!isset($_SESSION)) 
{
  session_start();
}

include('server_name.php');
include('connection.inc');

date_default_timezone_set('Australia/Melbourne');
$date = date('Y-m-d \00:00:00');
$year = date('Y');
$month = date('m');

if($month < '08')
{
  $season = 'S1';
}
else
{
  $season = 'S2';
}
/*
$sql_fix_date = "Select date FROM tbl_fixtures WHERE Season = '" . $season . "' and Year = '" . $year . "' order by date desc";
//echo($sql_fix_date . "<br>");
$result_fix_date = $dbcnx_client->query($sql_fix_date);
$row_fix_date = $result_fix_date->fetch_assoc();
if(($row_fix_date['date'] < $date) && ($season == 'S1'))
{
  $season = 'S2';
}
*/

// added for ios app login
if($_GET['username'] == '')
{
  $username = $_POST['username'];
  $password = $_POST['password'];
}
else
{
  $username = $_GET['username'];
  $password = $_GET['password'];
}

if((($password) <> "") && (($username) <> "")) 
//if((($_POST['password']) <> "") && (($_POST['username']) <> "")) 
{
  $sql = "Select * FROM tbl_authorise WHERE Email = '" . $username . "'";
  $result = $dbcnx_client->query($sql);
  $row = $result->fetch_assoc();
  if($row['Active'] == 1)
  { 
    if(($row["Password"] <> null) && (password_verify($password, trim($row["Password"]))))
    {
      $playerID = $row["PlayerNo"];
      $email = $row["Email"];
      $password = $row["Password"];
      $login_rights = $row["Access"];
      $clubname = $row["Team_1"];
      
      $sql_members = "Select * FROM members WHERE Email = '" . $email . "'";
      $result_member = $dbcnx_client->query($sql_members);
      $row_member = $result_member->fetch_assoc();
      $firstname = $row_member["FirstName"];

      $_SESSION['session_id'] = session_id();
      $_SESSION['isloggedin'] = true;
      $_SESSION['playerID'] = $row["PlayerNo"];
      $_SESSION['firstname'] = $firstname;
      $_SESSION['login_rights'] = $login_rights;    
      $_SESSION['clubname'] = $clubname;
      $_SESSION['username'] = $email;
      $_SESSION['password'] = $password;
      $_SESSION['season'] = $season;
      $_SESSION['year'] = $year;
      
      $logincaption = "Welcome " . $_SESSION['firstname'] . "." . "<br><br>" . "You have logged in as " . $_SESSION['username'] . "." . "<br><br>" . "You have '" . $_SESSION['login_rights'] . "' rights.";
      $caption = "Please make a selection from the top menu.";

      if(isset($clubname)) 
      {
        if($row["Team_1"] != '')
        {
          $administercaption = "You are administering  " . $clubname . ".";
        }
        else
        {
          $administercaption = "There are no teams to administer, contact the <a href='mailto:scores@vbsa.org.au'>Scores Registrar.</a>";
        }
        if($row["Team_2"] != '')
        {
          $administercaption = "<div>You are currently administering  " . $clubname . "<br><br>
          To change team, select from dropdown&nbsp;   
          <select id='administer_team'>
          <option value='" . $clubname . "' selected='selected'>" . $clubname . "</option>');
          <option value='" . $row['Team_2'] . "'>" . $row['Team_2'] . "</option>";
          if($row["Team_3"] != '')
          {
            $administercaption .= "<option value='" . $row['Team_3'] . "'>" . $row['Team_3'] . "</option>";
          }
          $administercaption .= "</select>";
          $administercaption .= "</div>";
        }
      }
      else
      {
        $administercaption = "";
      }  
    }
    else
    {
      header("Location: incorrectlogin.php");
    }
  }
  else
  {
    header("Location: unathorised.php");
  }
}
else
{
    header("Location: incorrectlogin.php");
}

//echo("PWD " . $_GET['password'] . ", UName " . $_GET['username'] . "<br>");
include ("header.php");

?>
<script language="JavaScript" type="text/JavaScript">

$(document).ready(function()
{
  $("#administer_team").change(function () {
    var team = $('#administer_team').val();
    $.ajax({
      url:"<?= $url ?>/check_team_admin.php?clubname=" + team,
      success : function(response){
        //alert("Team Administered Changed to " + response);
        $('#team_select').submit();
      }
    });
  });
});

</script>
<center>
<form name="team_select" id="team_select" method="post" action="update_session_clubname.php">
<input type="hidden" name="SelectedTeam" />
<input type="hidden" name="ButtonName" />
<div class="">
    <div class="page-title"></div>
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <div class="clearfix"></div>
        </div>
        <div class="x_content"> 
			    <div>
			      <div align="center" colspan="2"><?php echo($logincaption); ?><br><br></div>
            <div align="center" colspan="2"><?php echo($administercaption); ?><br><br>Please make a selection from the top menu.</div>
			    </div>
			  </div>
     </div>
    </div>
</div>
</form>
</center>
<?php
include("footer.php"); 
?>
