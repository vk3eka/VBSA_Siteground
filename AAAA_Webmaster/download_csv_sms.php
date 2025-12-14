<?php
error_reporting(0);
require_once('../Connections/connvbsa.php');

if (isset($_GET['page_from'])) 
{
    $page_from = $_GET['page_from'];
}

function prepare_data($sql, $filename) 
{
    global $database_connvbsa, $connvbsa, $tournament_id;
    mysql_select_db($database_connvbsa, $connvbsa) or die("Reporting!");
    $result = mysql_query($sql) or die("Error 101 :: ".mysql_error());
    /*
    $csv_array = array();

    while($row = mysql_fetch_array($run)) 
    {
        $array_to_put = array();
        $array_to_put[] = $row['MobilePhone'];
        $array_to_put[] = $row['LastName'];
        $array_to_put[] = $row['FirstName'];
        $csv_array[]    = $array_to_put;
    }
    return $csv_array;
    */

    $headers = $result->fetch_fields();
    foreach($headers as $header) {
        $head[] = $header->name;
    }
    $fp = fopen('php://output', 'w');
    if ($fp && $result) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        fputcsv($fp, array_values($head)); 
        while ($row = $result->fetch_array(MYSQLI_NUM)) 
        {
            fputcsv($fp, array_values($row));
        }
        fclose($fp);
    }
}

function write_to_csv($sql, $filename) 
{
    $csv_array = prepare_data($sql, $filename);
    $fp = fopen($filename, "a");
    foreach($csv_array as $array_to_put) {
        fputcsv($fp, $array_to_put);
    }
    fclose($fp);
}
/*
function copy_csv($filename) 
{
    $file    = 'csv_files/sample_csv.csv';
    $newfile = $filename;

    if (!copy($file, $newfile)) {
        echo "failed to copy $file...\n";
    }
}
*/

if($page_from == 'vbsa_members')
{
    $sql = "Select MobilePhone, FirstName, LastName FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR affiliate_player=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR affiliate_player = 1 OR hon_memb = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveSMS = 1 AND MobilePhone != '')";
    $filename = 'All_Members_' . date("M_Y") . ".csv";
}
else if($page_from == 'vbsa_Captains_S1')
{
    $sql = "Select  MobilePhone, FirstName, LastName  FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE (captain_scrs=1 OR authoriser_scrs=1) AND team_cal_year = YEAR( CURDATE( ) ) AND scr_season='S1' AND MobilePhone != '' ORDER BY Team_entries.team_grade, Team_entries.team_club";

    $filename = 'Captains_Authorisers_S1_' . date("M_Y") . ".csv";
}
else if($page_from == 'vbsa_Captains_S2')
{
    $sql = "Select  MobilePhone, FirstName, LastName  FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE (captain_scrs=1 OR authoriser_scrs=1) AND team_cal_year = YEAR( CURDATE( ) ) AND scr_season='S2' AND MobilePhone != '' ORDER BY Team_entries.team_grade, Team_entries.team_club";

    $filename = 'Captains_Authorisers_S2_' . date("M_Y") . ".csv";
}
else if($page_from == 'vbsa_affiliates')
{
    $sql = "Select  MobilePhone, FirstName, LastName FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE affiliate_player = 1 AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveSMS = 1 AND MobilePhone != '')";
    $filename = 'Affiliate_Members_' . date("M_Y") . ".csv";
}

//copy_csv($filename);
write_to_csv($sql, $filename);

header("Content-disposition: attachment; filename=" . $filename);
header("Content-type: application/pdf");
readfile($filename);

?>
