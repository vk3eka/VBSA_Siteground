<?php
error_reporting(E_ALL);
require_once('../Connections/connvbsa.php');


if (isset($_GET['id'])) {
    $FileName = $_GET['id'];
	} else {
	  exit("Error in id!");
	}

$file_name = $FileName.".csv";



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
								members.MobilePhone AS Mobile, members.LastName, members.FirstName
							FROM 
								members
								
							WHERE 
								Junior!='na' 
								AND MobilePhone is not null
								AND ReceiveSMS=1
								
							ORDER BY 
								LastName, FirstName");  
   
    $run = mysql_query($sql) or die("Error 101 :: ".mysql_error());

    $csv_array = array();

    while($row = mysql_fetch_array($run)) {
        $array_to_put = array();
        $array_to_put[] = $row['Mobile'];
        $array_to_put[] = $row['LastName'];
		$array_to_put[] = $row['FirstName'];
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
