<?php

// all members and affiliates
/*
LifeMember=1 
affiliate_player=1 
ccc_player=1 
referee=1 
active_coach=1 
Gender != 'Male' 
hon_memb = 1 
Deceased !=1 
curr_memb = 0 
ReceiveEmail = 1 
Email != ''
paid_memb=20
YEAR(paid_date)



// maybe need to update in update files.

totplayed_curr // update scores step 3
totplaybill_curr>0 // update scores step 3
captain_scrs = 1
Junior!='na' -- dob_year
// all playing members (check season)
//WHERE scr_season='$season' AND current_year_scrs = YEAR(CURDATE( ))";
// all players (bulk email)
//WHERE (current_year_scrs = YEAR(CURDATE( )) OR current_year_scrs = YEAR(CURDATE( ))-1) HAVING SUM(count_played)>0";
// all players by game (snooker or billiards)
//WHERE game_type='$comptype' AND current_year_scrs = YEAR( CURDATE( ) )";
// all players qualified for finals (check season)
//WHERE count_played>3 AND scr_season = '$season' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 HAVING audited='Yes'";
*/



/*
// change in members edit/add
GetSQLValueString(isset($_POST['LifeMember']) ? "true" : "", "defined","1","0"),
GetSQLValueString(isset($_POST['affiliate_player']) ? "true" : "", "defined","1","0"),
GetSQLValueString(isset($_POST['ccc_player']) ? "true" : "", "defined","1","0"),
GetSQLValueString(isset($_POST['Referee']) ? "true" : "", "defined","1","0"),
GetSQLValueString(isset($_POST['active_coach']) ? "true" : "", "defined","1","0"),
GetSQLValueString($_POST['Gender'], "text"),
GetSQLValueString(isset($_POST['hon_memb']) ? "true" : "", "defined","1","0"),
GetSQLValueString(isset($_POST['Deceased']) ? "true" : "", "defined","1","0"),
GetSQLValueString(isset($_POST['curr_memb']) ? "true" : "", "defined","1","0"),
GetSQLValueString(isset($_POST['ReceiveEmail']) ? "true" : "", "defined","1","0"),
GetSQLValueString($_POST['Email'], "text"),

*/
