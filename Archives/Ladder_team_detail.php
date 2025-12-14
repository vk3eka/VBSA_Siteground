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

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$colname_ladd_det = "-1";
if (isset($_GET['teamdet'])) {
  $colname_ladd_det = $_GET['teamdet'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_ladd_det = sprintf("SELECT scrs.team_id, members.MemberID, members.FirstName, scrs.team_grade,  members.LastName, LifeMember, paid_memb, totplayed_curr, totplayed_prev, members.club, scrs.pts_won, scrs.scr_season, scrs.captain_scrs, totplayed_curr, totplayed_prev, scrs.r01s,scrs.r02s, scrs.r03s, scrs.r04s, scrs.r05s, scrs.r06s, scrs.r07s, scrs.r08s, scrs.r09s, scrs.r10s, scrs.r11s, scrs.r12s, scrs.r13s, scrs.r14s, scrs.r15s, scrs.r16s, scrs.r17s, scrs.r18s, SF1, SF2, PF, GF, comptype, Team_entries.team_name, team_cal_year FROM scrs  LEFT JOIN Team_entries ON Team_entries.team_id = scrs.team_id  LEFT JOIN members ON members.MemberID = scrs.MemberID WHERE scrs.MemberID=members.MemberID  AND scrs.team_id=%s AND members.MemberID <>1 GROUP BY scrs.scrsID ORDER BY members.FirstName, LastName", GetSQLValueString($colname_ladd_det, "int"));
$ladd_det = mysql_query($query_ladd_det, $connvbsa) or die(mysql_error());
$row_ladd_det = mysql_fetch_assoc($ladd_det);
$totalRows_ladd_det = mysql_num_rows($ladd_det);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Archives</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="vbsa">
    
    <!-- Include Google Tracking -->
<?php include_once("../includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
  <!--Left--> 
  
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">VBSA - <?php echo $row_ladd_det['team_cal_year']; ?> Archives. Team Detail for <?php echo $row_ladd_det['team_name']; ?> in <?php echo $row_ladd_det['team_grade']; ?></div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>  

    <!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>
  

	<table class="table table-responsive table-striped center-block" style="max-width:900px">
    <tr>
    <thead>
      <tr>
        <th>Name</th>
        <th align="center">&nbsp;</th>
      <th align="center">Pts</th>
      <th align="center">1</th>
      <th align="center">2</th>
      <th align="center">3</th>
      <th align="center">4</th>
      <th align="center">5</th>
      <th align="center">6</th>
      <th align="center">7</th>
      <th align="center">8</th>
      <th align="center">9</th>
      <th align="center">10</th>
      <th align="center">11</th>
      <th align="center">12</th>
      <th align="center">13</th>
      <th align="center">14</th>
      <th align="center">15</th>
      <th align="center">16</th>
      <th align="center">17</th>
      <th align="center">18</th>
      <th align="center">EF1</th>
      <th align="center">EF2</th>
      <th align="center">SF1</th>
      <th align="center">SF2</th>
      <th align="center">Pre</th>
      <th align="center">Grand</th>
      </tr>
      </thead>
    <?php do { ?>
    <tbody>
      <tr>
        <td nowrap="nowrap"><?php echo $row_ladd_det['FirstName']; ?> <?php echo $row_ladd_det['LastName']; ?></td>
        <td align="center" class="italic"><?php if($row_ladd_det['team_grade']=='BPBS') echo "S"; elseif($row_ladd_det['team_grade']=='BPB') echo "B"; else echo ""; ?></td>
        <td align="center"><?php echo $row_ladd_det['pts_won']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r01s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r02s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r03s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r04s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r05s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r06s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r07s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r08s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r09s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r10s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r11s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r12s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r13s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r14s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r15s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r16s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r17s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['r18s']; ?></td>
        <td align="center"><?php echo $row_ladd_det['EF1']; ?></td>
        <td align="center"><?php echo $row_ladd_det['EF2']; ?></td>
        <td align="center"><?php echo $row_ladd_det['SF1']; ?></td>
        <td align="center"><?php echo $row_ladd_det['SF2']; ?></td>
        <td align="center"><?php echo $row_ladd_det['PF']; ?></td>
        <td align="center"><?php echo $row_ladd_det['GF']; ?></td>
        </tr>
      <?php } while ($row_ladd_det = mysql_fetch_assoc($ladd_det)); ?>
      </tbody>
    </table>
  
</div><!-- close containing wrapper -->   
</body>
</html>
<?php

?>
