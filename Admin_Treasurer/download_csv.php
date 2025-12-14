<?php
error_reporting(E_ALL);
require_once('../Connections/connvbsa.php');


if (isset($_GET['id'])) {
    $tournament_id = $_GET['id'];
} else {
    exit("Error!");
}

$file_name = "csv_files/tourn_details-".$tournament_id.".csv";

function copy_csv() {
    global $file_name;
    $file    = 'csv_files/sample_csv.csv';
    $newfile = $file_name;

    if (!copy($file, $newfile)) {
        echo "failed to copy $file...\n";
    }
}

function prepare_data() {

    global $database_connvbsa, $connvbsa, $tournament_id;

    mysql_select_db($database_connvbsa, $connvbsa) or die("Reporting!");
    $sql = sprintf("
                            SELECT
                                members.MemberID,
                                members.LastName,
                                members.FirstName,
                                members.Email,
                                members.MobilePhone,
                                tourn_entry.rank_pts,
                                tourn_entry.ID,
                                tourn_entry.tourn_memb_id,
                                tourn_entry.tournament_number,
                                tourn_entry.amount_entry,
                                tourn_entry.entered_by,
                                tourn_entry.how_paid,
                                tourn_entry.seed,
                                tourn_entry.junior_cat,
                                tourn_entry.tourn_date_ent,
                                tourn_entry.ranked,
                                tourn_entry.wcard

                            FROM
                                tourn_entry,
                                members
                            WHERE
                                tournament_number = %s AND
                                members.MemberID = tourn_memb_id
                            ORDER BY
                                tourn_entry.junior_cat,
                                members.FirstName,
                                members.LastName",
        $tournament_id);
    $run = mysql_query($sql) or die("Error 101 :: ".mysql_error());

    $csv_array = array();

    while($row = mysql_fetch_array($run)) {
        $array_to_put = array();
        $array_to_put[] = $row['MemberID'];
        $array_to_put[] = $row['LastName'];
        $array_to_put[] = $row['FirstName'];
        $array_to_put[] = $row['Email'];
        $array_to_put[] = $row['MobilePhone'];
        $array_to_put[] = $row['ID'];
        $array_to_put[] = $row['amount_entry'];
        $array_to_put[] = $row['how_paid'];
        $array_to_put[] = $row['junior_cat'];
        $array_to_put[] = $row['ranked'];
        $array_to_put[] = $row['wcard'];
        $array_to_put[] = $row['seed'];
        $array_to_put[] = $row['tourn_date_ent'];
        $csv_array[]    = $array_to_put;
    }

    return $csv_array;
}

function write_to_csv() {

    global $file_name;

    $csv_array = prepare_data();
    $fp = fopen($file_name, "a");
    foreach($csv_array as $array_to_put) {
        fputcsv($fp, $array_to_put);
        //print_r($array_to_put);
        //echo "<br/><br/>";
    }
    fclose($fp);

}
copy_csv();
write_to_csv();



header("Content-disposition: attachment; filename=".$file_name);
header("Content-type: application/pdf");
readfile($file_name);

?>