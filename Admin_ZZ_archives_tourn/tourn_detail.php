<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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

if (isset($_GET['tid'])) {
  $tid = $_GET['tid'];
}


mysql_select_db($database_connvbsa, $connvbsa);
$query_arch_tourn = "SELECT * FROM tourn_archives WHERE tournament_ID = '$tid'";
$arch_tourn = mysql_query($query_arch_tourn, $connvbsa) or die(mysql_error());
$row_arch_tourn = mysql_fetch_assoc($arch_tourn);
$totalRows_arch_tourn = mysql_num_rows($arch_tourn);

mysql_select_db($database_connvbsa, $connvbsa);
$query_t_arch = "SELECT ID, tourn_year,  
CONCAT(t1.FirstName, ' ', t1.LastName) AS winner,  
CONCAT(t2.FirstName, ' ', t2.LastName) AS runnerup,  
CONCAT(t3.FirstName, ' ', t3.LastName) AS brkby, 
CONCAT(t4.FirstName, ' ', t4.LastName) AS shared1,
CONCAT(' / ',t5.FirstName, ' ', t5.LastName) AS shared2,
break, brk_comment, venue, why_not_run 
FROM tourn_archives_results  
LEFT JOIN members t1 ON t1.MemberID = win 
LEFT JOIN members t2 ON t2.MemberID = rup 
LEFT JOIN members t3 ON t3.MemberID = brk 
LEFT JOIN members t4 ON t4.MemberID = brk_shared1
LEFT JOIN members t5 ON t5.MemberID = brk_shared2
WHERE tourn_id = '$tid' 
ORDER BY tourn_year DESC";
$t_arch = mysql_query($query_t_arch, $connvbsa) or die(mysql_error());
$row_t_arch = mysql_fetch_assoc($t_arch);
$totalRows_t_arch = mysql_num_rows($t_arch);


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

<link href="../Admin_xx_CSS/Archives.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />

</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="1000" align="center">
  <tr>
    <td align="right" class="greenbg"><a href="index.php">Return Tournament Archive</a></td>
  </tr>
  <tr>
    <td class="red_bold">Archives for <?php echo $row_arch_tourn['tourn_name']."  (Tournament ID: ".$tid.")"; ?></td>
  </tr>
  <tr>
    <td align="right" class="greenbg"><a href="user_files/results_insert.php?tid=<?php echo $row_arch_tourn['tournament_ID']; ?>">Insert a new results for this tournament</a></td>
  </tr>
</table>
<table width="1000" align="center" class="page">
  <tr>
    <td colspan="2"> <em>Text areas can be edited from the first page</em></td>
  </tr>
  <tr>
    <td>About the: <?php echo $row_arch_tourn['tourn_name']; ?><br/></td>
    <td><?php echo $row_arch_tourn['about']; ?></td>
  </tr>
  <tr>
    <td>Footer</td>
    <td><?php echo $row_arch_tourn['footer']; ?> </td>
  </tr>
</table>

<p>&nbsp;</p>

<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <th>Year</th>
    <th>Winner</th>
    <th>Runner Up</th>
    <th>High Break</th>
    <th>Break</th>
    <th>Venue</th>
    <th>If not run in this year, reason why</th>
    <th>&nbsp;</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_t_arch['tourn_year']; ?></td>
      <td><?php echo $row_t_arch['winner']; ?></td>
      <td><?php echo $row_t_arch['runnerup']; ?></td>
      <td>
	  	<?php 	
		if(!empty($row_t_arch['brkby'])) 
		echo $row_t_arch['brkby'];
		else echo $row_t_arch['shared1'].$row_t_arch['shared2'];
		
		if (!empty($row_t_arch['brkby']) && !empty($row_t_arch['shared1']) && !empty($row_t_arch['shared2']));
		echo $row_t_arch['brk_comment'] 
		?>
      </td>
      <td><?php echo $row_t_arch['break']; ?></td>
      <td><?php echo $row_t_arch['venue']; ?></td>
      <td><?php echo $row_t_arch['why_not_run']; ?></td>
      <td><a href="user_files/results_edit.php?record_id=<?php echo $row_t_arch['ID']; ?>&tid=<?php echo $tid ?>"><img src="../Admin_Images/edit_butt.png" width="18" /></a></td>
    </tr>
    <?php } while ($row_t_arch = mysql_fetch_assoc($t_arch)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
