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
  <form name='progress'>
  <table align="center">
    <tr>
      <td><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="header_red">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center">STEP 1 of the external mailing list calculation process</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="red_text">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="red_text">WARNING - THESE CALCULATIONS TAKE A COUPLE OF MINUTES TO COMPLETE, PLEASE WAIT UNTIL PAGE HAS STOPPED RUNNING IN YOUR BROWSER</td>
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
    <tr>
      <td colspan="4" align="center" class="red_text">If 'End of MalierLite Reports' does not appear when caculations are complete, please reload the page.</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="red_text">&nbsp;</td>
    </tr>
  </table>
</form>
<?php
mysql_select_db($database_connvbsa, $connvbsa);

if(isset($_POST["submit"]))
{
  echo("<center>");
  echo "<font face='arial'>STEP 1 completed go to ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP4E.php">'. "STEP 2". '</a></span>';
  
  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Update MalierLite Reports</font><br>";
  echo '<br/>';
 
  echo("<font face='arial' color='green'>1. Update Players with Captains S1 group</font><br>");
 
  $groupName = "Captains S1";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select Email FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE (captain_scrs=1 OR authoriser_scrs=1) AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S1' AND (ReceiveEmail = 1 AND Email != '')";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select * FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE (captain_scrs=1 OR authoriser_scrs=1) AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S1' AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET " . $offset;
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
        //echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>2. Players added to Captains S1 group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>3. Update Players with Captains S2 group</font><br>");
 
  $groupName = "Captains S2";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select Email FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE (captain_scrs=1 OR authoriser_scrs=1) AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S2' AND (ReceiveEmail = 1 AND Email != '')";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select * FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID WHERE (captain_scrs=1 OR authoriser_scrs=1) AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='S2' AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET " . $offset;
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
        //echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>4. Players added to Captains S2 group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>5. Update Players with Playing S1 group</font><br>");

  $groupName = "Playing S1";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select Email FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scr_season='S1'  AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberId != 1 AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName,  MobilePhone, Email, ReceiveEmail, current_year_scrs, SUM(count_played) AS played, current_year_scrs FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scr_season='S1'  AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberId != 1 AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID LIMIT 30 OFFSET " . $offset;
      //echo($query_audience_1 . "<br>");
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
        //echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>6. Players added to Playing S1 group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>7. Update Players with Playing S2 group</font><br>");

  $groupName = "Playing S2";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select Email FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scr_season='S2'  AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberId != 1 AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName,  MobilePhone, Email, ReceiveEmail, current_year_scrs, SUM(count_played) AS played, current_year_scrs FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scr_season='S2'  AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberId != 1 AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID LIMIT 30 OFFSET " . $offset;
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
        //echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>8. Players added to Playing S2 group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>9. Update Players with Playing Billiards group</font><br>");

  $groupName = "Playing Billiards";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select Email FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE game_type='Billiards' AND current_year_scrs = YEAR( CURDATE( ) ) AND (scrs.MemberID!=1 AND scrs.MemberID!=100 AND scrs.MemberID!=1000) AND (ReceiveEmail = 1 AND Email != '') GROUP BY scrs.MemberID ORDER BY LastName, FirstName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, SUM(count_played) AS TotPlayed, game_type FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE game_type='Billiards' AND current_year_scrs = YEAR( CURDATE( ) ) AND (scrs.MemberID!=1 AND scrs.MemberID!=100 AND scrs.MemberID!=1000) AND (ReceiveEmail = 1 AND Email != '') GROUP BY scrs.MemberID ORDER BY LastName, FirstName LIMIT 30 OFFSET " . $offset;
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
        //echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>10. Players added to Playing Billiards group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>11. Update Players with Playing Snooker group</font><br>");

  $groupName = "Playing Snooker";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select Email FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE game_type='Snooker' AND current_year_scrs = YEAR( CURDATE( ) ) AND (scrs.MemberID!=1 AND scrs.MemberID!=100 AND scrs.MemberID!=1000) AND (ReceiveEmail = 1 AND Email != '') GROUP BY scrs.MemberID ORDER BY LastName, FirstName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, SUM(count_played) AS TotPlayed, game_type FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE game_type='Snooker' AND current_year_scrs = YEAR( CURDATE( ) ) AND (scrs.MemberID!=1 AND scrs.MemberID!=100 AND scrs.MemberID!=1000) AND (ReceiveEmail = 1 AND Email != '') GROUP BY scrs.MemberID ORDER BY LastName, FirstName LIMIT 30 OFFSET " . $offset;
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
        //echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>12. Players added to Playing Snooker group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>13. Update Players with Qualified Finals S1 group</font><br>");

  $groupName = "Qualified Finals S1";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, scrs.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE count_played>3 AND scr_season = 'S1' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 HAVING audited='Yes' ORDER BY team_grade, team_id, FirstName, LastName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, scrs.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE count_played>3 AND scr_season = 'S1' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 HAVING audited='Yes' ORDER BY team_grade, team_id, FirstName, LastName LIMIT 30 OFFSET " . $offset;
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
        //echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>14. Players added to Qualified Finals S1 group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>15. Update Players with Qualified Finals S2 group</font><br>");

  $groupName = "Qualified Finals S2";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, scrs.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE count_played>3 AND scr_season = 'S2' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 HAVING audited='Yes' ORDER BY team_grade, team_id, FirstName, LastName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, scrs.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE count_played>3 AND scr_season = 'S2' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 HAVING audited='Yes' ORDER BY team_grade, team_id, FirstName, LastName LIMIT 30 OFFSET " . $offset;
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
        //echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>16. Players added to Qualified Finals S2 group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>17. Update Players with All Playing group</font><br>");

  $groupName = "All Playing";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName, MobilePhone, Email, SUM(count_played), current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE (current_year_scrs = YEAR(CURDATE( )) OR current_year_scrs = YEAR(CURDATE( ))-1)  AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID HAVING SUM(count_played)>0 ORDER BY LastName, FirstName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName, MobilePhone, Email, SUM(count_played), current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE (current_year_scrs = YEAR(CURDATE( )) OR current_year_scrs = YEAR(CURDATE( ))-1)  AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID HAVING SUM(count_played)>0 ORDER BY LastName, FirstName LIMIT 30 OFFSET " . $offset;
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
        //echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>18. Players added to All Playing group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>19. Update Players with Players Bulk Email group</font><br>");

  $groupName = "Players Bulk Email";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName, MobilePhone, Email, SUM(count_played), current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE (current_year_scrs = YEAR(CURDATE( )) OR current_year_scrs = YEAR(CURDATE( ))-1)  AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID HAVING SUM(count_played)>0 ORDER BY LastName, FirstName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName, MobilePhone, Email, SUM(count_played), current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE (current_year_scrs = YEAR(CURDATE( )) OR current_year_scrs = YEAR(CURDATE( ))-1)  AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID HAVING SUM(count_played)>0 ORDER BY LastName, FirstName LIMIT 30 OFFSET " . $offset;
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
        //echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>20. Players added to Players Bulk Email group</font><br>");
  echo '<br/>';
  /*
  echo("<font face='arial' color='green'>21. Update Players with All Records with Email group</font><br>");

  $groupName = "All Emails";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, memb_by, curr_memb FROM members WHERE (ReceiveEmail != 0 and MemberID != 1 AND MemberID != 100 AND MemberID != 500 AND MemberID != 1000 AND Deceased !=1 AND Email != '') ORDER BY LastName, FirstName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, memb_by, curr_memb FROM members WHERE (ReceiveEmail != 0 and MemberID != 1 AND MemberID != 100 AND MemberID != 500 AND MemberID != 1000 AND Deceased !=1 AND Email != '') ORDER BY LastName, FirstName LIMIT 30 OFFSET " . $offset;
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
        //echo("Session " . $y . " Complete<br>");
        $offset = ($offset+30);
        $data_1 = [];
     }
  }
  echo("<font face='arial' color='green'>22. Players added to All Records with Email group</font><br>");
  */
  
  echo '<br/>'.'<br/>';
  echo "<font face='arial'>End of MalierLite Reports";

  //echo '<br/>'.'<br/>';
  //echo "<font face='arial' size='3'>Calculations completed ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP5.php">'. "Thank you". '</a></spsn>';
  //echo '<br/><br/>';

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
