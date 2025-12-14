<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header_vbsa.php');
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

$sql_fix_date = "Select date FROM tbl_fixtures WHERE Season = '" . $season . "' and Year = '" . $year . "' order by date desc";
$result_fix_date = $dbcnx_client->query($sql_fix_date);
$row_fix_date = $result_fix_date->fetch_assoc();
if(($row_fix_date['date'] < $date) && ($season == 'S1'))
{
  $season = 'S2';
}

if((($_POST['password']) <> "") && (($_POST['username']) <> "")) 
{
  $sql = "Select * FROM tbl_authorise WHERE Email = '" . $_POST['username'] . "'";
  $result = $dbcnx_client->query($sql);
  $row = $result->fetch_assoc();
  if($row['Active'] == 1)
  { 
    if(($row["Password"] <> null) && (password_verify($_POST['password'], trim($row["Password"]))))
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
      $_SESSION['password'] = $password;
      $_SESSION['login_rights'] = $login_rights;    
      $_SESSION['clubname'] = $clubname;
      $_SESSION['username'] = $email;
      $_SESSION['season'] = $season;
      $_SESSION['year'] = $year;
      $_SESSION['firstname'] = $firstname;
      echo '<script type="text/javascript"> window.open("main.php","_self");</script>';
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

?>
<script language="JavaScript" type="text/JavaScript">

function EnterPressedAlert(e, textarea){
  var code = (e.keyCode ? e.keyCode : e.which);
  if(code == 13) { //'Enter' keycode
   document.login.Login = 'true';
   document.login.submit();
  }
}

function LoginButton() {
  document.login.Login = 'true';
	document.login.submit();
}

</script>
<center>
<div class="">
  <div class="page-title"></div>
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <div class="clearfix"></div>
      </div>
      <div class="x_content"> 
      <div onKeyPress="EnterPressedAlert(event, this)">
        <form name="login" method="post" action="">
          <input type="hidden" name="Login" />
          <table border="0" cellspacing="0" cellpadding="0" id="reply" name="reply" onKeyPress="EnterPressedAlert(event, this)">
            <tr>
              <td align="center"><h3>Administrator/Team Captain Login Form</h3></td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center"><input type="text" class="form-control input-sm" name="username" style="width:250px" autofocus placeholder="Enter your Username" required=""/></td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center"><input type="password" name="password" class="form-control input-sm" style="width:250px" placeholder="Enter your Password" required="" /></td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center">
                <button type="submit" class="btn btn-primary">Login</button>
              </td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
        	  <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center"><a class='btn btn-default btn-xs'  href='<?= $url ?>/forgot.php?forgot=password'>Forgot Password?</a></td>
            </tr>    
          </table>
        </form>
        </div>
    </div>
    </div>
  </div>
</div>
</center>

<?php 

include('footer.php'); 

?>