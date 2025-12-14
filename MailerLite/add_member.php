<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once('../vendor/autoload.php');

$apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiN2JlMmM1NDA0ODljMjUwMjUyODI2ODQ5MTQyNmJjYTVkYzBjZjAwOTJmZGQzNDFhYTc3NjkxMmU3YzNmNTQ1NDkxNTMxMzFiOTk2ZWQxOTEiLCJpYXQiOjE3MzI5MzMwNDkuOTkzMTc0LCJuYmYiOjE3MzI5MzMwNDkuOTkzMTc3LCJleHAiOjQ4ODg2MDY2NDkuOTg5MzgzLCJzdWIiOiIxMjI0MzAzIiwic2NvcGVzIjpbXX0.XU2PE1WSL7HYhPy12EzrHT_BR0uxBDi15Z81Ujopd9oco8Kc1ACu3_xFSrsktV9pK1yQfcao0AqAii4xdrKhisvhKzVbMn5eMvQPOJGW62vwRAp-KMvak5Ckr4hvxEGu8i2k5NpeFqLXMwbiAxsI59eMJD6XWhmAi9qOYgRh0V9W4VWbkba5HavyH-we1edlE4JTcZeyDVXR0NGDnPmn-1VxC0RHIwlZNVkwsz8FNlvnr4tefNBBxfRuuEsOY9vtDqEJ0E_8z8rFv0j_ldvQsdJr_qaOXvJYMdlU7oUiMPaj86ypItY6kmtsENdy66f8Iounce-lkTVfbkEzRN4dPlhVd396Nhcm-QW7qT7XeG-f0NGQDUUJuhVAVj1ZnbccQUph0lvFQEDwhA4oXvXvpZGhIuknSEpgA9t7cbmotrgjhrBYUl67ML9AVcWCXFGK7-CgIED2rYFa160Ws9ZEqYf6jl7P50Cia13Kqy8ebMuasQXShslkke5gbFvVMq6jgLrWniRONNSBng4-ZfNzwtRMe-iTVEP-Lc8exPSpwaX12-OaknqxLD7Lde_vQSmHNkJfAUdQWt8D5W-6m567lqBDHHy90X6mPoNRrLI-QwGXdnp37WY9zhzKBL1k6NDc0ybeLKgSpGdRemg6q0klE7PFLYpEcFJKfHGYIqGuRJg';

//$apiKey = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiNDkyOTE0OGM3NmE2YmNjYmM3MTdlNzczNTNhZGFlMDk2ZjhkYmI1NDE3MzM5OTJmYjFhOGU1ZWIwOWRlMjczMWNjYTBmOTUyYjUxNDY1YzUiLCJpYXQiOjE3MzMzNjQ1MTAuMDQyNjQsIm5iZiI6MTczMzM2NDUxMC4wNDI2NDMsImV4cCI6NDg4OTAzODExMC4wMzkxMTksInN1YiI6IjEyMjk5NzciLCJzY29wZXMiOltdfQ.jGQWUkbdZ-shyGyvzxS0gqN9kgLWS0Xl5YSV4qfQwuajdv9NaS_9e_Ax2sYLXl-TWjDAhDhell2Y348uUgZJDTI-4Q6dK6_6oPVYfO2kd74MCWrq-vq6SvYfICuMv07z61QegbuaEG5e7-spaLgXVIykjiuqwPPGLQMN6lDkHonRWFrxOi7xcxcsapkYayVeYHwq7w8A7Ntnhsljn2zO31NcGyKpp7I5wLtHGqCtz1UC3s0HZizQ6R70PHa3EUuX-2g6CtyFoWyf517_XoApYotcJFcWYF5piQT2AIAj1tKxAa8QRX4yBmV0dRcnrJzTQCEQYY5QszHkZgaQqXngYytK_0V8xddBpyPEUnRJz4fTaYNkF36cB_7bAbTjKYF5luFPJz1qIjltSeSpBMNJq_5vnJgBpq93EkAQzzD_Wcgg-wwOTCaCikfKJ9R4g-Jz6c67R-AhJCB81HIBInlNAYoorujPEfKE6YLbb3rh4kaGLurlo_yi-RbB-vLVTgb0smScf6CBsXF470hxchjO4LrUZXxVMvw2dDL44-Lzwcb49PtOhdR1GiDfaCyetlugz6LXSBQD0cBkPuwyoZxQmenNAW-KEFPP7bm1ev6Qh8VbYDeDjXqg85R8N28wlSlqoFtXLLZK-ZEtkaSCNylh3tTcnC8bG0jWczlvst3DS0I";

use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => $apiKey]);

mysql_select_db($database_connvbsa, $connvbsa);
// test members
$query_audience = "Select MemberID, LastName, FirstName, Email FROM test_members WHERE ReceiveEmail = 1 AND Email != ''";
//$groupID = '139843017473263525';

// all members and affiliates
//$limit = ' Limit 50';
//$limit = ' Limit 50 Offset 50';
//$limit = ' Limit 50 Offset 100';
//$limit = ' Limit 50 Offset 150';
//$limit = ' Limit 50 Offset 200';
//$limit = ' Limit 50 Offset 250';
//$limit = ' Limit 50 Offset 300';
//$limit = ' Limit 50 Offset 350';
//$limit = ' Limit 50 Offset 400';
//$limit = ' Limit 50 Offset 450';
//$limit = ' Limit 50 Offset 500';
//$limit = ' Limit 50 Offset 550';
//$limit = ' Limit 50 Offset 600';

//$query_audience = "Select MemberID, FirstName, LastName, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, hon_memb, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR affiliate_player=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR affiliate_player = 1 OR hon_memb = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '') " . $limit;
//$groupID = '139674893586269356';

//echo($query_audience . "<br>");
// all current mambers
//$query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, hon_memb FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR hon_memb=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '') " . $limit;
//$groupID = '139391020632638839';

// all affiliates
//$query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, LifeMember, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members Where affiliate_player = 1 and ReceiveEmail = 1 and Email != '' " . $limit;
//$groupID = '139674915446982306';

// all with email .... not included.....
//$query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, memb_by, curr_memb FROM members WHERE (ReceiveEmail != 0 and MemberID != 1 AND MemberID != 100 AND MemberID != 500 AND MemberID != 1000 AND Deceased !=1 AND Email != '') ORDER BY LastName, FirstName ";

// all captains (check season)
$query_audience = "Select members.MemberID, members.ReceiveEmail, LastName, FirstName, MobilePhone, Email, captain_scrs, Team_entries.team_id, Team_entries.team_grade, Team_entries.team_club, Team_entries.team_name, day_played, comptype FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE captain_scrs=1 AND team_cal_year = YEAR( CURDATE( ) ) AND scr_season='S1' AND (ReceiveEmail = 1 AND Email != '') ORDER BY Team_entries.team_grade, Team_entries.team_club";
//$groupID = '139674940507948858';

// all captains (check season)
//$query_audience = "Select members.MemberID, members.ReceiveEmail, LastName, FirstName, MobilePhone, Email, captain_scrs, Team_entries.team_id, Team_entries.team_grade, Team_entries.team_club, Team_entries.team_name, day_played, comptype FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE captain_scrs=1 AND team_cal_year = YEAR( CURDATE( ) ) AND scr_season='S2' AND (ReceiveEmail = 1 AND Email != '') ORDER BY Team_entries.team_grade, Team_entries.team_club";
//$groupID = '139675026885444943';

// all playing members (check season)
//$query_audience = "SELECT scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName,  MobilePhone, Email, ReceiveEmail, current_year_scrs, SUM(count_played) AS played, current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scr_season='S1'  AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberId != 1 AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID ORDER BY LastName, FirstName";
//$groupID = '139675045023712459';

// all playing members (check season)
//$query_audience = "SELECT scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName,  MobilePhone, Email, ReceiveEmail, current_year_scrs, SUM(count_played) AS played, current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scr_season='S2'  AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberId != 1 AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID ORDER BY LastName, FirstName";
//$groupID = '139675052255741695';

// all players (bulk email)
//$query_audience = "SELECT scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName, MobilePhone, Email, SUM(count_played), current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE (current_year_scrs = YEAR(CURDATE( )) OR current_year_scrs = YEAR(CURDATE( ))-1)  AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID HAVING SUM(count_played)>0 ORDER BY LastName, FirstName";

// all players by game (snooker or billiards)
//$query_audience = "SELECT scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, SUM(count_played) AS TotPlayed, game_type FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE game_type='Snooker' AND current_year_scrs = YEAR( CURDATE( ) ) AND (scrs.MemberID!=1 AND scrs.MemberID!=100 AND scrs.MemberID!=1000) GROUP BY scrs.MemberID ORDER BY LastName, FirstName";
//$groupID = '139675079996868000';

// all players by game (snooker or billiards)
//$query_audience = "SELECT scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, SUM(count_played) AS TotPlayed, game_type FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE game_type='Billiards' AND current_year_scrs = YEAR( CURDATE( ) ) AND (scrs.MemberID!=1 AND scrs.MemberID!=100 AND scrs.MemberID!=1000) GROUP BY scrs.MemberID ORDER BY LastName, FirstName";
//$groupID = '139675091799639211';

// all players qualified for finals (check season)
//$query_audience = "SELECT scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, scrs.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE count_played>3 AND scr_season = 'S1' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 HAVING audited='Yes' ORDER BY team_grade, team_id, FirstName, LastName";
//$groupID = '139675123889210565';

// all players qualified for finals (check season)
//$query_audience = "SELECT scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, scrs.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE count_played>3 AND scr_season = 'S2' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 HAVING audited='Yes' ORDER BY team_grade, team_id, FirstName, LastName";
//$groupID = '139675132276770177';

// all life members
//$query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND ReceiveEmail = 1 AND Email != '' AND LifeMember = 1";
//$groupID = '139675144279820132';

// all paid members
//$query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, paid_date, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND ReceiveEmail = 1 AND Email != '' AND (paid_memb = 20 AND YEAR(paid_date) = YEAR(NOW()))";
//$groupID = '139675152036136593';

// all city club players
//$query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND ReceiveEmail = 1 AND Email != '' AND ccc_player = 1";
//$groupID = '139675163354465701';

// all female/NB/NS players
//$query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, HomeState, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (Gender != 'Male') AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '')";
//$groupID = '139675179046406052';

// all junior players 
//$query_audience = "Select MemberID, LastName, FirstName, HomePhone, MobilePhone, Email, ReceiveEmail, Junior, dob_day, dob_mnth, dob_year, Homestate FROM members WHERE Junior != 'na' AND ReceiveEmail = 1 AND Email != '' ORDER BY LastName";
//$groupID = '139675186978883073';

// all referees
//$query_audience = "Select members.MemberID, members.LastName, members.FirstName, members.HomePhone, members.WorkPhone, members.MobilePhone, members.Email, members.ReceiveEmail, members.Referee, members.Ref_Class FROM members WHERE  Referee = 1 AND (ReceiveEmail = 1 AND Email != '')";
//$groupID = '139734468743136293';

// All coaches
//$query_audience = "Select MemberID, memb_id, FirstName, LastName, Email, MobilePhone, coach_id, class, comment, URL, coach_order, ReceiveEmail FROM members, coaches_vbsa WHERE members.MemberID = coaches_vbsa.memb_id AND (ReceiveEmail = 1 AND Email != '')  ORDER BY coach_order";
//$groupID = '139675217187308728';

// all deactivated members
//$query_audience = "Select MemberID, FirstName, LastName, MobilePhone, Email, paid_memb, LifeMember, referee, coach_id, ccc_player,  totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE curr_memb = '1'";
//$groupID = '139675226869859383';

$audience = mysql_query($query_audience, $connvbsa) or die(mysql_error());

/*
$test_admin = '139656709921572160';
$affiliates = '139674915446982306';
$captains_s1 = '139674940507948858';
$captains_s1 = '139675026885444943';
$ccc = '139675163354465701';
$coaches = '139675217187308728';
$members = '139391020632638839';
$deactivated = '139675226869859383';
$non_male = '139675179046406052';
$juniors = '139675186978883073';
$life = '139675144279820132';
$member_affiliates = '139674893586269356';
$paid = '139675152036136593';
$playing_biliiards = '139675091799639211';
$playing_s1 = '139675045023712459';
$playing_s2 = '139675052255741695';
$playing_snooker = '139675079996868000';
$finals_s1 = '139675123889210565';
$finals_s2 = '139675132276770177';
$referees = '139734468743136293';
$test_entry = '139764978906301564';
*/
$i = 0;
while ($row_audience = mysql_fetch_assoc($audience))
{
    $email = $row_audience['Email'];
    $first_name = $row_audience['FirstName'];
    $last_name = $row_audience['LastName'];
    $member_id = $row_audience['MemberID'];
    $data = [
        'email' => $email,
        'status' => 'active', // or 'unsubscribed'
        'fields' => [
            'memberid' => $member_id,
            'name' => $first_name,
            'last_name' => $last_name
        ],
    ];
    echo($email . "<br>");
    //echo("<pre>");
    //echo(var_dump($data));
    //echo("</pre>");
    $i++;
    $response = $mailerLite->subscribers->create($data);
}
echo 'Successfully updated ' . $i . ' members!';

/*
'groups' => [
            'id' => $groupID
        ],
*/
