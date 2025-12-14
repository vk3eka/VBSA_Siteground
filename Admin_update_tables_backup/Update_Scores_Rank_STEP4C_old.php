<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
        $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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


<script type="text/javascript" src="Scripts/AC_RunActiveContent.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
  
  <table align="center">
    <tr>
      <td><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="header_red">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center">STEP 4C of the calculation process</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="red_text">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="red_text">WARNING - PLEASE WAIT UNTIL PAGE HAS STOPPED RUNNING IN YOUR BROWSER</td>
    </tr>
    <tr>
      <td colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="red_text">ALL CALCULATIONS ARE FOR THE CURRENT YEAR. TO RECALCULATE PREVIOUS YEARS CONTACT THE WEBMASTER</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="red_text">&nbsp;</td>
    </tr>
    
  </table>
  
  <center>
  
  <?php require_once('../Connections/connvbsa.php'); ?>
  
  
  <?php

if(isset($_POST["submit"]))
{
  mysql_select_db($database_connvbsa, $connvbsa);

  echo "<font face='arial' size='3'>Calculations completed ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP5.php">'. "Thank you". '</a></spsn>';
  echo '<br/><br/>';

  echo "<font face='arial'>If you wish to update the External Emailing Lists go to ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP4D.php">'. "STEP 1". '</a></span>';
  echo '<br/><br/>';
  
  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Victorian Snooker Ranks</font><br>";

// ************************
// Add Snooker Rank Tables
// ************************

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Snooker Weekly, Open, Womens and Juniors rank tables</font><br>";

  // get existing total_rp from rank_Billiards before truncating
  $sum_total_rp = 'Select SUM(total_weekly_rp) as previous_total_rp from rank_S_open_weekly';
  $result = mysql_query($sum_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_previous  = $row_total['previous_total_rp'];
  echo("<br>Weekly Snooker Previous Total " . $total_previous . "<br>");

  $sum_existing_total_rp = "Select SUM(total_rp) as grand_total from (
  Select 
  rank_aa_snooker_master.memb_id AS memb_id,
  (ROUND(scr_curr_S2)) + 
  (ROUND(scr_curr_S1)) + 
  (ROUND(scr_1yr_S1)) +
  (ROUND(scr_1yr_S2)) +
  (ROUND(scr_2yr_S1)) +
  (ROUND(scr_2yr_S2)) as total_rp
  FROM rank_aa_snooker_master
  GROUP BY rank_aa_snooker_master.memb_id ) as subquery";
  //echo("Weekly " . $sum_existing_total_rp . "<br>");
  $result = mysql_query($sum_existing_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_new  = $row_total['grand_total'];
  echo("<br>Weekly Snooker New Total " . $total_new . "<br>");

  if($total_new != $total_previous)
  {
    $querytoexecute = "Truncate TABLE `temp_ranking`";
    $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

    $sql_copy = "Insert INTO temp_ranking SELECT * From rank_S_open_weekly order by total_weekly_rp;";
    $result = mysql_query($sql_copy, $connvbsa) or die(mysql_error());

    $querytoexecute = "Truncate TABLE `rank_S_open_weekly`";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - rank_S_open_weekly table - Snooker Rankings not truncated</font>");

    if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated rank_S_open_weekly table - Snooker Rankings successfully</font>";

    $querytoexecute = "Insert INTO `rank_S_open_weekly` 
    SELECT 
    0,
    0,
    rank_aa_snooker_master.memb_id AS memb_id,
    rank_aa_snooker_master.weekly_total AS total_weekly_rp,
    CURRENT_TIMESTAMP
    FROM rank_aa_snooker_master
    GROUP BY rank_aa_snooker_master.memb_id
    ORDER BY weekly_total DESC";
    //echo("Weekly " . $querytoexecute . "<br>");
    $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - rank_S_open_weekly table - Snooker Weekly Rankings data not inserted</font>");

    if (isset($result)) echo "<br><br><font face='arial' color='green'> 5. Data inserted to rank_S_open_weekly table - Snooker Weekly Rankings successfully</font>";


    $sql_exist = "Select * From rank_S_open_weekly Order By memb_id";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $querytoexecute = "Update rank_S_open_weekly Set previous_rank = " . $row_exist['ranknum'] . " Where memb_id = " . $row_exist['memb_id'];
      //echo("Query " . $querytoexecute . "<br>");
      $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
    }
  }
  $querytoexecute = "Truncate TABLE `temp_ranking`";
  $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

  echo("<br><br>rank_S_open_weekly complete<br>");

  // get existing total_rp from rank_S_open_tourn before truncating
  $sum_total_rp = 'Select SUM(total_tourn_rp) as previous_total_rp from rank_S_open_tourn';
  //echo("Tourn " . $sum_total_rp . "<br>");
  $result = mysql_query($sum_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_previous  = $row_total['previous_total_rp'];
  echo("<br>Open Snooker Previous Total " . $total_previous . "<br>");

  $sum_existing_total_rp = "Select SUM(total_rp) as grand_total from (
  Select 
  rank_aa_snooker_master.memb_id AS memb_id,
  Round((ROUND(tourn_2)) + 
  (ROUND(tourn_1)) + 
  tourn_curr) AS total_rp
  FROM rank_aa_snooker_master
  GROUP BY rank_aa_snooker_master.memb_id ) as subquery";
  //echo("Tourn " . $sum_existing_total_rp . "<br>");
  $result = mysql_query($sum_existing_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_new  = $row_total['grand_total'];
  echo("<br>Open Snooker New Total " . $total_new . "<br>");

  if($total_new != $total_previous)
  {
    $querytoexecute = "Truncate TABLE `temp_ranking`";
    $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

    $sql_copy = "Insert INTO temp_ranking SELECT * From rank_S_open_tourn Order By total_tourn_rp";
    $result = mysql_query($sql_copy, $connvbsa) or die(mysql_error());

    $querytoexecute = "Truncate TABLE `rank_S_open_tourn`";
    $result_truncate = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - rank_S_open_tourn table - Snooker Rankings not truncated</font>");

    if (isset($result_truncate)) echo "<br><font face='arial' color='green'> 1. Truncated rank_S_open_tourn table - Snooker Rankings successfully</font>";

    $querytoexecute = "Insert INTO `rank_S_open_tourn` 
    SELECT 
    0,
    0,
    rank_aa_snooker_master.memb_id AS memb_id,
    rank_aa_snooker_master.tourn_total AS total_tourn_rp,
    CURRENT_TIMESTAMP
    FROM rank_aa_snooker_master
    GROUP BY rank_aa_snooker_master.memb_id
    ORDER BY tourn_total DESC";
    //echo("Tourn Insert " . $querytoexecute . "<br>");
    $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - rank_S_open_tourn table - Snooker Weekly Rankings data not inserted</font>");

    if (isset($result)) echo "<br><br><font face='arial' color='green'> 5. Data inserted to rank_S_open_tourn table - Snooker Weekly Rankings successfully</font>";

    //$sql_exist = "Select * From rank_S_open_tourn Order By memb_id";
    $sql_exist = "Select * From rank_S_open_tourn Order By total_tourn_rp";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $querytoexecute = "Update rank_S_open_tourn Set previous_rank = " . $row_exist['ranknum'] . " Where memb_id = " . $row_exist['memb_id'];
      //echo("Query " . $querytoexecute . "<br>");
      $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
    }
  }
  $querytoexecute = "Truncate TABLE `temp_ranking`";
  $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

  echo("<br><br>rank_S_open_tourn complete<br>");



  // get existing total_rp from rank_Billiards before truncating
  $sum_total_rp = 'Select SUM(total_rp) as previous_total_rp from rank_S_womens';
  //echo("Tourn " . $sum_total_rp . "<br>");
  $result = mysql_query($sum_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_previous  = $row_total['previous_total_rp'];
  //echo("<br><br>Billiards<br>");
  echo("<br>Womens Snooker Previous Total " . $total_previous . "<br>");

  $sum_existing_total_rp = "Select SUM(total_rp) as grand_total from (
  Select 
  rank_aa_snooker_master.memb_id AS memb_id,
  Round((ROUND(tourn_2_w)) + 
        (ROUND(tourn_1_w)) + 
        tourn_curr_w + 
        (ROUND(scr_curr_S2)) + 
        (ROUND(scr_curr_S1)) + 
        (ROUND(scr_1yr_S1)) +
        (ROUND(scr_1yr_S2)) +
        (ROUND(scr_2yr_S1)) +
        (ROUND(scr_2yr_S2))) AS total_rp
  FROM rank_aa_snooker_master
  LEFT JOIN members T1 ON T1.MemberID = rank_aa_snooker_master.memb_id where Gender = 'Female'
  GROUP BY rank_aa_snooker_master.memb_id ) as subquery";
  //echo("Womens " . $sum_existing_total_rp . "<br>");
  $result = mysql_query($sum_existing_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_new  = $row_total['grand_total'];
  echo("<br>Womens Snooker New Total " . $total_new . "<br>");

  if($total_new != $total_previous)
  {
    $querytoexecute = "Truncate TABLE `temp_ranking`";
    $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

    $sql_copy = "Insert INTO temp_ranking SELECT * From rank_S_womens";
    $result = mysql_query($sql_copy, $connvbsa) or die(mysql_error());

    $querytoexecute = "Truncate TABLE `rank_S_womens`";
    $result_truncate = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - rank_S_womens table - Snooker Rankings not truncated</font>");

    if (isset($result_truncate)) echo "<br><font face='arial' color='green'> 1. Truncated rank_S_womens table - Snooker Rankings successfully</font>";

    $querytoexecute = "Insert INTO `rank_S_womens` 
    SELECT 
    0,
    0,
    rank_aa_snooker_master.memb_id AS memb_id,
    Round((ROUND(tourn_2_w)) + 
    (ROUND(tourn_1_w)) + 
    tourn_curr_w + 
    weekly_percent) AS total_rp,
    CURRENT_TIMESTAMP
    FROM rank_aa_snooker_master
    LEFT JOIN members T1 ON T1.MemberID = rank_aa_snooker_master.memb_id where Gender = 'Female'
    GROUP BY rank_aa_snooker_master.memb_id
    ORDER BY total_rp DESC";
/*
    $querytoexecute = "Insert INTO `rank_S_womens` 
    SELECT 
    0,
    0,
    rank_aa_snooker_master.memb_id AS memb_id,
    Round((ROUND(tourn_2_w)) + 
    (ROUND(tourn_1_w)) + 
    tourn_curr_w + 
    (ROUND(scr_curr_S2)) + 
    (ROUND(scr_curr_S1)) + 
    (ROUND(scr_1yr_S1)) +
    (ROUND(scr_1yr_S2)) +
    (ROUND(scr_2yr_S1)) +
    (ROUND(scr_2yr_S2))) AS total_rp,
    CURRENT_TIMESTAMP
    FROM rank_aa_snooker_master
    LEFT JOIN members T1 ON T1.MemberID = rank_aa_snooker_master.memb_id where Gender = 'Female'
    GROUP BY rank_aa_snooker_master.memb_id
    ORDER BY total_rp DESC";
    */

    //echo("Tourn Insert " . $querytoexecute . "<br>");
    $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - rank_S_womens table - Snooker Weekly Rankings data not inserted</font>");

    if (isset($result)) echo "<br><br><font face='arial' color='green'> 5. Data inserted to rank_S_womens table - Snooker Weekly Rankings successfully</font>";

    $sql_exist = "Select * From rank_S_womens Order By memb_id";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $querytoexecute = "Update rank_S_womens Set previous_rank = " . $row_exist['ranknum'] . " Where memb_id = " . $row_exist['memb_id'];
      //echo("Query " . $querytoexecute . "<br>");
      $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
    }
  }
  $querytoexecute = "Truncate TABLE `temp_ranking`";
  $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

  echo("<br><br>rank_S_womens complete<br>");



  // get existing total_rp from rank_Billiards before truncating
  $sum_total_rp = 'Select SUM(total_rp) as previous_total_rp from rank_S_junior';
  //echo("Tourn " . $sum_total_rp . "<br>");
  $result = mysql_query($sum_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_previous  = $row_total['previous_total_rp'];
  //echo("<br><br>Billiards<br>");
  echo("<br>Junior Snooker Previous Total " . $total_previous . "<br>");

  $sum_existing_total_rp = "Select SUM(total_rp) as grand_total from (
  Select 
  rank_aa_snooker_master.memb_id AS memb_id,
  Round((ROUND(tourn_2_w)) + 
  (ROUND(tourn_1_w)) + 
  tourn_curr_w + 
  (ROUND(scr_curr_S2)) + 
  (ROUND(scr_curr_S1)) + 
  (ROUND(scr_1yr_S1)) +
  (ROUND(scr_1yr_S2)) +
  (ROUND(scr_2yr_S1)) +
  (ROUND(scr_2yr_S2))) AS total_rp
  FROM rank_aa_snooker_master
  LEFT JOIN members T1 ON T1.MemberID = rank_aa_snooker_master.memb_id 
  where Junior != 'na' and Gender != 'Female'
  GROUP BY rank_aa_snooker_master.memb_id ) as subquery";
  //echo("Womens " . $sum_existing_total_rp . "<br>");
  $result = mysql_query($sum_existing_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_new  = $row_total['grand_total'];
  echo("<br>Junior Snooker New Total " . $total_new . "<br>");

  if($total_new != $total_previous)
  {
    $querytoexecute = "Truncate TABLE `temp_ranking`";
    $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

    $sql_copy = "Insert INTO temp_ranking SELECT * From rank_S_junior";
    $result = mysql_query($sql_copy, $connvbsa) or die(mysql_error());

    $querytoexecute = "Truncate TABLE `rank_S_junior`";
    $result_truncate = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - rank_S_junior table - Snooker Rankings not truncated</font>");

    if (isset($result_truncate)) echo "<br><font face='arial' color='green'> 1. Truncated rank_S_junior table - Snooker Rankings successfully</font>";

    $querytoexecute = "Insert INTO `rank_S_junior` 
    SELECT 
    0,
    0,
    rank_aa_snooker_master.memb_id AS memb_id,
    Round((ROUND(tourn_2_j)) + 
    (ROUND(tourn_1_j)) + 
    tourn_curr_j + 
    weekly_percent) AS total_rp,
    CURRENT_TIMESTAMP
    FROM rank_aa_snooker_master
    LEFT JOIN members T1 ON T1.MemberID = rank_aa_snooker_master.memb_id where Junior != 'na' and Gender != 'Female'
    GROUP BY rank_aa_snooker_master.memb_id
    ORDER BY total_rp DESC";

    /*$querytoexecute = "Insert INTO `rank_S_junior` 
    SELECT 
    0,
    0,
    rank_aa_snooker_master.memb_id AS memb_id,
    (weekly_percent + 
    tourn_2_w + 
    tourn_1_w +
    tourn_curr_w +
    (ROUND(scr_curr_S2)) + 
    (ROUND(scr_curr_S1)) + 
    (ROUND(scr_1yr_S1)) + 
    (ROUND(scr_1yr_S2)) + 
    (ROUND(scr_2yr_S1)) + 
    (ROUND(scr_2yr_S2))) as total_rp, 
    rank_aa_snooker_master.memb_id, FirstName, LastName, junior, Female,  
    rank_S_junior.ranknum, 
    rank_S_junior.previous_rank    
    FROM rank_aa_snooker_master 
    LEFT JOIN members T1 ON T1.MemberID = rank_aa_snooker_master.memb_id 
    LEFT JOIN rank_S_junior ON rank_S_junior.memb_id = T1.MemberID 
    where Junior != 'na' and Gender != 'Female' ORDER BY (total_rp) DESC";
    */

    //echo("Tourn Insert " . $querytoexecute . "<br>");
    $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - rank_S_junior table - Snooker Weekly Rankings data not inserted</font>");

    if (isset($result)) echo "<br><br><font face='arial' color='green'> 5. Data inserted to rank_S_junior table - Snooker Weekly Rankings successfully</font>";

    $sql_exist = "Select * From rank_S_junior Order By memb_id";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $querytoexecute = "Update rank_S_junior Set previous_rank = " . $row_exist['ranknum'] . " Where memb_id = " . $row_exist['memb_id'];
      //echo("Query " . $querytoexecute . "<br>");
      $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
    }
  }

  $querytoexecute = "Truncate TABLE `temp_ranking`";
  $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

  echo("<br><br>rank_S_junior complete<br>");


  //rank_S_open_weekly Snooker Weekly Rankings
  //$querytoexecute = "TRUNCATE TABLE `rank_S_open_weekly`";

  //$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - rank_S_open_weekly table - Snooker Weekly Rankings not truncated</font>");

  //if (isset($result)) echo "<br><br><font face='arial' color='green'> 1. Truncated rank_S_open_weekly table - Snooker Weekly Rankings successfully</font>";

  //rank_S_open_tourn Snooker Tournament Rankings
  //$querytoexecute = "TRUNCATE TABLE `rank_S_open_tourn`";

  //$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error - rank_S_open_tourn table - Snooker Tournament Rankings not truncated</font>");

  //if (isset($result)) echo "<br><br><font face='arial' color='green'> 2. Truncated rank_S_open_tourn table - Snooker Tournament Rankings successfully</font>";

  //rank_S_womens Womens Snooker Rankings
  //$querytoexecute = "TRUNCATE TABLE `rank_S_womens` ";

  //$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error - rank_S_womens table -  Womens Snooker Rankings not truncated</font>");

  //if (isset($result)) echo "<br><br><font face='arial' color='green'> 3. Truncated rank_S_womens table - Womens Snooker Rankings successfully</font>";

  //rank_S_junior Junior Snooker Rankings
  //$querytoexecute = "TRUNCATE TABLE `rank_S_junior`";

  //$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>4. Error - rank_S_junior table - Junior Snooker Rankings not truncated</font>");

  //if (isset($result)) echo "<br><br><font face='arial' color='green'> 4. Truncated rank_S_junior table - Junior Snooker successfully</font>";

  //Insert data into ranking tables
  /*
  //rank_S_open_weekly Snooker Weekly Rankings
  $querytoexecute = "INSERT INTO `rank_S_open_weekly` 
  SELECT 
  0,
  rank_aa_snooker_master.memb_id AS memb_id,
  rank_aa_snooker_master.weekly_total AS total_weekly_rp,
  CURRENT_TIMESTAMP
  FROM rank_aa_snooker_master
  GROUP BY rank_aa_snooker_master.memb_id
  ORDER BY weekly_total DESC";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - rank_S_open_weekly table - Snooker Weekly Rankings data not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 5. Data inserted to rank_S_open_weekly table - Snooker Weekly Rankings successfully</font>";
  */

  //rank_S_open_tourn Snooker Tournament Rankings
  /*$querytoexecute = "INSERT INTO `rank_S_open_tourn`
  SELECT 
  0,
  rank_aa_snooker_master.memb_id AS memb_id,
  rank_aa_snooker_master.tourn_total AS total_tourn_rp,
  CURRENT_TIMESTAMP
  FROM rank_aa_snooker_master
  WHERE tourn_total>0
  GROUP BY rank_aa_snooker_master.memb_id
  ORDER BY tourn_total DESC";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>6. Error - rank_S_open_tourn table - Snooker Tournament Rankings data not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 6. Data inserted to rank_S_open_tourn table - Snooker Tournament Rankings successfully</font>";
  */
  /*
  //rank_S_womens Womens Snooker Rankings
  $querytoexecute = "INSERT INTO `rank_S_womens`
  SELECT 
  0,
  rank_aa_snooker_master.memb_id AS memb_id,
  rank_aa_snooker_master.tourn_total AS total_rp,
  CURRENT_TIMESTAMP
  FROM rank_aa_snooker_master
  WHERE m_f='1' AND tourn_total>0 
  GROUP BY rank_aa_snooker_master.memb_id
  ORDER BY tourn_total DESC";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>7. Error - rank_S_womens table - Womens Snooker Rankings data not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 7. Data inserted to rank_S_womens table - Womens Snooker Rankings successfully</font>";

  //rank_S_junior Junior Snooker Rankings
  $querytoexecute = "INSERT INTO `rank_S_junior`
  SELECT 
  0,
  rank_aa_snooker_master.memb_id AS memb_id,
  rank_aa_snooker_master.tourn_total AS total_rp,
  CURRENT_TIMESTAMP
  FROM rank_aa_snooker_master
  WHERE jun !='na' AND tourn_total>0 
  GROUP BY rank_aa_snooker_master.memb_id
  ORDER BY tourn_total DESC";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>8. Error - rank_S_junior table - Junior Snooker Rankings data not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 8. Data inserted to rank_S_junior table - Junior Snooker Rankings successfully</font>";
  */

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Victorian Billiard Ranks</font.<br>";

  // ************************
  // Add Billiard Rank Tables
  // ************************

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Billiards Open, Womens and Juniors rank tables</font><br>";

  //START empty ranking tables then insert updated data from master tables
  //FOR Billiards open, womens and junior.

  // get existing total_rp from rank_Billiards before truncating
  $sum_total_rp = 'Select SUM(total_rp) as previous_total_rp from rank_Billiards';
  $result = mysql_query($sum_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_previous  = $row_total['previous_total_rp'];
  //echo("<br><br>Billiards<br>");
  echo("<br>Open Billiards Previous Total " . $total_previous . "<br>");

  $sum_existing_total_rp = "Select SUM(total_rp) as grand_total from (
  Select 
  rank_a_billiards_master.memb_id AS memb_id,
  Round((ROUND(tourn_2)) + 
  (ROUND(tourn_1)) + 
  tourn_curr + 
  brks_curr + 
  (ROUND(brks_2)) + 
  (ROUND(brks_1)) +
  (ROUND(scr_curr_S2)) + 
  (ROUND(scr_curr_S1)) + 
  (ROUND(scr_1yr_S1)) +
  (ROUND(scr_1yr_S2)) +
  (ROUND(scr_2yr_S1)) +
  (ROUND(scr_2yr_S2))) AS total_rp
  FROM rank_a_billiards_master
  GROUP BY rank_a_billiards_master.memb_id ) as subquery";
  $result = mysql_query($sum_existing_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_new  = $row_total['grand_total'];
  echo("<br>Open Billiards New Total " . $total_new . "<br>");

  if($total_new != $total_previous)
  {
    $querytoexecute = "Truncate TABLE `temp_ranking`";
    $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

    $sql_copy = "Insert INTO temp_ranking SELECT * From rank_Billiards";
    $result = mysql_query($sql_copy, $connvbsa) or die(mysql_error());

    $querytoexecute = "Truncate TABLE `rank_Billiards`";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - rank_Billiards table - Billiards Rankings not truncated</font>");

    if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated rank_Billiards table - Billiards Rankings successfully</font>";

    $querytoexecute = "Insert INTO `rank_Billiards`
    SELECT 
    0,
    0,
    rank_a_billiards_master.memb_id AS memb_id,
    Round((ROUND(tourn_2)) + 
    (ROUND(tourn_1)) + 
    tourn_curr + 
    brks_curr + 
    (ROUND(brks_2)) + 
    (ROUND(brks_1)) +
    (ROUND(scr_curr_S2)) + 
    (ROUND(scr_curr_S1)) + 
    (ROUND(scr_1yr_S1)) +
    (ROUND(scr_1yr_S2)) +
    (ROUND(scr_2yr_S1)) +
    (ROUND(scr_2yr_S2))) AS total_rp,
    CURRENT_TIMESTAMP
    FROM rank_a_billiards_master
    GROUP BY rank_a_billiards_master.memb_id
    ORDER BY total_rp DESC";
    //echo("Billiards " . $querytoexecute . "<br>");
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error - rank_Billiards table - Billiard Rankings data not inserted</font>");

    if (isset($result)) echo "<br><br><font face='arial' color='green'> 2. Data inserted to rank_Billiards table - Billiard Rankings successfully</font>";


    $sql_exist = "Select * From rank_Billiards Order By memb_id";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $querytoexecute = "Update rank_Billiards Set previous_rank = " . $row_exist['ranknum'] . " Where memb_id = " . $row_exist['memb_id'];
      //echo("Query " . $querytoexecute . "<br>");
      $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
    }
  }
  $querytoexecute = "Truncate TABLE `temp_ranking`";
  $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());


  // billiards womens

  // get existing total_rp from rank_Billiards before truncating
  $sum_total_rp = 'Select SUM(total_rp) as previous_total_rp from rank_B_womens';
  $result = mysql_query($sum_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_previous  = $row_total['previous_total_rp'];
  echo("<br>Womens Billiards Previous Total " . $total_previous . "<br>");

  $sum_existing_total_rp = "Select SUM(total_rp) as grand_total from (
  Select 
  rank_a_billiards_master.memb_id AS memb_id,
  Round((ROUND(tourn_2_w)) + 
  (ROUND(tourn_1_w)) + 
  tourn_curr_w + 
  brks_curr + 
  (ROUND(brks_2)) + 
  (ROUND(brks_1)) +
  (ROUND(scr_curr_S2)) + 
  (ROUND(scr_curr_S1)) + 
  (ROUND(scr_1yr_S1)) +
  (ROUND(scr_1yr_S2)) +
  (ROUND(scr_2yr_S1)) +
  (ROUND(scr_2yr_S2))
  ) 
  as total_rp
  FROM rank_a_billiards_master
  LEFT JOIN members T1 ON T1.MemberID = rank_a_billiards_master.memb_id where Gender = 'Female'
  GROUP BY rank_a_billiards_master.memb_id ) as subquery";
  //echo("Womens Billiards " . $sum_existing_total_rp . "<br>");
  $result = mysql_query($sum_existing_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  //$total_rp  = $row_total['total_rp'];
  $total_new  = $row_total['grand_total'];
  echo("<br>Womens Billiards New Total " . $total_new . "<br>");

  if($total_new != $total_previous)
  {
    $querytoexecute = "Truncate TABLE `temp_ranking`";
    $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

    $sql_copy = "Insert INTO temp_ranking SELECT * From rank_B_womens";
    $result = mysql_query($sql_copy, $connvbsa) or die(mysql_error());

    $querytoexecute = "Truncate TABLE `rank_B_womens`";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error - rank_Billiards table - Billiards Rankings not truncated</font>");

    if (isset($result)) echo "<br><font face='arial' color='green'> 3. Truncated rank_B_womens table - Billiards Rankings successfully</font>";

    $querytoexecute = "Insert INTO `rank_B_womens`
    SELECT 
    0,
    0,
    rank_a_billiards_master.memb_id AS memb_id,
    Round((ROUND(tourn_2_w)) + 
    (ROUND(tourn_1_w)) + 
    tourn_curr_w + 
    brks_curr + 
    (ROUND(brks_2)) + 
    (ROUND(brks_1)) +
    (ROUND(scr_curr_S2)) + 
    (ROUND(scr_curr_S1)) + 
    (ROUND(scr_1yr_S1)) +
    (ROUND(scr_1yr_S2)) +
    (ROUND(scr_2yr_S1)) +
    (ROUND(scr_2yr_S2))
    ) 
    as total_rp,
    CURRENT_TIMESTAMP
    FROM rank_a_billiards_master
    LEFT JOIN members T1 ON T1.MemberID = rank_a_billiards_master.memb_id where Gender = 'Female'
    GROUP BY rank_a_billiards_master.memb_id
    ORDER BY total_rp DESC";
    //echo("<br>Womens insert - " . $querytoexecute . "<br>");
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>4. Error - rank_B_womens table - Billiard Rankings data not inserted</font>");

    if (isset($result)) echo "<br><br><font face='arial' color='green'> 4. Data inserted to rank_B_womens table - Billiard Rankings successfully</font>";

    $sql_exist = "Select * From temp_ranking Order By memb_id";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $querytoexecute = "Update rank_B_womens Set previous_rank = " . $row_exist['ranknum'] . " Where memb_id = " . $row_exist['memb_id'];
      //echo("Query " . $querytoexecute . "<br>");
      $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
    }
  }
  $querytoexecute = "Truncate TABLE `temp_ranking`";
  $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());



  // billiards juniors
  // get existing total_rp from rank_Billiards before truncating

  $sum_total_rp = 'Select SUM(total_rp) as previous_total_rp from rank_B_junior';
  $result = mysql_query($sum_total_rp, $connvbsa) or die(mysql_error());
  $row_total = mysql_fetch_assoc($result);
  $total_previous  = $row_total['previous_total_rp'];
  echo("<br>Junior Previous Total " . $total_previous . "<br>");

  $sum_existing_total_rp_junior = "Select SUM(total_rp) as grand_total from (
  Select 
  rank_a_billiards_master.memb_id AS memb_id,
  Round((ROUND(tourn_2_j)) + 
  (ROUND(tourn_1_j)) + 
  tourn_curr_j + 
  brks_curr + 
  (ROUND(brks_2)) + 
  (ROUND(brks_1)) +
  (ROUND(scr_curr_S2)) + 
  (ROUND(scr_curr_S1)) + 
  (ROUND(scr_1yr_S1)) +
  (ROUND(scr_1yr_S2)) +
  (ROUND(scr_2yr_S1)) +
  (ROUND(scr_2yr_S2))
  ) 
  as total_rp
  FROM rank_a_billiards_master
  LEFT JOIN members T1 ON T1.MemberID = rank_a_billiards_master.memb_id 
  where Junior != 'na' and Gender != 'Female'
  GROUP BY rank_a_billiards_master.memb_id ) as subquery";
  //echo("Junior Billiards " . $sum_existing_total_rp_junior . "<br>");
  $result_junior = mysql_query($sum_existing_total_rp_junior, $connvbsa) or die(mysql_error());
  $row_total_junior = mysql_fetch_assoc($result_junior);
  $total_new  = $row_total_junior['grand_total'];
  echo("<br>Juniors New Total " . $row_total_junior['grand_total'] . "<br>");

  if($total_new != $total_previous)
  {
    $querytoexecute = "Truncate TABLE `temp_ranking`";
    $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

    $sql_copy = "Insert INTO temp_ranking SELECT * From rank_B_junior";
    $result = mysql_query($sql_copy, $connvbsa) or die(mysql_error());

    $querytoexecute = "Truncate TABLE `rank_B_junior`";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - rank_B_junior table - Billiards Rankings not truncated</font>");

    if (isset($result)) echo "<br><font face='arial' color='green'> 5. Truncated rank_B_junior table - Billiards Rankings successfully</font>";

    $querytoexecute = "Insert INTO `rank_B_junior`
    SELECT 
    0,
    0,
    rank_a_billiards_master.memb_id AS memb_id,
    Round((ROUND(tourn_2_j)) + 
    (ROUND(tourn_1_j)) + 
    tourn_curr_j + 
    brks_curr + 
    (ROUND(brks_2)) + 
    (ROUND(brks_1)) +
    (ROUND(scr_curr_S2)) + 
    (ROUND(scr_curr_S1)) + 
    (ROUND(scr_1yr_S1)) +
    (ROUND(scr_1yr_S2)) +
    (ROUND(scr_2yr_S1)) +
    (ROUND(scr_2yr_S2))
    ) 
    as total_rp,
    CURRENT_TIMESTAMP
    FROM rank_a_billiards_master
    LEFT JOIN members T1 ON T1.MemberID = rank_a_billiards_master.memb_id where (Junior != 'na') and Gender != 'Female'
    GROUP BY rank_a_billiards_master.memb_id
    ORDER BY total_rp DESC";
    //echo("Junior Billiards " . $querytoexecute . "<br>");
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>6. Error - rank_B_junior table - Billiard Rankings data not inserted</font>");

    if (isset($result)) echo "<br><br><font face='arial' color='green'> 6. Data inserted to rank_B_junior table - Billiard Rankings successfully</font><br>";

    $sql_exist = "Select * From temp_ranking Order By memb_id";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $querytoexecute = "Update rank_B_junior Set previous_rank = " . $row_exist['ranknum'] . " Where memb_id = " . $row_exist['memb_id'];
      //echo("Query " . $querytoexecute . "<br>");
      $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
    }
  }

  $querytoexecute = "Truncate TABLE `temp_ranking`";
  $result = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>End of Victorian Billiard Ranks";
  mysql_close ($connvbsa);
}
else
{

echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/Update_Scores_Rank_STEP4C.php?submit=1'>";
echo "<input type='submit' id='submit' name='submit'>";
echo "</form>";

}

?>
</center>
</body>
</html>
