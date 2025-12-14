<?php require_once('../Connections/connvbsa.php'); 

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

mysql_select_db($database_connvbsa, $connvbsa);
//$query_RPall = "SELECT ranknum, memb_id, FirstName, LastName, Email, MobilePhone, total_tourn_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_open_tourn LEFT JOIN members ON MemberID = memb_id ORDER BY total_tourn_rp DESC";
 $query_RPall = "Select 
        tourn_2_w, 
        tourn_1_w, 
        tourn_curr_w, 
        scr_curr_S1, 
        scr_curr_S2, 
        scr_1yr_S1, 
        scr_1yr_S2, 
        scr_2yr_S1, 
        scr_2yr_S2, 
        weekly_total,
        weekly_percent, 
        Round(tourn_2_w + 
        tourn_1_w +
        tourn_curr_w) as total_tourn,
        Round(ROUND(scr_curr_S2)) + 
        (ROUND(scr_curr_S1)) + 
        (ROUND(scr_1yr_S1)) + 
        (ROUND(scr_1yr_S2)) + 
        (ROUND(scr_2yr_S1)) + 
        (ROUND(scr_2yr_S2)) as total_rp,
        last_update, 
        rank_aa_snooker_master.memb_id, FirstName, LastName, junior, Female  
        FROM rank_aa_snooker_master 
        LEFT JOIN members ON rank_aa_snooker_master.memb_id = MemberID
        where Female = 1 ORDER BY (weekly_percent+total_tourn) DESC";

$RPall = mysql_query($query_RPall, $connvbsa) or die(mysql_error());
$row_RPall = mysql_fetch_assoc($RPall);
$totalRows_RPall = mysql_num_rows($RPall);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table width="746" align="center">
    <tr>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
  </tr>
    <tr>
      <td align="center" ><span class="red_bold"><?php echo date("Y"); ?> Victorian Tournament Snooker (Womens) Rankings</span> Last updated: <?php echo $row_RPall['last_update']; ?></td>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
    <tr>
      <td colspan="2" align="center" class="greenbg"></td>
  </tr>
  </table>
  <table align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td align="center">Member ID</td>
      <td align="left">Last Name</td>
      <td align="left">First Name</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center">Currently ranked</td>
      <td align="center">Total Ranking Points</td>
      <td align="center">&nbsp;</td>
      </tr>
    <?php 
    $i = 1;
    do { 
      if($row_RPall['total_rp']+$row_RPall['total_tourn'] > 0)
      {
        ?>
      <tr>
        <td align="center"><?php echo $row_RPall['memb_id']; ?></td>
        <td align="left"><?php echo $row_RPall['LastName']; ?></td>
        <td align="left"><?php echo $row_RPall['FirstName']; ?></td>
        <td align="left" class="page"><a href="mailto:<?php echo $row_RPall['Email']; ?>"><?php echo $row_RPall['Email']; ?></a></td>
        <td align="left" class="page"><a href="tel:<?php echo $row_RPall['MobilePhone']; ?>"><?php echo $row_RPall['MobilePhone']; ?></a></td>
        <td align="center"><?php echo $i; ?></td>
        <td align="center"><?php echo ($row_RPall['total_tourn']+$row_RPall['weekly_percent']); ?></td>
        <td align="center" class="page"><a href="rankings_vic_snooker_detail.php?rank=<?php echo $row_RPall['memb_id']; ?>&cohort=woman">How awarded</a></td>
        </tr>
      <?php
      $i++;
      }
    } while ($row_RPall = mysql_fetch_assoc($RPall)); ?>
    </table>
</body>
</html>
<?php

?>
