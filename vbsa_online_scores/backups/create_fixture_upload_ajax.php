<?php 
include("connection.inc");
include("header.php"); 

$current_year = $_SESSION['year'];
$season = $_SESSION['season'];

?>
<script type='text/javascript'>

$(document).ready(function()
{
    $('#upload_file').click(function(event){
        event.preventDefault();
        $('#uploadFile').modal('show');
        var file = $('#excel_file').val();
        var filearray = file.split("\\");
        var filename = filearray[2];
        var season = '<?= $season ?>';
        var year = <?= $current_year ?>;
        output = "You are about to upload a file (" + filename + "). <br><br>This will delete fixture data for season <b>" + season + ", " + year + "</b>.<br><br>Do you wish to proceed?";
        $($.parseHTML(output)).appendTo('#upload_text');
    });

    $('#proceed').click(function(event){
        event.preventDefault();
        //var file = '<?= $_FILES['excel_file']['tmp_name'] ?>';
        //alert(file);
        //var props = $('input[type=file]').prop('files'),
            //file = props[0];
            //console.log("File " + file.name + ", Size " + file.size);
            //alert(file.name);
            //alert(file.size);
            //alert(file.type);

        //var files = $('#excel_file').val();
        //var files = file.name;
        //console.log ($('input[type=file]').prop('files'))
        //if(files.length > 0 )
        //{
            //alert(window.URL.createObjectURL(files));
        //}
        
        var file = $('#excel_file').val();
        var season = '<?= $season ?>';
        var year = <?= $current_year ?>;
        $.ajax({
          url:"<?= $url ?>/import_excel.php?filename=" + file + "&year=" + year + "&season=" + season,
          success : function(response){
            //alert(response);
            $('#uploadFile').modal('hide');
            location.href = "select_fixtures.php";
          }
        });
    });

});

</script>
<?php
/** Include PHPSpreadsheet */
//require_once(__DIR__ . '/vendor/autoload.php');

function ImportExcel($filename, $current_year, $season) 
{
    global $dbcnx_client;
   
    // check if any data for this season exists
    $sql = "Select season From tbl_fixtures Where season = '" . $season . "' and year = " . $current_year;
    $result_fixture_season = $dbcnx_client->query($sql) or die("Couldn't execute season check. " . mysqli_error($dbcnx_client)); 
    $num_rows = $result_fixture_season->num_rows;
    $data_exists = false;
    //echo($sql . "<br>");
    if($num_rows > 0)
    {
        //$data_exists = true;
        //echo "<script type='text/javascript'>";
        //echo "alert('Data Exists')";
        //echo "</script>";
    } 

    //$data_exists = false;
    if($data_exists)
    {
        $sql_delete = "Delete from tbl_fixtures Where season = '" . $season . "' and year = " . $current_year;
        //$update_delete = $dbcnx_client->query($sql_delete);
        if(! $update_delete )
        {
          die("Could not delete season data: " . mysqli_error($dbcnx_client));
        }
    }
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
    //$rowData = $sheet->rangeToArray('A1:T111');
    for ($row = 1; $row < $highestRow; $row++) {
        //  Read a row of data into an array
        //$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
        $unix_date = ($rowData[$row][0] - 25569) * 86400;
        $sql = "Insert INTO tbl_fixtures (date, type, grade, round, fix1home, fix1away, fix2home, fix2away, fix3home, fix3away, fix4home, fix4away, fix5home, fix5away, fix6home, fix6away, year, season, team_grade, dayplayed) Values ('" . date('Y-m-d', $unix_date) . "', '" . $rowData[$row][1] ."', '" . $rowData[$row][2] . "', " . $rowData[$row][3] .", '" . $rowData[$row][4] . "', '" . $rowData[$row][5] . "', '" . $rowData[$row][6] . "', '" . $rowData[$row][7] . "', '" . $rowData[$row][8] . "', '" . $rowData[$row][9] . "', '" . $rowData[$row][10] . "', '" . $rowData[$row][11] . "', '" . $rowData[$row][12] . "', '" . $rowData[$row][13] . "', '" . $rowData[$row][14] . "', '" . $rowData[$row][15] . "', " . $rowData[$row][16] . ", '" . $rowData[$row][17] . "', '" . $rowData[$row][18] . "', '" . $rowData[$row][19] . "')";
        //echo($sql . "<br>");
        $update = $dbcnx_client->query($sql);
        if(!$update )
        {
            die("Could not update data: " . mysqli_error($dbcnx_client));
        } 
    }
    //echo("File After " . $filename . "<br>");
    header("Location: select_fixtures.php");
}

//if they DID upload a file...
if($_FILES['excel_file']['name'])
{
    echo("After Upload<br>");
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
            //echo($sql);
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
            if($score_data_exists)
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
                ImportExcel($_FILES['excel_file']['tmp_name'], $current_year, $season);
                echo('<br><br><center>Your file '.$_FILES['excel_file']['name'] . " has been uploaded and imported.</center><br>");
            }
        }
    }
    else
    {
        echo('Your upload triggered the following error:  '.$_FILES['excel_file']['error'] . "<br>");
    }
}
else
{
    echo("<form action='create_fixture_upload.php' id='uploadfile' method='post' enctype='multipart/form-data'>");
    echo("<center>");
    echo("<table border='0' align='center' cellpadding='0' cellspacing='0'>");
    echo("<tr>");
    echo("<td align=center><h2>Upload Fixtures List</h2></td>");
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
    echo("<td align=center><input type='file' name='excel_file' id='excel_file' size='75' /></td>");
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
    echo("<td align=center><input type='button' name='submit' id='upload_file' value='Upload' /></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>Please note: any existing fixture data will be deleted.</td>");
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
?>

<!-- File Upload Modal -->
<div class="modal fade" id="uploadFile" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">File Upload</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class='text-center' id='upload_text'></div>
        <br>
        
        <div class='text-center'><a class='btn btn-primary btn-xs' id='proceed'>Yes</a>&nbsp;<a class='btn btn-primary btn-xs' data-dismiss="modal">No</a></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php
include("footer.php"); 
?>