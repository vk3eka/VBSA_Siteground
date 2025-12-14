<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE members SET LastName=%s, FirstName=%s, MobilePhone=%s, ReceiveSMS=%s, Email=%s, ReceiveEmail=%s, entered_on=%s, Female=%s, LastUpdated=%s, UpdateBy=%s, dob_day=%s, dob_mnth=%s, dob_year=%s WHERE MemberID=%s",
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['MobilePhone'], "text"),
                       GetSQLValueString(isset($_POST['ReceiveSMS']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString(isset($_POST['ReceiveEmail']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['entered_on'], "date"),
                       GetSQLValueString(isset($_POST['Female']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['LastUpdated'], "date"),
                       GetSQLValueString($_POST['UpdateBy'], "text"),
                       GetSQLValueString($_POST['dob_day'], "int"),
                       GetSQLValueString($_POST['dob_mnth'], "int"),
                       GetSQLValueString($_POST['dob_year'], "date"),
                       GetSQLValueString($_POST['MemberID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../team_entries_player_multiple_insert.php?season=".$season."&team_club=".$team_club."&team_id=".$team_id;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$mid = "-1";
if (isset($_GET['mid'])) {
  $mid = $_GET['mid'];
}

$team_id = "-1";
if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

$team_grade = "-1";
if (isset($_GET['team_grade'])) {
  $team_grade = $_GET['team_grade'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}


mysql_select_db($database_connvbsa, $connvbsa);
$query_membedit = "SELECT members.MemberID, LastName, FirstName, MobilePhone, ReceiveSMS, Email, ReceiveEmail, members.LastUpdated, Female,  members.UpdateBy, entered_on, dob_day, dob_mnth, dob_year FROM members WHERE members.MemberID='$mid'";
$membedit = mysql_query($query_membedit, $connvbsa) or die(mysql_error());
$row_membedit = mysql_fetch_assoc($membedit);
$totalRows_membedit = mysql_num_rows($membedit);


$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);

mysql_select_db($database_connvbsa, $connvbsa);
$query_fin = "SELECT MemberID, paid_memb, paid_how, paid_date FROM members WHERE paid_memb is not null AND YEAR(paid_date)=YEAR( CURDATE( ) ) AND MemberID='$mid'";
$fin = mysql_query($query_fin, $connvbsa) or die(mysql_error());
$row_fin = mysql_fetch_assoc($fin);
$totalRows_fin = mysql_num_rows($fin);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Member Edit</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>


</head>

<body>
<table width="1000" align="center" cellpadding="2">
  <tr>
    <td class="red_bold">EDIT PERSONAL DETAIL (quick edit, to edit other fields go to the members section)</td>
  </tr>
</table>
<div id="DBcontent">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
              <table width="1000" align="center" cellpadding="4" cellspacing="4">
                      <tr>
                        <td align="right" nowrap="nowrap">Member ID: </td>
                        <td align="left"><?php echo $mid; ?></td>
                        <td>Playing in: Team ID</td>
                        <td><?php echo $team_id; ?></td>
                        <td>In Grade: <?php echo $team_grade; ?></td>
                        <td>Season: <?php echo $season; ?></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td >&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td >&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">Last Name: </td>
                        <td><input type="text" name="LastName" value="<?php echo htmlentities($row_membedit['LastName'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right">&nbsp;</td>
                        <td >&nbsp;</td>
                        <td align="right">Mobile</td>
                        <td ><input type="text" name="MobilePhone" value="<?php echo htmlentities($row_membedit['MobilePhone'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                      </tr>
                      <tr>
                        <td align="right">First Name: </td>
                        <td><input type="text" name="FirstName" value="<?php echo htmlentities($row_membedit['FirstName'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right">Email: </td>
                        <td><span class="page">
                          <input type="text" name="Email" value="<?php echo htmlentities($row_membedit['Email'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
                        </span></td>
                      </tr>
                      <tr>
                        <td height="41" align="right">Female</td>
                        <td><input type="checkbox" name="Female"  id="Female"  <?php if (!(strcmp(htmlentities($row_membedit['Female'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right" nowrap="nowrap">Receive Email</td>
                        <td><input type="checkbox" name="ReceiveEmail" id="ReceiveEmail"  <?php if (!(strcmp(htmlentities($row_membedit['ReceiveEmail'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right" nowrap="nowrap">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right" nowrap="nowrap">Receive SMS</td>
                        <td><input type="checkbox" name="ReceiveSMS" id="ReceiveSMS"  <?php if (!(strcmp(htmlentities($row_membedit['ReceiveSMS'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right" nowrap="nowrap">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">DOB</td>
                        <td colspan="5"><input type="text" name="dob_day" value="<?php echo htmlentities($row_membedit['dob_day'], ENT_COMPAT, 'utf-8'); ?>" size="2" /> <input type="text" name="dob_mnth" value="<?php echo htmlentities($row_membedit['dob_mnth'], ENT_COMPAT, 'utf-8'); ?>" size="2" /> 
                          <input type="date" name="dob_year" value="<?php echo htmlentities($row_membedit['dob_year'], ENT_COMPAT, 'utf-8'); ?>" size="4" /> 
                        Please insert as dd - mm - yyyy eg 01-01-2001. <span class="red_text">NOTE: For a player to appear as a junior they MUST have a date of birth listed</span></td>
                      </tr>
                      <tr>
                        <td align="right">Junior?:</td>
                        <td colspan="3">
						 <?php
						$age = (date("Y") -$row_membedit['dob_year']);
						if($age <=21)
						{
						echo "Yes";
						}
						elseif($age >21)
						{
						echo "No";
						}
						?>
                        
                         <?php
						$age = (date("Y") -$row_membedit['dob_year']);
						if($age==19 or $age==20 or $age==21)
						{
						echo "Minimum Competing age group: Under ";
						echo "21";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_membedit['dob_year'];
						}
						elseif($age==16 or $age==17 or $age==18)
						{
						echo "Minimum Competing age group: Under ";
						echo "18";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_membedit['dob_year'];
						}
						elseif($age==13 or $age==14 or $age==15)
						{
						echo "Minimum Competing age group: Under ";
						echo "15";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_membedit['dob_year'];
						}
						elseif($age==12)
						{
						echo "Minimum Competing age group: Under ";
						echo "12";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_membedit['dob_year'];
						}
						else
						{
						echo"";
						}
						?>
                        
                        </td>
                        <td align="left" nowrap="nowrap">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right" nowrap="nowrap">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">Entered on</td>
                        <td>&nbsp;
    						<?php
							$year = date("Y", strtotime($row_membedit['entered_on']));
							if($year >1900) {
							$newDate = date("d M Y", strtotime($row_membedit['entered_on'])); 
							echo $newDate; }
							else
							echo "Not Known";
							?>
                        </td>
                        <td align="right">Last Updated</td>
                        <td><?php $newDate = date("d M Y", strtotime($row_membedit['LastUpdated'])); echo $newDate; ?></td>
                        <td align="right">Updated By</td>
                        <td><?php echo $row_membedit['UpdateBy']; ?></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td align="left"><input type="submit" value="Update Member Details" /></td>
                        <td align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
    </table>
        <input type="hidden" name="MM_update" value="form1" />
      	<input type="hidden" name="MemberID" value="<?php echo $row_membedit['MemberID']; ?>" />
        <input type="hidden" name="UpdateBy" value="<?php echo $row_getusername['name']; ?>" />
        <input type="hidden" name="LastUpdated" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d"); ?> " />
   	<input type="hidden" name="MM_update" value="form1" />
  </form>
</div>
</body>
</html>
<?php
mysql_free_result($membedit);

mysql_free_result($fin);

mysql_free_result($getusername);
?>
