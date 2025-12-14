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
mysql_select_db($database_connvbsa, $connvbsa);
$query_RPall = "Select tourn_2, tourn_1, tourn_curr, scr_curr_s1, scr_curr_s2, scr_2yr_s1+scr_2yr_s2 as scr_2yr, scr_1yr_s1+scr_1yr_s2 as scr_1yr, scr_curr_s1+scr_curr_s2 as scr_curr, scr_2yr_s1+scr_2yr_s2+scr_1yr_s1+scr_1yr_s2+scr_curr_s1+scr_curr_s2+brks_2+brks_1+brks_curr+tourn_2+tourn_1+tourn_curr as total_points, ranknum, rank_Billiards.memb_id, FirstName, LastName, Female, Junior, date_format(rank_Billiards.last_update,'%D %b %Y') AS last_update FROM rank_Billiards LEFT JOIN members ON MemberID = rank_Billiards.memb_id LEFT JOIN rank_a_billiards_master ON rank_a_billiards_master.memb_id = rank_Billiards.memb_id ORDER BY rank_Billiards.total_rp DESC";
//echo($query_RPall . "<br>");
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
    <td align="center" ><span class="red_bold"><?php echo date("Y"); ?> Victorian Tournament Billiard Rankings</span> Last updated: <?php echo $row_RPall['last_update']; ?></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="greenbg"></td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <th class="text-center">Currently ranked</th>
    <th class="text-center">Member ID </th>
    <th class="text-left">Name</th>
    <th class="text-center">2021 Points</th>
    <th class="text-center">2022 Points</th>
    <th class="text-center">2023 Billiards Points</th>
    <th class="text-center">2023 Pennant S1</th>
    <th class="text-center">2023 Pennant S2</th>
    <th class="text-center">Total Points</th>
    <th>&nbsp;</th>
  </tr>
  <?php do { ?>
  <tr>
    <td class="text-center"><?php echo $row_RPall['ranknum']; ?></td>
    <td class="text-center"><?php echo $row_RPall['memb_id']; ?></td>
    <td class="text-left" nowrap="nowrap"><?php echo $row_RPall['FirstName'] . " ". $row_RPall['LastName']; ?></td>
    <td class="text-center"><?php echo $row_RPall['tourn_1']; ?></td>
    <td class="text-center"><?php echo $row_RPall['tourn_2']; ?></td>
    <td class="text-center"><?php echo $row_RPall['tourn_curr']; ?></td>
    <td class="text-center"><?php echo $row_RPall['scr_curr_s1']; ?></td>
    <td class="text-center"><?php echo $row_RPall['scr_curr_s2']; ?></td>
    <td class="text-center"><?php echo $row_RPall['total_points']; ?></td>
    <td class="text-center" nowrap="nowrap"><a href="rankings_vic_billiards_detail.php?rank=<?php echo $row_RPall['memb_id']; ?>">How awarded</a></td>
  </tr>
  <?php } while ($row_RPall = mysql_fetch_assoc($RPall)); ?>
</table>
</body>
</html>