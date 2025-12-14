<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

include '../vbsa_online_scores/header_admin.php';

//error_reporting(0);

if(isset($_GET['tourn_id']))
{
    $tourn_id = $_GET['tourn_id'];
    $idMap = [
        1 => [
            1 => [
                '3_1', // Ali Daryab Shafayi
                '2_1', // Ali Daryab Shafayi
                '1_1' // Ali Daryab Shafayi
            ],
            0 => [
                '2_2', // Bye
                '1_2', // Bye
                '1_3', // Bye
                '1_4', // Bye
            ],
        ],

        33 => [
            33 => [
                '2_3', // Luke Cody
                '1_5', // Luke Cody
            ],
            0 => [
                '1_6', // Bye
            ],
        ],

        32 => [
            32 => [
                '2_4', // Kevin Stone
                '1_7', // Kevin Stone
            ],
            0 => [
                '1_8', // Bye
            ],
        ],

        17 => [
            17 => [
                '2_5', // Pushpinder Brar
                '1_9', // Pushpinder Brar
            ],
            0 => [
                '1_10', // Bye
            ],
        ],

        48 => [
            48 => [
                '1_11', // James Bartolo
            ],
        ],

        49 => [
            49 => [
                '1_12', // Philip Vassallo
            ], 
        ],

        16 => [
            16 => [
                '3_4', // Shane Logan
                '2_7', // Shane Logan
                '1_13' // Shane Logan
            ],
            0 => [
                '2_8', // Bye
                '1_14', // Bye
                '1_15', // Bye
                '1_16', // Bye
            ],
        ],

        9 => [
            9 => [
                '3_5', // Livon Kurda
                '2_9', // Livon Kurda
                '1_17', // Livon Kurda
            ], 
            0 => [
                '2_10', // Bye
                '1_18', // Bye
                '1_19', // Bye
                '1_20', // Bye
            ],
        ],

        41 => [
            41 => [
                '2_11', // Richard Ball
                '1_21', // Richard Ball
            ],
            0 => [
                '1_22', // Bye
            ],
        ],

        24 => [
            24 => [
                '2_12', // John McAndrew
                '1_23', // John McAndrew
            ],
            0 => [
                '1_24', // Bye
            ],
        ],

        8 => [
            8 => [
                '3_7', // Masoud Alikhail
                '2_13', // Masoud Alikhail
                '1_25', // Masoud Alikhail
            ], 
            0 => [
                '2_14', // Bye
                '1_26', // Bye
                '1_27', // Bye
                '1_28', // Bye
            ],
        ],

        40 => [
            49 => [
                '2_15', // Henry Chetcuti
                '1_29', // Henry Chetcuti
            ],
            0 => [
                '1_30', // Bye
            ],
        ],

        25 => [
            25 => [
                '2_16', // John Walmsley
                '1_31', // John Walmsley
            ],
            0 => [
                '1_32', // Bye
            ],
        ],

        28 => [
            28 => [
                '2_17', // Baqer Ali
                '1_33', // Baqer Ali
            ],
            0 => [
                '1_34', // Bye
            ],
        ],

        37 => [
            37 => [
                '2_18', // James Cockburn
                '1_35', // James Cockburn
            ],
            0 => [
                '1_36', // Bye
            ],
        ],

        5 => [
            5 => [
                '3_10', // Luv Boricha 
                '2_19', // Luv Boricha 
                '1_37', // Luv Boricha 
            ], 
            0 => [
                '2_20', // Bye
                '1_38', // Bye
                '1_39', // Bye
                '1_40', // Bye
            ],
        ],

        12 => [
            12 => [
                '3_11', // Paul Thomerson  
                '2_21', // Paul Thomerson  
                '1_41', // Paul Thomerson  
            ], 
            0 => [
                '2_22', // Bye
                '1_42', // Bye
                '1_43', // Bye
                '1_44', // Bye
            ],
        ],

        21 => [
            21 => [
                '2_23', // Darryl Tippett
                '1_45', // Darryl Tippett
            ],
            0 => [
                '1_46', // Bye
            ],
        ],

        53 => [
            53 => [
                '1_47', // Swami Sivaramakrishnan
            ], 
        ],

        44 => [
            44 => [
                '1_48', // Rahul Jhamb
            ], 
        ],

        13 => [
            13 => [
                '3_13', // Alec Spyrou  
                '2_25', // Alec Spyrou  
                '1_49', // Alec Spyrou  
            ], 
            0 => [
                '2_26', // Bye
                '1_50', // Bye
                '1_51', // Bye
                '1_52', // Bye
            ],
        ],

        20 => [
            20 => [
                '2_27', // Sean Dempsey
                '1_53', // Sean Dempsey
            ],
            0 => [
                '1_54', // Bye
            ],
        ],

        52 => [
            52 => [
                '1_55', // Nish Lekhi
            ], 
        ],

        45 => [
            45 => [
                '1_56', // Warren Hayden
            ], 
        ],

        4 => [
            4 => [
                '3_15', // Sumit Abrol  
                '2_29', // Sumit Abrol  
                '1_57', // Sumit Abrol  
            ], 
            0 => [
                '2_30', // Bye
                '1_58', // Bye
                '1_59', // Bye
                '1_60', // Bye
            ],
        ],

        36 => [
            36 => [
                '2_31', // Michael Mihaljevic
                '1_61', // Michael Mihaljevic
            ],
            0 => [
                '1_62', // Bye
            ],
        ],

        29 => [
            29 => [
                '2_32', // Ahmad Alikhail
                '1_63', // Ahmad Alikhail
            ],
            0 => [
                '1_64', // Bye
            ],
        ],

        3 => [
            3 => [
                '3_17', // Marc Fridman  
                '2_33', // Marc Fridman  
                '1_65', // Marc Fridman  
            ], 
            0 => [
                '2_34', // Bye
                '1_66', // Bye
                '1_67', // Bye
                '1_68', // Bye
            ],
        ],

        35 => [
            35 => [
                '2_35', // Kelvin Small
                '1_69', // Kelvin Small
            ],
            0 => [
                '1_70', // Bye
            ],
        ],

        30 => [
            30 => [
                '2_36', // Jian (Tony) Li
                '1_71', // Jian (Tony) Li
            ],
            0 => [
                '1_72', // Bye
            ],
        ],

        14 => [
            14 => [
                '3_19', // Ray Rogers  
                '2_37', // Ray Rogers  
                '1_73', // Ray Rogers  
            ], 
            0 => [
                '2_38', // Bye
                '1_74', // Bye
                '1_75', // Bye
                '1_76', // Bye
            ],
        ],

        19 => [
            19 => [
                '2_39', // Jahanzaib Haroon
                '1_77', // Jahanzaib Haroon
            ],
            0 => [
                '1_78', // Bye
            ],
        ],

        46 => [
            46 => [
                '1_79', // Bashir Hussain
            ], 
        ],

        51 => [
            51 => [
                '1_80', // Mark Crennan
            ], 
        ],

        11 => [
            11 => [
                '3_21', // Tony Fridman  
                '2_41', // Tony Fridman  
                '1_81', // Tony Fridman  
            ], 
            0 => [
                '2_42', // Bye
                '1_82', // Bye
                '1_83', // Bye
                '1_84', // Bye
            ],
        ],

        22 => [
            22 => [
                '2_43', // Carlos Barrocas
                '1_85', // Carlos Barrocas
            ],
            0 => [
                '1_86', // Bye
            ],
        ],

        43 => [
            43 => [
                '1_87', // Ryan Neary
            ], 
        ],

        54 => [
            54 => [
                '1_88', // Pulkit Sharma
            ], 
        ],

        6 => [
            6 => [
                '3_23', // Henry Lau  
                '2_45', // Henry Lau  
                '1_89', // Henry Lau  
            ], 
            0 => [
                '2_46', // Bye
                '1_90', // Bye
                '1_91', // Bye
                '1_92', // Bye
            ],
        ],

        38 => [
            38 => [
                '2_47', // Mohammad Sharif Behroz
                '1_93', // Mohammad Sharif Behroz
            ],
            0 => [
                '1_94', // Bye
            ],
        ],

        31 => [
            31 => [
                '2_64', // Ying Guo
                '1_95', // Ying Guo
            ],
            0 => [
                '1_96', // Bye
            ],
        ],

        27 => [
            27 => [
                '2_48', // Paul James
                '1_97', // Paul James
            ],
            0 => [
                '1_98', // Bye
            ],
        ],

        7 => [
            7 => [
                '3_25', // Scott Preston   
                '2_50', // Scott Preston   
                '1_99', // Scott Preston   
            ], 
            0 => [
                '2_51', // Bye
                '1_100', // Bye
                '1_101', // Bye
                '1_102', // Bye
            ],
        ],

        39 => [
            39 => [
                '2_52', // Sanjay Kumar
                '1_103', // Sanjay Kumar
            ],
            0 => [
                '1_104', // Bye
            ],
        ],

        26 => [
            26 => [
                '2_53', // Alex Bruce
                '1_105', // Alex Bruce
            ],
            0 => [
                '1_106', // Bye
            ],
        ],

        10 => [
            10 => [
                '3_27', // Brendon Lang   
                '2_54', // Brendon Lang   
                '1_107', // Brendon Lang   
            ], 
            0 => [
                '2_55', // Bye
                '1_108', // Bye
                '1_109', // Bye
                '1_110', // Bye
            ],
        ],

        23 => [
            23 => [
                '2_55', // Kathy Howden
                '1_111', // Kathy Howden
            ],
            0 => [
                '1_112', // Bye
            ],
        ],

        55 => [
            55 => [
                '1_113', // Theo Tsaikos
            ], 
        ],

        42 => [
            42 => [
                '1_114', // David Heath
            ], 
        ],

        15 => [
            15 => [
                '3_29', // Ben Leung   
                '2_58', // Ben Leung   
                '1_115', // Ben Leung   
            ], 
            0 => [
                '2_59', // Bye
                '1_116', // Bye
                '1_117', // Bye
                '1_118', // Bye
            ],
        ],

        18 => [
            18 => [
                '2_60', // Shabbir Badshah
                '1_119', // Shabbir Badshah
            ],
            0 => [
                '1_120', // Bye
            ],
        ],

        47 => [
            47 => [
                '1_121', // Neil Maclachlan
            ], 
        ],

        50 => [
            50 => [
                '1_122', // Graham Wilson
            ], 
        ],

        2 => [
            2 => [
                '3_31', // Bassam Elbelli  
                '2_61', // Bassam Elbelli   
                '1_123', // Bassam Elbelli   
            ], 
            0 => [
                '2_62', // Bye
                '1_124', // Bye
                '1_125', // Bye
                '1_126', // Bye
            ],
        ],

        34 => [
            34 => [
                '2_63', // Adriam Hung
                '1_127', // Adriam Hung
            ],
            0 => [
                '1_128', // Bye
            ],
        ],
    ];

    // delete any existing players
    $query_delete_players = 'Delete from tournament_players where tourn_id = ' . $tourn_id;
    $result_delete_players = mysql_query($query_delete_players, $connvbsa) or die(mysql_error());

    // delete any existing scores
    $query_delete_scores = 'Delete from tournament_scores where tourn_id = ' . $tourn_id;
    $result_delete_scores = mysql_query($query_delete_scores, $connvbsa) or die(mysql_error());

    $sql_players = "Select 
        ROW_NUMBER() OVER (
            ORDER BY CASE WHEN rt.ranknum IS NULL OR rt.ranknum = 0 THEN 1 ELSE 0 END, rt.ranknum ASC
        ) AS row_num,
        te.tourn_memb_id,
        m.FirstName,
        m.LastName,
        te.ranked,
        te.rank_pts,
        te.seed,
        rt.ranknum
    FROM tourn_entry te
    LEFT JOIN members m 
        ON m.memberID = te.tourn_memb_id
    LEFT JOIN rank_S_open_tourn rt
        ON m.MemberID = rt.memb_id
    WHERE te.tournament_number = '$tourn_id' Order By row_num";

	//echo($sql_players . "<br>");
	$result_players = mysql_query($sql_players, $connvbsa) or die(mysql_error());
	$no_of_players = $result_players->num_rows;
    $player_count = 0;
    while($build_table = $result_players->fetch_assoc())
    {
        //$fullname = ($build_table['FirstName'] . " " . $build_table['LastName']);
        $sql = "Insert INTO tournament_scores (tourn_id, member_id) Values (" . $tourn_id . ", " . $build_table['tourn_memb_id'] . ")";
        //echo($sql . "<br>");
        $update = mysql_query($sql, $connvbsa) or die(mysql_error());
        $player_count++;
    }
    
    for($i = ($player_count+1); $i <= 128; $i++)
    {
        //echo("Add bye " . $i . "<br>");

        $sql = "Insert INTO tournament_scores (tourn_id, member_id) Values (" . $tourn_id . ", " . (10000+$i) . ")";
        //echo($sql . "<br>");
        $update = mysql_query($sql, $connvbsa) or die(mysql_error());
    }
    

    mysql_data_seek($result_players, 0);
	$bye_index = ($player_count+1);
    $player_index = 0;
	while($build_table = $result_players->fetch_assoc())
	{
        $rowNum = $build_table['row_num'];
        $fullname = ($build_table['FirstName'] . " " . $build_table['LastName']);
        if($build_table['ranknum'] != '')
        {
            $rank = $build_table['ranknum'];
        }
        else
        {
            $rank = 0;
        }
        foreach ($idMap as $mainKey => $groups) {
            if($mainKey == $rowNum)
            {
                foreach ($groups as $subKey => $values) {
                    foreach ($values as $value) {
                        //echo('Row No ' . $rowNum . ', Key ' . $mainKey . ", Sub Key " . $subKey . ", Value " . $value . "<br>");
                        //$player_index++;
                        $map_data = explode("_", $value);
                        $round = $map_data[0];
                        if($round == 3)
                        {
                            $player_index++;
                        }
                        $position = $map_data[1];
                        if($subKey == 0)
                        {
                            $memberID = (10000+$bye_index);
                            $rank = 0;
                            $fullname = 'Bye';
                            $bye_index++;
                        } 
                        else
                        {
                            $memberID = $build_table['tourn_memb_id'];
                        }  
                        $sql_3 = "Insert INTO tournament_players (draw_pos, round_no, tourn_id, ranknum, memb_id, fullname) Values (" . $position . ", " . $round . ", " . $tourn_id . ", " . $rank . ", " . $memberID . ", '" . $fullname . " (" . $value . ")" . "')";
                        //echo($sql_3 . "<br>");
                        $update = mysql_query($sql_3, $connvbsa) or die(mysql_error());
                    }
                }
            }
        }
	}
}

$query_tourn = 'Select * FROM tournament_scores Where tourn_id = ' . $tourn_id . ' '; 
//echo("SQL Original " . $query_tourn_orig . "<br>");
$player_index = 0;
$result_tourn = mysql_query($query_tourn, $connvbsa) or die(mysql_error());
$tourn_size = $result_tourn->num_rows;
//echo("Tourn Size " . $tourn_size . "<br>");


//echo($player_index . "<br>");
$i = 0;
$query_tourn_orig = 'Select * FROM tournament_players Where tourn_id = ' . $tourn_id . ' order by round_no, draw_pos'; 
echo("SQL Original " . $query_tourn_orig . "<br>");
$player_index = 0;
$result_tourn_orig = mysql_query($query_tourn_orig, $connvbsa) or die(mysql_error());
$tourn_size = $result_tourn_orig->num_rows;
//echo("Tourn Size " . $tourn_size . "<br>");
while($build_table = $result_tourn_orig->fetch_assoc())
{
    if($build_table['round_no'] == 1)
    {
        $R = 128;
    }
    else if($build_table['round_no'] == 2)
    {
        $R = 64;
    }
    else if($build_table['round_no'] == 3)
    {
        $R = 32;
    }
    $sql_update = "Update tournament_scores Set r_" . $R . "_position = " . $build_table['draw_pos'] . ", ranknum = " . $build_table['ranknum'] . " Where tourn_id = " . $tourn_id . " and member_id = " . $build_table['memb_id'];
    echo($sql_update . "<br>");
    $update = mysql_query($sql_update, $connvbsa) or die(mysql_error());  
    $player_index++;       
}

//echo("Player Count " . $count_round_1 . "<br>");
//echo("Total Count " . $tourn_size . "<br>");
//for($i < $count_round_1; $i > $tourn_size; $i++)
//{
//    echo("Add bye " . $i . "<br>");
//}
/*
// update tournament draw if tournament_players is not empty
    $query_draw_table = 'Select * FROM tournament_players where tourn_id = ' . $tourn_id . ' Order by id';
    //echo($query_draw_table . "<br>");
    $result_draw_table = mysql_query($query_draw_table, $connvbsa) or die(mysql_error());
    $total_draw_table = $result_draw_table->num_rows;
    if($total_draw_table > 0)
    {
        $sql_draw_pos = 'Update tournament_scores SET r_128_position = 0, r_64_position = 0, r_32_position = 0, r_16_position = 0, r_8_position = 0, r_4_position = 0, r_2_position = 0 where tourn_id = ' . $tourn_id;
        //echo($sql_draw_pos . "<br>");
        $update = mysql_query($sql_draw_pos, $connvbsa) or die(mysql_error());
        while($build_draw_table = $result_draw_table->fetch_assoc())
        {
            if($build_draw_table['round_no'] == 1)
            {
                $sql_draw = 'Update tournament_scores SET r_128_position = ' . $build_draw_table['draw_pos'] . ' where member_id = ' . $build_draw_table['memb_id'] . ' and tourn_id = ' . $tourn_id;
                echo("(1) " . $sql_draw . "<br>");
                $update = mysql_query($sql_draw, $connvbsa) or die(mysql_error());  
            }
            else if($build_draw_table['round_no'] == 2) 
            {
                $sql_draw_2 = 'Update tournament_scores SET r_64_position = ' . $build_draw_table['draw_pos'] . ' where member_id = ' . $build_draw_table['memb_id'] . ' and tourn_id = ' . $tourn_id;
                echo("(2) " . $sql_draw_2 . "<br>");
                $update = mysql_query($sql_draw_2, $connvbsa) or die(mysql_error());   
            }
            else if($build_draw_table['round_no'] == 3) 
            {
                $sql_draw_3 = 'Update tournament_scores SET r_32_position = ' . $build_draw_table['draw_pos'] . ' where member_id = ' . $build_draw_table['memb_id'] . ' and tourn_id = ' . $tourn_id;
                echo("(3) " . $sql_draw_3 . "<br>");
                $update = mysql_query($sql_draw_3, $connvbsa) or die(mysql_error());   
            }
        }
    }
*/


echo("<center>Data Added</center><br>");
echo('<div align="center" class="greenbg"><a href="tournament_draw.php?tourn_id=' . $tourn_id . '" style="width: 300px;">Return to Tournament Draw</a></div>');
//echo('<div align="center" class="greenbg"><a href="create_draw_order.php?tourn_id=' . $tourn_id . '" style="width: 300px;">Return to Tournament Draw</a></div>');
echo("<br>");



?>