<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

error_reporting(0);

if(isset($_GET['tournament']))
{
    $tournament_id = $_GET['tournament'];

    // delete any existing results
    $query_delete = 'Delete from tournament_scores where tourn_id = ' . $tournament_id;
    $result_delete = mysql_query($query_delete, $connvbsa) or die(mysql_error());

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
    //echo("SQL " . $query_tourn_orig . "<br>");
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

    //echo("<pre>");
    //echo(var_dump($original)); // ok
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

    // ---------------  get ranked players in team entries table ---------------------------------------------------
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
    //$ranked_r2 = [];
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
    
    // -------------  get non ranked players in team entries table ---------------------------------------------------
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
        //$zero_r2[$zero_index] = "Round: 2, Index: " . ($zero_index+1) . ", MemberID: " . $build_players['MemberID'] . ", Rank: " . $rank_num . ", Time: " . $build_players['time_' . $total_players];
        $zero_index++;
    }

    $arrCount = ($original_index);
    /*
    echo("Array Count " . $original_index . "<br>");
    echo("Total Original Players " . count($original) . "<br>");
    echo("Total Ranked Players " . count($ranked) . "<br>");
    echo("Total Non Ranked Players " . count($zero) . "<br>");
    echo("Total Players " . $total_players . "<br>");
    echo("Total Count " . $arrCount . "<br>");
    echo("Specified Move Count " . $move_to_R2 . "<br>");
    */
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

    $x = 0;
    $y = 0;
    $z = 0;
    $test = 0;
    for($i = 0; $i < $arrCount; $i++)
    {
        if(($i < $max_move_count) && ($max_move_count > 0)) // 6
        {
            if($i % 2 == 0) 
            {
                if($ranked[$y] != '')
                {
                    $even[] = $ranked[$y];
                    $y++;
                    $even[] = "Round: 1, Index: " . ($x+200) . ", MemberID: " . ($bye_index+100+$x) . ", Rank: 0";
                    $even_r2[] = $ranked_r2[$y];
                    $y++;
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
                    $y++;
                    $odd[] = "Round: 1, Index: " . ($x+200) . ", MemberID: " . ($bye_index+100+$x) . ", Rank: 0";
                    $odd_r2[] = $ranked_r2[$y];
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
    //sort($odd);
    

    echo("List of Even 2 <br>");
    echo("<pre>");
    echo(var_dump($even_r2));
    echo("</pre>"); 

    echo("List of Odd 2 <br>");
    echo("<pre>");
    echo(var_dump($odd_r2));
    echo("</pre>"); 


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
    
    $newArray = array_merge($odd, $bye, $even);
    //$newArrayR2 = array_merge($odd_r2, $even_r2);
/*
    echo("Combined List to display<br>");
    echo("<pre>");
    echo(var_dump($newArray));
    echo("</pre>"); 
*/
    // check if players already saved in the scoring table
    $query_exist = 'Select * FROM vbsa3364_vbsa2.tournament_scores where tourn_id = ' . $tournament_id;
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
        /*
        foreach($newArrayR2 as $key => $row)
        {
            $player = explode(", ", $row);
            $round = explode(": ", $player[0]);
            $member_id = explode(": ", $player[2]);
            $rank = explode(": ", $player[3]);
            $index = explode(": ", $player[1]);
            if($round[1] == 2)
            {
                $max = ($total_players/2);
                $sql_update = "Update tournament_scores Set r_" . ($total_players/2) . "_position = " . $index[1] . " Where member_id = " . $member_id[1];

                echo($sql_update . "<br>");
                $update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
            }
        }
        */
        echo("List updated");
    }
    //else
    //{
    //    echo("Tournament ID " . $_POST['tournament'] . " already has data.<br><br><br>");
    //}
}
?>
