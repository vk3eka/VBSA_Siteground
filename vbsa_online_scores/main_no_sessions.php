<?php

if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');

$sql_members = "Select * FROM tbl_authorise WHERE Email = '" . $_SESSION['username'] . "'";
$result_member = $dbcnx_client->query($sql_members);
$row = $result_member->fetch_assoc();

$logincaption = "Welcome " . $_SESSION['firstname'] . "." . "<br><br>" . "You have logged in as " . $_SESSION['username'] . "." . "<br><br>" . "You have '" . $_SESSION['login_rights'] . "' rights.";
$caption = "Please make a selection from the top menu.";

if(isset($_SESSION['clubname'])) 
{
  $administercaption = "You are administering  " . $_SESSION['clubname'] . ".";
  if($row["Team_2"] != '')
  {
    $administercaption = "<div>You are currently administering  " . $_SESSION['clubname'] . "<br><br>
    To change team, select from dropdown&nbsp;   
    <select id='administer_team'>
    <option value='" . $_SESSION['clubname'] . "' selected='selected'>" . $_SESSION['clubname'] . "</option>');
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
