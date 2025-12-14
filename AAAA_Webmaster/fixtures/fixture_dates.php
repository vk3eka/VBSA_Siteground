<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster";
$MM_donotCheckaccess = "false";

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
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../Webmaster_access_denied.php";
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

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1 = "SELECT * FROM fix_date WHERE fix_date.fix_season='S1' AND fix_yr = YEAR( CURDATE( ) ) ORDER BY rd_id ASC, fix_day";
$S1 = mysql_query($query_S1, $connvbsa) or die(mysql_error());
$row_S1 = mysql_fetch_assoc($S1);
$totalRows_S1 = mysql_num_rows($S1);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/fixtures.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" />

<!--------- Facebox Start-------------------------->
<script src="facebox/jquery.js" type="text/javascript"></script>
<link href="facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>

<script src="facebox/facebox.js" type="text/javascript"></script> 

  <script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loadingImage : 'facebox/loading.gif',
        closeImage   : 'facebox/closelabel.png'
      })
    })
  </script>
  
<!--------- Facebox Ends-------------------------->

</head>

<body>
<?php include '../../admin_xx_includes/db_nav.php';?>
<?php include '../../admin_xx_includes/db_srch.php';?>
<table width="600" align="center">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td width="446" align="center" class="red_bold">Fixtures Dates</td>
    <td width="142" align="right" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
        
    </td>
  </tr>
  </table>
<div class="fix_date_content">
  <div class="fix_date">	 
  <div class="fix_title"><span class="red_bold">All Mondays for: <?php echo date("Y"); ?></span></div>	   
    <?php // select monday dates of current year
		for($i = 0; $i <= 365; $i ++)
		{

			$startdate = strtotime("first monday of january + $i day");
  			if(date('D', $startdate) == 'Mon')
			{
			echo '<table align="center" cellpadding="5" cellspacing="5">';
				echo '<tr>';
				echo '<td nowrap="nowrap">';
      			echo date('D d M', $startdate);
				echo '</td>';
				echo '<td nowrap="nowrap" class="greenbg">';
				?> 
    <a href="ajax/fix_date_insert.php?fixdate=<?php echo date('Y-m-d', $startdate) ?>" rel="facebox">Insert this date into the fixture dates table</a>
    <?php
				echo '</td>'; 
				echo '</tr>';
			echo '</table>';
  			}
		}
        ?>
  </div>
  
  <div class="fix_date">	 
  <div class="fix_title"><span class="red_bold">All Wednesday for: <?php echo date("Y"); ?></span></div>	   
    <?php // select wednesday dates for current year
		for($i = 0; $i <= 365; $i ++)
		{
  			$startdate = strtotime("first wednesday of january + $i day");
  			if(date('D', $startdate) == 'Wed')
			{
			echo '<table align="center" cellpadding="5" cellspacing="5">';
				echo '<tr>';
				echo '<td nowrap="nowrap">';
      			echo date('D d M', $startdate);
				echo '</td>';
				echo '<td nowrap="nowrap" class="greenbg">';
				?> 
    <a href="ajax/fix_date_insert.php?fixdate=<?php echo date('Y-m-d', $startdate) ?>" rel="facebox">Insert this date into the fixture dates table</a>
    <?php
				echo '</td>'; 
				echo '</tr>';
			echo '</table>';
  			}
		}
        ?>
  </div>
  
  <div class="fix_date">	 
  <div class="fix_title"><span class="red_bold">Selected dates for S1</span></div>
  <?php if ($totalRows_S1 > 0) { // Show if recordset not empty ?>
    <table cellpadding="5" cellspacing="5">
      <tr>
        <td align="center">Round</td>
        <td>Date</td>
        <td align="center">Season</td>
        <td align="center">Year</td>
        <td align="center">Day</td>
        <td align="center">&nbsp;</td>
        </tr>
      <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_S1['rd_id']; ?></td>
          <td><?php $newDate = date("D d M", strtotime($row_S1['date_rd'])); echo $newDate; ?></td>
          <td align="center"><?php echo $row_S1['fix_season']; ?></td>
          <td align="center"><?php echo $row_S1['fix_yr']; ?></td>
          <td align="center"><?php echo $row_S1['fix_day']; ?></td>
          <td align="center"><a href="ajax/fix_date_edit.php?fixround=<?php echo $row_S1['id']; ?>" rel="facebox"><img src="../../Admin_Images/edit_butt.fw.png" width="24" /></a></td>
          </tr>
        <?php } while ($row_S1 = mysql_fetch_assoc($S1)); ?>
      </table>
    <?php } // Show if recordset not empty 
  else
  echo "No dates entered for current year";
  ?>
  </div>
  
</div>
</body>
</html>
<?php
mysql_free_result($S1);
?>
