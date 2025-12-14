<?php
require_once('../Connections/connvbsa.php'); 

//include '../vbsa_online_scores/header_admin.php';

mysql_select_db($database_connvbsa, $connvbsa);

error_reporting(0);

function GetMemberName($memberid)
{
    global $connvbsa;
    global $database_connvbsa;
    $query_member_name = 'Select FirstName, LastName FROM vbsa3364_vbsa2.members where MemberID = ' . $memberid;
    $result_member_name = mysql_query($query_member_name, $connvbsa) or die(mysql_error());
    $build_member_name = $result_member_name->fetch_assoc();
    $member_name = $build_member_name['FirstName'] . " " . $build_member_name['LastName'];
    return $member_name;
}

/*
'202272', '8'
'202274', '15'
'202281', '24'
'202251', '48'
'202269', '98'
*/

// change to $_GET when automated.

if(isset($_GET['tourn_id']))
{
    $tournament_id = $_GET['tourn_id'];

//if(isset($_POST['tournament']))
//{
//    $tournament_id = $_POST['tournament'];

    //echo("Tournament ID " . $tournament_id . "<br><br><br>");
/*
    // delete any existing scores results
    $query_delete = 'Delete from tournament_scores where tourn_id = ' . $tournament_id;
    $result_delete = mysql_query($query_delete, $connvbsa) or die(mysql_error());

    // delete any existing date results
    $query_delete_date = 'Delete from tournament_draw_dates where tourn_id = ' . $tournament_id;
    $result_delete_date = mysql_query($query_delete_date, $connvbsa) or die(mysql_error());
*/
    // get tournament name
    $query_tourn_name = 'Select *, tournaments.tourn_type as type FROM vbsa3364_vbsa2.tournaments LEFT JOIN calendar ON tournaments.tourn_id = calendar.tourn_id where tournaments.tourn_id = ' . $tournament_id;
    //echo($query_tourn_name . "<br>");
    $result_tourn_name = mysql_query($query_tourn_name, $connvbsa) or die(mysql_error());
    $build_tourn_name = $result_tourn_name->fetch_assoc();
    $tourn_type = $build_tourn_name['type'];
    $move_to_R2 = $build_tourn_name['move_top_seed'];
    $previous_winner = $build_tourn_name['previous_winner'];
    //echo("Previous Winner " . $previous_winner . "<br>");
    // get original players in team entries table
    if($tourn_type == 'Billiards')
    {
        $query_tourn_orig = '(Select *, 1 as sort_col FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_Billiards on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournament_id . ' and MemberID != ""  AND ranknum > 0 Order by ranknum ASC)
        Union All 
        (Select *, 2 as sort_col FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_Billiards on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournament_id . ' and MemberID != ""  AND ranknum IS NULL Order by ranknum ASC) Order by sort_col, ranknum';
    }
    else if($tourn_type == 'Snooker')
    {
        $query_tourn_orig = '(Select *, 1 as sort_col FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_S_open_tourn on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournament_id . ' and MemberID != ""  AND ranknum > 0 Order by ranknum ASC)
        Union All 
        (Select *, 2 as sort_col FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_S_open_tourn on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournament_id . ' and MemberID != ""  AND ranknum IS NULL Order by ranknum ASC) Order by sort_col, ranknum';
    }
    //echo("SQL Original " . $query_tourn_orig . "<br>");
    $result_tourn_orig = mysql_query($query_tourn_orig, $connvbsa) or die(mysql_error());
    $total_original = $result_tourn_orig->num_rows;
    //echo("Total Players in team_entry " . $total_original . "<br>");
    $original = [];
    $i = 1;
    $original_index = 0;
    while($build_players_orig = $result_tourn_orig->fetch_assoc())
    {
        if($build_players_orig['MemberID'] == $previous_winner)
        {
            //echo("Yes, I'm here!<br>");
            $original[$original_index] = "Round: 1, Index: " . $i . ", MemberID: " . $build_players_orig['MemberID'] . ", Rank: -1, Time: " . $build_players_orig['time_' . $total_players];
            $original_index = 1;
        }
    }
    $i++;
    //$i = 1;
    //echo("Original List in Ranking Order (smallest number is the highest ranked)<br>");
    mysql_data_seek($result_tourn_orig, 0);
    //$original_index = 1;
    while($build_players_orig = $result_tourn_orig->fetch_assoc())
    {
        if($build_players_orig['ranknum'] != '')
        {
            $rank_num = $build_players_orig['ranknum'];
        }
        else
        {
            $rank_num = 0;
        }

        $original[$original_index] = "Round: 1, Index: " . $i . ", MemberID: " . $build_players_orig['MemberID'] . ", Rank: " . $rank_num . ", Time: " . $build_players_orig['time_' . $total_players];
        $original_index++;
        $i++;
    }

    //echo("<pre>");
    //echo(var_dump($original)); // ok
    //echo("</pre>"); 

$ranked_index = 0;
foreach($original as $key => $row)
{
    $player = explode(", ", $row);
    $round = explode(": ", $player[0]);
    $member_id = explode(": ", $player[2]);
    $rank = explode(": ", $player[3]);
    $time = explode(": ", $player[4]);
    if($rank[1] != 0)
    {
        $ranked[$ranked_index] = "Round: 1, Index: " . ($ranked_index+1) . ", MemberID: " . $member_id[1] . ", Rank: " . $rank[1] . ", Time: " . $time[1];
        //echo($ranked[$ranked_index] . "<br>");
        $ranked_index++;
    }
}

//echo("<pre>");
//echo(var_dump($ranked)); // ok
//echo("</pre>"); 


$zero_index = 0;
foreach($original as $key => $row)
{
    $player = explode(", ", $row);
    $round = explode(": ", $player[0]);
    $member_id = explode(": ", $player[2]);
    $rank = explode(": ", $player[3]);
    $time = explode(": ", $player[4]);
    if($rank[1] == 0)
    {
        if($member_id[1] != $previous_winner)
        {
            $zero[$zero_index] = "Round: 1, Index: " . ($zero_index+1) . ", MemberID: " . $member_id[1] . ", Rank: " . $rank[1] . ", Time: " . $time[1];
            //echo($zero[$zero_index] . "<br>");
            $zero_index++;
        }
    }
}

//echo("<pre>");
//echo(var_dump($zero)); // ok
//echo("</pre>"); 


    $total_tourn = count($original);
    switch ($total_tourn) 
    {
        case ($total_tourn <= 8):
          $total_players = 8;
          break;
        case ($total_tourn <= 16) && ($total_tourn > 8):
          $total_players = 16;
          break;
        case ($total_tourn <= 32) && ($total_tourn > 16):
          $total_players = 32;
          break;
        case ($total_tourn <= 64) && ($total_tourn > 32):
          $total_players = 64;
          break;
        case ($total_tourn <= 128) && ($total_tourn > 64):
          $total_players = 128;
          break;
    }

    $odd = [];
    $even = [];
    $bye = [];
    $playersArr = [];

    //$i = 1;
    $bye_index = 10000;
/*    
    // get non ranked players in team entries table
    // create non-ranked array
    if($tourn_type == 'Billiards')
    {
        $query_tourn_zero = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_Billiards on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournament_id . ' and MemberID != "" and ranknum IS NULL Order by ranknum DESC';
    }
    else if($tourn_type == 'Snooker')
    {
        $query_tourn_zero = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_S_open_tourn on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournament_id . ' and MemberID != "" and ranknum IS NULL Order by ranknum DESC';
    }
    //echo("Non Ranked SQL " . $query_tourn_zero . "<br>");
    $result_tourn_zero = mysql_query($query_tourn_zero, $connvbsa) or die(mysql_error());
    $zero_count = $result_tourn_zero->num_rows;
    $zero = [];
    //$zero_r2 = [];
    //echo("Total Players in tourn_entry with no rank points - " . $zero_count . "<br>");
    $zero_index = 0;
    while($build_players = $result_tourn_zero->fetch_assoc())
    {
        //echo($build_players['MemberID'] . "<br>");
        if($build_players['ranknum'] == null)
        {
            $rank_num = 0;
        }
        else
        {
            $rank_num = $build_players['ranknum'];
        }
        if($build_players['MemberID'] == $previous_winner)
        {
            $rank_num = -1;
        }
        //$zero[$zero_index] = "Rank: " . $rank_num . ", Round: 1, Index: " . ($zero_index+1) . ", MemberID: " . $build_players['MemberID'] . ", Time: " . $build_players['time_' . $total_players];
        $zero[$zero_index] = "Round: 1, Index: " . ($zero_index+1) . ", MemberID: " . $build_players['MemberID'] . ", Rank: " . $rank_num . ", Time: " . $build_players['time_' . $total_players];
        $zero_index++;
    }

    //sort($zero);

    //echo("List of Non Ranked (index)<br>");
    //echo("<pre>");
    //echo(var_dump($zero));
    //echo("</pre>"); 
*/
    //$arrCount = ($original_index);
    $arrCount = ($ranked_index+$zero_index);
    //echo("Array Count " . $original_index . "<br>");
    /*
    echo("Total Original Players " . count($original) . "<br>");
    echo("Total Ranked Players " . count($ranked) . "<br>");
    echo("Total Non Ranked Players " . count($zero) . "<br>");
    echo("Total Players " . $total_players . "<br>");
    echo("Total Count " . $arrCount . "<br>");
    */
    //echo("Specified Move Count " . $move_to_R2 . "<br>");
    $max_move_count = ($total_players-$arrCount);
    if($max_move_count > count($ranked))
    {
        $max_move_count = count($ranked);
    }
    if($max_move_count > $move_to_R2)
    {
        $max_move_count = $move_to_R2;
    }
    //echo("Max Move Count " . $max_move_count . "<br>");

    // -----------------------  get byes required  ---------------------------------------------------

    //$arrCount = ($original_index);
    $byes_required = ($total_players-$arrCount-$max_move_count);
    //echo("Byes " . $byes_required . "<br>");
    //echo("Total Players " . $total_players . "<br>");
    //echo("Total Count " . $arrCount . "<br>");
    for($x = 0; $x < $byes_required; $x++)
    {
        $bye[] = "Round: 1, Index: " . ($x+200) . ", MemberID: " . ($bye_index+100+$x) . ", Rank: 0";
    }
/*
    echo("List of Byes<br>");
    echo("<pre>");
    echo(var_dump($bye));
    echo("</pre>"); 

    echo("List of Ranked (index)<br>");
    echo("<pre>");
    echo(var_dump($ranked));
    echo("</pre>"); 

    echo("List of Non Ranked (index)<br>");
    echo("<pre>");
    echo(var_dump($zero));
    echo("</pre>"); 
*/    
    $even_r2 = [];
    $odd_r2 = [];

    $total = [];
    $x = 0;
    $y = 0;
    $z = 0;
    $test = 0;
    for($i = 0; $i < $arrCount; $i++)
    {
        if(($i < $max_move_count) && ($max_move_count > 0)) // 6
        {
            $total[] = $ranked_r2[$i];
            //echo($total[$i] . "<br>");
            if($i % 2 == 0) 
            {
                if($ranked[$y] != '')
                {
                    $even[] = $ranked[$y];
                    $even_r2[] = $ranked_r2[$y];
                    $y++;
                    $even[] = "Round: 1, Index: " . ($x+200) . ", MemberID: " . ($bye_index+100+$x) . ", Rank: 0";
                }
                if($zero[$z] != '')
                {
                    $even[] = $zero[$z];
                    $z++;
                    $even[] = $zero[$z];
                    $z++;
                }
            }
            else
            {
                if($ranked[$y] != '')
                {
                    $odd[] = $ranked[$y];
                    $odd_r2[] = $ranked_r2[$y];
                    $y++;
                    $odd[] = "Round: 1, Index: " . ($x+200) . ", MemberID: " . ($bye_index+100+$x) . ", Rank: 0";
                }
                if($zero[$z] != '')
                {
                    $odd[] = $zero[$z];
                    $z++;
                    $odd[] = $zero[$z];
                    $z++;
                }
            }
        }
        if(($max_move_count < $arrCount) && ($max_move_count > 0)) // 18
        {
            if($max_move_count % 2 == 0) 
            {
                if($ranked[$y] != '')
                {
                    $even[] = $ranked[$y];
                    $y++;
                }
                if($zero[$z] != '')
                {
                    $even[] = $zero[$z];
                    $z++;
                }
            }
            else
            {
                if($ranked[$y] != '')
                {
                    $odd[] = $ranked[$y];
                    $y++;
                }
                if($zero[$z] != '')
                {
                    $odd[] = $zero[$z];
                    $z++;
                }
            }
        }
        
        if($max_move_count == 0) // no players moved - fill draw with normal player positions ........All OK
        {
            if($i % 2 == 0) 
            {
                if($ranked[$y] != '')
                {
                    $even[] = "Ranked Even (0 moves) " . $ranked[$y];
                    $y++;
                }
                if($zero[$z] != '')
                {
                    $even[] = "Zero Even (0 moves) " . $zero[$z];
                    $z++;
                }
            }
            else
            {
                if($ranked[$y] != '')
                {
                    $odd[] = "Ranked Odd (0 moves) " . $ranked[$y];
                    $y++;
                }
                if($zero[$z] != '')
                {
                    $odd[] = "Zero Odd (0 moves) " . $zero[$z];
                    $z++;
                }
            }
        }
        $bye_index++;
    }
    //$even = array_reverse($even);
    //sort($odd);
/*  
    echo("List of Even<br>");
    echo("<pre>");
    echo(var_dump($even));
    echo("</pre>"); 

    echo("List of Odd<br>");
    echo("<pre>");
    echo(var_dump($odd));
    echo("</pre>"); 
*/
/*    echo("List of Even 2 <br>");
    echo("<pre>");
    echo(var_dump($even_r2));
    echo("</pre>"); 

    echo("List of Odd 2 <br>");
    echo("<pre>");
    echo(var_dump($odd_r2));
    echo("</pre>"); 

    echo("List of All<br>");
    echo("<pre>");
    echo(var_dump($total));
    echo("</pre>"); 
*/
/*
    echo("Even Count " . count($even) . "<br>");
    echo("Odd Count " . count($odd) . "<br>");
    echo("Zero Count " . count($zero) . "<br>");
    echo("Total Count " . (count($even)+count($odd)+count($zero)+$byes_required) . "<br>");
    echo("Byes Required " . $byes_required . "<br><br>");
*/
    if($max_move_count == 0)
    {
        $newArray = array_merge($odd, $zero, $bye, $even);
    }
    else
    {
        $newArray = array_merge($odd, $bye, $even);
    }
    
    $newArray = array_merge($even, $bye, $odd); // ????????????????
    
    //echo(count($newArray) . "<br>");
    //$newArrayR2 = array_merge($odd_r2, $even_r2);
    //$newArrayR2 = array_merge($total);
/*
    echo("Combined List to display<br>");
    echo("<pre>");
    echo(var_dump($newArray));
    echo("</pre>"); 
*/

    //$score_table_index = 0;
    //$i = 0;
    //$score_table = [];
    //mysql_select_db($database_connvbsa, $connvbsa);
    $query_exist = 'Select * FROM tournament_scores where tourn_id = ' . $tournament_id;
    //echo("Exist Query " . $query_exist . "<br>");
    $result_exist = mysql_query($query_exist, $connvbsa) or die(mysql_error());
    $total_exist = $result_exist->num_rows;
    //echo("Existing " . $total_exist . "<br>");
    
    if($total_exist == 0)
    {
        $i = 0;
        foreach($newArray as $key => $row)
        {
            $player = explode(", ", $row);
            $round = explode(": ", $player[0]);
            $member_id = explode(": ", $player[2]);
            $rank = explode(": ", $player[3]);
            $time = explode(": ", $player[4]);
            if($round[1] == 1)
            {
                $sql_insert = "Insert into tournament_scores (
                  tourn_id, 
                  member_id, 
                  r_" . $total_players . "_position, 
                  ranknum,
                  r_" . $total_players . "_time)
                  Values (" . 
                  $tournament_id . ", " . 
                  $member_id[1] . ", " . 
                  ($i+1) . ", '" . 
                  $rank[1] . "', '" .
                  $time[1] . "')"; 
                  //echo($sql_insert . "<br>");
                  $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());           
                $i++;
            }
        }

        // add data for round 2
        if($max_move_count > 0)
        {
            $score_table_index = 0;
            $i = 0;
            $score_table = [];
            $query_score_table = 'Select * FROM tournament_scores where tourn_id = ' . $tournament_id;
            $result_score_table = mysql_query($query_score_table, $connvbsa) or die(mysql_error());
            $total_score_table = $result_score_table->num_rows;
            while($build_score_table = $result_score_table->fetch_assoc())
            {
                if($build_score_table['ranknum'] != '')
                {
                    $rank_num = $build_score_table['ranknum'];
                }
                else
                {
                    $rank_num = 0;
                }
                $score_table[$score_table_index] = "Round: 2, Index: " . $i . ", MemberID: " . $build_score_table['member_id'] . ", Rank: " . $rank_num . ", Time: " . $build_score_table['time_' . $total_players];
                $score_table_index++;
                $i++;
            }

            //echo("Existing List<br>");
            //echo("<pre>");
            //echo(var_dump($score_table));
            //echo("</pre>"); 

    
            $need_move = 0;
            for($c = 0; $c < $total_score_table; $c+=2)
            {
                $player_1 = explode(", ", $score_table[$c]);
                $player_2 = explode(", ", $score_table[$c+1]);
                $member_id_1 = explode(": ", $player_1[2]);
                $member_id_2 = explode(": ", $player_2[2]);
                $index_1 = explode(": ", $player_1[1]);
                $index_2 = explode(": ", $player_2[1]);
                $rank_1 = explode(": ", $player_1[3]);
                $rank_2 = explode(": ", $player_2[3]);
                
                if(($member_id_1[1] < 10000) && (!empty($rank_1[1])) && ($member_id_2[1] > 10000) && ($need_move < 8))
                {
                    $move_sql_1 = 'Update tournament_scores SET r_' . floor($total_players/2) . '_position = ' . floor(($index_1[1]/2)+1) . ' where member_id = ' . $member_id_1[1] . ' and tourn_id = ' . $tournament_id;
                    $update = mysql_query($move_sql_1, $connvbsa) or die(mysql_error());    
                    $need_move++;
                }
                if(($member_id_2[1] < 10000) && (!empty($rank_2[1])) && ($member_id_1[1] > 10000) && ($need_move < 8))
                {
                    $move_sql_2 = 'Update tournament_scores SET r_' . floor($total_players/2) . '_position = ' . floor(($index_2[1]/2)+1) . ' where member_id = ' . $member_id_2[1] . ' and tourn_id = ' . $tournament_id;
                    $update = mysql_query($move_sql_2, $connvbsa) or die(mysql_error()); 
                    $need_move++;
                }
            }
        }

        // add date data.
        $query_dates = 'Select * FROM tournaments left join calendar on calendar.tourn_id=tournaments.tourn_id where tournaments.tourn_id = ' . $tournament_id;
        //echo($query_dates . "<br>");
        $result_dates = mysql_query($query_dates, $connvbsa) or die(mysql_error());
        $build_dates = $result_dates->fetch_assoc();
        $start_date  = new DateTime($build_dates['startdate']);
        $finish_date  = new DateTime($build_dates['finishdate']);
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start_date, $interval, $finish_date->modify('+1 day'));
        $day_count = 0;
        foreach($period as $date)
        {
            $day_count++;
        }
        //echo("Days " . $day_count . "<br>");
        $query_tables = 'Select ClubTables FROM clubs where ClubTitle = "' . $build_dates['venue'] . '"';
        //echo($query_tables . "<br>");
        $result_tables = mysql_query($query_tables, $connvbsa) or die(mysql_error());
        $build_tables = $result_tables->fetch_assoc();
        $no_of_tables = $build_tables['ClubTables'];

        // set default number of tables
        if($no_of_tables == null)
        {
            $no_of_tables = 8;
        }
        $total_matches = ($total_players/2);
        
        //echo("No of Tables - " . $no_of_tables . "<br>");
        //echo("Tournament Size - " . $total_players . "<br>");
        //echo("Matches per day  Day 1 - " . $build_dates['matches_day_1'] . "<br>");
        //echo("Matches per day  Day 2 - " . $build_dates['matches_day_2'] . "<br>");
        //echo("Matches per day  Day 3 - " . $build_dates['matches_day_3'] . "<br>");
        //echo("Matches per day  Day 4 - " . $build_dates['matches_day_4'] . "<br>");
        //echo("Total Matches - " . ($build_dates['matches_day_1'] + $build_dates['matches_day_2'] + $build_dates['matches_day_3']) . "<br>");
        //echo("Total Matches - " . ($total_players) . "<br>");



/*        echo("No of Matches R 64 - " . $total_matches . "<br>");
        echo("No of Sessions R 64 - " . ceil($total_matches/$build_tables['ClubTables']) . "<br>");
        //$no_of_sessions = ceil($total_matches/$build_tables['ClubTables']);
        echo("No of Matches R 32 - " . ceil($total_matches/2) . "<br>");
        echo("No of Sessions R 32 - " . ceil(($total_matches/2)/$build_tables['ClubTables']) . "<br>");
        echo("No of Matches R 16 - " . ceil($total_matches/4) . "<br>");
        echo("No of Sessions R 16 - " . ceil(($total_matches/4)/$build_tables['ClubTables']) . "<br>");
        echo("No of Matches R 8 - " . ceil($total_matches/8) . "<br>");
        echo("No of Sessions R 8 - " . ceil(($total_matches/8)/$build_tables['ClubTables']) . "<br>");
        echo("No of Matches R 4 - " . ceil($total_matches/16) . "<br>");
        echo("No of Sessions R 4 - " . ceil(($total_matches/16)/$build_tables['ClubTables']) . "<br>");
        echo("No of Matches R 2 - " . ceil($total_matches/32) . "<br>");
        echo("No of Sessions R 2 - " . ceil(($total_matches/32)/$build_tables['ClubTables']) . "<br>");
        echo("No of Matches R 1 - " . ceil($total_matches/64) . "<br>");
        echo("No of Sessions R 1 - " . ceil(($total_matches/64)/$build_tables['ClubTables']) . "<br>");
*/
        //echo("<br>");
        $starttime = date($build_dates['time_' . $total_players]);
        $day = 1;
        $index = 0;
        foreach($period as $date)
        {
            //echo("Day (" . ($day) . ") " . $date->format("l") . "<br>");
            if($day == 1)
            {
                $day_1 = $date->format("l");
            }
            if($day == 2)
            {
                $day_2 = $date->format("l");
            }
            if($day == 3)
            {
                $day_3 = $date->format("l");
            }
            if($day == 4)
            {
                $day_4 = $date->format("l");
            }
            $day++;
        }

        //echo("No of Matches R 64 - " . $total_matches . "<br>");
        //echo("Index - " . $index . "<br>");
        // insert placeholders for draw dates
        for($r = 0; $r < ($build_dates['matches_day_1']); $r++)
        {
            $sql_insert = "Insert into tournament_draw_dates (
              match_index,
              tourn_id, 
              matches_day_1)
              Values (" . 
              ($index+1) . ", " . 
              $tournament_id . ", '" . 
              ($r+1) . "_1'" . ")"; 
              //echo($sql_insert . "<br>");
              $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());  
              $index++;
              //$starttime = date('H:i:s',strtotime('+2 hour', strtotime($starttime))); 
              $sql_insert = "Insert into tournament_draw_dates (
              match_index, 
              tourn_id, 
              matches_day_1)
              Values (" . 
              ($index+1) . ", " . 
              $tournament_id . ", '" . 
              ($r+1) . "_2'" . ")"; 
              //echo($sql_insert . "<br>");
              $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());  
              $starttime = date('H:i:s',strtotime('+2 hour', strtotime($starttime))); 
              $index++;
        }
        //echo("R " . $r . "<br>");
        //$starttime = date($build_dates['time_' . $total_players]);
        for($r = ($build_dates['matches_day_1']); $r < (($build_dates['matches_day_2']+$build_dates['matches_day_1'])); $r++)
        {
            $sql_insert = "Insert into tournament_draw_dates (
              match_index, 
              tourn_id, 
              matches_day_2)
              Values (" . 
              ($index+1) . ", " . 
              $tournament_id . ", '" . 
              ($r+1) . "_1'" . ")"; 
              //echo($sql_insert . "<br>");
              $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error()); 
              $index++; 
              $sql_insert = "Insert into tournament_draw_dates (
              match_index, 
              tourn_id, 
              matches_day_2)
              Values (" . 
              ($index+1) . ", " . 
              $tournament_id . ", '" . 
              ($r+1) . "_2'" . ")"; 
              //echo($sql_insert . "<br>");
              $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());  
              $starttime = date('H:i:s',strtotime('+2 hour', strtotime($starttime))); 
              $index++;
        }
        //$starttime = date($build_dates['time_' . $total_players]);
        for($r = (($build_dates['matches_day_2']+$build_dates['matches_day_1'])); $r < (($build_dates['matches_day_2']+$build_dates['matches_day_1']+$build_dates['matches_day_3'])); $r++)
        {
            $sql_insert = "Insert into tournament_draw_dates (
              match_index, 
              tourn_id, 
              matches_day_3)
              Values (" . 
              ($index+1) . ", " . 
              $tournament_id . ", '" . 
              ($r+1) . "_1'" . ")"; 
              //echo($sql_insert . "<br>");
              $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error()); 
              $index++;
              $sql_insert = "Insert into tournament_draw_dates (
              match_index, 
              tourn_id, 
              matches_day_3)
              Values (" . 
              ($index+1) . ", " . 
              $tournament_id . ", '" . 
              ($r+1) . "_2'" . ")"; 
              //echo($sql_insert . "<br>");
              $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error()); 
              $starttime = date('H:i:s',strtotime('+2 hour', strtotime($starttime))); 
              $index++;
        }
        //$starttime = date($build_dates['time_' . $total_players]);
        for($r = (($build_dates['matches_day_3']+$build_dates['matches_day_2']+$build_dates['matches_day_1'])); $r < (($build_dates['matches_day_2']+$build_dates['matches_day_1']+$build_dates['matches_day_3']+$build_dates['matches_day_4'])); $r++)
        {
            $sql_insert = "Insert into tournament_draw_dates (
              match_index, 
              tourn_id, 
              matches_day_4)
              Values (" . 
              ($index+1) . ", " . 
              $tournament_id . ", '" . 
              ($r+1) . "_1'" . ")"; 
              //echo($sql_insert . "<br>");
              $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error()); 
              $index++;
              $sql_insert = "Insert into tournament_draw_dates (
              match_index, 
              tourn_id, 
              matches_day_4)
              Values (" . 
              ($index+1) . ", " . 
              $tournament_id . ", '" . 
              ($r+1) . "_2'" . ")"; 
              //echo($sql_insert . "<br>");
              $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error()); 
              $starttime = date('H:i:s',strtotime('+2 hour', strtotime($starttime))); 
              $index++;
        }

        // add draw dates for day 1
        //$starttime = date($build_dates['time_' . $total_players]);
        $starttime = date($build_dates['time_day_1']);
        $x = 1;
        for($r = 0; $r < ($build_dates['matches_day_1']); $r++)
        {
            //echo("R mod " . (($x)%$build_tables['ClubTables']) . "<br>");
            $sql_insert = "Update tournament_draw_dates Set
            time = '" . $starttime  . "',
            day = '" . $day_1  . "', 
            day_1 = '" . $day_1  . "'
            Where tourn_id = " . 
            $tournament_id . " and  matches_day_1 = '" . ($r+1) . "_1'"; 
            //echo($sql_insert . "<br>");
            $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());  
            $index++; 
            $sql_insert = "Update tournament_draw_dates Set
            time = '" . $starttime  . "',
            day = '" . $day_1  . "', 
            day_1 = '" . $day_1  . "'
            Where tourn_id = " . 
            $tournament_id . " and  matches_day_1 = '" . ($r+1) . "_2'"; 
            //echo($sql_insert . "<br>");
            $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error()); 
            $index++;
            if(($x%$no_of_tables) === 0)
            {
                $starttime = date('H:i:s',strtotime('+2 hour', strtotime($starttime)));
            } 
            $x++;
        }
        // add draw dates for day 2
        //$starttime = date($build_dates['time_' . $total_players]);
        $starttime = date($build_dates['time_day_2']);
        $x = 1;
        for($r = ($build_dates['matches_day_1']); $r < (($build_dates['matches_day_2']+$build_dates['matches_day_1'])); $r++)
        {
            //echo("R mod " . (($x)%$build_tables['ClubTables']) . "<br>");
            $sql_insert = "Update tournament_draw_dates Set
            time = '" . $starttime  . "',
            day = '" . $day_2  . "', 
            day_2 = '" . $day_2  . "'
            Where tourn_id = " . 
            $tournament_id . " and  matches_day_2 = '" . ($r+1) . "_1'"; 
            //echo($sql_insert . "<br>");
            $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());   
            $sql_insert = "Update tournament_draw_dates Set
            time = '" . $starttime  . "',
            day = '" . $day_2  . "', 
            day_2 = '" . $day_2  . "'
            Where tourn_id = " . 
            $tournament_id . " and  matches_day_2 = '" . ($r+1) . "_2'"; 
            //echo($sql_insert . "<br>");
            $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error()); 
            if(($x%$no_of_tables) === 0)
            {
                $starttime = date('H:i:s',strtotime('+2 hour', strtotime($starttime)));
            } 
            $x++;
        }
        // add draw dates for day 3
        //$starttime = date($build_dates['time_' . $total_players]);
        $starttime = date($build_dates['time_day_3']);
        $x = 1;
        for($r = (($build_dates['matches_day_2']+$build_dates['matches_day_1'])); $r < (($build_dates['matches_day_2']+$build_dates['matches_day_1']+$build_dates['matches_day_3'])); $r++)
        {
            //echo("R mod " . (($x)%$build_tables['ClubTables']) . "<br>");
            $sql_insert = "Update tournament_draw_dates Set
            time = '" . $starttime  . "',
            day = '" . $day_3  . "', 
            day_3 = '" . $day_3  . "'
            Where tourn_id = " . 
            $tournament_id . " and  matches_day_3 = '" . ($r+1) . "_1'"; 
            //echo($sql_insert . "<br>");
            $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());   
            $sql_insert = "Update tournament_draw_dates Set
            time = '" . $starttime  . "',
            day = '" . $day_3  . "', 
            day_3 = '" . $day_3  . "'
            Where tourn_id = " . 
            $tournament_id . " and  matches_day_3 = '" . ($r+1) . "_2'"; 
            //echo($sql_insert . "<br>");
            $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error()); 
            if(($x%$no_of_tables) === 0)
            {
                $starttime = date('H:i:s',strtotime('+2 hour', strtotime($starttime)));
            } 
            $x++;
        }

        // add draw dates for day 4
        //$starttime = date($build_dates['time_' . $total_players]);
        $starttime = date($build_dates['time_day_4']);
        $x = 1;
        for($r = (($build_dates['matches_day_3']+$build_dates['matches_day_2']+$build_dates['matches_day_1'])); $r < (($build_dates['matches_day_2']+$build_dates['matches_day_1']+$build_dates['matches_day_3']+$build_dates['matches_day_4'])); $r++)
        {
            //echo("R mod " . (($x)%$build_tables['ClubTables']) . "<br>");
            $sql_insert = "Update tournament_draw_dates Set
            time = '" . $starttime  . "',
            day = '" . $day_4  . "', 
            day_4 = '" . $day_4  . "'
            Where tourn_id = " . 
            $tournament_id . " and  matches_day_4 = '" . ($r+1) . "_1'"; 
            //echo($sql_insert . "<br>");
            $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());   
            $sql_insert = "Update tournament_draw_dates Set
            time = '" . $starttime  . "',
            day = '" . $day_4  . "', 
            day_4 = '" . $day_4  . "'
            Where tourn_id = " . 
            $tournament_id . " and  matches_day_4 = '" . ($r+1) . "_2'"; 
            //echo($sql_insert . "<br>");
            $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error()); 
            if(($x%$no_of_tables) === 0)
            {
                $starttime = date('H:i:s',strtotime('+2 hour', strtotime($starttime)));
            } 
            $x++;
        }

        //echo("List updated 1<br>");
        //header("Location: http://vbsa.org.au/Admin_Tournaments/tournament_draw.php?tourn_id=" . $tournament_id);
        //header("Location: http://vbsa.cpc-world.com/Admin_Tournaments/tournament_draw.php?tourn_id=" . $tournament_id);
        //header("Location: http://172.16.10.32/VBSA_Siteground/Admin_Tournaments/tournament_draw.php?tourn_id=" . $tournament_id);
    }
    else
    {
        echo("Tournament ID " . $_POST['tournament'] . " already has data.<br><br><br>");
    }
    header("Location: http://172.16.10.32/VBSA_Siteground/Admin_Tournaments/tournament_draw.php?tourn_id=" . $tournament_id);
    //header("Location: http://vbsa.cpc-world.com/Admin_Tournaments/tournament_draw.php?tourn_id=" . $tournament_id);
}
?>
