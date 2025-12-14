<?php 
require_once('../Connections/connvbsa.php'); 
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
      <td colspan="4" align="center">STEP 2 of the calculation process</td>
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
      <td colspan="4" align="center" class="red_text">If 'End of MalierLite Member Reports' does not appear when caculations are complete, please reload the page.</td>
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
  //echo '<br/>'.'<br/>';
  //echo "<font face='arial' size='3'>Calculations completed ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP5.php">'. "Thank you". '</a></spsn>';
  //echo '<br/><br/>';

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Update MalierLite Member Reports</font><br>";
  echo '<br/>';
 
  echo("<font face='arial' color='green'>1. Update all Members and Affiliates group</font><br>");

  $groupName = "Members/Affiliates";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select MemberID, FirstName, LastName, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, hon_memb, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR affiliate_player=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR affiliate_player = 1 OR hon_memb = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '')";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select MemberID, FirstName, LastName, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, hon_memb, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR affiliate_player=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR affiliate_player = 1 OR hon_memb = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>2. Members added to all Members and Affiliates group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>3. Update all Current Members group</font><br>");

  $groupName = "Current Members";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, hon_memb FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR hon_memb=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '') ";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, hon_memb FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR hon_memb=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>4. Members added to all Current Members group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>5. Update all Affiliate Members group</font><br>");

  $groupName = "Affiliates";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, LifeMember, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members Where affiliate_player = 1 and ReceiveEmail = 1 and Email != ''";
  //echo($query_audience . "<br>");
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, LifeMember, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members Where affiliate_player = 1 and ReceiveEmail = 1 and Email != '' LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>6. Members added to all Affiliate Members group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>7. Update Members with Life group</font><br>");

  $groupName = "Life";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND ReceiveEmail = 1 AND Email != '' AND LifeMember = 1";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND ReceiveEmail = 1 AND Email != '' AND LifeMember = 1 LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>8. Members added to Life group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>9. Update Members with Paid group</font><br>");

  $groupName = "Paid";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, paid_date, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND ReceiveEmail = 1 AND Email != '' AND (paid_memb = 20 AND YEAR(paid_date) = YEAR(NOW()))";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, paid_date, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND ReceiveEmail = 1 AND Email != '' AND (paid_memb = 20 AND YEAR(paid_date) = YEAR(NOW())) LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>10. Members added to Paid group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>11. Update Members with CCC group</font><br>");

  $groupName = "CCC";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND ReceiveEmail = 1 AND Email != '' AND ccc_player = 1";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND ReceiveEmail = 1 AND Email != '' AND ccc_player = 1 LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>12. Members added to CCC group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>13. Update Members with Female/NB/NS group</font><br>");

  $groupName = "Female/NB/NS";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, HomeState, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (Gender != 'Male') AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '')";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select MemberID, FirstName, LastName, MobilePhone, Email, HomeState, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (Gender != 'Male') AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>14. Members added to Female/NB/NS group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>15. Update Members with Juniors group</font><br>");

  $groupName = "Juniors";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select MemberID, LastName, FirstName, HomePhone, MobilePhone, Email, ReceiveEmail, Junior, dob_day, dob_mnth, dob_year, Homestate FROM members WHERE Junior != 'na' AND ReceiveEmail = 1 AND Email != '' ORDER BY LastName";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select MemberID, LastName, FirstName, HomePhone, MobilePhone, Email, ReceiveEmail, Junior, dob_day, dob_mnth, dob_year, Homestate FROM members WHERE Junior != 'na' AND ReceiveEmail = 1 AND Email != '' ORDER BY LastName LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>16. Members added to Juniors group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>17. Update Members with Referees group</font><br>");

  $groupName = "Referees";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select members.MemberID, members.LastName, members.FirstName, members.HomePhone, members.WorkPhone, members.MobilePhone, members.Email, members.ReceiveEmail, members.Referee, members.Ref_Class FROM members WHERE  Referee = 1 AND (ReceiveEmail = 1 AND Email != '')";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select members.MemberID, members.LastName, members.FirstName, members.HomePhone, members.WorkPhone, members.MobilePhone, members.Email, members.ReceiveEmail, members.Referee, members.Ref_Class FROM members WHERE  Referee = 1 AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>18. Members added to Referees group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>19. Update Members with Coaches group</font><br>");

  $groupName = "Coaches";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select MemberID, memb_id, FirstName, LastName, Email, MobilePhone, coach_id, class, comment, URL, coach_order, ReceiveEmail FROM members, coaches_vbsa WHERE members.MemberID = coaches_vbsa.memb_id AND (ReceiveEmail = 1 AND Email != '')  ORDER BY coach_order";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select MemberID, memb_id, FirstName, LastName, Email, MobilePhone, coach_id, class, comment, URL, coach_order, ReceiveEmail FROM members, coaches_vbsa WHERE members.MemberID = coaches_vbsa.memb_id AND (ReceiveEmail = 1 AND Email != '')  ORDER BY coach_order LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>20. Members added to Coaches group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>21. Update Members with Deactivated group</font><br>");

  $groupName = "Deactivated";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, paid_memb, LifeMember, referee, coach_id, ccc_player,  totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE curr_memb = '1'";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select MemberID, FirstName, LastName, MobilePhone, Email, paid_memb, LifeMember, referee, coach_id, ccc_player,  totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE curr_memb = '1' LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>22. Members added to Deactivated group</font><br>");
  echo '<br/>';
  echo("<font face='arial' color='green'>23. Update Members with Honorary group</font><br>");

  $groupName = "Honorary";
  $groupID = GetGroupID($groupName);
  DeleteGroup($groupID);
  CreateGroup($groupName);
  $new_groupId = GetGroupID($groupName);

  $query_audience = "Select members.MemberID, members.LastName, members.FirstName, members.HomePhone, members.WorkPhone, members.MobilePhone, members.Email, members.ReceiveEmail FROM members WHERE hon_memb = 1 AND (ReceiveEmail = 1 AND Email != '')";
  $result = mysql_query($query_audience, $connvbsa) or die(mysql_error());
  $no_of_records = $result->num_rows;
  $offset = 0; // offset required for mysql batches
  $y = 0; // number of sessions
  for($i = 0; $i < $no_of_records; $i++)
  {  
    if($i % 30 == 0)
    {
      $query_audience_1 = "Select members.MemberID, members.LastName, members.FirstName, members.HomePhone, members.WorkPhone, members.MobilePhone, members.Email, members.ReceiveEmail FROM members WHERE hon_memb = 1 AND (ReceiveEmail = 1 AND Email != '') LIMIT 30 OFFSET " . $offset;
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
  echo("<font face='arial' color='green'>24. Members added to Honorary group</font><br>");

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>End of MalierLite Members Reports";

  echo '<br/>'.'<br/>';
  echo "<font face='arial' size='3'>Calculations completed ".'<span class="greenbg"><a href="../Admin_DB_VBSA/A_memb_index.php">'. "Thank you". '</a></spsn>';
  echo '<br/><br/>';

  mysql_close ($connvbsa);
}
else
{
  echo '<center/>';
  echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/Update_Scores_Rank_STEP4E.php?submit=1'>";
  echo "<input type='submit' id='submit' name='submit'>";
  echo "</form>";
}

?>
</center>
</body>
</html>
