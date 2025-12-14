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

?>
<script type='text/javascript'>

    function GetTournament(sel) {
        var tournament_id = sel.options[sel.selectedIndex].value;
        document.getElementById("tournament").value = tournament_id;
        document.tournament_draw.submit();
    }

</script>
<?php
// change to $_GET when automated.
$tournament_id = '';
//$number_of_records = 0;

if(isset($_GET['tourn_id']))
{
    $tournament_id = $_GET['tourn_id']; 

    // delete existing records for testing
    //$query_delete = 'Delete from tournament_scores where tourn_id = ' . $tournament_id;
    //echo($query_delete . "<br>");
    //$result_delete = mysql_query($query_delete, $connvbsa) or die(mysql_error());

    //echo("Tournament ID " . $tournament_id . "<br><br><br>");
    // get any existing results
    $query_select = 'Select * from tournament_scores where tourn_id = ' . $tournament_id;
    $result_select = mysql_query($query_select, $connvbsa) or die(mysql_error());
    $number_of_records = $result_select->num_rows;

}

if($number_of_records == 0) // if no results, create new dataset
{

    // get tournament name
    $query_tourn_name = 'Select *, tournaments.tourn_type as type FROM vbsa3364_vbsa2.tournaments LEFT JOIN calendar ON tournaments.tourn_id = calendar.tourn_id where tournaments.tourn_id = ' . $tournament_id;
    //echo($query_tourn_name . "<br>");
    $result_tourn_name = mysql_query($query_tourn_name, $connvbsa) or die(mysql_error());
    $build_tourn_name = $result_tourn_name->fetch_assoc();
    $tourn_type = $build_tourn_name['type'];
    $move_to_R2 = $build_tourn_name['move_top_seed'];

    // ---------------  get original players in team entries table ---------------------------------------------------
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
    //echo("Original List in Ranking Order (smallest number is the highest ranked)<br>");

    $original_index = 0;
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

    //  get ranked players in team entries table 
    // create ranked array
    if($tourn_type == 'Billiards')
    {
    $query_tourn_ranked = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_Billiards on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournament_id . ' and MemberID != ""  and ranknum != 0 Order by ranknum = 0, ranknum ASC';
    }
    else if($tourn_type == 'Snooker')
    {
    $query_tourn_ranked = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_S_open_tourn on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournament_id . ' and MemberID != ""  and ranknum != 0 Order by ranknum = 0, ranknum ASC';
    }
    //echo("Ranked SQL " . $query_tourn_ranked . "<br>");
    $result_tourn_ranked = mysql_query($query_tourn_ranked, $connvbsa) or die(mysql_error());
    $total_ranked = $result_tourn_ranked->num_rows;
    $ranked = [];
    $ranked_r2 = [];
    //echo("Total Players in tourn_entry with rank points " . $total_ranked . "<br>");
    $ranked_index = 0;
    while($build_players = $result_tourn_ranked->fetch_assoc())
    {
        $ranked[$ranked_index] = "Round: 1, Index: " . ($ranked_index+1) . ", MemberID: " . $build_players['MemberID'] . ", Rank: " . $build_players['ranknum'] . ", Time: " . $build_players['time_' . $total_players];
        $ranked_r2[$ranked_index] = "Round: 2, Index: " . ($ranked_index+1) . ", MemberID: " . $build_players['MemberID'] . ", Rank: " . $build_players['ranknum'] . ", Time: " . $build_players['time_' . $total_players];
        $ranked_index++;
    }

    $odd = [];
    $even = [];
    $bye = [];
    $playersArr = [];

    //$i = 1;
    $bye_index = 10000;
    
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
        if($build_players['ranknum'] == null)
        {
            $rank_num = 0;
        }
        else
        {
            $rank_num = $build_players['ranknum'];
        }
        $zero[$zero_index] = "Round: 1, Index: " . ($zero_index+1) . ", MemberID: " . $build_players['MemberID'] . ", Rank: " . $rank_num . ", Time: " . $build_players['time_' . $total_players];
        $zero_index++;
    }

    $arrCount = ($original_index);
    //echo("Array Count " . $original_index . "<br>");
    //echo("Total Original Players " . count($original) . "<br>");
    //echo("Total Ranked Players " . count($ranked) . "<br>");
    //echo("Total Non Ranked Players " . count($zero) . "<br>");
    //echo("Total Players " . $total_players . "<br>");
    //echo("Total Count " . $arrCount . "<br>");
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

    for($x = 0; $x < $byes_required; $x++)
    {
        $bye[] = "Round: 1, Index: " . ($x+200) . ", MemberID: " . ($bye_index+100+$x) . ", Rank: 0";
    }
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
            //echo('Moves ' . $max_move_count . '<br>');
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
    $even = array_reverse($even);
    
    if($max_move_count == 0)
    {
        $newArray = array_merge($odd, $zero, $bye, $even);
    }
    else
    {
        $newArray = array_merge($odd, $bye, $even);
    }
    
    $newArray = array_merge($odd, $bye, $even);
    
    $query_exist = 'Select * FROM tournament_scores where tourn_id = ' . $tournament_id;
    //echo("Exist Query " . $query_exist . "<br>");
    $result_exist = mysql_query($query_exist, $connvbsa) or die(mysql_error());
    $total_exist = $result_exist->num_rows;

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

            // add date data.
            $query_dates = 'Select * FROM tournaments left join calendar on calendar.tourn_id=tournaments.tourn_id where tournaments.tourn_id = ' . $tournament_id;
            //echo($query_dates . "<br>");
            $result_dates = mysql_query($query_dates, $connvbsa) or die(mysql_error());
            $build_dates = $result_dates->fetch_assoc();
            $start_date  = new DateTime($build_dates['startdate']);
            $finish_date  = new DateTime($build_dates['finishdate']);
            $interval = new DateInterval('P1D');
            $period = new DatePeriod($start_date, $interval, $finish_date->modify('+1 day'));
            foreach($period as $date)
            {
                //echo("Playing Days:" . $date->format("l") . "<br>");
            }
           
            //echo("No of Players " . $original_index . "<br>");
            //echo("Tournament Size " . $total_players . "<br>");
            $matches = $build_dates['matches_per_day'];
            //echo("Matches per Day " . $matches . "<br>");
            //echo("Venue " . $build_dates['venue'] . "<br>");

            $query_tables = 'Select ClubTables FROM clubs where ClubTitle = "' . $build_dates['venue'] . '"';
            //echo($query_tables . "<br>");
            $result_tables = mysql_query($query_tables, $connvbsa) or die(mysql_error());
            $build_tables = $result_tables->fetch_assoc();
            //echo("No of Tables " . $build_tables['ClubTables'] . "<br>");
            //echo("<br>");
            $rounds = $total_players;
            do{
                //echo("Rounds " . $rounds . "<br>");
                $best_of = $build_dates['best_of_' . $rounds];
                $start_time = $build_dates['time_' . $rounds];
                $average_time = $build_dates['ave_time_best_of_' . $rounds];
                //echo("Best of " . $rounds . ": " . $best_of . "<br>");
                //echo("Start Time " . $rounds . ": " . $start_time . "<br>");
                //echo("Average Time " . $rounds . ": " . $average_time . "<br>");
                //echo("<br>");
                $rounds = ($rounds/2);
            } while($rounds > 1);


            $query_dates = 'Select * FROM tournaments left join calendar on calendar.tourn_id=tournaments.tourn_id where tournaments.tourn_id = ' . $tournament_id;
            //echo($query_dates . "<br>");
            $result_dates = mysql_query($query_dates, $connvbsa) or die(mysql_error());
            $total_dates = $result_dates->num_rows;
            $build_dates = $result_dates->fetch_assoc();
            $query_score_table = 'Select * FROM tournament_scores where tourn_id = ' . $tournament_id;
            $result_score_table = mysql_query($query_score_table, $connvbsa) or die(mysql_error());
            $rounds = $total_players;
            while($build_score_table = $result_score_table->fetch_assoc())
            {
                do {
                    $sql_add_date = 'Update tournament_scores SET r_' . floor($rounds) . '_time = "09:00:00", r_' . floor($rounds) . '_day = "' . date('l', strtotime($build_dates['startdate'])) . '" where member_id = ' . $build_score_table['member_id'] . ' and tourn_id = ' . $tournament_id;
                    $update = mysql_query($sql_add_date, $connvbsa) or die(mysql_error()); 
                    $rounds = $rounds/2;
                } while ($rounds > 1);
                $rounds = $total_players;
            }
        }
    }
}
header("Location: http://172.16.10.32/VBSA_Siteground/Admin_Tournaments/tournament_draw.php?tourn_id=" . $tournament_id);
?>
