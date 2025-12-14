<?php 
if (!isset($_SESSION)) 
{
  session_start();
}
include("header.php"); 
include("connection.inc");

$current_year = $_SESSION['year'];
$season = $_SESSION['season'];

/** Include PHPSpreadsheet */
require_once(__DIR__ . '/vendor/autoload.php');

function ImportExcel($filename, $current_year, $season) 
{
    global $dbcnx_client;
   
    // check if any data for this season exists
    $sql = "Select season From tbl_test_fixtures Where season = '" . $season . "' and year = " . $current_year;
    $result_fixture_season = $dbcnx_client->query($sql) or die("Couldn't execute season check. " . mysqli_error($dbcnx_client)); 
    $num_rows = $result_fixture_season->num_rows;
    $data_exists = false;
    if($num_rows > 0)
    {
        $data_exists = true;
    } 
    if($data_exists)
    {
        $sql_delete = "Delete from tbl_test_fixtures Where season = '" . $season . "' and year = " . $current_year;
        $update_delete = $dbcnx_client->query($sql_delete);
        if(! $update_delete )
        {
          die("Could not delete season data: " . mysqli_error($dbcnx_client));
        }
    }
    echo($sql . "<br>");
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
    $inputFileType = "Xlsx";
    $inputFileName = $filename; 
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    $spreadsheet = $reader->load($inputFileName);
    $sheet = $spreadsheet->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestDataColumn();
    //echo("Row - " . $highestRow . ", Col - " . $highestColumn . "<br>");
    $rowData = $sheet->rangeToArray("A1:" . $highestColumn . $highestRow, NULL, TRUE, FALSE);
    for ($row = 1; $row < $highestRow; $row++) {
        //  Read a row of data into an array
        $unix_date = ($rowData[$row][0] - 25569) * 86400;
        $sql = "Insert INTO tbl_test_fixtures (date, type, grade, round, fix1home, fix1away, fix2home, fix2away, fix3home, fix3away, fix4home, fix4away, fix5home, fix5away, fix6home, fix6away, year, season, team_grade, dayplayed) Values ('" . date('Y-m-d', $unix_date) . "', '" . $rowData[$row][1] ."', '" . $rowData[$row][2] . "', " . $rowData[$row][3] .", '" . $rowData[$row][4] . "', '" . $rowData[$row][5] . "', '" . $rowData[$row][6] . "', '" . $rowData[$row][7] . "', '" . $rowData[$row][8] . "', '" . $rowData[$row][9] . "', '" . $rowData[$row][10] . "', '" . $rowData[$row][11] . "', '" . $rowData[$row][12] . "', '" . $rowData[$row][13] . "', '" . $rowData[$row][14] . "', '" . $rowData[$row][15] . "', " . $rowData[$row][16] . ", '" . $rowData[$row][17] . "', '" . $rowData[$row][18] . "', '" . $rowData[$row][19] . "')";
        echo($sql . "<br>");
        //$update = $dbcnx_client->query($sql);
        //if(!$update )
        //{
        //    die("Could not update data: " . mysqli_error($dbcnx_client));
        //} 
    }

    header("Location: select_fixtures.php");
}

//if they DID upload a file...
if($_FILES['excel_file']['name'])
{
    if(!$_FILES['excel_file']['error'])
    {
        $new_file_name = strtolower($_FILES['excel_file']['tmp_name']); //rename file
        if($_FILES['excel_file']['size'] > (1024000)) //can't be larger than 1 MB
        {
            $valid_file = false;
            echo('Your file\'s size is too large.' . "<br>");
        }
        else
        {
            $valid_file = true;
        }

        if($valid_file)
        {
            $sql = "Select score_1, season, year From tbl_scoresheet Where season = '" . $season . "' and year = " . $current_year;
            $result_fixture_season = $dbcnx_client->query($sql) or die("Couldn't execute season check. " . mysqli_error($dbcnx_client));  
            $row_count = $result_fixture_season->num_rows;
            $score_data_exists = false;
            while($score_data = $result_fixture_season->fetch_assoc())
            {
                if($score_data['score_1'] != '')
                {
                    $score_data_exists = true;
                    break;
                }
            }
            $score_data_exists = false; // temp setting until in production
            if($score_data_exists === true)
            {
                echo("<center>");
                echo("<table border='0' align='center' cellpadding='0' cellspacing='0'>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>The fixture list already contains scoring data.</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>The fixture list cannot be changed.</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("</table>");
                echo("</center>");
            }
            else
            {
                echo("File name ". $_FILES['excel_file']['tmp_name'] . ", Year " . $current_year . ", Season " . $season . "<br>");
                ImportExcel($_FILES['excel_file']['tmp_name'], $current_year, $season);
                echo('<br><br><center>Your file '. $_FILES['excel_file']['name'] . " has been uploaded and imported.</center><br>");
            }
        }
    }
    else
    {
        echo('Your upload triggered the following error:  '. $_FILES['excel_file']['error'] . "<br>");
    }
}
else
{
    echo("<form action='test_create_upload.php' method='post' enctype='multipart/form-data'>");
    echo("<center>");
    echo("<table border='0' align='center' cellpadding='0' cellspacing='0'>");
    echo("<tr>");
    echo("<td align=center><h2>Upload Fixtures List (Test)</h2></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>Select the Excel File to upload:</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><input type='file' name='excel_file' size='25' /></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><input type='submit' name='submit' value='Upload' /></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><b><font color=red>Please note: any existing fixture data for season " . $season . ", in " . $current_year . " will be deleted.</b></font></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>Please ensure the upload file contains the whole seasons fixtures.</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>You can download a template file <a href='Import_Fixtures_Template.xlsx'>here.</a></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("</table>");
    echo("</center>");
    echo("</form>");
}
include("footer.php"); 
?>