<?php require_once('../Connections/connvbsa.php'); 
error_reporting(0);
include('../vbsa_online_scores/server_name.php');?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
      case "text":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;    
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
      case "double":
        $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
        break;
      case "date":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("Update members SET LastName=%s, FirstName=%s, HomeState=%s, HomePostcode=%s, HomePhone=%s, MobilePhone=%s, ReceiveSMS=%s, Email=%s, memb_occupation=%s, ReceiveEmail=%s, Overseas=%s, dob_day=%s, dob_mnth=%s, dob_year=%s, Gender=%s, promo_pref=%s, promo_optin_out=%s, memb_submit_date=%s, memb_by=%s WHERE MemberID=%s",
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['HomeState'], "text"),
                       GetSQLValueString($_POST['HomePostcode'], "text"),
                       GetSQLValueString($_POST['HomePhone'], "text"),
                       GetSQLValueString($_POST['MobilePhone'], "text"),
                       GetSQLValueString(isset($_POST['rec_sms']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['occupation'], "text"),
                       GetSQLValueString(isset($_POST['rec_email']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['Overseas'], "text"),
                       GetSQLValueString($_POST['dob_day'], "int"),
                       GetSQLValueString($_POST['dob_month'], "int"),
                       GetSQLValueString($_POST['dob_year'], "int"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['promo_pref'], "text"),
                       GetSQLValueString(isset($_POST['promo_optin_out']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['app_date'], "date"),
                       GetSQLValueString('Online', "text"),
                       GetSQLValueString($_POST['memberID'], "int"));
  //echo("Select " . $updateSQL . "<br>");
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  // move original entry to archive
  //$updateArchive = "Update tbl_membership_online set archive = 1 where id  = " . $_POST['id'];
  //mysql_select_db($database_connvbsa, $connvbsa);
  //echo($updateArchive); 
  //$result = mysql_query($updateArchive, $connvbsa) or die(mysql_error());

  $insertGoTo = $_SESSION['page'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  //header(sprintf("Location: %s", $insertGoTo));
  header("Location: vbsa_online_member_list.php"); 
}
else
{
  mysql_select_db($database_connvbsa, $connvbsa);
  $query_compare = "Select * from tbl_membership_online WHERE id = " . $_POST['Applications'];
  $member_compare = mysql_query($query_compare, $connvbsa) or die(mysql_error());
  $row_compare = mysql_fetch_assoc($member_compare);
  $totalRows_compare = mysql_num_rows($member_compare);

  // data for add player dropdown
  $players = array();
  $sql_players = "Select MemberID, FirstName, LastName From members Order By LastName";
  $search_list = mysql_query($sql_players, $connvbsa) or die(mysql_error());
  $num_rows = mysql_num_rows($search_list);
  if ($num_rows > 0) 
  {
    while($build_data = mysql_fetch_assoc($search_list)) 
    {
      $firstname = $build_data['FirstName'];
      $lastname = $build_data['LastName'];
      $players[] = $build_data['FirstName'] . " " . $build_data['LastName']; 
    }
    $player_data = json_encode($players);
  }
  $search_list->free_result();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Member Compare</title>
<!-- check jquery for autocomplete dropdown -->
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!-- -->
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="1000" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<script>

$(document).ready(function()
{
  var availableTags = <?php echo $player_data; ?>;
  $("#tags").autocomplete({
    source:  availableTags,
    appendTo: "#autocompleteAppendToMe"
  });

  $('#newplayer').click(function(event){
    event.preventDefault();
    var new_player = $('#tags').val();
    if(new_player == '')
    {
      alert("No player to check!");
      return;
    }
    $.ajax({
      url:"<?= $url ?>/check_player.php?fullname=" + new_player,
      success : function(data)
      {
        player_obj = jQuery.parseJSON(data);
        if(player_obj[2] != '')
        {
          $('#FirstName').val(player_obj[0]);
          $('#LastName').val(player_obj[1]);
          $('#memberID').val(player_obj[2]);
          $('#MobilePhone').val(player_obj[3]);
          $('#HomeState').val(player_obj[4]);
          $('#HomePhone').val(player_obj[5]);
          $('#HomePostcode').val(player_obj[6]);
          $('#Email').val(player_obj[7]);
          $('#Overseas').val(player_obj[8]);
          $('#dob_day').val(player_obj[9]);
          $('#dob_month').val(player_obj[10]);
          $('#dob_year').val(player_obj[11]);
          if(player_obj[12] == 1)
          {
            $('#referee').prop('checked', true);
          }
          else
          {
            $('#referee').prop('checked', false);
          }
          $('#ref_class').val(player_obj[13]);
          $('#occupation').val(player_obj[14]);
          if(player_obj[15] == 1)
          {
            $('#rec_email').prop('checked', true);
          }
          else
          {
            $('#rec_email').prop('checked', false);
          }
          if(player_obj[16] == 1)
          {
            $("#rec_sms").prop('checked', true);
          }
          else
          {
            $('#rec_sms').prop('checked', false);
          }
          $('#promo_pref').val(player_obj[17]);
          if(player_obj[18] == 1)
          {
            $("#promo_optin_out").prop('checked', true);
          }
          else
          {
            $('#promo_optin_out').prop('checked', false);
          }
          $('#gender').val(player_obj[19]);
        }
      },
      error: function (request, error) {
        alert("Error");
      }
    });
  });
});

</script>
<form name="form2" method="post" action='<?php echo $editFormAction; ?>'>
<input type="hidden" name="MM_update" id="MM_update" value="form2" />
<input type="hidden" name="id" id="id" value="<?= $row_compare['id'] ?>" />
<input type="hidden" name="app_date" id="app_date" value="<?= $row_compare['entered_on'] ?>" />

<table width="1000" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="left" class="red_bold">Compare Online with existing member</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan=2>Select the member you wish to compare from the drop down in the Existing members form.</td>
  </tr>
  <tr>
    <td colspan=2>You can edit the existing member to change the record based on the online application record.</td>
  </tr>
  <tr>
    <td colspan=2>Click 'Save Changes' to save the edited page to the members table.</td>
  </tr>
  <tr>
    <td colspan=2>You can return to the Online Applications page to delete the entry.</td>
  </tr>
  <tr>
    <td colspan=2>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><h3>Online Application</h3></td>
    <td align="center"><h3>Existing Member</h3></td>
</table>
<table style='width:250px' align="center">
  <tr>
    <td>
      <table style='width:250px' align="left" class="tableborder" >
        <tr>
          <td colspan=4 style='height:22px' >&nbsp;</td>
        </tr>
        <tr>
          <td colspan=4>&nbsp;</td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">First Name:</td>
          <td><input type="text" value="<?= $row_compare['FirstName'] ?>" size="20" /></td>
          <td align="right" nowrap="nowrap">Last Name:</td>
          <td><input type="text" value="<?= $row_compare['LastName'] ?>" size="20" /></td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">Home Phone:</td>
          <td><input type="text" value="<?= $row_compare['HomePhone'] ?>" size="20" /></td>
          <td align="right" nowrap="nowrap">Mobile Phone:</td>
          <td><input type="text" value="<?= $row_compare['MobilePhone'] ?>" size="20" /></td>
        </tr>
        <tr>
          <td align="right">State:</td>
          <td><select>
            <option value="<?= $row_compare['HomeState'] ?>" selected="selected" ><?= $row_compare['HomeState'] ?></option>
            <option value="" >No Entry</option>
            <option value="Vic" selected="selected" >Vic</option>
            <option value="NT">NT</option>
            <option value="NSW">NSW</option>
            <option value="Qld">Qld</option>
            <option value="SA">SA</option>
            <option value="Tas">Tas</option>
            <option value="WA">WA</option>
          </select></td>
          <td align="right">Postcode:</td>
          <td><input type="text" value="<?= $row_compare['HomePostcode'] ?>" size="20" /></td>
        </tr>
        <?php
        /*
        if($row_compare['Overseas'] == 1)
        {
          $os_checked = 'checked';
        }
        else
        {
          $os_checked = '';
        }
        */
        ?>
        <tr>
          <td align="right" nowrap="nowrap">Email:</td>
          <td><input type="text" value="<?= $row_compare['Email'] ?>" size="20" /></td>
          <td align="right">Country:</td>
          <td><input type="text" value="<?= $row_compare['Overseas'] ?>" size="20" /></td>
          <!--<td><input type="checkbox" <?= $os_checked ?>></td>-->
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">Occupation:</td>
          <td><input type="text" value="<?= $row_compare['memb_occupation'] ?>" size="20" /></td>
          <td align="right" nowrap="nowrap">Comms. Pref:</td>
          <td><input type="text" value="<?= $row_compare['promo_pref'] ?>" size="20" /></td>
        </tr>
        <?php
        if($row_compare['ReceiveEmail'] == 1)
        {
          $email_checked = 'checked';
        }
        else
        {
          $email_checked = '';
        }
        if($row_compare['ReceiveSMS'] == 1)
        {
          $sms_checked = 'checked';
        }
        else
        {
          $sms_checked = '';
        }
        ?>
        <tr>
          <td align="right" nowrap="nowrap">Receive Email:</td>
          <td><input type="checkbox" <?= $email_checked ?>></td>
          <td align="right">Receive SMS:</td>
          <td><input type="checkbox" <?= $sms_checked ?>></td>
        </tr>
        <tr>
          <td align="right">DOB:</td>
          <td><input type="text" value="<?= $row_compare['dob_day'] ?>" size="2" />
            <input type="text" value="<?= $row_compare['dob_mnth'] ?>" size="2" /><input type="text" value="<?= $row_compare['dob_year'] ?>" size="4">
          </td>
          <td align="right" nowrap="nowrap">Gender:</td>
          <td><input type="text" value="<?= $row_compare['Gender'] ?>" size="20" /></td>
        </tr>
        <?php
        if($row_compare['Referee'] == 1)
        {
          $ref_checked = 'checked';
        }
        else
        {
          $ref_checked = '';
        }
        if($row_compare['promo_optin_out'] == 1)
        {
          $promo_checked = 'checked';
        }
        else
        {
          $promo_checked = '';
        }
        ?>
        <input type="hidden" value="<?= $row_compare['Ref_Class'] ?>" />
        <tr>
          <td align="right" nowrap="nowrap">Receive Promo.</td>
          <td align="left"><input type="checkbox"  <?= $promo_checked ?> /></td>
          <td colspan='2'>&nbsp;</td>
         </td>
        </tr>
      </table>
    </td>
    <td>&nbsp;</td> <!-- second player table -->
    <td>
      <table style='width:250px' align="left" class="tableborder" >
        <tr>
          <td colspan=2 class='text-center ui-widget'>
            <label for='tags'>Enter members name to compare:</label></td>
          <td colspan=2><input id='tags'>&nbsp;<input type="button" value="Add" id='newplayer'/>
            <div id='autocompleteAppendToMe'></div>
          </td>
        </tr>
        <tr>
          <td colspan=4>&nbsp;</td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">First Name:</td>
          <td><input type="text" name="FirstName" id="FirstName" value="" size="20" /></td>
          <td align="right" nowrap="nowrap">Last Name:</td>
          <td><input type="text" name="LastName" id="LastName" value="" size="20" /></td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">Home Phone:</td>
          <td><input type="text" name="HomePhone" id="HomePhone" value="" size="20" /></td>
          <td align="right" nowrap="nowrap">Mobile Phone:</td>
          <td><input type="text" name="MobilePhone" id="MobilePhone" value="" size="20" /></td>
        </tr>
        <tr>
          <td align="right">State:</td>
          <td><select name="HomeState" id="HomeState">
            <option value="">No Entry</option>
            <option value="Vic" selected="selected" >Vic</option>
            <option value="NT">NT</option>
            <option value="NSW">NSW</option>
            <option value="Qld">Qld</option>
            <option value="SA">SA</option>
            <option value="Tas">Tas</option>
            <option value="WA">WA</option>
          </select></td>
          <td align="right">Postcode:</td>
          <td><input type="text" name="HomePostcode" id="HomePostcode" value="" size="20" /></td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">Email:</td>
          <td><input type="text" name="Email" id="Email" value="" size="20" /></td>
          <td align="right">Country:</td>
          <td><input type="text" name="Overseas" id="Overseas" value="" size="20" /></td>
          <!--<td><input type="checkbox" name="Overseas" id="Overseas"></td>-->
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">Occupation:</td>
          <td><input type="text" name="occupation" id="occupation" value="" size="20" /></td>
          <td align="right" nowrap="nowrap">Comms. Pref:</td>
          <td><input type="text" name="promo_pref" id="promo_pref" value="" size="20" /></td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">Receive Email:</td>
          <td><input type="checkbox" name="rec_email" id="rec_email"></td></td>
          <td align="right">Receive SMS:</td>
          <td><input type="checkbox" name="rec_sms" id="rec_sms"></td>
        </tr>
        <tr>
          <td align="right">DOB:</td>
          <td><input type="text" name="dob_day" id="dob_day" value="" size="2" />
            <input type="text" name="dob_month" id="dob_month" value="" size="2" />
            <input type="text" name="dob_year" id="dob_year" value="" size="4" />
          </td>
          <td align="right">Gender:</td>
          <td><input type="text" name="gender" id="gender" value="" size="20" /></td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">MemberID:</td>
          <td><input type="text" name="memberID" id="memberID" /></td>
          <td align="right" nowrap="nowrap">Receive Promo.</td>
          <td align="left"><input type="checkbox" name='promo_optin_out' id='promo_optin_out' /></td>
          <td colspan='2'>&nbsp;</td>
        </tr>
        <!--<input type="hidden" name="memberID" id="memberID" value=""/>-->
      </table>
    </td>
  </tr>
</table>
<table width="1000" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><input type="submit" value="Save Changes Made To Existing Member Data" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>