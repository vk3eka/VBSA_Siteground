<?php require_once('../Connections/connvbsa.php'); ?>
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

$col1_players = "-1";
if (isset($_GET['year'])) {
  $col1_players = $_GET['year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_players = sprintf("SELECT scrs.MemberID, FirstName, LastName, Club, MobilePhone, Email, SUM(played_S1 + played_S2 + played_bill_S1 + played_bill_S2) AS played FROM scrs LEFT JOIN members ON members.MemberID = scrs.MemberID WHERE current_year_scrs =%s GROUP BY scrs.MemberID HAVING SUM(played_S1 + played_S2 + played_bill_S1 + played_bill_S2)>0 ORDER BY Club, FirstName ", GetSQLValueString($col1_players, "int"));

//echo($query_players . "<br>");

$players = mysql_query($query_players, $connvbsa) or die(mysql_error());
$row_players = mysql_fetch_assoc($players);
$totalRows_players = mysql_num_rows($players);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />

<!--------- Facebox Starts-------------------------->
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
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="800" align="center">
  <tr>
    <td align="center" class="red_bold" nowrap="nowrap">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="red_bold" nowrap="nowrap">All Players that have played 1 or more matches for the <?php echo $col1_players; ?> Calendar Year</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td>Total Players: <?php echo $totalRows_players ?></td>
    <td>&nbsp;</td>
  </tr>
</table>
	<p>&nbsp;</p>
    <table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td align="left">MemberID</td>
        <td align="left">First Name</td>
        <td align="left">Last Name</td>
        <td align="left">Club</td>
        <td align="left">Mobile Phone</td>
        <td align="left">Email</td>
        <td align="center">Total played</td>
        <td align="center">&nbsp;</td>
      </tr>
      <?php do { ?>
        <tr>
          <td align="left"><?php echo $row_players['MemberID']; ?></td>
          <td align="left"><?php echo $row_players['FirstName']; ?></td>
          <td align="left"><?php echo $row_players['LastName']; ?></td>
          <td align="left" class="page"><?php echo $row_players['Club']; ?></td>
          <td align="left" class="page"><a href="tel:<?php echo $row_players['MobilePhone']; ?>"><?php echo $row_players['MobilePhone']; ?></a></td>
          <td align="left" class="page"><a href="mailto:<?php echo $row_players['Email']; ?>"><?php echo $row_players['Email']; ?></a></td>
          <td align="center" class="page"><?php echo $row_players['played']; ?></td>
          <td><a href="ajax/vbsa_personal_detail.php?MembDetail=<?php echo $row_players['MemberID']; ?>" rel="facebox"><img src="../Admin_Images/detail.fw.png" width="20" height="20" /></a></td>
        </tr>
        <?php } while ($row_players = mysql_fetch_assoc($players)); ?>
    </table>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
