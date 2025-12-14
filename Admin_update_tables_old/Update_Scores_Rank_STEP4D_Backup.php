<?php require_once('../Connections/connvbsa.php'); 
require_once('../MailerLite/mailerlite_functions.php'); 

error_reporting(0);
?>
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
      <td colspan="4" align="center">STEP 4D of the calculation process</td>
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
<?php
mysql_select_db($database_connvbsa, $connvbsa);

if(isset($_POST["submit"]))
{
  echo("<center>");
  echo '<br/>'.'<br/>';
  echo "<font face='arial' size='3'>Calculations completed ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP5.php">'. "Thank you". '</a></spsn>';
  echo '<br/><br/>';

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Update MalierLite Reports</font><br>";
  echo '<br/>';
  /*
  echo("<font face='arial' color='green'>1. Update Players with Captains S1 group</font><br>");

  $groupName = "Captains S1";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select Email FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE captain_scrs=1 AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S1' AND (ReceiveEmail = 1 AND Email != '')";
  //echo($query_audience . "<br>");
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  while($build_data = $result->fetch_assoc())
  {
      $email = $build_data['Email'];
      AddPlayerToGroup($email, $new_groupId);
  }

  echo("<font face='arial' color='green'>2. Players added to Captains S1 group</font><br>");

  sleep(60);
  */
  echo("<font face='arial' color='green'>3. Update Players with Captains S1 group</font><br>");

  $groupName = "Captains S1";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select Email FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE captain_scrs=1 AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S1' AND (ReceiveEmail = 1 AND Email != '')";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select * FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE captain_scrs=1 AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S1' AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET " . $offset;
      $result_1 = mysql_query($query_audience_1, $connvbsa) or die(mysql_error());
      while($row_audience_1 = $result_1->fetch_assoc())
      {
        $email = $row_audience_1['Email'];
        $first_name = $row_audience_1['FirstName'];
        $last_name = $row_audience_1['LastName'];
        $member_id = $row_audience_1['MemberID'];
        $data_1['requests'][] = [
                "method" => "POST",
                "path" => "api/subscribers",
                'body' => [
                    'email' => $email,
                    'status' => 'active', // or 'unsubscribed'
                    'fields' => [
                        'memberid' => $member_id,
                        'name' => $first_name,
                        'last_name' => $last_name
                    ],
                    'groups' => [ $new_groupId ]
                ],
            ];
        }
        $response = $mailerLite->batches->send($data_1);
        $y++;
        echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>2. Players added to Captains S1 group</font><br>");

  echo("<font face='arial' color='green'>3. Update Players with Captains S2 group</font><br>");

  $groupName = "Captains S2";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select Email FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE captain_scrs=1 AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S2' AND (ReceiveEmail = 1 AND Email != '')";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select * FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE captain_scrs=1 AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S2' AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET " . $offset;
      $result_1 = mysql_query($query_audience_1, $connvbsa) or die(mysql_error());
      while($row_audience_1 = $result_1->fetch_assoc())
      {
        $email = $row_audience_1['Email'];
        $first_name = $row_audience_1['FirstName'];
        $last_name = $row_audience_1['LastName'];
        $member_id = $row_audience_1['MemberID'];
        $data_1['requests'][] = [
                "method" => "POST",
                "path" => "api/subscribers",
                'body' => [
                    'email' => $email,
                    'status' => 'active', // or 'unsubscribed'
                    'fields' => [
                        'memberid' => $member_id,
                        'name' => $first_name,
                        'last_name' => $last_name
                    ],
                    'groups' => [ $new_groupId ]
                ],
            ];
        }
        $response = $mailerLite->batches->send($data_1);
        $y++;
        echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }

/*


  $query_audience_1 = "Select Email FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE captain_scrs=1 AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S2' AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET 0";
  $result_1 = mysql_query($query_audience_1, $connvbsa) or die(mysql_error());
  while($row_audience_1 = $result_1->fetch_assoc())
  {
  //  $email_1 = $build_data_1['Email'];
  //  AddPlayerToGroup($email_1, $new_groupId);
  //}
    $email = $row_audience_1['Email'];
    $first_name = $row_audience_1['FirstName'];
    $last_name = $row_audience_1['LastName'];
    $member_id = $row_audience_1['MemberID'];
    
    $data_1['requests'][] = [
            "method" => "POST",
            "path" => "api/subscribers",
            'body' => [
                'email' => $email,
                'status' => 'active', // or 'unsubscribed'
                'fields' => [
                    'memberid' => $member_id,
                    'name' => $first_name,
                    'last_name' => $last_name
                ],
                'groups' => [ $new_groupId ]
            ],
        ];
  }

  //echo("<pre>");
  //echo(var_dump($data));
  //echo("</pre>");

  $response = $mailerLite->batches->send($data_1);
  echo("Session 1 Complete<br>");
  //$response = $mailerLite->batch->create(['request' => $data]);
  //echo("<pre>");
  //echo(var_dump($response));
  //echo("</pre>");
  //sleep(10);
 
  $query_audience_2 = "Select Email FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE captain_scrs=1 AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S2' AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET 30";
  $result_2 = mysql_query($query_audience_2, $connvbsa) or die(mysql_error());
  while($row_audience_2 = $result_2->fetch_assoc())
  {
    //  $email_1 = $build_data_1['Email'];
    //  AddPlayerToGroup($email_1, $new_groupId);
    //}
      $email = $row_audience_2['Email'];
      $first_name = $row_audience_2['FirstName'];
      $last_name = $row_audience_2['LastName'];
      $member_id = $row_audience_2['MemberID'];
      
      $data_2['requests'][] = [
            "method" => "POST",
            "path" => "api/subscribers",
            'body' => [
                'email' => $email,
                'status' => 'active', // or 'unsubscribed'
                'fields' => [
                    'memberid' => $member_id,
                    'name' => $first_name,
                    'last_name' => $last_name
                ],
                'groups' => [ $new_groupId ]
            ],
        ];
  }
  $response = $mailerLite->batches->send($data_2);
  echo("Session 2 Complete<br>");
  //sleep(10);
 
  $query_audience_3 = "Select Email FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE captain_scrs=1 AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S2' AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET 60";
  $result_3 = mysql_query($query_audience_3, $connvbsa) or die(mysql_error());
  while($row_audience_3 = $result_3->fetch_assoc())
  {
    //$email_3 = $build_data_3['Email'];
    //AddPlayerToGroup($email_3, $new_groupId);
  //}
      $email = $row_audience_3['Email'];
      $first_name = $row_audience_3['FirstName'];
      $last_name = $row_audience_3['LastName'];
      $member_id = $row_audience_3['MemberID'];
      
      $data_3['requests'][] = [
            "method" => "POST",
            "path" => "api/subscribers",
            'body' => [
                'email' => $email,
                'status' => 'active', // or 'unsubscribed'
                'fields' => [
                    'memberid' => $member_id,
                    'name' => $first_name,
                    'last_name' => $last_name
                ],
                'groups' => [ $new_groupId ]
            ],
        ];
  }
  $response = $mailerLite->batches->send($data_3);
  //sleep(10);
  echo("Session 3 Complete<br>");

/*
  $query_audience_4 = "Select Email FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE captain_scrs=1 AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S2' AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET 90";
  $result_4 = mysql_query($query_audience_4, $connvbsa) or die(mysql_error());
  while($row_audience_4 = $result_4->fetch_assoc())
  {
    //$email_3 = $build_data_3['Email'];
    //AddPlayerToGroup($email_3, $new_groupId);
  //}
      $email = $row_audience_4['Email'];
      $first_name = $row_audience_4['FirstName'];
      $last_name = $row_audience_4['LastName'];
      $member_id = $row_audience_4['MemberID'];
      
      $data_4['requests'][] = [
            "method" => "POST",
            "path" => "api/subscribers",
            'body' => [
                'email' => $email,
                'status' => 'active', // or 'unsubscribed'
                'fields' => [
                    'memberid' => $member_id,
                    'name' => $first_name,
                    'last_name' => $last_name
                ],
                'groups' => [ $new_groupId ]
            ],
        ];
  }
  $response = $mailerLite->batches->send($data_4);
  ///sleep(10);
  echo("Session 4 Complete<br>");

  $query_audience_5 = "Select Email FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE captain_scrs=1 AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S2' AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET 120";
  $result_5 = mysql_query($query_audience_5, $connvbsa) or die(mysql_error());
  while($row_audience_5 = $result_5->fetch_assoc())
  {
    //$email_3 = $build_data_3['Email'];
    //AddPlayerToGroup($email_3, $new_groupId);
  //}
      $email = $row_audience_5['Email'];
      $first_name = $row_audience_5['FirstName'];
      $last_name = $row_audience_5['LastName'];
      $member_id = $row_audience_5['MemberID'];
      
      $data_5['requests'][] = [
            "method" => "POST",
            "path" => "api/subscribers",
            'body' => [
                'email' => $email,
                'status' => 'active', // or 'unsubscribed'
                'fields' => [
                    'memberid' => $member_id,
                    'name' => $first_name,
                    'last_name' => $last_name
                ],
                'groups' => [ $new_groupId ]
            ],
        ];
  }
  $response = $mailerLite->batches->send($data_5);
  echo("Session 5 Complete<br>");
*/

  echo("<font face='arial' color='green'>4. Players added to Captains S2 group</font><br>");
  echo("<pre>");
  //echo(var_dump($response));
  echo("</pre>");
/*

  sleep(60);

  echo("<font face='arial' color='green'>5. Update Players with Players S1 group</font><br>");
  $groupName = "Players S1";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName,  MobilePhone, Email, ReceiveEmail, current_year_scrs, SUM(count_played) AS played, current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scr_season='S1'  AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberId != 1 AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  while($build_data = $result->fetch_assoc())
  {
    $email = $build_data['Email'];
    AddPlayerToGroup($email, $new_groupId);
  }
  echo("<font face='arial' color='green'>6. Players added to Players S1 group</font><br>");

  sleep(60);

  echo("<font face='arial' color='green'>7. Update Players with Players S2 group</font><br>");
  $groupName = "Players S2";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName,  MobilePhone, Email, ReceiveEmail, current_year_scrs, SUM(count_played) AS played, current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scr_season='S2'  AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberId != 1 AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  while($build_data = $result->fetch_assoc())
  {
    $email = $build_data['Email'];
    AddPlayerToGroup($email, $new_groupId);
  }
  echo("<font face='arial' color='green'>8. Players added to Players S2 group</font><br>");

  echo("<font face='arial' color='green'>9. Update Players with Playing Billiards group</font><br>");
  $groupName = "Playing Billiards";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, SUM(count_played) AS TotPlayed, game_type FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE game_type='Billiards' AND current_year_scrs = YEAR( CURDATE( ) ) AND (scrs.MemberID!=1 AND scrs.MemberID!=100 AND scrs.MemberID!=1000) GROUP BY scrs.MemberID ORDER BY LastName, FirstName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  while($build_data = $result->fetch_assoc())
  {
    $email = $build_data['Email'];
    AddPlayerToGroup($email, $new_groupId);
  }
  echo("<font face='arial' color='green'>10. Players added to Playing Billiards group</font><br>");

  echo("<font face='arial' color='green'>11. Update Players with Playing Snooker group</font><br>");
  $groupName = "Playing Snooker";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, SUM(count_played) AS TotPlayed, game_type FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE game_type='Snooker' AND current_year_scrs = YEAR( CURDATE( ) ) AND (scrs.MemberID!=1 AND scrs.MemberID!=100 AND scrs.MemberID!=1000) GROUP BY scrs.MemberID ORDER BY LastName, FirstName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  while($build_data = $result->fetch_assoc())
  {
    $email = $build_data['Email'];
    AddPlayerToGroup($email, $new_groupId);
  }
  echo("<font face='arial' color='green'>12. Players added to Playing Snooker group</font><br>");


  echo("<font face='arial' color='green'>13. Update Players with Qualified Finals S1 group</font><br>");
  $groupName = "Qualified Finals S1";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, scrs.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE count_played>3 AND scr_season = 'S1' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 HAVING audited='Yes' ORDER BY team_grade, team_id, FirstName, LastName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  while($build_data = $result->fetch_assoc())
  {
    $email = $build_data['Email'];
    AddPlayerToGroup($email, $new_groupId);
  }
  echo("<font face='arial' color='green'>14. Players added to Qualified Finals S1 group</font><br>");


  echo("<font face='arial' color='green'>15. Update Players with Qualified Finals S2 group</font><br>");
  $groupName = "Qualified Finals S2";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, scrs.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE count_played>3 AND scr_season = 'S2' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 HAVING audited='Yes' ORDER BY team_grade, team_id, FirstName, LastName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  while($build_data = $result->fetch_assoc())
  {
    $email = $build_data['Email'];
    AddPlayerToGroup($email, $new_groupId);
  }
  echo("<font face='arial' color='green'>16. Players added to Qualified Finals S2 group</font><br>");

  echo("<font face='arial' color='green'>17. Update Players with All Playing group</font><br>");
  $groupName = "All Playing";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName, MobilePhone, Email, SUM(count_played), current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE (current_year_scrs = YEAR(CURDATE( )) OR current_year_scrs = YEAR(CURDATE( ))-1)  AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID HAVING SUM(count_played)>0 ORDER BY LastName, FirstName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  while($build_data = $result->fetch_assoc())
  {
    $email = $build_data['Email'];
    AddPlayerToGroup($email, $new_groupId);
  }
  echo("<font face='arial' color='green'>18. Players added to All Playing group</font><br>");
*/
  echo '<br/>'.'<br/>';
  echo "<font face='arial'>End of MalierLite Reports";
  mysql_close ($connvbsa);
}
else
{
  echo '<center/>';
  echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/Update_Scores_Rank_STEP4D.php?submit=1'>";
  echo "<input type='submit' id='submit' name='submit'>";
  echo "</form>";
}

?>
</center>
</body>
</html>
