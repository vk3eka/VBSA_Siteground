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
								tournament_number AS TournID,
								MemberID,
								CONCAT(FirstName, ' ', LastName) AS FullName,
								members.Email,
								members.MobilePhone,
								amount_entry,
								how_paid,
								entry_confirmed,
								(CASE
        							WHEN memb_by IS NOT NULL then 'Yes'
        							WHEN memb_by IS NULL then 'No'
    							end)  AS memb,
								seed,
								tourn_date_ent,
								ranked,
								wcard,
								rank_pts,
								ranknum,
								Junior
						
							FROM
									tourn_entry, members
							
							LEFT JOIN rank_S_open_tourn ON memb_id=MemberID		    
														
							WHERE
								tournament_number = %s AND
                                MemberID=tourn_memb_id
								AND entry_confirmed=1
														
							ORDER BY
									FirstName,
									Junior",
        $tournament_id);
    $run = mysql_query($sql) or die("Error 101 :: ".mysql_error());
	

    $csv_array = array();

    while($row = mysql_fetch_array($run)) {
        $array_to_put = array();
		$array_to_put[] = $row['TournID'];
        $array_to_put[] = $row['MemberID'];
        $array_to_put[] = $row['FullName'];
        $array_to_put[] = $row['Email'];
        $array_to_put[] = $row['MobilePhone'];
        $array_to_put[] = $row['amount_entry'];
        $array_to_put[] = $row['how_paid'];
		$array_to_put[] = $row['entry_confirmed'];
        $array_to_put[] = $row['memb'];
        $array_to_put[] = $row['seed']; 
        $array_to_put[] = $row['tourn_date_ent'];
        $array_to_put[] = $row['ranked'];
        $array_to_put[] = $row['wcard'];
		$array_to_put[] = $row['rank_pts'];
		$array_to_put[] = $row['ranknum'];
		$array_to_put[] = $row['Junior'];
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