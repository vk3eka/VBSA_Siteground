<?php 
include("connection.inc");
include("header.php"); 
include("php_functions.php");

/** Include PHPSpreadsheet */
require_once(__DIR__ . '/vendor/autoload.php');

function ImportExcel($filename) 
{
    global $dbcnx_client;
   
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
    $inputFileType = "Xlsx";
    $inputFileName = $filename; 
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    $spreadsheet = $reader->load($inputFileName);
    $sheet = $spreadsheet->getActiveSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestDataColumn();
    $rowData = $sheet->rangeToArray("A1:" . $highestColumn . $highestRow, NULL, TRUE, FALSE);
    for ($row = 1; $row < $highestRow; $row++) 
    {
        //echo("Range - " . "A" . $row . ":" . $highestColumn . $row . "<br>");
        //echo("Row - " . $row . "<br>");
        //  Read a row of data into an array
        //$rowData = $sheet->rangeToArray("A" . $row . ":" . $highestColumn . $row);
        //$rowData = $sheet->rangeToArray('A1:H51');

        $sql_select = "Select * FROM tbl_authorise WHERE PlayerNo = '" . $rowData[$row][7] . "'";
  		$result_select = $dbcnx_client->query($sql_select);
  		$num_rows = $result_select->num_rows;
  		if($num_rows == 0)
  		{
			$sql = "Insert INTO tbl_authorise (Name, Email, Team_1, Team_2, Team_3, Access, PlayerNo, Password, Active, BulkEmail) Values ('" . $rowData[$row][1] . " " . $rowData[$row][0] . "', '" . $rowData[$row][2] . "', '" . $rowData[$row][3] ."', '" . $rowData[$row][4] . "', '" . $rowData[$row][5] . "', '" . $rowData[$row][6] . "', " . $rowData[$row][7]  . ", 'Not Yet Allocated', 0, 1)";
        	echo("Insert " . $sql . "<br>");
  		}
  		else
  		{
  			$sql = "Update tbl_authorise Set Team_1 = '" . trim($rowData[$row][3]) . "', Team_2 = '" . trim($rowData[$row][4]) . "', Team_3 = '" . trim($rowData[$row][5]) . "' Where PlayerNo = " . $rowData[$row][7];
        	echo("Update " . $sql . "<br>");
  		}
        $update = $dbcnx_client->query($sql);
        if(!$update )
        {
            die("Could not update data: " . mysqli_error($dbcnx_client));
        }
    }
    header("Location: authorise.php");
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
            ImportExcel($_FILES['excel_file']['tmp_name']);
            echo('<br><br><center>Your file '.$_FILES['excel_file']['name'] . " has been uploaded and imported.</center><br>");
        }
    }
    else
    {
        echo('Your upload triggered the following error:  '.$_FILES['excel_file']['error'] . "<br>");
    }
}
else
{
    echo("<form action='upload_bulk_list.php' method='post' enctype='multipart/form-data'>");
    echo("<center>");
    echo("<table border='0' align='center' cellpadding='0' cellspacing='0'>");
    echo("<tr>");
    echo("<td align=center><h2>Bulk Upload Team Captains</h2></td>");
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
    echo("<td align=center>Please Note: Duplicate Team Captains are not checked.</td>");
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
    echo("<td align=center>You can download a template file <a href='Import_Captains_Template.xlsx'>here.</a></td>");
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