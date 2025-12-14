<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
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

$editFormAction = 'ajax/Treas_member_insert.php';
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO members (MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone, MobilePhone, Email, Club, BoardMemb, contact_only, entered_on, Female, Overseas, Referee, Ref_Class, AffiliateMemb, board_position, Club2, dob_day, dob_mnth, dob_year, prospective_ref) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['MemberID'], "int"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['HomeAddress'], "text"),
                       GetSQLValueString($_POST['HomeSuburb'], "text"),
                       GetSQLValueString($_POST['HomeState'], "text"),
                       GetSQLValueString($_POST['HomePostcode'], "text"),
                       GetSQLValueString($_POST['HomePhone'], "text"),
                       GetSQLValueString($_POST['WorkPhone'], "text"),
                       GetSQLValueString($_POST['MobilePhone'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Club'], "text"),
					   GetSQLValueString(isset($_POST['BoardMemb']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['contact_only']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['entered_on'], "date"),
                       GetSQLValueString(isset($_POST['Female']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['Overseas'], "text"),
                       GetSQLValueString(isset($_POST['Referee']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['Ref_Class'], "text"),
                       GetSQLValueString($_POST['AffiliateMemb'], "text"),
                       GetSQLValueString($_POST['board_position'], "text"),
                       GetSQLValueString($_POST['Club2'], "text"),
                       GetSQLValueString($_POST['dob_day'], "int"),
                       GetSQLValueString($_POST['dob_mnth'], "int"),
                       GetSQLValueString($_POST['dob_year'], "date"),
                       GetSQLValueString(isset($_POST['prospective_ref']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
  echo "Error!";
  exit;
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_nextmember = "SELECT Max(MemberID+1) FROM members";
$nextmember = mysql_query($query_nextmember, $connvbsa) or die(mysql_error());
$row_nextmember = mysql_fetch_assoc($nextmember);
$totalRows_nextmember = mysql_num_rows($nextmember);

mysql_select_db($database_connvbsa, $connvbsa);
$query_vbsa_clubs = "SELECT ClubNumber, ClubTitle, ClubNameVBSA FROM clubs WHERE VBSAteam=1 ORDER BY ClubNameVBSA";
$vbsa_clubs = mysql_query($query_vbsa_clubs, $connvbsa) or die(mysql_error());
$row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs);
$totalRows_vbsa_clubs = mysql_num_rows($vbsa_clubs);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA New Member Insert</title>
<script src="../../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
</head>

<body>
<table width="1000" align="center" cellpadding="2">
  <tr>
    <td width="742" class="red_bold">INSERT A NEW  PERSON, PLAYER OR MEMBER</td>
    <td width="262">&nbsp;</td>
  </tr>
  </table>
<div id="DBcontent">  
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return doit()">
   <table align="center" width="1000">
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">New Member ID:</td>
        <td><?php echo $row_nextmember['Max(MemberID+1)']; ?></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">Inserted On:</td>
        <td colspan="2"><?php echo date('l jS F Y'); ?> (Auto inserted) </td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Last Name:</td>
        <td><input type="text" name="LastName" value="" size="32" /></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle" nowrap="nowrap">Home Phone:</td>
        <td colspan="2"><input type="text" name="HomePhone" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">First Name:</td>
        <td><input type="text" name="FirstName" value="" size="32" /></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">Work Phone:</td>
        <td colspan="2"><input type="text" name="WorkPhone" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Mobile Phone:</td>
        <td><input type="text" name="MobilePhone" value="" size="32" /></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">Club:</td>
        <td colspan="2"><select name="Club">
          <option value="" selected="selected" >No Entry</option>
          <?php do {  ?>
        <option value="<?php echo $row_vbsa_clubs['ClubNameVBSA']?>"<?php if (!(strcmp($row_vbsa_clubs['ClubNameVBSA'], $row_nextmember['']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vbsa_clubs['ClubNameVBSA']?></option>
          <?php
} while ($row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs));
  $rows = mysql_num_rows($vbsa_clubs);
  if($rows > 0) {
      mysql_data_seek($vbsa_clubs, 0);
	  $row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs);
  }
?>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Email:</td>
        <td><input type="text" name="Email" value="" size="32" /></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">Club 2:</td>
        <td valign="middle"><select name="Club2">
          <option value="" selected="selected" >No Entry</option>
          <?php do {  ?>
        <option value="<?php echo $row_vbsa_clubs['ClubNameVBSA']?>"<?php if (!(strcmp($row_vbsa_clubs['ClubNameVBSA'], $row_nextmember['']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vbsa_clubs['ClubNameVBSA']?></option>
          <?php
} while ($row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs));
  $rows = mysql_num_rows($vbsa_clubs);
  if($rows > 0) {
      mysql_data_seek($vbsa_clubs, 0);
	  $row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs);
  }
?>
        </select></td>
        <td valign="middle" nowrap="nowrap"> (if playing with a 2nd club)</td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Female:</td>
        <td><input type="checkbox" name="Female" id="Female" /></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">Overseas?:</td>
        <td><input type="text" name="Overseas" value="" size="32" /></td>
        <td valign="middle">(What Country?)</td>
      </tr>
      <tr valign="baseline">
        <td align="right">DOB</td>
        <td colspan="5"><input type="text" name="dob_day" value="" size="2" /> <input type="text" name="dob_mnth" value="" size="2" /> <input type="text" name="dob_year" value="" size="4" />
          Please insert as dd - mm - yyyy eg 01-01-2001.</td>

      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td colspan="5" class="red_text">NOTE: For a player to appear as a junior they MUST have a date of birth listed. Insert Year of Birth only for statistical purposes</td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Affiliate Memb:</td>
        <td><select name="AffiliateMemb">
          <option value="" selected="selected" >No Entry</option>
          <option value="ChurchBill">ChurchBill</option>
          <option value="DVSA">DVSA</option>
          <option value="MSBA">MSBA</option>
          <option value="O55">O55</option>
          <option value="RSL">RSL</option>
          <option value="Southern">Southern</option>
          <option value="SSA">SSA</option>
        </select></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Receive SMS:</td>
        <td>Auto inserted as: Yes , edit to alter</td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">Receive Email:</td>
        <td colspan="2">Auto inserted as: Yes , edit to alter</td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Referee:</td>
        <td><input type="checkbox" name="Referee" id="Referee" /></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">Ref Class:</td>
        <td colspan="2"><input type="text" name="Ref_Class" value="" size="60" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Prospective Referee?</td>
        <td><input type="checkbox" name="prospective_ref" id="prospective_ref" /></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Board Memb:</td>
        <td><input type="checkbox" name="BoardMemb" id="BoardMemb" /></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle" nowrap="nowrap">Board position:</td>
        <td colspan="2"><input type="text" name="board_position" value="" size="60" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#CDCDCD">Contact only:</td>
        <td bgcolor="#CDCDCD"><input type="checkbox" name="contact_only" id="contact_only" /></td>
        <td bgcolor="#CDCDCD">&nbsp;</td>
        <td align="right" valign="middle" bgcolor="#CDCDCD">Address:</td>
        <td colspan="2" bgcolor="#CDCDCD"><input type="text" name="HomeAddress" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#CDCDCD">&nbsp;</td>
        <td rowspan="3" bgcolor="#CDCDCD" class="red_text">* Use this area if this person is a contact for a club. The VBSA do not record address unless it is required to send information. State &amp; Postcode may be entered for any member</td>
        <td bgcolor="#CDCDCD">&nbsp;</td>
        <td align="right" valign="middle" bgcolor="#CDCDCD">Suburb:</td>
        <td colspan="2" bgcolor="#CDCDCD"><input type="text" name="HomeSuburb" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#CDCDCD">&nbsp;</td>
        <td bgcolor="#CDCDCD">&nbsp;</td>
        <td align="right" valign="middle">State:</td>
        <td colspan="2">
        <select name="HomeState">
                       	  <option value="" >No Entry</option>
							<option value="Vic" selected="selected" >Vic</option>
							<option value="NT">NT</option>
							<option value="NSW">NSW</option>
							<option value="Qld">Qld</option>
							<option value="SA">SA</option>
							<option value="Tas">Tas</option>
							<option value="WA">WA</option>
                        </select>
        </td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#CDCDCD">&nbsp;</td>
        <td bgcolor="#CDCDCD">&nbsp;</td>
        <td align="right" valign="middle">Postcode:</td>
        <td colspan="2"><input type="text" name="HomePostcode" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td><input type="submit" value="Insert person" /></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1" />
    <input type="hidden" name="MemberID" value="<?php echo $row_nextmember['Max(MemberID+1)']; ?>" />
    <input type="hidden" name="entered_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d"); ?> " />
  </form> 

</div>
</body>
</html>
<?php
mysql_free_result($nextmember);

mysql_free_result($vbsa_clubs);
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>

<script type="text/javascript">

function doit(){     
	
	var tx = jQuery.noConflict();
        tx.ajax({
            url     : '<?PHP echo $editFormAction ?>',
            type    : tx('#form1').attr('method'),
            data    : tx('#form1').serialize(),
            success : function( data ) {
                        alert('Inserted Succesfully!');
						location.reload(); 
                      },
            error   : function( xhr, err ) {
                        alert('Error');     
                      }
        }); 
        return false;
}

</script>