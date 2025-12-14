<?php 
include("connection.inc");
include("header.php"); 

$current_year = $_GET['year'];
$season = $_GET['season'];
$filename = $_GET['filename'];

//$current_year = 2023;
//$season = 'S2';
$filename = "/Users/peterj/Google Drive/VBSA_Stuff/Spreadsheet/NR_Exhibition_Contacts.xlsx";

echo("Year " . $current_year . "<br>");
echo("Season " . $season . "<br>");
echo("File " . $filename . "<br>");

echo("Dir " . __DIR__ . "<br>");

/** Include PHPSpreadsheet */
require_once(__DIR__ . '/vendor/autoload.php');

function ImportExcel($filename, $current_year, $season) 
{
    global $dbcnx_client;
    // check if any data for this season exists
    /*
    $sql = "Select season From tbl_fixtures Where season = '" . $season . "' and year = " . $current_year;
    $result_fixture_season = $dbcnx_client->query($sql) or die("Couldn't execute season check. " . mysqli_error($dbcnx_client)); 
    $num_rows = $result_fixture_season->num_rows;
    $data_exists = false;
    if($num_rows > 0)
    {
       $data_exists = true;
    } 
    if($data_exists)
    {
        $sql_delete = "Delete from tbl_fixtures Where season = '" . $season . "' and year = " . $current_year;
        $update_delete = $dbcnx_client->query($sql_delete);
    }
    */
    echo("File Before " . $filename . "<br>");
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
    echo("Spreadsheet " . $spreadsheet . "<br>");
    $inputFileType = "xlsx";
    $inputFileName = $filename; 
    echo("File After " . $inputFileName . "<br>");
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    $spreadsheet = $reader->load($inputFileName);
    $sheet = $spreadsheet->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestDataColumn();
    echo("Row - " . $highestRow . ", Col - " . $highestColumn . "<br>");
    $rowData = $sheet->rangeToArray("A1:" . $highestColumn . $highestRow, NULL, TRUE, FALSE);
    for ($row = 1; $row < $highestRow; $row++) {
        //  Read a row of data into an array
        $unix_date = ($rowData[$row][0] - 25569) * 86400;
        $sql = "Insert INTO members (FirstName, LastName, Email, MobilePhone) Values ('" . $rowData[$row][1] ."', '" . $rowData[$row][2] . "', " . $rowData[$row][3] .", '" . $rowData[$row][4] . "')";
        echo($sql . "<br>");
        //$update = $dbcnx_client->query($sql);
       
        if(!$update )
        {
            die("Could not update data: " . mysqli_error($dbcnx_client));
            echo("Data Not Imported!");
        } 
        else
        {
            echo("Data Imported!");
        }
        
    }
}

ImportExcel($filename, $current_year, $season);


//echo("Data Deleted!");
?>

