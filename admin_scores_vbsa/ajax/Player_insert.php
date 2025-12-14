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

$editFormAction = 'ajax/Player_insert.php';
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO scrs (MemberID, team_grade, allocated_rp, game_type, scr_season, team_id, maxpts, fin_year_scrs, current_year_scrs, final_sub) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['MemberID'], "int"),
                       GetSQLValueString($_POST['team_grade'], "text"),
					   GetSQLValueString($_POST['allocated_rp'], "int"),
					   GetSQLValueString($_POST['game_type'], "text"),
					   GetSQLValueString($_POST['scr_season'], "text"),
					   GetSQLValueString($_POST['team_id'], "int"),
					   GetSQLValueString($_POST['maxpts'], "int"),
					   GetSQLValueString($_POST['fin_year_scrs'], "date"),
                       GetSQLValueString($_POST['current_year_scrs'], "date"),
					   GetSQLValueString($_POST['final_sub'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
  exit;
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}
  
$colname_Teams = "-1";
if (isset($_GET['team_id'])) {
  $colname_Teams = $_GET['team_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Teams = sprintf("SELECT scrs.team_id, Team_entries.team_grade, members.MemberID, members.FirstName, members.LastName FROM scrs, members, Team_entries WHERE scrs.MemberID= members.MemberID AND scrs.team_id=Team_entries.team_id AND scrs.team_id=%s ORDER BY FirstName", GetSQLValueString($colname_Teams, "int"));
$Teams = mysql_query($query_Teams, $connvbsa) or die(mysql_error());
$row_Teams = mysql_fetch_assoc($Teams);
$totalRows_Teams = mysql_num_rows($Teams);

$colname_Members = "-1";
if (isset($_GET['team_id'])) {
  $colname_Members = $_GET['team_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Members = sprintf("SELECT DISTINCT members.MemberID, members.LastName, members.FirstName, members.Club, members.Club2, Team_entries.team_id, Team_entries.team_club FROM members, Team_entries WHERE Team_entries.team_club=members.Club AND Team_entries.team_id=%s ORDER BY members.LastName", GetSQLValueString($colname_Members, "int"));
$Members = mysql_query($query_Members, $connvbsa) or die(mysql_error());
$row_Members = mysql_fetch_assoc($Members);
$totalRows_Members = mysql_num_rows($Members);

$colname_Club2 = "-1";
if (isset($_GET['teamid'])) {
  $colname_Club2 = $_GET['teamid'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Club2 = sprintf("SELECT DISTINCT members.MemberID, members.LastName, members.FirstName, members.Club, members.Club2, Team_entries.team_id, Team_entries.team_club FROM members, Team_entries WHERE Team_entries.team_club=members.Club2 AND Team_entries.team_id=%s ORDER BY members.LastName", GetSQLValueString($colname_Club2, "int"));
$Club2 = mysql_query($query_Club2, $connvbsa) or die(mysql_error());
$row_Club2 = mysql_fetch_assoc($Club2);
$totalRows_Club2 = mysql_num_rows($Club2);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grade_det = "SELECT grade, RP, type, season FROM Team_grade WHERE grade='$grade'";
$grade_det = mysql_query($query_grade_det, $connvbsa) or die(mysql_error());
$row_grade_det = mysql_fetch_assoc($grade_det);
$totalRows_grade_det = mysql_num_rows($grade_det);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
</head>

<body>
</div>
<center>

  <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1" onsubmit="return doit()">
    <table align="center">
      <tr valign="baseline">
        <td colspan="4" align="left" valign="middle" nowrap="nowrap" class="red_bold">Insert a Player</span> into Team ID:<span class="pagetitle"><?php echo $colname_Teams; ?> in Grade: <?php echo $grade; ?> in Season <?php echo $season; ?></td>
      </tr>
      <tr valign="baseline">
        <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td width="350" align="left" valign="middle" nowrap="nowrap">To insert a bye enter Member ID : &quot;1&quot;</td>
        <td align="right" valign="middle" nowrap="nowrap">Member ID</td>
        <td width="90" align="left" valign="middle" nowrap="nowrap"><input type="text" name="MemberID" value="" size="15" /></td>
        <td width="179" align="left" valign="middle" nowrap="nowrap"><span class="pagetitle">
          <input type="submit" value="Insert Player" />
        </span></td>
      </tr>
      <tr valign="baseline">
        <td align="left" valign="middle" nowrap="nowrap">To insert a Player forfeit enter Member ID: &quot;100&quot;</td>
        <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td colspan="2" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td width="350" align="left" valign="middle" nowrap="nowrap">To insert a Team forfeit enter Member ID: &quot;1000&quot;</td>
        <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td colspan="2" rowspan="2" align="left" valign="middle" nowrap="nowrap">
        <!--Nested Table -->
        	<table>
            <tr>
                <td><input name="quicksearch" type="text" id="quick_search" size="20" /></td><td nowrap="nowrap">Quick Surname Search</td>
            </tr>
            <tr>
                <td><div id="qs_result" align="left" style="z-index: 1; position: absolute; "></div></td><td>&nbsp;</td>
            </tr>
        </table>
        
        
        </td>
      </tr>
      <tr valign="baseline">
        <td width="350" align="left" valign="middle" nowrap="nowrap"><span class="pagetitle">Player will be inserted into Season <?php echo $season; ?> </span></td>
        <td width="219" align="right" valign="middle">&nbsp;</td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1" />
    <input type="hidden" name="team_id" value="<?php echo $colname_Teams; ?>" />
    <input type="hidden" name="maxpts" value="<?php if($row_grade_det['type']=='Billiards') echo 2; else echo 3 ?>" />
    <input type="hidden" name="team_grade" value="<?php echo $grade; ?>" />
    <input type="hidden" name="allocated_rp" value="<?php echo $row_grade_det['RP']; ?>" />
    <input type="hidden" name="game_type" value="<?php echo $row_grade_det['type']; ?>" />
    <input type="hidden" name="scr_season" value="<?php echo $season; ?>" />
    <input type="hidden" name="fin_year_scrs" value="<?php 
		if(date('m')<07) echo date("Y"); // Enters Current year as fin year if date before June 30
		else echo date("Y")+1; // Enters New fin year if date after June 30
		?> " />
    <input type="hidden" name="current_year_scrs" value="<?php echo date("Y")?> " />
    <input type="hidden" name="final_sub" value="No" />
  </form>
  <table>
    <tr>
      <td colspan="3" class="red_bold">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="red_bold">Current Members of team <?php echo $row_Teams['team_id']; ?></td>
    </tr>
    <tr>
      <td>Member ID</td>
      <td>First Name</td>
      <td>Last Name</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_Teams['MemberID']; ?></td>
        <td><?php echo $row_Teams['FirstName']; ?></td>
        <td><?php echo $row_Teams['LastName']; ?></td>
      </tr>
      <?php } while ($row_Teams = mysql_fetch_assoc($Teams)); ?>
  </table>
  <p>&nbsp;</p>
  <table width="800" border="1">

    <tr>
      <td colspan="4">From Members where &quot;Club&quot; = Team Entries Club</td>
    </tr>
    <tr>
      <td>MemberID</td>
      <td>LastName</td>
      <td>FirstName</td>
      <td>Club</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_Members['MemberID']; ?></td>
        <td><?php echo $row_Members['LastName']; ?></td>
        <td><?php echo $row_Members['FirstName']; ?></td>
        <td><?php echo $row_Members['Club']; ?></td>
      </tr>
      <?php } while ($row_Members = mysql_fetch_assoc($Members)); ?>
  </table>
  <table width="800" border="1">
    <tr>
      <td colspan="4">From Members where &quot;Club2&quot; = Team Entries Club</td>
    </tr>
    <tr>
      <td>MemberID</td>
      <td>LastName</td>
      <td>FirstName</td>
      <td>Club2</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_Club2['MemberID']; ?></td>
        <td><?php echo $row_Club2['LastName']; ?></td>
        <td><?php echo $row_Club2['FirstName']; ?></td>
        <td><?php echo $row_Club2['Club2']; ?></td>
      </tr>
      <?php } while ($row_Club2 = mysql_fetch_assoc($Club2)); ?>
  </table>
</center>
</body>
</html>
<?php
mysql_free_result($Teams);

mysql_free_result($Members);

mysql_free_result($Club2);

mysql_free_result($grade_det);
?>

<script type="text/javascript">
    $(function(){
        $('#quick_search').keyup(function(e){
            console.log("Got call");
            var input = $(this).val();
            input = input.trim();
            if(input.length == 0) {
                return;
            }
            $.ajax({
                type: "get",
                url: "ajax/quick_member_search.php",
                data: {last_name: input},
                async: true,
                success: function(data){
                    var member = $.parseJSON(data);
                    $('#qs_result').html('');
                    for(x = 0; x < member.length; x++){
                        $('#qs_result').prepend('<div>' + member[x].MemberID + ' ' + member[x].FirstName + ' ' + member[x].LastName +  '</div>'); //Fills the #auto div with the options
                    }
                }
            })
        })
    });
</script>

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
                        alert('Updated Succesfully!');
						location.reload(); 
                      },
            error   : function( xhr, err ) {
                        alert('Error');     
                      }
        }); 
        return false;
}

</script>