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
  
  <table width="800" align="center">
    <tr>
      <td colspan="4" align="center"><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="header_red">&nbsp;</td>
    </tr>
    <tr>
      <td width="784" colspan="4" align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr>
      <td colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center">Please COMPLETE this sequence of pages every time you update scores</td>
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
      <td colspan="4" align="center">&nbsp;</td>
    </tr>
    
  </table>
  
  <center>
  
  <?php require_once('../Connections/connvbsa.php'); ?>
  <?php
  mysql_select_db($database_connvbsa, $connvbsa);

  echo "<br><br><font face='arial' color='green'>Testing Previous Ranking Points</font><br><br>";
  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Snooker Weekly, Open, Womens and Juniors previous rank tables</font><br>";

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Check if totals have changed (weekly).</font><br>";

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

  $total_new = 10;
  $total_previous = 13;
  
  if($total_new != $total_previous)
  {

    $querytoexecute = "Truncate TABLE temp_weekly_snooker_ranking";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_weekly_snooker_ranking not truncated</font>");
    if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_weekly_snooker_ranking table successfully</font>";

    

    echo '<br/>'.'<br/>';
    echo "<font face='arial'>Updating Rank tables</font><br>";
    echo '<br/>'.'<br/>';
    echo "<font face='arial'>Snooker Weekly Rank tables</font><br>";
    $rank_num = 1;
    $sql_exist = "Select * From rank_S_open_weekly Order By ranknum";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $sql_insert = "Insert into temp_weekly_snooker_ranking Set previous_rank = " . $rank_num . ", memb_id = " . $row_exist['memb_id'] . ", total_rp = " . $row_exist['total_weekly_rp'] . ", last_update = CurDate()";
      //echo($sql_insert . "<br>");
      $result=mysql_query($sql_insert, $connvbsa) or die("Table was not updated");
      $rank_num++;
    }
    echo("<font face='arial' color='green'>9. Weekly Snooker Ranking points inserted into temp table</font><br>");
    echo '<br/>';
  }



  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Check if totals have changed (open).</font><br>";

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
    $querytoexecute = "Truncate TABLE temp_open_snooker_ranking";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>4. Error - temp_open_snooker_ranking not truncated</font>");
    if (isset($result)) echo "<br><font face='arial' color='green'> 4. Truncated temp_open_snooker_ranking table successfully</font>";

    echo("<font face='arial' color='green'>8. Open Snooker Ranking points inserted into temp table</font><br>");
    echo '<br/>';
    echo "<font face='arial'>Snooker Open Rank tables</font><br>";
    $rank_num = 1;
    $sql_exist = "Select * From rank_S_open_tourn Order By ranknum";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $sql_insert = "Insert into temp_open_snooker_ranking Set previous_rank = " . $rank_num . ", memb_id = " . $row_exist['memb_id'] . ", total_rp = " . $row_exist['total_tourn_rp'] . ", last_update = CurDate()";
      //echo($sql_insert . "<br>");
      $result=mysql_query($sql_insert, $connvbsa) or die("Table was not updated");
      $rank_num++;
    }
    echo("<font face='arial' color='green'>10. Open Snooker Ranking points inserted into temp table</font><br>");
    echo '<br/>';
  }


  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Check if totals have changed (womens).</font><br>";

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
    $querytoexecute = "Truncate TABLE temp_womens_snooker_ranking";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error - temp_womens_snooker_ranking not truncated</font>");
    if (isset($result)) echo "<br><font face='arial' color='green'> 2. Truncated temp_womens_snooker_ranking table successfully</font>";

    echo("<font face='arial' color='green'>9. Open Snooker Ranking points inserted into temp table</font><br>");
    echo '<br/>';
    echo "<font face='arial'>Snooker Womens Rank tables</font><br>";
    $rank_num = 1;
    $sql_exist = "Select * From rank_S_womens Order By ranknum";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $sql_insert = "Insert into temp_womens_snooker_ranking Set previous_rank = " . $rank_num . ", memb_id = " . $row_exist['memb_id'] . ", total_rp = " . $row_exist['total_rp'] . ", last_update = CurDate()";
      //echo($sql_insert . "<br>");
      $result=mysql_query($sql_insert, $connvbsa) or die("Table was not updated");
      $rank_num++;
    }
    echo("<font face='arial' color='green'>10. Womens Snooker Ranking points inserted into temp table</font><br>");
    echo '<br/>';
  }

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Check if totals have changed (junior).</font><br>";

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
    $querytoexecute = "Truncate TABLE temp_junior_snooker_ranking";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error - temp_junior_snooker_ranking not truncated</font>");
    if (isset($result)) echo "<br><font face='arial' color='green'> 3. Truncated temp_junior_snooker_ranking table successfully</font>";

    echo("<font face='arial' color='green'>10. Womens Snooker Ranking points inserted into temp table</font><br>");
    echo '<br/>';
    echo "<font face='arial'>Snooker Junior Rank tables</font><br>";
    $rank_num = 1;
    $sql_exist = "Select * From rank_S_junior Order By ranknum";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $sql_insert = "Insert into temp_junior_snooker_ranking Set previous_rank = " . $rank_num . ", memb_id = " . $row_exist['memb_id'] . ", total_rp = " . $row_exist['total_rp'] . ", last_update = CurDate()";
      //echo($sql_insert . "<br>");
      $result=mysql_query($sql_insert, $connvbsa) or die("Table was not updated");
      $rank_num++;
    }
    echo("<font face='arial' color='green'>11. Junior Snooker Ranking points inserted into temp table</font><br>");
    echo '<br/>';
  }

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Check if totals have changed (open billiards).</font><br>";

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
  
    $querytoexecute = "Truncate TABLE temp_open_billiards_ranking";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - temp_open_billiards_ranking not truncated</font>");
    if (isset($result)) echo "<br><font face='arial' color='green'> 5. Truncated temp_open_billiards_ranking table successfully</font>";

    echo("<font face='arial' color='green'>11. Junior Snooker Ranking points inserted into temp table</font><br>");
    echo '<br/>';
    echo "<font face='arial'>Billiards Open Rank tables</font><br>";
    $rank_num = 1;
    $sql_exist = "Select * From rank_Billiards Order By ranknum";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $sql_insert = "Insert into temp_open_billiards_ranking Set previous_rank = " . $rank_num . ", memb_id = " . $row_exist['memb_id'] . ", total_rp = " . $row_exist['total_rp'] . ", last_update = CurDate()";
      //echo($sql_insert . "<br>");
      $result=mysql_query($sql_insert, $connvbsa) or die("Table was not updated");
      $rank_num++;
    }
    echo("<font face='arial' color='green'>12. Open Billiards Ranking points inserted into temp table</font><br>");
    echo '<br/>';
  }

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Check if totals have changed (womens billiards).</font><br>";

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
    $querytoexecute = "Truncate TABLE temp_womens_billiards_ranking";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>6. Error - temp_womens_billiards_ranking not truncated</font>");
    if (isset($result)) echo "<br><font face='arial' color='green'> 6. Truncated temp_womens_billiards_ranking table successfully</font>";

    echo("<font face='arial' color='green'>12. Open Billiards Ranking points inserted into temp table</font><br>");
    echo '<br/>';
    echo "<font face='arial'>Billiards Womens Rank tables</font><br>";
    $rank_num = 1;
    $sql_exist = "Select * From rank_B_womens Order By ranknum";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $sql_insert = "Insert into temp_womens_billiards_ranking Set previous_rank = " . $rank_num . ", memb_id = " . $row_exist['memb_id'] . ", total_rp = " . $row_exist['total_rp'] . ", last_update = CurDate()";
      //echo($sql_insert . "<br>");
      $result=mysql_query($sql_insert, $connvbsa) or die("Table was not updated");
      $rank_num++;
    }
    echo("<font face='arial' color='green'>13. Womens Billiards Ranking points inserted into temp table</font><br>");
    echo '<br/>';
  }

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Check if totals have changed (junior billiards).</font><br>";

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
    $querytoexecute = "Truncate TABLE temp_junior_billiards_ranking";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>7. Error - temp_junior_billiards_ranking not truncated</font>");
    if (isset($result)) echo "<br><font face='arial' color='green'> 7. Truncated temp_junior_billiards_ranking table successfully</font>";

    echo("<font face='arial' color='green'>13. Womens Billiards Ranking points inserted into temp table</font><br>");
    echo '<br/>';
    echo "<font face='arial'>Billiards Junior Rank tables</font><br>";
    $rank_num = 1;
    $sql_exist = "Select * From rank_B_junior Order By ranknum";
    $result_exist = mysql_query($sql_exist, $connvbsa) or die(mysql_error());
    while($row_exist = mysql_fetch_assoc($result_exist))
    {
      $sql_insert = "Insert into temp_junior_billiards_ranking Set previous_rank = " . $rank_num . ", memb_id = " . $row_exist['memb_id'] . ", total_rp = " . $row_exist['total_rp'] . ", last_update = CurDate()";
      //echo($sql_insert . "<br>");
      $result=mysql_query($sql_insert, $connvbsa) or die("Table was not updated");
      $rank_num++;
    }
    echo("<font face='arial' color='green'>14. Junior Billiards Ranking points inserted into temp table</font><br>");
    echo '<br/>';
  }



  