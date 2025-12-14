<?php require_once('../Connections/connvbsa.php'); 
error_reporting(0);

include('../security_header.php');

$page = "../Admin_DB_VBSA/vbsa_online_member_list.php";
$_SESSION['page'] = $page;

if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
	  //echo "X" . PHP_VERSION . "X";
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

if (isset($_GET['pagename'])) {
  $pagename = $_GET['pagename'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($_POST['ButtonName'] == "CopySelected")
{
  //echo("Here<br>");
  $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
  $keys = array_keys($packeddata);
 
  for ($i = 0; $i < count($keys); $i++) 
  {
    $applications = explode(", ", $packeddata[$keys[$i]]);
    $insertSQL = sprintf("Insert INTO members 
    (LastName, 
    FirstName,  
    HomePhone, 
    MobilePhone, 
    HomeAddress,
    HomeSuburb,
    HomePostcode, 
    HomeState, 
    Email, 
    ReceiveEmail, 
    ReceiveSMS, 
    dob_year, 
    dob_mnth, 
    dob_day,
    memb_occupation,
    prospective_ref, 
    promo_optin_out,
    entered_on, 
    promo_pref,
    paid_memb,
    paid_how,
    paid_date,
    memb_by,
    memb_submit_date,
    Gender) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        GetSQLValueString($applications[0], "text"),
        GetSQLValueString($applications[1], "text"),
        GetSQLValueString($applications[2], "text"),
        GetSQLValueString($applications[3], "text"),
        GetSQLValueString($applications[4], "text"),
        GetSQLValueString($applications[5], "text"),
        GetSQLValueString($applications[6], "text"),
        GetSQLValueString($applications[7], "text"),
        GetSQLValueString($applications[8], "text"),
        GetSQLValueString($applications[9], "text"),
        GetSQLValueString($applications[10], "text"),
        GetSQLValueString($applications[11], "text"),
        GetSQLValueString($applications[12], "text"),
        GetSQLValueString($applications[13], "text"),
        GetSQLValueString($applications[14], "text"),
        GetSQLValueString($applications[15], "text"),
        GetSQLValueString($applications[16], "text"),
        GetSQLValueString($applications[17], "text"),
        GetSQLValueString($applications[18], "date"),
        '0',
        '"Online"',
        GetSQLValueString($applications[17], "date"),
        '"Online"',
        GetSQLValueString($applications[17], "date"),
        GetSQLValueString($applications[19], "text"));
    mysql_select_db($database_connvbsa, $connvbsa);
    //echo($insertSQL . "<br>");
    $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

    // move original entry to archive
    $updateArchive = "Update tbl_membership_online set archive = 1 where id  = " . $applications[20];  
    mysql_select_db($database_connvbsa, $connvbsa);
    //echo($updateArchive); 
    $result = mysql_query($updateArchive, $connvbsa) or die(mysql_error());
  }
  //header("Location: A_memb_index.php"); 
  header("Location: vbsa_online_member_list.php"); 
}

if ($_POST['ButtonName'] == "Archive")
{
  //echo('Here'); 
  $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
  foreach($packeddata as $item => $id) {
    $updateSQL = "Update tbl_membership_online set archive = 1 where id  = " . $id;  
    mysql_select_db($database_connvbsa, $connvbsa);
    //echo($updateSQL); 
    $result = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
  }
  header("Location: vbsa_online_member_list.php"); 
}

// on load
mysql_select_db($database_connvbsa, $connvbsa);
$query_memb = "Select * From tbl_membership_online where archive = 0 Order By entered_on desc";
$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);

// on load
mysql_select_db($database_connvbsa, $connvbsa);
$query_archive = "Select * From tbl_membership_online where archive = 1 Order By entered_on desc";
$archive = mysql_query($query_archive, $connvbsa) or die(mysql_error());
//echo($query_archive . "<br>");; 
$row_archive = mysql_fetch_assoc($archive);
$totalRows_archive = mysql_num_rows($archive);

?>
<script>

function ArchiveSelectedButton(no_of_applications)  
{
  //alert("Here");
  var transferdata = {};
  for(var i = 0; i < no_of_applications; i++) // get number of applications
  {
    if(document.getElementById("copy_to_members_" + i).checked === true)
    {
      transferdata[i] = document.getElementById("id_" + i).value;
    }
  }
  var data = JSON.stringify(transferdata);
  //alert(data);
  document.save_edits.Applications.value = no_of_applications;
  document.save_edits.PackedData.value = data;  
  document.save_edits.ButtonName.value = "Archive"; 
  document.save_edits.submit();
}

function CompareSelectedButton(no_of_applications)  
{
  for(var i = 0; i < no_of_applications; i++) // get number of applications
  {
    if(document.getElementById("copy_to_members_" + i).checked === true)
    {
      selected_id = document.getElementById("id_" + i).value;
    }
  }
  document.save_edits.Applications.value = selected_id;
  document.save_edits.action = 'compare_members.php';
  document.save_edits.submit();
}

function CopySelectedButton(no_of_applications)  
{
  //alert("Here");
  var transferdata = {};
  for(var i = 0; i < no_of_applications; i++) // get number of applications received
  {
    if(document.getElementById("receive_email_" + i).checked === true)
    {
      rec_email = 1;
    }
    else
    {
      rec_email = 0;
    }
    if(document.getElementById("receive_sms_" + i).checked === true)
    {
      rec_sms = 1;
    }
    else
    {
      rec_sms = 0;
    }
    if(document.getElementById("consent_" + i).checked === true)
    {
      consent = 1;
    }
    else
    {
      consent = 0;
    }
    if(document.getElementById("ref_" + i).checked === true)
    {
      referee = 1;
    }
    else
    {
      referee = 0;
    }
    if(document.getElementById("copy_to_members_" + i).checked === true)
    {    
      transferdata[i] = document.getElementById("lastname_" + i).innerHTML + ", " +
                        document.getElementById("firstname_" + i).innerHTML + ", " +
                        document.getElementById("home_phone_" + i).innerHTML + ", " +
                        document.getElementById("mobile_phone_" + i).innerHTML + ", " +
                        document.getElementById("address_" + i).innerHTML + ", " +
                        document.getElementById("suburb_" + i).innerHTML + ", " +
                        document.getElementById("postcode_" + i).innerHTML + ", " +
                        document.getElementById("state_" + i).innerHTML + ", " +
                        document.getElementById("email_" + i).innerHTML + ", " +
                        rec_email + ", " +
                        rec_sms + ", " +
                        document.getElementById("dob_year_" + i).value + ", " +
                        document.getElementById("dob_month_" + i).value + ", " +
                        document.getElementById("dob_day_" + i).value + ", " +
                        document.getElementById("occupation_" + i).innerHTML + ", " +
                        referee + ", " +
                        consent + ", " +
                        document.getElementById("entered_" + i).innerHTML + ", " +
                        document.getElementById("memb_comms_" + i).innerHTML + ", " +
                        document.getElementById("gender_" + i).innerHTML + ", " +
                        document.getElementById("id_" + i).value;
    }
  }
  var data = JSON.stringify(transferdata);
  //alert(data);
  document.save_edits.Applications.value = no_of_applications;
  document.save_edits.PackedData.value = data;  
  document.save_edits.ButtonName.value = "CopySelected"; 
  document.save_edits.submit();
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<form action="<?php echo $editFormAction; ?>" method="post" name="save_edits" id="save_edits">
<input type='hidden' id='Applications' name="Applications">
<input type='hidden' id='PackedData' name="PackedData">
<input type='hidden' id='ButtonName' name="ButtonName">
<table width="1000" align="center">
    <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><b>Copy Selected to members</b> - If  the New/Renewal field is 'New Application' click on the 'Select" box to copy the online data to the members Table. Multiple selections can be made.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><b>Compare Selected to Existing Members</b> - If  the New/Renewal field is 'Renewal' click on the 'Select" box to open the 'Compare Members' page. Select only one record.</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center" nowrap="nowrap"><span class="red_bold">Online Player Applications.</span></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="center"><button style='width:300px' onclick="CopySelectedButton(<?= $totalRows_memb ?>)">Copy Selected to Members.</button></td>
  
    <td align="center"><button style='width:300px' onclick="CompareSelectedButton(<?= $totalRows_memb ?>)">Compare Selected to Existing Members.</button></td>
  
    <td align="center"><button style='width:300px' onclick="ArchiveSelectedButton(<?= $totalRows_memb ?>)">Move Selected to Archive/Completed.</button></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
<table align="center" cellpadding="3" cellspacing="3" border = 1>
<?php
if($totalRows_memb > 0)
{
?>
   <tr>
    <td colspan="22" align="center" nowrap="nowrap"><b>Pending Membership Applications.</b></td>
  </tr>
  <tr>
    <td align="left" nowrap="nowrap">Last Name</td>
    <td align="left" nowrap="nowrap">First Name</td>
    <td align="left" nowrap="nowrap">Home Phone</td>
    <td align="left" nowrap="nowrap">Mobile Phone</td>
    <!--<td align="left" nowrap="nowrap">Address</td>
    <td align="left" nowrap="nowrap">Suburb</td>-->
    <td align="left" nowrap="nowrap">Post Code</td>
    <!--<td align="left" nowrap="nowrap">State</td>-->
    <td align="left" nowrap="nowrap">Email</td>
    <td align="left" nowrap="nowrap">Rec. Email</td>
    <td align="left" nowrap="nowrap">Rec. SMS</td>
    <td align="center" nowrap="nowrap">Comms Pref.</td>
    <td align="left" nowrap="nowrap">Date of Birth</td>
    <td align="left" nowrap="nowrap">Occupation</td>
    <td align="center" nowrap="nowrap">Referee?</td>
    <td align="center" nowrap="nowrap">Cue</td>
    <td align="center" nowrap="nowrap">Gender</td>
    <td align="center" nowrap="nowrap">New/Renewal</td>
    <td align="center" nowrap="nowrap">Pennant/Tourn.</td>
    <td align="center" nowrap="nowrap">Promo</td>
    <td align="center" nowrap="nowrap">Other Person</td>
    <td align="center" nowrap="nowrap">Application Date</td>
    <td align="center" nowrap="nowrap">Select</td>
    <td align="center" nowrap="nowrap">Clear</td>
  </tr>
  <?php 
  $i = 0;
  do {
	?>
    <tr>
      <input type='hidden' id='id_<?= $i ?>' value='<?php echo $row_memb['id']; ?>'>
      <input type='hidden' id='dob_year_<?= $i ?>' value='<?php echo $row_memb['dob_year']; ?>'>
      <input type='hidden' id='dob_month_<?= $i ?>' value='<?php echo $row_memb['dob_mnth']; ?>'>
      <input type='hidden' id='dob_day_<?= $i ?>' value='<?php echo $row_memb['dob_day']; ?>'>

      <input type='hidden' id='address_<?= $i ?>' value='<?php echo $row_memb['HomeAddress']; ?>'>
      <input type='hidden' id='suburb_<?= $i ?>' value='<?php echo $row_memb['HomeSuburb']; ?>'>
      <input type='hidden' id='state_<?= $i ?>' value='<?php echo $row_memb['HomeState']; ?>'>
      
      <td align="left" id='lastname_<?= $i ?>'><?php echo $row_memb['LastName']; ?></td>
      <td align="left" id='firstname_<?= $i ?>'><?php echo $row_memb['FirstName']; ?></td>
      <td class="page" id='home_phone_<?= $i ?>'><?php echo $row_memb['HomePhone']; ?></td>
      <td class="page" id='mobile_phone_<?= $i ?>'><?php echo $row_memb['MobilePhone']; ?></td>
      <!--<td nowrap="nowrap" id='address_<?= $i ?>'><?php echo $row_memb['HomeAddress']; ?></td>
      <td nowrap="nowrap" id='suburb_<?= $i ?>'><?php echo $row_memb['HomeSuburb']; ?></td>-->
      <td nowrap="nowrap" id='postcode_<?= $i ?>'><?php echo $row_memb['HomePostcode']; ?></td>
      <!--<td nowrap="nowrap" id='state_<?= $i ?>'><?php echo $row_memb['HomeState']; ?></td>-->
      <td nowrap="nowrap" id='email_<?= $i ?>'><?php echo $row_memb['Email']; ?></td>
      <?php 
      if($row_memb['ReceiveEmail'] == 1)
      {
      	echo("<td align='center'><input type='checkbox' id='receive_email_" . $i . "' checked='checked' disabled ></td>");
      }
      else
      {
      	echo("<td align='center'><input type='checkbox' id='receive_email_" . $i . "' disabled></td>");
      }
      if($row_memb['ReceiveSMS'] == 1)
      {
      	echo("<td align='center'><input type='checkbox' id='receive_sms_" . $i . "' checked='checked' disabled ></td>");
      }
      else
      {
      	echo("<td align='center'><input type='checkbox' id='receive_sms_" . $i . "' disabled></td>");
      }
      echo("<td align='center' id='memb_comms_" . $i . "'>" . $row_memb['promo_pref'] . "</td>");
      if($row_memb['dob_day'] < 10)
      {
        $dob_day = ('0' . $row_memb['dob_day']);
      }
      else
      {
        $dob_day = $row_memb['dob_day'];
      }
      if($row_memb['dob_mnth'] < 10)
      {
        $dob_month = ('0' . $row_memb['dob_mnth']);
      }
      else
      {
        $dob_month = $row_memb['dob_mnth'];
      }
      echo("<td align='left' id='dob_" . $i . "'>" . $row_memb['dob_year'] . "-" . $dob_month . "-" . $dob_day . "</td>");
      echo("<td nowrap='nowrap' align='left' id='occupation_" . $i . "'>" . $row_memb['memb_occupation'] . "</td>");
      if($row_memb['prospective_ref'] == 1)
      {
      	echo("<td align='center'><input type='checkbox' id='ref_" . $i . "' checked disabled></td>");
      }
      else
      {
      	echo("<td align='center'><input type='checkbox' id='ref_" . $i . "' disabled></td>");
      }
      echo("<td align='center' id='cue_" . $i . "'>" . $row_memb['cue'] . "</td>");
      echo("<td align='center' id='gender_" . $i . "'>" . $row_memb['Gender'] . "</td>");
      echo("<td align='center' id='renewal_" . $i . "'>" . $row_memb['new_renewal'] . "</td>");
      echo("<td align='center' id='reason_" . $i . "'>" . $row_memb['reason'] . "</td>");
      if($row_memb['promo_optin_out'] == 1)
      {
      	echo("<td align='center'><input type='checkbox' id='consent_" . $i . "' checked disabled></td>");
      }
      else
      {
      	echo("<td align='center'><input type='checkbox' id='consent_" . $i . "' disabled></td>");
      }
      echo("<td align='center' id='other_person_" . $i . "'>" . $row_memb['other_person'] . "</td>");
      echo("<td nowrap='nowrap' align='left' id='entered_" . $i . "'>" . $row_memb['entered_on'] . "</td>");
      ?>
      <td align="center"><input type="radio" name="action_<?= $i ?>"  id="copy_to_members_<?= $i ?>" /></td>
      <td align="center"><input type="radio" name="action_<?= $i ?>"  id="clear_<?= $i ?>" checked/></td>
    </tr>
    <?php 
    $i++;
	  } while ($row_memb = mysql_fetch_assoc($memb)); 
     $no_of_applications = $i;
}
?>
   <tr>
    <td colspan="22" align="center" nowrap="nowrap"><b>Archived Membership Applications.</b></td>
  </tr>
  <tr>
    <td align="left" nowrap="nowrap">Last Name</td>
    <td align="left" nowrap="nowrap">First Name</td>
    <td align="left" nowrap="nowrap">Home Phone</td>
    <td align="left" nowrap="nowrap">Mobile Phone</td>
    <!--<td align="left" nowrap="nowrap">Address</td>
    <td align="left" nowrap="nowrap">Suburb</td>-->
    <td align="left" nowrap="nowrap">Post Code</td>
    <!--<td align="left" nowrap="nowrap">State</td>-->
    <td align="left" nowrap="nowrap">Email</td>
    <td align="left" nowrap="nowrap">Rec. Email</td>
    <td align="left" nowrap="nowrap">Rec. SMS</td>
    <td align="center" nowrap="nowrap">Comms Pref.</td>
    <td align="left" nowrap="nowrap">Date of Birth</td>
    <td align="left" nowrap="nowrap">Occupation</td>
    <td align="center" nowrap="nowrap">Referee?</td>
    <td align="center" nowrap="nowrap">Cue</td>
    <td align="center" nowrap="nowrap">Gender</td>
    <td align="center" nowrap="nowrap">New/Renewal</td>
    <td align="center" nowrap="nowrap">Pennant/Tourn.</td>
    <td align="center" nowrap="nowrap">Promo</td>
    <td align="center" nowrap="nowrap">Other Person</td>
    <td align="center" nowrap="nowrap">Application Date</td>
    <td align="center" nowrap="nowrap">Select</td>
    <td align="center" nowrap="nowrap">Clear</td>
  </tr>
  <?php 
  $y = 0;
  do {
  ?>
    <tr>
      <input type='hidden' id='id_<?= $y ?>' value='<?php echo $row_archive['id']; ?>'>
      <input type='hidden' id='dob_year_<?= $y ?>' value='<?php echo $row_memb['dob_year']; ?>'></td>
      <input type='hidden' id='dob_month_<?= $y ?>' value='<?php echo $row_archive['dob_mnth']; ?>'></td>
      <input type='hidden' id='dob_day_<?= $y ?>' value='<?php echo $row_archive['dob_day']; ?>'></td>
      <td align="left" id='lastname_<?= $y ?>'><?php echo $row_archive['LastName']; ?></td>
      <td align="left" id='firstname_<?= $y ?>'><?php echo $row_archive['FirstName']; ?></td>
      <td class="page" id='home_phone_<?= $y ?>'><?php echo $row_archive['HomePhone']; ?></td>
      <td class="page" id='mobile_phone_<?= $y ?>'><?php echo $row_archive['MobilePhone']; ?></td>
      <!--<td nowrap="nowrap" id='address_<?= $y ?>'><?php echo $row_archive['HomeAddress']; ?></td>
      <td nowrap="nowrap" id='suburb_<?= $y ?>'><?php echo $row_archive['HomeSuburb']; ?></td>-->
      <td nowrap="nowrap" id='postcode_<?= $y ?>'><?php echo $row_archive['HomePostcode']; ?></td>
      <!--<td nowrap="nowrap" id='state_<?= $y ?>'><?php echo $row_archive['HomeState']; ?></td>-->
      <td nowrap="nowrap" id='email_<?= $y ?>'><?php echo $row_archive['Email']; ?></td>
      <?php 
      if($row_archive['ReceiveEmail'] == 1)
      {
        echo("<td align='center'><input type='checkbox' id='receive_email_" . $y . "' checked='checked' disabled ></td>");
      }
      else
      {
        echo("<td align='center'><input type='checkbox' id='receive_email_" . $y . "' disabled></td>");
      }
      if($row_archive['ReceiveSMS'] == 1)
      {
        echo("<td align='center'><input type='checkbox' id='receive_sms_" . $y . "' checked='checked' disabled ></td>");
      }
      else
      {
        echo("<td align='center'><input type='checkbox' id='receive_sms_" . $y . "' disabled></td>");
      }
      echo("<td align='center' id='memb_comms_" . $y . "'>" . $row_archive['promo_pref'] . "</td>");
      if($row_archive['dob_day'] < 10)
      {
        $dob_day = ('0' . $row_archive['dob_day']);
      }
      else
      {
        $dob_month = $row_archive['dob_mnth'];
      }
      if($row_archive['dob_mnth'] < 10)
      {
        $dob_month = ('0' . $row_archive['dob_mnth']);
      }
      else
      {
        $dob_month = $row_archive['dob_mnth'];
      }
      echo("<td align='left' id='dob_" . $y . "'>" . $row_archive['dob_year'] . "-" . $dob_month . "-" .$dob_day . "</td>");
      echo("<td nowrap='nowrap' align='left' id='occupation_" . $y . "'>" . $row_archive['memb_occupation'] . "</td>");
      if($row_archive['prospective_ref'] == 1)
      {
        echo("<td align='center'><input type='checkbox' id='ref_" . $y . "' checked disabled></td>");
      }
      else
      {
        echo("<td align='center'><input type='checkbox' id='ref_" . $y . "' disabled></td>");
      }
      echo("<td align='center' id='cue_" . $y . "'>" . $row_archive['cue'] . "</td>");
      echo("<td align='center' id='gender_" . $y . "'>" . $row_archive['Gender'] . "</td>");
      echo("<td align='center' id='renewal_" . $y . "'>" . $row_archive['new_renewal'] . "</td>");
      echo("<td align='center' id='reason_" . $y . "'>" . $row_archive['reason'] . "</td>");
      if($row_archive['consent'] == 1)
      {
        echo("<td align='center'><input type='checkbox' id='consent_" . $y . "' checked disabled></td>");
      }
      else
      {
        echo("<td align='center'><input type='checkbox' id='consent_" . $y . "' disabled></td>");
      }
      echo("<td align='center' id='other_person_" . $y . "'>" . $row_archive['other_person'] . "</td>");
      echo("<td nowrap='nowrap' align='left' id='entered_" . $y . "'>" . $row_archive['entered_on'] . "</td>");
      ?>
      <td align="center"><input type="radio" name="action_<?= $y ?>"  id="copy_to_members_<?= $y ?>" disbaled /></td>
      <td align="center"><input type="radio" name="action_<?= $y ?>"  id="clear_<?= $y ?>" disbaled /></td>
    </tr>
    <?php 
    $y++;
    } while ($row_archive = mysql_fetch_assoc($archive)); 
    ?>
</table>
</form>
</body>
</html>

