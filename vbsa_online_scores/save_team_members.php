<?php

include('connection.inc');
include('php_functions.php'); 

$current_year = $_GET['year'];
$current_season = $_GET['season'];

$team_grade = $_GET['team_grade'];
$team_name = $_GET['team_name'];
$club_name = $_GET['club_name'];
$team_id = $_GET['team_id'];
$team_array = $_GET['members_array'];
$row_arr = explode(";", $team_array);

//echo($team_id . "<br>");
//echo("<pre>");
//echo(var_dump($team_array));
//echo("</pre>");

function TeamMemberEmail($email, $name, $captain_name, $captain_email, $grade_name)
{
   $current_year = $_GET['year'];
   $current_season = $_GET['season'];
   $team_grade = $_GET['team_grade'];
   $team_name = $_GET['team_name'];

   $subject = 'VBSA Team Registration'; 
   $message = '<html><body>';
   $message .= "<p>" . $name . "</p>";
   $message .= "<p>The team " . $team_name . " has been entered in the " . $grade_name . " Grade for " . $current_season . " of " . $current_year . " and you have been added to that team.</p>";
   $message .= "<p>You can review the team on the VBSA Scores page at <a href='https://www.vbsa.org.au/VBSA_scores/scores_index_detail.php?season=" . $current_season . "&year=" . $current_year . "'>https://www.vbsa.org.au/VBSA_scores/scores_index_detail.php?season=" . $current_season . "&year=" . $current_year . "</a></p>";
   $message .= "<p>All enquiries should be directed to the Team Captain, " . $captain_name . " at <a href=mailto:" . $captain_email . ">" . $captain_email . "</a></p>";
   $message .= "<p><font color='red'>IMPORTANT.  Grades will not be finalised until ALL teams have been submitted.  If entries are low, grades may be combined.</font>";
   $message .= "<p>Thank you for your interest in the VBSA.</p>";
   $message .= "<p>VBSA Scores Registrar.</p>";
   $message .= "<p>" . date('d/m/Y') . "</p>";
   $message .= "<p><i>Email <a href='mailto:scores@vbsa.org.au'>scores@vbsa.org.au</a></i></p>";
   $message .= "</body></html>";
   SendBulkEmail($subject, $message, $email);
}

function SREmail($email, $name, $captain_name, $captain_email, $grade_name)
{
   $current_year = $_GET['year'];
   $current_season = $_GET['season'];
   $team_grade = $_GET['team_grade'];
   $team_name = $_GET['team_name'];

   $subject = 'VBSA Team Registration'; 
   $message = '<html><body>';
   $message .= "<p>" . $name . "</p>";
   $message .= "<p>The team " . $team_name . " has been entered in the " . $grade_name . " Grade for " . $current_season . " of " . $current_year . ".</p>";
   $message .= "<p>You can review the team on the VBSA Scores page at <a href='https://www.vbsa.org.au/VBSA_scores/scores_index_detail.php?season=" . $current_season . "&year=" . $current_year . "'>https://www.vbsa.org.au/VBSA_scores/scores_index_detail.php?season=" . $current_season . "&year=" . $current_year . "</a></p>";
   $message .= "<p>All enquiries should be directed to the Team Captain, " . $captain_name . " at <a href=mailto:" . $captain_email . ">" . $captain_email . "</a></p>";
   $message .= "<p><font color='red'>IMPORTANT.  Grades will not be finalised until ALL teams have been submitted.  If entries are low, grades may be combined.</font>";
   $message .= "<p>Thank you for your interest in the VBSA.</p>";
   $message .= "<p>VBSA Scores Registrar.</p>";
   $message .= "<p>" . date('d/m/Y') . "</p>";
   $message .= "<p><i>Email <a href='mailto:scores@vbsa.org.au'>scores@vbsa.org.au</a></i></p>";
   $message .= "</body></html>";
   SendBulkEmail($subject, $message, $email);
}

function TestEmail($email, $name, $captain_name, $captain_email, $grade_name)
{
   //echo("Here");
   $current_year = $_GET['year'];
   $current_season = $_GET['season'];
   $team_name = $_GET['team_name'];

   $subject = 'VBSA Team Registration (Test)'; 
   $message = '<html><body>';
   $message .= "<p>" . $name . "</p>";
   $message .= "<p>The team " . $team_name . " has been entered in the " . $grade_name . " Grade for " . $current_season . " of " . $current_year . ".</p>";
   $message .= "<p>You can review the team on the VBSA Scores page at <a href='https://www.vbsa.org.au/VBSA_scores/scores_index_detail.php?season=" . $current_season . "&year=" . $current_year . "'>https://www.vbsa.org.au/VBSA_scores/scores_index_detail.php?season=" . $current_season . "&year=" . $current_year . "</a></p>";
   $message .= "<p>All enquiries should be directed to the Team Captain, " . $captain_name . " at <a href=mailto:" . $captain_email . ">" . $captain_email . "</a></p>";
   $message .= "<p><font color='red'>IMPORTANT.  Grades will not be finalised until ALL teams have been submitted.  If entries are low, grades may be combined.</font>";
   $message .= "<p>Thank you for your interest in the VBSA.</p>";
   $message .= "<p>VBSA Scores Registrar.</p>";
   $message .= "<p>" . date('d/m/Y') . "</p>";
   $message .= "<p><i>Email <a href='mailto:scores@vbsa.org.au'>scores@vbsa.org.au</a></i></p>";
   $message .= "</body></html>";
   //echo($message . "<br>");
   SendBulkEmail($subject, $message, 'vk3eka@bigpond.net.au');
}

function AuthoriseEmail($access, $firstname, $rego_id, $password, $email, $team)
{
   $subject = 'VBSA Scoring System Access'; 
   $message = '<html><body>';
   $message .= "<p>" . $firstname . "</p>";
   $message .= "<p>Here are your log-in details for the VBSA scoring system, the Scores Entry Webpage System (SEWS). You have been granted " . $access . " access for " . $team . ".</p>";
   $message .= "<p>Username " . $email . "</p>";
   $message .= "<p>Password " . $password . "</p>";

   $message .= "<p>Click <a href='https://vbsa.org.au/vbsa_online_scores'>here </a>to access the Scores Entry Webpage System (SEWS).</p>";
    $message .= "<p>Select 'Scoresheet Log In' from the menu item at far right of menu bar.</p>";
    $message .= "<p>Once logged in to SEWS, you can change your password, if you wish to.</p>";
    $message .= "<p>Instructions on how to use SEWS are located on the VBSA website Scores <a href='https://www.vbsa.org.au/VBSA_scores/scores_index.php'> page.</a></p>";
    $message .= "<p>Please note that a condition of being a SEWS Captain or Authoriser is that you agree to receive important or time sensitive messages regarding weekday Pennant.</p>";
    $message .= "<p>This does not override any other communication preferences you may have made with us.</p>";
    $message .= "<p>Your co-operation in this regard is appreciated.</p>";

/*    
   $message .= "<p>Please access the system and change your password, if you wish to.</p>";
   $message .= "<p>Click <a href='http://vbsa.org.au/'>here </a>to access the Scoring APP portal.</p>";
   $message .= "<p>Select from the menu item at far right of menu bar.</p>";
   $message .= "<p>Instructions on how to use the APP are located on the VBSA website Scores <a href='https://www.vbsa.org.au/VBSA_scores/scores_index.php'> page.</a></p>";

*/

   $message .= "<p>Thanks.</p>";
   $message .= "<p>VBSA Scores Registrar.</p>";
   $message .= "<p>" . date('d/m/Y') . "</p>";
   $message .= "<p>&nbsp;</p>";
   $message .= "<p><i>NOTE: Please do not reply to this email as this email address is not monitored.</i></p>";
   $message .= "<p><i>Direct all emails to <a href='mailto:scores@vbsa.org.au'>scores@vbsa.org.au</a></i></p>";
   $message .= "<img src='http://vbsa.org.au/vbsa_online_scores/MarkDunn.jpg' width = '400px' height = '140px'>";
   $message .= "</body></html>";
   SendBulkEmail($subject, $message, $email);
}

// get grade name from grade
$sql_grade = "Select grade, grade_name, RP FROM Team_grade WHERE season = '" . $current_season . "' AND fix_cal_year = " . $current_year . " and grade = '" . $team_grade . "'";
//echo("Grade " . $sql_grade . "<br>");
$result_grades = $dbcnx_client->query($sql_grade);
$build_data = $result_grades->fetch_assoc();
$team_grade_name = $build_data['grade_name'];
$allocated_rp = $build_data['RP'];

 // delete existing data
$sql_scrsheet = "Delete From scrs where scr_season = '" . $current_season . "' and current_year_scrs = " . $current_year . " and team_grade = '" . $team_grade . "' and team_id = '" . $team_id . "'"; 
//echo("Delete " . $sql_scrsheet . "<br>");
$update = $dbcnx_client->query($sql_scrsheet);

// delete existing temp data
$sql_team_rego = "Delete From tbl_team_rego where team_grade = '" . $team_grade . "' and team_name = '" . $team_name . "' and club_name = '" . $club_name . "'";
//echo($sql_team_rego . "<br>");
$update = $dbcnx_client->query($sql_team_rego);

for($i = 0; $i < (count($row_arr)-1); $i++)
{
   $member_arr = explode(", ", $row_arr[$i]);
//echo("<pre>");
//echo(var_dump($member_arr));
//echo("</pre>");
   $memberID = $member_arr[0];
   // remove , from start of string
   if(substr($memberID, 0, 1) == ',')
   {
      $memberID = substr($memberID, 1, strlen($memberID));
   }
   //$manager_id = $member_arr[7];
   $team_name = $member_arr[6];
   $team_grade = $member_arr[2];
   //$team_id = $member_arr[1];
   $captain = $member_arr[3];
   $selected = $member_arr[4];
   $type = $member_arr[5];
   $need = $member_arr[8];
   $authoriser = $member_arr[9];
   //echo("Need " . $need . "<br>");
   $count_played = 0;
   //$allocated_rp = 50;
   $average_position = 0;
   $max_pts = 0;
   $final_sub = 'No';
   
   $sql = "Insert INTO scrs (
   MemberID,
   team_grade,
   allocated_rp,
   game_type,
   scr_season,
   team_id,
   selected,
   captain_scrs,
   count_played,
   average_position,
   maxpts,
   final_sub,
   fin_year_scrs,
   current_year_scrs,
   authoriser_scrs
   )
   VALUES 
   (" .
   $memberID . ", '" .
   $team_grade . "', " .
   $allocated_rp . ", '" .
   $type . "', '" .
   $current_season . "', " .
   $team_id . ", " .
   $selected . ", " .
   $captain . ", " .
   $count_played . ", " .
   $average_position . ", " .
   $max_pts . ", '" .
   $final_sub . "', " .
   $current_year . ", " .
   $current_year . ", " .
   $authoriser . ")";
   //echo($sql . "<br>");
   $update = $dbcnx_client->query($sql);
   if(!$update)
   {
      $caption = "Failed: " . mysqli_error($dbcnx_client);
   } 
   else
   {
      $caption = "Success";
   }

   // add tag if new players are needed
   if($need == 1)
   {
      $sql = "Update Team_entries SET need_players = 1 where team_id = " . $team_id . " and team_season = " . $current_season . " and team_grade = '" . $team_grade . "' and team_cal_year = " . $current_year;
      $update = $dbcnx_client->query($sql);
   }
   
   if(($captain == 1) || ($authoriser == 1))
   {
      // add captain/authoriser password and send email
      // check if authorised already
      $sql_auth = "Select PlayerNo FROM tbl_authorise WHERE PlayerNo = " . $memberID;
      $result_auth = $dbcnx_client->query($sql_auth);
      $row_data = $result_auth->fetch_assoc();
      $original_team = $row_data['Team_1'];

      if($result_auth->num_rows == 0)
      {
         if($authoriser == 1)
         {
            $access = 'Team Authoriser';
         }
         else
         {
            $access = 'Team Captain';
         }
         // get members name from rego number
         $sql = "Select * from members where MemberID  = " . $memberID;
         $result_select_rego = $dbcnx_client->query($sql);
         $row_data = $result_select_rego->fetch_assoc();
         $fullname = ($row_data['FirstName'] . " " . $row_data['LastName']);
         $password = generatePassword(10);
         // send email
         AuthoriseEmail($access, $row_data['FirstName'], $row_data['MemberID'], $password, $row_data['Email'], $team_name);
         ////SREmail("scores@vbsa.org.au", $fullname, $row_data['Email'], $team_name, $team_grade);
         $sql = "Insert into tbl_authorise (Name, Password, Access, Team_1, PlayerNo, Email, Active) VALUES ('" . $fullname . "', '" . password_hash($password, PASSWORD_DEFAULT) . "', '" . $access . "', '" . $team_name . "', " . $memberID . ", '" . $row_data['Email'] . "', 1)";
         $update = $dbcnx_client->query($sql);
      }
      else //added to cater for a no team login...................
      {
         // get original team name

         $sql = "Update tbl_authorise SET Team_1 = '" . $team_name . "' where PlayerNo = " . $memberID;
         $update = $dbcnx_client->query($sql);

         if($original_team === 'Temp Login')
         {
            // add data to scrs table

            // get team details from team name
            $sql_get_team_id = "Select * FROM vbsa3364_vbsa2.Team_entries where team_season = '$season' and team_cal_year = $year and team_name = '" . $team_name . "'";
            //echo($sql_get_team_id . "<br>");     
            $result_get_team_id = $dbcnx_client->query($sql_get_team_id);
            $row_get_team_id = $result_get_team_id->fetch_assoc();
            $team_name = $row_get_team_id['team_name'];
            $team_id = $row_get_team_id['team_id'];
            $team_grade = $row_get_team_id['team_grade'];
            $team_type = $row_get_team_id['comptype'];
            $sql_get_scrs_id = "Select * FROM scrs where scr_season = '$season' and current_year_scrs = $year and team_id = $team_id and MemberID = " . $_POST['MemberID'];
            $result_get_scrs_id = $dbcnx_client->query($sql_get_scrs_id);
            $row_get_scrs_id = $result_get_scrs_id->fetch_assoc();
            $num_rows = $result_get_scrs_id->num_rows;
            if($num_rows > 0)
            {
                $scrs_id = $row_get_scrs_id['scrsID'];
                $sql = "Update scrs SET MemberID='" . $_POST['MemberID'] . "', team_grade='$team_grade', game_type='$team_type', scr_season='$season', team_id=$team_id, captain_scrs=$captain_scrs, authoriser_scrs=$authoriser_scrs WHERE scrsID=$scrs_id";
            }
            else
            {
                $sql = "Insert into scrs (MemberID, team_grade, game_type, scr_season, current_year_scrs, team_id, captain_scrs, authoriser_scrs) VALUES (" . $_POST['MemberID'] . ", '" . $team_grade . "', '" . $team_type . "', '" . $season . "', " . $year . ", " . $team_id . ", " . $captain_scrs . ", " . $authoriser_scrs . ")";
            } 
            //echo($sql . "<br>");     
            $update = $dbcnx_client->query($sql);
         }
      }
   }
}

// get captain email
$sql_captain = "Select FirstName, LastName, Email FROM members, scrs WHERE members.MemberID = scrs.MemberID and scr_season = '" . $current_season . "' and current_year_scrs = " . $current_year . " and team_grade = '" . $team_grade . "' and captain_scrs = 1 and team_id = " . $team_id;
//echo($sql_captain . "<br>");
$result_captain = $dbcnx_client->query($sql_captain);
$build_captain_data = $result_captain->fetch_assoc();
$captain_name = $build_captain_data['FirstName'] . " " . $build_captain_data['LastName'];
$captain_email = $build_captain_data['Email'];

$managerID = 0;
for($i = 0; $i < (count($row_arr)-1); $i++)
{
   $member_arr = explode(", ", $row_arr[$i]);
   $memberID = $member_arr[0];
   $managerID = $member_arr[7];
   // remove , from start of string
   if(substr($memberID, 0, 1) == ',')
   {
      $memberID = substr($memberID, 1, strlen($memberID));
   }
   // send email to team members
   $sql_member = "Select FirstName, LastName, Email FROM members WHERE MemberID = " . $memberID;
   $result_member = $dbcnx_client->query($sql_member);
   $build_member_data = $result_member->fetch_assoc();
   $member_name = $build_member_data['FirstName'] . " " . $build_member_data['LastName'];
   $member_email = $build_member_data['Email'];
   TeamMemberEmail($member_email, $member_name, $captain_name, $captain_email, $team_grade_name);
}

$sql_manager = "Select FirstName, LastName, Email FROM members WHERE MemberID = " . $managerID;
$result_manager = $dbcnx_client->query($sql_manager);
$build_manager_data = $result_manager->fetch_assoc();
$manager_name = $build_manager_data['FirstName'] . " " . $build_manager_data['LastName'];
$manager_email = $build_manager_data['Email'];

SREmail('scores@vbsa.org.au', 'Scores Registrar', $captain_name, $captain_email, $team_grade_name);
//SREmail($manager_email, $manager_name, $captain_name, $captain_email, $team_grade_name);

//echo("Team Name " . $team_name . "<br>");

// for testing only.....
if($manager_email == '')
{
   $manager_email = "No Email provided!";
}
if($manager_name == '')
{
   $manager_name = "No Name provided";
}
if($captain_name == '')
{
   $captain_name = "No Captain Name provided!";
}
if($captain_email == '')
{
   $captain_email = "No Captain Email provided!";
}
if($team_grade_name == '')
{
   $team_grade_name = "No Grade Name provided!";
}

/*
echo("Manager Email " . $manager_email . "<br>");
echo("Manager Name " . $manager_name . "<br>");
echo("Captain Name " . $captain_name . "<br>");
echo("Captian Email " . $captain_email . "<br>");
echo("Team Grade " . $team_grade_name . "<br>");
*/
TestEmail($manager_email, $manager_name, $captain_name, $captain_email, $team_grade_name);
// end of test

echo($caption);

?>
