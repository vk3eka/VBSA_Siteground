<?php require_once('../Connections/connvbsa.php'); ?>
<?php include('../security_header.php'); ?>
<?php

error_reporting(0);

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

if (isset($_GET['memb_id'])) {
  $memb_id = $_GET['memb_id'];
}

// check if member is a coach.
mysql_select_db($database_connvbsa, $connvbsa);
$query_coach = "Select coach_id FROM coaches_vbsa WHERE memb_id='$memb_id'";
$coach = mysql_query($query_coach, $connvbsa) or die(mysql_error());
$row_coach = mysql_fetch_assoc($coach);
$totalRows_coach = mysql_num_rows($coach);
//echo("<br>Coach Rows "  . $totalRows_coach . "<br>");

mysql_select_db($database_connvbsa, $connvbsa);
if($totalRows_coach > 0)
{
  $query_Details = "Select members.MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone,  MobilePhone, memb_occupation, ReceiveSMS, Email, ReceiveEmail, LifeMember, Deceased, Gender, Overseas, Referee, Ref_Class, members.LastUpdated, members.UpdateBy, AffiliateMemb, entered_on,  dob_day, dob_mnth, dob_year, prospective_ref, ccc_player, affiliate_player, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4, memb_by, contact_only, coach_id, class, comment, active_coach, members.curr_memb, members.hon_memb, paid_memb, paid_how, paid_date, community FROM members JOIN coaches_vbsa on memb_id = members.memberid WHERE members.MemberID = '$memb_id'";
}
else
{
  $query_Details = "Select members.MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone, MobilePhone, memb_occupation, ReceiveSMS, Email, ReceiveEmail, Club, LifeMember, ccc_player, affiliate_player, Deceased, Junior, Gender, Referee, Ref_Class, prospective_ref, members.LastUpdated, members.UpdateBy, AffiliateMemb, entered_on, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4, memb_by, dob_day, dob_mnth, dob_year, Overseas, members.paid_memb, members.paid_how, members.paid_date, members.curr_memb, members.hon_memb, paid_memb, paid_how, paid_date, community FROM members WHERE members.MemberID = '$memb_id'";
}

//mysql_select_db($database_connvbsa, $connvbsa);
//$query_Details = "SELECT members.MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone,  MobilePhone, ReceiveSMS, Email, ReceiveEmail, Club, LifeMember, ccc_player, Deceased, Junior, Female, Referee,  Ref_Class, prospective_ref, members.LastUpdated, members.UpdateBy, AffiliateMemb, entered_on, memb_by, dob_day, dob_mnth, dob_year, Overseas, members.paid_memb, members.paid_how, members.paid_date FROM members WHERE members.MemberID = '$memb_id'";

//$query_Details = "Select members.MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone, MobilePhone, ReceiveSMS, Email, ReceiveEmail, Club, LifeMember, ccc_player, Deceased, Junior, Female, Referee, Ref_Class, prospective_ref, members.LastUpdated, members.UpdateBy, AffiliateMemb, entered_on, memb_by, dob_day, dob_mnth, dob_year, Overseas, members.paid_memb, members.paid_how, members.paid_date, coach_id, class, comment FROM members JOIN coaches_vbsa on memb_id = members.memberid WHERE members.MemberID = '$memb_id'";
//echo("<br>Details "  . $query_Details . "<br>");

$Details = mysql_query($query_Details, $connvbsa) or die(mysql_error());
$row_Details = mysql_fetch_assoc($Details);
$totalRows_Details = mysql_num_rows($Details);

mysql_select_db($database_connvbsa, $connvbsa);
//$query_playedcalyear = "SELECT scrsID, MemberID , SUM(count_played) as totplayedcal FROM scrs WHERE MemberID= '$memb_id' AND current_year_scrs = YEAR( CURDATE( ) )";
$query_playedcalyear = "SELECT MemberID, (totplayed_curr+totplaybill_curr) as totplayedcal FROM members WHERE MemberID='$memb_id'";

//echo("<br>Cal Year "  . $query_playedcalyear . "<br>");
$playedcalyear = mysql_query($query_playedcalyear, $connvbsa) or die(mysql_error());
$row_playedcalyear = mysql_fetch_assoc($playedcalyear);
$totalRows_playedcalyear = mysql_num_rows($playedcalyear);

mysql_select_db($database_connvbsa, $connvbsa);
$query_fin = "SELECT MemberID, paid_memb, paid_how, paid_date FROM members WHERE paid_memb is not null AND YEAR(paid_date)=YEAR( CURDATE( ) ) AND MemberID= '$memb_id'";
//echo("<br>Financial "  . $query_fin . "<br>");
$fin = mysql_query($query_fin, $connvbsa) or die(mysql_error());
$row_fin = mysql_fetch_assoc($fin);
$totalRows_fin = mysql_num_rows($fin);

mysql_select_db($database_connvbsa, $connvbsa);
$query_MonClub = "Select scrs.team_id, scrs.team_grade, team_club, team_name, comptype FROM scrs, Team_entries WHERE Team_entries.team_id = scrs.team_id  AND scrs.MemberID ='$memb_id'  AND Team_entries.day_played='Mon' AND current_year_scrs = YEAR( CURDATE( ) ) ORDER BY scr_season DESC, count_played DESC LIMIT 1";
//echo("<br>Monday "  . $query_MonClub . "<br>");
$MonClub = mysql_query($query_MonClub, $connvbsa) or die(mysql_error());
$row_MonClub = mysql_fetch_assoc($MonClub);
$totalRows_MonClub = mysql_num_rows($MonClub);

mysql_select_db($database_connvbsa, $connvbsa);
$query_WedClub = "Select scrs.team_id, scrs.team_grade, team_club, team_name, comptype FROM scrs, Team_entries WHERE Team_entries.team_id = scrs.team_id  AND scrs.MemberID ='$memb_id' AND Team_entries.day_played='Wed' AND current_year_scrs = YEAR( CURDATE( ) ) ORDER BY scr_season DESC, count_played DESC LIMIT 1";
//echo("<br>Wednesday "  . $query_WedClub . "<br>");
$WedClub = mysql_query($query_WedClub, $connvbsa) or die(mysql_error());
$row_WedClub = mysql_fetch_assoc($WedClub);
$totalRows_WedClub = mysql_num_rows($WedClub);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<table width="1000" align="center" cellpadding="2">
  <tr>
    <td width="742" class="red_bold">PERSONAL DETAIL</td>
    <td width="262" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>

<div id="DBcontent">
<table width="1000" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="right" nowrap="nowrap">Member ID: </td>
    <td align="left"><?php echo $row_Details['MemberID']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Last Name: </td>
    <td><?php echo $row_Details['LastName']; ?></td>
    <td align="right">Mobile</td>
    <td><span class="page"><a href="tel:<?php echo $row_Details['MobilePhone']; ?>"><?php echo $row_Details['MobilePhone']; ?></a></span></td>
  </tr>
  <tr>
    <td align="right">First Name: </td>
    <td><?php echo $row_Details['FirstName']; ?></td>
    <td align="right">State</td>
    <td class="page"><?php echo $row_Details['HomeState']; ?></td>
  </tr>
  <tr>
    <td align="right">Land Line: </td>
    <td><?php echo $row_Details['HomePhone']; ?></td>
    <td align="right">Postcode</td>
    <td><?php echo $row_Details['HomePostcode']; ?></td>
  </tr>
  <tr>
    <td align="right">Email: </td>
    <td class="page"><a href="mailto:<?php echo $row_Details['Email']; ?>"><?php echo $row_Details['Email']; ?></a></td>
    <td align="right">Country:</td>
    <td><?php echo $row_Details['Overseas']; ?></td>
  </tr>
  <tr>
    <td align="right">Occupation: </td>
    <td><?php echo $row_Details['memb_occupation']; ?></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<!--</table>
<table width="1000" align="center" cellpadding="3" cellspacing="3">-->
  <tr>
    <td align="center" colspan='4'><span class="red_text">NOTE: For a player to appear as a junior they MUST have a date of birth listed and the year of birth be &lt;18 years ago</span></td>
  </tr>
  <tr>
    <td align="right">DOB</td>
    <td><?php echo $row_Details['dob_day']; ?> <?php echo $row_Details['dob_mnth']; ?> <?php echo $row_Details['dob_year']; ?></td>
    <td align="right">Junior?</td>
    <td align="left"><?php
  		$age = (date("Y") - $row_Details['dob_year']);
  		if($age <=18)
  		{
  		echo "Yes";
  		}
  		elseif($age >18)
  		{
  		echo "No";
  		}
		?>&nbsp;
    </td>
  </tr>
  <!--<tr>
    <td align="right">Junior Category: </td>
    <td><select name="Junior">
          <option value="<?= $row_Details['Junior'] ?>" selected="selected" ><?= $row_Details['Junior'] ?></option>
          <option value="U12">U12</option>
          <option value="U15">U15</option>
          <option value="U18">U18</option>
        </select></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>-->
  <tr>
    <td colspan='4'>&nbsp;</td>
  </tr>
  </table>
    <table width="1000" align="center" cellpadding="3" cellspacing="3">
      <tr>
        <td align="right">Deactivated Member</td>
        <td><input type="checkbox" name="curr_memb" id="curr_memb"  <?php if (!(strcmp(htmlentities($row_Details['curr_memb'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        <td colspan=5 class='red_text'>A member who has had their membership cancelled at the direction of the VBSA Board.</td>
      </tr>
      <tr>
        <td align="right">Honorary Member</td>
        <td><input type="checkbox" name="hon_memb" id="hon_memb"  <?php if (!(strcmp(htmlentities($row_Details['hon_memb'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        <td align="right">Community Member</td>
        <td><input type="checkbox" name="community" id="community"  <?php if (!(strcmp(htmlentities($row_Details['community'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        <td colspan=3 class='red_text'>&nbsp;</td>
      </tr>
    	<tr>
    	  <td align="right">Receive Email</td>
    	  <td align="left"><input name="ReceiveEmail" type="checkbox" <?php if($row_Details['ReceiveEmail']==1 && !empty($row_Details['Email'])) { ?> checked <?php } ?>/></td>
    	  <td align="right">Gender:</td>
        <td colspan="2"><select name="Gender">
          <option value="<?= $row_Details['Gender'] ?>" selected="selected" ><?= $row_Details['Gender'] ?></option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="NonBinary">Non Binary</option>
          <option value="NoGender">No Gender</option>
        </select></td>
    	  <td align="right">Monday Club<?php if(isset($row_MonClub['team_grade'])) echo "(". $row_MonClub['team_grade'].")"; else echo "" ?></td>
    	  <td align="left"><?php if(isset($row_MonClub['team_id'])) echo $row_MonClub['team_club'].", " .$row_MonClub['team_name']. "(". $row_MonClub['comptype'].")"; else echo "Not Playing" ?></td>
        <!--<td align="right">Grade</td>
        <td align="left"><?php echo($row_MonClub['team_grade']); ?></td>-->
  	  </tr>
    	<tr>
    	  <td align="right">Receive SMS</td>
    	  <td align="left"><input name="ReceiveSMS" type="checkbox" 
                            <?php if($row_Details['ReceiveSMS']==1 && !empty($row_Details['MobilePhone'])) { ?> checked <?php } ?>/></td>
    	  <td align="right">Deceased</td>
    	  <td align="left"><input name="Deceased" type="checkbox" <?php if($row_Details['Deceased']==1) { ?> checked <?php } ?>/></td>
    	  <td align="left">&nbsp;</td>
    	  <td align="right">Wednesday Club <?php if(isset($row_WedClub['team_grade'])) echo "(". $row_WedClub['team_grade'].")"; else echo "" ?></td>
    	  <td align="left"><?php if(isset($row_WedClub['team_id'])) echo $row_WedClub['team_club'].", " .$row_WedClub['team_name']. "(". $row_WedClub['comptype'].")"; else echo "Not Playing" ?></td>
        <!--<td align="right">Grade</td>
        <td align="left"><?php echo($row_WedClub['team_grade']); ?></td>-->
  	  </tr>
    	<tr>
    	  <td align="right">&nbsp;</td>
    	  <td align="left">&nbsp;</td>
    	  <td align="right">&nbsp;</td>
    	  <td align="left">&nbsp;</td>
    	  <td align="left">&nbsp;</td>
    	  <td align="right">&nbsp;</td>
    	  <td align="left">&nbsp;</td>
  	  </tr>
    	<tr>
    	  <td align="right">Life Member ?</td>
    	  <td align="left"><input name="LifeMember" type="checkbox" <?php if($row_Details['LifeMember']==1){ ?> checked <?php } ?>/></td>
    	  <td align="right">Referee</td>
    	  <td align="left"><input name="Referee" type="checkbox" <?php if($row_Details['Referee']==1){ ?> checked <?php } ?>/></td>
    	  <td align="left"><?php if($row_Details['Referee']==1) echo $row_Details['Ref_Class'] ; else echo "" ?></td>
    	  <td align="right">Prospective Referee</td>
    	  <td align="left"><input name="prospective_ref" type="checkbox" <?php if($row_Details['prospective_ref']==1)  { ?> checked="checked" <?php } ?>/></td>
  	  </tr>
    	<tr>
    	  <td align="right">CCC Player</td>
    	  <td align="left"><input name="ccc_player" type="checkbox" <?php if($row_Details['ccc_player']==1){ ?> checked <?php } ?>/></td>
    	  <td align="right">Paid Member</td>
    	  <td align="left"><input name="paid_memb" type="checkbox" <?php if(isset($row_Details['paid_memb'])) { ?> checked <?php } ?>/></td>
    	  <td>&nbsp;</td>
    	  <td align="right">M'ship form OK</td>
    	  <td align="left"><input name="memb_by" type="checkbox" <?php if(isset($row_Details['memb_by']))  { ?> checked <?php } ?>/></td>
  	  </tr>
      <tr>
        <td align="right">Affiliate</td>
        <td align="left"><input name="affiliate_player" type="checkbox" <?php if($row_Details['affiliate_player']==1)  { ?> checked="checked" <?php } ?>/></td>
        <td align="center">Affiliate 1&nbsp;<input type='text' name="affiliate_1" value="<?= $row_Details['Affiliate_1'] ?>" size="10"></td>
        <td align="center">Affiliate 2&nbsp;<input type='text' name="affiliate_2" value="<?= $row_Details['Affiliate_2'] ?>" size="10"></td>
        <td align="center">Affiliate 3&nbsp;<input type='text' name="affiliate_3" value="<?= $row_Details['Affiliate_3'] ?>" size="10"></td>
        <td align="center">Affiliate 4&nbsp;<input type='text' name="affiliate_4" value="<?= $row_Details['Affiliate_4'] ?>" size="10"></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Coach</td>
        <td align="left"><input name="coach" type="checkbox" <?php if($row_Details['coach_id']>0){ ?> checked <?php } ?>/></td>
        <td align="right">Class</td>
        <td><input name="class" type="text" value='<?php echo($row_Details['class']) ?>'/></td>
        <td>&nbsp;</td>
        <td align="right">Coach ID</td>
        <td align="left"><input type="text" name='coach_id' value='<?php echo($row_Details['coach_id']) ?>' size=5 ></td>
      </tr>
      <tr>
         <td align="right">Is Coach Active?</td>
         <td><input type="checkbox" name="active" id="active" <?php if($row_Details['active'] == 1) {echo "checked=\"checked\"";} ?> /></td>
         <td align="right" >Comment</td>
         <td valign='top' colspan=5 align="left"><textarea name='comment' rows="3" cols="86" align=top><?php echo($row_Details['comment']) ?></textarea></td>
      </tr>
      <tr>
        <td colspan="7">&nbsp;</td>
      </tr>
      <?php 
        if($row_Details['paid_memb'] == 0)
        {
          $paid = NULL;
        }
        else
        {
          $paid = $row_Details['paid_memb'];
        }
        ?>
      <tr>
        <td align="right">Paid $</td>
        <td align="left"><input type="text" name="paid_memb" value="<?php echo $paid; ?>" size="10" /></td>
        <td align="right">How Paid:</td>
        <td><select name="paid_how">
                <option value="" >No Entry</option>
                <option value="PP" <?php if (!(strcmp("PP", htmlentities($row_Details['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>PP</option>
                <option value="Cash" <?php if (!(strcmp("Cash", htmlentities($row_Details['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Cash</option>
                <option value="BT" <?php if (!(strcmp("BT", htmlentities($row_Details['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>BT</option>
                <option value="CHQ" <?php if (!(strcmp("CHQ", htmlentities($row_Details['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>CHQ</option>
                <option value="Other" <?php if (!(strcmp("Other", htmlentities($row_Details['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Other</option>
              </select>
        </td>
        <td align="right">Date Paid:&nbsp;</td>
        <td colspan='2' align="left"><input type="text" name="paid_date" value="<?php echo htmlentities($row_Details['paid_date'], ENT_COMPAT, 'utf-8'); ?>" size="15" /><input type="button" value="Select Date Paid" onclick="displayDatePicker('paid_date', false, 'ymd', '.');" /></td>
      </tr>
      <tr>
        <td align='center' colspan="7">(Remove ALL fields if removing as paid)</td>
      </tr>
      <tr>
        <td align="right">Entered on</td>
        <td>&nbsp;
      <?php
      $year = date("Y", strtotime($row_Details['entered_on']));
      if($year >1900) {
      $newDate = date("d M Y", strtotime($row_Details['entered_on'])); 
      echo $newDate; }
      else
      echo "Not Known";
      ?>
        </td>
        <td align="right">Last Updated</td>
        <td>
      <?php $newDate = date("d M Y", strtotime($row_Details['LastUpdated']));
      if($row_Details['LastUpdated'] != NULL)
        echo $newDate; else echo "No update" ?>
        </td>
        <td align="right">Updated By</td>
        <td colspan="2"><?php echo $row_Details['UpdateBy']; ?></td>
      </tr>
    	
    </table>
</div>
  <div id="DBcontent">
  <table width="1000" align="center" cellpadding="3" cellspacing="3"> 
  <tr>
    <td colspan="3"><span class="red_bold"> Member? </span> One of the following conditions must be true</td>
    </tr>
  <tr>
    <td align="left">1. Life member of the VBSA.
      <?php
      				if ($row_Details['LifeMember']==1)
      				{
		  				  echo "<img src='../Admin_Images/tick.JPG' width=\"16\">";
		  				}
		  				else
              {
						    echo '<span class="red_text"> No </span>';
              }
          		?>
      </td>
    <td colspan="-1" align="left">2. Referee (reviewed annually by Vic head of Referees) 
      <?php
      				if ($row_Details['Referee']==1)
      				{
		  				  echo "<img src='../Admin_Images/tick.JPG' width=\"16\">";
		  				}
		  				else
              {
                echo '<span class="red_text"> No </span>';
              }
          		?></td>
    </tr>
  <tr>
    <td align="left">3 Having played 1 or more matches in the current year.
      <?php
      				if ($row_playedcalyear['totplayedcal'] >0)
      				{
  		  				echo "<img src='../Admin_Images/tick.JPG' width=\"16\">";
  		  				}
		  				else
              {
                echo '<span class="red_text"> No </span>';
              }
          		?></td>
    <td colspan="-1" align="left">4. Having paid $20 in the current year. 
      <?php
          	$curYear = date('Y');
						echo $curYear; 
						if ((isset($row_Details['paid_memb'])) && ($row_Details['paid_memb'] > 0))
          	{
		  				echo "<img src='../Admin_Images/tick.JPG' width=\"16\">";
		  			}
		  			else
            {
              echo '<span class="red_text"> No </span>';
            }
          	?> 
    </td>
        
    </tr>
</table>

</div>
<!--  <div id="DBcontent">
  <table width="1000" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="6"><span class="red_bold"><?php echo date("Y"); ?> Financial Detail - (Tournament Members only) editable by the treasurer only.<a href="mailto:treasurer@vbsa.org.au"></a></span></td>
  </tr>
  <tr>
    <td align="right">Paid $</td>
    <td align="left"><?php echo $row_fin['paid_memb']; ?></td>
    <td align="right">How Paid:</td>
    <td align="left"><?php echo $row_fin['paid_how']; ?></td>
    <td align="right">Date Paid:&nbsp;</td>
    <td align="left">&nbsp;
    <?php
	$year = ($row_fin['paid_date'] ? date("Y", strtotime($row_fin['paid_date'])) : null);
	if($year >2000) {
	$newDate = date("d M Y", strtotime($row_fin['paid_date'])); 
	echo $newDate; }
	else
	echo "";
	?>
	</td>
  </tr>
  </table>-->

</body>
</html>
<?php
mysql_free_result($Details);

mysql_free_result($playedcalyear);

mysql_free_result($fin);

mysql_free_result($MonClub);

mysql_free_result($WedClub);
?>