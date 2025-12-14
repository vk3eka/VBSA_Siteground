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
?><?php require_once('../../Connections/connvbsa.php'); ?>
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

if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_grades = "SELECT grade, grade_name, season, type, RP, `current` FROM Team_grade WHERE current='Yes' AND season='$season'";
$grades = mysql_query($query_grades, $connvbsa) or die(mysql_error());
$row_grades = mysql_fetch_assoc($grades);
$totalRows_grades = mysql_num_rows($grades);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO Team_entries (team_id, team_name, team_grade, team_season, day_played, players, Final5, include_draw, audited, team_cal_year, comptype) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['team_id'], "int"),
                       GetSQLValueString($_POST['team_name'], "text"),
                       GetSQLValueString($_POST['team_grade'], "text"),
					   GetSQLValueString($_POST['team_season'], "text"),
					   GetSQLValueString($_POST['day_played'], "text"),
					   GetSQLValueString($_POST['players'], "text"),
					   GetSQLValueString($_POST['Final5'], "int"),
					   GetSQLValueString($_POST['include_draw'], "text"),
					   GetSQLValueString($_POST['audited'], "text"),
					   GetSQLValueString($_POST['team_cal_year'], "date"),
					   GetSQLValueString($_POST['comptype'], "text"));


 mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../team_entries.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td align="center" nowrap="nowrap" class="red_bold">This will insert a Bye into Season <?php echo $season; ?></td>
      <td align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap" class="pagetitle">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Club ID:</td>
      <td>NA</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Club Title</td>
      <td>NA</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team Name:</td>
        <td>Bye (Auto entered)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team Grade:</td>
        <td>
          <select name="team_grade" id="team_grade">
            <?php
do {  
?>
            <option value="<?php echo $row_grades['grade']?>"<?php if (!(strcmp($row_grades['grade'], $row_grades['grade']))) {echo "selected=\"selected\"";} ?>><?php echo $row_grades['grade_name']?></option>
            <?php
} while ($row_grades = mysql_fetch_assoc($grades));
  $rows = mysql_num_rows($grades);
  if($rows > 0) {
      mysql_data_seek($grades, 0);
	  $row_grades = mysql_fetch_assoc($grades);
  }
?>
        </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Day</td>
        <td>
          <select name="day_played">
            <option value="Mon">Mon</option>
            <option value="Wed">Wed</option>
          </select>
        </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Type</td>
      <td><select name="comptype">
        <option value="Snooker" selected="selected">Snooker</option>
        <option value="Billiards">Billiards</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Season:</td>
      <td><?php echo $season; ?> (auto entered)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Year entered:</td>
      <td><?php echo date("Y")?> (auto entered)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Insert Team" /></td>
    </tr>
  </table>
  <input type="hidden" name="team_id" value="" />
  <input type="hidden" name="team_name" value="Bye" />
  <input type="hidden" name="team_season" value="<?php echo $season; ?>" />
  <input type="hidden" name="players" value="4" />
  <input type="hidden" name="Final5" value="4" />
  <input type="hidden" name="include_draw" value="Yes" />
  <input type="hidden" name="audited" value="No" />
  <input type="hidden" name="team_cal_year" value="<?php echo date("Y")?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>

</body>
</html>
<?php
mysql_free_result($grades);
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
