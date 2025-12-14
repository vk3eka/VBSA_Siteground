<?php require_once('../Connections/connvbsa.php'); 

$season = $_GET['season'];

error_reporting(0);

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
      <td colspan="4" align="center">STEP 4B of the calculation process</td>
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
<?php

if(isset($_POST["submit"]))
{
  echo "<font face='arial'>STEP 4B completed go to ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP4C.php?season=' . $season . '">STEP 4C</a></span>';
  echo '<br/><br/>';
  echo "<br><br><font face='arial' color='green'>Update Billiard ranking points (Tiers)</font>";

  //$year = 2025;
  //$current_season = 'S1';

  $year = date('Y');
   $month = date('m');
  if($month < '08')
  {
    $current_season = 'S1';
  }
  else
  {
    $current_season = 'S2';
  }

  //function BilliardRP($year, $current_season)
  //{
  //global $database_connvbsa;
  //global $connvbsa;
  mysql_select_db($database_connvbsa, $connvbsa);
  // check that team grades has tier ranking points entered
  $querytoexecute = "Select tier_2_rp, tier_1_rp, tier0_rp, tier1_rp, tier2_rp, tier3_rp, tier4_rp, tier5_rp, tier6_rp, tier7_rp, tier8_rp, tier9_rp, tier10_rp, tier11_rp, tier12_rp from `vbsa3364_vbsa2`.`Team_grade` where type = 'Billiards' and fix_cal_year = $year and season = '$current_season' and tier1_rp != 0";
  // added tier_rp 7 to 12
  // added tier_rp -2 to 0
  //$querytoexecute = "Select tier1_rp, tier2_rp, tier3_rp, tier4_rp, tier5_rp, tier6_rp from `vbsa3364_vbsa2`.`tbl_team_grade` where type = 'Billiards' and fix_cal_year = $year and season = '$current_season' and tier1_rp != 0";
  //echo($querytoexecute . "<br>");
  //mysql_select_db($database_connvbsa, $connvbsa);
  $Tier = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
  $TierAll = mysql_fetch_assoc($Tier);

  $querytoexecute = "Select * from `vbsa3364_vbsa2`.`scrs` where game_type = 'Billiards' and scr_season = '$current_season' and current_year_scrs = $year and MemberID != 1 and MemberID != 100 and MemberID != 1000 Order By MemberID";
  //echo($querytoexecute . "<br>");
  //mysql_select_db($database_connvbsa, $connvbsa);
  $RPall = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
  $row_RPall = mysql_fetch_assoc($RPall);
  $totalRows_RPall = mysql_num_rows($RPall);

  while ($row_RPall = mysql_fetch_assoc($RPall))
  { 
    $querytoexecute = "Select * from `vbsa3364_vbsa2`.`scrs` where MemberID = " . $row_RPall['MemberID'] . " and game_type = 'Billiards' and scr_season = '$current_season' and current_year_scrs = $year and MemberID != 1 and MemberID != 100 and MemberID != 1000";
    //echo($querytoexecute . "<br>");
    //mysql_select_db($database_connvbsa, $connvbsa);
    $RPindividual = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
    $row_RPindividual = mysql_fetch_assoc($RPindividual);
    $totalRows_RPindividual = mysql_num_rows($RPindividual);

    $Array = [];
    for($i = 0; $i < 18; $i++)
    {
      // format round number
      if($i > 8)
      {
          $rnd_no = ($i+1);
      }
      else
      {
          $rnd_no = '0' . ($i+1);
      }
      if($row_RPindividual['r' . $rnd_no . "s"] > 0)
      {
        $Array[$i] = $row_RPindividual['tier_r' . $rnd_no . '_rp'] . "" . $Array[$i];
      }
    }
    //echo("<pre>");
    //echo(var_dump($Array));
    //echo("</pre>");

    $result = array_count_values($Array);
    $total_rp = 
      (($result[$TierAll['tier1_rp']]*$TierAll['tier1_rp'])
      + 
      ($result[$TierAll['tier3_rp']]*$TierAll['tier3_rp'])
      + 
      ($result[$TierAll['tier4_rp']]*$TierAll['tier4_rp'])
      +
      ($result[$TierAll['tier1_rp']/2]*$TierAll['tier1_rp']/2)
      + 
      ($result[$TierAll['tier3_rp']/2]*$TierAll['tier3_rp']/2)
      + 
      ($result[$TierAll['tier4_rp']/2]*$TierAll['tier4_rp']/2));
    if($row_RPindividual['scrsID'] > 0)
    {
      $sql_tiers_rp = "Update scrs set tier_rp = " . $total_rp . " Where scrsID = " . $row_RPindividual['scrsID'];
      //echo($sql_tiers_rp . "<br>");
      $update = mysql_query($sql_tiers_rp, $connvbsa); 
      //if(!$update )
      //{
      //    die("Could not update tier data: " . mysqli_error($dbcnx_client));
      //}
    }
    $tier_rp = '';
    for($i = 0; $i < 18; $i++)
    {
      // format round number
      if($i > 8)
      {
          $rnd_no = ($i+1);
      }
      else
      {
          $rnd_no = '0' . ($i+1);
      }
      $tier_rp = $row_RPindividual['tier_r' . $rnd_no . '_rp'] . "+" . $tier_rp;
    }
    $tier_rp = $tier_rp . "0";

    $query = "Update scrs set total_RP = (Select SUM(" . $tier_rp . ") as total_RP) WHERE MemberID=" . $row_RPall['MemberID'] . " AND current_year_scrs = YEAR(CURDATE( ))  AND game_type='Billiards' AND scr_season='$current_season'";
    //echo($query . "<br>");
    $update = mysql_query($query, $connvbsa); 
  }
      //echo("<br>" . $year . " Tier Data Saved.");
    //}

  //BilliardRP(2024, 'S1');

    //echo("Here<br>");
    //BilliardRP(2024, 'S1');

    /*echo("<center>");
    echo '<br/>'.'<br/>';
    echo "<font face='arial'>Test Victorian Billiard Rankings</font><br>";
    echo '<br/>'.'<br/>';
    echo '<br/>'.'<br/>';
    echo("</center>");*/

    //BilliardRP(2023, 'S2');
  /*
  Update `vbsa3364_vbsa2`.`scrs` SET total_RP =
  (Select Case
  WHEN game_type = 'Snooker' THEN allocated_rp*pts_won
  WHEN game_type = 'Billiards' AND scr_season = 'S1' AND current_year_scrs <= 2023 THEN allocated_rp*(pts_won/2)
  ELSE 0
  END)
  WHERE current_year_scrs = YEAR(CURDATE( )) -1";
  */

    //$query_ = "Update scrs set total_RP (Select SUM(tier1_rp+tier2_rp+tier3_rp+tier4_rp+tier5_rp+tier6_rp) as total_RP) WHERE MemberID=398 AND current_year_scrs = YEAR(CURDATE( ))-2  AND game_type='Billiards' AND scr_season='S2' GROUP BY scrsID";


  /*
  function BilliardRP($year, $current_season)
  {

    // check that team grades has tier ranking points entered
    $querytoexecute = "Select tier1_rp, tier2_rp, tier3_rp, tier4_rp, tier5_rp, tier6_rp from `vbsa3364_vbsa2`.`tbl_team_grade` where type = 'Billiards' and fix_cal_year = 2023 and season = 'S2' and tier1_rp != 0";
    mysql_select_db($database_connvbsa, $connvbsa);
    $Tier = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
    $TierAll = mysql_fetch_assoc($Tier);
    
    $querytoexecute = "Select * from `vbsa3364_vbsa2`.`scrs` where game_type = 'Billiards' and scr_season = 'S2' and current_year_scrs = 2023 Order By MemberID";
    mysql_select_db($database_connvbsa, $connvbsa);
    $RPall = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
    $row_RPall = mysql_fetch_assoc($RPall);
    $totalRows_RPall = mysql_num_rows($RPall);

    while ($row_RPall = mysql_fetch_assoc($RPall))
    { 
      $querytoexecute = "Select * from `vbsa3364_vbsa2`.`scrs` where MemberID = " . $row_RPall['MemberID'] . " and game_type = 'Billiards' and scr_season = 'S2' and current_year_scrs = 2023 and MemberID != 1 and MemberID != 100 and MemberID != 1000";
      mysql_select_db($database_connvbsa, $connvbsa);
      $RPindividual = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
      $row_RPindividual = mysql_fetch_assoc($RPindividual);
      $totalRows_RPindividual = mysql_num_rows($RPindividual);

      $Array = [];
      for($i = 0; $i < 18; $i++)
      {
         // format round number
        if($i > 8)
        {
            $rnd_no = ($i+1);
        }
        else
        {
            $rnd_no = '0' . ($i+1);
        }
        if($row_RPindividual['r' . $rnd_no . "s"] > 0)
        {
          $Array[$i] = $row_RPindividual['tier_r' . $rnd_no . '_rp'] . "" . $Array[$i];
        }
      }
      $result = array_count_values($Array);
      $total_rp = 
        (($result[$TierAll['tier1_rp']]*$TierAll['tier1_rp'])
        + 
        ($result[$TierAll['tier3_rp']]*$TierAll['tier3_rp'])
        + 
        ($result[$TierAll['tier4_rp']]*$TierAll['tier4_rp'])
        +
        ($result[$TierAll['tier1_rp']/2]*$TierAll['tier1_rp']/2)
        + 
        ($result[$TierAll['tier3_rp']/2]*$TierAll['tier3_rp']/2)
        + 
        ($result[$TierAll['tier4_rp']/2]*$TierAll['tier4_rp']/2));
      if($row_RPindividual['scrsID'] > 0)
      {
        echo ("Update scrs set tier_rp = " . $total_rp . " Where scrsID = " . $row_RPindividual['scrsID'] . ". Member ID = " . $row_RPindividual['MemberID'] . "<br>");
      }
      
    }
  }
  */
  echo "<br><br><font face='arial' color='green'>Tiers for Billiard Rankings Updated</font>";
  mysql_close ($connvbsa);
}
else
{
  echo '<center/>';
  echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/Update_Scores_Rank_STEP4B.php?submit=1&season=" . $season . "'>";
  echo "<input type='submit' id='submit' name='submit'>";
  echo "</form>";
}
mysql_close ($connvbsa);
?>
</center>
</body>
</html>
<?php
?>